<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\InvestmentGuide;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectIndustries;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->selectedMainMenu = 'dashboard';
        parent::__construct();
    }

    public function index(Request $request)
    {
        $user = auth('web')->user();
        if ($user->status_approve != 'approved') {
            auth('web')->logout();
            return redirect()->route('login')->with('error', 'Tài khoản của bạn đang chờ phê duyệt hoặc bị từ chối. Vui lòng liên hệ quản trị viên.');
        }

        if ($user->isSuperAdmin()) {
            $quantityProjects = Project::count() ?? 0;
            $quantityUser = Guest::count() ?? 0;
            $quantityPost = Post::count() ?? 0;
            $quantityInvestmentGuide = InvestmentGuide::count() ?? 0;

            // Monthly Activity Chart Data
            $range = (int) $request->get('range', 6);
            if ($range <= 0 || $range > 24) $range = 6; // Limit range for performance

            $chartLabels = [];
            $projectChartData = [];
            $postChartData = [];
            $guestChartData = [];
            $investmentGuideChartData = [];

            for ($i = $range - 1; $i >= 0; $i--) {
                $date = now()->startOfMonth()->subMonths($i);
                $month = $date->format('m');
                $year = $date->format('Y');

                $chartLabels[] = 'Tháng ' . (int)$month;

                $projectChartData[] = Project::whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->count();

                $postChartData[] = Post::whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->count();

                $guestChartData[] = Guest::whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->count();

                $investmentGuideChartData[] = InvestmentGuide::whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->count();
            }

            // Project Distribution by Industry
            $industries = ProjectIndustries::all();
            $industryLabels = [];
            $industryData = [];
            foreach ($industries as $industry) {
                $industryLabels[] = $industry->getTranslation('name', app()->getLocale());
                $count = Project::where('industry_number', $industry->id)->count();
                $industryData[] = $count;
            }

            // Activity Logs
            $activitySearch = $request->get('search_log');
            $activityEvent = $request->get('event_log');

            $activities = Activity::with('causer')
                ->latest()
                ->when($activitySearch, function ($query) use ($activitySearch) {
                    return $query->where('description', 'like', "%{$activitySearch}%")
                        ->orWhereHasMorph('causer', [User::class], function ($q) use ($activitySearch) {
                            $q->where('name', 'like', "%{$activitySearch}%");
                        });
                })
                ->when($activityEvent, function ($query) use ($activityEvent) {
                    return $query->where('event', $activityEvent);
                })
                ->paginate(10, ['*'], 'activity_page')
                ->appends($request->all());

            if ($request->ajax()) {
                return view('backend.dashboard.partials._activity_log_table', compact('activities'))->render();
            }

            return view('backend.dashboard.index', compact(
                'quantityProjects',
                'quantityUser',
                'quantityPost',
                'quantityInvestmentGuide',
                'chartLabels',
                'projectChartData',
                'postChartData',
                'guestChartData',
                'investmentGuideChartData',
                'industryLabels',
                'industryData',
                'range',
                'activities'
            ));
        }
        $permissions = $user->getAllPermissionsFromGroup();
        $permissions = array_values(array_filter($permissions, fn($p) => $p !== 'backend_access'));
        if (empty($permissions)) {
            return view('backend.no_permission', [
                'message' => 'Bạn chưa được cấp quyền truy cập bất kỳ chức năng nào. Vui lòng liên hệ quản trị viên.',
                'hideSidebar' => true,
                'hideHeader' => true,
                'hideFooter' => true,
                'hideBreadcrumb' => true
            ]);
        }
        $firstPermission = $permissions[0];
        $routeName = 'backend_' . str_replace('/', '_', $firstPermission);
        if (app('router')->has($routeName)) {
            return redirect()->route($routeName);
        }

        return view('backend.no_permission', [
            'message' => 'Không tìm thấy trang phù hợp với quyền của bạn.',
            'hideSidebar' => true
        ]);
    }
}
