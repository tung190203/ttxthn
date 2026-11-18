<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class MapLayerAuditLog extends Model
{
    use HasFactory;

    protected $table = 'map_layer_audit_logs';

    protected $fillable = [
        'map_layer_id',
        'layer_name',
        'user_id',
        'user_name',
        'action',
        'action_label',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    const ACTION_CREATE = 'CREATE';
    const ACTION_UPDATE = 'UPDATE';
    const ACTION_DELETE = 'DELETE';
    const ACTION_TOGGLE_STATUS = 'TOGGLE_STATUS';
    const ACTION_REORDER = 'REORDER';

    public static array $actions = [
        self::ACTION_CREATE => 'Thêm mới layer',
        self::ACTION_UPDATE => 'Cập nhật thông tin',
        self::ACTION_DELETE => 'Xóa layer',
        self::ACTION_TOGGLE_STATUS => 'Bật / Ẩn layer',
        self::ACTION_REORDER => 'Sắp xếp thứ tự',
    ];

    public function layer()
    {
        return $this->belongsTo(MapLayer::class, 'map_layer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Ghi nhận nhật ký thay đổi
     */
    public static function record(
        ?int $layerId,
        string $layerName,
        string $action,
        string $actionLabel,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        $user = auth('web')->user();

        return self::create([
            'map_layer_id' => $layerId,
            'layer_name' => $layerName,
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'QTHT Hệ Thống',
            'action' => $action,
            'action_label' => $actionLabel,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip() ?? '127.0.0.1',
            'user_agent' => Request::userAgent(),
        ]);
    }
}
