<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectMap extends Model
{
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
}
