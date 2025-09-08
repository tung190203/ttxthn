<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class Project extends Model
{
    use HasFactory;
    protected $table = 'projects';

    protected $fillable = [
        'name',
        'slug',
        'short_desc',
        'description',
        'lat',
        'lng',
        'area',
        'type_number',
        'industry_number',
        'price',
        'link',
        'banner_image',
        'detail_image',
        'location_image',
        'advantage_images',
        'advantage_titles',
        'advantage_descriptions',
        'link_vrtour',
        'design_short_desc',
        'design_images',
        'design_description',
        'legal_short_desc',
        'legal_file',
        'legal_description',
        'layout_id',
        'is_invest',
        'is_pinned',
        'pin_order',
        'link_sand_table'
    ];

    const LAYOUTS = [
        1 => 'Layout 1',
        2 => 'Layout 2',
        3 => 'Layout 3',
    ];

    const PROJECTS_PER_PAGE = 9;

    public function type()
    {
        return $this->belongsTo(ProjectType::class, 'type_number', 'id');
    }
    public function industry()
    {
        return $this->belongsTo(ProjectIndustries::class, 'industry_number', 'id');
    }
    public function districts()
    {
        return $this->belongsToMany(District::class, 'project_district');
    }

    public function industrialProjects()
    {
        return $this->hasMany(IndustrialProject::class);
    }

    public static function makeListProject($selected_id = '')
    {
        $query = Project::select('id', 'name')->get();
        $html = '<option value="">-- Chọn dự án --</option>';
        foreach ($query as $project) {
            $isSelected = ($project->id == $selected_id) ? 'selected' : '';
            $html .= "<option value=\"{$project->id}\" {$isSelected}>{$project->name}</option>";
        }

        return $html;
    }

    public static function makeListProjectArray()
    {
        return Project::pluck('name', 'id')->toArray();
    }

    public function scopeInBounds($query, $minLat, $maxLat, $minLng, $maxLng)
    {
        return $query->whereBetween('lat', [$minLat, $maxLat])
            ->whereBetween('lng', [$minLng, $maxLng]);
    }

    public function scopeFilterByRequest($query, Request $request)
    {
        return $query
            ->when(
                $request->filled('type') && $request->type !== 'all',
                fn($q) => $q->where('type_number', $request->type)
            )
            ->when(
                $request->filled('industry') && $request->industry !== 'all',
                fn($q) => $q->where('industry_number', $request->industry)
            )
            ->when(
                $request->filled('search'),
                fn($q) => $q->where('name', 'like', '%' . $request->search . '%')
            )
            ->when(
                $request->has('price') && (int)$request->price > 0,
                fn($q) => $q->where(function ($sub) use ($request) {
                    $sub->whereNull('price')
                        ->orWhere('price', '<=', (int) $request->price);
                })
            )
            ->when(
                $request->filled('district'),
                fn($q) => $q->whereHas('districts', fn($q2) => $q2->where('name', $request->district))
            );
    }

    protected static function booted()
    {
        static::creating(function ($project) {
            // Chỉ tạo slug nếu chưa có
            if (empty($project->slug) && !empty($project->name)) {
                $project->slug = Str::slug($project->name);
            }
        });

        static::updating(function ($project) {
            // Ví dụ nếu muốn cập nhật lại slug khi đổi name
            if ($project->isDirty('name')) {
                $project->slug = Str::slug($project->name);
            }
        });
    }

    public static function makeOptionColumnButton(): array
    {
        $options = [
            'view' => [
                'route' => 'project_detail',
            ]
        ];

        foreach (['edit', 'delete'] as $action) {
            if (Gate::allows('post/' . $action)) {
                $options[$action] = [
                    'route' => 'backend_project_' . $action,
                ];
            }
        }

        return $options;
    }
    public static function makeListType($selected = null, $include_default = false)
    {
        $types = ProjectType::all();
        $html = '';
    
        // Nếu cần hiển thị option mặc định
        if ($include_default || $selected === null) {
            $isSelected = ($selected === null) ? 'selected' : '';
            $html .= "<option value=\"\" {$isSelected}>-- Chọn loại dự án --</option>";
        }
    
        foreach ($types as $type) {
            $isSelected = ($type->id == $selected) ? 'selected' : '';
            $html .= "<option value=\"{$type->id}\" {$isSelected}>{$type->name}</option>";
        }
    
        // Trường hợp giá trị $selected không nằm trong danh sách, hiển thị "Khác"
        if (
            $selected !== null &&
            $selected != 0 &&
            !$types->pluck('id')->contains($selected)
        ) {
            $html .= "<option value=\"{$selected}\" selected>Khác</option>";
        }
    
        return $html;
    }
    public static function makeListIndustry($selected = null, $include_default = false)
    {
        $industries = ProjectIndustries::all();
        $html = '';
    
        if ($include_default || $selected === null) {
            $isSelected = ($selected === null) ? 'selected' : '';
            $html .= "<option value=\"\" {$isSelected}>-- Chọn Ngành / Lĩnh vực --</option>";
        }
    
        foreach ($industries as $industry) {
            $isSelected = ($industry->id == $selected) ? 'selected' : '';
            $html .= "<option value=\"{$industry->id}\" {$isSelected}>{$industry->name}</option>";
        }

        if (
            $selected !== null &&
            $selected != 0 &&
            !$industries->pluck('id')->contains($selected)
        ) {
            $html .= "<option value=\"{$selected}\" selected>Khác</option>";
        }
    
        return $html;
    }

    public static function makeListDistricts()
    {
       return District::pluck('name', 'id')->toArray();
    }

    public static function makeListLayout($selected = null, $include_default = false)
    {
        $layouts = self::LAYOUTS;
    
        $html = '';
    
        if ($include_default || $selected === null) {
            $isSelected = ($selected === null) ? 'selected' : '';
            $html .= "<option value=\"\" {$isSelected}>-- Chọn Layout --</option>";
        }
    
        foreach ($layouts as $id => $name) {
            $isSelected = ($id == $selected) ? 'selected' : '';
            $html .= "<option value=\"{$id}\" {$isSelected}>{$name}</option>";
        }
    
        return $html;
    }
}
