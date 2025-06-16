<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate; // Uncomment jika Anda menggunakan Gates
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

// Import Model dan Policy yang akan didaftarkan
use App\Models\Pembelian;
use App\Policies\PembelianPolicy;
// use App\Models\Barang; // Contoh jika ada BarangPolicy
// use App\Policies\BarangPolicy; // Contoh jika ada BarangPolicy

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Daftarkan Model Anda ke Policy-nya di sini
        // 'App\Models\Model' => 'App\Policies\ModelPolicy', // Format umum

        Pembelian::class => PembelianPolicy::class,
        // Contoh:
        // Barang::class => BarangPolicy::class,
        // Jika Anda membuat policy untuk model User (misal, untuk update profil oleh user lain), daftarkan di sini:
        // \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Di sini Anda juga bisa mendefinisikan Gates jika diperlukan
        // Gate::define('update-post', function (User $user, Post $post) {
        //     return $user->id === $post->user_id;
        // });
    }
}