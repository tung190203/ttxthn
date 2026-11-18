<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestViewHistory extends Model
{
    protected $fillable = ['guest_id', 'project_id', 'viewed_at'];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
