<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SimpananSukarela;
use Carbon\Carbon;

class SimpananAnggotaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:anggota']);
    }

    /**
     * Menampilkan ringkasan dan riwayat semua simpanan anggota yang login.
     * Menangani request standar dan AJAX untuk paginasi tab dengan filter.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function showSimpananSaya(Request $request)
    {
        $anggota = Auth::user();
        $dataSimpanan = [];

        // Ambil parameter filter
        $searchTerm = $request->input('search', '');
        $selectedPeriod = $request->input('period', 'all');
        $sortBy = $request->input('sort_by', 'date');
        $sortOrder = $request->input('sort_order', 'desc');

        // Data Simpanan Pokok (tidak dipaginasi, tapi bisa difilter)
        $queryPokok = $anggota->simpananPokoks();
        
        // Apply filters untuk Pokok
        if (!empty($searchTerm)) {
            $queryPokok->where(function($q) use ($searchTerm) {
                $q->where('keterangan', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('pengurus', function($subQ) use ($searchTerm) {
                      $subQ->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Apply period filter untuk Pokok
        $queryPokok = $this->applyPeriodFilter($queryPokok, $selectedPeriod, 'tanggal_bayar');

        // Apply sorting untuk Pokok
        if ($sortBy === 'amount') {
            $queryPokok->orderBy('jumlah', $sortOrder);
        } else {
            $queryPokok->orderBy('tanggal_bayar', $sortOrder);
        }

        $riwayat_pokok = $queryPokok->with('pengurus')->get();
        $dataSimpanan['pokok'] = $riwayat_pokok;
        $dataSimpanan['total_pokok'] = $anggota->simpananPokoks()->sum('jumlah');

        // Data Simpanan Wajib (dipaginasi dengan filter)
        $perPageWajib = $request->input('per_page_wajib', 10);
        $queryWajib = $anggota->simpananWajibs();

        // Apply filters untuk Wajib
        if (!empty($searchTerm)) {
            $queryWajib->where('keterangan', 'like', '%' . $searchTerm . '%');
        }

        // Apply period filter untuk Wajib
        if ($selectedPeriod !== 'all') {
            $queryWajib = $this->applyPeriodFilterWajib($queryWajib, $selectedPeriod);
        }

        // Apply sorting untuk Wajib
        if ($sortBy === 'amount') {
            $queryWajib->orderBy('jumlah', $sortOrder);
        } else {
            $queryWajib->orderBy('tahun', $sortOrder)->orderBy('bulan', $sortOrder);
        }

        $riwayat_wajib = $queryWajib->paginate($perPageWajib, ['*'], 'page_wajib')->withQueryString();
        $dataSimpanan['wajib'] = $riwayat_wajib;
        $dataSimpanan['total_wajib'] = $anggota->simpananWajibs()->sum('jumlah');

        // Data Simpanan Sukarela (dipaginasi dengan filter)
        $perPageSukarela = $request->input('per_page_sukarela', 10);
        $querySukarela = $anggota->simpananSukarelas();

        // Apply filters untuk Sukarela
        if (!empty($searchTerm)) {
            $querySukarela->where(function($q) use ($searchTerm) {
                $q->where('keterangan', 'like', '%' . $searchTerm . '%')
                  ->orWhere('tipe_transaksi', 'like', '%' . $searchTerm . '%');
            });
        }

        // Apply period filter untuk Sukarela
        $querySukarela = $this->applyPeriodFilter($querySukarela, $selectedPeriod, 'tanggal_transaksi');

        // Apply sorting untuk Sukarela
        if ($sortBy === 'amount') {
            $querySukarela->orderBy('jumlah', $sortOrder);
        } else {
            $querySukarela->orderBy('tanggal_transaksi', $sortOrder)->orderBy('created_at', $sortOrder);
        }

        $riwayat_sukarela = $querySukarela->paginate($perPageSukarela, ['*'], 'page_sukarela')->withQueryString();
        $dataSimpanan['sukarela'] = $riwayat_sukarela;
        
        // Saldo sukarela terkini
        $transaksiTerakhirSukarela = $anggota->simpananSukarelas()->latest('tanggal_transaksi')->latest('id')->first();
        $dataSimpanan['saldo_sukarela_terkini'] = $transaksiTerakhirSukarela ? $transaksiTerakhirSukarela->saldo_sesudah : 0;

        // Menangani request AJAX untuk paginasi dan filter di dalam tab
        if ($request->ajax()) {
            $viewHtml = '';
            $paginationHtml = '';
            $tab = $request->input('tab', $request->has('page_wajib') ? 'wajib' : ($request->has('page_sukarela') ? 'sukarela' : 'pokok'));

            if ($tab === 'pokok') {
                $viewHtml = view('anggota.simpanan.partials._riwayat_pokok_table', ['riwayat_pokok' => $dataSimpanan['pokok']])->render();
                return response()->json(['html' => $viewHtml, 'pagination' => '']);
            } elseif ($tab === 'wajib') {
                $viewHtml = view('anggota.simpanan.partials._riwayat_wajib_table', ['riwayat_wajib' => $dataSimpanan['wajib']])->render();
                $paginationHtml = (string) $dataSimpanan['wajib']->links('vendor.pagination.tailwind-ajax');
                return response()->json(['html' => $viewHtml, 'pagination' => $paginationHtml]);
            } elseif ($tab === 'sukarela') {
                $viewHtml = view('anggota.simpanan.partials._riwayat_sukarela_table', ['riwayat_sukarela' => $dataSimpanan['sukarela']])->render();
                $paginationHtml = (string) $dataSimpanan['sukarela']->links('vendor.pagination.tailwind-ajax');
                return response()->json(['html' => $viewHtml, 'pagination' => $paginationHtml]);
            }
            
            return response()->json(['message' => 'Tab tidak valid untuk request AJAX.'], 400);
        }

        // Untuk request non-AJAX, kirim semua data ke view utama
        return view('anggota.simpanan.show', [
            'simpanan' => $dataSimpanan,
            'filters' => [
                'search' => $searchTerm,
                'period' => $selectedPeriod,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder
            ]
        ]);
    }

    /**
     * Apply period filter to query
     */
    private function applyPeriodFilter($query, $period, $dateColumn)
    {
        switch ($period) {
            case 'thisMonth':
                return $query->whereMonth($dateColumn, Carbon::now()->month)
                           ->whereYear($dateColumn, Carbon::now()->year);
            case 'lastMonth':
                $lastMonth = Carbon::now()->subMonth();
                return $query->whereMonth($dateColumn, $lastMonth->month)
                           ->whereYear($dateColumn, $lastMonth->year);
            case 'thisYear':
                return $query->whereYear($dateColumn, Carbon::now()->year);
            case 'lastYear':
                return $query->whereYear($dateColumn, Carbon::now()->subYear()->year);
            case 'last3Months':
                return $query->where($dateColumn, '>=', Carbon::now()->subMonths(3));
            case 'last6Months':
                return $query->where($dateColumn, '>=', Carbon::now()->subMonths(6));
            default:
                return $query;
        }
    }

    /**
     * Apply period filter specifically for Simpanan Wajib (uses bulan/tahun columns)
     */
    private function applyPeriodFilterWajib($query, $period)
    {
        switch ($period) {
            case 'thisMonth':
                return $query->where('bulan', Carbon::now()->month)
                           ->where('tahun', Carbon::now()->year);
            case 'lastMonth':
                $lastMonth = Carbon::now()->subMonth();
                return $query->where('bulan', $lastMonth->month)
                           ->where('tahun', $lastMonth->year);
            case 'thisYear':
                return $query->where('tahun', Carbon::now()->year);
            case 'lastYear':
                return $query->where('tahun', Carbon::now()->subYear()->year);
            case 'last3Months':
                $threeMonthsAgo = Carbon::now()->subMonths(3);
                return $query->where(function($q) use ($threeMonthsAgo) {
                    $q->where('tahun', '>', $threeMonthsAgo->year)
                      ->orWhere(function($subQ) use ($threeMonthsAgo) {
                          $subQ->where('tahun', $threeMonthsAgo->year)
                               ->where('bulan', '>=', $threeMonthsAgo->month);
                      });
                });
            case 'last6Months':
                $sixMonthsAgo = Carbon::now()->subMonths(6);
                return $query->where(function($q) use ($sixMonthsAgo) {
                    $q->where('tahun', '>', $sixMonthsAgo->year)
                      ->orWhere(function($subQ) use ($sixMonthsAgo) {
                          $subQ->where('tahun', $sixMonthsAgo->year)
                               ->where('bulan', '>=', $sixMonthsAgo->month);
                      });
                });
            default:
                return $query;
        }
    }
}
