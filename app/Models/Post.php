<?php

namespace App\Models;

use App\Libs\Util;
use App\Traits\HasGlobalScopes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

class Post extends Model
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

    public function getAllTags(): array
    {
        $language = App::getLocale();
        $posts = Post::select('meta_keywords')
            ->where('language', $language)
            ->where('meta_keywords', '<>', '')
            ->orderBy('id', 'desc')
            ->limit(100)
            ->get();
        $list_tags = [];
        foreach ($posts as $post) {
            $list_tags = array_merge($list_tags, preg_split("/\s?+,\s?+/", $post->meta_keywords));
        }

        $list_tags = array_filter($list_tags, function ($tag) {
            return !empty($tag);
        });

        return array_unique($list_tags);
    }

    public function getTags(): array
    {
        return $this->meta_keywords ? preg_split("/\s?+,\s?+/", $this->meta_keywords) : [];
    }

    public function getNextPost()
    {
        return Post::where('status', 1)
            ->where('language', App::getLocale())
            ->where('id', '>', $this->id)
            ->orderBy('id')
            ->first();
    }

    public function getPreviousPost()
    {
        return Post::where('status', 1)
            ->where('language', App::getLocale())
            ->where('id', '<', $this->id)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function scopePopular($query, $limit = 5)
    {
        return Post::with('category')
            ->select($this->getSimpleField())
            ->language()
            ->active()
            ->orderBy('view_num', 'desc')
            ->limit($limit);
    }

    public function getOtherPost($limit)
    {
        return Post::with('category')
            ->select($this->getSimpleField())
            ->where('id', '<>', $this->id)
            ->where('cat_id', $this->cat_id)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getHotPostsInCategory($limit)
    {
        return Post::with('category')
            ->select($this->getSimpleField())
            ->where('id', '<>', $this->id)
            ->where('cat_id', $this->cat_id)
            ->orderBy('view_num', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getListPostHot($limit)
    {
        $language = App::getLocale();
        return Post::with('category')->select($this->getSimpleField())
            ->where('language', $language)
            ->where('status', 1)
            ->where('is_hot', 1)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getListLatestPost($limit = 8)
    {
        $language = App::getLocale();
        return Post::with('category')
            ->select($this->getSimpleField())
            ->where('language', $language)
            ->where('status', 1)
            ->limit($limit)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getListRandomPost($limit)
    {
        $language = App::getLocale();
        return Post::with('category')
            ->select($this->getSimpleField())
            ->where('language', $language)
            ->where('status', 1)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public function getListRelatedPostByKeyword($meta_keywords, $limit = 5): Collection|array
    {
        $keywords = $meta_keywords ? preg_split("/\s?+,\s?+/", $meta_keywords) : [];
        if (empty($keywords)) {
            return [];
        }

        $query = Post::with('category')
            ->where('language', App::getLocale())
            ->where('status', 1);

        $query->where(function ($query) use ($keywords) {
            foreach ($keywords as $keyword) {
                $query->orWhere('name', 'like', "%$keyword%");
            }
        });

        return $query->orderBy('id', 'DESC')
            ->limit($limit)->get();
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

    public function getAllPostByCatID($cat_id = 0, $limit = 5)
    {
        $language = App::getLocale();
        $clsCategory = new Category();
        $clsCategory->getParentArray();
        $cat_ids = $clsCategory->getAllCatStr($cat_id);
        $cat_ids[] = (int)$cat_id;
        return Post::with('category')
            ->select($this->getSimpleField())
            ->where('status', 1)
            ->where('language', $language)
            ->whereIn('cat_id', $cat_ids)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
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
