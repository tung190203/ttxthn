<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectType extends Model
{
    use HasFactory;
    protected $table = 'project_types';

    public function projects()
    {
        return $this->hasMany(Project::class, 'type_number', 'id');
    }
}
