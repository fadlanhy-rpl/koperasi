<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembelian;
use App\Models\Barang; // Untuk katalog
use App\Models\UnitUsaha; // Untuk katalog

class PembelianAnggotaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:anggota']);
    }

    /**
     * Menampilkan riwayat pembelian barang anggota yang login.
     */
    public function showRiwayatPembelianSaya(Request $request)
    {
        $anggota = Auth::user();
        $query = $anggota->pembelians()
                         ->with(['kasir:id,name', 'detailPembelians:id,pembelian_id,barang_id,jumlah']) // Hanya ambil field yang perlu untuk hitung item
                         ->orderBy('tanggal_pembelian', 'desc');

        if ($request->filled('search_kode')) { // Filter berdasarkan kode pembelian
            $query->where('kode_pembelian', 'like', '%' . $request->search_kode . '%');
        }

        if ($request->filled('status_pembayaran_filter') && $request->status_pembayaran_filter != 'all') {
            $query->where('status_pembayaran', $request->status_pembayaran_filter);
        }
        
        if ($request->filled('tanggal_mulai_filter')) {
            $query->whereDate('tanggal_pembelian', '>=', $request->tanggal_mulai_filter);
        }
        if ($request->filled('tanggal_selesai_filter')) {
            $query->whereDate('tanggal_pembelian', '<=', $request->tanggal_selesai_filter);
        }


        $pembelians = $query->paginate(10)->withQueryString();
        
        // Menambahkan jumlah item ke setiap pembelian untuk tampilan
        $pembelians->getCollection()->transform(function ($pembelian) {
            $pembelian->jumlah_item = $pembelian->detailPembelians->sum('jumlah');
            return $pembelian;
        });

        $statuses = ['all' => 'Semua Status', 'lunas' => 'Lunas', 'belum_lunas' => 'Belum Lunas', 'cicilan' => 'Cicilan'];

        return view('anggota.pembelian.riwayat', compact('pembelians', 'statuses'));
    }

    /**
     * Menampilkan detail satu pembelian milik anggota yang login.
     */
    public function showDetailPembelianSaya(Pembelian $pembelian) // Route Model Binding
    {
        // Otorisasi menggunakan policy untuk memastikan anggota hanya bisa lihat miliknya
        $this->authorize('view', $pembelian); 

        $pembelian->load([
            'user:id,name,nomor_anggota', 
            'kasir:id,name', 
            'detailPembelians.barang:id,nama_barang,kode_barang,satuan', // Muat juga satuan barang
            'cicilans' => function ($query) { // Urutkan cicilan
                $query->orderBy('tanggal_bayar', 'desc')->orderBy('created_at', 'desc')->with('pengurus:id,name');
            }
        ]);
        
        $sisaTagihan = 0;
        if($pembelian->status_pembayaran !== 'lunas') {
            $totalSudahBayarCicilan = $pembelian->cicilans->sum('jumlah_bayar');
            $pembayaranAwalDiHeader = $pembelian->total_bayar;
            $sisaTagihan = $pembelian->total_harga - $pembayaranAwalDiHeader - $totalSudahBayarCicilan;
        }

        return view('anggota.pembelian.detail', compact('pembelian', 'sisaTagihan'));
    }

    /**
     * Menampilkan katalog barang untuk anggota (jika ada).
     */
    public function showKatalogBarang(Request $request)
    {
        $query = Barang::where('stok', '>', 0)->with('unitUsaha:id,nama_unit_usaha');

        if ($request->filled('search_barang_katalog')) {
            $searchTerm = $request->search_barang_katalog;
            $query->where(function($q) use ($searchTerm){
                $q->where('nama_barang', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kode_barang', 'like', '%' . $searchTerm . '%');
            });
        }
        if ($request->filled('unit_usaha_katalog') && $request->unit_usaha_katalog != '') {
            $query->where('unit_usaha_id', $request->unit_usaha_katalog);
        }

        $barangs = $query->orderBy('nama_barang')->paginate(12)->withQueryString();
        $unitUsahas = UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']);

        return view('anggota.pembelian.katalog', compact('barangs', 'unitUsahas'));
    }
}