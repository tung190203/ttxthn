<?php

namespace App\Http\Controllers;

use App\Libs\Util;
use App\Libs\Validate;
use App\Models\Category;
use App\Models\Contact;
use App\Models\District;
use App\Models\Post;
use App\Models\InvestmentGuide;
use App\Models\Page;
use App\Models\ProductType;
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

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $setting = Setting::getAllSetting();

        $banners = Widget::getByPosition('HOME_BANNER');
        $list_post_popular = Post::where('published_at', '<=', Carbon::now())->popular(4)->get();
        $rawProjects = Project::withRelations()->get();
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
        $list_projects = Project::where('industry_number', 6)->get()->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
            ];
        })->toArray();
        $product_types = ProductType::all()->map(function ($productType) {
            return [
                'id' => $productType->id,
                'name' => $productType->name,
            ];
        })->toArray();
        $posts = Post::where('published_at', '<=', Carbon::now())
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();
        $list_industries = ProjectIndustries::all()->map(function ($industry) {
            return [
                'id' => $industry->id,
                'name' => $industry->name,
            ];
        })->toArray();
        $filteredProjectsQuery = Project::withRelations();

        if ($request->industry) {
            $filteredProjectsQuery->where('industry_number', $request->industry);
        }

        $filteredProjects = $filteredProjectsQuery->get();

        $project_category = $filteredProjects->map(function ($project) {
            return [
                'id' => $project->id,
                'detail_image' => $project->detail_image,
                'name' => $project->name,
                'slug' => $project->slug,
                'type_number' => $project->type_number,
                'industry_number' => $project->industry_number,
                'area' => $project->area,
                'unit' => $project->unit_type_text,
                'districts' => $project->districts->pluck('name')->implode(', '),
                'is_invest' => $project->is_invest,
            ];
        })->toArray();
        $maxPrice = $rawProjects->max('price');
        $maxPriceSp = Project::where('industry_number', 6)->max('price');
        return view(
            'frontend.home.index',
            compact(
                'project_category',
                'setting',
                'banners',
                'list_post_popular',
                'projects',
                'types',
                'industries',
                'list_projects',
                'product_types',
                'posts',
                'maxPrice',
                'maxPriceSp',
                'list_industries'
            )
        );
    }

    public function projects(Request $request)
    {
        $setting = Setting::getAllSetting();
        $setting['menu_active'] = 'du-an-keu-goi-dau-tu';

        $banners = Widget::getByPosition('HOME_BANNER');
        $list_post_popular = Post::popular(Post::POSTS_TAKE)->where('published_at', '<=', Carbon::now())->get();

        $list_types = ProjectType::all()->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
            ];
        })->toArray();

        $list_districts = District::all()->map(function ($district) {
            return [
                'id' => $district->id,
                'name' => $district->name,
            ];
        })->toArray();

        $list_industries = ProjectIndustries::all()->map(function ($industry) {
            return [
                'id' => $industry->id,
                'name' => $industry->name,
            ];
        })->toArray();

        $projects = Project::with(['type', 'industry', 'districts'])
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->keyword . '%');
            })
            ->when($request->type_id, function ($query) use ($request) {
                $query->where('type_number', $request->type_id);
            })
            ->when($request->district_id, function ($query) use ($request) {
                $query->whereHas('districts', function ($q) use ($request) {
                    $q->where('districts.id', $request->district_id);
                });
            })
            ->when($request->industries, function ($query) use ($request) {
                $query->whereIn('industry_number', $request->industries);
            })
            ->when(!is_null($request->is_invest), function ($query) use ($request) {
                // lọc theo dự án có/không có nhà đầu tư
                $query->where('is_invest', $request->is_invest);
            })
            ->orderBy('is_pinned', 'desc')
            ->orderByRaw('CASE WHEN pin_order IS NULL THEN 999999 ELSE pin_order END ASC')
            ->orderBy('updated_at', 'desc')
            ->paginate(Project::PROJECTS_PER_PAGE)
            ->appends($request->all());

        return view('frontend.home.project', compact(
            'projects',
            'setting',
            'banners',
            'list_post_popular',
            'list_districts',
            'list_types',
            'list_industries',
        ));
    }

    public function projectDetail(Request $request, $slug = null)
    {
        $setting = Setting::getAllSetting();
        $setting['menu_active'] = 'du-an-keu-goi-dau-tu';

        $banners = Widget::getByPosition('HOME_BANNER');
        $list_post_popular = Post::popular(Post::POSTS_TAKE)->where('published_at', '<=', Carbon::now())->get();

        $project = Project::with(['type', 'industry', 'districts', 'plan'])
            ->where('slug', $slug)
            ->firstOrFail();

        $preferential = collect(); // default empty
        $posts = collect();        // default empty

        $categoryId = Category::CATEGORY_TYPE_INVESTMENT_HANDBOOK;

        $categoryIds = Category::where('id', $categoryId)
            ->orWhere('parent_id', $categoryId)
            ->pluck('id');

        $preferential = InvestmentGuide::whereIn('cat_id', $categoryIds)
            ->where('published_at', '<=', Carbon::now())
            ->whereHas('projects', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })
            ->orderBy('published_at', 'desc')
            ->get();
        // bài viết theo tin tức của dự án
        $posts = Post::where('cat_id', Category::CATEGORY_TYPE_POST)
            ->where('published_at', '<=', Carbon::now())
            ->whereHas('projects', function ($q) use ($slug) {
                $q->where('slug', $slug);
            })->get();
            $backUrl = url()->previous();
            $backLabel = $request->get('ref');
            
            if (rtrim($backUrl, '/') === rtrim(url('/'), '/')) {
                $backUrl = null;
                $backLabel = null;
            }            

        return view('frontend.home.project_detail', compact(
            'setting',
            'banners',
            'list_post_popular',
            'preferential',
            'posts',
            'project',
            'backUrl'
            ,'backLabel'
        ));
    }

    public function account(Request $request)
    {
        $setting = Setting::getAllSetting();
        $user = Auth::guard('guest')->user();

        $banners = Widget::getByPosition('HOME_BANNER');
        $list_post_popular = Post::popular(4)->where('published_at', '<=', Carbon::now())->get();

        return view(
            'frontend.home.account',
            compact(
                'setting',
                'banners',
                'list_post_popular',
                'user'
            )
        );
    }

    public function introducePotential(Request $request)
    {
        $setting = Setting::getAllSetting();
        $banners = Widget::getByPosition('HOME_BANNER');
        $setting['menu_active'] = 'cam-nang-dau-tu';
        $parentCategory = Category::where('slug', $setting['menu_active'])
            ->where('type', Category::CATEGORY_TYPE_INVESTMENT_HANDBOOK)
            ->firstOrFail();

        $subQuery = Category::where('parent_id', $parentCategory->id);

        $subCategories = $subQuery->pluck('id')->toArray();

        $allCatIds = array_merge([$parentCategory->id], $subCategories);

        $selectedCatId = $request->get('cat_id');
        $catIds = ($selectedCatId && in_array($selectedCatId, $allCatIds)) ? [$selectedCatId] : $allCatIds;

        $query = InvestmentGuide::whereIn('cat_id', $catIds)
            ->where('published_at', '<=', Carbon::now())
            ->where('status', InvestmentGuide::STATUS_ACTIVE)
            ->orderBy('published_at', 'desc');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $list_investment = $query->latest()->paginate(InvestmentGuide::INVESTMENT_PER_PAGE);

        $childCategories = $subQuery->pluck('name', 'id');

        return view(
            'frontend.home.introduce_potential',
            compact(
                'banners',
                'setting',
                'list_investment',
                'childCategories',
                'selectedCatId'
            )
        );
    }

    public function jobs(Request $request)
    {
        $setting = Setting::getAllSetting();
        $job_data = $setting['job_data'] ?? [];
        $job_data = !empty($job_data) ? unserialize($job_data) : [];

        $setting['menu_active'] = '事業内容';

        return view(
            'frontend.home.jobs',
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

    protected function validateCustomAttributes(Request $request, $validator): void
    {
        $validator->after(function ($validator) use ($request) {
            if (!Validate::validatePhoneNumber($request->get('phone'))) {
                $validator->errors()->add('phone', 'Số điện thoại không đúng');
            }
        });
    }

    public function contact(Request $request, Contact $contact)
    {
        $setting = Setting::getAllSetting();
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name'       => 'required|string|max:255',
                'email'      => 'required|email|max:255',
                'phone'      => 'nullable|string|max:20',
                'project_industry_id' => 'nullable|integer|exists:project_industries,id',
                'message'    => 'nullable|string|max:2000',
            ], [
                'name.required'    => 'Vui lòng nhập họ tên',
                'email.required'   => 'Vui lòng nhập email',
                'email.email'      => 'Email không hợp lệ',
                'message.required' => 'Vui lòng nhập nội dung liên hệ',
            ]);
            $contact->fill($validated);
            $contact->save();

            return redirect()->route('contact')
                ->with('success', __('app.contact.success'));
        }

        // SEO MOZ Cấu hình SEO
        $setting['meta_title'] = 'Liên hệ với chúng tôi';
        $setting['menu_active'] = 'lien-he';

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
