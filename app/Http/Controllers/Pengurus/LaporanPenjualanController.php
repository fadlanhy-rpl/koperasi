<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barang;
use App\Models\User;
use App\Models\UnitUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Exception;

class LaporanPenjualanController extends Controller
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
            'tanggal_mulai' => ['nullable', 'date_format:Y-m-d'],
            'tanggal_selesai' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:tanggal_mulai'],
            'unit_usaha_id' => ['nullable', 'exists:unit_usahas,id'],
            'barang_id' => ['nullable', 'exists:barangs,id'],
            'anggota_id' => ['nullable', Rule::exists('users', 'id')->where('role', 'anggota')],
            'status_pembayaran' => ['nullable', 'string', Rule::in(['lunas', 'belum_lunas', 'cicilan', 'all'])],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100']
        ];

        return $request->validate(array_merge($defaultRules, $rules));
    }

    /**
     * Enhanced data retrieval with better error handling
     */
    private function getPenjualanUmumData(Request $request)
    {
        try {
            $this->validateRequest($request);

            $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->toDateString());
            $tanggalSelesai = $request->input('tanggal_selesai', Carbon::now()->endOfMonth()->toDateString());
            
            // Build query with proper error handling
            $queryDetail = DetailPembelian::query()
                ->with([
                    'pembelian' => function ($pembelianQuery) use ($tanggalMulai, $tanggalSelesai, $request) {
                        $pembelianQuery->with(['user:id,name,nomor_anggota', 'kasir:id,name']);
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
                ->whereHas('pembelian', function ($pembelianQuery) use ($tanggalMulai, $tanggalSelesai, $request) {
                    $pembelianQuery->whereBetween(DB::raw('DATE(pembelians.tanggal_pembelian)'), [$tanggalMulai, $tanggalSelesai]);
                    
                    if ($request->filled('anggota_id')) {
                        $pembelianQuery->where('pembelians.user_id', $request->anggota_id);
                    }
                    
                    if ($request->filled('status_pembayaran') && $request->status_pembayaran !== 'all') {
                        $pembelianQuery->where('pembelians.status_pembayaran', $request->status_pembayaran);
                    }
                });

            // Apply additional filters
            if ($request->filled('barang_id')) {
                $queryDetail->where('barang_id', $request->barang_id);
            } elseif ($request->filled('unit_usaha_id')) {
                $queryDetail->whereHas('barang', function ($barangQuery) use ($request) {
                    $barangQuery->where('unit_usaha_id', $request->unit_usaha_id);
                });
            }

            // Apply search filter
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $queryDetail->where(function ($query) use ($searchTerm) {
                    $query->whereHas('barang', function ($barangQuery) use ($searchTerm) {
                        $barangQuery->where('nama_barang', 'like', "%{$searchTerm}%")
                                   ->orWhere('kode_barang', 'like', "%{$searchTerm}%");
                    })
                    ->orWhereHas('pembelian', function ($pembelianQuery) use ($searchTerm) {
                        $pembelianQuery->where('kode_pembelian', 'like', "%{$searchTerm}%")
                                      ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                                          $userQuery->where('name', 'like', "%{$searchTerm}%");
                                      });
                    });
                });
            }
            
            $queryDetail->join('pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id')
                        ->select('detail_pembelians.*')
                        ->orderBy('pembelians.tanggal_pembelian', 'desc')
                        ->orderBy('detail_pembelians.id', 'desc');

            return ['queryDetail' => $queryDetail, 'tanggalMulai' => $tanggalMulai, 'tanggalSelesai' => $tanggalSelesai];

        } catch (Exception $e) {
            Log::error('Error in getPenjualanUmumData: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Enhanced main method with better error handling
     */
    public function penjualanUmum(Request $request)
    {
        try {
            $data = $this->getPenjualanUmumData($request);
            $queryDetail = $data['queryDetail'];
            $tanggalMulai = $data['tanggalMulai'];
            $tanggalSelesai = $data['tanggalSelesai'];

            $perPage = $request->input('per_page', 25);
            $detailPembelians = $queryDetail->paginate($perPage)->withQueryString();

            // Enhanced summary calculation with error handling
            $ringkasan = $this->calculateSummary($queryDetail, $request, $tanggalMulai, $tanggalSelesai);

            $filters = $this->getFilters();
            
            // Handle export requests
            if ($request->has('export')) {
                return $this->handleExport($request, $queryDetail, $tanggalMulai, $tanggalSelesai, $ringkasan);
            }

            // Handle AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('pengurus.laporan.penjualan.partials._penjualan_umum_rows', compact('detailPembelians'))->render(),
                    'pagination' => (string) $detailPembelians->links('vendor.pagination.tailwind'),
                    'ringkasan' => [
                        'total_omset_formatted' => 'Rp ' . number_format($ringkasan['totalOmset'], 0, ',', '.'),
                        'total_item_terjual' => number_format($ringkasan['totalItemTerjual'], 0, ',', '.'),
                        'jumlah_transaksi' => number_format($ringkasan['jumlahTransaksi'], 0, ',', '.'),
                    ]
                ]);
            }
            
            return view('pengurus.laporan.penjualan.umum', array_merge($ringkasan, compact(
                'detailPembelians', 
                'tanggalMulai', 
                'tanggalSelesai', 
                'filters'
            )));

        } catch (Exception $e) {
            Log::error('Error in penjualanUmum: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat memuat data.',
                    'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan penjualan.');
        }
    }

    /**
     * Enhanced summary calculation
     */
    private function calculateSummary($queryDetail, $request, $tanggalMulai, $tanggalSelesai)
    {
        try {
            // Clone query for summary calculation
            $summaryQuery = clone $queryDetail;
            
            // Get all matching detail pembelian IDs
            $detailIds = $summaryQuery->pluck('detail_pembelians.id');
            
            // Calculate totals more efficiently
            $summaryData = DetailPembelian::whereIn('id', $detailIds)
                ->selectRaw('
                    SUM(subtotal) as total_omset,
                    SUM(jumlah) as total_item_terjual,
                    COUNT(DISTINCT pembelian_id) as jumlah_transaksi
                ')
                ->first();

            return [
                'totalOmset' => $summaryData->total_omset ?? 0,
                'totalItemTerjual' => $summaryData->total_item_terjual ?? 0,
                'jumlahTransaksi' => $summaryData->jumlah_transaksi ?? 0,
            ];

        } catch (Exception $e) {
            Log::error('Error calculating summary: ' . $e->getMessage());
            return [
                'totalOmset' => 0,
                'totalItemTerjual' => 0,
                'jumlahTransaksi' => 0,
            ];
        }
    }

    /**
     * Get filter options with error handling
     */
    private function getFilters()
    {
        try {
            return [
                'unit_usahas' => UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']),
                'barangs' => Barang::orderBy('nama_barang')->get(['id', 'nama_barang', 'kode_barang']),
                'anggotas' => User::where('role', 'anggota')->orderBy('name')->get(['id', 'name', 'nomor_anggota']),
                'status_pembayaran_options' => [
                    'all' => 'Semua Status', 
                    'lunas' => 'Lunas', 
                    'belum_lunas' => 'Belum Lunas', 
                    'cicilan' => 'Cicilan'
                ],
            ];
        } catch (Exception $e) {
            Log::error('Error getting filters: ' . $e->getMessage());
            return [
                'unit_usahas' => collect(),
                'barangs' => collect(),
                'anggotas' => collect(),
                'status_pembayaran_options' => ['all' => 'Semua Status'],
            ];
        }
    }

    /**
     * Enhanced export handling
     */
    private function handleExport($request, $queryDetail, $tanggalMulai, $tanggalSelesai, $ringkasan)
    {
        try {
            $allDetailPembelians = $queryDetail->get();
            $exportData = [
                'detailPembelians' => $allDetailPembelians,
                'tanggalMulai' => $tanggalMulai,
                'tanggalSelesai' => $tanggalSelesai,
                'totalOmset' => $ringkasan['totalOmset'],
                'totalItemTerjual' => $ringkasan['totalItemTerjual'],
                'jumlahTransaksi' => $ringkasan['jumlahTransaksi'],
                'filtersApplied' => $request->except(['page', 'export', '_token']),
                'generated_at' => now()
            ];

            if ($request->export == 'pdf') {
                $pdf = Pdf::loadView('pengurus.laporan.penjualan.exports.pdf_penjualan_umum', $exportData)
                          ->setPaper('a4', 'landscape');
                return $pdf->download('laporan-penjualan-umum-'.date('YmdHis').'.pdf');
            } 
            
            if ($request->export == 'excel') {
                return $this->exportToCSV($allDetailPembelians, 'laporan-penjualan-umum');
            }

        } catch (Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data. Silakan coba lagi.');
        }
    }

    /**
     * Enhanced CSV export
     */
    private function exportToCSV($data, $filename)
    {
        try {
            $fileName = $filename . '-' . date('YmdHis') . '.csv';
            $headers = [
                "Content-type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $columns = [
                'Kode Transaksi', 'Tanggal', 'Anggota', 'No. Anggota', 
                'Barang', 'Kode Barang', 'Unit Usaha', 'Jumlah', 
                'Harga Satuan', 'Subtotal', 'Status Bayar', 'Metode Bayar'
            ];

            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, $columns);

                foreach ($data as $detail) {
                    $row = [
                        $detail->pembelian->kode_pembelian ?? 'N/A',
                        $detail->pembelian ? Carbon::parse($detail->pembelian->tanggal_pembelian)->isoFormat('DD MMM YYYY, HH:mm') : 'N/A',
                        $detail->pembelian->user->name ?? 'N/A',
                        $detail->pembelian->user->nomor_anggota ?? '-',
                        $detail->barang->nama_barang ?? 'Barang Dihapus',
                        $detail->barang->kode_barang ?? '-',
                        $detail->barang->unitUsaha->nama_unit_usaha ?? 'N/A',
                        $detail->jumlah,
                        $detail->harga_satuan,
                        $detail->subtotal,
                        $detail->pembelian ? ucfirst($detail->pembelian->status_pembayaran) : 'N/A',
                        $detail->pembelian ? ucfirst(str_replace('_', ' ', $detail->pembelian->metode_pembayaran)) : 'N/A'
                    ];
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);

        } catch (Exception $e) {
            Log::error('CSV export error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Enhanced per barang report
     */
    public function penjualanPerBarang(Request $request)
    {
        try {
            $this->validateRequest($request, [
                'limit' => ['nullable', 'integer', 'min:1', 'max:100']
            ]);

            $data = $this->getPenjualanPerBarangData($request);
            $filters = ['unit_usahas' => UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha'])];

            if ($request->has('export')) {
                return $this->handlePerBarangExport($request, $data);
            }
            
            return view('pengurus.laporan.penjualan.per_barang', array_merge($data, compact('filters')));

        } catch (Exception $e) {
            Log::error('Error in penjualanPerBarang: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan per barang.');
        }
    }

    /**
     * Get per barang data with enhanced error handling
     */
    private function getPenjualanPerBarangData(Request $request)
    {
        try {
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
                    'barangs.satuan',
                    'barangs.unit_usaha_id',
                    DB::raw('SUM(detail_pembelians.jumlah) as total_terjual'),
                    DB::raw('SUM(detail_pembelians.subtotal) as total_omset_barang')
                )
                ->groupBy('barangs.id', 'barangs.nama_barang', 'barangs.kode_barang', 'barangs.satuan', 'barangs.unit_usaha_id')
                ->orderBy('total_omset_barang', 'desc');

            if ($request->filled('unit_usaha_id')) {
                $query->where('barangs.unit_usaha_id', $request->unit_usaha_id);
            }
            
            $laporanData = $query->take($limit)->get();

            return [
                'laporanPerBarang' => $laporanData,
                'tanggalMulai' => $tanggalMulai,
                'tanggalSelesai' => $tanggalSelesai,
                'limit' => $limit,
            ];

        } catch (Exception $e) {
            Log::error('Error in getPenjualanPerBarangData: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle per barang export
     */
    private function handlePerBarangExport($request, $data)
    {
        try {
            $exportData = array_merge($data, [
                'filtersApplied' => $request->except(['export', '_token']),
                'generated_at' => now()
            ]);
            
            if ($request->export == 'pdf') {
                $pdf = PDF::loadView('pengurus.laporan.penjualan.exports.pdf_penjualan_per_barang', $exportData)
                          ->setPaper('a4', 'portrait');
                return $pdf->download('laporan-penjualan-per-barang-'.date('YmdHis').'.pdf');
            }
            
            if ($request->export == 'excel') {
                return $this->exportPerBarangToCSV($data['laporanPerBarang']);
            }

        } catch (Exception $e) {
            Log::error('Per barang export error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data per barang.');
        }
    }

    /**
     * Export per barang to CSV
     */
    private function exportPerBarangToCSV($data)
    {
        $fileName = 'laporan-penjualan-per-barang-'.date('YmdHis').'.csv';
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No.', 'Kode Barang', 'Nama Barang', 'Satuan', 'Total Terjual', 'Total Omset'];
        
        $callback = function() use($data, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);
            
            foreach ($data as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item->kode_barang ?? '-',
                    $item->nama_barang,
                    $item->satuan,
                    $item->total_terjual,
                    $item->total_omset_barang
                ]);
            }
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }

    /**
     * Enhanced laba rugi report
     */
    public function labaRugiPenjualan(Request $request)
    {
        try {
            $data = $this->getLabaRugiPenjualanData($request);
            $filters = [
                'unit_usahas' => UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']),
                'barangs' => Barang::orderBy('nama_barang')->get(['id', 'nama_barang', 'kode_barang']),
            ];

            if ($request->has('export')) {
                return $this->handleLabaRugiExport($request, $data);
            }

            return view('pengurus.laporan.penjualan.laba_rugi', array_merge($data, compact('filters')));

        } catch (Exception $e) {
            Log::error('Error in labaRugiPenjualan: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan laba rugi.');
        }
    }

    /**
     * Get laba rugi data with enhanced error handling
     */
    private function getLabaRugiPenjualanData(Request $request)
    {
        try {
            $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->toDateString());
            $tanggalSelesai = $request->input('tanggal_selesai', Carbon::now()->endOfMonth()->toDateString());
            
            $query = DetailPembelian::query()
                ->join('barangs', 'detail_pembelians.barang_id', '=', 'barangs.id')
                ->join('pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id')
                ->whereBetween(DB::raw('DATE(pembelians.tanggal_pembelian)'), [$tanggalMulai, $tanggalSelesai])
                ->select(
                    'barangs.id as barang_id',
                    'barangs.nama_barang',
                    'barangs.kode_barang',
                    'barangs.satuan',
                    'barangs.unit_usaha_id',
                    DB::raw('SUM(detail_pembelians.jumlah) as total_terjual'),
                    DB::raw('SUM(detail_pembelians.subtotal) as total_pendapatan'),
                    DB::raw('SUM(detail_pembelians.jumlah * COALESCE(barangs.harga_beli, 0)) as total_hpp_estimasi'),
                    DB::raw('SUM(detail_pembelians.subtotal) - SUM(detail_pembelians.jumlah * COALESCE(barangs.harga_beli, 0)) as estimasi_laba_kotor')
                )
                ->groupBy('barangs.id', 'barangs.nama_barang', 'barangs.kode_barang', 'barangs.satuan', 'barangs.unit_usaha_id')
                ->orderBy('estimasi_laba_kotor', 'desc');

            if ($request->filled('barang_id')) {
                $query->where('detail_pembelians.barang_id', $request->barang_id);
            } elseif ($request->filled('unit_usaha_id')) {
                $query->where('barangs.unit_usaha_id', $request->unit_usaha_id);
            }
            
            $perPage = $request->input('per_page', 25);
            $laporanData = $query->paginate($perPage)->withQueryString();
            
            // Calculate totals
            $allFilteredItems = clone $query;
            $totals = $allFilteredItems->selectRaw('
                SUM(SUM(detail_pembelians.subtotal)) as total_pendapatan,
                SUM(SUM(detail_pembelians.jumlah * COALESCE(barangs.harga_beli, 0))) as total_hpp,
                SUM(SUM(detail_pembelians.subtotal) - SUM(detail_pembelians.jumlah * COALESCE(barangs.harga_beli, 0))) as total_laba
            ')->first();

            return [
                'laporanLabaRugiItems' => $laporanData,
                'tanggalMulai' => $tanggalMulai,
                'tanggalSelesai' => $tanggalSelesai,
                'totalPendapatanKeseluruhan' => $totals->total_pendapatan ?? 0,
                'totalHppEstimasiKeseluruhan' => $totals->total_hpp ?? 0,
                'totalEstimasiLabaKotorKeseluruhan' => $totals->total_laba ?? 0,
            ];

        } catch (Exception $e) {
            Log::error('Error in getLabaRugiPenjualanData: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle laba rugi export
     */
    private function handleLabaRugiExport($request, $data)
    {
        try {
            $exportData = array_merge($data, [
                'filtersApplied' => $request->except(['page', 'export', '_token', 'per_page']),
                'generated_at' => now()
            ]);
            
            if ($request->export == 'pdf') {
                $pdf = PDF::loadView('pengurus.laporan.penjualan.exports.pdf_laba_rugi', $exportData)
                          ->setPaper('a4', 'landscape');
                return $pdf->download('laporan-laba-rugi-'.date('YmdHis').'.pdf');
            }
            
            if ($request->export == 'excel') {
                return $this->exportLabaRugiToCSV($data['laporanLabaRugiItems'], $exportData);
            }

        } catch (Exception $e) {
            Log::error('Laba rugi export error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data laba rugi.');
        }
    }

    /**
     * Export laba rugi to CSV
     */
    private function exportLabaRugiToCSV($data, $exportData)
    {
        $fileName = 'laporan-laba-rugi-'.date('YmdHis').'.csv';
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['No.', 'Kode Barang', 'Nama Barang', 'Satuan', 'Total Terjual', 'Total Pendapatan', 'Total Estimasi HPP', 'Estimasi Laba Kotor'];
        
        $callback = function() use($data, $columns, $exportData) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);
            
            foreach ($data as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item->kode_barang ?? '-',
                    $item->nama_barang,
                    $item->satuan,
                    $item->total_terjual,
                    $item->total_pendapatan,
                    $item->total_hpp_estimasi,
                    $item->estimasi_laba_kotor
                ]);
            }
            
            // Add totals row
            fputcsv($file, []);
            fputcsv($file, [
                '', '', '', '', 'TOTAL:', 
                $exportData['totalPendapatanKeseluruhan'], 
                $exportData['totalHppEstimasiKeseluruhan'], 
                $exportData['totalEstimasiLabaKotorKeseluruhan']
            ]);
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}
