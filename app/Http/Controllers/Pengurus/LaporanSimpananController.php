<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SimpananPokok;
use App\Models\SimpananWajib;
use App\Models\SimpananSukarela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Exception;

class LaporanSimpananController extends Controller
{
    public function __construct()
    {
        // Middleware sudah diterapkan pada level route group
    }

    /**
     * Enhanced error handling and data validation
     */
    private function validateRequest(Request $request, array $rules = [])
    {
        $defaultRules = [
            'search_anggota' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
            'bulan' => ['nullable', 'integer', 'min:1', 'max:12'],
            'tahun' => ['nullable', 'integer', 'min:2020', 'max:' . (date('Y') + 1)]
        ];

        return $request->validate(array_merge($defaultRules, $rules));
    }

    /**
     * Menampilkan rekapitulasi total semua jenis simpanan dengan enhanced features.
     */
    public function rekapTotalSimpanan(Request $request)
    {
        try {
            $this->validateRequest($request);

            $totalSimpananPokok = SimpananPokok::sum('jumlah');
            $totalSimpananWajib = SimpananWajib::sum('jumlah');

            $saldoTotalSukarela = 0;
            // Ambil semua user dengan transaksi sukarela, lalu ambil saldo terakhir masing-masing
            $anggotaDenganSukarela = User::where('role', 'anggota')
                                        ->whereHas('simpananSukarelas')
                                        ->with(['simpananSukarelas' => function ($query) {
                                            $query->orderBy('tanggal_transaksi', 'desc')
                                                  ->orderBy('created_at', 'desc')
                                                  ->limit(1);
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

            // Get additional statistics for charts
            $statistikTambahan = $this->getAdditionalStatistics();

            // Handle export requests
            if ($request->has('export')) {
                return $this->handleRekapExport($request, $rekapitulasi, $statistikTambahan);
            }

            // Handle AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'rekapitulasi' => $rekapitulasi,
                    'statistik_tambahan' => $statistikTambahan,
                    'html' => view('pengurus.laporan.simpanan.partials._rekap_cards', compact('rekapitulasi', 'statistikTambahan'))->render()
                ]);
            }

            return view('pengurus.laporan.simpanan.rekap_total', compact('rekapitulasi', 'statistikTambahan'));

        } catch (Exception $e) {
            Log::error('Error in rekapTotalSimpanan: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat memuat data.',
                    'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat memuat rekapitulasi simpanan.');
        }
    }

    /**
     * Get additional statistics for enhanced reporting
     */
    private function getAdditionalStatistics()
    {
        try {
            // Jumlah anggota aktif
            $jumlahAnggotaAktif = User::where('role', 'anggota')->count();
            
            // Anggota dengan simpanan sukarela
            $anggotaDenganSukarela = User::where('role', 'anggota')
                                        ->whereHas('simpananSukarelas')
                                        ->count();
            
            // Rata-rata simpanan per anggota
            $totalSimpanan = SimpananPokok::sum('jumlah') + SimpananWajib::sum('jumlah');
            $rataSimpananPerAnggota = $jumlahAnggotaAktif > 0 ? $totalSimpanan / $jumlahAnggotaAktif : 0;

            // Growth statistics (contoh: bandingkan dengan bulan lalu)
            $bulanLalu = Carbon::now()->subMonth();
            $totalBulanLalu = SimpananWajib::whereMonth('created_at', $bulanLalu->month)
                                          ->whereYear('created_at', $bulanLalu->year)
                                          ->sum('jumlah');
            
            $totalBulanIni = SimpananWajib::whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->sum('jumlah');
            
            $pertumbuhanPersen = $totalBulanLalu > 0 ? 
                (($totalBulanIni - $totalBulanLalu) / $totalBulanLalu) * 100 : 0;

            return [
                'jumlah_anggota_aktif' => $jumlahAnggotaAktif,
                'anggota_dengan_sukarela' => $anggotaDenganSukarela,
                'rata_simpanan_per_anggota' => $rataSimpananPerAnggota,
                'pertumbuhan_persen' => $pertumbuhanPersen,
                'total_bulan_ini' => $totalBulanIni,
                'total_bulan_lalu' => $totalBulanLalu
            ];

        } catch (Exception $e) {
            Log::error('Error getting additional statistics: ' . $e->getMessage());
            return [
                'jumlah_anggota_aktif' => 0,
                'anggota_dengan_sukarela' => 0,
                'rata_simpanan_per_anggota' => 0,
                'pertumbuhan_persen' => 0,
                'total_bulan_ini' => 0,
                'total_bulan_lalu' => 0
            ];
        }
    }

    /**
     * Menampilkan laporan rincian simpanan per anggota dengan enhanced features.
     */
    public function rincianSimpananPerAnggota(Request $request)
    {
        try {
            $this->validateRequest($request);

            $anggotaQuery = User::where('role', 'anggota')
                ->with([
                    'simpananPokoks' => function($q) { 
                        $q->select(DB::raw('user_id, SUM(jumlah) as total_pokok'))->groupBy('user_id'); 
                    },
                    'simpananWajibs' => function($q) { 
                        $q->select(DB::raw('user_id, SUM(jumlah) as total_wajib'))->groupBy('user_id'); 
                    },
                    'simpananSukarelas' => function($q) {
                        $q->select('user_id', 'saldo_sesudah')
                          ->orderByDesc('tanggal_transaksi')
                          ->orderByDesc('created_at')
                          ->limit(1);
                    }
                ]);

            if ($request->filled('search_anggota')) {
                $searchTerm = $request->search_anggota;
                $anggotaQuery->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('nomor_anggota', 'like', '%' . $searchTerm . '%');
                });
            }

            $perPage = $request->input('per_page', 15);
            $laporan_per_anggota = $anggotaQuery->orderBy('name')
                                              ->paginate($perPage)
                                              ->withQueryString();

            // Transform data for better view handling
            $laporan_per_anggota->getCollection()->transform(function ($anggota) {
                $anggota->total_simpanan_pokok_view = $anggota->simpananPokoks->first()->total_pokok ?? 0;
                $anggota->total_simpanan_wajib_view = $anggota->simpananWajibs->first()->total_wajib ?? 0;
                $anggota->saldo_simpanan_sukarela_view = $anggota->simpananSukarelas->first()->saldo_sesudah ?? 0;
                return $anggota;
            });

            // Calculate summary statistics
            $ringkasan = $this->calculateRincianSummary($laporan_per_anggota);

            // Handle export requests
            if ($request->has('export')) {
                return $this->handleRincianExport($request, $laporan_per_anggota, $ringkasan);
            }

            // Handle AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('pengurus.laporan.simpanan.partials._rincian_rows', compact('laporan_per_anggota'))->render(),
                    'pagination' => (string) $laporan_per_anggota->links('vendor.pagination.tailwind'),
                    'ringkasan' => $ringkasan
                ]);
            }
            
            return view('pengurus.laporan.simpanan.rincian_per_anggota', compact('laporan_per_anggota', 'ringkasan'));

        } catch (Exception $e) {
            Log::error('Error in rincianSimpananPerAnggota: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat memuat data.',
                    'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan rincian simpanan.');
        }
    }

    /**
     * Calculate summary for rincian per anggota
     */
    private function calculateRincianSummary($laporan_per_anggota)
    {
        try {
            $totalPokok = 0;
            $totalWajib = 0;
            $totalSukarela = 0;
            $jumlahAnggota = $laporan_per_anggota->count();

            foreach ($laporan_per_anggota as $anggota) {
                $totalPokok += $anggota->total_simpanan_pokok_view;
                $totalWajib += $anggota->total_simpanan_wajib_view;
                $totalSukarela += $anggota->saldo_simpanan_sukarela_view;
            }

            return [
                'total_pokok' => $totalPokok,
                'total_wajib' => $totalWajib,
                'total_sukarela' => $totalSukarela,
                'grand_total' => $totalPokok + $totalWajib + $totalSukarela,
                'jumlah_anggota' => $jumlahAnggota,
                'rata_rata_per_anggota' => $jumlahAnggota > 0 ? ($totalPokok + $totalWajib + $totalSukarela) / $jumlahAnggota : 0
            ];

        } catch (Exception $e) {
            Log::error('Error calculating rincian summary: ' . $e->getMessage());
            return [
                'total_pokok' => 0,
                'total_wajib' => 0,
                'total_sukarela' => 0,
                'grand_total' => 0,
                'jumlah_anggota' => 0,
                'rata_rata_per_anggota' => 0
            ];
        }
    }

    /**
     * Laporan simpanan wajib yang belum dibayar per periode dengan enhanced features.
     */
    public function simpananWajibBelumBayar(Request $request)
    {
        try {
            $this->validateRequest($request);

            $bulan = $request->input('bulan', Carbon::now()->month);
            $tahun = $request->input('tahun', Carbon::now()->year);

            $anggota_belum_bayar_wajib = User::where('role', 'anggota')
                ->whereDoesntHave('simpananWajibs', function ($query) use ($bulan, $tahun) {
                    $query->where('bulan', $bulan)->where('tahun', $tahun);
                })
                ->select('id', 'name', 'nomor_anggota', 'email')
                ->orderBy('name');

            $perPage = $request->input('per_page', 25);
            $anggota_belum_bayar_wajib = $anggota_belum_bayar_wajib->paginate($perPage)->withQueryString();

            $periode = [
                'bulan' => $bulan,
                'tahun' => $tahun,
            ];

            // Calculate additional statistics
            $totalAnggota = User::where('role', 'anggota')->count();
            $anggotaSudahBayar = $totalAnggota - $anggota_belum_bayar_wajib->total();
            $persentaseBayar = $totalAnggota > 0 ? ($anggotaSudahBayar / $totalAnggota) * 100 : 0;

            $statistik = [
                'total_anggota' => $totalAnggota,
                'sudah_bayar' => $anggotaSudahBayar,
                'belum_bayar' => $anggota_belum_bayar_wajib->total(),
                'persentase_bayar' => $persentaseBayar
            ];

            // Handle export requests
            if ($request->has('export')) {
                return $this->handleWajibBelumBayarExport($request, $anggota_belum_bayar_wajib, $periode, $statistik);
            }

            // Handle AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('pengurus.laporan.simpanan.partials._wajib_belum_bayar_rows', compact('anggota_belum_bayar_wajib'))->render(),
                    'pagination' => (string) $anggota_belum_bayar_wajib->links('vendor.pagination.tailwind'),
                    'statistik' => $statistik
                ]);
            }
            
            return view('pengurus.laporan.simpanan.wajib_belum_bayar', compact('anggota_belum_bayar_wajib', 'periode', 'statistik'));

        } catch (Exception $e) {
            Log::error('Error in simpananWajibBelumBayar: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat memuat data.',
                    'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan simpanan wajib.');
        }
    }

    /**
     * Handle rekap export
     */
    private function handleRekapExport($request, $rekapitulasi, $statistikTambahan)
    {
        try {
            $exportData = [
                'rekapitulasi' => $rekapitulasi,
                'statistik_tambahan' => $statistikTambahan,
                'generated_at' => now()
            ];

            if ($request->export == 'pdf') {
                $pdf = Pdf::loadView('pengurus.laporan.simpanan.exports.pdf_rekap_total', $exportData)
                          ->setPaper('a4', 'portrait');
                return $pdf->download('rekap-total-simpanan-'.date('YmdHis').'.pdf');
            } 
            
            if ($request->export == 'excel') {
                return $this->exportRekapToCSV($rekapitulasi, $statistikTambahan);
            }

        } catch (Exception $e) {
            Log::error('Rekap export error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data rekapitulasi.');
        }
    }

    /**
     * Handle rincian export
     */
    private function handleRincianExport($request, $laporan_per_anggota, $ringkasan)
    {
        try {
            $exportData = [
                'laporan_per_anggota' => $laporan_per_anggota,
                'ringkasan' => $ringkasan,
                'generated_at' => now()
            ];

            if ($request->export == 'pdf') {
                $pdf = Pdf::loadView('pengurus.laporan.simpanan.exports.pdf_rincian_per_anggota', $exportData)
                          ->setPaper('a4', 'landscape');
                return $pdf->download('rincian-simpanan-per-anggota-'.date('YmdHis').'.pdf');
            } 
            
            if ($request->export == 'excel') {
                return $this->exportRincianToCSV($laporan_per_anggota);
            }

        } catch (Exception $e) {
            Log::error('Rincian export error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data rincian simpanan.');
        }
    }

    /**
     * Handle wajib belum bayar export
     */
    private function handleWajibBelumBayarExport($request, $anggota_belum_bayar_wajib, $periode, $statistik)
    {
        try {
            $exportData = [
                'anggota_belum_bayar_wajib' => $anggota_belum_bayar_wajib,
                'periode' => $periode,
                'statistik' => $statistik,
                'generated_at' => now()
            ];

            if ($request->export == 'pdf') {
                $pdf = Pdf::loadView('pengurus.laporan.simpanan.exports.pdf_wajib_belum_bayar', $exportData)
                          ->setPaper('a4', 'portrait');
                return $pdf->download('simpanan-wajib-belum-bayar-'.date('YmdHis').'.pdf');
            } 
            
            if ($request->export == 'excel') {
                return $this->exportWajibBelumBayarToCSV($anggota_belum_bayar_wajib, $periode);
            }

        } catch (Exception $e) {
            Log::error('Wajib belum bayar export error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data simpanan wajib belum bayar.');
        }
    }

    /**
     * Export rekap to CSV
     */
    private function exportRekapToCSV($rekapitulasi, $statistikTambahan)
    {
        $fileName = 'rekap-total-simpanan-'.date('YmdHis').'.csv';
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use($rekapitulasi, $statistikTambahan) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['REKAPITULASI TOTAL SIMPANAN']);
            fputcsv($file, ['Tanggal Export: ' . now()->format('d/m/Y H:i:s')]);
            fputcsv($file, []);
            
            // Data rekapitulasi
            fputcsv($file, ['Jenis Simpanan', 'Total (Rupiah)']);
            fputcsv($file, ['Total Simpanan Pokok', number_format($rekapitulasi['total_simpanan_pokok'], 0, ',', '.')]);
            fputcsv($file, ['Total Simpanan Wajib', number_format($rekapitulasi['total_simpanan_wajib'], 0, ',', '.')]);
            fputcsv($file, ['Total Saldo Simpanan Sukarela', number_format($rekapitulasi['total_simpanan_sukarela_aktif'], 0, ',', '.')]);
            fputcsv($file, ['GRAND TOTAL', number_format($rekapitulasi['grand_total_simpanan'], 0, ',', '.')]);
            
            fputcsv($file, []);
            fputcsv($file, ['STATISTIK TAMBAHAN']);
            fputcsv($file, ['Jumlah Anggota Aktif', $statistikTambahan['jumlah_anggota_aktif']]);
            fputcsv($file, ['Anggota dengan Simpanan Sukarela', $statistikTambahan['anggota_dengan_sukarela']]);
            fputcsv($file, ['Rata-rata Simpanan per Anggota', number_format($statistikTambahan['rata_simpanan_per_anggota'], 0, ',', '.')]);
            fputcsv($file, ['Pertumbuhan (%)', number_format($statistikTambahan['pertumbuhan_persen'], 2, ',', '.')]);
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export rincian to CSV
     */
    private function exportRincianToCSV($laporan_per_anggota)
    {
        $fileName = 'rincian-simpanan-per-anggota-'.date('YmdHis').'.csv';
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No.', 'Nama Anggota', 'No. Anggota', 'Total Simp. Pokok', 'Total Simp. Wajib', 'Saldo Simp. Sukarela', 'Total Semua Simpanan'];
        
        $callback = function() use($laporan_per_anggota, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);
            
            foreach ($laporan_per_anggota as $index => $anggota) {
                fputcsv($file, [
                    $index + 1,
                    $anggota->name,
                    $anggota->nomor_anggota ?? '-',
                    $anggota->total_simpanan_pokok_view,
                    $anggota->total_simpanan_wajib_view,
                    $anggota->saldo_simpanan_sukarela_view,
                    $anggota->total_simpanan_pokok_view + $anggota->total_simpanan_wajib_view + $anggota->saldo_simpanan_sukarela_view
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export wajib belum bayar to CSV
     */
    private function exportWajibBelumBayarToCSV($anggota_belum_bayar_wajib, $periode)
    {
        $fileName = 'simpanan-wajib-belum-bayar-'.date('YmdHis').'.csv';
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No.', 'Nama Anggota', 'No. Anggota', 'Email'];
        
        $callback = function() use($anggota_belum_bayar_wajib, $columns, $periode) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header info
            fputcsv($file, ['LAPORAN SIMPANAN WAJIB BELUM BAYAR']);
            fputcsv($file, ['Periode: ' . Carbon::create()->month($periode['bulan'])->translatedFormat('F') . ' ' . $periode['tahun']]);
            fputcsv($file, ['Tanggal Export: ' . now()->format('d/m/Y H:i:s')]);
            fputcsv($file, []);
            
            fputcsv($file, $columns);
            
            foreach ($anggota_belum_bayar_wajib as $index => $anggota) {
                fputcsv($file, [
                    $index + 1,
                    $anggota->name,
                    $anggota->nomor_anggota ?? '-',
                    $anggota->email
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}
