<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barang;
use App\Models\User;
use App\Models\UnitUsaha; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule; // Pastikan ini di-import
use Illuminate\Support\Facades\Log;

class LaporanPenjualanController extends Controller
{
    public function __construct()
    {
        // Middleware sudah diterapkan pada level route group
        // $this->middleware(['auth', 'role:admin,pengurus']);
    }

    /**
     * Menampilkan form filter dan hasil laporan penjualan umum (berdasarkan detail item).
     */
    public function penjualanUmum(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => ['nullable', 'date_format:Y-m-d'],
            'tanggal_selesai' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:tanggal_mulai'],
            'unit_usaha_id' => ['nullable', 'exists:unit_usahas,id'],
            'barang_id' => ['nullable', 'exists:barangs,id'],
            'anggota_id' => ['nullable', Rule::exists('users', 'id')->where('role', 'anggota')],
            'status_pembayaran' => ['nullable', 'string', Rule::in(['lunas', 'belum_lunas', 'cicilan', 'all'])],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100']
        ]);

        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::now()->endOfMonth()->toDateString());
        $perPage = $request->input('per_page', 25);

        // Query utama untuk mengambil detail pembelian (item per item)
        $queryDetail = DetailPembelian::query()
            ->with([
                'pembelian' => function ($pembelianQuery) use ($tanggalMulai, $tanggalSelesai, $request) {
                    $pembelianQuery->with(['user:id,name,nomor_anggota', 'kasir:id,name']);
                    // Filter tanggal pada relasi pembelian (akan digunakan oleh whereHas)
                    $pembelianQuery->whereBetween(DB::raw('DATE(pembelians.tanggal_pembelian)'), [$tanggalMulai, $tanggalSelesai]);
                    if ($request->filled('anggota_id')) {
                        $pembelianQuery->where('pembelians.user_id', $request->anggota_id);
                    }
                    if ($request->filled('status_pembayaran') && $request->status_pembayaran !== 'all') {
                        $pembelianQuery->where('pembelians.status_pembayaran', $request->status_pembayaran);
                    }
                }, 
                'barang.unitUsaha:id,nama_unit_usaha'
            ])
            // Filter utama untuk memastikan hanya detail dari pembelian yang sesuai yang diambil
            ->whereHas('pembelian', function ($pembelianQuery) use ($tanggalMulai, $tanggalSelesai, $request) {
                $pembelianQuery->whereBetween(DB::raw('DATE(pembelians.tanggal_pembelian)'), [$tanggalMulai, $tanggalSelesai]);
                if ($request->filled('anggota_id')) {
                    $pembelianQuery->where('pembelians.user_id', $request->anggota_id);
                }
                if ($request->filled('status_pembayaran') && $request->status_pembayaran !== 'all') {
                    $pembelianQuery->where('pembelians.status_pembayaran', $request->status_pembayaran);
                }
            });

        if ($request->filled('barang_id')) {
            $queryDetail->where('barang_id', $request->barang_id);
        } elseif ($request->filled('unit_usaha_id')) {
            $unitUsahaIdFilter = $request->unit_usaha_id; // Gunakan nama variabel berbeda
            $queryDetail->whereHas('barang', function ($barangQuery) use ($unitUsahaIdFilter) {
                $barangQuery->where('unit_usaha_id', $unitUsahaIdFilter);
            });
        }
        
        // Urutkan berdasarkan tanggal transaksi pembelian, lalu ID detail
        $queryDetail->join('pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id')
                    ->select('detail_pembelians.*') // Hindari ambiguitas kolom ID
                    ->orderBy('pembelians.tanggal_pembelian', 'desc')
                    ->orderBy('detail_pembelians.id', 'desc');

        $detailPembelians = $queryDetail->paginate($perPage)->withQueryString();

        // Kalkulasi ringkasan berdasarkan filter yang sama (diterapkan pada tabel Pembelian)
        $queryRingkasanPembelian = Pembelian::query()
            ->whereBetween(DB::raw('DATE(tanggal_pembelian)'), [$tanggalMulai, $tanggalSelesai]);

        if ($request->filled('anggota_id')) {
            $queryRingkasanPembelian->where('user_id', $request->anggota_id);
        }
        if ($request->filled('status_pembayaran') && $request->status_pembayaran !== 'all') {
            $queryRingkasanPembelian->where('status_pembayaran', $request->status_pembayaran);
        }
        
        // Untuk total omset dan item, kita perlu join atau subquery jika ada filter barang/unit usaha
        $subQueryPembelianIds = clone $queryRingkasanPembelian; // Clone untuk mendapatkan ID pembelian yang terfilter
        if ($request->filled('barang_id') || $request->filled('unit_usaha_id')) {
            $subQueryPembelianIds->whereHas('detailPembelians', function($qDetail) use ($request){
                if ($request->filled('barang_id')) {
                    $qDetail->where('barang_id', $request->barang_id);
                } elseif ($request->filled('unit_usaha_id')) {
                    $unitUsahaIdFilterRingkasan = $request->unit_usaha_id;
                    $qDetail->whereHas('barang', function ($barangQuery) use ($unitUsahaIdFilterRingkasan) {
                        $barangQuery->where('unit_usaha_id', $unitUsahaIdFilterRingkasan);
                    });
                }
            });
        }
        $pembelianIdsTerfilter = $subQueryPembelianIds->pluck('id');

        $totalOmset = Pembelian::whereIn('id', $pembelianIdsTerfilter)->sum('total_harga');
        $totalItemTerjual = DetailPembelian::whereIn('pembelian_id', $pembelianIdsTerfilter)->sum('jumlah');
        $jumlahTransaksi = $pembelianIdsTerfilter->count();

        // Data untuk filter di frontend
        $filters = [
            'unit_usahas' => UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']),
            'barangs' => Barang::orderBy('nama_barang')->get(['id', 'nama_barang', 'kode_barang']),
            'anggotas' => User::where('role', 'anggota')->orderBy('name')->get(['id', 'name', 'nomor_anggota']),
            'status_pembayaran_options' => ['all' => 'Semua Status', 'lunas' => 'Lunas', 'belum_lunas' => 'Belum Lunas', 'cicilan' => 'Cicilan'],
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('pengurus.laporan.penjualan.partials._penjualan_umum_rows', compact('detailPembelians'))->render(),
                'pagination' => (string) $detailPembelians->links('vendor.pagination.tailwind-ajax'),
                'ringkasan' => [
                    'total_omset_formatted' => 'Rp ' . number_format($totalOmset, 0, ',', '.'),
                    'total_item_terjual' => number_format($totalItemTerjual, 0, ',', '.'),
                    'jumlah_transaksi' => number_format($jumlahTransaksi, 0, ',', '.'),
                ]
            ]);
        }
        
        return view('pengurus.laporan.penjualan.umum', compact(
            'detailPembelians', 
            'tanggalMulai', 
            'tanggalSelesai', 
            'totalOmset', 
            'totalItemTerjual', 
            'jumlahTransaksi',
            'filters'
        ));
    }

    /**
     * Laporan penjualan per barang.
     */
    public function penjualanPerBarang(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => ['nullable', 'date_format:Y-m-d'],
            'tanggal_selesai' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:tanggal_mulai'],
            'unit_usaha_id' => ['nullable', 'exists:unit_usahas,id'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100']
        ]);

        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::now()->endOfMonth()->toDateString());
        $limit = $request->input('limit', 10);

        $query = DetailPembelian::query()
            ->join('barangs', 'detail_pembelians.barang_id', '=', 'barangs.id')
            ->join('pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id')
            ->whereBetween(DB::raw('DATE(pembelians.tanggal_pembelian)'), [$tanggalMulai, $tanggalSelesai])
            ->select(
                'barangs.id as barang_id',
                'barangs.nama_barang',
                'barangs.kode_barang',
                'barangs.satuan', // Tambahkan satuan
                DB::raw('SUM(detail_pembelians.jumlah) as total_terjual'),
                DB::raw('SUM(detail_pembelians.subtotal) as total_omset_barang')
            )
            ->groupBy('barangs.id', 'barangs.nama_barang', 'barangs.kode_barang', 'barangs.satuan')
            ->orderBy('total_omset_barang', 'desc');

        if ($request->filled('unit_usaha_id')) {
            $query->where('barangs.unit_usaha_id', $request->unit_usaha_id);
        }

        $laporanPerBarang = $query->take($limit)->get();

        $filters = [
            'unit_usahas' => UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']),
        ];

        return view('pengurus.laporan.penjualan.per_barang', compact(
            'laporanPerBarang', 
            'tanggalMulai', 
            'tanggalSelesai', 
            'limit',
            'filters'
        ));
    }

    /**
     * Laporan Laba Rugi Sederhana dari Penjualan Barang
     */
    public function labaRugiPenjualan(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => ['nullable', 'date_format:Y-m-d'],
            'tanggal_selesai' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:tanggal_mulai'],
            'unit_usaha_id' => ['nullable', 'exists:unit_usahas,id'],
            'barang_id' => ['nullable', 'exists:barangs,id'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100']
        ]);

        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::now()->endOfMonth()->toDateString());
        $perPage = $request->input('per_page', 25);

        $query = DetailPembelian::query()
            ->join('barangs', 'detail_pembelians.barang_id', '=', 'barangs.id')
            ->join('pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id')
            ->whereBetween(DB::raw('DATE(pembelians.tanggal_pembelian)'), [$tanggalMulai, $tanggalSelesai])
            ->select(
                'barangs.id as barang_id',
                'barangs.nama_barang',
                'barangs.kode_barang',
                'barangs.satuan',
                DB::raw('SUM(detail_pembelians.jumlah) as total_terjual'),
                DB::raw('SUM(detail_pembelians.subtotal) as total_pendapatan'),
                DB::raw('SUM(detail_pembelians.jumlah * barangs.harga_beli) as total_hpp_estimasi'),
                DB::raw('SUM(detail_pembelians.subtotal) - SUM(detail_pembelians.jumlah * barangs.harga_beli) as estimasi_laba_kotor')
            )
            ->groupBy('barangs.id', 'barangs.nama_barang', 'barangs.kode_barang', 'barangs.satuan')
            ->orderBy('estimasi_laba_kotor', 'desc');

        if ($request->filled('barang_id')) {
            $query->where('detail_pembelians.barang_id', $request->barang_id);
        } elseif ($request->filled('unit_usaha_id')) {
            $query->where('barangs.unit_usaha_id', $request->unit_usaha_id);
        }

        $laporanLabaRugiItems = $query->paginate($perPage)->withQueryString();

        // Untuk total keseluruhan, kita perlu menjalankan query tanpa paginasi
        $allFilteredItems = $query->get(); // Ini akan mengambil semua hasil dari query yang sudah difilter
        $totalPendapatanKeseluruhan = $allFilteredItems->sum('total_pendapatan');
        $totalHppEstimasiKeseluruhan = $allFilteredItems->sum('total_hpp_estimasi');
        $totalEstimasiLabaKotorKeseluruhan = $allFilteredItems->sum('estimasi_laba_kotor');

        $filters = [
            'unit_usahas' => UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']),
            'barangs' => Barang::orderBy('nama_barang')->get(['id', 'nama_barang', 'kode_barang']),
        ];

        return view('pengurus.laporan.penjualan.laba_rugi', compact(
            'laporanLabaRugiItems',
            'tanggalMulai',
            'tanggalSelesai',
            'totalPendapatanKeseluruhan',
            'totalHppEstimasiKeseluruhan',
            'totalEstimasiLabaKotorKeseluruhan',
            'filters'
        ));
    }
}