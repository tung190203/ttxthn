<?php

namespace App\Http\Controllers\Backend\VrTour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Project;
use App\Models\Hotspot;
use App\Models\IndustrialProject;
use App\Models\InvestmentProductStatus;
use App\Models\ProductType;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\HotspotExport;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class HotspotController extends Controller
{
    public $hotspot;

    public function __construct(Hotspot $hotspot)
    {
        $this->hotspot = $hotspot;
        $this->selectedMainMenu = 'vr_tour';
        $this->selectedSubMenu('hotspot');
        parent::__construct();

        if (!Gate::allows('vr_tour/hotspot')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth('web')->user();
        $query = Project::query()->visibleFor($user);
        $scope = $user->getScope('project');
        if (!empty($scope)) {
            $query->whereIn('id', $scope);
        }
        $vrtour = $query->orderBy('name')->get();
        $investmentStatuses = InvestmentProductStatus::orderBy('id')->get();
        return view('backend.vrtour.hotspot.index', compact('vrtour', 'investmentStatuses'));
    }

    public function getHotspot(Request $request, $vrtour_id)
    {
        try {
            DB::beginTransaction();
            //get tour
            $vrtour = Project::find($vrtour_id);
            $link_vrtour = $vrtour->link_vrtour;
            $media_index = $vrtour->media_index;
            //get hotspot from db
            $typeFilter = match ((int) $request->type) {
                0 => [1, 2],
                1 => [1, 2, 3],
                2 => [2],
            };
            $user = auth()->user();

            if ($request->reset == 'true') {
                $response = getDataVrtour($link_vrtour . 'vista3d/hotspot.json');
                if (empty($response) || empty($response['data'])) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Hiện tại data hotspot không tồn tại',
                        'data' => ''
                    ]);
                }
                $hotspotsJson = json_decode($response['data'], true);
                $media_index = $response['media_index'];
                $vrtour->media_index = $media_index;
                $vrtour->save();
                $positionsFromJson = collect($hotspotsJson)->pluck('position')->toArray();
                $existingHotspots = Hotspot::where('vrtour_id', $vrtour_id)->get()->keyBy('potision');
                $existingProjects = IndustrialProject::where('project_id', $vrtour_id)->get()->keyBy('code');

                foreach ($hotspotsJson as $hp) {
                    $position = $hp['position'];
                    $existsHotspot = $existingHotspots->has($position);
                    $existsProject = $existingProjects->has($position);
                    if (!$existsHotspot) {
                        $new_hp = new Hotspot;
                        $new_hp->vrtour_id = $vrtour_id;
                        $new_hp->potision = $position;
                        $new_hp->acreage = $hp['acreage'] ?? null;
                        $new_hp->url = $hp['url'];
                        $new_hp->opacity = $hp['opacity'];
                        $new_hp->tooltip = (!str_contains($position, 'cms_eye') && !str_contains($position, 'cms_fly')) ? $hp['tooltip'] : '';
                        $new_hp->tooltip_en = $hp['tooltip_en'] ?? null;
                        $new_hp->type = str_contains($position, 'cms_') ? 1 : (str_contains($position, 'cmss_') ? 2 : 3);
                        $new_hp->user_id = Auth::id();
                        $new_hp->save();
                    }
                    if (str_starts_with($position, 'cmss_')) {
                        $link = $link_vrtour
                            . 'vista3d/search.html?search_link='
                            . $link_vrtour
                            . '?media-index=' . $media_index
                            . '&hct=HOLDER_SELECT_LANGUAGE_CN2&trigger-overlay-name=' . $position
                            . '&focus-overlay-name=' . $position
                            . '&show-overlays-names=' . $position
                            . '&skip-loading';

                        if (!$existsProject) {
                            $translations = [
                                'vi' => $hp['tooltip'] ?? null,
                                'en' => $hp['tooltip_en'] ?? null,
                            ];
                            IndustrialProject::create([
                                'project_id'  => $vrtour_id,
                                'code'        => $position,
                                'name'        => $vrtour->name,
                                'acreage'     => $hp['acreage'] ?? null,
                                'description' => $translations,
                                'link'        => $link,
                            ]);
                        } else {
                            IndustrialProject::where('project_id', $vrtour_id)->where('code', $position)->update(['link' => $link,]);
                        }
                    }
                }
                Hotspot::where('vrtour_id', $vrtour_id)->whereNotIn('potision', $positionsFromJson)->delete();
                IndustrialProject::where('project_id', $vrtour_id)->whereNotIn('code', $positionsFromJson)->delete();
                createFile('vrtour/' . $vrtour->vrtour_code, 'hotspot.js');
                $this->writeHotspotJs($vrtour);
            }
            $hotspots = Hotspot::with(['draft', 'parent', 'investmentProductStatus'])
                ->visibleFor($user)
                ->where('vrtour_id', $vrtour_id)
                ->whereIn('type', $typeFilter)
                ->when($request->status === 'none', fn($q) => $q->whereNull('investment_product_statuses_id'))
                ->when(is_numeric($request->status), fn($q) => $q->where('investment_product_statuses_id', $request->status))
                ->orderByDesc('id')
                ->paginate(15)
                ->appends([
                    'type'   => $request->type,
                    'status' => $request->status,   // giữ bộ lọc khi bấm sang trang
                ]);
            DB::commit();
            if ($hotspots->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có hotspot nào',
                    'data' => '',
                    'pagination' => '',
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => view('backend.vrtour.hotspot._rows', compact('hotspots'))->render(),
                'pagination' => $hotspots->links('pagination::bootstrap-4')->render(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại',
                'data' => '',
                'pagination' => '',
            ]);
        }
    }
    public function edit(Request $request, $hotspot_id)
    {
        if (!Gate::allows('vr_tour/hotspot')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        $hotspot = Hotspot::with(['draft','parent','user',])->findOrFail($hotspot_id);
        $industrialProject = IndustrialProject::with('productType')->where('code', $hotspot->potision)->where('project_id', $hotspot->vrtour_id)->first();
        $productType = ProductType::latest('id')->get();

        $selected = old('product_type') ?: $hotspot->product_type ?: optional($industrialProject)->product_type;

        $option_product_types = ProductType::makeOptions($productType, $selected);

        $hotspot_unit = Hotspot::makeUnitOptions($hotspot->unit);

        // Trạng thái sản phẩm đầu tư
        $selectedStatus = old('investment_product_statuses_id')
            ?: $hotspot->investment_product_statuses_id
            ?: optional($industrialProject)->investment_product_statuses_id;

        $investmentStatuses = InvestmentProductStatus::orderBy('id')->get();
        $option_investment_product_statuses = InvestmentProductStatus::makeOptions($investmentStatuses, $selectedStatus);

        $parentData = [];
        $draftData  = [];

        if ($hotspot->is_draft && $hotspot->parent) {
            $parent = $hotspot->parent;
            $fields = [
                'potision'      => 'Vị trí',
                'url'           => 'Ảnh',
                'tooltip'       => 'Tooltip (VI)',
                'tooltip_en'    => 'Tooltip (EN)',
                'acreage'       => 'Diện tích',
                'unit'          => 'Đơn vị',
                'intended_use'  => 'Mục đích sử dụng',
                'product_type'  => 'Loại sản phẩm',
                'opacity'       => 'Hiển thị',
                'investment_product_statuses_id' => 'Trạng thái sản phẩm',
                'price'               => 'Giá gốc',
                'lease_price'         => 'Giá thuê',
                'sale_price'          => 'Giá bán',
                'transaction_note'     => 'Ghi chú giao dịch',
                'available_amenities' => 'Tiện ích sẵn có',
                'contact_info'        => 'Thông tin liên hệ',
            ];

            foreach ($fields as $field => $label) {
                $parentData[$label] = (string) ($parent->$field ?? '');
                $draftData[$label]  = (string) ($hotspot->$field ?? '');
            }

            // Hiển thị tên trạng thái thay vì id trong màn hình so sánh
            $statusNames = $investmentStatuses->pluck('name', 'id');
            $parentData['Trạng thái sản phẩm'] = (string) ($statusNames[$parent->investment_product_statuses_id] ?? '');
            $draftData['Trạng thái sản phẩm']  = (string) ($statusNames[$hotspot->investment_product_statuses_id] ?? '');
        }

        return view(
            'backend.vrtour.hotspot.edit',
            compact(
                'hotspot',
                'option_product_types',
                'hotspot_unit',
                'parentData',
                'draftData',
                'option_investment_product_statuses',
                'investmentStatuses',
            )
        );
    }

    public function store(Request $request, $hotspot_id)
    {
        if (!Gate::allows('vr_tour/hotspot')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $request->validate([
            'hp_url'      => 'required',
            'hp_potision' => 'required',
            'hp_opacity'  => 'required',
            'investment_product_statuses_id' => 'nullable|exists:investment_product_statuses,id',
            'price'               => 'nullable|integer|min:0',
            'lease_price'         => 'nullable|integer|min:0',
            'sale_price'          => 'nullable|integer|min:0',
            'transaction_note'     => 'nullable|string',
            'available_amenities' => 'nullable|string',
            'contact_info'        => 'nullable|string',
        ]);

        $user = Auth::user();

        $translations = [
            'vi' => $request->hp_tooltip,
            'en' => $request->hp_tooltip_en ?? null,
        ];

        $hotspot = Hotspot::findOrFail($hotspot_id);

        if ($user->is_super_admin) {
            $main = $hotspot->parent_id ? Hotspot::findOrFail($hotspot->parent_id) : $hotspot;

            $main->potision      = $request->hp_potision;
            $main->url           = $request->hp_url;
            $main->opacity       = $request->hp_opacity;
            $main->tooltip       = $request->hp_tooltip;
            $main->tooltip_en    = $request->hp_tooltip_en;
            $main->acreage       = $request->acreage;
            $main->intended_use  = $request->intended_use;
            $main->unit          = $request->unit ?? 0;
            $main->product_type  = $request->product_type;
            $main->investment_product_statuses_id = $request->investment_product_statuses_id;
            $main->price               = $request->price;
            $main->lease_price         = $request->lease_price;
            $main->sale_price          = $request->sale_price;
            $main->transaction_note     = $request->transaction_note;
            $main->available_amenities = $request->available_amenities;
            $main->contact_info        = $request->contact_info;
            $main->user_id       = $user->id;

            $main->approval_level = $main->max_approval;
            $main->status         = 'approved';
            $main->is_draft       = 0;
            $main->parent_id      = null;

            $main->save();

            if ($main->IndustrialProject) {

                IndustrialProject::updateOrCreate(
                    [
                        'project_id' => $main->vrtour_id,
                        'code'       => $main->potision
                    ],
                    [
                        'name'         => Project::find($main->vrtour_id)->name,
                        'product_type' => $main->product_type,
                        'intended_use' => $main->intended_use,
                        'unit'         => $main->unit,
                        'acreage'      => $main->acreage,
                        'description'  => $translations,
                        'investment_product_statuses_id' => $main->investment_product_statuses_id,
                        'price'               => $main->price,
                        'lease_price'         => $main->lease_price,
                        'sale_price'          => $main->sale_price,
                        'transaction_note'     => $main->transaction_note,
                        'available_amenities' => $main->available_amenities,
                        'contact_info'        => $main->contact_info,
                    ]
                );
            }

            Hotspot::where('parent_id', $main->id)->delete();
            $this->writeHotspotJs(Project::find($main->vrtour_id));

            return redirect()
                ->to(route('backend_vrtour_hotspot_index') .
                    '?vrtour=' . $main->vrtour_id .
                    '&type=' . ($main->type))
                ->with('success', 'Đã duyệt và cập nhật Hotspot');
        }

        if (!$hotspot->is_draft) {
            $draft = Hotspot::where('parent_id', $hotspot->id)->where('is_draft', 1)->first();
            if (!$draft) {
                $draft = $hotspot->replicate();
                $draft->parent_id = $hotspot->id;
                $draft->is_draft = 1;
                $draft->status = 'pending';
            }
            $draft->approval_level = $user->is_approve ? 1 : 0;
        } else {
            $draft = $hotspot;
            $draft->status = 'pending';
            $draft->approval_level = $user->is_approve ? 1 : 0;
        }

        $draft->potision      = $request->hp_potision;
        $draft->url           = $request->hp_url;
        $draft->opacity       = $request->hp_opacity;
        $draft->tooltip       = $request->hp_tooltip;
        $draft->tooltip_en    = $request->hp_tooltip_en;
        $draft->acreage       = $request->acreage;
        $draft->intended_use  = $request->intended_use;
        $draft->unit          = $request->unit ?? 0;
        $draft->product_type = $request->product_type;
        $draft->investment_product_statuses_id = $request->investment_product_statuses_id;
        $draft->price               = $request->price;
        $draft->lease_price         = $request->lease_price;
        $draft->sale_price          = $request->sale_price;
        $draft->transaction_note     = $request->transaction_note;
        $draft->available_amenities = $request->available_amenities;
        $draft->contact_info        = $request->contact_info;
        $draft->user_id       = $user->id;

        $draft->save();

        return redirect()
            ->to(route('backend_vrtour_hotspot_index') .
                '?vrtour=' . $draft->vrtour_id .
                '&type=' . $draft->type)
            ->with(
                'success',
                $user->is_approve
                    ? 'Đã gửi duyệt cấp 2'
                    : 'Đã gửi duyệt cấp 1'
            );
    }

    public function reject(Request $request, Hotspot $hotspot)
    {
        $user = auth('web')->user();
        if (!($user->is_super_admin || $user->is_approve)) {
            abort(403, 'Bạn không có quyền từ chối hotspot.');
        }
        $hotspot->delete();
        return redirect()->route('backend_vrtour_hotspot_index', [
            "vrtour" => $hotspot->vrtour_id,
            "type" => $request->type
        ])->with(
            'success',
            'Từ chối duyệt hotspot thành công'
        );
    }
    public function approve(Request $request, Hotspot $hotspot)
    {
        $user = auth('web')->user();
        if (!($user->is_super_admin || $user->is_approve)) {
            abort(403, 'Bạn không có quyền duyệt hotspot.');
        }
        if ($user->is_super_admin) {
            if ($hotspot->parent_id) {
                $parent = Hotspot::find($hotspot->parent_id);
                if ($parent) {
                    DB::transaction(function () use (
                        $hotspot,
                        $parent
                    ) {
                        $draftData = $hotspot->toArray();
                        unset(
                            $draftData['id'],
                            $draftData['parent_id'],
                            $draftData['created_at'],
                            $draftData['updated_at']
                        );
                        $parent->fill($draftData);
                        $parent->parent_id = null;
                        $parent->is_draft = 0;
                        $parent->status = 'approved';
                        $parent->approval_level = $parent->max_approval;
                        $parent->save();
                        $translations = [
                            'vi' => $parent->tooltip,
                            'en' => $parent->tooltip_en,
                        ];
                        if ($parent->IndustrialProject) {

                            IndustrialProject::updateOrCreate(
                                [
                                    'project_id' => $parent->vrtour_id,
                                    'code'       => $parent->potision,
                                ],
                                [
                                    'name' => Project::find($parent->vrtour_id)->name,
                                    'product_type' => $parent->product_type,
                                    'intended_use' => $parent->intended_use,
                                    'unit' => $parent->unit ?? 0,
                                    'acreage' => $parent->acreage,
                                    'description' => $translations,
                                    'investment_product_statuses_id' => $parent->investment_product_statuses_id,
                                    'price'               => $parent->price,
                                    'lease_price'         => $parent->lease_price,
                                    'sale_price'          => $parent->sale_price,
                                    'transaction_note'     => $parent->transaction_note,
                                    'available_amenities' => $parent->available_amenities,
                                    'contact_info'        => $parent->contact_info,
                                ]
                            );
                        }
                        $project = Project::find($parent->vrtour_id);
                        $this->writeHotspotJs($project);

                        $hotspot->delete();
                    });

                    return redirect()->route('backend_vrtour_hotspot_index', [
                        "vrtour" => $hotspot->vrtour_id,
                        "type" => $request->type
                    ])->with(
                        'success',
                        'Duyệt hotspot thành công (Đã duyệt)'
                    );
                }
            }
        }

        if ($user->is_approve) {
            if ($hotspot->approval_level < 1) {
                $hotspot->approval_level = 1;
                $hotspot->status = 'pending';
                $hotspot->save();
            }
            return redirect()->route('backend_vrtour_hotspot_index', [
                "vrtour" => $hotspot->vrtour_id,
                "type" => $request->type
            ])->with(
                'success',
                'Đã duyệt cấp 1, chờ duyệt cấp 2'
            );
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        $user = auth('web')->user();

        $data = $request->validate([
            'vrtour_id' => 'required|integer',
            'ids'       => 'required|array|min:1|max:100',
            'ids.*'     => 'integer',
            'status_id' => 'required|integer|exists:investment_product_statuses,id',
        ], [
            'ids.required'       => 'Vui lòng chọn ít nhất một lô đất',
            'status_id.required' => 'Vui lòng chọn trạng thái',
        ]);

        $project = Project::findOrFail($data['vrtour_id']);

        // Kiểm tra phạm vi dự án của người dùng
        $scope = $user->getScope('project');
        if (!empty($scope) && !in_array($project->id, $scope)) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $status   = InvestmentProductStatus::findOrFail($data['status_id']);
        $statusId = (int) $status->id;
        $direct   = 0;   // đã ghi thẳng vào bản gốc (super admin)
        $queued   = 0;   // đã tạo/cập nhật bản nháp chờ duyệt
        $skipped  = [];

        try {
            DB::transaction(function () use ($data, $project, $user, $statusId, &$direct, &$queued, &$skipped) {
                $ids = collect($data['ids'])->map(fn($i) => (int) $i)->unique();

                // Quy về bản gốc: id nào là bản nháp thì lấy parent_id
                $requested = Hotspot::whereIn('id', $ids)->where('vrtour_id', $project->id)->get(['id', 'parent_id']);
                $rootIds = $requested->map(fn($h) => $h->parent_id ?? $h->id)->unique()->values();

                $originals = Hotspot::with('draft')->whereIn('id', $rootIds)
                    ->where('vrtour_id', $project->id)
                    ->where('type', 2)
                    ->where('is_draft', 0)
                    ->lockForUpdate()
                    ->get();

                $invalid = ($ids->count() - $requested->count()) + ($rootIds->count() - $originals->count());
                if ($invalid > 0) {
                    $skipped[] = [
                        'position' => null,
                        'reason'   => "{$invalid} mục không hợp lệ (không thuộc dự án này hoặc không phải lô đất)",
                    ];
                }

                foreach ($originals as $hotspot) {
                    $label = $this->plotLabel($hotspot);
                    $draft = $hotspot->draft;

                    // ---- Super admin: ghi thẳng bản gốc ----
                    if ($user->is_super_admin) {
                        if ($draft && $draft->status !== 'rejected') {
                            $skipped[] = ['position' => $label, 'reason' => 'Đang có bản chỉnh sửa chờ duyệt, hãy duyệt hoặc từ chối trước'];
                            continue;
                        }
                        if ((int) $hotspot->investment_product_statuses_id === $statusId) {
                            $skipped[] = ['position' => $label, 'reason' => 'Đã ở trạng thái này'];
                            continue;
                        }
                        if ($draft) {
                            $draft->delete(); // bản nháp đã bị từ chối, dọn giống hàm store
                        }

                        $hotspot->investment_product_statuses_id = $statusId;
                        $hotspot->user_id = $user->id;
                        $hotspot->save();

                        $industrial = IndustrialProject::where('project_id', $hotspot->vrtour_id)
                            ->where('code', $hotspot->potision)
                            ->first();
                        if ($industrial) {
                            $industrial->investment_product_statuses_id = $statusId;
                            $industrial->save();
                        }

                        $direct++;
                        continue;
                    }

                    // ---- Người khác: đi qua bản nháp chờ duyệt ----
                    $current = $draft
                        ? $draft->investment_product_statuses_id
                        : $hotspot->investment_product_statuses_id;

                    if ((int) $current === $statusId) {
                        $skipped[] = [
                            'position' => $label,
                            'reason'   => $draft ? 'Bản chỉnh sửa đang chờ duyệt đã có trạng thái này' : 'Đã ở trạng thái này',
                        ];
                        continue;
                    }

                    if (!$draft) {
                        $draft = $hotspot->replicate();
                        $draft->parent_id = $hotspot->id;
                        $draft->is_draft  = 1;
                    }
                    $draft->investment_product_statuses_id = $statusId;
                    $draft->status         = 'pending';
                    $draft->approval_level = $user->is_approve ? 1 : 0;
                    $draft->user_id        = $user->id;
                    $draft->save();

                    $queued++;
                }
            });
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, không có lô nào bị thay đổi',
            ], 500);
        }

        // Sinh lại hotspot.js đúng một lần, chỉ khi có bản gốc bị đổi
        $fileFailed = false;
        if ($direct > 0) {
            try {
                $this->writeHotspotJs($project);
            } catch (\Throwable $e) {
                report($e);
                $fileFailed = true;
            }
        }

        $parts = [];
        if ($direct > 0) {
            $parts[] = "Đã cập nhật {$direct} lô sang \"{$status->name}\"";
        }
        if ($queued > 0) {
            $parts[] = "Đã gửi duyệt {$queued} lô sang \"{$status->name}\" ("
                . ($user->is_approve ? 'chờ duyệt cấp 2' : 'chờ duyệt cấp 1') . ')';
        }
        if (empty($parts)) {
            $parts[] = 'Không có lô nào được thay đổi';
        }
        if (!empty($skipped)) {
            $parts[] = 'Bỏ qua ' . count($skipped) . ' mục';
        }
        if ($fileFailed) {
            $parts[] = 'nhưng chưa ghi được file hotspot.js, hãy báo kỹ thuật';
        }

        return response()->json([
            'success' => ($direct + $queued) > 0,
            'message' => implode('. ', $parts),
            'applied' => $direct,
            'queued'  => $queued,
            'skipped' => $skipped,
        ]);
    }

    private function plotLabel(Hotspot $hotspot): string
    {
        return str_starts_with($hotspot->potision, 'cmss_') ? substr($hotspot->potision, 5) : $hotspot->potision;
    }

    private function writeHotspotJs(Project $project): void
    {
        $data = Hotspot::with('investmentProductStatus')
            ->where('vrtour_id', $project->id)
            ->where('is_draft', 0)
            ->get()
            ->map(function ($h) {
                $arr = $h->toArray();
                $arr['status_color'] = optional($h->investmentProductStatus)->color_code;
                return $arr;
            });

        file_put_contents('vrtour/' . $project->vrtour_code . '/hotspot.js', $data->toJson());
    }

    public function exportExcel(Request $request)
    {
        $user = auth('web')->user();

        $data = $request->validate([
            'vrtour_id' => 'required|integer',
            'type'      => 'nullable|in:0,1,2',
            'status'    => 'nullable|string',
            'fields'    => 'required|array|min:1',
            'fields.*'  => ['string', Rule::in(array_keys(HotspotExport::FIELDS))],
        ], [
            'fields.required' => 'Vui lòng chọn ít nhất một trường cần xuất',
        ]);

        $project = Project::findOrFail($data['vrtour_id']);

        // Phạm vi dự án của người dùng
        $scope = $user->getScope('project');
        if (!empty($scope) && !in_array($project->id, $scope)) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $typeFilter = match ((int) ($data['type'] ?? 2)) {
            0 => [1, 2],
            1 => [1, 2, 3],
            default => [2],
        };
        $status = $data['status'] ?? null;

        // Xuất theo bản gốc (is_draft = 0), cùng phạm vi với hotspot.js
        $hotspots = Hotspot::with(['investmentProductStatus', 'draft'])
            ->where('vrtour_id', $project->id)
            ->where('is_draft', 0)
            ->whereIn('type', $typeFilter)
            ->when($status === 'none', fn($q) => $q->whereNull('investment_product_statuses_id'))
            ->when(is_numeric($status), fn($q) => $q->where('investment_product_statuses_id', $status))
            ->orderBy('id')
            ->get();

        $statusText = match (true) {
            $status === 'none'   => 'Chưa có trạng thái',
            is_numeric($status)  => optional(InvestmentProductStatus::find($status))->name ?? 'Tất cả',
            default              => 'Tất cả trạng thái',
        };

        $filterText = 'Dự án: ' . $project->name . ' | Trạng thái: ' . $statusText;
        $filename   = 'lo-dat_' . Str::slug($project->vrtour_code ?: $project->name) . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new HotspotExport(
                $hotspots,
                $data['fields'],
                $project->name,
                ProductType::pluck('name', 'id'),
                $user->name,
                $filterText
            ),
            $filename
        );
    }
}
