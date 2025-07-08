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
                    $search = $request->search;

                    $subQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                        ->orWhereRaw('LOWER(code) LIKE ?', ['%' . strtolower($search) . '%']);

                    if (is_numeric($search)) {
                        $value = (float) $search;
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
    public function scopeFilterByRequest($query, Request $request)
    {
        return $query
            ->when(
                $request->filled('search'),
                function ($q) use ($request) {
                    $search = strtolower($request->search);
                    $q->where(function ($subQuery) use ($search) {
                        $subQuery->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('LOWER(code) LIKE ?', ["%{$search}%"]);

                        if (is_numeric($search)) {
                            $value = (float) $search;
                            $subQuery->orWhereBetween('acreage', [$value - 0.5, $value + 0.5]);
                        }
                    });
                }
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
                $request->has('price') && (int) $request->price > 0,
                fn($q) => $q->where(function ($sub) use ($request) {
                    $sub->whereNull('price')
                        ->orWhere('price', '<=', (int) $request->price);
                })
            );
    }
}
