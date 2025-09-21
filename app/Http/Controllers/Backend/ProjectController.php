<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Libs\DataGrid;
use App\Models\District;
use App\Models\ProjectIndustries;
use App\Models\ProjectType;
use Illuminate\Support\Arr;
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

        $query = $this->project->with(['type', 'industry', 'districts'])
        ->orderBy('is_pinned', 'desc')
        ->orderByRaw('CASE WHEN pin_order IS NULL THEN 999999 ELSE pin_order END ASC')
        ->orderBy('updated_at', 'desc');
    
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
        $paginate = 10;
        $projects = $query->paginate($paginate);
        $route_name = 'backend_project_edit';
        $option_column_button = Project::makeOptionColumnButton();

        $clsDataGrid = new DataGrid();
        $clsDataGrid->setLinkEdit($route_name);
        $clsDataGrid->addColumnLabel("name", "Tên dự án", "width='10%' nowrap");
        $clsDataGrid->addColumnImage("banner_image", "Ảnh chính", "", "width='10%' nowrap");
        $clsDataGrid->addColumnLabel("coordinates", "Tọa độ(lat/lng)", "width='10%' nowrap", 1, '', function ($col, $val, $id, $row) {
            return ($row->lat && $row->lng) ? $row->lat . ' - ' . $row->lng : '';
        });
        $clsDataGrid->addColumnLabel('area', 'Diện tích', "width='10%' nowrap", 1, '', function ($col, $val, $id, $row) {
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
        if (!Gate::allows('project/edit')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $update = $request->get('update', []);
        foreach ($update as $key => $value) {
            Project::where('id', $key)->update($value);
        }
        return redirect()->route('backend_project')->with('success', 'Cập nhật thông tin thành công');
    }

    public function edit(Project $project)
    {
        if (!Gate::allows('project/' . ($project->exists ? 'edit' : 'add'))) {
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
        if (!Gate::allows('project/' . ($project->exists ? 'edit' : 'add'))) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|max:255|unique:projects,slug,' . $project->id,
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

        // Gộp các trường array thành chuỗi bằng dấu ';'
        $fieldsToJsonEncode = ['advantage_titles', 'advantage_descs', 'design_descs', 'files_descs'];
        foreach ($fieldsToJsonEncode as $field) {
            if (isset($validated[$field]) && is_array($validated[$field])) {
            $validated[$field] = json_encode(array_map('trim', $validated[$field]));
            }
        }

        try {
            $fillableData = Arr::except($validated, ['districts']);
            $fillableData['advantage_descriptions'] = $validated['advantage_descs'] ?? '';
            $fillableData['design_description'] = $validated['design_descs'] ?? '';
            $fillableData['legal_description'] = $validated['files_descs'] ?? '';
            $project->fill($fillableData);
            $project->slug = $validated['slug'] ?: Str::slug($validated['name']);
            $project->save();

            if ($request->filled('banner_image')) {
                $project->banner_image = $request->input('banner_image');
            }
            if ($request->filled('detail_image')) {
                $project->detail_image = $request->input('detail_image');
            }
            if ($request->filled('location_image')) {
                $project->location_image = $request->input('location_image');
            }

            if ($request->filled('advantage_images') && is_array($request->advantage_images)) {
                $project->advantage_images = implode(';', array_map('trim', $request->advantage_images));
            }

            if ($request->filled('design_images') && is_array($request->design_images)) {
                $project->design_images = implode(';', array_map('trim', $request->design_images));
            }

            if( $request->filled('files_images') && is_array($request->files_images)) {
                $project->legal_file = implode(';', array_map('trim', $request->files_images));
            }

            $project->save();

            // Đồng bộ districts
            if ($request->filled('districts')) {
                $project->districts()->syncWithPivotValues(
                    $request->input('districts'),
                    ['created_at' => now(), 'updated_at' => now()]
                );
            } else {
                $project->districts()->detach();
            }

            return redirect()->route('backend_project_edit', $project)->with('success', 'Cập nhật thông tin thành công');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Lỗi khi lưu dữ liệu: ' . $e->getMessage()]);
        }
    }

    public function delete(Request $request, $id)
    {
        if (!Gate::allows('project/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $this->project->destroy($id);
        $this->project->districts()->detach($id);
        return redirect()->to(route('backend_project'))->with('success', 'Xóa dự án thành công');
    }

    public function bulkDelete(Request $request)
    {
        if (!Gate::allows('project/delete')) {
            abort(403, self::MESSAGE_UNAUTHORIZED);
        }

        $request->validate(['ids' => 'required|array']);

        $ids = $request->get('ids');
        if (empty($ids)) {
            return $this->responseJsonBadRequest();
        }

        $this->project->destroy($ids);
        $this->project->districts()->detach($ids);
        return $this->responseJsonOk();
    }
}
