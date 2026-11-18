<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MapSourceMonitor extends Model
{
    use HasFactory;

    protected $table = 'map_source_monitors';

    protected $fillable = [
        'map_layer_id',
        'status',
        'response_time_ms',
        'http_status_code',
        'error_message',
        'checked_at',
    ];

    protected $casts = [
        'response_time_ms' => 'integer',
        'checked_at' => 'datetime',
    ];

    const STATUS_HEALTHY = 'healthy';
    const STATUS_WARNING = 'warning';
    const STATUS_ERROR = 'error';

    public function layer()
    {
        return $this->belongsTo(MapLayer::class, 'map_layer_id');
    }

    public function scopeLast30Days($query)
    {
        return $query->where('checked_at', '>=', Carbon::now()->subDays(30));
    }

    /**
     * Tính tỷ lệ uptime (%) trong N ngày qua (mặc định 30 ngày)
     */
    public static function calculateUptimePercentage(int $layerId, int $days = 30): float
    {
        $since = Carbon::now()->subDays($days);
        $total = self::where('map_layer_id', $layerId)->where('checked_at', '>=', $since)->count();

        if ($total === 0) {
            return 100.0;
        }

        $successful = self::where('map_layer_id', $layerId)
            ->where('checked_at', '>=', $since)
            ->whereIn('status', [self::STATUS_HEALTHY, self::STATUS_WARNING])
            ->count();

        return round(($successful / $total) * 100, 2);
    }
}
