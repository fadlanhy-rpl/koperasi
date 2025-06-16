<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UnitUsaha;
use App\Models\Pembelian;
use App\Models\SimpananPokok;
use App\Models\SimpananWajib;
use App\Models\SimpananSukarela;
use App\Models\Barang; // Untuk stok menipis
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        // Data untuk Stats Cards
        $totalPengguna = User::count();
        $unitUsahaAktif = UnitUsaha::count(); // Asumsi semua unit usaha yang ada di DB adalah aktif
        $totalTransaksi = Pembelian::count();
        
        $totalPendapatanKotor = Pembelian::sum('total_harga'); // Ini omset, bukan laba bersih

        // Data untuk System Overview
        $penggunaAktif24Jam = User::where('last_login_at', '>=', Carbon::now()->subDay())->count(); // Asumsi ada kolom last_login_at
        $transaksiHariIni = Pembelian::whereDate('tanggal_pembelian', Carbon::today())->count();
        
        // Stok Menipis (contoh: stok < 10)
        $batasStokMenipis = 10; // Bisa dari konfigurasi
        $stokMenipisCount = Barang::where('stok', '<=', $batasStokMenipis)->where('stok', '>', 0)->count();

        // Data untuk Chart Distribusi Pengguna
        $adminCount = User::where('role', 'admin')->count();
        $pengurusCount = User::where('role', 'pengurus')->count();
        $anggotaCount = User::where('role', 'anggota')->count();

        $dataDistribusiPengguna = [
            'labels' => ['Admin', 'Pengurus', 'Anggota'],
            'data' => [$adminCount, $pengurusCount, $anggotaCount]
        ];
        
        // Data untuk Aktivitas Sistem (Contoh - Anda perlu logika/tabel log aktivitas yang lebih baik)
        $aktivitasSistem = collect([
            (object)['icon_bg' => 'blue', 'icon' => 'users', 'judul' => 'Pengguna Aktif (24 Jam)', 'deskripsi' => $penggunaAktif24Jam . ' pengguna', 'nilai' => $penggunaAktif24Jam],
            (object)['icon_bg' => 'green', 'icon' => 'shopping-cart', 'judul' => 'Transaksi Hari Ini', 'deskripsi' => $transaksiHariIni . ' transaksi', 'nilai' => $transaksiHariIni],
            (object)['icon_bg' => 'yellow', 'icon' => 'exclamation-triangle', 'judul' => 'Stok Menipis (<' . $batasStokMenipis . ')', 'deskripsi' => $stokMenipisCount . ' item barang', 'nilai' => $stokMenipisCount],
        ]);


        return view('admin.dashboard', compact(
            'totalPengguna',
            'unitUsahaAktif',
            'totalTransaksi',
            'totalPendapatanKotor',
            'penggunaAktif24Jam', // Digunakan di Aktivitas Sistem
            'transaksiHariIni',   // Digunakan di Aktivitas Sistem
            'stokMenipisCount',     // Digunakan di Aktivitas Sistem
            'dataDistribusiPengguna',
            'aktivitasSistem' // Menggantikan $penggunaAktif, $transaksiHariIni, $stokMenipis untuk bagian "Aktivitas Sistem"
        ));
    }
}