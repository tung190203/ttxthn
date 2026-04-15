<?php

use App\Http\Middleware\BasePasswordMiddleware;
use App\Http\Middleware\MemberAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
//        $middleware->redirectGuestsTo('/backend/login');
        $middleware->append(\App\Http\Middleware\TrackVisits::class);
        $middleware->alias([
            'member_auth' => MemberAuth::class,
            'base_auth' => BasePasswordMiddleware::class,
        ]);
        $middleware->encryptCookies(except: [
            'ckCsrfToken',
        ]);
        $middleware->validateCsrfTokens(
            except: ['ckfinder/*']
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
