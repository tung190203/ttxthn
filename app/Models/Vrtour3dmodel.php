<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Vrtour3dmodel extends Model
{
    use LogsActivity;

    protected $table = 'vrtour_3dmodel';

    protected $fillable = [
        'vrtour_id',
        'user_id',
        'title_3d',
        'title_3d_en',
        'file_3d',
        'show',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'vrtour_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pendingSkinApproval()
    {
        return $this->hasOne(SkinApproval::class, 'record_id')->where('type', SkinApproval::TYPE_MODEL3D)->where('status', 'pending');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

}
