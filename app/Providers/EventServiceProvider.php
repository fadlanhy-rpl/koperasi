<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered; // Event default untuk user registrasi
use Illuminate\Auth\Events\Login;     // Event yang kita gunakan
use Illuminate\Auth\Listeners\SendEmailVerificationNotification; // Listener default
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// Import Listener kita
use App\Listeners\UpdateLastLoginAt;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [ // Daftarkan event Login dan listener-nya
            UpdateLastLoginAt::class,
        ],
        // Daftarkan event dan listener lain di sini jika ada
        // \App\Events\SomeEvent::class => [
        //     \App\Listeners\HandleSomeEvent::class,
        // ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        // Event::listen( ... ); // Cara lain untuk mendaftarkan event listener secara manual
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * Laravel 9+ akan otomatis discover jika ini true.
     * Laravel 11+ mungkin tidak lagi menggunakan properti ini dan auto-discovery adalah default.
     * Anda bisa membiarkannya atau menghapusnya tergantung versi Laravel Anda.
     * Jika error, coba hapus method ini.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return false; // Set ke false jika Anda mendaftarkan listener secara manual di array $listen
                     // Set ke true jika ingin Laravel otomatis mencari listener di App/Listeners
                     // Untuk kasus kita, karena sudah eksplisit di $listen, false lebih aman.
    }
}