<?php

namespace App\Http\Controllers;

use App\Libs\Util;
use App\Libs\Validate;
use App\Models\Category;
use App\Models\Post;
use App\Models\Feedback;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectIndustries;
use App\Models\ProjectType;
use App\Models\Widget;
use App\Transformers\ProjectTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $setting = Setting::getAllSetting();

        $banners = Widget::getByPosition('HOME_BANNER');
        $list_post_popular = Post::popular(4)->get();
        $all_category_coupons = Category::getAllMenuLink(0, Category::CATEGORY_TYPE_COUPON);
        $all_category_coupons = array_chunk($all_category_coupons, 3);
        $rawProjects = Project::with(['type', 'industry', 'districts'])->get();
        $projects = $rawProjects->map([ProjectTransformer::class, 'transform']);
        $types = ProjectType::all()->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
            ];
        })->toArray();
        $industries = ProjectIndustries::all()->map(function ($industry) {
            return [
                'id' => $industry->id,
                'name' => $industry->name,
            ];
        })->toArray();
        return view('frontend.home.index',
            compact(
                'setting',
                'banners',
                'list_post_popular',
                'all_category_coupons',
                'projects',
                'types',
                'industries',
            )
        );
    }

    public function projects(Request $request)
    {
        $setting = Setting::getAllSetting();
        $setting['menu_active'] = 'Dự án kêu gọi đầu tư';

        $banners = Widget::getByPosition('HOME_BANNER');
        $list_post_popular = Post::popular(4)->get();
        $all_category_coupons = Category::getAllMenuLink(0, Category::CATEGORY_TYPE_COUPON);
        $all_category_coupons = array_chunk($all_category_coupons, 3);

        return view('frontend.home.project',
            compact(
                'setting',
                'banners',
                'list_post_popular',
                'all_category_coupons',
            )
        );
    }

    public function projectDetail(Request $request)
    {
        $setting = Setting::getAllSetting();
        $setting['menu_active'] = 'Dự án kêu gọi đầu tư';

        $banners = Widget::getByPosition('HOME_BANNER');
        $list_post_popular = Post::popular(4)->get();
        $all_category_coupons = Category::getAllMenuLink(0, Category::CATEGORY_TYPE_COUPON);
        $all_category_coupons = array_chunk($all_category_coupons, 3);

        return view('frontend.home.project_detail',
            compact(
                'setting',
                'banners',
                'list_post_popular',
                'all_category_coupons',
            )
        );
    }

    public function account(Request $request)
    {
        $setting = Setting::getAllSetting();

        $banners = Widget::getByPosition('HOME_BANNER');
        $list_post_popular = Post::popular(4)->get();
        $all_category_coupons = Category::getAllMenuLink(0, Category::CATEGORY_TYPE_COUPON);
        $all_category_coupons = array_chunk($all_category_coupons, 3);

        return view('frontend.home.account',
            compact(
                'setting',
                'banners',
                'list_post_popular',
                'all_category_coupons',
            )
        );
    }

    public function news(Request $request)
    {
        $setting = Setting::getAllSetting();
        $setting['menu_active'] = 'Tin tức';

        $banners = Widget::getByPosition('HOME_BANNER');
        $list_post_popular = Post::popular(4)->get();
        $all_category_coupons = Category::getAllMenuLink(0, Category::CATEGORY_TYPE_COUPON);
        $all_category_coupons = array_chunk($all_category_coupons, 3);

        return view('frontend.home.news',
            compact(
                'setting',
                'banners',
                'list_post_popular',
                'all_category_coupons',
            )
        );
    }

    public function newDetail(Request $request)
    {
        $setting = Setting::getAllSetting();
        $setting['menu_active'] = 'Tin tức';

        $banners = Widget::getByPosition('HOME_BANNER');
        $list_post_popular = Post::popular(4)->get();
        $all_category_coupons = Category::getAllMenuLink(0, Category::CATEGORY_TYPE_COUPON);
        $all_category_coupons = array_chunk($all_category_coupons, 3);

        return view('frontend.home.new_detail',
            compact(
                'setting',
                'banners',
                'list_post_popular',
                'all_category_coupons',
            )
        );
    }

    public function jobs(Request $request)
    {
        $setting = Setting::getAllSetting();
        $job_data = $setting['job_data'] ?? [];
        $job_data = !empty($job_data) ? unserialize($job_data) : [];

        $setting['menu_active'] = '事業内容';

        return view('frontend.home.jobs',
            compact(
                'setting',
                'job_data',
            )
        );
    }


    public function page(Request $request, $slug)
    {
        $language = App::getLocale();
        $page = Page::where('slug', $slug)->where('language', $language)->firstOrFail();

        //SEO MOZ Cấu hình SEO
        $setting = Setting::getAllSetting();
        $setting['meta_title'] = $page->meta_title ?: $page->name;
        $setting['meta_description'] = $page->meta_description ?: $setting['meta_description'];
        $setting['hide_menu_pc'] = true;
        $setting['no_footer'] = true;
        $setting['menu_active'] = 'Thể lệ';

        return view('frontend.home.page', compact('page', 'setting'));

    }

    public function contactPost(Request $request)
    {
        $member_id = Auth::guard('member')->id();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'address' => 'required|string|min:5|max:255',
            'dealer_id' => 'required|numeric',
            'motorcycle_type' => 'required|string',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'name.*' => 'Họ và tên không hợp lệ.',
            'phone.*' => 'Số điện thoại không đúng.',
            'address.*' => 'Vui lòng nhập địa chỉ chính xác.',
            'dealer_id.*' => 'Đại lý không tồn tại.',
            'motorcycle_type.*' => 'Loại xe không tồn tại.',
        ]);

        if ($validator->fails()) {

            $list_error = $validator->errors()->toArray();
            return response()->json([
                'success' => false,
                'message' => array_pop($list_error)[0],
                'errors' => $validator->errors(),
            ], 422);
        }
        try {

            $feedback = new Feedback();
            $feedback->name = trim(strip_tags($request->get('name')));
            $feedback->phone = trim(strip_tags($request->get('phone')));
            $feedback->address = trim(strip_tags($request->get('address')));
            $feedback->dealer_id = intval($request->get('dealer_id'));
            $feedback->motorcycle_type = trim(strip_tags($request->get('motorcycle_type')));
            $feedback->content = trim(strip_tags($request->get('content')));
            $feedback->save();

        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage(),
                'errors' => ['error' => 'Đã xảy ra lỗi, Vui lòng thử lại sau'],
            ], 422);
        }

        return response()->json(['success' => true, 'message' => 'Lưu thông tin thành công!'], 201);
    }

    protected function validateCustomAttributes(Request $request, $validator): void
    {
        $validator->after(function ($validator) use ($request) {
            if (!Validate::validatePhoneNumber($request->get('phone'))) {
                $validator->errors()->add('phone', 'Số điện thoại không đúng');
            }
        });
    }

    public function contact(Request $request, Feedback $feedback)
    {
        $setting = Setting::getAllSetting();
        if ($request->isMethod('post')) {
            $feedback->name = $request->get('name');
            $feedback->kananame = $request->get('kananame');
            $feedback->phone = $request->get('phone');
            $feedback->email = $request->get('email');
            $feedback->content = $request->get('content');
            $feedback->save();
            return redirect()->route('contact')->with('success', __('app.contact.success'));
        }

        //SEO MOZ Cấu hình SEO
        $setting['meta_title'] = 'お問合せ';
        $setting['menu_active'] = 'お問合せ';

        return view('frontend.home.contact', compact('setting'));

    }

    public function siteMap(Request $request)
    {
        $lang_code = App::getLocale();
        $data = [];
        $time_cache = 24 * 60 * 60;

        //System
        $data['job']['loc'] = route('job_page');
        $data['job']['lastmod'] = Carbon::now();

        $data['contact']['loc'] = route('contact');
        $data['contact']['lastmod'] = Carbon::now();
//
//        //Category
//        $key_categories = 'site_map_categories_' . $lang_code;
//        $categories = Cache::remember($key_categories, $time_cache, function () use ($lang_code) {
//            return Category::where('state', 1)
//                ->where('lang_code', $lang_code)
//                ->select(['id', 'name', 'slug', 'updated_at'])->get();
//        });
//        foreach ($categories as $key_c => $category) {
//            $data['category' . $key_c]['loc'] = Util::url_category($category);
//            $data['category' . $key_c]['lastmod'] = $category->updated_at;
//        }
//
//        //Product
//        $key_products = 'site_map_products_' . $lang_code;
//        $products = Cache::remember($key_products, $time_cache, function () use ($lang_code) {
//            return Product::where('state', 1)
//                ->where('lang_code', $lang_code)
//                ->select(['id', 'name', 'slug', 'updated_at'])->get();
//        });
//        foreach ($products as $key_pr => $product) {
//            $data['product' . $key_pr]['loc'] = Util::url_product($product);
//            $data['product' . $key_pr]['lastmod'] = $product->updated_at;
//        }
//
//        //Post
//        $key_posts = 'site_map_posts_' . $lang_code;
//        $posts = Cache::remember($key_posts, $time_cache, function () use ($lang_code) {
//            return Post::where('state', 1)
//                ->where('lang_code', $lang_code)
//                ->select(['id', 'name', 'slug', 'updated_at'])->get();
//        });
//        foreach ($posts as $key_po => $post) {
//            $data['post' . $key_po]['loc'] = Util::url_post($post);
//            $data['post' . $key_po]['lastmod'] = $post->updated_at;
//        }
//
//        //Page
//        $key_pages = 'site_map_pages_' . $lang_code;
//        $pages = Cache::remember($key_pages, $time_cache, function () use ($lang_code) {
//            return Page::where('state', 1)
//                ->where('lang_code', $lang_code)
//                ->select(['id', 'name', 'slug', 'updated_at'])->get();
//        });
//        foreach ($pages as $key_pa => $page) {
//            $data['page' . $key_pa]['loc'] = Util::url_page($page);
//            $data['page' . $key_pa]['lastmod'] = $page->updated_at;
//        }
        $last_mod = data_get($data, 'category0.lastmod', Carbon::now());
//        dd($data);

        return response()->view('frontend.home.sitemap', compact('data', 'last_mod'))
            ->header('Content-Type', 'text/xml');
    }

    public function testSendMail()
    {
        $template = 'email.test';
        $data = ['name' => 'Nguyễn Đức'];
        Util::sendEmail($template, $data, 'Nguyễn Đức Test', 'huuductin1k12@gmail.com');
    }

}
