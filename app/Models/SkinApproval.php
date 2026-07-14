<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkinApproval extends Model
{
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
        return $this->belongsTo(WelcomeScreen::class, 'record_id')->where('type', 1);
    }
}
