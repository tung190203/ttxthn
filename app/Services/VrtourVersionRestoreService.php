<?php

namespace App\Services;

use App\Models\Panorama;
use App\Models\Project;
use App\Models\User;
use App\Models\VrtourVersion;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

// Dịch vụ khôi phục phiên bản panorama của dự án
class VrtourVersionRestoreService
{
    private const TOKEN_TTL = 300; // giây

    public function __construct(private VrtourVersionDiffService $diffService) {}

    /** Bước 1: kiểm tra, lưu token, trả về tóm tắt tác động */
    public function prepare(VrtourVersion $source, User $user, string $reason): array
    {
        $latest = VrtourVersion::where('vrtour_id', $source->vrtour_id)->orderByDesc('version_number')->firstOrFail();

        if ($latest->id === $source->id) {
            throw ValidationException::withMessages([
                'version_id' => 'Đây đã là phiên bản mới nhất, không cần khôi phục.',
            ]);
        }

        $token = (string) Str::uuid();

        Cache::put($this->key($token), [
            'user_id'    => $user->id,
            'vrtour_id'  => $source->vrtour_id,
            'version_id' => $source->id,
            'reason'     => $reason,
        ], now()->addSeconds(self::TOKEN_TTL));

        // So bản mới nhất (hiện tại) với bản nguồn để biết khôi phục sẽ thay đổi gì
        $summary = $this->diffService->diff($latest, $source, 'panorama')['summary'];

        return [
            'token'   => $token,
            'expires' => self::TOKEN_TTL,
            'source'  => ['number' => $source->version_number, 'at' => $source->created_at?->format('d/m/Y H:i')],
            'latest'  => ['number' => $latest->version_number],
            'summary' => [
                'restored_back' => $summary[VrtourVersionDiffService::STATUS_ADDED],    // có ở bản nguồn, đang thiếu
                'will_delete'   => $summary[VrtourVersionDiffService::STATUS_REMOVED],  // đang có, không có ở bản nguồn
                'will_revert'   => $summary[VrtourVersionDiffService::STATUS_MODIFIED],
                'unchanged'     => $summary[VrtourVersionDiffService::STATUS_UNCHANGED],
            ],
            'retention' => app(VrtourVersionRetentionService::class)->status($source->vrtour_id),
        ];
    }

    /** Bước 2: thực thi khôi phục */
    public function execute(string $token, User $user): VrtourVersion
    {
        // pull = lấy rồi xóa luôn, token chỉ dùng được một lần
        $payload = Cache::pull($this->key($token));
        if (!$payload || (int) $payload['user_id'] !== (int) $user->id) {
            throw ValidationException::withMessages([
                'token' => 'Phiên xác nhận đã hết hạn hoặc không hợp lệ. Vui lòng thực hiện lại từ bước 1.',
            ]);
        }

        $vrtourId = (int) $payload['vrtour_id'];
        $version = DB::transaction(function () use ($payload, $user, $vrtourId) {
            Project::whereKey($vrtourId)->lockForUpdate()->firstOrFail();

            $source = VrtourVersion::where('vrtour_id', $vrtourId)->findOrFail($payload['version_id']);

            $items   = $source->items()->where('entity_type', 'panorama')->get();
            $keepIds = $items->pluck('entity_key')->map(fn($k) => (int) $k)->all();

            // 1. Xóa panorama đã xuất bản mà bản nguồn không có (bản nháp không đụng tới)
            Panorama::where('vrtour_id', $vrtourId)
                ->where('is_draft', false)
                ->whereNotIn('id', $keepIds)
                ->get()
                ->each->delete();

            // 2. Ghi lại nội dung của bản nguồn, giữ nguyên ID
            foreach ($items as $item) {
                $panorama = Panorama::find((int) $item->entity_key) ?? new Panorama();

                $panorama->forceFill($item->data);
                $panorama->id        = (int) $item->entity_key;
                $panorama->vrtour_id = $vrtourId;
                $panorama->save();
            }

            // 3. Tạo version mới (không ghi đè lịch sử), luôn tạo dù dữ liệu không đổi
            return VrtourVersion::createPanoramaSnapshot($vrtourId, $user->id, [
                'version_type'      => VrtourVersion::TYPE_RESTORE,
                'source_version_id' => $source->id,
                'restore_reason'    => $payload['reason'],
                'confirmed_by'      => $user->id,
                'confirmed_at'      => now(),
                'change_summary'    => 'Khôi phục từ phiên bản v' . $source->version_number,
            ], false);
        });

        // Ghi pano.js sau khi DB đã commit (lỗi ghi file chỉ ghi log, không làm hỏng khôi phục)
        Project::query()->find($vrtourId)?->syncPanoFile();

        return $version;
    }

    private function key(string $token): string
    {
        return 'vrtour_restore:' . $token;
    }
}
