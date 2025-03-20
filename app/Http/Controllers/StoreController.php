<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Category;
use Illuminate\Support\Facades\App;

class StoreController extends Controller
{
    public function all(Request $request)
    {
        $letter = $request->get('letter', '');
        $list_store_popular = Store::popular(54)->get();
        $list_store_by_letter = Store::getStoreByFirstLetter($letter)->get();

        $setting = Setting::getAllSetting();

        return view('frontend.store.all',
            compact(
                'list_store_popular',
                'letter',
                'list_store_by_letter',
                'setting'
            )
        );
    }

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

        $list_post_popular = $clsPost->getListPopular(6);

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
                'list_post_popular',
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

    public function detail(Request $request, $slug)
    {
        /* @var $store Store */
        $store = Store::where('status', 1)
            ->language()
            ->where('slug', $slug)->firstOrFail();

        $category = Category::where('id', data_get($store, 'cat_id'))->first();

        $store->increment('view_num');

        //SEO MOZ
        $setting = Setting::getAllSetting();
        $setting['meta_title'] = ($store->meta_title) ?: $store->name;
        $setting['meta_keywords'] = ($store->meta_keywords) ?: $setting['meta_keywords'];
        $setting['meta_description'] = ($store->meta_description) ?: $setting['meta_description'];
        $setting['og_image'] = ($store->image) ?: ($setting['og_image'] ?? '');

        return view('frontend.store.detail',
            compact(
                'setting',
                'store',
                'category',
            )
        );
    }
}
