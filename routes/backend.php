<?php

use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\MemberController;
use App\Http\Controllers\Backend\PopupController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\FeedbackController;
use App\Http\Controllers\Backend\FileManagerController;
use App\Http\Controllers\Backend\LandingPageController;
use App\Http\Controllers\Backend\ProjectController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\WidgetController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\GroupController;
use App\Http\Controllers\Backend\GuestController;
use App\Http\Controllers\Backend\InvestMentGuideController;
use App\Http\Controllers\Backend\VrTour\SkinController;
use App\Http\Controllers\Backend\VrTour\HotspotController;
use App\Http\Controllers\Backend\VrTour\ContentController;
use App\Models\InvestmentGuide;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\AIChatMonitorController;
use Illuminate\Support\Facades\Route;

Route::localized(function () {
    Route::get('/backend', function () {
        return redirect()->route('backend_dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::prefix('backend')->middleware(['auth', 'can:backend_access'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('backend_dashboard');
        Route::post('/profile/update', [ProfileController::class, 'update'])->name('backend.profile.update');

        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('backend_category');
            Route::post('/', [CategoryController::class, 'saveDataIndex'])->name('backend_category_save_data_index');
            Route::get('/create', [CategoryController::class, 'edit'])->name('backend_category_create');
            Route::get('/edit/{category}', [CategoryController::class, 'edit'])->name('backend_category_edit');
            Route::post('/save/{category?}', [CategoryController::class, 'save'])->name('backend_category_save');
            Route::get('/delete/{id}', [CategoryController::class, 'delete'])->name('backend_category_delete');
            Route::post('/bulk_delete', [CategoryController::class, 'bulkDelete'])->name('backend_category_bulk_delete');
            Route::post('approve/{category}', [CategoryController::class, 'approve'])->name('backend_category_approve');
            Route::post('/reject/{category}', [CategoryController::class, 'reject'])->name('backend_category_reject');
        });

        Route::prefix('menu')->group(function () {
            Route::get('/', [MenuController::class, 'index'])->name('backend_menu');
            Route::post('/', [MenuController::class, 'saveDataIndex'])->name('backend_menu_save_data_index');
            Route::get('/create', [MenuController::class, 'edit'])->name('backend_menu_create');
            Route::get('/edit/{menu}', [MenuController::class, 'edit'])->name('backend_menu_edit');
            Route::post('/save/{menu?}', [MenuController::class, 'save'])->name('backend_menu_save');
            Route::get('/delete/{id}', [MenuController::class, 'delete'])->name('backend_menu_delete');
            Route::post('/bulk_delete', [MenuController::class, 'bulkDelete'])->name('backend_menu_bulk_delete');
            Route::post('approve/{menu}', [MenuController::class, 'approve'])->name('backend_menu_approve');
            Route::post('/reject/{menu}', [MenuController::class, 'reject'])->name('backend_menu_reject');
        });

        Route::prefix('page')->group(function () {
            Route::get('/', [PageController::class, 'index'])->name('backend_page');
            Route::post('/', [PageController::class, 'saveDataIndex'])->name('backend_page_save_data_index');
            Route::get('/create', [PageController::class, 'edit'])->name('backend_page_create');
            Route::get('/edit/{page}', [PageController::class, 'edit'])->name('backend_page_edit');
            Route::post('/save/{page?}', [PageController::class, 'save'])->name('backend_page_save');
            Route::get('/delete/{id}', [PageController::class, 'delete'])->name('backend_page_delete');
            Route::post('/bulk_delete', [PageController::class, 'bulkDelete'])->name('backend_page_bulk_delete');
        });

        Route::prefix('popup')->group(function () {
            Route::get('/', [PopupController::class, 'index'])->name('backend_popup');
            Route::post('/', [PopupController::class, 'saveDataIndex'])->name('backend_popup_save_data_index');
            Route::get('/create', [PopupController::class, 'edit'])->name('backend_popup_create');
            Route::get('/edit/{popup}', [PopupController::class, 'edit'])->name('backend_popup_edit');
            Route::post('/save/{popup?}', [PopupController::class, 'save'])->name('backend_popup_save');
            Route::get('/delete/{id}', [PopupController::class, 'delete'])->name('backend_popup_delete');
            Route::post('/bulk_delete', [PopupController::class, 'bulkDelete'])->name('backend_popup_bulk_delete');
            Route::post('approve/{popup}', [PopupController::class, 'approve'])->name('backend_popup_approve');
            Route::post('/reject/{popup}', [PopupController::class, 'reject'])->name('backend_popup_reject');
        });

        Route::prefix('guest')->group(function () {
            Route::get('/', [GuestController::class, 'index'])->name('backend_guest');
            Route::post('/', [GuestController::class, 'saveDataIndex'])->name('backend_guest_save_data_index');
            Route::get('/create', [GuestController::class, 'edit'])->name('backend_guest_create');
            Route::get('/edit/{guest}', [GuestController::class, 'edit'])->name('backend_guest_edit');
            Route::post('/save/{guest?}', [GuestController::class, 'save'])->name('backend_guest_save');
            Route::get('/delete/{id}', [GuestController::class, 'delete'])->name('backend_guest_delete');
            Route::post('/bulk_delete', [GuestController::class, 'bulkDelete'])->name('backend_guest_bulk_delete');
        });

        Route::prefix('member')->group(function () {
            Route::get('/', [MemberController::class, 'index'])->name('backend_member');
            Route::post('/', [MemberController::class, 'saveDataIndex'])->name('backend_member_save_data_index');
            Route::get('/create', [MemberController::class, 'edit'])->name('backend_member_create');
            Route::get('/edit/{member}', [MemberController::class, 'edit'])->name('backend_member_edit');
            Route::post('/save/{member?}', [MemberController::class, 'save'])->name('backend_member_save');
            Route::get('/delete/{id}', [MemberController::class, 'delete'])->name('backend_member_delete');
            Route::post('/bulk_delete', [MemberController::class, 'bulkDelete'])->name('backend_member_bulk_delete');
        });

        Route::prefix('post')->group(function () {
            Route::get('/', [PostController::class, 'index'])->name('backend_post');
            Route::post('/', [PostController::class, 'saveDataIndex'])->name('backend_post_save_data_index');
            Route::get('create', [PostController::class, 'edit'])->name('backend_post_create');
            Route::get('edit/{post}', [PostController::class, 'edit'])->name('backend_post_edit');
            Route::post('save/{post?}', [PostController::class, 'save'])->name('backend_post_save');
            Route::get('delete/{id}', [PostController::class, 'delete'])->name('backend_post_delete');
            Route::post('bulk_delete', [PostController::class, 'bulkDelete'])->name('backend_post_bulk_delete');
            Route::get('clone/{post}', [PostController::class, 'clone'])->name('backend_post_clone');
            Route::get('restore/{id}', [PostController::class, 'restore'])->name('backend_post_restore');
            Route::get('force-delete/{id}', [PostController::class, 'forceDelete'])->name('backend_post_force_delete');
            Route::get('import', [PostController::class, 'showImportForm'])->name('backend_post_show_import_form');
            Route::post('import', [PostController::class, 'importFromUrl'])->name('backend_post_import');
            Route::post('approve/{post}', [PostController::class, 'approve'])->name('backend_post_approve');
            Route::post('/reject/{post}', [PostController::class, 'reject'])->name('backend_post_reject');
        });

        Route::prefix('investment_guide')->group(function () {
            Route::get('/', [InvestMentGuideController::class, 'index'])->name('backend_investment_guide');
            Route::post('/', [InvestMentGuideController::class, 'saveDataIndex'])->name('backend_investment_guide_save_data_index');
            Route::get('create', [InvestMentGuideController::class, 'edit'])->name('backend_investment_guide_create');
            Route::get('edit/{investment_guide}', [InvestMentGuideController::class, 'edit'])->name('backend_investment_guide_edit');
            Route::post('save/{investment_guide?}', [InvestMentGuideController::class, 'save'])->name('backend_investment_guide_save');
            Route::get('delete/{id}', [InvestMentGuideController::class, 'delete'])->name('backend_investment_guide_delete');
            Route::post('bulk_delete', [InvestMentGuideController::class, 'bulkDelete'])->name('backend_investment_guide_bulk_delete');
            Route::get('clone/{investment_guide}', [InvestMentGuideController::class, 'clone'])->name('backend_investment_guide_clone');
            Route::get('restore/{id}', [InvestMentGuideController::class, 'restore'])->name('backend_investment_guide_restore');
            Route::get('force-delete/{id}', [InvestMentGuideController::class, 'forceDelete'])->name('backend_investment_guide_force_delete');
            Route::get('import', [InvestMentGuideController::class, 'showImportForm'])->name('backend_investment_guide_show_import_form');
            Route::post('import', [InvestMentGuideController::class, 'importFromUrl'])->name('backend_investment_guide_import');
            Route::post('approve/{investment_guide}', [InvestMentGuideController::class, 'approve'])->name('backend_investment_guide_approve');
            Route::post('/reject/{investment_guide}', [InvestmentGuideController::class, 'reject'])->name('backend_investment_guide_reject');
        });

        Route::get('file-manager', [FileManagerController::class, 'index'])->name('backend_file_manager');

        Route::prefix('lading-page')->group(function () {
            Route::get('/home', [LandingPageController::class, 'home'])->name('backend_landing_page_home');
            Route::get('/job', [LandingPageController::class, 'job'])->name('backend_landing_page_job');
            Route::post('/save/{key}', [LandingPageController::class, 'save'])->name('backend_landing_page_save');
        });

        Route::prefix('setting')->group(function () {
            Route::get('/general', [SettingController::class, 'general'])->name('backend_setting_general');
            Route::get('/author', [SettingController::class, 'author'])->name('backend_setting_author');
            Route::get('/payment', [SettingController::class, 'payment'])->name('backend_setting_payment');
            Route::get('/social', [SettingController::class, 'social'])->name('backend_setting_social');
            Route::get('/seo', [SettingController::class, 'seo'])->name('backend_setting_seo');
            Route::post('/save', [SettingController::class, 'save'])->name('backend_setting_save');
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('backend_user');
            Route::post('save/{user?}', [UserController::class, 'save'])->name('backend_user_save');
            Route::get('create', [UserController::class, 'edit'])->name('backend_user_create');
            Route::get('edit/{user}', [UserController::class, 'edit'])->name('backend_user_edit');
            Route::get('delete/{user}', [UserController::class, 'delete'])->name('backend_user_delete');
            Route::post('approve/{user}', [UserController::class, 'approve'])->name('backend_user_approve');
            Route::post('/reject/{user}', [UserController::class, 'reject'])->name('backend_user_reject');
        });

        Route::prefix('group')->group(function () {
            Route::get('/', [GroupController::class, 'index'])->name('backend_group');
            Route::post('save/{group?}', [GroupController::class, 'save'])->name('backend_group_save');
            Route::get('create', [GroupController::class, 'edit'])->name('backend_group_create');
            Route::get('edit/{group}', [GroupController::class, 'edit'])->name('backend_group_edit');
            Route::get('delete/{group}', [GroupController::class, 'delete'])->name('backend_group_delete');
        });

        Route::prefix('widget')->group(function () {
            Route::get('/', [WidgetController::class, 'index'])->name('backend_widget');
            Route::post('/', [WidgetController::class, 'saveDataIndex'])->name('backend_widget_save_data_index');
            Route::get('create', [WidgetController::class, 'edit'])->name('backend_widget_create');
            Route::get('edit/{widget}', [WidgetController::class, 'edit'])->name('backend_widget_edit');
            Route::post('save/{widget?}', [WidgetController::class, 'save'])->name('backend_widget_save');
            Route::get('clone/{widget}', [WidgetController::class, 'clone'])->name('backend_widget_clone');
            Route::get('delete/{id}', [WidgetController::class, 'delete'])->name('backend_widget_delete');
            Route::get('clone/{widget}', [WidgetController::class, 'clone'])->name('backend_widget_clone');
            Route::post('bulk_delete', [WidgetController::class, 'bulkDelete'])->name('backend_widget_bulk_delete');
        });
        Route::prefix('project')->group(function () {
            Route::get('/', [ProjectController::class, 'index'])->name('backend_project');
            Route::post('/', [ProjectController::class, 'saveDataIndex'])->name('backend_project_save_data_index');
            Route::get('create', [ProjectController::class, 'edit'])->name('backend_project_create');
            Route::get('edit/{project}', [ProjectController::class, 'edit'])->name('backend_project_edit');
            Route::post('save/{project?}', [ProjectController::class, 'save'])->name('backend_project_save');
            Route::get('delete/{id}', [ProjectController::class, 'delete'])->name('backend_project_delete');
            Route::post('bulk_delete', [ProjectController::class, 'bulkDelete'])->name('backend_project_bulk_delete');
            Route::post('/approve/{project}', [ProjectController::class, 'approve'])->name('backend_project_approve');
            Route::post('/reject/{project}', [ProjectController::class, 'reject'])->name('backend_project_reject');
            Route::get('/export', [ProjectController::class, 'exportCsv'])->name('backend_project_export');
        });

        Route::prefix('vrtour')->group(function () {
            Route::prefix('skin')->group(function () {
                Route::get('index', [SkinController::class, 'index'])->name('backend_vrtour_skin_index');
                Route::get('get-data/{vrtour_id}/{type}', [SkinController::class, 'getDataAll'])->name('backend_vrtour_skin_getdata');
                Route::post('update-data/{vrtour_id}', [SkinController::class, 'updateDataAll'])->name('backend_vrtour_skin_updatedata');
            });
            Route::prefix('hotspot')->group(function () {
                Route::get('index', [HotspotController::class, 'index'])->name('backend_vrtour_hotspot_index');
                Route::get('get-hotspot/{id}', [HotspotController::class, 'getHotspot'])->name('backend_vrtour_get_hotspot_index');
                Route::get('edit/{id}', [HotspotController::class, 'edit'])->name('backend_vrtour_hotspot_edit');
                Route::post('save/{id}', [HotspotController::class, 'store'])->name('backend_vrtour_hotspot_store');
            });
            Route::prefix('content')->group(function () {
                Route::get('index', [ContentController::class, 'index'])->name('backend_vrtour_content_index');
                Route::get('get-data/{vrtour_id}', [ContentController::class, 'getDataAll'])->name('backend_vrtour_content_getdata');
                Route::get('edit/{id}', [ContentController::class, 'edit'])->name('backend_vrtour_content_edit');
                Route::post('save/{id}', [ContentController::class, 'store'])->name('backend_vrtour_content_store');
            });
        });

        Route::prefix('ai-monitor')->group(function () {
            Route::get('/status', [AIChatMonitorController::class, 'getApiStatus'])->name('backend_ai_monitor_status');
        });
    });
});

Route::middleware(['auth'])->group(function () {
    Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')->name('ckfinder_connector');
    Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')->name('ckfinder_browser');
});
