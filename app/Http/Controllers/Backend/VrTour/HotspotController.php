<?php

namespace App\Http\Controllers\Backend\VrTour;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Project;
use App\Models\Hotspot;
use App\Models\IndustrialProject;
use App\Models\ProductType;
use Auth;
use Illuminate\Support\Facades\DB;

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
        $vrtour     = Project::all();
        return view('backend.vrtour.hotspot.index', compact(['vrtour']));
    }

    public function getHotspot(Request $request, $vrtour_id)
    {
        $html = '';
        try {
            DB::beginTransaction();
            //get tour
            $vrtour = Project::find($vrtour_id);
            $link_vrtour = $vrtour->link_vrtour;
            $media_index = $vrtour->media_index;
            //get hotspot from db
            $typeFilter = match ((int) $request->type ) {
                0 => [1, 2],
                1 => [1, 2, 3],
                2 => [2],
            };
            $user = auth()->user();

            $hotspot_db = Hotspot::with(['draft', 'parent'])
                ->visibleFor($user)
                ->where('vrtour_id', $vrtour_id)
                ->whereIn('type', $typeFilter)
                ->orderByDesc('id')
                ->get();
            if ($request->reset == 'true') {
                $response = getDataVrtour($link_vrtour . 'vista3d/hotspot.json');
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
                    if ($existsHotspot && $existsProject) {
                        continue;
                    }
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
                    if (str_contains($position, 'cmss_') && !$existsProject) {

                        $translations = [
                            'vi' => $hp['tooltip'] ?? null,
                            'en' => $hp['tooltip_en'] ?? null,
                        ];

                        IndustrialProject::create([
                            'project_id' => $vrtour_id,
                            'code'       => $position,
                            'name'       => $vrtour->name,
                            'acreage'    => $hp['acreage'] ?? null,
                            'description' => $translations,
                            'link'       => $link_vrtour . 'vista3d/search.html?search_link='
                                . $link_vrtour . '?media-index=' . $media_index
                                . '&hct=HOLDER_SELECT_LANGUAGE_CN2&trigger-overlay-name=' . $position
                                . '&focus-overlay-name=' . $position
                                . '&show-overlays-names=' . $position
                                . '&skip-loading',
                        ]);
                    }
                }
                Hotspot::where('vrtour_id', $vrtour_id)
                    ->whereNotIn('potision', $positionsFromJson)
                    ->delete();
                IndustrialProject::where('project_id', $vrtour_id)
                    ->whereNotIn('code', $positionsFromJson)
                    ->delete();
                createFile('vrtour/' . $vrtour->vrtour_code, 'hotspot.js');
                file_put_contents('vrtour/' . $vrtour->vrtour_code . '/hotspot.js', Hotspot::where('vrtour_id', $vrtour_id)->where('is_draft', 0)->get());
            }
          
            // --- KẾT THÚC KHỐI SỬA ĐỔI ---
            foreach ($hotspot_db as $key => $hp) {  
                $statusHtml = '';
                if ($hp->is_draft) {
                    $statusHtml .= "<span class='badge bg-warning'>Bản chỉnh sửa</span> ";
                }

                if ($hp->status === 'pending') {
                    if ($hp->approval_level == 0) {
                        $statusHtml .= "<span class='badge bg-secondary'>Chờ duyệt cấp 1</span>";
                    } elseif ($hp->approval_level == 1) {
                        $statusHtml .= "<span class='badge bg-primary'>Chờ duyệt cấp 2</span>";
                    }
                } elseif ($hp->status === 'approved') {
                    $statusHtml .= "<span class='badge bg-success'>Đã duyệt</span>";
                } elseif ($hp->status === 'rejected') {
                    $statusHtml .= "<span class='badge bg-danger'>Từ chối</span>";
                }
                $potision = str_starts_with($hp->potision, 'cmss_') ? substr($hp->potision, 5) : $hp->potision;
                $html .= '<tr>';
                $html .= '<td>' . (++$key) . '</td>';
                $html .= '<td><img src="' . $hp->url . '" style="width:100px;height:100px;""></td>';
                $html .= '<td>' . $potision . '</td>';
                $html .= '<td>' . $statusHtml . '</td>';
                $html .= '<td>' . $hp->tooltip . '</td>';
                $html .= '<td>' . $hp->opacity . '</td>';
                $html .= '<td class="grid_row1">';
                $html .= '<a class="btn btn-info btn-sm mr-1" href="' . route('backend_vrtour_hotspot_edit', $hp->id) . '" title="Chỉnh sửa"><i class="fas fa-pencil-alt"></i></a>';
                $html .= '</td>';
                $html .= '</tr>';
            }
            DB::commit();
            return response()->json(['data' => $html]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return response()->json(['data' => $html]);
        }
    }
    public function edit($hotspot_id)
    {
        if (!Gate::allows('vr_tour/hotspot')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        $hotspot = Hotspot::with([
            'IndustrialProject.productType',
            'draft',
            'parent'
        ])->findOrFail($hotspot_id);
        $productType = ProductType::latest('id')->get();

        $selected = old('product_type') ?: $hotspot->product_type ?: optional($hotspot->IndustrialProject)->product_type;

        $option_product_types = ProductType::makeOptions($productType, $selected);
        $hotspot_unit = Hotspot::makeUnitOptions($hotspot->unit);
        return view('backend.vrtour.hotspot.edit', compact(['hotspot', 'option_product_types','hotspot_unit']));
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
        ]);

        $user = Auth::user();

        $translations = [
            'vi' => $request->hp_tooltip,
            'en' => $request->hp_tooltip_en ?? null,
        ];

        $hotspot = Hotspot::findOrFail($hotspot_id);

        if ($user->is_super_admin) {

            $main = $hotspot->parent_id
                ? Hotspot::findOrFail($hotspot->parent_id)
                : $hotspot;

            $main->potision      = $request->hp_potision;
            $main->url           = $request->hp_url;
            $main->url_en        = $request->hp_url_en;
            $main->opacity       = $request->hp_opacity;
            $main->tooltip       = $request->hp_tooltip;
            $main->tooltip_en    = $request->hp_tooltip_en;
            $main->acreage       = $request->acreage;
            $main->intended_use  = $request->intended_use;
            $main->unit          = $request->unit ?? 0;
            $main->product_type  = $request->product_type;
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
                    ]
                );
            }

            Hotspot::where('parent_id', $main->id)->delete();

            file_put_contents(
                'vrtour/' . Project::find($main->vrtour_id)->vrtour_code . '/hotspot.js',
                Hotspot::where('vrtour_id', $main->vrtour_id)
                    ->where('is_draft', 0)
                    ->get()
            );

            return redirect()
                ->to(route('backend_vrtour_hotspot_index') .
                    '?vrtour=' . $main->vrtour_id .
                    '&type=' . ($main->type == 3 ? 1 : 0))
                ->with('success', 'Đã duyệt và cập nhật Hotspot');
        }

        if (!$hotspot->is_draft) {

            $draft = $hotspot->replicate();

            $draft->parent_id = $hotspot->id;
            $draft->is_draft = 1;
            $draft->status = 'pending';
            $draft->approval_level = $user->is_approve ? 1 : 0;
        } else {

            $draft = $hotspot;

            $draft->status = 'pending';
            $draft->approval_level = $user->is_approve ? 1 : 0;
        }

        $draft->potision      = $request->hp_potision;
        $draft->url           = $request->hp_url;
        $draft->url_en        = $request->hp_url_en;
        $draft->opacity       = $request->hp_opacity;
        $draft->tooltip       = $request->hp_tooltip;
        $draft->tooltip_en    = $request->hp_tooltip_en;
        $draft->acreage       = $request->acreage;
        $draft->intended_use  = $request->intended_use;
        $draft->unit          = $request->unit ?? 0;
        $draft->product_type = $request->product_type;
        $draft->user_id       = $user->id;

        $draft->save();

        return redirect()
            ->to(route('backend_vrtour_hotspot_index') .
                '?vrtour=' . $draft->vrtour_id .
                '&type=' . ($draft->type == 3 ? 1 : 0))
            ->with(
                'success',
                $user->is_approve
                    ? 'Đã gửi duyệt cấp 2'
                    : 'Đã gửi duyệt cấp 1'
            );
    }

    public function reject(Hotspot $hotspot)
    {
        $user = auth('web')->user();
        if (!($user->is_super_admin || $user->is_approve)) {
            abort(403, 'Bạn không có quyền từ chối hotspot.');
        }
        $hotspot->delete();
        return redirect()->route('backend_vrtour_hotspot_index')->with(
            'success',
            'Từ chối duyệt hotspot thành công'
        );
    }
    public function approve(Hotspot $hotspot)
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
                                ]
                            );
                        }
                        $project = Project::find($parent->vrtour_id);
                        file_put_contents(
                            'vrtour/' . $project->vrtour_code . '/hotspot.js',
                            Hotspot::where('vrtour_id', $parent->vrtour_id)
                                ->where('is_draft', 0)
                                ->get()
                        );

                        $hotspot->delete();
                    });

                    return redirect()->route('backend_vrtour_hotspot_index')->with(
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
            return redirect()->route('backend_vrtour_hotspot_index')->with(
                'success',
                'Đã duyệt cấp 1, chờ duyệt cấp 2'
            );
        }
    }
}
