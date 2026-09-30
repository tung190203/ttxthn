<?php

use App\Http\Controllers\AIChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvestMentGuideController;
use App\Http\Controllers\TtxtWebhookController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';
require __DIR__ . '/backend.php';

Route::post('/ttxt/webhook', TtxtWebhookController::class)->name('ttxt_webhook');

Route::localized(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/register', [AuthController::class, 'register'])->name('auth_register');
        Route::post('/login', [AuthController::class, 'login'])->name('auth_login');
        Route::post('/verify-email', [AuthController::class, 'verifyEmailApi'])->name('auth_verify_email_api');
        Route::get('/verify-email/{id}/{token}', [AuthController::class, 'verifyEmail'])->name('auth_verify_email');
        Route::post('/resend-verification', [AuthController::class, 'resendVerificationEmail'])->name('auth_resend_verification');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('auth_forgot_password');
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('auth_reset_password_form');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('auth_reset_password');
        Route::get('/logout', [AuthController::class, 'logout'])->name('auth_logout');
        Route::get('/google', [AuthController::class, 'redirectToGoogle'])->name('auth_google');
        Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth_google_callback');
    });

    Route::group(['prefix' => 'guest'], function () {
        Route::post('/register', [AuthController::class, 'register'])->name('guest_register');
        Route::post('/login', [AuthController::class, 'login'])->name('guest_login');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('guest_password_email');
        Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('guest_password_reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('guest_password_update');
        Route::get('/logout', [AuthController::class, 'logout'])->name('guest_logout');
        Route::post('/update-info', [AuthController::class, 'updateAccount'])->name('guest_update_account');
        Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google_login');
        Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
    });

    Route::get('/', [HomeController::class, 'index'])->name('home_page');
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/map/filter', [MapController::class, 'filter']);
    Route::get('/map/bounds', [MapController::class, 'getProjectsInBounds']);
    Route::get('/api/districts', [MapController::class, 'getDistricts'])->name('api_districts');
    Route::get('/api/railway-projects', [MapController::class, 'getRailwayProjects'])->name('api_railway_projects');
    Route::get('/projects', [HomeController::class, 'projects'])->name('projects');
    Route::get('/industrial-projects', [HomeController::class, 'industrialProjects'])->name('industrial_projects');
    Route::get('/project-detail/{slug}', [HomeController::class, 'projectDetail'])->name('project_detail');
    Route::get('/vrtour/{slug}', [HomeController::class, 'showVrtour'])->name('show_Vrtour');
    Route::get('/account', [HomeController::class, 'account'])->name('account');
    Route::get('/sitemap.xml', [HomeController::class, 'siteMap'])->name('site_map');
    Route::match(['get', 'post'], '/lien-he', [HomeController::class, 'contact'])->name('contact');
    Route::match(['get', 'post'], '/contact', [HomeController::class, 'contact'])->name('contact');
    Route::post('/interest', [InterestController::class, 'toggleInterest'])->name('interest');
    Route::get('/search', [HomeController::class, 'search'])->name('search');
    Route::get('/ajax-project-suggestions', [HomeController::class, 'ajaxSuggestions'])->name('ajax_project_suggestions');
    Route::get('/huongdansudungbando', function () {
        $brochure = app()->getLocale() === 'en'
            ? 'huong-dan-su-dung-ban-do-eng.html'
            : 'huong-dan-su-dung-ban-do.html';

        return response()->file(public_path("brochures/{$brochure}"));
    })->name('map_user_guide');
    Route::group(['prefix' => 'chat'], function () {
        Route::post('/', [AIChatController::class, 'chat']);
        Route::get('/session/{sessionId}', [AIChatController::class, 'sessionHistory']);
        Route::delete('/session/{sessionId}', [AIChatController::class, 'deleteSession']);
        Route::post('/feedback', [AIChatController::class, 'submitFeedback']);
        Route::get('/health', [AIChatController::class, 'getHealthStatus']);
    });

    Route::get('{slug}-n{id}.html', [PostController::class, 'detail'])->where(['slug' => '[a-z0-9\-]+', 'id' => '[0-9]+'])->name('post_detail');
    Route::get('{slug}-p{id}.html', [InvestMentGuideController::class, 'detail'])->where(['slug' => '[a-z0-9\-]+', 'id' => '[0-9]+'])->name('investment_guide_detail');

    Route::get('{slug}', [CategoryController::class, 'index'])->where(['slug' => '[a-zA-Z0-9\-]+'])->name('category');

    Route::get('hotspot-image/{code}/{lot}/{file}', function (string $code, string $lot, string $file) {
        $path = public_path("vrtour/{$code}/image/{$lot}/{$file}");
        abort_unless(is_file($path), 404);

        return response()->file($path, [
            'Content-Type' => 'image/png','Access-Control-Allow-Origin' => '*','Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    })->where(['code' => '[A-Za-z0-9._-]+','lot'  => '[A-Za-z0-9._-]+','file' => '(current|pending|original|[0-9a-f]{6})\.png',])->name('hotspot_image');
});
