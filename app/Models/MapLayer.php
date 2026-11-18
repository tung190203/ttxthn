<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MapLayer extends Model
{
    use HasFactory;

    protected $table = 'map_layers';

    protected $fillable = [
        'name',
        'type',
        'url_endpoint',
        'file_path',
        'description',
        'display_order',
        'is_active',
        'layer_options',
        'max_downtime_minutes',
        'max_latency_ms',
        'last_status',
        'last_checked_at',
        'last_latency_ms',
        'consecutive_failures',
        'last_alert_sent_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'layer_options' => 'array',
        'max_downtime_minutes' => 'integer',
        'max_latency_ms' => 'integer',
        'last_latency_ms' => 'integer',
        'consecutive_failures' => 'integer',
        'last_checked_at' => 'datetime',
        'last_alert_sent_at' => 'datetime',
    ];

    const TYPE_WMS = 'WMS';
    const TYPE_WFS = 'WFS';
    const TYPE_GEOJSON = 'GeoJSON';
    const TYPE_SHAPEFILE = 'Shapefile';

    public static array $types = [
        self::TYPE_GEOJSON => 'GeoJSON (Dữ liệu không gian vector)',
        self::TYPE_WMS => 'WMS (Web Map Service - Bản đồ ảnh)',
        self::TYPE_WFS => 'WFS (Web Feature Service - Bản đồ thuộc tính)',
        self::TYPE_SHAPEFILE => 'Shapefile (Tập tin nén ESRI Shapefile)',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_map_layer', 'map_layer_id', 'project_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(MapLayerAuditLog::class, 'map_layer_id')->latest();
    }

    public function monitors()
    {
        return $this->hasMany(MapSourceMonitor::class, 'map_layer_id')->latest('checked_at');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Xóa cache bản đồ công khai ngay lập tức (đảm bảo phản ánh < 60s)
     */
    public static function clearPublicCache(): void
    {
        Cache::forget('public_map_layers');
        Cache::forget('public_map_layers_admin');
    }

    protected static function booted()
    {
        static::saved(function () {
            self::clearPublicCache();
        });

        static::deleted(function () {
            self::clearPublicCache();
        });
    }
}
