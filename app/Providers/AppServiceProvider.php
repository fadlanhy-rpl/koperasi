<?php

namespace App\Providers;

use Illuminate\Support\Facades\View; // Import View facade
use Illuminate\Support\Facades\Blade; // Import Blade facade
use Illuminate\Support\ServiceProvider;

// Import View Composers
use App\Http\View\Composers\SidebarAdminComposer;
use App\Http\View\Composers\SidebarPengurusComposer;
use App\Http\View\Composers\SidebarAnggotaComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Di sini Anda bisa mendaftarkan service ke container jika diperlukan.
        // Misalnya:
        // $this->app->singleton(MyService::class, function ($app) {
        //     return new MyService();
        // });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Mendaftarkan View Composers untuk partials sidebar
        // Data dari composer ini akan tersedia secara otomatis di view yang ditentukan.
        View::composer('layouts.partials.sidebar_admin', SidebarAdminComposer::class);
        View::composer('layouts.partials.sidebar_pengurus', SidebarPengurusComposer::class);
        View::composer('layouts.partials.sidebar_anggota', SidebarAnggotaComposer::class);

        // Anda juga bisa mendaftarkan composer ke beberapa view sekaligus:
        // View::composer(
        //     ['profile', 'dashboard'], // Array view atau wildcard seperti 'admin.*'
        //     MultiViewComposer::class
        // );

        // Mendaftarkan custom Blade directive untuk format Rupiah
        // Penggunaan di Blade: @rupiah($nilai)
        Blade::directive('rupiah', function ($expression) {
            // $expression adalah nilai yang di-pass ke directive, contoh: $totalSimpanan
            // Kita akan format nilai tersebut sebagai mata uang Rupiah.
            return "<?php echo 'Rp ' . number_format((float) $expression, 0, ',', '.'); ?>";
        });

        // Contoh Blade directive lain jika diperlukan:
        // Blade::directive('datetime', function ($expression) {
        //     return "<?php echo ($expression)->format('d M Y H:i'); 
        //

        // Di sini Anda juga bisa melakukan hal lain saat aplikasi booting,
        // seperti mendaftarkan observer, event listener, dll.
    }
}