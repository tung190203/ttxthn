<?php

namespace App\Http\Controllers;

use App\Models\IndustrialProject;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\District;

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
            ($request->filled('invest_status') && $request->invest_status !== 'all') ||
            $request->filled('district');

        if ($request->tab === 'project') {
            $query = Project::with(['type', 'industry', 'districts']);

            if (!$hasFilters) {
                $query->inBounds($request->minLat, $request->maxLat, $request->minLng, $request->maxLng);
            }

            $query->filterByRequest($request);
        } else {
            $query = Project::with(['type', 'industry', 'districts', 'industrialProjects']);

            if (!$hasFilters) {
                $query->inBounds($request->minLat, $request->maxLat, $request->minLng, $request->maxLng);
            } else {
                $projectIds = IndustrialProject::filterProjectIds($request);

                if ($projectIds->isEmpty()) {
                    return response()->json([]);
                }

                $query->with([
                    'industrialProjects' => fn($q) => $q->filterByRequest($request)
                ])
                    ->whereIn('id', $projectIds)
                    ->whereHas('industrialProjects', fn($q) => $q->filterByRequest($request));

                $query->filterProjectOnly($request);
            }
        }

        $projects = $query->whereNull('parent_id')->where('status', 'approved')->get();
        $data = $this->returnData($projects);
        return response()->json($data);
    }

    public function getDistricts()
    {
        $districts = District::query()
            ->select('name', 'boundary')
            ->orderBy('name')
            ->get()
            ->map(function ($district) {
                return [
                    'name' => $district->name,
                    'name_vi' => $district->getTranslation('name', 'vi'),
                    'boundary' => $district->boundary,
                ];
            });

        return response()->json($districts);
    }

    public function getRailwayProjects()
    {
        $projects = Project::with(['type', 'industry', 'districts'])
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->where('industry_number', Project::RAILWAY_INDUSTRY_NUMBER)
            ->whereNotNull('railway_lines')
            ->get()
            ->filter(fn($project) => !empty($project->railway_lines));

        return response()->json($this->returnData($projects)->values());
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
                'area' => $project->area,
                'unit' => $project->unit_type_text,
                'price' => $project->price,
                'link' => $project->link,
                'link_vrtour' => $project->hide_vrtour ? null : $project->link_vrtour,
                'is_invest' => $project->is_invest,
                'banner_image' => $project->banner_image,
                'detail_image' => $project->detail_image,
                'boundary' => $project->boundary,
                'has_occupancy_rate' => $project->has_occupancy_rate,
                'occupancy_rate' => $project->occupancy_rate,
                'railway_lines' => $project->railway_lines ?? [],
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
                        'intended_use' => $industrialProject->intended_use,
                        'unit' => $industrialProject->unit ? "km" : "ha",
                    ];
                })->toArray(),
            ];
        });
    }
}
