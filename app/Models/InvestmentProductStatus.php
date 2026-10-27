<?php

namespace App\Models;

use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

class InvestmentProductStatus extends Model
{
    protected $fillable = [
        'name',
        'color_code',
    ];



    public static function makeOptions($statuses, $selected = null)
    {
        $html = '<option value="">-- Chọn trạng thái lô đất --</option>';
        foreach ($statuses as $item) {
            $isSelected = ($item->id == $selected) ? 'selected' : '';
            $html .= "<option value=\"{$item->id}\" {$isSelected}>{$item->name}</option>";
        }
        return new HtmlString($html);
    }
}
