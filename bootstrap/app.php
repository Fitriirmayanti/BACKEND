<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // ❌ HAPUS EnsureFrontendRequestsAreStateful (tidak perlu untuk API token)

        // middleware custom kamu
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'guest-role' => \App\Http\Middleware\RedirectIfAuthenticatedWithRole::class,
        ]);

        // CORS
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();