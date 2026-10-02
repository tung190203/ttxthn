<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\Interest;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->selectedMainMenu = 'reports';
        parent::__construct();
    }
    public function investorBehavior(Request $request)
    {
        // 1. Lọc theo kỳ (tháng, quý, năm)
        $period = $request->get('period', 'month'); // month, quarter, year
        $dateFrom = null;
        if ($period == 'month') {
            $dateFrom = Carbon::now()->startOfMonth();
        } elseif ($period == 'quarter') {
            $dateFrom = Carbon::now()->startOfQuarter();
        } else {
            $dateFrom = Carbon::now()->startOfYear();
        }

        $industryId = $request->get('industry');
        $districtId = $request->get('district');

        // Tóm tắt điều hành
        $newGuestsCount = Guest::where('created_at', '>=', $dateFrom)->count();
        $totalBookmarksQuery = Interest::where('created_at', '>=', $dateFrom);
        
        // If we want to filter bookmarks by industry/district, we only count Project bookmarks
        if ($industryId || $districtId) {
            $totalBookmarksQuery->where('interestable_type', Project::class)
                ->whereHasMorph('interestable', [Project::class], function ($query) use ($industryId, $districtId) {
                    if ($industryId) {
                        $query->where('industry_number', $industryId);
                    }
                    if ($districtId) {
                        $query->whereHas('districts', function ($q) use ($districtId) {
                            $q->where('district_id', $districtId);
                        });
                    }
                });
        }
        $totalBookmarks = $totalBookmarksQuery->count();

        // Bookmark nhiều nhất (Dự án)
        $topProjectsQuery = Interest::where('interestable_type', Project::class)
            ->where('created_at', '>=', $dateFrom)
            ->select('interestable_id', DB::raw('count(*) as total'))
            ->groupBy('interestable_id')
            ->orderByDesc('total');

        if ($industryId || $districtId) {
            $topProjectsQuery->whereHasMorph('interestable', [Project::class], function ($query) use ($industryId, $districtId) {
                if ($industryId) {
                    $query->where('industry_number', $industryId);
                }
                if ($districtId) {
                    $query->whereHas('districts', function ($q) use ($districtId) {
                        $q->where('district_id', $districtId);
                    });
                }
            });
        }
            
        $topProjects = $topProjectsQuery->limit(5)->get();
            
        $topProjectIds = $topProjects->pluck('interestable_id');
        $projects = Project::whereIn('id', $topProjectIds)->get()->keyBy('id');
        
        $projectChartData = [
            'labels' => [],
            'data' => []
        ];
        
        foreach ($topProjects as $tp) {
            if(isset($projects[$tp->interestable_id])) {
                $projectChartData['labels'][] = $projects[$tp->interestable_id]->name;
                $projectChartData['data'][] = $tp->total;
            }
        }

        // Lĩnh vực được quan tâm nhất (Ngành nghề)
        $industryBookmarksQuery = DB::table('interests')
            ->join('projects', 'interests.interestable_id', '=', 'projects.id')
            ->join('project_industries', 'projects.industry_number', '=', 'project_industries.id')
            ->where('interests.interestable_type', Project::class)
            ->where('interests.created_at', '>=', $dateFrom);

        if ($industryId) {
            $industryBookmarksQuery->where('projects.industry_number', $industryId);
        }
        if ($districtId) {
            $industryBookmarksQuery->join('project_district', 'projects.id', '=', 'project_district.project_id')
                ->where('project_district.district_id', $districtId);
        }

        $industryBookmarks = $industryBookmarksQuery
            ->select('project_industries.name', DB::raw('count(interests.id) as total'))
            ->groupBy('project_industries.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $industryLabels = [];
        foreach ($industryBookmarks as $ib) {
            $nameObj = json_decode($ib->name, true);
            $industryLabels[] = is_array($nameObj) ? ($nameObj['vi'] ?? $ib->name) : $ib->name;
        }

        $industryChartData = [
            'labels' => $industryLabels,
            'data' => $industryBookmarks->pluck('total')
        ];

        // Tỷ lệ chuyển đổi
        $totalVisits = \App\Models\VisitLog::where('created_at', '>=', $dateFrom)->count();
        if ($totalVisits == 0) $totalVisits = 1; // tránh chia 0
        $conversionRate = round(($newGuestsCount / $totalVisits) * 100, 2);

        // Fetch options for filters
        $industries = \App\Models\ProjectIndustries::all()->map(function($item) {
            $nameObj = is_string($item->name) ? json_decode($item->name, true) : $item->name;
            return (object) ['id' => $item->id, 'name' => is_array($nameObj) ? ($nameObj['vi'] ?? '') : $item->name];
        });
        
        $districts = \App\Models\District::all()->map(function($item) {
            $nameObj = is_string($item->name) ? json_decode($item->name, true) : $item->name;
            return (object) ['id' => $item->id, 'name' => is_array($nameObj) ? ($nameObj['vi'] ?? '') : $item->name];
        });

        return view('backend.reports.investor_behavior', compact(
            'period', 'newGuestsCount', 'totalBookmarks', 'conversionRate',
            'projectChartData', 'industryChartData', 'industries', 'districts',
            'industryId', 'districtId'
        ));
    }
}
