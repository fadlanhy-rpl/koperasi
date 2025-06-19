<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Import Auth facade

// Import Controllers
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ManajemenPenggunaController;
use App\Http\Controllers\Admin\SettingController;

// Pengurus Controllers
use App\Http\Controllers\Pengurus\DashboardController as PengurusDashboardController;
use App\Http\Controllers\Pengurus\ManajemenUnitUsahaController;
use App\Http\Controllers\Pengurus\ManajemenBarangController;
use App\Http\Controllers\Pengurus\PencatatanStokController;
use App\Http\Controllers\Pengurus\ManajemenSimpananController;
use App\Http\Controllers\Pengurus\TransaksiPembelianController;
use App\Http\Controllers\Pengurus\PembayaranCicilanController;
use App\Http\Controllers\Pengurus\LaporanPenjualanController;
use App\Http\Controllers\Pengurus\LaporanSimpananController;
use App\Http\Controllers\Pengurus\LaporanStokController;
use App\Http\Controllers\Pengurus\StokExportController;


// Anggota Controllers
use App\Http\Controllers\Anggota\DashboardController as AnggotaDashboardController;
use App\Http\Controllers\Anggota\ProfilAnggotaController;
use App\Http\Controllers\Anggota\SimpananAnggotaController;
use App\Http\Controllers\Anggota\PembelianAnggotaController;
use App\Http\Controllers\Anggota\ProsesPembelianController
;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Landing / Welcome
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home'); // Jika sudah login, arahkan ke home
    }
    return view('welcome'); // Tampilkan halaman welcome jika belum login
})->name('welcome');

// Auth Routes (Hanya untuk Guest)
Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Routes yang Memerlukan Autentikasi
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/home', [HomeController::class, 'index'])->name('home'); // Akan redirect berdasarkan role

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('manajemen-pengguna', ManajemenPenggunaController::class)->parameters([
            'manajemen-pengguna' => 'user' // Map parameter {manajemen_pengguna} ke $user
        ]);
        Route::post('/pengurus/stok/{barang}/quick-update', [PencatatanStokController::class, 'quickStockUpdate'])
        ->name('pengurus.stok.quickUpdate');
        
        Route::prefix('settings')->name('settings.')->controller(SettingController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('general', 'updateGeneral')->name('general.update');
        Route::put('simpanan', 'updateSimpananDefaults')->name('simpanan.update');
        Route::put('appearance', 'updateAppearance')->name('appearance.update');
        Route::put('my-profile', 'updateMyProfile')->name('myprofile.update'); // Untuk update profil admin
        Route::put('my-password', 'updateMyPassword')->name('mypassword.update'); // Untuk update password admin
    });
        // Route untuk backup data (contoh, perlu controller)
        // Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
    });

    /*
    |--------------------------------------------------------------------------
    | Pengurus Routes (Admin juga bisa akses ini)
    |--------------------------------------------------------------------------
    */
    Route::prefix('pengurus')->name('pengurus.')->middleware('role:admin,pengurus')->group(function () {
        Route::get('dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('unit-usaha', ManajemenUnitUsahaController::class)->parameters([
            'unit-usaha' => 'unitUsaha' // Map parameter {unit_usaha} ke $unitUsaha
        ]);
        Route::resource('barang', ManajemenBarangController::class);  // Parameter default {barang} sudah pas

        Route::prefix('stok')->name('stok.')->controller(PencatatanStokController::class)->group(function() {
        Route::get('/', 'index')->name('index'); // HALAMAN UTAMA MODUL STOK
        Route::get('barang-masuk/{barang}/form', 'showFormBarangMasuk')->name('formBarangMasuk');
        Route::post('barang-masuk/{barang}', 'storeBarangMasuk')->name('storeBarangMasuk');
        Route::get('barang-keluar/{barang}/form', 'showFormBarangKeluar')->name('formBarangKeluar');
        Route::post('barang-keluar/{barang}', 'storeBarangKeluar')->name('storeBarangKeluar');
        Route::get('penyesuaian/{barang}/form', 'showFormPenyesuaianStok')->name('formPenyesuaianStok');
        Route::post('penyesuaian/{barang}', 'storePenyesuaianStok')->name('storePenyesuaianStok');
        // Route untuk histori per barang sudah implisit di ManajemenBarangController@show
        // atau jika ingin dedicated, bisa ditambahkan:
        // Route::get('histori/{barang}', 'historiStokBarang')->name('historiPerBarang');
    });

        Route::prefix('simpanan')->name('simpanan.')->controller(ManajemenSimpananController::class)->group(function() {
            Route::get('pokok', 'indexPokok')->name('pokok.index');
            Route::post('pokok', 'storePokok')->name('pokok.store');
            Route::get('wajib', 'indexWajib')->name('wajib.index');
            Route::post('wajib', 'storeWajib')->name('wajib.store');
            Route::get('sukarela', 'indexSukarela')->name('sukarela.index');
            Route::post('sukarela/setor', 'storeSetoranSukarela')->name('sukarela.storeSetoran');
            Route::post('sukarela/tarik', 'storePenarikanSukarela')->name('sukarela.storePenarikan');
            Route::get('riwayat/{anggota}', 'riwayatSimpananAnggota')->name('riwayatAnggota');
        });

        Route::prefix('transaksi-pembelian')->name('transaksi-pembelian.')->controller(TransaksiPembelianController::class)->group(function() {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{pembelian}', 'show')->name('show');
        });

         Route::prefix('pembayaran-cicilan')->name('pembayaran-cicilan.')->controller(PembayaranCicilanController::class)->group(function() {
        Route::get('/', 'index')->name('index'); // <-- ROUTE BARU UNTUK DAFTAR PEMBELIAN YANG DICICIL
        Route::get('{pembelian}/create', 'showFormBayarCicilan')->name('create');
        Route::post('{pembelian}', 'storePembayaranCicilan')->name('store');
    });

        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::prefix('penjualan')->name('penjualan.')->controller(LaporanPenjualanController::class)->group(function () {
                Route::get('umum', 'penjualanUmum')->name('umum');
                Route::get('per-barang', 'penjualanPerBarang')->name('perBarang');
                Route::get('laba-rugi', 'labaRugiPenjualan')->name('labaRugi');
            });
            Route::prefix('simpanan')->name('simpanan.')->controller(LaporanSimpananController::class)->group(function () {
                Route::get('rekap-total', 'rekapTotalSimpanan')->name('rekapTotal');
                Route::get('rincian-per-anggota', 'rincianSimpananPerAnggota')->name('rincianPerAnggota');
                Route::get('wajib-belum-bayar', 'simpananWajibBelumBayar')->name('wajibBelumBayar');
            });
            Route::prefix('stok')->name('stok.')->controller(LaporanStokController::class)->group(function () {
                Route::get('daftar-terkini', 'daftarStokTerkini')->name('daftarTerkini');
                Route::get('kartu-stok/{barang}', 'kartuStokBarang')->name('kartuStok');
                // Route::get('opname', [LaporanStokController::class, 'stokOpname'])->name('stokOpname'); // Contoh jika ada

                // Route::get('showExportHistoryModal')->controller(StokExportController::class)->name('history');

            });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Anggota Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('anggota')->name('anggota.')->middleware('role:anggota')->group(function() {
        Route::get('dashboard', [AnggotaDashboardController::class, 'index'])->name('dashboard');
        
         Route::prefix('profil')->name('profil.')->controller(ProfilAnggotaController::class)->group(function() {
        Route::get('/', 'showProfilSaya')->name('show');
        Route::get('edit', 'editProfilSaya')->name('edit');
        Route::put('update', 'updateProfilSaya')->name('update'); // Menggabungkan update nama, email, dan foto
        Route::put('update-password', 'updatePasswordSaya')->name('updatePassword');
        Route::delete('delete-photo', 'deleteProfilePhoto')->name('photo.delete');
    });

        Route::get('simpanan', [SimpananAnggotaController::class, 'showSimpananSaya'])->name('simpanan.show');

        Route::prefix('pembelian')->name('pembelian.')->group(function() { // Hapus ->controller() jika method ada di controller berbeda
        Route::get('katalog', [PembelianAnggotaController::class, 'showKatalogBarang'])->name('katalog');
        Route::get('riwayat', [PembelianAnggotaController::class, 'showRiwayatPembelianSaya'])->name('riwayat');
        Route::get('{pembelian}/detail', [PembelianAnggotaController::class, 'showDetailPembelianSaya'])->name('detail');
        
        // Route baru untuk detail barang dan proses pembelian oleh anggota
        Route::get('barang/{barang}/lihat', [ProsesPembelianController::class, 'showDetailBarang'])->name('barang.detail');
        Route::post('barang/{barang}/beli-saldo', [ProsesPembelianController::class, 'prosesPembelianDenganSaldo'])->name('barang.beliSaldo');
    });
    });
});