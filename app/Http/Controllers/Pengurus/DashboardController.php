<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Barang;
use App\Models\SimpananWajib;
use App\Models\SimpananSukarela;
// use App\Models\Cicilan; // Tidak digunakan secara langsung di index() saat ini
use App\Models\UnitUsaha; // <-- TAMBAHKAN IMPORT INI
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Pastikan DB juga di-import jika belum

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth', 'role:admin,pengurus']); 
        // Di-handle di route group, jadi bisa dikomentari/dihapus
    }

    public function index()
    {
        // Data untuk Stats Cards
        $transaksiHariIni = Pembelian::whereDate('tanggal_pembelian', Carbon::today())->count();
        
        $batasStokMenipis = 10; 
        $stokMenipisCount = Barang::where('stok', '<=', $batasStokMenipis)->where('stok', '>', 0)->count();

        $simpananWajibBulanIni = SimpananWajib::whereMonth('tanggal_bayar', Carbon::now()->month)
                                            ->whereYear('tanggal_bayar', Carbon::now()->year)
                                            ->sum('jumlah');
        $simpananSukarelaBulanIniSetor = SimpananSukarela::where('tipe_transaksi', 'setor')
                                            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
                                            ->whereYear('tanggal_transaksi', Carbon::now()->year)
                                            ->sum('jumlah');
        $simpananSukarelaBulanIniTarik = SimpananSukarela::where('tipe_transaksi', 'tarik')
                                            ->whereMonth('tanggal_transaksi', Carbon::now()->month)
                                            ->whereYear('tanggal_transaksi', Carbon::now()->year)
                                            ->sum('jumlah');
        $totalSimpananMasukBulanIni = $simpananWajibBulanIni + ($simpananSukarelaBulanIniSetor - $simpananSukarelaBulanIniTarik);

        $cicilanJatuhTempoCount = Pembelian::where('status_pembayaran', 'cicilan')->count(); 

        // Data untuk "Stok Menipis" detail
        $stokMenipisDetail = Barang::where('stok', '<=', $batasStokMenipis)
                                    ->where('stok', '>', 0)
                                    ->with('unitUsaha:id,nama_unit_usaha')
                                    ->orderBy('stok', 'asc')
                                    ->take(5)
                                    ->get(['id', 'nama_barang', 'stok', 'unit_usaha_id']);

        // Data untuk Chart Penjualan Harian per Unit Usaha
        // Sekarang 'UnitUsaha' akan dikenali
        $penjualanHariIniPerUnit = UnitUsaha::withSum(['detailPembelians as total_penjualan_hari_ini' => function($queryDetail) {
            $queryDetail->whereHas('pembelian', function($queryPembelian){
                $queryPembelian->whereDate('tanggal_pembelian', Carbon::today());
            });
        }], 'subtotal') // Menjumlahkan subtotal dari detail pembelian terkait
        ->get();
        
        $dataPenjualanHarianLabels = $penjualanHariIniPerUnit->pluck('nama_unit_usaha')->toArray();
        // Ambil hasil sum yang sudah dialias
        $dataPenjualanHarianData = $penjualanHariIniPerUnit->pluck('total_penjualan_hari_ini')->map(function ($value) {
            return (float) $value; // Pastikan nilainya float
        })->toArray();
        
        $dataPenjualanHarianChart = [
            'labels' => $dataPenjualanHarianLabels,
            'data' => $dataPenjualanHarianData,
        ];
        
        // Data untuk Transaksi Terbaru
        $transaksiTerbaru = Pembelian::with(['user:id,name', 'detailPembelians.barang.unitUsaha:id,nama_unit_usaha'])
                                    ->whereDate('tanggal_pembelian', Carbon::today()) // Hanya transaksi hari ini
                                    ->orderBy('tanggal_pembelian', 'desc')
                                    ->take(5)
                                    ->get();
                                    
        $transaksiTerbaruFormatted = $transaksiTerbaru->map(function($trx) {
            $unitUsahaNama = 'N/A'; 
            if ($trx->detailPembelians->isNotEmpty()) {
                $unitUsahaNamaList = $trx->detailPembelians->map(function($detail) {
                    return $detail->barang->unitUsaha->nama_unit_usaha ?? null;
                })->filter()->unique(); // Ambil nama unit usaha unik dan filter null

                if($unitUsahaNamaList->count() > 1) {
                    $unitUsahaNama = 'Multi Unit';
                } elseif($unitUsahaNamaList->count() == 1) {
                    $unitUsahaNama = $unitUsahaNamaList->first();
                }
            }
            return (object)[
                'waktu' => Carbon::parse($trx->tanggal_pembelian)->format('H:i'),
                'anggota_name' => $trx->user->name,
                'unit_usaha' => $unitUsahaNama,
                'total' => $trx->total_harga,
                'status_pembayaran' => $trx->status_pembayaran, // pastikan ini adalah status_pembayaran, bukan status
            ];
        });


        return view('pengurus.dashboard', compact(
            'transaksiHariIni',
            'stokMenipisCount',
            'totalSimpananMasukBulanIni',
            'cicilanJatuhTempoCount',
            'stokMenipisDetail',
            'dataPenjualanHarianChart',
            'transaksiTerbaruFormatted'
        ));
    }
}