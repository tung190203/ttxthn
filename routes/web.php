<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SlugController;
use App\Http\Controllers\AjaxController;
use Illuminate\Support\Facades\Route;

// require __DIR__ . '/auth.php';
// require __DIR__ . '/backend.php';
// require __DIR__ . '/member.php';

Route::localized(function () {
    Route::group(['prefix' => 'ajax'], function () {
        Route::post('/get_district', [AjaxController::class, 'getDistrict'])->name('ajax_get_district');
        Route::post('/get_ward', [AjaxController::class, 'getWard'])->name('ajax_get_ward');
    });

//Route::get('test-send-mail', 'HomeController@testSendMail');

    Route::get('/', [HomeController::class, 'index'])->name('home_page');
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/map/filter', [MapController::class, 'filter']);
    Route::get('/map/bounds', [MapController::class, 'getProjectsInBounds']);
    Route::get('/api/districts', [MapController::class, 'getDistricts'])->name('api_districts');
    Route::get('/projects.html', [HomeController::class, 'projects'])->name('projects');
    Route::get('/project-detail.html', [HomeController::class, 'projectDetail'])->name('project_detail');
    Route::get('/project-detail_cn2.html', [HomeController::class, 'projectDetailCN2'])->name('project_detail_cn2');
    Route::get('/account.html', [HomeController::class, 'account'])->name('account');
    Route::get('/news', [HomeController::class, 'news'])->name('news');
    Route::get('/introduce-potential', [HomeController::class, 'introducePotential'])->name('introduce_potential');
    Route::get('/new-detail', [HomeController::class, 'newDetail'])->name('new_detail');
    Route::get('/sitemap.xml', [HomeController::class, 'siteMap'])->name('site_map');
    Route::match(['get', 'post'], '/contact.html', [HomeController::class, 'contact'])->name('contact');

//    Route::post('/subscriber', [HomeController::class, 'subscriber'])->name('subscriber');
    Route::get('/page/{slug}.html', [HomeController::class, 'page'])->where(['slug' => '[a-z0-9\-]+'])->name('page_content');
//    Route::post('contact-post', [HomeController::class, 'contactPost'])->name('contact_post');

    Route::get('categories', [CouponController::class, 'categories'])->name('category_coupons');
    Route::get('stores', [StoreController::class, 'all'])->name('store_all');
    Route::get('stores/{slug}', [StoreController::class, 'detail'])->where(['slug' => '[a-z0-9\-]+'])->name('store_detail');

    Route::get('{slug}-n{id}.html', [PostController::class, 'detail'])->where(['slug' => '[a-z0-9\-]+', 'id' => '[0-9]+'])->name('post_detail');

    Route::get('{slug}', [SlugController::class, 'index'])->where(['slug' => '[a-zA-Z0-9\-]+'])->name('category');
});
