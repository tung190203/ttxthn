<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    protected $table = "investor";
    protected $fillable = [
        'vrtour_id',
        'name',
        'name_en',
        'content1',
        'image',
        'content1_en',
        'content2',
        'content2_en',
        'content3',
        'content3_en',
        'website',
        'sologan',
        'sologan_en',
        'status',
        'user_id',
    ];
    public function pendingSkinApproval()
    {
        return $this->hasOne(SkinApproval::class, 'record_id')
            ->where('type', SkinApproval::TYPE_INVESTOR)
            ->where('status', 'pending');
    }
}
