<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SimpananSukarela; // Hanya perlu ini jika ingin query saldo terakhir secara eksplisit
// Model SimpananPokok dan SimpananWajib diakses via relasi User

class SimpananAnggotaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:anggota']);
    }

    /**
     * Menampilkan ringkasan dan riwayat semua simpanan anggota yang login.
     * Menangani request standar dan AJAX untuk paginasi tab.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function showSimpananSaya(Request $request)
    {
        $anggota = Auth::user();
        $dataSimpanan = [];

        // Data Simpanan Pokok (tidak dipaginasi)
        $riwayat_pokok = $anggota->simpananPokoks()
                                ->orderBy('tanggal_bayar', 'desc')
                                ->get(); // Tidak perlu ->with('pengurus') jika tidak ditampilkan ke anggota
        $dataSimpanan['pokok'] = $riwayat_pokok;
        $dataSimpanan['total_pokok'] = $riwayat_pokok->sum('jumlah');

        // Data Simpanan Wajib (dipaginasi)
        $perPageWajib = $request->input('per_page_wajib', 10);
        $riwayat_wajib = $anggota->simpananWajibs()
                                   ->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')
                                   ->paginate($perPageWajib, ['*'], 'page_wajib')->withQueryString();
        $dataSimpanan['wajib'] = $riwayat_wajib;
        $dataSimpanan['total_wajib'] = $anggota->simpananWajibs()->sum('jumlah'); // Total keseluruhan

        // Data Simpanan Sukarela (dipaginasi)
        $perPageSukarela = $request->input('per_page_sukarela', 10);
        $riwayat_sukarela = $anggota->simpananSukarelas()
                                           ->orderBy('tanggal_transaksi', 'desc')->orderBy('created_at', 'desc')
                                           ->paginate($perPageSukarela, ['*'], 'page_sukarela')->withQueryString();
        $dataSimpanan['sukarela'] = $riwayat_sukarela;
        
        // Saldo sukarela terkini
        $transaksiTerakhirSukarela = $anggota->simpananSukarelas()->latest('tanggal_transaksi')->latest('id')->first();
        $dataSimpanan['saldo_sukarela_terkini'] = $transaksiTerakhirSukarela ? $transaksiTerakhirSukarela->saldo_sesudah : 0;

        // Menangani request AJAX untuk paginasi di dalam tab
        if ($request->ajax()) {
            $viewHtml = '';
            $paginationHtml = '';
            $tab = $request->input('tab', $request->has('page_wajib') ? 'wajib' : ($request->has('page_sukarela') ? 'sukarela' : null));

            if ($tab === 'wajib') {
                $viewHtml = view('anggota.simpanan.partials._riwayat_wajib_table', ['riwayat_wajib' => $dataSimpanan['wajib']])->render();
                $paginationHtml = (string) $dataSimpanan['wajib']->links('vendor.pagination.tailwind-ajax');
                return response()->json(['html' => $viewHtml, 'pagination' => $paginationHtml]);
            } elseif ($tab === 'sukarela') {
                $viewHtml = view('anggota.simpanan.partials._riwayat_sukarela_table', ['riwayat_sukarela' => $dataSimpanan['sukarela']])->render();
                $paginationHtml = (string) $dataSimpanan['sukarela']->links('vendor.pagination.tailwind-ajax');
                return response()->json(['html' => $viewHtml, 'pagination' => $paginationHtml]);
            }
            // Jika tidak ada tab spesifik di AJAX, mungkin kirim error atau default
            return response()->json(['message' => 'Tab tidak valid untuk request AJAX.'], 400);
        }

        // Untuk request non-AJAX, kirim semua data ke view utama
        return view('anggota.simpanan.show', ['simpanan' => $dataSimpanan]);
    }
}