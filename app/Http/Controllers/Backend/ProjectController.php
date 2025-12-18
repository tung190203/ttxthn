<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ProjectsExport;
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
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelType;

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
        ->visibleFor($user)
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
    
        $locales = config('app.locales', ['vi' => 'Tiếng Việt', 'en' => 'Tiếng Anh']);
        $firstLocale = array_key_first($locales);
        $validationRules = [];
    
        // 1. Validation cho các trường đa ngôn ngữ
        foreach (array_keys($locales) as $locale) {
            $validationRules["name.{$locale}"] = $locale === $firstLocale ? 'required|max:255' : 'nullable|max:255';
            $validationRules["slug.{$locale}"] = 'nullable|alpha_dash|max:255';
            $validationRules["short_desc.{$locale}"] = $locale === $firstLocale ? 'required' : 'nullable';
            $validationRules["description.{$locale}"] = $locale === $firstLocale ? 'required' : 'nullable';
            $validationRules["design_short_desc.{$locale}"] = 'nullable';
            $validationRules["legal_short_desc.{$locale}"] = 'nullable|string';
    
            $validationRules["advantage_titles.{$locale}"] = 'nullable|array';
            $validationRules["advantage_titles.{$locale}.*"] = 'nullable|string';
            $validationRules["advantage_descs.{$locale}"] = 'nullable|array';
            $validationRules["advantage_descs.{$locale}.*"] = 'nullable';
            $validationRules["design_descs.{$locale}"] = 'nullable|array';
            $validationRules["design_descs.{$locale}.*"] = 'nullable';
            $validationRules["files_descs.{$locale}"] = 'nullable|array';
            $validationRules["files_descs.{$locale}.*"] = 'nullable';
        }
    
        // 2. Validation cho các trường đơn ngữ
        $validationRules = array_merge($validationRules, [
            'banner_image' => 'nullable|max:2048',
            'detail_image' => 'nullable|max:2048',
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
            'design_images' => 'nullable|array',
            'files_images' => 'nullable|array',
            'link_vrtour' => 'nullable|url',
            'link_sand_table' => 'nullable|url',
            'layout_id' => 'required|integer|min:1|max:3',
            'is_invest' => 'nullable|boolean',
            'is_pinned' => 'nullable|boolean',
            'pin_order' => 'nullable|integer|min:1',
        ]);
    
        $validated = $request->validate($validationRules);
    
        // Xử lý ảnh đơn ngữ (nối chuỗi dấu chấm phẩy)
        $validated['advantage_images'] = $request->has('advantage_images') && is_array($request->advantage_images) 
            ? implode(';', array_filter(array_map('trim', $request->advantage_images))) 
            : null;
    
        $validated['design_images'] = $request->has('design_images') && is_array($request->design_images)
            ? implode(';', array_filter(array_map('trim', $request->design_images))) 
            : null;
    
        $legalFileStr = $request->has('files_images') && is_array($request->files_images)
            ? implode(';', array_filter(array_map('trim', $request->files_images))) 
            : null;
    
        // ✅ CHUẨN BỊ DATA ĐA NGÔN NGỮ (KHÔNG json_encode)
        $translatableData = [];
        foreach (array_keys($locales) as $locale) {
            $translatableData['name'][$locale] = $request->input("name.{$locale}");
            $translatableData['slug'][$locale] = $request->input("slug.{$locale}") ?: $request->input("slug.vi");
            $translatableData['short_desc'][$locale] = $request->input("short_desc.{$locale}");
            $translatableData['description'][$locale] = $request->input("description.{$locale}");
            $translatableData['design_short_desc'][$locale] = $request->input("design_short_desc.{$locale}");
            $translatableData['legal_short_desc'][$locale] = $request->input("legal_short_desc.{$locale}");
    
            // TRUYỀN MẢNG THUẦN - Spatie sẽ tự động encode khi lưu
            $translatableData['advantage_titles'][$locale] = array_values(array_filter((array)$request->input("advantage_titles.{$locale}")));
            $translatableData['advantage_descriptions'][$locale] = array_values(array_filter((array)$request->input("advantage_descs.{$locale}")));
            $translatableData['design_description'][$locale] = array_values(array_filter((array)$request->input("design_descs.{$locale}")));
            $translatableData['legal_description'][$locale] = array_values(array_filter((array)$request->input("files_descs.{$locale}")));
        }
    
        try {
            if (!$project->exists) {
                // 🟩 TẠO MỚI DỰ ÁN
                $project->fill($validated);
                $project->legal_file = $legalFileStr;
    
                foreach ($translatableData as $key => $values) {
                    $project->setTranslations($key, $values);
                }
    
                $project->approval_level = $user->is_super_admin ? 2 : ($user->is_approve ? 1 : 0);
                $project->max_approval = 2;
                $project->is_draft = false;
                $project->status = $user->is_super_admin ? 'approved' : 'pending';
    
                $slug = $project->getTranslation('slug', $firstLocale) ?: \Str::slug($project->getTranslation('name', $firstLocale));
                $originalSlug = $slug;
                $counter = 1;
                while (Project::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }
                $project->setTranslation('slug', $firstLocale, $slug);
    
                $project->save();
    
                if ($request->filled('districts')) {
                    $project->districts()->sync($request->input('districts'));
                }
    
                if (method_exists($this, 'addProjectToScope')) {
                    $this->addProjectToScope($user, $project->id);
                }
            } else {
                if ($user->is_super_admin) {
                    // 🟦 SUPER ADMIN - DUYỆT VÀ MERGE
                    $mainProject = $project->parent_id ? Project::find($project->parent_id) : $project;
    
                    $mainProject->fill($validated);
                    $mainProject->legal_file = $legalFileStr;
    
                    foreach ($translatableData as $key => $values) {
                        $mainProject->setTranslations($key, $values);
                    }
    
                    $mainProject->approval_level = $mainProject->max_approval;
                    $mainProject->status = 'approved';
                    $mainProject->is_draft = false;
                    $mainProject->parent_id = null;
    
                    $requestedSlug = $mainProject->getTranslation('slug', $firstLocale) ?: \Str::slug($mainProject->getTranslation('name', $firstLocale));
                    $slug = preg_replace('/-draft$/', '', \Str::slug($requestedSlug));
                    $originalSlug = $slug;
                    $counter = 1;
                    while (Project::where('slug', $slug)->where('id', '<>', $mainProject->id)->exists()) {
                        $slug = $originalSlug . '-' . $counter++;
                    }
                    $mainProject->setTranslation('slug', $firstLocale, $slug);
    
                    $mainProject->save();
    
                    if (isset($validated['districts'])) {
                        $mainProject->districts()->sync($validated['districts']);
                    } else {
                        $mainProject->districts()->detach();
                    }
    
                    // Xóa các bản nháp cũ
                    $drafts = Project::where('parent_id', $mainProject->id)->get();
                    foreach ($drafts as $draft) {
                        if (method_exists($this, 'removeProjectFromScope')) {
                            $this->removeProjectFromScope($draft->id);
                        }
                        $draft->delete();
                    }
                    $project = $mainProject;
                } else {
                    // 🟨 NGƯỜI DÙNG THƯỜNG / APPROVER CẤP 1
                    if ($project->status === 'approved' && !$project->is_draft) {
                        // Tạo bản nháp khi sửa bản chính đã duyệt
                        $draft = $project->replicate();
                        $draft->fill($validated);
                        $draft->legal_file = $legalFileStr;
    
                        foreach ($translatableData as $key => $values) {
                            $draft->setTranslations($key, $values);
                        }
    
                        $draft->is_draft = true;
                        $draft->status = 'pending';
                        $draft->approval_level = $user->is_approve ? 1 : 0;
                        $draft->parent_id = $project->id;
    
                        $currentSlug = $draft->getTranslation('slug', $firstLocale) ?: \Str::slug($draft->getTranslation('name', $firstLocale));
                        $draft->setTranslation('slug', $firstLocale, $currentSlug . '-draft');
    
                        $draft->save();
    
                        if ($request->filled('districts')) {
                            $draft->districts()->sync($request->input('districts'));
                        }
                        if (method_exists($this, 'addProjectToScope')) {
                            $this->addProjectToScope($user, $draft->id);
                        }
                        $project = $draft;
                    } else {
                        // Cập nhật trực tiếp trên bản nháp hoặc bản chưa duyệt
                        $project->fill($validated);
                        $project->legal_file = $legalFileStr;
    
                        foreach ($translatableData as $key => $values) {
                            $project->setTranslations($key, $values);
                        }
    
                        $project->status = 'pending';
                        $project->approval_level = $user->is_approve ? 1 : 0;
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
            return redirect()->back()->withInput()->withErrors(['error' => 'Lỗi khi lưu dữ liệu: ' . $e->getMessage()]);
        }
    }
    public $translatable = [
        'name',
        'slug',
        'short_desc',
        'description',
        'advantage_titles',
        'advantage_descriptions',
        'design_short_desc',
        'design_description',
        'legal_short_desc',
        'legal_description',
    ];

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
        $project->delete();

        return redirect()
            ->route('backend_category')
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

    public function exportCsv() {
        return Excel::download(new ProjectsExport, 'projects.xlsx', ExcelType::XLSX);
    }
}
