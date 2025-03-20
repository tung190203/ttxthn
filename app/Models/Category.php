<?php

namespace App\Models;

use App\Libs\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class Category extends Model
{
    protected $table = "categories";
    public array $parents = [];

    const int CATEGORY_TYPE_POST = 0;
    const int CATEGORY_TYPE_COUPON = 1;

    protected $fillable = ['slug', 'name', 'type'];

    const array OPTIONS_CATEGORY = [
        self::CATEGORY_TYPE_POST => 'Danh mục bài viết',
        self::CATEGORY_TYPE_COUPON => 'Danh mục coupon',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            //Slug::deleteSlug(Slug::MODULE['CATEGORY'], $category->id);
        });

        static::saved(function ($category) {
            //Slug::insertOrUpdateSlug($category->slug, Slug::MODULE['CATEGORY'], $category->id);
        });
    }

    public function getSimpleField()
    {
        return [
            'id',
            'name',
            'slug',
            'type',
            'priority',
            'parent_id',
            'image',
        ];
    }

    public function getUrl(): string
    {
        return Util::url_category($this);
    }

    public function getAll($type = -1)
    {
        $language = App::getLocale();
        $query = Category::select($this->getSimpleField())
            ->where('status', 1)
            ->where('language', $language);
        if ($type > -1) {
            $query->where('type', $type);
        }
        return $query->orderBy('type')
            ->orderBy('priority')
            ->orderBy('name')
            ->get();
    }

    public function showCategories($categories, $parent_id = 0, $char = '')
    {
        foreach ($categories as $key => $category) {
            if ($category->parent_id == $parent_id) {
                $categories1 = $categories->firstWhere('parent_id', $parent_id);
                if ($categories1) {
                    $categories1->name = $char . $categories1->name;
                    $categories[] = $categories1;
                }

                unset($categories[$key]);
                $this->showCategories($categories, data_get($category, 'id'), '&brvbar;--- ' . $char);
            }
        }
        return $categories->values();
    }

    public static function makeListCategory($parent_id = 0, $type = -1, $selected_id = '', $include_default = false)
    {
        $language = App::getLocale();
        $query = Category::where('language', $language);
        if ($type > -1) {
            $query = $query->where('type', $type);
        }
        $categories = $query->orderBy('priority')->orderBy('name')->get(['id', 'parent_id', 'name']);
        $html = '';

        if ($include_default) {
            $html .= "<option value='0'>__ROOT__</option>";
        }

        $list_categories = (new self())->showCategories($categories, $parent_id);
        foreach ($list_categories as $category) {
            if (is_array($selected_id)) {
                $selected = in_array($category->id, $selected_id) ? 'selected' : '';
            } else {
                $selected = ($category->id == $selected_id) ? 'selected' : '';
            }
            $html .= "<option value=\"$category->id\" $selected>" . $category->name . "</option>";
        }
        return $html;

    }

    public static function makeArrayListCategory($parent_id = 0, $type = -1): array
    {
        $query = Category::where('language', App::getLocale());

        if ($type > -1) {
            $query = $query->where('type', $type);
        }

        $categories = $query->orderBy('priority')->orderBy('name')->get(['id', 'parent_id', 'name']);
        $list_categories = (new self())->showCategories($categories, $parent_id, '');
        $results = [];
        if ($list_categories) {
            foreach ($list_categories as $category) {
                $results[$category->id] = $category->name;
            }
        }
        return $results;
    }

    public static function getAllMenuLink($parent_id = 0, $type = -1): array
    {
        static $cached = [];
        $cache_key = __FUNCTION__ . 'categories';
        if (isset($cached[$cache_key])) {
            $categories = $cached[$cache_key];
        } else {
            $categories = (new self)->getAll(-1);;
            $cached[$cache_key] = $categories;
        }
        $result = [];
        foreach ($categories as $key => $category) {
            if ($type > -1) {
                if ($category->type != $type) {
                    continue;
                }
            }
            $item = [];
            if ($category->parent_id == $parent_id) {
                $item['id'] = $category->id;
                $item['parent_id'] = $category->parent_id;
                $item['name'] = $category->name;
                $item['image'] = $category->image;
                $item['href'] = Util::url_category($category);
                $item['type'] = 'category';
                $item['children'] = self::getAllMenuLink($category->id, $category->type);
                $result[$key] = $item;
            }
        }
        return $result;
    }

    /**
     * @param int $cat_id
     * @return array breadcrumb
     */
    public function getAllParentCatArr(int $cat_id = 0): array
    {
        $list = [];
        $i = $cat_id;
        while ($this->parents[$i] > 0) {
            $arrCat = $this->getOneLink($this->parents[$i]);
            $list[] = $arrCat;
            $i = $this->parents[$i];
        }
        return array_reverse($list);
    }

    public function getOneLink($cat_id): array
    {
        $category = $this->find($cat_id);
        $result = [];
        $result['name'] = $category->name;
        $result['href'] = Util::url_category($category);
        return $result;
    }

    public function getParentArray(): array
    {
        if (!empty($this->parents)) {
            return $this->parents;
        }

        $list_categories = self::where('status', 1)->orderBy('priority')->select('id', 'name', 'parent_id')->get();
        if ($list_categories) {
            foreach ($list_categories as $v) {
                $this->parents[$v->id] = $v->parent_id;
            }
        }

        return $this->parents;
    }

    public function getAllCatStr($cat_id = 0): array
    {
        $arr_cat = [];
        foreach ($this->parents as $k => $v) {
            if ($v == $cat_id) {
                $arr_cat[] = $k;
                $arr_cat[] = $this->getAllCatStr($k);
            }
        }
        return Arr::flatten($arr_cat);
    }

    public static function makeOptionColumnButton(): array
    {
        $options = [];

        foreach (['edit', 'delete'] as $action) {
            if (Gate::allows('category/' . $action)) {
                $options[$action] = [
                    'route' => 'backend_category_' . $action,
                ];
            }
        }

        return $options;
    }

    //code dự án
    public function getChildrenCategories($type = self::CATEGORY_TYPE_POST)
    {
        $language = App::getLocale();

        $children = Category::where('status', 1)
            ->where('parent_id', data_get($this, 'id', 0))
            ->where('language', $language)
            ->where('type', $type)
            ->orderBy('priority', 'DESC')
            ->orderBy('name')
            ->get();

        if (!$children->count()) {
            $children = Category::where('status', 1)
                ->where('parent_id', data_get($this, 'parent_id', 0))
                ->where('type', $type)
                ->orderBy('name')
                ->get();
        }

        return $children;
    }

    public function getParentCategory()
    {
        $language = App::getLocale();

        $parent_id = data_get($this, 'parent_id', 0);

        if (!$parent_id) {
            return null;
        }

        return Category::where('status', 1)
            ->where('id', $parent_id)
            ->where('language', $language)
            ->first();
    }

    public function getCategoryPostHome($limit_post = 12)
    {
        $language = App::getLocale();
        $clsPost = new Post();
        $category = Category::where('at_home', 1)
            ->where('status', 1)->where('language', $language)
            ->where('type', Category::CATEGORY_TYPE_POST)
            ->orderBy('priority')
            ->orderBy('id', 'DESC')
            ->first();
        if ($category) {
            $category->posts = $clsPost->getAllPostByCatID($category->id, $limit_post);
        }
        return $category;
    }

    public static function getCategoryPostHomeById($category_id, $limit_post = 8)
    {
        if (!$category_id) {
            return null;
        }
        $clsPost = new Post();
        $category = Category::where('id', $category_id)->first();
        if ($category) {
            $category->posts = $clsPost->getAllPostByCatID($category->id, $limit_post);
        }
        return $category;
    }

    public function groupCategories($categories, $cat_id): array
    {
        $results = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $cat_id) {
                $results[] = $category;
            }
        }
        return $results;
    }

}
