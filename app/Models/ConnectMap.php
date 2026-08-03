<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ConnectMap extends Model
{
    use LogsActivity;
    protected $table = "connect_map";
    protected $fillable = [
        'vrtour_id',
        'image',
        'image_en',
        'content',
        'content_en',
        'user_id',
    ];
    public function pendingSkinApproval()
    {
        return $this->hasOne(SkinApproval::class, 'record_id')->where('type', SkinApproval::TYPE_CONNECT_MAP)->where('status', 'pending');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
