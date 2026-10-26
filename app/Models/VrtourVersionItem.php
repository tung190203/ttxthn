<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VrtourVersionItem extends Model
{
    public const ACTION_ADDED    = 'added';
    public const ACTION_MODIFIED = 'modified';
    public const ACTION_REMOVED  = 'removed';

    public const ENTITY_PANORAMA = 'panorama';
    public const ENTITY_HOTSPOT  = 'hotspot';

    public const PANORAMA_FIELDS = [
        'vrtour_id',
        'label_audio',
        'ids',
        'title',
        'title_en',
        'content',
        'content_en',
        'audio',
        'audio_en',
        'user_id',
        'approval_level',
        'max_approval',
        'is_draft',
        'parent_id',
        'status',
    ];

    public const HOTSPOT_FIELDS = [
        'vrtour_id',
        'investment_product_statuses_id',
        'potision',
        'acreage',
        'unit',
        'intended_use',
        'url',
        'opacity',
        'tooltip',
        'tooltip_en',
        'type',
        'user_id',
        'product_type',
        'approval_level',
        'max_approval',
        'is_draft',
        'parent_id',
        'status',
        'price',
        'lease_price',
        'sale_price',
        'transaction_note',
        'available_amenities',
        'contact_info',
    ];

    public const SKIN_FIELDS = [
        'welcome' => ['title', 'description', 'voice', 'show_investor', 'investor_img','investor_desc1', 'investor_desc2', 'investor_desc3'],
        'connect_map' => ['image', 'image_en', 'content', 'content_en'],
        'plan' => ['show',
            'image1', 'title1', 'title1_en', 'content1', 'content1_en',
            'image2', 'title2', 'title2_en', 'content2', 'content2_en',
            'image3', 'title3', 'title3_en', 'content3', 'content3_en'],
        'investor' => ['name', 'name_en', 'image', 'content1', 'content1_en', 'content2', 'content2_en',
                    'content3', 'content3_en', 'website', 'sologan', 'sologan_en', 'status'],
        'location' => ['location_in_tour'],
    ];

    public $timestamps = false;

    protected $fillable = [
        'version_id',
        'entity_type',
        'entity_key',
        'action',
        'before_data',
        'after_data',
        'before_hash',
        'after_hash',
        'created_at',
    ];

    protected $casts = [
        'before_data' => 'array',
        'after_data'  => 'array',
        'created_at'  => 'datetime',
    ];

    public function version()
    {
        return $this->belongsTo(VrtourVersion::class, 'version_id');
    }
    public static function hashData(array $data): string
    {
        ksort($data);
        return hash('sha256', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public static function skinData(string $entityType, Model $m)
    {
        return $m ? $m->only(self::SKIN_FIELDS[$entityType]) : null;
    }

    protected static function booted(): void
    {
        // Lịch sử bất biến: không cho sửa item đã ghi
        static::updating(fn() => false);
    }

    public static function normalize(?array $data): ?array
    {
        if ($data === null) {
            return null;
        }

        return array_map(
            fn($v) => is_array($v) ? $v : (is_bool($v) ? (int) $v : ($v === null ? null : (string) $v)),
            $data
        );
    }

    /** Dữ liệu một hotspot để lưu: field của hotspot + thông tin industrial (ip_exists, ip_link) */
    public static function hotspotData(Hotspot $h, $links = null): array
    {
        // $links: [code => link] của cả tour (truyền vào để tránh query lặp)
        $links ??= IndustrialProject::where('project_id', $h->vrtour_id)->where('code', $h->potision)->pluck('link', 'code');

        // only() đọc thẳng thuộc tính nên không bị $hidden che mất các trường giá
        $data = $h->only(self::HOTSPOT_FIELDS);
        $data['ip_exists'] = $links->has($h->potision);
        $data['ip_link']   = $links->get($h->potision);

        return $data;
    }

    /** Trạng thái hiện tại của mọi lô đất đã xuất bản: [id => data] */
    public static function hotspotStates(int $vrtourId): array
    {
        $links = IndustrialProject::where('project_id', $vrtourId)->pluck('link', 'code');

        return Hotspot::where('vrtour_id', $vrtourId)
            ->where('type', 2)->where('is_draft', 0)
            ->orderBy('id')->get()
            ->mapWithKeys(fn($h) => [(string) $h->getKey() => self::hotspotData($h, $links)])
            ->all();
    }
}
