<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class WelcomeScreen extends Model
{
    use LogsActivity;
    protected $table = "welcome_screen";
    protected $fillable = [
        'vrtour_id',
        'title',
        'description',
        'voice',
        'show_investor',
        'investor_img',
        'investor_desc1',
        'investor_desc2',
        'investor_desc3',
        'user_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
    public function pendingSkinApproval()
    {
        return $this->hasOne(SkinApproval::class, 'record_id')
            ->where('type', SkinApproval::TYPE_WELCOME)
            ->where('status', 'pending');
    }
}
