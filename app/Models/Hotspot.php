<?php

namespace App\Models;

use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

class Hotspot extends Model
{
    protected $table = "hotspot";
    protected $fillable = [
        'vrtour_id',
        'potision',
        'acreage',
        'unit',
        'intended_use',
        'url',
        'opacity',
        'tooltip',
        'tooltip_en',
        'type',
        'user_id',
        'product_type',
        'approval_level',
        'max_approval',
        'is_draft',
        'parent_id',
        'status',
    ];

    public function draft()
    {
        return $this->hasOne(Hotspot::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Hotspot::class, 'parent_id');
    }

    public function scopeVisibleFor($query, $user)
    {
        return $query->where(function ($q) use ($user) {

            if ($user->is_super_admin || $user->is_approve) {
                $q->where(function ($sub) {
                    $sub->where('is_draft', false)
                        ->where(function ($s) {
                            $s->whereDoesntHave('draft')
                                ->orWhereHas('draft', function ($d) {
                                    $d->where('status', 'rejected');
                                });
                        });
                })
                    ->orWhere(function ($sub) {
                        $sub->where('is_draft', true)
                            ->where('status', '!=', 'rejected');
                    });
            } else {
                $q->where(function ($sub) {
                    $sub->where('is_draft', false)
                        ->whereDoesntHave('draft');
                })
                    ->orWhere(function ($sub) {
                        $sub->where('is_draft', true);
                    });
            }
        });
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'vrtour_id', 'id');
    }

    public function IndustrialProject()
    {
        return $this->hasOne(IndustrialProject::class, 'code', 'potision')->whereColumn(
            'industrial_projects.project_id',
            'hotspot.vrtour_id'
        );
    }

    public static function makeUnitOptions($selected = null)
    {
        $units = [
            0 => 'ha',
            1 => 'km'
        ];

        $html = '<option value="">-- Chọn đơn vị --</option>';

        foreach ($units as $value => $label) {
            $isSelected = ($selected !== null && $selected == $value) ? 'selected' : '';
            $html .= "<option value=\"{$value}\" {$isSelected}>{$label}</option>";
        }

        return new HtmlString($html);
    }

}
