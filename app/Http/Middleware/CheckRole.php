<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response; // <-- Wajib import ini

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek dulu apakah user sudah login
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah Role User ADA di dalam daftar role yang diizinkan
        // Contoh: route('role:admin,owner') -> $roles = ['admin', 'owner']
        if (! in_array($request->user()->role, $roles)) {
            
            // Jika role tidak cocok, tampilkan error 403
            abort(403, 'Akses Ditolak! Halaman ini khusus untuk ' . implode(' atau ', $roles));
        }

        // 3. Jika Role cocok, izinkan masuk
        return $next($request);
    }
}