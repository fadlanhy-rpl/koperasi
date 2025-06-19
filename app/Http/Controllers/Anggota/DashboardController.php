<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SimpananPokok;
use App\Models\SimpananWajib;
use App\Models\SimpananSukarela;
use App\Models\Pembelian;
use Illuminate\Support\Carbon; // Gunakan Illuminate\Support\Carbon
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:anggota']);
    }

    public function index()
    {
        $anggota = Auth::user();

        // 1. Data untuk Stats Cards Simpanan
        $totalSimpananPokok = $anggota->simpananPokoks()->sum('jumlah');
        $totalSimpananWajib = $anggota->simpananWajibs()->sum('jumlah');
        $jumlahBulanBayarWajib = $anggota->simpananWajibs()->count(); // Jumlah periode bayar

        $transaksiTerakhirSukarela = $anggota->simpananSukarelas()
                                            ->orderBy('tanggal_transaksi', 'desc')
                                            ->orderBy('created_at', 'desc') // Tie-breaker jika tanggal sama
                                            ->orderBy('id', 'desc') // Tie-breaker lebih lanjut
                                            ->first();
        $saldoSimpananSukarela = $transaksiTerakhirSukarela ? $transaksiTerakhirSukarela->saldo_sesudah : 0;
        
        // 2. Data untuk Ringkasan Keanggotaan
        $lamaKeanggotaanBulan = $anggota->created_at ? $anggota->created_at->diffInMonths(Carbon::now()) : 0;
        if ($lamaKeanggotaanBulan == 0 && $anggota->created_at->diffInDays(Carbon::now()) > 0) {
            // Jika kurang dari sebulan tapi sudah beberapa hari, hitung sebagai 1 bulan (atau 0 tergantung preferensi)
            $lamaKeanggotaanBulan = 1; 
        } elseif ($anggota->created_at->isFuture()) {
             $lamaKeanggotaanBulan = 0; // Jika created_at di masa depan (data anomali)
        }


        $totalPembelianCount = $anggota->pembelians()->count();
        $totalSemuaSimpanan = $totalSimpananPokok + $totalSimpananWajib + $saldoSimpananSukarela;
        $statusKeanggotaan = $anggota->status ?? 'Aktif'; // Asumsi ada field 'status' di model User

        // 3. Data untuk Chart Simpanan Anggota (6 Bulan Terakhir)
        $labelsSavingsChart = [];
        $dataWajibSavingsChart = [];
        $dataSukarelaSavingsChart = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $bulanPeriode = Carbon::now()->subMonths($i);
            $labelsSavingsChart[] = $bulanPeriode->translatedFormat('M Y'); // Contoh: Jun 2024

            // Total simpanan wajib yang dibayarkan PADA bulan tersebut
            $dataWajibSavingsChart[] = (float) $anggota->simpananWajibs()
                                        ->whereYear('tanggal_bayar', $bulanPeriode->year)
                                        ->whereMonth('tanggal_bayar', $bulanPeriode->month)
                                        ->sum('jumlah');
            
            // Total setoran simpanan sukarela PADA bulan tersebut
            $dataSukarelaSavingsChart[] = (float) $anggota->simpananSukarelas()
                                        ->where('tipe_transaksi', 'setor')
                                        ->whereYear('tanggal_transaksi', $bulanPeriode->year)
                                        ->whereMonth('tanggal_transaksi', $bulanPeriode->month)
                                        ->sum('jumlah');
        }
        $dataSavingsChart = [
            'labels' => $labelsSavingsChart,
            'wajib' => $dataWajibSavingsChart,
            'sukarela' => $dataSukarelaSavingsChart,
        ];

        // 4. Data untuk Aktivitas Terbaru Anggota (Contoh: 3-5 transaksi simpanan & pembelian terakhir)
        $limitAktivitas = 3; // Jumlah aktivitas yang ingin ditampilkan
        $aktivitasSimpanan = $anggota->simpananSukarelas() // Kita ambil dari sukarela sebagai contoh umum
                                    ->select('jumlah', 'tipe_transaksi', 'tanggal_transaksi as tanggal_aktivitas', DB::raw("'simpanan_sukarela' as jenis_aktivitas"))
                                    ->orderBy('tanggal_transaksi', 'desc')
                                    ->orderBy('created_at', 'desc');
                                    
        $aktivitasPembelian = $anggota->pembelians()
                                    ->select('total_harga as jumlah', DB::raw("'-' as tipe_transaksi"), 'tanggal_pembelian as tanggal_aktivitas', DB::raw("'pembelian' as jenis_aktivitas"))
                                    ->orderBy('tanggal_pembelian', 'desc');

        // Gabungkan query menggunakan unionAll jika strukturnya sama, atau ambil terpisah dan merge di collection
        // Menggunakan Collection untuk merge dan sort:
        $semuaAktivitas = collect();
        $semuaAktivitas = $semuaAktivitas->merge(
            $anggota->simpananSukarelas()
                ->orderBy('tanggal_transaksi', 'desc')->orderBy('created_at', 'desc')->take($limitAktivitas)
                ->get()->map(function ($item) {
                    return (object)[
                        'icon_bg' => $item->tipe_transaksi == 'setor' ? 'green' : 'red',
                        'icon' => $item->tipe_transaksi == 'setor' ? 'plus-circle' : 'minus-circle',
                        'deskripsi' => ucfirst($item->tipe_transaksi) . " Simp. Sukarela",
                        'tanggal_format' => Carbon::parse($item->tanggal_transaksi)->diffForHumans(),
                        'tanggal_asli' => $item->tanggal_transaksi, // Untuk sorting
                        'jumlah' => $item->jumlah
                    ];
                })
        );
         $semuaAktivitas = $semuaAktivitas->merge(
            $anggota->simpananWajibs()
                ->orderBy('tanggal_bayar', 'desc')->orderBy('created_at', 'desc')->take($limitAktivitas)
                ->get()->map(function ($item) {
                    return (object)[
                        'icon_bg' => 'blue', // Warna berbeda untuk wajib
                        'icon' => 'calendar-check',
                        'deskripsi' => "Bayar Simp. Wajib (" . Carbon::createFromDate($item->tahun, $item->bulan)->translatedFormat('M Y') .")",
                        'tanggal_format' => Carbon::parse($item->tanggal_bayar)->diffForHumans(),
                        'tanggal_asli' => $item->tanggal_bayar,
                        'jumlah' => $item->jumlah
                    ];
                })
        );
        $semuaAktivitas = $semuaAktivitas->merge(
            $anggota->pembelians()
                ->orderBy('tanggal_pembelian', 'desc')->orderBy('created_at', 'desc')->take($limitAktivitas)
                ->get()->map(function ($item) {
                    return (object)[
                        'icon_bg' => 'purple', // Warna berbeda untuk pembelian
                        'icon' => 'shopping-cart',
                        'deskripsi' => "Pembelian " . Str::limit($item->kode_pembelian, 15),
                        'tanggal_format' => Carbon::parse($item->tanggal_pembelian)->diffForHumans(),
                        'tanggal_asli' => $item->tanggal_pembelian,
                        'jumlah' => $item->total_harga
                    ];
                })
        );

        // Urutkan semua aktivitas berdasarkan tanggal asli dan ambil sejumlah limit
        $aktivitasTerbaru = $semuaAktivitas->sortByDesc('tanggal_asli')->take($limitAktivitas + 2); // Ambil sedikit lebih banyak untuk variasi

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