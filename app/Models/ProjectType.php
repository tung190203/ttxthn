<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectType extends Model
{
    use HasFactory;
    protected $table = 'project_types';

    public function projects()
    {
        return $this->hasMany(Project::class, 'type_number', 'id');
    }

    public static function makeListProjectType($selected_id = '')
    {
        $query = ProjectType::select('id', 'name')->get();
        $html = '<option value="">-- Chọn dự án --</option>';
        foreach ($query as $project_type) {
            $isSelected = ($project_type->id == $selected_id) ? 'selected' : '';
            $html .= "<option value=\"{$project_type->id}\" {$isSelected}>{$project_type->name}</option>";
        }

        return $html;
    }
}
