<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

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
    public static function filterProjectIds(Request $request)
    {
        return self::query()
            ->select('project_id')
            ->when(
                $request->filled('search'),
                fn($q) => $q->where(function ($subQuery) use ($request) {
                    $subQuery->where('name', 'like', '%' . $request->search . '%');
                    if (is_numeric($request->search)) {
                        $value = (float) $request->search;
                        $subQuery->orWhereBetween('acreage', [$value - 0.5, $value + 0.5]);
                    }
                })
            )
            ->when(
                $request->filled('project_id') && $request->project_id !== 'all',
                fn($q) => $q->where('project_id', $request->project_id)
            )
            ->when(
                $request->filled('product_type') && $request->product_type !== 'all',
                fn($q) => $q->where('product_type', $request->product_type)
            )
            ->when(
                $request->has('price') && (int)$request->price > 0,
                fn($q) => $q->where(function ($sub) use ($request) {
                    $sub->whereNull('price')
                        ->orWhere('price', '<=', (int) $request->price);
                })
            )
            ->pluck('project_id');
    }
}
