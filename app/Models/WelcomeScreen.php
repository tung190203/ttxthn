<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeScreen extends Model
{
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
    public function pendingSkinApproval()
    {
        return $this->hasOne(SkinApproval::class, 'record_id')
            ->where('type', SkinApproval::TYPE_WELCOME)
            ->where('status', 'pending');
    }
}
