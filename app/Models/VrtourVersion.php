<?php

namespace App\Models;

use App\Services\VrtourVersionRetentionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VrtourVersion extends Model
{
    public const TYPE_PUBLISH = 'publish';
    public const TYPE_RESTORE = 'restore';

    public $timestamps = false;

    protected $fillable = [
        'vrtour_id',
        'version_number',
        'version_type',
        'source_version_id',
        'created_by',
        'created_at',
        'change_summary',
        'restore_reason',
        'confirmed_by',
        'confirmed_at',
    ];

    protected $casts = [
        'created_at'   => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(VrtourVersionItem::class, 'version_id');
    }

    public function sourceVersion(): BelongsTo
    {
        return $this->belongsTo(self::class, 'source_version_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function confirmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    /**
     * Tạo snapshot panorama.
     * - Không có thay đổi so với bản mới nhất: trả về bản mới nhất, kèm cờ snapshot_skipped.
     * - Vượt trần: tạo bản mới rồi xóa các bản cũ nhất, kèm danh sách pruned.
     * Hai cờ này chỉ gắn tạm lên object, không lưu DB.
     */
    public static function createPanoramaSnapshot(
        int $vrtourId,
        int $userId,
        array $attributes = [],
        bool $skipIfUnchanged = true
    ): self {
        return DB::transaction(function () use ($vrtourId, $userId, $attributes, $skipIfUnchanged) {
            // Khóa dòng cha: mọi kiểm tra bên dưới đều tuần tự theo từng tour
            Project::whereKey($vrtourId)->lockForUpdate()->firstOrFail();

            // Dữ liệu hiện tại: entity_key => [json, hash]
            $current = [];
            Panorama::where('vrtour_id', $vrtourId)
                ->where('is_draft', false)->orderBy('id')
                ->each(function ($p) use (&$current) {
                    $data = $p->only(VrtourVersionItem::PANORAMA_FIELDS);
                    $current[(string) $p->getKey()] = [
                        'json' => json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'hash' => VrtourVersionItem::hashData($data),
                    ];
                });

            $latest = self::where('vrtour_id', $vrtourId)->orderByDesc('version_number')->first();

            // Bỏ qua nếu không có thay đổi
            if ($skipIfUnchanged && $latest) {
                $latestHashes = $latest->items()->where('entity_type', 'panorama')->pluck('content_hash', 'entity_key')->all();
                $currentHashes = array_map(fn($r) => $r['hash'], $current);
                ksort($latestHashes);
                ksort($currentHashes);

                if ($latestHashes === $currentHashes) {
                    $latest->setAttribute('snapshot_skipped', true);
                    return $latest;
                }
            }

            $version = self::create(array_merge([
                'vrtour_id'      => $vrtourId,
                'version_number' => (int) self::where('vrtour_id', $vrtourId)->max('version_number') + 1,
                'version_type'   => self::TYPE_PUBLISH,
                'created_by'     => $userId,
                'created_at'     => now(),
                'change_summary' => 'Lưu phiên bản dữ liệu Panorama',
            ], $attributes));

            $now  = now();
            $rows = [];
            foreach ($current as $key => $r) {
                $rows[] = [
                    'version_id'   => $version->id,
                    'entity_type'  => 'panorama',
                    'entity_key'   => (string) $key,
                    'data'         => $r['json'],
                    'content_hash' => $r['hash'],
                    'created_at'   => $now,
                ];
            }
            foreach (array_chunk($rows, 500) as $chunk) {
                VrtourVersionItem::insert($chunk);
            }

            // Vượt trần thì xóa các bản cũ nhất (tạo bản mới trước nên không bao giờ tụt dưới sàn)
            $excess = self::where('vrtour_id', $vrtourId)->count() - VrtourVersionRetentionService::max();
            $pruned = [];

            if ($excess > 0) {
                $old = self::where('vrtour_id', $vrtourId)
                    ->where('id', '!=', $version->id)
                    ->when(
                        !empty($attributes['source_version_id']),   // giữ bản nguồn của lần khôi phục
                        fn($q) => $q->where('id', '!=', $attributes['source_version_id'])
                    )
                    ->orderBy('version_number')
                    ->limit($excess)
                    ->get(['id', 'version_number', 'created_at']);

                $pruned = $old->map(fn($v) => [
                    'number' => $v->version_number,
                    'at'     => $v->created_at?->format('d/m/Y H:i'),
                ])->all();

                self::whereIn('id', $old->pluck('id'))->delete(); // item xóa theo cascade ở DB

                Log::info('VRTour: xóa phiên bản cũ do vượt giới hạn lưu trữ', [
                    'vrtour_id' => $vrtourId,
                    'by_user'   => $userId,
                    'deleted'   => $pruned,
                ]);
            }

            $version->setAttribute('pruned', $pruned);

            return $version;
        });
    }
}
