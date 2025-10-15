<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Libs\DataGrid;
use App\Models\District;
use App\Models\Group;
use App\Models\ProjectIndustries;
use App\Models\ProjectType;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    private Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
        $this->selectedMainMenu = 'project';

        parent::__construct();

        if (!Gate::allows('project')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
    }

    public function index(Request $request)
    {
        $this->selectedSubMenu('project');
        $filter = [
            'name' => $request->get('name', ''),
            'type_number' => $request->get('type_number') !== null ? (int) $request->get('type_number') : null,
            'industry_number' => $request->get('industry_number') !== null ? (int) $request->get('industry_number') : null,
            'district_id' => $request->get('district_id') !== null ? (int) $request->get('district_id') : null,
        ];
    
        $paginate = 10;
        $user = auth('web')->user();

        $query = $this->project
        ->with(['type', 'industry', 'districts', 'draft', 'parent'])
        ->where(function ($q) {
            $q->where(function ($sub) {
                $sub->where('is_draft', false)
                    ->whereDoesntHave('draft');
            })->orWhere(function ($sub) {
                $sub->where('is_draft', true);
            });
        })
        ->orderBy('is_pinned', 'desc')
        ->orderByRaw('CASE WHEN pin_order IS NULL THEN 999999 ELSE pin_order END ASC')
        ->orderBy('updated_at', 'desc');
    
        // Áp dụng filter
        if (!empty($filter['name'])) {
            $query->where('name', 'like', '%' . $filter['name'] . '%');
        }
    
        if (!is_null($filter['type_number'])) {
            $query->where('type_number', $filter['type_number']);
        }
    
        if (!is_null($filter['industry_number'])) {
            $query->where('industry_number', $filter['industry_number']);
        }
    
        if (!is_null($filter['district_id'])) {
            $query->whereHas('districts', function ($q) use ($filter) {
                $q->where('district_id', $filter['district_id']);
            });
        }
    
        // Giới hạn theo scope quyền truy cập
        $scope = $user->getScope('project');
        if (!empty($scope)) {
            $query->whereIn('id', $scope);
        }
        $projects = $query->paginate($paginate);

        $route_name = 'backend_project_edit';
        $option_column_button = Project::makeOptionColumnButton();

        $clsDataGrid = new DataGrid();
        $clsDataGrid->setLinkEdit($route_name);
        $clsDataGrid->addColumnLabel("name", "Tên dự án", "width='10%' nowrap", 1, '', function ($col, $val, $id, $row) {
            $html = e($row->name);
    
            // Hiển thị nhãn trạng thái
            if ($row->is_draft) {
                $html .= " <span class='badge bg-warning'>Bản chỉnh sửa</span>";
            } elseif ($row->draft) {
                $html .= " <span class='badge bg-info'>Có bản nháp</span>";
            }
    
            // Hiển thị trạng thái duyệt
            if ($row->status === 'pending') {
                if ($row->approval_level == 0) $html .= " <span class='badge bg-secondary'>Chờ duyệt cấp 1</span>";
                elseif ($row->approval_level == 1) $html .= " <span class='badge bg-primary'>Chờ duyệt cấp 2</span>";
            } elseif ($row->status === 'approved') {
                $html .= " <span class='badge bg-success'>Đã duyệt</span>";
            } elseif ($row->status === 'rejected') {
                $html .= " <span class='badge bg-danger'>Từ chối</span>";
            }
    
            return $html;
        });
    
        $clsDataGrid->addColumnImage("banner_image", "Ảnh chính", "", "width='10%' nowrap");
        $clsDataGrid->addColumnLabel("coordinates", "Tọa độ(lat/lng)", "width='10%' nowrap", 1, '', function ($col, $val, $id, $row) {
            return ($row->lat && $row->lng) ? "{$row->lat} - {$row->lng}" : '';
        });
        $clsDataGrid->addColumnLabel('area', 'Giá trị', "width='10%' nowrap", 1, '', function ($col, $val, $id, $row) {
            return $row->area ? number_format($row->area) : '';
        });
        $clsDataGrid->addColumnLabel('unit', 'Đơn vị tính', "width='5%' nowrap", 1, '', function ($col, $val, $id, $row) {
            return Project::UNIT_OPTIONS[$row->unit] ?? '';
        });
        $clsDataGrid->addColumnLabel("type.name", "Loại dự án", "width='10%' nowrap");
        $clsDataGrid->addColumnLabel("industry.name", "Ngành nghề", "width='2%' nowrap");
        $clsDataGrid->addColumnLabel("districts", "Khu vực", "width='15%' nowrap", 1, '', function ($col, $val, $id, $row) {
            return collect($row->districts)->pluck('name')->implode(', ');
        });
        $clsDataGrid->addColumnDate("created_at", "Ngày tạo", "width='10%' nowrap", 'd-m-Y');
        $clsDataGrid->addColumnButton('id', '&nbsp', $option_column_button, "width='5%' nowrap ");

        $dataGrid = $clsDataGrid->showDataGrid($projects, $paginate, $projects->total());

        $types = ProjectType::pluck('name', 'id');
        $industries = ProjectIndustries::pluck('name', 'id');
        $districts = District::pluck('name', 'id');

        return view('backend.project.index', compact(
            'projects',
            'dataGrid',
            'filter',
            'types',
            'industries',
            'districts'
        ));
    }

    public function saveDataIndex(Request $request)
    {
        // foreach ($request->ids as $id) {
        //     $p = Project::find($id);
        //     if (!Gate::allows('project/edit', $p)) {
        //         abort(403);
        //     }
        // }

        $update = $request->get('update', []);
        foreach ($update as $key => $value) {
            Project::where('id', $key)->update($value);
        }
        return redirect()->route('backend_project')->with('success', 'Cập nhật thông tin thành công');
    }

    public function edit(Project $project)
    {
        if ($project->exists && !Gate::allows('project/edit', $project)) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }
        if (!$project->exists && !Gate::allows('project/add')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $option_types = Project::makeListType($project->type_number, true);
        $option_industries = Project::makeListIndustry($project->industry_number, true);
        $option_districts = Project::makeListDistricts();
        $option_layouts = Project::makeListLayout($project->layout_id, true);
        $option_units = Project::makeListUnit($project->unit, true);

        return view('backend.project.create', compact(
            'project',
            'option_types',
            'option_industries',
            'option_districts',
            'option_layouts',
            'option_units'
        ));
    }

    public function save(Project $project, Request $request)
{
    $user = auth('web')->user();

    if ($project->exists && !Gate::allows('project/edit', $project)) {
        abort(403, self::MESSAGE_UNAUTHORIZED);
    }
    if (!$project->exists && !Gate::allows('project/add')) {
        abort(403, self::MESSAGE_UNAUTHORIZED);
    }

    $validated = $request->validate([
        'name' => 'required|max:255',
        'banner_image' => 'nullable|max:2048',
        'detail_image' => 'nullable|max:2048',
        'short_desc' => 'nullable',
        'description' => 'nullable',
        'lat' => 'nullable|numeric',
        'lng' => 'nullable|numeric',
        'area' => 'nullable|numeric|min:0',
        'unit' => 'nullable|integer',
        'type_number' => 'nullable|integer|min:0|exists:project_types,id',
        'industry_number' => 'nullable|integer|min:0|exists:project_industries,id',
        'price' => 'nullable|numeric|min:0',
        'link' => 'nullable|url',
        'location_image' => 'nullable|max:2048',
        'districts' => 'nullable|array',
        'districts.*' => 'integer|exists:districts,id',
        'advantage_images' => 'nullable|array',
        'advantage_images.*' => 'nullable',
        'advantage_titles' => 'nullable|array',
        'advantage_titles.*' => 'nullable',
        'advantage_descs' => 'nullable|array',
        'advantage_descs.*' => 'nullable',
        'link_vrtour' => 'nullable|url',
        'link_sand_table' => 'nullable|url',
        'design_short_desc' => 'nullable',
        'design_images' => 'nullable|array',
        'design_images.*' => 'nullable|max:2048',
        'design_descs' => 'nullable|array',
        'design_descs.*' => 'nullable',
        'files_images' => 'nullable|array',
        'files_images.*' => 'nullable|max:10240',
        'legal_short_desc' => 'nullable|string',
        'files_descs' => 'nullable|array',
        'files_descs.*' => 'nullable',
        'layout_id' => 'required|integer|min:1|max:3',
        'is_invest' => 'nullable|boolean',
        'is_pinned' => 'nullable|boolean',
        'pin_order' => 'nullable|integer|min:1',
    ]);

    // ✅ Chuẩn hoá dữ liệu array → JSON/string để lưu
    $fieldsToJsonEncode = ['advantage_titles', 'advantage_descs', 'design_descs', 'files_descs'];
    foreach ($fieldsToJsonEncode as $field) {
        if (isset($validated[$field]) && is_array($validated[$field])) {
            $validated[$field] = json_encode(array_map('trim', $validated[$field]));
        }
    }

    // ✅ Chuẩn hoá ảnh dạng array → chuỗi nối
    if ($request->filled('advantage_images')) {
        $validated['advantage_images'] = implode(';', array_map('trim', $request->advantage_images));
    }
    if ($request->filled('design_images')) {
        $validated['design_images'] = implode(';', array_map('trim', $request->design_images));
    }
    if ($request->filled('files_images')) {
        $validated['legal_file'] = implode(';', array_map('trim', $request->files_images));
    }

    try {
        if (!$project->exists) {
            // 🟩 Tạo bản chính mới
            $project->fill($validated);
            $project->advantage_descriptions = $validated['advantage_descs'] ?? null;
            $project->design_description = $validated['design_descs'] ?? null;
            $project->legal_description = $validated['files_descs'] ?? null;
            $project->approval_level = $user->is_super_admin ? 2 : ($user->is_approve ? 1 : 0);
            $project->max_approval = 2;
            $project->is_draft = false;
            $project->status = $user->is_super_admin ? 'approved' : ($user->is_approve ? 'pending' : 'pending');

            // Tạo slug unique
            $slug = Str::slug($project->name);
            $originalSlug = $slug;
            $counter = 1;
            while (Project::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
            $project->slug = $slug;
            $project->save();

            if($request->filled('districts')) {
                $project->districts()->sync($request->input('districts'));
            }

            if (Gate::allows('project/add')) {
                $this->addProjectToScope($user, $project->id);
            }
        } else {
            if ($user->is_super_admin) {
                // 🟦 Super admin merge nháp vào bản chính
                $mainProject = $project->parent_id ? Project::find($project->parent_id) : $project;

                // Merge dữ liệu validated (ưu tiên data mới)
                $mainProject->fill($validated);
                $mainProject->advantage_descriptions = $validated['advantage_descs'] ?? $mainProject->advantage_descriptions;
                $mainProject->design_description = $validated['design_descs'] ?? $mainProject->design_description;
                $mainProject->legal_description = $validated['files_descs'] ?? $mainProject->legal_description;

                // Reset duyệt
                $mainProject->approval_level = $mainProject->max_approval;
                $mainProject->status = 'approved';
                $mainProject->is_draft = false;
                $mainProject->parent_id = null;

                // Slug unique
                $slug = preg_replace('/-draft$/', '', Str::slug($mainProject->name));
                $originalSlug = $slug;
                $counter = 1;
                while (Project::where('slug', $slug)->where('id', '<>', $mainProject->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }
                $mainProject->slug = $slug;

                $mainProject->save();

                // Đồng bộ districts
                if (isset($validated['districts'])) {
                    $mainProject->districts()->sync($validated['districts']);
                } else {
                    $mainProject->districts()->detach();
                }

                // Xoá toàn bộ nháp cũ
                $drafts = Project::where('parent_id', $mainProject->id)->get();
                foreach ($drafts as $draft) {
                    $this->removeProjectFromScope($draft->id);
                    $draft->delete();
                }

                $project = $mainProject;
            } else {
                // 🟨 Người dùng thường
                if ($project->status === 'approved' && !$project->is_draft) {
                    // Bản chính đã duyệt → tạo nháp mới
                    $draft = $project->replicate();
                    $draft->fill($validated);
                    $draft->is_draft = true;
                    $draft->status = 'pending';
                    $draft->approval_level = $user->is_approve ? 1 : 0;
                    $draft->parent_id = $project->id;
                    $draft->slug = Str::slug($draft->name) . '-draft';
                    $draft->save();

                    if ($request->filled('districts')) {
                        $draft->districts()->sync($request->input('districts'));
                    }

                    if (Gate::allows('project/add')) {
                        $this->addProjectToScope($user, $draft->id);
                    }

                    $project = $draft;
                } else {
                    // Cập nhật bản hiện tại (nháp/chưa duyệt)
                    $project->fill($validated);
                    $project->advantage_descriptions = $validated['advantage_descs'] ?? $project->advantage_descriptions;
                    $project->design_description = $validated['design_descs'] ?? $project->design_description;
                    $project->legal_description = $validated['files_descs'] ?? $project->legal_description;
                    $project->save();

                    if ($request->filled('districts')) {
                        $project->districts()->sync($request->input('districts'));
                    } else {
                        $project->districts()->detach();
                    }
                }
            }
        }

        return redirect()
            ->route('backend_project_edit', $project)
            ->with('success', 'Lưu dữ liệu thành công ' . (
                $user->is_super_admin ? '(Đã duyệt)' : ($user->is_approve ? '(Chờ duyệt cấp 2)' : '')
            ));
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Lỗi khi lưu dữ liệu: ' . $e->getMessage()]);
    }
}

    public function approve(Project $project)
    {
        $user = auth('web')->user();

        if (!($user->is_super_admin || $user->is_approve)) {
            abort(403, 'Bạn không có quyền duyệt dự án.');
        }
    
        if ($user->is_super_admin) {
            $project->approval_level = $project->max_approval;
            $project->status = 'approved';
            $project->is_draft = false;
    
            if ($project->parent_id) {
                $parent = Project::find($project->parent_id);
                if ($parent) {
                    $draftData = $project->toArray();
                    $draftDistricts = $project->districts->pluck('id')->toArray();

                    $this->removeProjectFromScope($project->id);
                    $project->delete();

                    $parent->fill($draftData);

                    $parent->parent_id = null;
                    $parent->is_draft = false;
                    $parent->status = 'approved';
                    $parent->approval_level = $parent->max_approval;

                    $slug = Str::slug($parent->name);
                    $originalSlug = $slug;
                    $counter = 1;
                    while (Project::where('slug', $slug)->where('id', '<>', $parent->id)->exists()) {
                        $slug = $originalSlug . '-' . $counter;
                        $counter++;
                    }
                    $parent->slug = $slug;

                    $parent->save();

                    $parent->districts()->sync($draftDistricts);

                    $project = $parent;
                }
            }
        } elseif ($user->is_approve) {
            if ($project->approval_level < 1) {
                $project->approval_level = 1;
                $project->status = 'pending';
            }
        }
    
        $project->save();

        return redirect()
            ->route('backend_project_edit', $project->id)
            ->with('success', 'Duyệt dự án thành công ' . ($user->is_super_admin ? '(Đã duyệt)' : '(Chờ duyệt cấp 2)'));
    }

    public function reject(Project $project)
    {
        $user = auth('web')->user();

        if (!($user->is_super_admin || $user->is_approve)) {
            abort(403, 'Bạn không có quyền từ chối duyệt dự án.');
        }

        $project->status = 'rejected';
        $project->save();

        return redirect()
            ->route('backend_category_edit', ['project' => $project->id])
            ->with('success', 'Từ chối duyệt dự án thành công');
    }

    protected function addProjectToScope($user, $projectId)
    {
        $group = Group::find($user->group_id);
        if (!$group) return;

        $scopeData = $group->scope_data ?? [];
        $resource = 'project';

        if (empty($scopeData[$resource])) {
            return;
        }

        if (!in_array((string) $projectId, $scopeData[$resource])) {
            $scopeData[$resource][] = (string) $projectId;
            $group->scope_data = $scopeData;
            $group->save();
        }
    }

    protected function removeProjectFromScope($projectId)
    {
        $groups = Group::whereJsonContains('scope_data->project', (string)$projectId)->get();

        foreach ($groups as $group) {
            $scopeData = $group->scope_data ?? [];
    
            if (!isset($scopeData['project']) || !is_array($scopeData['project'])) {
                continue;
            }

            if (empty($scopeData['project'])) {
                continue;
            }

            $scopeData['project'] = array_values(array_filter(
                $scopeData['project'],
                fn($id) => (string)$id !== (string)$projectId
            ));
    
            $group->scope_data = $scopeData;
            $group->save();
        }
    }

    public function delete(Request $request, $id)
    {
        $project = Project::find($id);
        if (!Gate::allows('project/delete',$project)) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $this->project->destroy($id);
        $project->districts()->detach();
        $this->removeProjectFromScope($id);

        return redirect()->route('backend_project')->with('success', 'Xóa dự án thành công');
    }

    public function bulkDelete(Request $request)
    {
        foreach ($request->ids as $id) {
            $p = Project::find($id);
            if (!Gate::allows('project/delete', $p)) {
                abort(403);
            }
        }

        $request->validate(['ids' => 'required|array']);
        $ids = $request->get('ids');
        if (empty($ids)) {
            return response()->json(['status' => 'bad_request'], 400);
        }

        $this->project->destroy($ids);
        $this->project->districts()->detach($ids);
        foreach ($ids as $id) {
            $this->removeProjectFromScope($id);
        }

        return response()->json(['status' => 'ok']);
    }
}
