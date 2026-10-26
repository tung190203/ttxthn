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
    public const SCOPE_HOTSPOT = 'hotspot';
    public const SCOPE_SKIN    = 'skin';
    public const SCOPE_PANORAMA = 'panorama';
    public const TYPE_PUBLISH = 'publish';
    public const TYPE_RESTORE = 'restore';
    public const TYPE_RESET = 'reset';

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
        'scope',
        'item_count',
        'added_count',
        'removed_count',
        'modified_count',
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

    /** Ghi version cho panorama. $pairs = [[id, before|null, after|null], ...] */
    public static function recordPanorama(int $vrtourId, int $userId, array $pairs, array $attributes = [], bool $allowEmpty = false): ?self
    {
        $changes = array_map(fn($p) => [
            'type'   => VrtourVersionItem::ENTITY_PANORAMA,
            'key'    => (string) $p[0],
            'before' => $p[1],
            'after'  => $p[2],
        ], $pairs);

        return self::record(self::SCOPE_PANORAMA, $vrtourId, $userId, $changes, $attributes, $allowEmpty);
    }

    public static function recordHotspot(int $vrtourId, int $userId, array $pairs, array $attributes = [], bool $allowEmpty = false): ?self
    {
        $changes = array_map(fn($p) => [
            'type'   => VrtourVersionItem::ENTITY_HOTSPOT,
            'key'    => (string) $p[0],
            'before' => $p[1],
            'after'  => $p[2],
        ], $pairs);

        return self::record(self::SCOPE_HOTSPOT, $vrtourId, $userId, $changes, $attributes, $allowEmpty);
    }

    public static function recordSkin(int $vrtourId, int $userId, array $changes, string $summaryPrefix = 'Cập nhật Skin', array $attributes = []): ?self
    {
        // Bỏ các phần thật sự không đổi
        $changes = array_values(array_filter($changes, function ($c) {
            $b = VrtourVersionItem::normalize($c['before']);
            $a = VrtourVersionItem::normalize($c['after']);

            return $b !== $a;
        }));

        if (empty($changes)) {
            return null;
        }

        $names = [
            'welcome'     => 'Màn hình chào mừng',
            'connect_map' => 'Sơ đồ liên kết vùng',
            'plan'        => 'Kế hoạch triển khai',
            'investor'    => 'Chủ đầu tư',
            'location'    => 'Vị trí',
        ];
        $list = collect($changes)->pluck('type')->unique()
            ->map(fn($t) => $names[$t] ?? $t)->implode(', ');

        return self::record(
            self::SCOPE_SKIN,
            $vrtourId,
            $userId,
            $changes,
            array_merge(['change_summary' => "{$summaryPrefix}: {$list}"], $attributes)
        );
    }

    /**
     * Hàm dùng chung cho mọi scope.
     * $changes = [['type'=>, 'key'=>, 'before'=>array|null, 'after'=>array|null], ...]
     * Trả null nếu không có thay đổi thật (và không $allowEmpty).
     */
    public static function record(string $scope, int $vrtourId, int $userId, array $changes, array $attributes = [], bool $allowEmpty = false): ?self
    {
        // Gộp trùng (cùng type+key): giữ before đầu, after cuối; bỏ mục before == after
        $merged = [];
        foreach ($changes as $c) {
            $k = $c['type'] . '|' . $c['key'];
            if (isset($merged[$k])) {
                $merged[$k]['after'] = $c['after'];
            } else {
                $merged[$k] = $c;
            }
        }

        $rows = [];
        $count = ['added' => 0, 'removed' => 0, 'modified' => 0];
        foreach ($merged as $c) {
            $c['before'] = VrtourVersionItem::normalize($c['before']);
            $c['after']  = VrtourVersionItem::normalize($c['after']);
            $bh = $c['before'] !== null ? VrtourVersionItem::hashData($c['before']) : null;
            $ah = $c['after']  !== null ? VrtourVersionItem::hashData($c['after'])  : null;

            if ($bh === null && $ah === null) continue;
            if ($bh !== null && $bh === $ah) continue;   // không đổi thì không lưu

            $action = $bh === null ? VrtourVersionItem::ACTION_ADDED
                : ($ah === null ? VrtourVersionItem::ACTION_REMOVED : VrtourVersionItem::ACTION_MODIFIED);
            $count[$action]++;

            $rows[] = [
                'entity_type' => $c['type'],
                'entity_key'  => (string) $c['key'],
                'action'      => $action,
                'before_data' => $c['before'] !== null ? json_encode($c['before'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
                'after_data'  => $c['after']  !== null ? json_encode($c['after'],  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
                'before_hash' => $bh,
                'after_hash'  => $ah,
            ];
        }

        if (empty($rows) && !$allowEmpty) {
            return null;
        }

        return DB::transaction(function () use ($scope, $vrtourId, $userId, $attributes, $rows, $count) {
            Project::whereKey($vrtourId)->lockForUpdate()->firstOrFail();

            $version = self::create(array_merge([
                'vrtour_id'      => $vrtourId,
                'version_type'   => self::TYPE_PUBLISH,
                'created_by'     => $userId,
                'created_at'     => now(),
                'change_summary' => 'Cập nhật ' . $scope,
            ], $attributes, [
                'scope'          => $scope,
                'version_number' => (int) self::where('vrtour_id', $vrtourId)->where('scope', $scope)->max('version_number') + 1,
                'item_count'     => count($rows),
                'added_count'    => $count['added'],
                'removed_count'  => $count['removed'],
                'modified_count' => $count['modified'],
            ]));

            $now = now();
            foreach ($rows as &$r) {
                $r['version_id'] = $version->id;
                $r['created_at'] = $now;
            }
            unset($r);
            foreach (array_chunk($rows, 200) as $chunk) {
                VrtourVersionItem::insert($chunk);
            }

            // Vượt trần: xóa bản cũ nhất của CHÍNH scope này
            $excess = self::where('vrtour_id', $vrtourId)->where('scope', $scope)->count()
                - VrtourVersionRetentionService::max();
            $pruned = [];

            if ($excess > 0) {
                $old = self::where('vrtour_id', $vrtourId)->where('scope', $scope)
                    ->where('id', '!=', $version->id)
                    ->when(
                        !empty($attributes['source_version_id']),
                        fn($q) => $q->where('id', '!=', $attributes['source_version_id'])
                    )
                    ->orderBy('version_number')->limit($excess)
                    ->get(['id', 'version_number', 'created_at']);

                $pruned = $old->map(fn($v) => [
                    'number' => $v->version_number,
                    'at'     => $v->created_at?->format('d/m/Y H:i'),
                ])->all();

                self::whereIn('id', $old->pluck('id'))->delete();   // item xóa theo cascade

                Log::info('VRTour: xóa phiên bản cũ do vượt giới hạn lưu trữ', [
                    'vrtour_id' => $vrtourId,
                    'scope'   => $scope,
                    'by_user' => $userId,
                    'deleted' => $pruned,
                ]);
            }

            $version->setAttribute('pruned', $pruned);

            return $version;
        });
    }

    public function pruneNotice(): ?string
    {
        $pruned = $this->getAttribute('pruned');
        if (empty($pruned)) return null;

        $list = collect($pruned)->pluck('number')->map(fn($n) => 'v' . $n)->implode(', ');

        return "Đã đạt giới hạn lưu trữ, phiên bản cũ nhất ({$list}) đã bị xóa.";
    }
}
