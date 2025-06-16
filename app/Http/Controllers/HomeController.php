<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SimpananPokok;
use App\Models\SimpananWajib;
use App\Models\SimpananSukarela;
use App\Models\Pembelian;
use App\Models\Cicilan;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Arahkan ke dashboard spesifik jika diinginkan
        if ($user->isAdmin()) {
            // Jika admin punya dashboard sendiri yang berbeda dari home.blade.php
            return redirect()->route('admin.dashboard');
            // Untuk sekarang, kita biarkan Admin menggunakan home.blade.php juga
            // atau kita bisa siapkan data khusus admin di sini.
        } elseif ($user->isPengurus()) {
            return redirect()->route('pengurus.dashboard');
        } elseif ($user->isAnggota()) {
            return redirect()->route('anggota.dashboard'); // Anggota langsung ke dashboardnya
        }

        // Data untuk home.blade.php (yang Anda berikan, diasumsikan untuk Admin/Pengurus)
        $totalAnggota = User::where('role', 'anggota')->count();
        
        $totalSimpanan = SimpananPokok::sum('jumlah') +
                         SimpananWajib::sum('jumlah') +
                         SimpananSukarela::selectRaw('SUM(CASE WHEN tipe_transaksi = "setor" THEN jumlah ELSE -jumlah END) as total_saldo_sukarela')
                             ->value('total_saldo_sukarela') ?? 0;

        $penjualanBulanIni = Pembelian::whereMonth('tanggal_pembelian', Carbon::now()->month)
                                    ->whereYear('tanggal_pembelian', Carbon::now()->year)
                                    ->sum('total_harga');
        
        $cicilanAktif = Pembelian::where('status_pembayaran', 'cicilan')->count();

        // Data untuk Chart Penjualan Unit Usaha (Contoh, perlu disesuaikan)
        // Anda perlu mendefinisikan bagaimana data ini diambil (misal, per bulan untuk 6 bulan terakhir)
        $dataPenjualanUnitUsaha = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [12, 19, 15, 25, 22, 30] // Data dummy, ganti dengan query sebenarnya
        ];

        // Data untuk Chart Distribusi Simpanan (Contoh)
        $dataDistribusiSimpanan = [
            'labels' => ['Simpanan Pokok', 'Simpanan Wajib', 'Simpanan Sukarela'],
            'data' => [
                SimpananPokok::sum('jumlah'),
                SimpananWajib::sum('jumlah'),
                SimpananSukarela::selectRaw('SUM(CASE WHEN tipe_transaksi = "setor" THEN jumlah ELSE -jumlah END) as total_saldo_sukarela')->value('total_saldo_sukarela') ?? 0
            ]
        ];
        
        // Data untuk Aktivitas Terbaru (Contoh, perlu logika untuk mengambil aktivitas relevan)
        // Ini bisa berupa gabungan dari log pendaftaran user baru, pembayaran simpanan, transaksi pembelian.
        // Anda mungkin perlu tabel 'aktivitas_logs' atau query gabungan.
        $aktivitasTerbaru = collect([ // Data dummy
            (object)['icon_bg' => 'blue', 'icon' => 'user-plus', 'judul' => 'Anggota baru bergabung', 'deskripsi' => 'Budi Santoso - ' . Carbon::now()->subHours(2)->diffForHumans(null, true, true)],
            (object)['icon_bg' => 'green', 'icon' => 'money-bill-wave', 'judul' => 'Pembayaran simpanan wajib', 'deskripsi' => 'Siti Aminah - Rp 50.000 - ' . Carbon::now()->subHours(3)->diffForHumans(null, true, true)],
            (object)['icon_bg' => 'yellow', 'icon' => 'shopping-cart', 'judul' => 'Pembelian alat tulis', 'deskripsi' => 'Ahmad Fauzi - Rp 25.000 - ' . Carbon::now()->subHours(5)->diffForHumans(null, true, true)],
        ]);


        return view('home', compact(
            'user',
            'totalAnggota',
            'totalSimpanan',
            'penjualanBulanIni',
            'cicilanAktif',
            'dataPenjualanUnitUsaha',
            'dataDistribusiSimpanan',
            'aktivitasTerbaru'
        ));
    }
}