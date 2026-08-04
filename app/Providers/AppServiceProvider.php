<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Models\Nation;
use App\Models\ProjectIndustries;
use App\Models\Project;
use App\Models\Post;
use App\Models\InvestmentGuide;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Popup;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        Paginator::useBootstrapFour();

        // Chỉ log hành động của người dùng, bỏ qua system (không có causer)
        \Spatie\Activitylog\Models\Activity::creating(function (\Spatie\Activitylog\Models\Activity $activity) {
            if (!$activity->causer_id) {
                return false;
            }
        });

        //Set route local
        $firstSegment = $request->segment(1);
        $availableLocales = config('app.available_locales');
        if (in_array($firstSegment, $availableLocales)) {
            $locale = $firstSegment;
        } else {
            $locale = config('app.fallback_locale');
        }

        App::setLocale($locale);

        $locale = $locale == config('app.fallback_locale') ? '' : $locale;
        // Tự động thêm prefix locale cho tất cả route
        Route::macro('localized', function ($callback) use ($availableLocales, $locale) {
            Route::group(['prefix' => $locale], function () use ($callback) {
                $callback();
            })->where(['locale' => implode('|', $availableLocales)]);
        });

        // View Composers to prevent memory exhaustion by loading models only when needed
        View::composer(['frontend.index', 'backend.guest.create'], function ($view) {
            $view->with('nations', Nation::all());
        });

        View::composer(['frontend.home.contact'], function ($view) {
            $view->with('project_industries', ProjectIndustries::orderBy('created_at', 'desc')->get());
        });

        // Group create/edit needs these global lists for permissions mapping
        View::composer(['backend.group.create'], function ($view) {
            $view->with('projects', Project::orderBy('created_at', 'desc')->where('status', 'approved')->get());
            $view->with('posts', Post::orderBy('created_at', 'desc')->where('status_approve', 'approved')->get());
            $view->with('investment_guides', InvestmentGuide::orderBy('created_at', 'desc')->where('status_approve', 'approved')->get());
            $view->with('category', Category::where('status_approve','approved')->get());
            $view->with('menus', Menu::where('status_approve','approved')->get());
            $view->with('popups', Popup::where('status_approve','approved')->get());
            $view->with('users', User::where('status_approve', 'approved')
                ->where('id', '<>', auth('web')->id())
                ->when(auth('web')->check() && !auth('web')->user()->is_super_admin, function($query) {
                    $query->where('is_super_admin', false);
                })->get());
        });

        // Sidebar View Composer
        View::composer('backend.blocks.sidebar', function ($view) {
            $user = auth('web')->user();
            if (!$user) return;
            
            $pendingCounts = [
                'project' => Project::visibleFor($user)->whereIn('status', ['pending', 'pending_delete'])->count(),
                'post' => Post::visibleFor($user)->whereIn('status_approve', ['pending', 'pending_delete'])->count(),
                'category' => Category::visibleFor($user)->whereIn('status_approve', ['pending', 'pending_delete'])->count(),
                'user' => User::visibleFor($user)->whereIn('status_approve', ['pending', 'pending_delete'])->count(),
                'popup' => Popup::visibleFor($user)->whereIn('status_approve', ['pending', 'pending_delete'])->count(),
                'menu' => Menu::visibleFor($user)->whereIn('status_approve', ['pending', 'pending_delete'])->count(),
                'investment_guide' => InvestmentGuide::visibleFor($user)->whereIn('status_approve', ['pending', 'pending_delete'])->count(),
            ];

            $view->with('pendingCounts', $pendingCounts);
        });

        // Notifications Event Listeners
        $notifyModels = [
            'project' => Project::class,
            'post' => Post::class,
            'category' => Category::class,
            'user' => User::class,
            'popup' => Popup::class,
            'menu' => Menu::class,
            'investment_guide' => InvestmentGuide::class,
        ];

        foreach ($notifyModels as $module => $class) {
            $class::saved(function ($model) use ($module) {
                // Prevent duplicate notifications during the same request if needed
                // But for now, rely on isDirty
                $statusField = $module === 'project' ? 'status' : 'status_approve';
                
                // Only trigger if status changed to pending/pending_delete
                if ($model->isDirty($statusField) || $model->wasRecentlyCreated) {
                    $status = $model->{$statusField};
                    if (in_array($status, ['pending', 'pending_delete'])) {
                        $action = $status === 'pending' ? 'chờ duyệt' : 'yêu cầu xóa';
                        
                        $moduleNames = [
                            'project' => 'Dự án',
                            'post' => 'Tin tức',
                            'category' => 'Danh mục',
                            'user' => 'Người dùng',
                            'popup' => 'Popup',
                            'menu' => 'Menu',
                            'investment_guide' => 'Cẩm nang đầu tư',
                        ];
                        $moduleNameVn = $moduleNames[$module] ?? $module;

                        $itemName = $model->name ?? ($model->title ?? 'Không tên');
                        if (is_array($itemName)) {
                            $itemName = $itemName['vi'] ?? reset($itemName);
                        }
                        $message = "Có <b>{$moduleNameVn}</b> mới đang {$action}: " . \Illuminate\Support\Str::limit($itemName, 60);
                        
                        $url = route("backend_{$module}_edit", $model->id);
                        User::notifyApprovers($module, $message, $url);
                    }
                }
            });
        }
    }
}