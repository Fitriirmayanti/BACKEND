<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedWithRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Jika request dari API (expects JSON), jangan redirect
            if ($request->expectsJson()) {
                return $next($request);
            }

            return redirect(match (trim($user->role)) {
            'admin_pusat' => '/admin-pusat',
            'admin_lapangan' => '/admin-lapangan',
            'user' => '/',
            default => '/',
             });
         }

        return $next($request);
    }
}
