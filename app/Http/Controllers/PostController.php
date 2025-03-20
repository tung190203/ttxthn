<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Support\Facades\App;

class PostController extends Controller
{
    public function index(Request $request, Category $category)
    {
        $language = App::getLocale();
        $clsCategory = new Category();
        $clsPost = new Post();
        $clsCategory->getParentArray();
        $cat_ids = $clsCategory->getAllCatStr($category->id);
        $cat_ids[] = (int)$category->id;

        $paginate = 15;
        $query_post = Post::with('category')
            ->where('status', 1)
            ->where('language', $language)
            ->whereIn('cat_id', $cat_ids)
            ->orderBy('priority')
            ->orderBy('id', 'desc');
        $posts = $query_post->paginate($paginate);

        $children_category = $category->getChildrenCategories();
        $parent_category = $category->getParentCategory();

        $setting = Setting::getAllSetting();
        $setting['meta_title'] = ($category->meta_title) ?: $category->name;
        $setting['meta_keywords'] = ($category->meta_keywords) ?: $setting['meta_keywords'];
        $setting['meta_description'] = ($category->meta_description) ?: $setting['meta_description'];

        return view('frontend.post.index',
            compact(
                'category',
                'posts',
                'children_category',
                'parent_category',
                'setting'
            )
        );
    }

    public function search(Request $request)
    {
        $language = App::getLocale();
        $category = new Category();
        $clsPost = new Post();

        $key = $request->get('key');

        $paginate = 8;
        $query_post = Post::with('category')
            ->where('status', 1)
            ->where('language', $language)
            ->where('name', 'like', '%' . $key . '%')
            ->orderBy('priority')
            ->orderBy('id', 'desc');
        $posts = $query_post->paginate($paginate);

        $category->name = 'Kết quả tìm kiếm cho từ khóa "' . $key . '"';
        $children_category = $category->getChildrenCategories();

        $list_post_popular = $clsPost->getListPopular(4);

        $setting = Setting::getAllSetting();
        $setting['meta_title'] = 'Search';

        return view('frontend.post.index',
            compact(
                'posts',
                'key',
                'category',
                'children_category',
                'list_post_popular',
                'setting'
            )
        );
    }

    public function detail(Request $request, $slug, $id)
    {
        /* @var $post Post */
        $post = Post::where('status', 1)
            ->where('language', App::getLocale())
            ->where('id', $id)->firstOrFail();

        $category = Category::where('id', data_get($post, 'cat_id'))->first();

        $post->increment('view_num');

        //SEO MOZ
        $setting = Setting::getAllSetting();
        $setting['meta_title'] = ($post->meta_title) ?: $post->name;
        $setting['meta_keywords'] = ($post->meta_keywords) ?: $setting['meta_keywords'];
        $setting['meta_description'] = ($post->meta_description) ?: $setting['meta_description'];
        $setting['og_image'] = ($post->image) ?: ($setting['og_image'] ?? '');

        return view('frontend.post.detail',
            compact(
                'setting',
                'post',
                'category',
            )
        );
    }
}
