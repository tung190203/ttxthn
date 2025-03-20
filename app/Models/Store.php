<?php

namespace App\Models;

use App\Libs\Util;
use App\Traits\HasGlobalScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class Store extends Model
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

//        static::deleting(function ($store) {
////            Slug::deleteSlug(Slug::MODULE['POST'], $store->id);
//        });
//
//        static::saved(function ($store) {
////            Slug::insertOrUpdateSlug($store->slug, Slug::MODULE['POST'], $store->id);
//        });
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
        return Util::url_store($this);
    }

    public function scopePopular($query, $limit = 5)
    {
        return self::with('category')
            ->select($this->getSimpleField())
            ->language()
            ->active()
            ->where('is_hot', 1)
            ->orderBy('id', 'desc')
            ->limit($limit);
    }

    public function scopeGetStoreByFirstLetter($query, $letter)
    {
        $letter = strtoupper($letter ?: 'A'); // Nếu rỗng thì mặc định 'A'

        if (!preg_match('/^[A-Z]$/', $letter) && $letter !== '0-9') {
            return $query->whereRaw('1 = 0'); // Trả về truy vấn rỗng, tránh sai kiểu dữ liệu
        }

        $query->with('category')
            ->select(self::getSimpleField())
            ->language()
            ->active()
            ->orderBy('name');

        return ($letter === '0-9')
            ? $query->where('name', 'REGEXP', '^[0-9]')
            : $query->where('name', 'LIKE', "{$letter}%");
    }


    public function getOtherStore($limit)
    {
        return Store::with('category')
            ->select($this->getSimpleField())
            ->where('id', '<>', $this->id)
            ->where('cat_id', $this->cat_id)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
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

    public static function makeListStore($selected_id = '')
    {
        $stores = self::language()->orderBy('name')->get(['id', 'name']);
        $html = '';

        foreach ($stores as $store) {
            if (is_array($selected_id)) {
                $selected = in_array($store->id, $selected_id) ? 'selected' : '';
            } else {
                $selected = ($store->id == $selected_id) ? 'selected' : '';
            }
            $html .= "<option value=\"$store->id\" $selected>" . $store->name . "</option>";
        }
        return $html;

    }

    public static function makeOptionColumnButton(): array
    {
        $options = [
            'view' => [
                'route' => 'store_detail',
            ]
        ];

        foreach (['edit', 'delete', 'clone'] as $action) {
            if (Gate::allows('store/' . $action)) {
                $options[$action] = [
                    'route' => 'backend_store_' . $action,
                ];
            }
        }

        return $options;
    }
}
