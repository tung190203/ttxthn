<?php

namespace App\Http\Controllers\Backend\Vrtour;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Project;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->selectedMainMenu = 'vr_tour';
        $this->selectedSubMenu('approval');
        parent::__construct();
        if (!Gate::allows('vr_tour/approval')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }

    public function index(Request $request)
    {
        $user = auth('web')->user();
        $filter['name'] = $request->get('name', '');
        $activeTab = $request->get('tab', 'approved');
        $query = Project::query()->visibleFor($user);
        $scope = $user->getScope('project');

        if (!empty($scope)) {
            $query->whereIn('id', $scope);
        }
        if (!empty($filter['name'])) {
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        }

        $query->select(['id', 'name',])->withCount([
            'panoramas as content_pending_count' => function ($q) {
                $q->where('status', 'pending');
            },

            'panoramas as content_approved_count' => function ($q) {
                $q->where('status', 'approved')
                    ->where('is_draft', 0);
            },
            'hotspots as hotspot_pending_count' => function ($q) {
                $q->where('status', 'pending');
            },

            'hotspots as hotspot_approved_count' => function ($q) {
                $q->where('status', 'approved')
                    ->where('is_draft', 0);
            },
            'skinApprovals as skinApproval_pending_count' => function ($q) {
                $q->where('status', 'pending');
            },

            'skinApprovals as skinApproval_approved_count' => function ($q) {
                $q->where('status', 'approved')
                    ->where('is_draft', 0);
            }
        ]);

        $pendingCount = (clone $query)->get()->filter(function ($project) {
            return $project->content_pending_count > 0
                || $project->hotspot_pending_count > 0
                || $project->skinApproval_pending_count > 0;
        })->count();

        if ($activeTab == 'pending') {
            $query->havingRaw(
                'content_pending_count > 0
                OR hotspot_pending_count > 0
                OR skinApproval_pending_count > 0'
            );
        } else {
            $query->havingRaw(
                'content_pending_count = 0
                AND hotspot_pending_count = 0
                AND skinApproval_pending_count = 0'
            );
        }
        $projects = $query->orderBy('name')->paginate(10)->withQueryString();
        return view(
            'backend.vrtour.approval.index',
            compact(
                'projects',
                'filter',
                'activeTab',
                'pendingCount'
            )
        );
    }
    public function getPendingCount()
    {
        $count = Project::where(function ($query) {
            $query->whereHas('panoramas', function ($q) {
                $q->where('status', 'pending');
            })
                ->orWhereHas('hotspots', function ($q) {
                    $q->where('status', 'pending');
                })
                ->orWhereHas('skinApprovals', function ($q) {
                    $q->where('status', 'pending');
                });
        })->count();

        return response()->json([
            'count' => $count
        ]);
    }
}
