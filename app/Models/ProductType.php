<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class ProductType extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
    ];
    public $translatable = [
        'name',
    ];

    public function industrialProjects()
    {
        return $this->hasMany(IndustrialProject::class, 'product_type', 'id');
    }
}
