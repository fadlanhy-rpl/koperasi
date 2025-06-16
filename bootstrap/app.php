<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; // Pastikan ini di-import

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php', // Path ke file route web Anda
        // api: __DIR__.'/../routes/api.php', // Uncomment jika Anda menggunakan API routes
        commands: __DIR__.'/../routes/console.php', // Path ke file route console Anda
        health: '/up', // Endpoint health check standar Laravel
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware global yang dijalankan di setiap request web
        // Contoh:
        // $middleware->web(append: [
        //     \App\Http\Middleware\HandleInertiaRequests::class, // Jika menggunakan Inertia.js
        //     \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class, // Jika menggunakan Vite preloading
        // ]);

        // Middleware yang akan selalu dijalankan untuk semua request (web dan console)
        // $middleware->append(EnsureUserIsSubscribed::class);

        // Alias untuk middleware yang bisa digunakan di route
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class, // Atau \Illuminate\Auth\Middleware\Authenticate::class jika tidak ada custom
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // Atau \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class jika tidak ada custom
            'role' => \App\Http\Middleware\RoleMiddleware::class, // Middleware custom kita
            // Tambahkan alias middleware lain jika ada, contoh:
            // 'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            // 'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            // 'can' => \Illuminate\Auth\Middleware\Authorize::class,
        ]);

        // Middleware groups (grup 'web' dan 'api' sudah default)
        // Anda bisa memodifikasi atau menambahkan grup di sini jika perlu.
        // Contoh: menambahkan middleware ke grup 'web'
        // $middleware->group('web', [
        //     \App\Http\Middleware\EncryptCookies::class,
        //     \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        //     \Illuminate\Session\Middleware\StartSession::class,
        //     // \Illuminate\Session\Middleware\AuthenticateSession::class, // Jika menggunakan session-based auth
        //     \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        //     \App\Http\Middleware\VerifyCsrfToken::class,
        //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // ]);

        // Prioritas middleware (jika urutan eksekusi penting)
        // $middleware->priority([
        //     \Illuminate\Cookie\Middleware\EncryptCookies::class,
        //     \Illuminate\Session\Middleware\StartSession::class,
        //     \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        //     \App\Http\Middleware\Authenticate::class, // Middleware auth kita
        //     // \Illuminate\Session\Middleware\AuthenticateSession::class,
        //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
        //     \Illuminate\Auth\Middleware\Authorize::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Konfigurasi bagaimana exception ditangani
        // Contoh:
        // $exceptions->dontReport([
        //     PostTooLargeException::class,
        // ]);

        // $exceptions->report(function (InvalidOrderException $e) {
        //     // ...
        // });

        // $exceptions->render(function (InvalidOrderException $e, Request $request) {
        //     if ($request->expectsJson()) {
        //         return response()->json(['error' => $e->getMessage()], 422);
        //     }
        //     return response()->view('errors.invalid-order', [], 500);
        // });
    })->create();