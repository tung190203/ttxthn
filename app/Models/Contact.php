<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'project_id',
        'message',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
