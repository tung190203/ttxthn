<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IndustrialProject extends Model
{

    use HasFactory;
    protected $table = 'industrial_projects';

    protected $fillable = [
        'project_id',
        'name',
        'link',
        'acreage',
        'description',
        'product_type',
        'price',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type', 'id');
    }
}
