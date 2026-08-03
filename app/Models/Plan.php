<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Plan extends Model
{
    use LogsActivity;
    protected $table = "plan";
    protected $fillable = [
        'vrtour_id',
        'show',
        'background',
        'image1',
        'image2',
        'image3',
        'title1',
        'title1_en',
        'title2',
        'title2_en',
        'title3',
        'title3_en',
        'content1',
        'content1_en',
        'content2',
        'content2_en',
        'content3',
        'content3_en',
        'user_id',
    ];
    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'vrtour_id');
    }
    public function pendingSkinApproval()
    {
        return $this->hasOne(SkinApproval::class, 'record_id')
            ->where('type', SkinApproval::TYPE_PLAN)
            ->where('status', 'pending');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
}
