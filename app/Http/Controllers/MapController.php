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

        $hasFilters = $request->filled('search') ||
            ($request->filled('project_id') && $request->project_id !== 'all') ||
            ($request->filled('product_type') && $request->product_type !== 'all') ||
            ($request->has('price') && (int)$request->price > 0) ||
            ($request->filled('type') && $request->type !== 'all') ||
            ($request->filled('industry') && $request->industry !== 'all') ||
            $request->filled('district');

        if ($request->tab === 'project') {
            $query = Project::with(['type', 'industry', 'districts']);

            if (!$hasFilters) {
                $query->inBounds($request->minLat, $request->maxLat, $request->minLng, $request->maxLng);
            }

            $query->filterByRequest($request);
        } else {
            if (!$hasFilters) {
                $query = Project::with(['type', 'industry', 'districts', 'industrialProjects']);
            } else {
                $projectIds = IndustrialProject::filterProjectIds($request);

                if ($projectIds->isEmpty()) {
                    return response()->json([]);
                }

                $query = Project::with([
                    'type',
                    'industry',
                    'districts',
                    'industrialProjects' => fn($q) => $q->filterByRequest($request)
                ])
                    ->whereIn('id', $projectIds)
                    ->whereHas('industrialProjects', fn($q) => $q->filterByRequest($request));
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
