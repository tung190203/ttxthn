<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotspot extends Model
{
    protected $table = "hotspot";

    public function project()
    {
        return $this->belongsTo(Project::class, 'vrtour_id', 'id');
    }

    public function IndustrialProject()
    {
        return $this->hasOne(IndustrialProject::class, 'code', 'potision');
    }
}
