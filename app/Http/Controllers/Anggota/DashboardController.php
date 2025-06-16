<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SimpananPokok;
use App\Models\SimpananWajib;
use App\Models\SimpananSukarela;
use App\Models\Pembelian; // Untuk total pembelian
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:anggota']);
    }

    public function index()
    {
        $anggota = Auth::user();

        // Data untuk Stats Cards
        $totalSimpananPokok = $anggota->simpananPokoks()->sum('jumlah');
        $totalSimpananWajib = $anggota->simpananWajibs()->sum('jumlah');
        $transaksiTerakhirSukarela = $anggota->simpananSukarelas()
                                            ->orderBy('tanggal_transaksi', 'desc')
                                            ->orderBy('created_at', 'desc')->first();
        $saldoSimpananSukarela = $transaksiTerakhirSukarela ? $transaksiTerakhirSukarela->saldo_sesudah : 0;
        
        $jumlahBulanBayarWajib = $anggota->simpananWajibs()->count(); // Jumlah periode bayar

        // Data untuk Ringkasan Keanggotaan
        $lamaKeanggotaanBulan = $anggota->created_at ? $anggota->created_at->diffInMonths(Carbon::now()) : 0;
        $totalPembelianCount = $anggota->pembelians()->count();
        $totalSemuaSimpanan = $totalSimpananPokok + $totalSimpananWajib + $saldoSimpananSukarela;
        $statusKeanggotaan = $anggota->status ?? 'Aktif'; // Asumsi ada field 'status' di model User

        // Data untuk Chart Simpanan Anggota (Contoh: progres simpanan wajib per bulan selama 6 bulan terakhir)
        $labelsSavingsChart = [];
        $dataWajibSavingsChart = [];
        $dataSukarelaSavingsChart = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $labelsSavingsChart[] = $bulan->shortMonthName; // Jan, Feb, etc.

            $dataWajibSavingsChart[] = (float) $anggota->simpananWajibs()
                                        ->whereMonth('tanggal_bayar', $bulan->month)
                                        ->whereYear('tanggal_bayar', $bulan->year)
                                        ->sum('jumlah');
            // Untuk sukarela, mungkin lebih relevan saldo akhir bulan atau total setoran bulan itu
            $dataSukarelaSavingsChart[] = (float) $anggota->simpananSukarelas()
                                        ->where('tipe_transaksi', 'setor')
                                        ->whereMonth('tanggal_transaksi', $bulan->month)
                                        ->whereYear('tanggal_transaksi', $bulan->year)
                                        ->sum('jumlah');
        }
        $dataSavingsChart = [
            'labels' => $labelsSavingsChart,
            'wajib' => $dataWajibSavingsChart,
            'sukarela' => $dataSukarelaSavingsChart,
        ];

        // Data untuk Aktivitas Terbaru Anggota (Contoh: 3 transaksi simpanan/pembelian terakhir)
        $aktivitasSimpanan = $anggota->simpananSukarelas() // Atau gabungan semua simpanan
                                    ->orderBy('tanggal_transaksi', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->take(2)->get()->map(function ($item) {
                                        return (object)[
                                            'type' => 'simpanan',
                                            'icon_bg' => $item->tipe_transaksi == 'setor' ? 'green' : 'red',
                                            'icon' => $item->tipe_transaksi == 'setor' ? 'plus-circle' : 'minus-circle',
                                            'deskripsi' => ucfirst($item->tipe_transaksi) . " Simp. Sukarela",
                                            'tanggal_format' => Carbon::parse($item->tanggal_transaksi)->diffForHumans(),
                                            'jumlah' => $item->jumlah
                                        ];
                                    });
        $aktivitasPembelian = $anggota->pembelians()
                                    ->orderBy('tanggal_pembelian', 'desc')
                                    ->take(2)->get()->map(function ($item) {
                                        return (object)[
                                            'type' => 'pembelian',
                                            'icon_bg' => 'blue',
                                            'icon' => 'shopping-cart',
                                            'deskripsi' => "Pembelian No. " . $item->kode_pembelian,
                                            'tanggal_format' => Carbon::parse($item->tanggal_pembelian)->diffForHumans(),
                                            'jumlah' => $item->total_harga
                                        ];
                                    });
        $aktivitasTerbaru = $aktivitasSimpanan->merge($aktivitasPembelian)->sortByDesc(function($item){
            // Sort by original date if available, or make it complex
            return $item->tanggal_format; // Ini hanya sort by string diffForHumans, tidak ideal. Perlu tanggal asli.
        })->take(3); // Ambil 3 teratas setelah merge

        return view('anggota.dashboard', compact(
            'totalSimpananPokok',
            'totalSimpananWajib',
            'saldoSimpananSukarela',
            'jumlahBulanBayarWajib',
            'lamaKeanggotaanBulan',
            'totalPembelianCount',
            'totalSemuaSimpanan',
            'statusKeanggotaan',
            'dataSavingsChart',
            'aktivitasTerbaru'
        ));
    }
}