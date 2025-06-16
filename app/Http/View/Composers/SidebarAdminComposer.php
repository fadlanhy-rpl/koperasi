<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHKAN IMPORT INI
use App\Models\User;
use App\Models\SimpananPokok;
use App\Models\SimpananWajib;
use App\Models\SimpananSukarela;
use App\Models\Pembelian;
use Carbon\Carbon;

class SidebarAdminComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Gunakan Auth::check() dan Auth::user()
        if (Auth::check() && Auth::user()->isAdmin()) {
            $totalAnggota = User::where('role', 'anggota')->count();
            
            $saldoTotalSukarela = 0;
            $anggotaDenganSukarela = User::where('role', 'anggota')
                                        ->whereHas('simpananSukarelas')
                                        ->with(['simpananSukarelas' => function ($query) {
                                            $query->orderBy('tanggal_transaksi', 'desc')->orderBy('created_at', 'desc')->limit(1);
                                        }])
                                        ->get();
            foreach ($anggotaDenganSukarela as $anggotaLoop) { // Ganti nama variabel agar tidak konflik dengan $anggota di scope lain
                if ($anggotaLoop->simpananSukarelas->isNotEmpty()) {
                    $saldoTotalSukarela += $anggotaLoop->simpananSukarelas->first()->saldo_sesudah;
                }
            }

            $totalSimpanan = SimpananPokok::sum('jumlah') +
                             SimpananWajib::sum('jumlah') +
                             $saldoTotalSukarela;

            $penjualanHariIni = Pembelian::whereDate('tanggal_pembelian', Carbon::today())->sum('total_harga');

            $view->with([
                'sidebarTotalAnggota' => $totalAnggota,
                'sidebarTotalSimpanan' => $totalSimpanan, 
                'sidebarPenjualanHariIni' => $penjualanHariIni,
            ]);
        } else {
            $view->with([
                'sidebarTotalAnggota' => 0,
                'sidebarTotalSimpanan' => 0,
                'sidebarPenjualanHariIni' => 0,
            ]);
        }
    }
}