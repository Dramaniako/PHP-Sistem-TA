<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // 1. Cek apakah user sudah login?
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Cek apakah role user SAMA dengan role yang diminta?
        // Auth::user()->role diambil dari database langkah 1
        if (Auth::user()->role !== $role) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
        }

        return $next($request);
    }
}