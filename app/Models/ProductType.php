<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function industrialProjects()
    {
        return $this->hasMany(IndustrialProject::class, 'product_type', 'id');
    }
}
