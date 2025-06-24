<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function getProjectsInBounds(Request $request)
    {
        $request->validate([
            'minLat' => 'required|numeric',
            'maxLat' => 'required|numeric',
            'minLng' => 'required|numeric',
            'maxLng' => 'required|numeric',
        ]);

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
                $request->filled('price'),
                fn($q) =>
                $q->where(function ($sub) use ($request) {
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

        $projects = $query->get();
        $data = $this->returnData($projects);

        return response()->json($data);
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
            ];
        });
    }
}
