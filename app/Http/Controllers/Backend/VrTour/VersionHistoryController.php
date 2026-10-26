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
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProductType;
use App\Models\InvestmentProductStatus;
use Illuminate\Support\Str;

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

        if ($selectedVrtour) {
            $itemColumns = $tab === VrtourVersion::SCOPE_HOTSPOT
                ? 'items:id,version_id,entity_type,entity_key,action,before_data,after_data'
                : 'items:id,version_id,entity_type,entity_key,action';

            $versions = $this->versionsQuery($selectedVrtour->id, $tab, $request)
                ->with($itemColumns)
                ->orderByDesc('version_number')
                ->paginate(10)
                ->withQueryString();
        }

        $retention = $selectedVrtour ? app(VrtourVersionRetentionService::class)->status($selectedVrtour->id, $tab) : null;
        $canRestore = $this->canRestore($user);
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
            'scope'     => 'nullable|in:panorama,hotspot,skin',
            'a'         => 'required|integer|different:b',
            'b'         => 'required|integer',
        ]);
        $scope = $data['scope'] ?? VrtourVersion::SCOPE_PANORAMA;

        $selectedVrtour = $this->accessibleProjects(auth('web')->user())->firstWhere('id', $data['vrtour_id']);
        abort_if(!$selectedVrtour, 404);

        $versions = VrtourVersion::with('creator')
            ->where('vrtour_id', $selectedVrtour->id)
            ->where('scope', $scope)
            ->whereIn('id', [$data['a'], $data['b']])
            ->get();
        abort_if($versions->count() !== 2, 404);

        [$old, $new] = $versions->sortBy('version_number')->values()->all();

        $result = $diffService->diff($old, $new, $scope);
        $userIds = collect($result['rows'])
            ->flatMap(fn($r) => [$r['old']['user_id'] ?? null, $r['new']['user_id'] ?? null])
            ->filter()->unique()->values();
        $users = User::whereIn('id', $userIds)->pluck('name', 'id');

        $lookups = [
            'status'       => InvestmentProductStatus::pluck('name', 'id'),
            'product_type' => ProductType::pluck('name', 'id'),
        ];

        return view('backend.vrtour.history.compare', [
            'selectedVrtour' => $selectedVrtour,
            'scope'          => $scope,
            'old'            => $old,
            'new'            => $new,
            'labels'         => VrtourVersionDiffService::labelsFor($scope),
            'rows'           => $diffService->buildCells($result['rows'], $users, $scope, $lookups),
            'summary'        => $result['summary'],
            'backUrl'        => route('backend_vrtour_history_index', [
                'slt_vrtour' => $selectedVrtour->id,
                'tab'        => $scope,
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
        abort_unless(in_array($source->scope, self::TABS, true), 404);

        return response()->json($service->prepare($source, auth('web')->user(), trim($data['reason'])));
    }

    public function restoreConfirm(Request $request, VrtourVersionRestoreService $service)
    {
        abort_unless($this->canRestore(auth('web')->user()), 403);

        $data = $request->validate(['token' => 'required|string']);

        $version = $service->execute($data['token'], auth('web')->user());

        // Flash cho trang lịch sử sau khi redirect (layout đã hiển thị session('warning'))
        if ($notice = $version->pruneNotice()) {
            session()->flash('warning', $notice);
        }

        return response()->json([
            'message'  => 'Đã khôi phục. Phiên bản mới: v' . $version->version_number,
            'redirect' => route('backend_vrtour_history_index', [
                'slt_vrtour' => $version->vrtour_id,
                'tab'        => $version->scope,   // panorama | hotspot
            ]),
        ]);
    }

    /** Chỉ QTHT. Đổi điều kiện cho khớp cách phân quyền của dự án. */
    private function canRestore($user): bool
    {
        return (bool) $user->is_super_admin;
    }

    private function versionsQuery(int $projectId, string $scope, Request $request)
    {
        return VrtourVersion::query()
            ->where('vrtour_id', $projectId)
            ->where('scope', $scope)
            ->when($request->filled('from_date'), fn($q) => $q->whereDate('created_at', '>=', $request->input('from_date')))
            ->when($request->filled('to_date'), fn($q) => $q->whereDate('created_at', '<=', $request->input('to_date')))
            ->with(['creator', 'sourceVersion']);
    }

    public function exportPdf(Request $request, VrtourVersionDiffService $diffService)
    {
        $request->validate([
            'slt_vrtour' => 'required|integer',
            'tab'        => 'required|in:' . implode(',', self::TABS),
            'from_date'  => 'nullable|date',
            'to_date'    => 'nullable|date|after_or_equal:from_date',
        ]);

        $user    = auth('web')->user();
        $project = $this->accessibleProjects($user)->firstWhere('id', $request->input('slt_vrtour'));
        abort_if(!$project, 404);

        $tab = $request->input('tab');
        if (!in_array($tab, self::TABS, true)) {
            return back()->with('error', 'Tab này chưa có dữ liệu để xuất báo cáo.');
        }

        $versions = $this->versionsQuery($project->id, $tab, $request)
            ->with('items')
            ->orderByDesc('version_number')
            ->get();

        if ($versions->isEmpty()) {
            return back()->with('error', 'Không có phiên bản nào trong khoảng thời gian đã chọn để xuất.');
        }

        $labels     = VrtourVersionDiffService::labelsFor($tab);
        $withValues = $tab !== VrtourVersion::SCOPE_PANORAMA;

        $userIds = $versions->pluck('items')->flatten()
            ->flatMap(fn($it) => [$it->before_data['user_id'] ?? null, $it->after_data['user_id'] ?? null])
            ->filter()->unique()->values();
        $users   = User::whereIn('id', $userIds)->pluck('name', 'id');
        $lookups = [
            'status'       => InvestmentProductStatus::pluck('name', 'id'),
            'product_type' => ProductType::pluck('name', 'id'),
        ];

        foreach ($versions as $v) {
            $v->details = $v->items
                ->map(fn($it) => $diffService->describeItem($it, $labels, $lookups, $users, $withValues))
                ->all();
        }

        $pdf = Pdf::loadView('backend.vrtour.history.pdf.report', [
            'project'    => $project,
            'tab'        => $tab,
            'tabLabel'   => ['hotspot' => 'Hotspot', 'skin' => 'Skin'][$tab] ?? 'Panorama',
            'versions'   => $versions,
            'from'       => $request->input('from_date'),
            'to'         => $request->input('to_date'),
            'exporter'   => $user,
            'exportedAt' => now(),
            'retention'  => app(VrtourVersionRetentionService::class)->status($project->id, $tab),
        ])->setPaper('a4', 'landscape');

        $file = 'lich-su-phien-ban-' . Str::slug($project->vrtour_code ?: $project->id)
            . '-' . $tab . '-' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($file);
    }
}
