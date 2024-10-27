<?php 

namespace App\Http;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Kernel as HttpKernel; // Ajoutez cette ligne
use Illuminate\Routing\Middleware\ValidateSignature;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // Ajoutez ici des middlewares globaux, s'il y en a
    ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => ValidateSignature::class, // Utilisez le bon import
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ];
}
