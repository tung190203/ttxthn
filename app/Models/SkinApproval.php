<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SkinApproval extends Model
{
    use LogsActivity;
    protected $fillable = [
        'vrtour_id',
        'type',
        'record_id',
        'payload',
        'user_id',
        'approval_level',
        'max_approval',
        'is_draft',
        'status',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }
    public const TYPE_ALL = 0;
    public const TYPE_WELCOME = 1;
    public const TYPE_CONNECT_MAP = 3;
    public const TYPE_DOCUMENT = 4;
    public const TYPE_PLAN = 5;
    public const TYPE_INVESTOR = 6;
    public const TYPE_LOCATION = 7;

    protected $casts = [
        'payload' => 'array',
        'is_draft' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'vrtour_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function welcomeScreen()
    {
        return $this->belongsTo(WelcomeScreen::class, 'record_id')->where('type', self::TYPE_WELCOME);
    }
    public function connectMap()
    {
        return $this->belongsTo(ConnectMap::class, 'record_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'record_id');
    }
    public function investor()
    {
        return $this->belongsTo(Investor::class, 'record_id');
    }
    public function location()
    {
        return $this->belongsTo(Project::class, 'record_id');
    }
    public function legalDocument()
    {
        return $this->belongsTo(LegalDocument::class, 'record_id');
    }
}
