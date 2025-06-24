<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectIndustries extends Model
{
    use HasFactory;
    protected $table = 'project_industries';

    public function projects()
    {
        return $this->hasMany(Project::class, 'industry_number', 'id');
    }
}
