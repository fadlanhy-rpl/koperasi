<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards  // The guards that should be checked.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        // Jika tidak ada guard spesifik yang diberikan, gunakan guard default.
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Jika pengguna sudah terautentikasi dengan guard ini,
                // redirect mereka ke halaman home atau dashboard.
                // Anda bisa mengganti route('home') dengan route lain jika perlu.
                return redirect(route('home'));
            }
        }

        // Jika pengguna tidak terautentikasi dengan guard manapun yang dicek,
        // lanjutkan request ke tujuan berikutnya (misalnya, controller AuthController).
        return $next($request);
    }
}