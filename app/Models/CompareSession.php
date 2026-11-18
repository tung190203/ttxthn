<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompareSession extends Model
{
    protected $fillable = ['guest_id', 'name', 'project_ids'];

    protected $casts = [
        'project_ids' => 'array',
    ];

    public function notes()
    {
        return $this->hasMany(CompareNote::class, 'session_id');
    }
}
