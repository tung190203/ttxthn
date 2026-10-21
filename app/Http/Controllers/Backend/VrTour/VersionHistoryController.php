<?php

namespace App\Http\Controllers\Backend\VrTour;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\VrtourVersion;
use App\Services\VrtourVersionDiffService;
use App\Services\VrtourVersionRestoreService;
use App\Services\VrtourVersionRetentionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VersionHistoryController extends Controller
{
    private const TABS = ['skin', 'panorama', 'hotspot'];

    public function __construct()
    {
        $this->selectedMainMenu = 'vr_tour';
        $this->selectedSubMenu('history');
        parent::__construct();

        if (!Gate::allows('vr_tour/history')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }

    public function index(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date'   => 'nullable|date|after_or_equal:from_date',
        ]);

        $user   = auth('web')->user();
        $vrtour = $this->accessibleProjects($user);

        // Hỗ trợ cả tham số mới và URL cũ
        $projectId = $request->input('slt_vrtour', $request->input('vrtour_id'));

        // Chỉ nhận dự án thuộc danh sách người dùng được phép truy cập
        $selectedVrtour = $projectId ? $vrtour->firstWhere('id', $projectId) : null;

        $tab = $request->input('tab', 'skin');
        if (!in_array($tab, self::TABS, true)) {
            $tab = 'skin';
        }

        $versions = VrtourVersion::query()->whereRaw('1 = 0')->paginate(10)->withQueryString();

        if ($selectedVrtour && $tab === 'panorama') {
            $versions = VrtourVersion::query()
                ->where('vrtour_id', $selectedVrtour->id)
                ->when($request->filled('from_date'), fn($q) =>
                $q->whereDate('created_at', '>=', $request->input('from_date')))
                ->when($request->filled('to_date'), fn($q) =>
                $q->whereDate('created_at', '<=', $request->input('to_date')))
                ->with('creator')
                ->withCount([
                    'items as panorama_count' => fn($q) => $q->where('entity_type', 'panorama'),
                ])
                ->orderByDesc('version_number')
                ->paginate(10)
                ->withQueryString();
        }
        $canRestore = $this->canRestore($user);
        $retention = $selectedVrtour
            ? app(VrtourVersionRetentionService::class)->status($selectedVrtour->id)
            : null;
        return view('backend.vrtour.history.index', compact(
            'vrtour',
            'selectedVrtour',
            'versions',
            'tab',
            'canRestore',
            'retention'
        ));
    }

    public function compare(Request $request, VrtourVersionDiffService $diffService)
    {
        $data = $request->validate([
            'vrtour_id' => 'required|integer',
            'a'         => 'required|integer|different:b',
            'b'         => 'required|integer',
        ]);

        // Tour phải nằm trong scope của user
        $selectedVrtour = $this->accessibleProjects(auth('web')->user())->firstWhere('id', $data['vrtour_id']);
        abort_if(!$selectedVrtour, 404);

        // Cả hai version phải thuộc đúng tour này
        $versions = VrtourVersion::with('creator')
            ->where('vrtour_id', $selectedVrtour->id)
            ->whereIn('id', [$data['a'], $data['b']])
            ->get();
        abort_if($versions->count() !== 2, 404);

        // Bản nhỏ hơn là cũ (trái), bản lớn hơn là mới (phải), bất kể thứ tự user tick
        [$old, $new] = $versions->sortBy('version_number')->values()->all();

        $result = $diffService->diff($old, $new, 'panorama');
        // Tra tên user cho các field user_id trong snapshot
        $userIds = collect($result['rows'])
            ->flatMap(fn($r) => [$r['old']['user_id'] ?? null, $r['new']['user_id'] ?? null])
            ->filter()
            ->unique()
            ->values();

        $users = User::whereIn('id', $userIds)->pluck('name', 'id');

        return view('backend.vrtour.history.compare', [
            'selectedVrtour' => $selectedVrtour,
            'old'            => $old,
            'new'            => $new,
            'rows'           => $diffService->buildCells($result['rows'], $users),
            'summary'        => $result['summary'],
            'backUrl'        => route('backend_vrtour_history_index', [
                'slt_vrtour' => $selectedVrtour->id,
                'tab'        => 'panorama',
            ]),
        ]);
    }

    /** Danh sách dự án user được phép truy cập (dùng chung cho index và compare) */
    private function accessibleProjects($user)
    {
        $query = Project::query()->visibleFor($user);
        $scope = $user->getScope('project');

        if (!empty($scope)) {
            $query->whereIn('id', $scope);
        }

        return $query->orderBy('name')->get();
    }

    public function restorePrepare(Request $request, VrtourVersionRestoreService $service)
    {
        abort_unless($this->canRestore(auth('web')->user()), 403);

        $data = $request->validate([
            'version_id' => 'required|integer',
            'reason'     => 'required|string|min:10|max:1000',
        ], [
            'reason.required' => 'Vui lòng nhập lý do khôi phục.',
            'reason.min'      => 'Lý do phải có ít nhất 10 ký tự.',
        ]);

        $source = VrtourVersion::findOrFail($data['version_id']);

        // Tour của version phải nằm trong scope của user
        abort_unless($this->accessibleProjects(auth('web')->user())->contains('id', $source->vrtour_id), 404);

        return response()->json($service->prepare($source, auth('web')->user(), trim($data['reason'])));
    }

    public function restoreConfirm(Request $request, VrtourVersionRestoreService $service)
    {
        abort_unless($this->canRestore(auth('web')->user()), 403);

        $data = $request->validate(['token' => 'required|string']);

        $version = $service->execute($data['token'], auth('web')->user());

        return response()->json([
            'message'  => 'Đã khôi phục. Phiên bản mới: v' . $version->version_number,
            'redirect' => route('backend_vrtour_history_index', [
                'slt_vrtour' => $version->vrtour_id,
                'tab'        => 'panorama',
            ]),
        ]);
    }

    /** Chỉ QTHT. Đổi điều kiện cho khớp cách phân quyền của dự án. */
    private function canRestore($user): bool
    {
        return (bool) $user->is_super_admin;
    }
}
