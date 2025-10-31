<?php

namespace App\Http\Controllers;

use App\Libs\Util;
use App\Libs\Validate;
use App\Models\Category;
use App\Models\Contact;
use App\Models\District;
use App\Models\IndustrialProject;
use App\Models\Post;
use App\Models\InvestmentGuide;
use App\Models\Page;
use App\Models\Popup;
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
        $list_post_popular = Post::where('published_at', '<=', Carbon::now())->popular(4)->whereNull('parent_id')->where('status_approve','approved')->get();
        $rawProjects = Project::withRelations()->whereNull('parent_id')->where('status','approved')->get();
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
        $list_projects = Project::where('industry_number', 6)->whereNull('parent_id')->where('status','approved')->get()->map(function ($project) {
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
        $posts = Post::with('interests')->where('published_at', '<=', Carbon::now())
            ->whereNull('parent_id')
            ->where('status_approve','approved')
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'name' => $post->name,
                    'slug' => $post->slug,
                    'image' => $post->image,
                    'published_at' => $post->published_at,
                    'description' => $post->description,
                    'is_interested' => $post->interests()->where('guest_id', Auth::guard('guest')->id())->exists(),
                ];
            })->toArray();
        $list_industries = ProjectIndustries::all()->map(function ($industry) {
            return [
                'id' => $industry->id,
                'name' => $industry->name,
            ];
        })->toArray();
        $filteredProjectsQuery = Project::withRelations()->whereNull('parent_id')->where('status','approved');

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
                'price' => $project->price,
                'districts' => $project->districts->pluck('name')->implode(', '),
                'is_invest' => $project->is_invest,
                'is_interested' => $project->interests()->where('guest_id', Auth::guard('guest')->id())->exists(),
            ];
        })->toArray();
        $maxPrice = $rawProjects->max('price');
        $maxPriceSp = Project::where('industry_number', 6)->where('status','approved')->max('price');
        $popups = Popup::where('status_approve', 'approved')->get();
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
                'list_industries',
                'popups'
            )
        );
    }

    public function projects(Request $request)
    {
        $setting = Setting::getAllSetting();
        $setting['menu_active'] = __('app.project_link');

        $banners = Widget::getByPosition('HOME_BANNER');
        $list_post_popular = Post::popular(Post::POSTS_TAKE)->whereNull('parent_id')->where('status_approve','approved')->where('published_at', '<=', Carbon::now())->get();

        $list_types = ProjectType::all()->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
            ];
        })->toArray();

        $list_districts = District::withCount([
            'projects as invest_count' => function ($query) {
                $query->where('is_invest', 0)->where('is_draft', 0);
            }
        ])
        ->get()
        ->map(function ($district) {
            return [
                'id' => $district->id,
                'name' => $district->name,
                'invest_count' => $district->invest_count,
            ];
        })
        ->toArray();        

        $list_industries = ProjectIndustries::withCount([
            'projects as invest_count' => function ($query) {
                $query->where('is_invest', 0)->where('is_draft', 0);
            }
        ])
        ->get()
        ->map(function ($industry) {
            return [
                'id' => $industry->id,
                'name' => $industry->name,
                'invest_count' => $industry->invest_count,
            ];
        })
        ->toArray();
        $projects = Project::withRelations()->whereNull('parent_id')->where('status','approved')
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

        $guestId = Auth::guard('guest')->id();
        $projects->getCollection()->transform(function ($item) use ($guestId) {
            $item->is_interested = $item->interests()
                ->where('guest_id', $guestId)
                ->exists();
            return $item;
        });

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

    public function industrialProjects(Request $request)
    {
        $setting = Setting::getAllSetting();
        $setting['menu_active'] = __('app.investment_products_link');
        $industrialProjects = IndustrialProject::with([
            'project:id,name',
            'hotspots',
        ])
            ->when($request->keyword, function ($query) use ($request) {
                $keyword = '%' . $request->keyword . '%';
                $query->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', $keyword)
                        ->orWhere('code', 'like', $keyword)
                        ->orWhereHas('project', function ($sub) use ($keyword) {
                            $sub->where('name', 'like', $keyword);
                        });
                });
            })
            ->when($request->project_id, function ($query) use ($request) {
                $query->where('project_id', $request->project_id);
            })
            ->orderByDesc('id')
            ->paginate(IndustrialProject::INDUSTRIAL_PROJECT_PER_PAGE)
            ->appends($request->all());

        $projects = Project::pluck('name', 'id')->toArray();

        return view('frontend.home.industrial_project', compact(
            'setting',
            'industrialProjects',
            'projects'
        ));
    }


    public function projectDetail(Request $request, $slug = null)
    {
        $setting = Setting::getAllSetting();
        $setting['menu_active'] = 'du-an-keu-goi-dau-tu';
        $locate = app()->getLocale();
        if($locate == 'vn'){
            $locate = 'vi';
        }

        $banners = Widget::getByPosition('HOME_BANNER');
        $list_post_popular = Post::popular(Post::POSTS_TAKE)->whereNull('parent_id')->where('status_approve','approved')->where('published_at', '<=', Carbon::now())
            ->orderBy('published_at', 'desc')
            ->get();

        $availableLocales = config('app.available_locales', ['vi', 'en']);
        $project = Project::with(['type', 'industry', 'districts', 'plan'])
            ->where(function ($query) use ($slug, $locate, $availableLocales) {
                $query->where("slug->{$locate}", $slug);
                foreach ($availableLocales as $loc) {
                    if ($loc !== $locate) {
                        $query->orWhere("slug->{$loc}", $slug);
                    }
                }
            })->firstOrFail();

        $preferential = collect(); // default empty
        $posts = collect();        // default empty

        $categoryId = Category::CATEGORY_TYPE_INVESTMENT_HANDBOOK;

        $categoryIds = Category::where('id', $categoryId)
            ->orWhere('parent_id', $categoryId)
            ->pluck('id');

        $guestId = Auth::guard('guest')->id();
        $preferential = InvestmentGuide::whereIn('cat_id', $categoryIds)
            ->whereNull('parent_id')
            ->where('status_approve','approved')
            ->where('published_at', '<=', Carbon::now())
            ->whereHas('projects', function ($q) use ($slug, $locate) {
                $q->where("slug->{$locate}", $slug);
            })
            ->orderBy('published_at', 'desc')
            ->get()
            ->transform(function ($item) use ($guestId) {
                $item->is_interested = $item->interests()
                    ->where('guest_id', $guestId)
                    ->exists();
                return $item;
            });

        $posts = Post::where('cat_id', Category::CATEGORY_TYPE_POST)
            ->where('published_at', '<=', Carbon::now())
            ->whereNull('parent_id')
            ->where('status_approve','approved')
            ->whereHas('projects', function ($q) use ($slug, $locate) {
                $q->where("slug->{$locate}", $slug);
            })
            ->orderByDesc('published_at')
            ->get()
            ->transform(function ($item) use ($guestId) {
                $item->is_interested = $item->interests()
                    ->where('guest_id', $guestId)
                    ->exists();
                return $item;
            });
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
            'backUrl',
            'backLabel'
        ));
    }

    public function showVrtour($slug)
    {
        $link_vrtour = Project::where('slug', $slug)->value('link_vrtour');
        return view('frontend.home.tour', compact('link_vrtour'));
    }

    public function account(Request $request)
    {
        $setting = Setting::getAllSetting();
        $user = Auth::guard('guest')->user();

        $banners = Widget::getByPosition('HOME_BANNER');
        $industries = ProjectIndustries::all()->map(fn($industry) => [
            'id' => $industry->id,
            'name' => $industry->name,
        ])->toArray();

        $list_project_interest = collect();
        $list_post_interest = [];
        if ($user) {
            $interestQuery = $user->interests()
                ->where('interestable_type', Project::class)
                ->with([
                    'interestable' => function ($q) {
                        $q->withRelations();
                    }
                ]);

            $list_project_interest = $interestQuery->get()
                ->map(function ($item) {
                    $project = $item->interestable;
                    if ($project) {
                        $project->is_interested = true;
                        $project->name = $project->name;
                        return $project;
                    }
                    return null;
                })->filter();
            if ($request->industry) {
                $list_project_interest = $list_project_interest->filter(function ($project) use ($request) {
                    return $project->industry_number == $request->industry;
                });
            }

            if ($request->keyword) {
                $keyword = mb_strtolower($request->keyword);
                $list_project_interest = $list_project_interest->filter(function ($project) use ($keyword) {
                    return str_contains(mb_strtolower($project->name), $keyword);
                });
            }

            $list_project_interest = $list_project_interest->values();

            $list_post_interest = $user->interests()
                ->where('interestable_type', Post::class)
                ->with('interestable')
                ->get()
                ->map(function ($item) {
                    $post = $item->interestable;
                    if ($post) {
                        return [
                            'id' => $post->id,
                            'name' => $post->name,
                            'slug' => $post->slug,
                            'image' => $post->image,
                            'published_at' => $post->published_at,
                            'description' => $post->description,
                            'is_interested' => true,
                        ];
                    }
                    return null;
                })
                ->filter()
                ->toArray();
        }

        return view('frontend.home.account', compact(
            'setting',
            'banners',
            'user',
            'list_project_interest',
            'list_post_interest',
            'industries'
        ));
    }
    public function introducePotential(Request $request)
    {
        $setting = Setting::getAllSetting();
        $banners = Widget::getByPosition('HOME_BANNER');
        $setting['menu_active'] = __('app.investment_guide_link');
        $locale = app()->getLocale();
        $availableLocales = config('app.available_locales', ['vi', 'en']);

        $parentCategory = Category::where('status_approve', 'approved')
            ->where('type', Category::CATEGORY_TYPE_INVESTMENT_HANDBOOK)
            ->where(function ($query) use ($setting, $locale, $availableLocales) {
                $slug = $setting['menu_active'];
                $query->where("slug->{$locale}", $slug);
                foreach ($availableLocales as $loc) {
                    if ($loc !== $locale) {
                        $query->orWhere("slug->{$loc}", $slug);
                    }
                }
            })
            ->firstOrFail();

        $subQuery = Category::where('parent_id', $parentCategory->id)->where('status_approve', 'approved');

        $subCategories = $subQuery->pluck('id')->toArray();

        $allCatIds = array_merge([$parentCategory->id], $subCategories);

        $selectedCatId = $request->get('cat_id');
        $catIds = ($selectedCatId && in_array($selectedCatId, $allCatIds)) ? [$selectedCatId] : $allCatIds;

        $query = InvestmentGuide::with('interests')->whereIn('cat_id', $catIds)
            ->whereNull('parent_id')
            ->where('status_approve','approved')
            ->where('published_at', '<=', Carbon::now())
            ->where('status', InvestmentGuide::STATUS_ACTIVE)
            ->orderBy('published_at', 'desc');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $list_investment = $query->latest()->paginate(InvestmentGuide::INVESTMENT_PER_PAGE);
        $list_investment->getCollection()->transform(function ($item) {
            $item->is_interested = $item->interests()
                ->where('guest_id', Auth::guard('guest')->id())
                ->exists();
            return $item;
        });

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
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'project_industry_id' => 'nullable|integer|exists:project_industries,id',
                'message' => 'nullable|string|max:2000',
            ], [
                'name.required' => 'Vui lòng nhập họ tên',
                'email.required' => 'Vui lòng nhập email',
                'email.email' => 'Email không hợp lệ',
                'message.required' => 'Vui lòng nhập nội dung liên hệ',
            ]);
            $contact->fill($validated);
            $contact->save();

            return redirect()->route('contact')
                ->with('success', __('app.contact.success'));
        }

        // SEO MOZ Cấu hình SEO
        $setting['meta_title'] = 'Liên hệ với chúng tôi';
        $setting['menu_active'] = __('app.contact_link');

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

    // {
    //     $setting = Setting::getAllSetting();
    //     $setting['menu_active'] = 'search';
    //     $keyword = $request->input('keyword');
    //     $type = $request->input('type', 'all');

    //     // Nếu đây là yêu cầu AJAX để lấy dữ liệu trang mới (từ JS)
    //     if ($request->ajax() && $request->filled('ajax_type')) {
    //         return $this->ajaxSearch($request);
    //     }

    //     $groupedResults = [];
    //     $perPage = 6;

    //     if ($request->filled('keyword')) {
    //         // LẤY RIÊNG từng page param (post_page, project_page, guide_page)
    //         $postPage = $request->input('post_page', 1);
    //         $projectPage = $request->input('project_page', 1);
    //         $guidePage = $request->input('guide_page', 1);

    //         // --- 1. Posts ---
    //         $posts = Post::with('interests')
    //             ->where('published_at', '<=', Carbon::now())
    //             ->where('name', 'like', '%' . $keyword . '%')
    //             ->orderBy('published_at', 'desc')
    //             ->paginate($perPage, ['*'], 'post_page', $postPage)
    //             ->appends(['keyword' => $keyword]);

    //         if ($posts->total() > 0) {
    //             $posts->through(function ($post) {
    //                 $item = $post->toArray();
    //                 $item['type'] = 'post';
    //                 $item['is_interested'] = $post->interests()->where('guest_id', Auth::guard('guest')->id())->exists();
    //                 return (object)$item;
    //             });
    //             $groupedResults['Bài viết & Tin tức'] = $posts;
    //         }

    //         // --- 2. Projects ---
    //         $projects = Project::withRelations()
    //             ->where('name', 'like', '%' . $keyword . '%')
    //             ->orderBy('updated_at', 'desc')
    //             ->paginate($perPage, ['*'], 'project_page', $projectPage)
    //             ->appends(['keyword' => $keyword]);

    //         if ($projects->total() > 0) {
    //             $projects->through(function ($project) {
    //                 $item = $project->toArray();
    //                 $item['type'] = 'project';
    //                 $item['districts'] = $project->districts;
    //                 $item['unit_type_text'] = $project->unit_type_text;
    //                 $item['is_interested'] = $project->interests()->where('guest_id', Auth::guard('guest')->id())->exists();
    //                 return (object)$item;
    //             });
    //             $groupedResults['Các Dự án Đầu tư'] = $projects;
    //         }

    //         // --- 3. Investment Guides ---
    //         $investment_guides = InvestmentGuide::with('interests')
    //             ->where('published_at', '<=', Carbon::now())
    //             ->where('name', 'like', '%' . $keyword . '%')
    //             ->orderBy('published_at', 'desc')
    //             ->paginate($perPage, ['*'], 'guide_page', $guidePage)
    //             ->appends(['keyword' => $keyword]);

    //         if ($investment_guides->total() > 0) {
    //             $investment_guides->through(function ($item) {
    //                 $data = $item->toArray();
    //                 $data['type'] = 'guide';
    //                 $data['is_interested'] = $item->interests()->where('guest_id', Auth::guard('guest')->id())->exists();
    //                 return (object)$data;
    //             });
    //             $groupedResults['Cẩm nang Đầu tư'] = $investment_guides;
    //         }
    //     }

    //     $key = $keyword ?? '';
    //     $list_module_allow_search = [
    //         'all' => 'Tất cả',
    //         'post' => 'Bài viết',
    //         'project' => 'Dự án',
    //         'guide' => 'Cẩm nang',
    //     ];

    //     return view('frontend.home.search', compact(
    //         'groupedResults',
    //         'key',
    //         'type',
    //         'list_module_allow_search',
    //         'setting'
    //     ));
    // }

    // public function search(Request $request)
    // {
    //     $setting = Setting::getAllSetting();
    //     $setting['menu_active'] = 'search';
    //     $keyword = $request->input('keyword');

    //     if ($request->ajax() && $request->filled('ajax_type')) {
    //         return $this->ajaxSearch($request);
    //     }

    //     $groupedResults = [];
    //     $perPage = 6;

    //     // --- 1. Posts ---
    //     $postPage = $request->input('post_page', 1);
    //     $posts = Post::with('interests')
    //         ->whereNull('parent_id')
    //         ->where('status_approve','approved')
    //         ->where('published_at', '<=', Carbon::now())
    //         ->when($keyword, function ($query, $keyword) {
    //             return $query->where('name', 'like', "%{$keyword}%");
    //         })
    //         ->orderBy('published_at', 'desc')
    //         ->paginate($perPage, ['*'], 'post_page', $postPage)
    //         ->appends(['keyword' => $keyword]);

    //     if ($posts->total() > 0) {
    //         $posts->through(function ($post) {
    //             $item = $post->toArray();
    //             $item['type'] = 'post';
    //             $item['is_interested'] = $post->interests()->where('guest_id', Auth::guard('guest')->id())->exists();
    //             return (object) $item;
    //         });
    //         $groupedResults[__('app.news')] = $posts;
    //     }

    //     // --- 2. Projects ---
    //     $projectPage = $request->input('project_page', 1);
    //     $projects = Project::withRelations()
    //         ->whereNull('parent_id')
    //         ->where('status','approved')
    //         ->when($keyword, function ($query, $keyword) {
    //             return $query->where('name', 'like', "%{$keyword}%");
    //         })
    //         ->orderBy('updated_at', 'desc')
    //         ->paginate($perPage, ['*'], 'project_page', $projectPage)
    //         ->appends(['keyword' => $keyword]);

    //     if ($projects->total() > 0) {
    //         $projects->through(function ($project) {
    //             $item = $project->toArray();
    //             $item['type'] = 'project';
    //             $item['districts'] = $project->districts;
    //             $item['unit_type_text'] = $project->unit_type_text;
    //             $item['is_interested'] = $project->interests()->where('guest_id', Auth::guard('guest')->id())->exists();
    //             return (object) $item;
    //         });
    //         $groupedResults[__('app.investment_projects')] = $projects;
    //     }

    //     // --- 3. Investment Guides ---
    //     $guidePage = $request->input('guide_page', 1);
    //     $investment_guides = InvestmentGuide::with('interests')
    //         ->whereNull('parent_id')
    //         ->where('status_approve','approved')
    //         ->where('published_at', '<=', Carbon::now())
    //         ->when($keyword, function ($query, $keyword) {
    //             return $query->where('name', 'like', "%{$keyword}%");
    //         })
    //         ->orderBy('published_at', 'desc')
    //         ->paginate($perPage, ['*'], 'guide_page', $guidePage)
    //         ->appends(['keyword' => $keyword]);

    //     if ($investment_guides->total() > 0) {
    //         $investment_guides->through(function ($item) {
    //             $data = $item->toArray();
    //             $data['type'] = 'guide';
    //             $data['is_interested'] = $item->interests()->where('guest_id', Auth::guard('guest')->id())->exists();
    //             return (object) $data;
    //         });
    //         $groupedResults[__('app.investment_guide')] = $investment_guides;
    //     }

    //     $key = $keyword ?? '';

    //     return view('frontend.home.search', compact(
    //         'groupedResults',
    //         'key',
    //         'setting'
    //     ));
    // }

    public function search(Request $request)
    {
        $setting = Setting::getAllSetting();
        $setting['menu_active'] = 'search';
        $keyword = $request->input('keyword');
        $locale = app()->getLocale(); // lấy ngôn ngữ hiện tại

        if ($request->ajax() && $request->filled('ajax_type')) {
            return $this->ajaxSearch($request);
        }

        $groupedResults = [];
        $perPage = 6;

        // --- 1. Posts ---
        $postPage = $request->input('post_page', 1);
        $posts = Post::with('interests')
            ->whereNull('parent_id')
            ->where('status_approve', 'approved')
            ->where('published_at', '<=', now())
            ->when($keyword, function ($query, $keyword) use ($locale) {
                return $query->where("name->{$locale}", 'like', "%{$keyword}%");
            })
            ->orderBy('published_at', 'desc')
            ->paginate($perPage, ['*'], 'post_page', $postPage)
            ->appends(['keyword' => $keyword]);

        if ($posts->total() > 0) {
            $posts->through(function ($post) {
                $item = $post->toArray();
                $item['slug'] = $post->slug;
                $item['name'] = $post->name;
                $item['description'] = $post->description;
                $item['type'] = 'post';
                $item['is_interested'] = $post->interests()->where('guest_id', Auth::guard('guest')->id())->exists();
                return (object) $item;
            });
            $groupedResults[__('app.news')] = $posts;
        }

        // --- 2. Projects ---
        $projectPage = $request->input('project_page', 1);
        $projects = Project::withRelations()
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->when($keyword, function ($query, $keyword) use ($locale) {
                return $query->where("name->{$locale}", 'like', "%{$keyword}%");
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage, ['*'], 'project_page', $projectPage)
            ->appends(['keyword' => $keyword]);

        if ($projects->total() > 0) {
            $projects->through(function ($project) {
                $item = $project->toArray();
                $item['slug'] = $project->slug;
                $item['name'] = $project->name;
                $item['type'] = 'project';
                $item['districts'] = $project->districts;
                $item['unit_type_text'] = $project->unit_type_text;
                $item['is_interested'] = $project->interests()->where('guest_id', Auth::guard('guest')->id())->exists();
                return (object) $item;
            });
            $groupedResults[__('app.investment_projects')] = $projects;
        }

        // --- 3. Investment Guides ---
        $guidePage = $request->input('guide_page', 1);
        $investment_guides = InvestmentGuide::with('interests')
            ->whereNull('parent_id')
            ->where('status_approve', 'approved')
            ->where('published_at', '<=', now())
            ->when($keyword, function ($query, $keyword) use ($locale) {
                return $query->where("name->{$locale}", 'like', "%{$keyword}%");
            })
            ->orderBy('published_at', 'desc')
            ->paginate($perPage, ['*'], 'guide_page', $guidePage)
            ->appends(['keyword' => $keyword]);

        if ($investment_guides->total() > 0) {
            $investment_guides->through(function ($item) {
                $data = $item->toArray();
                $data['slug'] = $item->slug;
                $data['name'] = $item->name;
                $data['description'] = $item->description;
                $data['type'] = 'guide';
                $data['is_interested'] = $item->interests()->where('guest_id', Auth::guard('guest')->id())->exists();
                return (object) $data;
            });
            $groupedResults[__('app.investment_guide')] = $investment_guides;
        }

        $key = $keyword ?? '';

        return view('frontend.home.search', compact(
            'groupedResults',
            'key',
            'setting'
        ));
    }

    protected function ajaxSearch(Request $request)
    {
        $keyword = $request->input('keyword');
        $ajax_type = $request->input('ajax_type');
        $perPage = 6;

        $page = $request->input($ajax_type . '_page', $request->input('page', 1));

        $query = match ($ajax_type) {
            'post' => Post::with('interests')->whereNull('parent_id')->where('status_approve','approved')->where('published_at', '<=', Carbon::now())->where('status', Post::STATUS_ACTIVE),
            'project' => Project::withRelations()->whereNull('parent_id')->where('status','approved'),
            'guide' => InvestmentGuide::with('interests')->whereNull('parent_id')->where('status_approve','approved')->where('published_at', '<=', Carbon::now())->where('status', InvestmentGuide::STATUS_ACTIVE),
            default => null,
        };

        if (!$query) {
            return response()->json(['html' => ''], 400);
        }

        $results = $query
            ->when($keyword, function ($q, $keyword) {
                return $q->where('name', 'like', "%{$keyword}%");
            })
            ->orderBy($ajax_type == 'project' ? 'updated_at' : 'published_at', 'desc')
            ->paginate($perPage, ['*'], $ajax_type . '_page', $page)
            ->appends(['keyword' => $keyword]);

        $results->through(function ($item) use ($ajax_type) {
            $data = $item->toArray();
            $data['type'] = $ajax_type;
            $data['is_interested'] = $item->interests()->where('guest_id', Auth::guard('guest')->id())->exists();

            if ($ajax_type === 'project') {
                $data['districts'] = $item->districts;
                $data['unit_type_text'] = $item->unit_type_text;
            }
            return (object) $data;
        });

        $html = view('frontend.home.partials.search_results_ajax', [
            'results' => $results,
            'type_name' => match ($ajax_type) {
                'post' => 'Tin tức',
                'project' => 'Dự án kêu gọi đầu tư',
                'guide' => 'Cẩm nang Đầu tư',
                default => 'Kết quả',
            },
        ])->render();

        return response()->json([
            'html' => $html,
        ]);
    }

}
