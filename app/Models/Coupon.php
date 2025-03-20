<?php

namespace App\Models;

use App\Libs\Util;
use App\Traits\HasGlobalScopes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class Coupon extends Model
{
    use SoftDeletes;
    use HasGlobalScopes;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    const array STATUS_ARRAY = [
        0 => 'Chưa duyệt',
        1 => 'Đã duyệt',
        2 => 'Đã xóa',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
//            Slug::deleteSlug(Slug::MODULE['POST'], $post->id);
        });

        static::saved(function ($post) {
//            Slug::insertOrUpdateSlug($post->slug, Slug::MODULE['POST'], $post->id);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getUrl(): string
    {
        return Util::url_post($this);
    }

    public function getSimpleField()
    {
        return [
            'id',
            'name',
            'slug',
            'cat_id',
            'priority',
            'description',
            'image',
            'view_num',
            'created_at'
        ];
    }

    public static function makeListType($selected = ''): string
    {
        $html = '';

        foreach (['Off', 'Ship', 'Deal'] as $type) {
            $selected_attr = ($type == $selected) ? 'selected' : '';
            $html .= "<option value=\"$type\" $selected_attr>" . $type . "</option>";
        }
        return $html;
    }

    public static function makeOptionColumnButton(): array
    {
        $options = [
            'view' => [
                'route' => 'post_detail',
            ]
        ];

        foreach (['edit', 'delete', 'clone'] as $action) {
            if (Gate::allows('post/' . $action)) {
                $options[$action] = [
                    'route' => 'backend_post_' . $action,
                ];
            }
        }

        return $options;
    }
}
