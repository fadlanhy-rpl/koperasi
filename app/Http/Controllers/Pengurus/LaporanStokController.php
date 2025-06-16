<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\UnitUsaha;
use App\Models\HistoriStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanStokController extends Controller
{
    public function __construct()
    {
        // Middleware
    }

    public function daftarStokTerkini(Request $request)
    {
        $baseFilterQuery = Barang::query();

        if ($request->filled('search_barang')) {
            $searchTerm = $request->search_barang;
            $baseFilterQuery->where(function($q) use ($searchTerm){
                $q->where('nama_barang', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kode_barang', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('unit_usaha_id') && $request->unit_usaha_id != '') {
            $baseFilterQuery->where('unit_usaha_id', $request->unit_usaha_id);
        }

        if ($request->filled('stok_kurang_dari') && is_numeric($request->stok_kurang_dari)) {
            $baseFilterQuery->where('stok', '<=', (int)$request->stok_kurang_dari);
        }

        $queryDaftarBarang = clone $baseFilterQuery;
        $daftar_stok = $queryDaftarBarang->with('unitUsaha:id,nama_unit_usaha')
                                     ->select('id', 'unit_usaha_id', 'nama_barang', 'kode_barang', 'stok', 'satuan', 'harga_beli', 'harga_jual')
                                     ->orderBy('nama_barang')
                                     ->paginate($request->input('per_page', 25))
                                     ->withQueryString();

        $queryTotalNilai = clone $baseFilterQuery;
        $total_nilai_stok_estimasi = $queryTotalNilai->sum(DB::raw('stok * harga_beli')); // Dihitung dari item yang terfilter

        // PASTIKAN KEY-NYA "unit_usahas" agar sesuai dengan Blade
        $filters = [
            'unit_usahas' => UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']),
        ];
        
        return view('pengurus.laporan.stok.daftar_terkini', compact(
            'daftar_stok', 
            'total_nilai_stok_estimasi',
            'filters' // Variabel filters sekarang berisi 'unit_usahas'
        ));
    }

    public function kartuStokBarang(Request $request, Barang $barang)
    {
        $barang->load('unitUsaha:id,nama_unit_usaha');
        
        $kartu_stok = HistoriStok::where('barang_id', $barang->id)
                                ->with('user:id,name') 
                                ->orderBy('created_at', 'desc')
                                ->paginate($request->input('per_page', 15), ['*'], 'page_kartu_stok')
                                ->withQueryString();
        
        if($request->ajax() && $request->has('page_kartu_stok')){
            return response()->json([
                'html_histori' => view('pengurus.barang.partials._histori_stok_rows', ['historiStoks' => $kartu_stok])->render(),
                'pagination_histori' => (string) $kartu_stok->links('vendor.pagination.tailwind-ajax')
            ]);
        }
        
        return view('pengurus.laporan.stok.kartu_stok', compact('barang', 'kartu_stok'));
    }
}