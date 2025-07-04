<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';

    protected $fillable = [
        'name',
        'lat',
        'lng',
        'type_number',
        'industry_number',
        'price',
        'link',
        'image',
        'description',
    ];

    public function type()
    {
        return $this->belongsTo(ProjectType::class, 'type_number', 'id');
    }
    public function industry()
    {
        return $this->belongsTo(ProjectIndustries::class, 'industry_number', 'id');
    }
    public function districts()
    {
        return $this->belongsToMany(District::class, 'project_district');
    }

    public function industrialProjects()
    {
        return $this->hasMany(IndustrialProject::class);
    }
}
