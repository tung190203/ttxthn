<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

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
    public function scopeInBounds($query, $minLat, $maxLat, $minLng, $maxLng)
    {
        return $query->whereBetween('lat', [$minLat, $maxLat])
            ->whereBetween('lng', [$minLng, $maxLng]);
    }

    public function scopeFilterByRequest($query, Request $request)
    {
        return $query
            ->when(
                $request->filled('type') && $request->type !== 'all',
                fn($q) => $q->where('type_number', $request->type)
            )
            ->when(
                $request->filled('industry') && $request->industry !== 'all',
                fn($q) => $q->where('industry_number', $request->industry)
            )
            ->when(
                $request->filled('search'),
                fn($q) => $q->where('name', 'like', '%' . $request->search . '%')
            )
            ->when(
                $request->has('price') && (int)$request->price > 0,
                fn($q) => $q->where(function ($sub) use ($request) {
                    $sub->whereNull('price')
                        ->orWhere('price', '<=', (int) $request->price);
                })
            )
            ->when(
                $request->filled('district'),
                fn($q) => $q->whereHas('districts', fn($q2) => $q2->where('name', $request->district))
            );
    }
}
