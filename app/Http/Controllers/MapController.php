<?php

namespace App\Http\Controllers;

use App\Models\IndustrialProject;
use App\Models\Project;
use Illuminate\Http\Request;

class MapController extends Controller
{
    private const NUMERIC_VALIDATION_RULE = 'required|numeric';

    public function getProjectsInBounds(Request $request)
    {
        $request->validate([
            'minLat' => self::NUMERIC_VALIDATION_RULE,
            'maxLat' => self::NUMERIC_VALIDATION_RULE,
            'minLng' => self::NUMERIC_VALIDATION_RULE,
            'maxLng' => self::NUMERIC_VALIDATION_RULE,
            'tab' => 'required|in:project,industrial',
        ]);

        if ($request->tab === 'project') {
            $query = Project::with(['type', 'industry', 'districts'])
                ->whereBetween('lat', [$request->minLat, $request->maxLat])
                ->whereBetween('lng', [$request->minLng, $request->maxLng])
                // Lọc type
                ->when(
                    $request->filled('type') && $request->type !== 'all',
                    fn($q) =>
                    $q->where('type_number', $request->type)
                )
                // Lọc industry
                ->when(
                    $request->filled('industry') && $request->industry !== 'all',
                    fn($q) =>
                    $q->where('industry_number', $request->industry)
                )
                // Lọc theo từ khóa
                ->when(
                    $request->filled('search'),
                    fn($q) =>
                    $q->where('name', 'like', '%' . $request->search . '%')
                )
                // Lọc theo giá
                ->when(
                    $request->has('price') && (int)$request->price > 0,
                    fn($q) => $q->where(function ($sub) use ($request) {
                        $sub->whereNull('price')
                            ->orWhere('price', '<=', (int) $request->price);
                    })
                )
                // Lọc theo quận/huyện
                ->when(
                    $request->filled('district'),
                    fn($q) =>
                    $q->whereHas(
                        'districts',
                        fn($q2) =>
                        $q2->where('name', $request->district)
                    )
                );
        } else {
            $hasIndustrialFilters =
                $request->filled('search') ||
                ($request->filled('project_id') && $request->project_id !== 'all') ||
                ($request->filled('product_type') && $request->product_type !== 'all') ||
                $request->filled('price') && (int)$request->price > 0;

            $query = Project::with(['type', 'industry', 'districts'])
                // ->whereBetween('lat', [$request->minLat, $request->maxLat])
                // ->whereBetween('lng', [$request->minLng, $request->maxLng])
                ->when(
                    $request->filled('district'),
                    fn($q) => $q->whereHas(
                        'districts',
                        fn($q2) => $q2->where('name', $request->district)
                    )
                );

            if ($hasIndustrialFilters) {
                $subQuery = IndustrialProject::query()
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
                    );

                $projectIds = $subQuery->pluck('project_id');

                $query->whereIn('id', $projectIds);
            }
        }

        $projects = $query->get();
        $data = $this->returnData($projects);

        return response()->json($data);
    }

    public function getDistricts()
    {
        $districts = Project::with('districts')
            ->get()
            ->pluck('districts')
            ->flatten()
            ->unique('name')
            ->sortBy('name')
            ->values();

        return response()->json($districts->pluck('name'));
    }

    private function returnData($projects)
    {
        return $projects->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'lat' => $project->lat,
                'lng' => $project->lng,
                'type_number' => $project->type_number,
                'type_name' => $project->type->name ?? null,
                'industry_name' => $project->industry->name ?? null,
                'industry_number' => $project->industry_number,
                'price' => $project->price,
                'link' => $project->link,
                'districts' => $project->districts->pluck('name')->toArray(),
                'industrial' => $project->industrialProjects->map(function ($industrialProject) {
                    return [
                        'id' => $industrialProject->id,
                        'name' => $industrialProject->name,
                        'acreage' => $industrialProject->acreage,
                        'code' => $industrialProject->code,
                        'description' => $industrialProject->description,
                        'product_type' => $industrialProject->product_type,
                        'product_type_name' => $industrialProject->productType->name ?? null,
                        'price' => $industrialProject->price,
                        'link' => $industrialProject->link,
                    ];
                })->toArray(),
            ];
        });
    }
}
