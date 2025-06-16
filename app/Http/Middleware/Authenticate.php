<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        // Jika request mengharapkan JSON (misalnya dari API client), jangan redirect,
        // kembalikan null agar handler exception mengembalikan response 401.
        // Jika tidak (request web biasa), redirect ke route 'login'.
        return $request->expectsJson() ? null : route('login');
    }
}