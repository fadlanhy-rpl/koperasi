<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // <-- IMPORT SUDAH ADA, PASTIKAN BENAR
// use App\Models\SimpananPokok; // Tidak perlu jika akses via relasi $anggota->simpananPokoks()
// use App\Models\SimpananWajib; // Tidak perlu jika akses via relasi
// use App\Models\SimpananSukarela; // Tidak perlu jika akses via relasi

class SidebarAnggotaComposer
{
    public function compose(View $view)
    {
        // Gunakan Auth::check() dan Auth::user()
        if (Auth::check() && Auth::user()->isAnggota()) {
            $anggota = Auth::user(); // $anggota sudah instance User yang login

            $simpananPokok = $anggota->simpananPokoks()->sum('jumlah');
            $totalSimpananWajib = $anggota->simpananWajibs()->sum('jumlah');
            
            $transaksiTerakhirSukarela = $anggota->simpananSukarelas()
                                                ->orderBy('tanggal_transaksi', 'desc')
                                                ->orderBy('created_at', 'desc')->first();
            $saldoSimpananSukarela = $transaksiTerakhirSukarela ? $transaksiTerakhirSukarela->saldo_sesudah : 0;
            
            $view->with([
                'sidebarSimpananPokok' => $simpananPokok,
                'sidebarTotalSimpananWajib' => $totalSimpananWajib,
                'sidebarSaldoSimpananSukarela' => $saldoSimpananSukarela,
            ]);
        } else {
             $view->with([
                'sidebarSimpananPokok' => 0,
                'sidebarTotalSimpananWajib' => 0,
                'sidebarSaldoSimpananSukarela' => 0,
            ]);
        }
    }
}