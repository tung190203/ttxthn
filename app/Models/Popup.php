<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Popup extends Model
{
    protected $table = 'popups';
    protected $fillable = [
        'image',
        'link'
    ];

    public static function makeOptionColumnButton(): array
    {
        $options = [];

        foreach (['edit', 'delete'] as $action) {
            if (Gate::allows('popup/' . $action)) {
                $options[$action] = [
                    'route' => 'backend_popup_' . $action,
                ];
            }
        }

        return $options;
    }

    public function draft()
    {
        return $this->hasOne(Popup::class, 'parent_id')->where('is_draft', true);
    }

    public function parent()
    {
        return $this->belongsTo(Popup::class, 'parent_id');
    }
}
