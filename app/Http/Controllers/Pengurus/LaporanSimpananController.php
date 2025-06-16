<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SimpananPokok;
use App\Models\SimpananWajib;
use App\Models\SimpananSukarela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan DB di-import jika digunakan untuk query kompleks
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Jika ada Auth::id() atau Auth::user()

class LaporanSimpananController extends Controller
{
    public function __construct()
    {
        // Middleware sudah diterapkan pada level route group
        //$this->middleware(['auth', 'role:admin,pengurus']);
    }

    /**
     * Menampilkan rekapitulasi total semua jenis simpanan.
     */
    public function rekapTotalSimpanan(Request $request) // Tambahkan Request $request jika ada filter nantinya
    {
        $totalSimpananPokok = SimpananPokok::sum('jumlah');
        $totalSimpananWajib = SimpananWajib::sum('jumlah');

        $saldoTotalSukarela = 0;
        // Ambil semua user dengan transaksi sukarela, lalu ambil saldo terakhir masing-masing
        $anggotaDenganSukarela = User::where('role', 'anggota')
                                    ->whereHas('simpananSukarelas')
                                    ->with(['simpananSukarelas' => function ($query) {
                                        // Urutkan untuk mendapatkan transaksi terakhir sebagai yang pertama
                                        $query->orderBy('tanggal_transaksi', 'desc')->orderBy('created_at', 'desc')->limit(1);
                                    }])
                                    ->get();
        
        foreach ($anggotaDenganSukarela as $anggota) {
            if ($anggota->simpananSukarelas->isNotEmpty()) {
                $saldoTotalSukarela += $anggota->simpananSukarelas->first()->saldo_sesudah;
            }
        }
        
        $rekapitulasi = [
            'total_simpanan_pokok' => (float) $totalSimpananPokok,
            'total_simpanan_wajib' => (float) $totalSimpananWajib,
            'total_simpanan_sukarela_aktif' => (float) $saldoTotalSukarela,
            'grand_total_simpanan' => (float) ($totalSimpananPokok + $totalSimpananWajib + $saldoTotalSukarela),
        ];

        // Mengembalikan view Blade, bukan JSON
        return view('pengurus.laporan.simpanan.rekap_total', compact('rekapitulasi'));
    }

    /**
     * Menampilkan laporan rincian simpanan per anggota.
     */
    public function rincianSimpananPerAnggota(Request $request)
    {
        $anggotaQuery = User::where('role', 'anggota')
            ->with([
                // Menggunakan subquery untuk menghitung total simpanan pokok
                'simpananPokoks' => function($q) { 
                    $q->select(DB::raw('user_id, SUM(jumlah) as total_pokok'))->groupBy('user_id'); 
                },
                // Menggunakan subquery untuk menghitung total simpanan wajib
                'simpananWajibs' => function($q) { 
                    $q->select(DB::raw('user_id, SUM(jumlah) as total_wajib'))->groupBy('user_id'); 
                },
                // Mengambil transaksi sukarela terakhir untuk mendapatkan saldo
                'simpananSukarelas' => function($q) {
                    $q->select('user_id', 'saldo_sesudah')
                      ->orderByDesc('tanggal_transaksi')
                      ->orderByDesc('created_at') // Tie breaker
                      ->limit(1); // Hanya transaksi terakhir per user
                }
            ]);

        if ($request->filled('search_anggota')) {
            $searchTerm = $request->search_anggota;
            $anggotaQuery->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $searchTerm . '%');
            });
        }

        $laporan_per_anggota = $anggotaQuery->orderBy('name')
                                          ->paginate($request->input('per_page', 15))
                                          ->withQueryString();

        // Memproses data untuk tampilan yang lebih baik di view (sudah dilakukan transform di controller sebelumnya)
        // Kita bisa pastikan accessor ada di model User atau lakukan di sini jika perlu
        $laporan_per_anggota->getCollection()->transform(function ($anggota) {
            $anggota->total_simpanan_pokok_view = $anggota->simpananPokoks->first()->total_pokok ?? 0;
            $anggota->total_simpanan_wajib_view = $anggota->simpananWajibs->first()->total_wajib ?? 0;
            $anggota->saldo_simpanan_sukarela_view = $anggota->simpananSukarelas->first()->saldo_sesudah ?? 0;
            // Hapus relasi mentah agar tidak membebani jika tidak dipakai langsung di Blade
            // unset($anggota->simpananPokoks, $anggota->simpananWajibs, $anggota->simpananSukarelas);
            return $anggota;
        });
        
        return view('pengurus.laporan.simpanan.rincian_per_anggota', compact('laporan_per_anggota'));
    }

    /**
     * Laporan simpanan wajib yang belum dibayar per periode.
     */
    public function simpananWajibBelumBayar(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $anggota_belum_bayar_wajib = User::where('role', 'anggota')
            ->whereDoesntHave('simpananWajibs', function ($query) use ($bulan, $tahun) {
                $query->where('bulan', $bulan)->where('tahun', $tahun);
            })
            ->select('id', 'name', 'nomor_anggota', 'email')
            ->orderBy('name')
            ->paginate($request->input('per_page', 25))
            ->withQueryString();

        $periode = [
            'bulan' => $bulan,
            'tahun' => $tahun,
        ];
        
        return view('pengurus.laporan.simpanan.wajib_belum_bayar', compact('anggota_belum_bayar_wajib', 'periode'));
    }
}