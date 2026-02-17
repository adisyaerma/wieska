<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // pastikan user login dan role ada di daftar
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return redirect('/dashboard')->with('error', 'Anda tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }
}
