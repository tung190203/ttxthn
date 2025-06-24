<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';
    protected $casts = [
        'boundary' => 'array',
    ];
    
    protected $fillable = [
        'name',
        'boundary',
    ];
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_district');
    }
}
