<?php

namespace App\Services;

use App\Models\ConnectMap;
use App\Models\Hotspot;
use App\Models\IndustrialProject;
use App\Models\Investor;
use App\Models\Panorama;
use App\Models\Plan;
use App\Models\Project;
use App\Models\SkinApproval;
use App\Models\User;
use App\Models\VrtourVersion;
use App\Models\VrtourVersionItem;
use App\Models\WelcomeScreen;
use App\Supports\HotspotImageColorizer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VrtourVersionRestoreService
{
    private const TOKEN_TTL = 300;
    private const SKIN_MODELS = [
        'welcome'     => [WelcomeScreen::class, 'welcome_screen.js'],
        'connect_map' => [ConnectMap::class,    'connectmap.js'],
        'plan'        => [Plan::class,          'plan.js'],
        'investor'    => [Investor::class,      'investor.js'],
    ];

    public function __construct(private VrtourVersionDiffService $diffService)
    {
    }

    /** Bước 1: kiểm tra, lưu token, trả về tóm tắt tác động */
    public function prepare(VrtourVersion $source, User $user, string $reason): array
    {
        $latest = VrtourVersion::where('vrtour_id', $source->vrtour_id)
            ->where('scope', $source->scope)
            ->orderByDesc('version_number')
            ->firstOrFail();

        if ($latest->id === $source->id) {
            throw ValidationException::withMessages([
                'version_id' => 'Đây đã là phiên bản mới nhất, không cần khôi phục.',
            ]);
        }

        $type    = $source->scope; // entity_type trùng tên scope
        $target  = $this->diffService->stateAt($source, $type);
        $live    = $this->diffService->liveState($source->scope, $source->vrtour_id, $type);
        $summary = $this->diffService->diffStates($live, $target)['summary'];

        $changes = $summary[VrtourVersionDiffService::STATUS_ADDED]
            + $summary[VrtourVersionDiffService::STATUS_REMOVED]
            + $summary[VrtourVersionDiffService::STATUS_MODIFIED];

        if ($changes === 0) {
            throw ValidationException::withMessages([
                'version_id' => 'Dữ liệu hiện tại đã trùng với phiên bản này, không có gì để khôi phục.',
            ]);
        }

        $token = (string) Str::uuid();
        Cache::put($this->key($token), [
            'user_id'    => $user->id,
            'vrtour_id'  => $source->vrtour_id,
            'version_id' => $source->id,
            'reason'     => $reason,
        ], now()->addSeconds(self::TOKEN_TTL));

        return [
            'token'   => $token,
            'expires' => self::TOKEN_TTL,
            'scope'   => $source->scope,
            'source'  => ['number' => $source->version_number, 'at' => $source->created_at?->format('d/m/Y H:i')],
            'latest'  => ['number' => $latest->version_number],
            'summary' => [
                'restored_back' => $summary[VrtourVersionDiffService::STATUS_ADDED],
                'will_delete'   => $summary[VrtourVersionDiffService::STATUS_REMOVED],
                'will_revert'   => $summary[VrtourVersionDiffService::STATUS_MODIFIED],
                'unchanged'     => $summary[VrtourVersionDiffService::STATUS_UNCHANGED],
            ],
            'retention' => app(VrtourVersionRetentionService::class)->status($source->vrtour_id, $source->scope),
            'pending' => $source->scope === VrtourVersion::SCOPE_SKIN ? SkinApproval::where('vrtour_id', $source->vrtour_id)->where('status', 'pending')->count() : 0,
        ];
    }

    /** Bước 2: thực thi khôi phục */
    public function execute(string $token, User $user): VrtourVersion
    {
        $payload = Cache::pull($this->key($token)); // dùng một lần

        if (!$payload || (int) $payload['user_id'] !== (int) $user->id) {
            throw ValidationException::withMessages([
                'token' => 'Phiên xác nhận đã hết hạn hoặc không hợp lệ. Vui lòng thực hiện lại từ bước 1.',
            ]);
        }

        $vrtourId = (int) $payload['vrtour_id'];
        $touched = [];

        $version = DB::transaction(function () use ($payload, $user, $vrtourId, &$touched) {
            Project::whereKey($vrtourId)->lockForUpdate()->firstOrFail();

            $source = VrtourVersion::where('vrtour_id', $vrtourId)->findOrFail($payload['version_id']);
            $type   = $source->scope;

            $target = $this->diffService->stateAt($source, $type);
            $live   = $this->diffService->liveState($type, $vrtourId, $type);

            $attrs = [
                'version_type'      => VrtourVersion::TYPE_RESTORE,
                'source_version_id' => $source->id,
                'restore_reason'    => $payload['reason'],
                'confirmed_by'      => $user->id,
                'confirmed_at'      => now(),
                'change_summary'    => 'Khôi phục từ phiên bản v' . $source->version_number,
            ];

            if ($type === VrtourVersion::SCOPE_SKIN) {
                $touched = $this->restoreSkin($vrtourId, $user->id, $live, $target);

                $changes = [];
                foreach (array_unique(array_merge(array_keys($live), array_keys($target))) as $k) {
                    [$t, $id] = explode('|', $k, 2);
                    $changes[] = ['type' => $t, 'key' => $id, 'before' => $live[$k] ?? null, 'after' => $target[$k] ?? null];
                }

                return VrtourVersion::recordSkin($vrtourId, $user->id, $changes, 'Khôi phục Skin', $attrs);
            }

            $isHot = $type === VrtourVersion::SCOPE_HOTSPOT;
            $after = $isHot
                ? $this->restoreHotspots($vrtourId, $live, $target)
                : $this->restorePanoramas($vrtourId, $live, $target);

            $pairs = [];
            foreach (array_unique(array_merge(array_keys($live), array_keys($after))) as $k) {
                $pairs[] = [$k, $live[$k] ?? null, $after[$k] ?? null];
            }

            return $isHot
                ? VrtourVersion::recordHotspot($vrtourId, $user->id, $pairs, $attrs, true)
                : VrtourVersion::recordPanorama($vrtourId, $user->id, $pairs, $attrs, true);
        });

        // Ghi file sau khi DB đã commit
        try {
            $project = Project::find($vrtourId);
            match ($version->scope) {
                VrtourVersion::SCOPE_HOTSPOT => $project?->syncHotspotFile(),
                VrtourVersion::SCOPE_SKIN    => $project ? $this->writeSkinFiles($project, array_keys($touched)) : null,
                default                      => $project?->syncPanoFile(),
            };
        } catch (\Throwable $e) {
            Log::error('VRTour: ghi file sau khôi phục thất bại', [
                'vrtour_id' => $vrtourId,
                'scope' => $version->scope,
                'error' => $e->getMessage(),
            ]);
        }

        return $version;
    }

    private function restoreSkin(int $vrtourId, int $userId, array $live, array $target): array
    {
        $touched = [];

        foreach (array_unique(array_merge(array_keys($live), array_keys($target))) as $k) {
            $before = $live[$k] ?? null;
            $after  = $target[$k] ?? null;
            if ($before === $after) {
                continue;
            }

            [$type, $id] = explode('|', $k, 2);
            $touched[$type] = true;

            // Vị trí là cột của Project: chỉ ghi lại đúng cột này
            if ($type === 'location') {
                if ($after !== null) {
                    Project::findOrFail($vrtourId)->forceFill($after)->save();
                }
                continue;
            }

            [$class] = self::SKIN_MODELS[$type];

            // Bản nguồn chưa có phần này (tạo sau đó): xóa
            if ($after === null) {
                $class::find((int) $id)?->delete();
                continue;
            }

            $m = $class::find((int) $id) ?? new $class();
            $m->forceFill($after);
            $m->id        = (int) $id;
            $m->vrtour_id = $vrtourId;
            if (!$m->exists) {
                $m->user_id = $userId;
            }
            $m->save();
        }

        return $touched;
    }

    /** Ghi lại các file .js của những phần vừa khôi phục */
    private function writeSkinFiles(Project $project, array $types): void
    {
        $dir = 'vrtour/' . $project->vrtour_code;

        foreach ($types as $type) {
            if ($type === 'location') {
                $file    = 'location.js';
                $content = json_encode(['location' => $project->location_in_tour, 'general' => $project->link]);
            } else {
                [$class, $file] = self::SKIN_MODELS[$type];
                $content = $class::where('vrtour_id', $project->id)->first()?->toJson() ?? 'null';
            }

            createFile($dir, $file);
            file_put_contents($dir . '/' . $file, $content);
        }
    }

    private function restorePanoramas(int $vrtourId, array $live, array $target): array
    {
        foreach (array_unique(array_merge(array_keys($live), array_keys($target))) as $k) {
            $before = $live[$k] ?? null;
            $after  = $target[$k] ?? null;
            if ($before === $after) {
                continue;
            }

            if ($after === null) {
                Panorama::where('parent_id', (int) $k)->get()->each->delete();
                Panorama::find((int) $k)?->delete();
                continue;
            }

            $p = Panorama::find((int) $k) ?? new Panorama();
            $p->forceFill($after);
            $p->id        = (int) $k;
            $p->vrtour_id = $vrtourId;
            $p->save();
        }

        return $target;
    }

    private function restoreHotspots(int $vrtourId, array $live, array $target): array
    {
        $project   = Project::findOrFail($vrtourId);
        $needColor = [];

        foreach (array_unique(array_merge(array_keys($live), array_keys($target))) as $k) {
            $before = $live[$k] ?? null;
            $after  = $target[$k] ?? null;
            if ($before === $after) {
                continue;
            }

            // Bản nguồn không có lô này: xóa lô, bản nháp con, ảnh chờ duyệt và dòng industrial
            if ($after === null) {
                $this->deleteHotspot((int) $k, $vrtourId, $before['potision'] ?? null);
                continue;
            }

            $d        = $after;
            $ipExists = (bool) ($d['ip_exists'] ?? false);
            $ipLink   = $d['ip_link'] ?? null;
            unset($d['ip_exists'], $d['ip_link']);

            $h         = Hotspot::find((int) $k) ?? new Hotspot();
            $isNew     = !$h->exists;
            $oldStatus = $isNew ? null : $h->investment_product_statuses_id;

            $h->forceFill($d);
            $h->id        = (int) $k;
            $h->vrtour_id = $vrtourId;
            $h->save();

            // industrial_projects sinh lại từ hotspot, cùng cách với store()/approve()
            if ($ipExists) {
                IndustrialProject::updateOrCreate(
                    ['project_id' => $vrtourId, 'code' => $h->potision],
                    [
                        'name'         => $project->name,
                        'product_type' => $h->product_type,
                        'intended_use' => $h->intended_use,
                        'unit'         => $h->unit ?? 0,
                        'acreage'      => $h->acreage,
                        'description'  => ['vi' => $h->tooltip, 'en' => $h->tooltip_en],
                        'link'         => $ipLink,
                        'investment_product_statuses_id' => $h->investment_product_statuses_id,
                        'price'               => $h->price,
                        'lease_price'         => $h->lease_price,
                        'sale_price'          => $h->sale_price,
                        'transaction_note'    => $h->transaction_note,
                        'available_amenities' => $h->available_amenities,
                        'contact_info'        => $h->contact_info,
                    ]
                );
            }

            if ($isNew || (int) $oldStatus !== (int) $h->investment_product_statuses_id) {
                $needColor[] = $h;
            }
        }

        // Đổi lại màu ảnh các lô khác trạng thái, TRƯỚC khi ghi version (colorizer ghi lại cột url)
        if ($needColor) {
            try {
                HotspotImageColorizer::apply($needColor);
            } catch (\Throwable $e) {
                Log::error('VRTour: đổi màu ảnh hotspot sau khôi phục thất bại', ['error' => $e->getMessage()]);
            }
        }

        // Trạng thái thực tế sau khi ghi
        return array_map(fn ($d) => VrtourVersionItem::normalize($d), VrtourVersionItem::hotspotStates($vrtourId));
    }

    private function deleteHotspot(int $id, int $vrtourId, ?string $code): void
    {
        $h = Hotspot::find($id);
        if ($h) {
            HotspotImageColorizer::discardPending($h);
            Hotspot::where('parent_id', $id)->get()->each->delete();
            $h->delete();
        }
        if ($code) {
            IndustrialProject::where('project_id', $vrtourId)->where('code', $code)->delete();
        }
    }

    private function key(string $token): string
    {
        return 'vrtour_restore:' . $token;
    }
}