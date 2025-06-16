<?php

// routes/api.php
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\Admin\UnitUsahaController as AdminUnitUsahaController;
// use App\Http\Controllers\Pengurus\BarangController as PengurusBarangController;
// use App\Http\Controllers\Pengurus\StokController as PengurusStokController;
// use App\Http\Controllers\Pengurus\SimpananController as PengurusSimpananController;
// use App\Http\Controllers\Pengurus\PembelianController as PengurusPembelianController;
// use App\Http\Controllers\Pengurus\CicilanController as PengurusCicilanController;
// use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
// use App\Http\Controllers\Anggota\ProfilController as AnggotaProfilController;
// ... (dan controller otentikasi seperti Sanctum/Passport)

// Public routes (misal: login, register jika ada)
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']); // Mungkin hanya admin yg bisa register user baru

// Route::middleware('auth:sanctum')->group(function () {
//     // Admin Routes
//     Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
//         Route::apiResource('users', UserController::class);
//         Route::apiResource('unit-usaha', AdminUnitUsahaController::class);
//         // Laporan juga bisa diakses admin
//         Route::get('laporan/penjualan', [AdminLaporanController::class, 'rekapPenjualan'])->name('laporan.penjualan');
//         Route::get('laporan/simpanan', [AdminLaporanController::class, 'rekapSimpanan'])->name('laporan.simpanan');
//         Route::get('laporan/stok', [AdminLaporanController::class, 'laporanStokBarang'])->name('laporan.stok');
//     });

//     // Pengurus Routes
//     Route::middleware('role:pengurus,admin')->prefix('pengurus')->name('pengurus.')->group(function () { // Admin juga bisa akses fitur pengurus
//         Route::apiResource('barang', PengurusBarangController::class);
//         Route::post('stok/masuk', [PengurusStokController::class, 'catatBarangMasuk'])->name('stok.masuk');
//         Route::post('stok/keluar', [PengurusStokController::class, 'catatBarangKeluar'])->name('stok.keluar');
//         Route::post('stok/penyesuaian', [PengurusStokController::class, 'catatPenyesuaianStok'])->name('stok.penyesuaian');
//         Route::get('stok/histori', [PengurusStokController::class, 'index'])->name('stok.histori');

//         Route::post('simpanan/pokok', [PengurusSimpananController::class, 'storePokok'])->name('simpanan.storePokok');
//         Route::post('simpanan/wajib', [PengurusSimpananController::class, 'storeWajib'])->name('simpanan.storeWajib');
//         Route::post('simpanan/sukarela', [PengurusSimpananController::class, 'storeSukarela'])->name('simpanan.storeSukarela');
//         Route::get('simpanan/pokok/{user}', [PengurusSimpananController::class, 'indexPokok'])->name('simpanan.indexPokok');
//         // ... (route simpanan lainnya)

//         Route::apiResource('pembelian', PengurusPembelianController::class)->except(['update', 'edit']);
//         Route::post('pembelian/{pembelian}/cicilan', [PengurusCicilanController::class, 'store'])->name('pembelian.cicilan.store');
//         Route::get('pembelian/{pembelian}/cicilan', [PengurusCicilanController::class, 'index'])->name('pembelian.cicilan.index');
        
//         // Laporan juga bisa diakses pengurus (sesuaikan jika perlu)
//         Route::get('laporan/penjualan', [AdminLaporanController::class, 'rekapPenjualan'])->name('laporan.penjualan');
//         // ... (laporan lainnya yg boleh diakses pengurus)
//     });

//     // Anggota Routes
//     Route::middleware('role:anggota,pengurus,admin')->prefix('anggota')->name('anggota.')->group(function () { // Semua role bisa akses profilnya
//         Route::get('profil', [AnggotaProfilController::class, 'showProfil'])->name('profil.show');
//         Route::get('profil/riwayat-simpanan', [AnggotaProfilController::class, 'riwayatSimpanan'])->name('profil.simpanan');
//         Route::get('profil/riwayat-pembelian', [AnggotaProfilController::class, 'riwayatPembelian'])->name('profil.pembelian');
//         Route::get('profil/pembelian/{pembelian}', [AnggotaProfilController::class, 'detailPembelian'])->name('profil.pembelian.detail')
//              ->middleware('can:view,pembelian'); // Pastikan anggota hanya bisa lihat pembeliannya sendiri (gunakan Policy)
//     });
// });