<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Array of allowed roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            // Jika belum login, biarkan middleware 'auth' yang menangani redirect ke login.
            // Atau, Anda bisa langsung redirect dari sini jika ingin perilaku berbeda.
            // Untuk konsistensi, lebih baik biarkan middleware 'auth' yang bekerja.
            return $next($request); // Lanjutkan, middleware 'auth' akan intercept jika belum login
        }

        $user = Auth::user();

        // Periksa apakah role pengguna ada dalam daftar role yang diizinkan
        if ($user && in_array($user->role, $roles)) {
            return $next($request); // Pengguna memiliki role yang sesuai, lanjutkan request
        }

        // Jika role tidak sesuai, kembalikan response 403 (Forbidden)
        // Anda juga bisa redirect ke halaman tertentu dengan pesan error
        // return redirect()->route('home')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        return response()->json(['message' => 'Akses ditolak. Anda tidak memiliki peran yang sesuai.'], 403);
        // atau
        // abort(403, 'AKSES DITOLAK. ANDA TIDAK MEMILIKI PERAN YANG SESUAI.');
    }
}