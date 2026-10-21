<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VrtourVersionItem extends Model
{
    public $timestamps = false;
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
    protected $fillable = [
        'version_id',
        'entity_type',
        'entity_key',
        'parent_entity_key',
        'data',
        'content_hash',
        'created_at',
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
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

    protected static function booted(): void
    {
        // Snapshot bất biến
        static::updating(fn() => false);
        static::deleting(fn() => false);
    }
}
