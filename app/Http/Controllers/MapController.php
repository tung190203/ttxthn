<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function getProjects(Request $request)
    {
        $projects = Project::with(['type', 'industry', 'districts'])->get();

        $data = $projects->map(function ($project) {
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

        return response()->json($data);
    }

    public function filter(Request $request)
    {
        $query = Project::query()
            ->with(['districts']) // Eager load districts

            ->when($request->type !== 'all', fn($q) =>
                $q->where('type_number', $request->type)
            )

            ->when($request->filled('industry') && $request->industry !== 'all', fn($q) =>
                $q->where('industry_number', $request->industry)
            )

            ->when($request->filled('search'), fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
            )

            ->when($request->filled('price'), fn($q) =>
                $q->where(function ($sub) use ($request) {
                    $sub->whereNull('price')
                        ->orWhere('price', '<=', (int) $request->price);
                })
            )

            ->when($request->filled('district'), fn($q) =>
                $q->whereHas('districts', fn($q2) =>
                    $q2->where('name', $request->district)
                )
            );

        $projects = $query->get();

        // Chuẩn hoá lại dữ liệu gửi về cho frontend
        $projects = $projects->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'lat' => $p->lat,
                'lng' => $p->lng,
                'type_number' => $p->type_number,
                'type_name' => $p->type->name ?? null,
                'industry_name' => $p->industry->name ?? null,
                'industry_number' => $p->industry_number,
                'price' => $p->price,
                'link' => $p->link,
                'districts' => $p->districts->pluck('name')->toArray(),
            ];
        });

        return response()->json($projects);
    }
}
