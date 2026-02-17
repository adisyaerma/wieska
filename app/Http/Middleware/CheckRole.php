<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // cek apakah role user ada di daftar role yang diizinkan
        if (!in_array(Auth::user()->jabatan, $roles)) {
            return abort(403, 'Anda tidak punya akses ke halaman ini.');
        }

        return $next($request);
    }

}
