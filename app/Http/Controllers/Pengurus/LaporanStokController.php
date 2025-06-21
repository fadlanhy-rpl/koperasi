<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\UnitUsaha;
use App\Models\HistoriStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;

class LaporanStokController extends Controller
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
            'search_barang' => ['nullable', 'string', 'max:255'],
            'unit_usaha_id' => ['nullable', 'exists:unit_usahas,id'],
            'stok_kurang_dari' => ['nullable', 'integer', 'min:0'],
            'stok_lebih_dari' => ['nullable', 'integer', 'min:0'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
            'sort_by' => ['nullable', 'string', 'in:nama_barang,stok,nilai_stok,unit_usaha'],
            'sort_direction' => ['nullable', 'string', 'in:asc,desc']
        ];

        return $request->validate(array_merge($defaultRules, $rules));
    }

    /**
     * Enhanced daftar stok terkini with export functionality
     */
    public function daftarStokTerkini(Request $request)
    {
        try {
            $this->validateRequest($request);

            $data = $this->getStokTerkiniData($request);
            $filters = $this->getFilters();

            // Handle export requests
            if ($request->has('export')) {
                return $this->handleStokExport($request, $data);
            }

            // Handle AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('pengurus.laporan.stok.partials._stok_terkini_rows', [
                        'daftar_stok' => $data['daftar_stok']
                    ])->render(),
                    'pagination' => (string) $data['daftar_stok']->links('vendor.pagination.tailwind'),
                    'summary' => [
                        'total_nilai_stok_formatted' => 'Rp ' . number_format($data['total_nilai_stok_estimasi'], 0, ',', '.'),
                        'total_items' => $data['total_items'],
                        'stok_rendah_count' => $data['stok_rendah_count'],
                        'stok_habis_count' => $data['stok_habis_count']
                    ]
                ]);
            }

            return view('pengurus.laporan.stok.daftar_terkini', array_merge($data, compact('filters')));

        } catch (Exception $e) {
            Log::error('Error in daftarStokTerkini: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat memuat data.',
                    'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat memuat laporan stok.');
        }
    }

    /**
     * Get stok terkini data with enhanced filtering
     */
    private function getStokTerkiniData(Request $request)
    {
        try {
            $baseFilterQuery = Barang::query();

            // Apply filters
            if ($request->filled('search_barang')) {
                $searchTerm = $request->search_barang;
                $baseFilterQuery->where(function($q) use ($searchTerm) {
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

            if ($request->filled('stok_lebih_dari') && is_numeric($request->stok_lebih_dari)) {
                $baseFilterQuery->where('stok', '>=', (int)$request->stok_lebih_dari);
            }

            // Apply sorting
            $sortBy = $request->input('sort_by', 'nama_barang');
            $sortDirection = $request->input('sort_direction', 'asc');

            switch ($sortBy) {
                case 'nilai_stok':
                    $baseFilterQuery->orderByRaw('(stok * harga_beli) ' . $sortDirection);
                    break;
                case 'unit_usaha':
                    $baseFilterQuery->join('unit_usahas', 'barangs.unit_usaha_id', '=', 'unit_usahas.id')
                                   ->orderBy('unit_usahas.nama_unit_usaha', $sortDirection)
                                   ->select('barangs.*');
                    break;
                default:
                    $baseFilterQuery->orderBy($sortBy, $sortDirection);
                    break;
            }

            // Get paginated data
            $queryDaftarBarang = clone $baseFilterQuery;
            $perPage = $request->input('per_page', 25);
            $daftar_stok = $queryDaftarBarang->with('unitUsaha:id,nama_unit_usaha')
                                           ->select('id', 'unit_usaha_id', 'nama_barang', 'kode_barang', 'stok', 'satuan', 'harga_beli', 'harga_jual')
                                           ->paginate($perPage)
                                           ->withQueryString();

            // Calculate summary statistics
            $queryTotalNilai = clone $baseFilterQuery;
            $summaryData = $queryTotalNilai->selectRaw('
                SUM(stok * harga_beli) as total_nilai_stok,
                COUNT(*) as total_items,
                SUM(CASE WHEN stok <= 10 AND stok > 0 THEN 1 ELSE 0 END) as stok_rendah_count,
                SUM(CASE WHEN stok = 0 THEN 1 ELSE 0 END) as stok_habis_count
            ')->first();

            return [
                'daftar_stok' => $daftar_stok,
                'total_nilai_stok_estimasi' => $summaryData->total_nilai_stok ?? 0,
                'total_items' => $summaryData->total_items ?? 0,
                'stok_rendah_count' => $summaryData->stok_rendah_count ?? 0,
                'stok_habis_count' => $summaryData->stok_habis_count ?? 0,
            ];

        } catch (Exception $e) {
            Log::error('Error in getStokTerkiniData: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Enhanced kartu stok with export functionality
     */
    public function kartuStokBarang(Request $request, Barang $barang)
    {
        try {
            $barang->load('unitUsaha:id,nama_unit_usaha');
            
            $perPage = $request->input('per_page', 15);
            $kartu_stok = HistoriStok::where('barang_id', $barang->id)
                                    ->with('user:id,name') 
                                    ->orderBy('created_at', 'desc')
                                    ->paginate($perPage, ['*'], 'page_kartu_stok')
                                    ->withQueryString();

            // Handle export requests
            if ($request->has('export')) {
                return $this->handleKartuStokExport($request, $barang, $kartu_stok);
            }
            
            // Handle AJAX requests for pagination
            if ($request->ajax() && $request->has('page_kartu_stok')) {
                return response()->json([
                    'html_histori' => view('pengurus.barang.partials._histori_stok_rows', [
                        'historiStoks' => $kartu_stok
                    ])->render(),
                    'pagination_histori' => (string) $kartu_stok->links('vendor.pagination.tailwind')
                ]);
            }
            
            return view('pengurus.laporan.stok.kartu_stok', compact('barang', 'kartu_stok'));

        } catch (Exception $e) {
            Log::error('Error in kartuStokBarang: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat kartu stok.');
        }
    }

    /**
     * Get filter options
     */
    private function getFilters()
    {
        try {
            return [
                'unit_usahas' => UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']),
            ];
        } catch (Exception $e) {
            Log::error('Error getting filters: ' . $e->getMessage());
            return [
                'unit_usahas' => collect(),
            ];
        }
    }

    /**
     * Handle stok export
     */
    private function handleStokExport($request, $data)
    {
        try {
            // Get all data for export (not paginated)
            $baseFilterQuery = Barang::query();
            
            // Apply same filters as main query
            if ($request->filled('search_barang')) {
                $searchTerm = $request->search_barang;
                $baseFilterQuery->where(function($q) use ($searchTerm) {
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

            if ($request->filled('stok_lebih_dari') && is_numeric($request->stok_lebih_dari)) {
                $baseFilterQuery->where('stok', '>=', (int)$request->stok_lebih_dari);
            }

            $allStokData = $baseFilterQuery->with('unitUsaha:id,nama_unit_usaha')
                                          ->orderBy('nama_barang')
                                          ->get();

            $exportData = [
                'daftar_stok' => $allStokData,
                'total_nilai_stok_estimasi' => $data['total_nilai_stok_estimasi'],
                'total_items' => $data['total_items'],
                'stok_rendah_count' => $data['stok_rendah_count'],
                'stok_habis_count' => $data['stok_habis_count'],
                'filtersApplied' => $request->except(['page', 'export', '_token']),
                'generated_at' => now()
            ];

            if ($request->export == 'pdf') {
                $pdf = Pdf::loadView('pengurus.laporan.stok.exports.pdf_laporan_stok', $exportData)
                          ->setPaper('a4', 'landscape');
                return $pdf->download('laporan-stok-terkini-'.date('YmdHis').'.pdf');
            } 
            
            if ($request->export == 'excel') {
                return $this->exportStokToCSV($allStokData, 'laporan-stok-terkini');
            }

        } catch (Exception $e) {
            Log::error('Stok export error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data stok. Silakan coba lagi.');
        }
    }

    /**
     * Handle kartu stok export
     */
    private function handleKartuStokExport($request, $barang, $kartu_stok)
    {
        try {
            // Get all histori stok for export
            $allHistoriStok = HistoriStok::where('barang_id', $barang->id)
                                        ->with('user:id,name')
                                        ->orderBy('created_at', 'desc')
                                        ->get();

            $exportData = [
                'barang' => $barang,
                'kartu_stok' => $allHistoriStok,
                'generated_at' => now()
            ];

            if ($request->export == 'pdf') {
                $pdf = Pdf::loadView('pengurus.laporan.stok.exports.pdf_kartu_stok', $exportData)
                          ->setPaper('a4', 'portrait');
                return $pdf->download('kartu-stok-'.$barang->nama_barang.'-'.date('YmdHis').'.pdf');
            }
            
            if ($request->export == 'excel') {
                return $this->exportKartuStokToCSV($barang, $allHistoriStok);
            }

        } catch (Exception $e) {
            Log::error('Kartu stok export error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor kartu stok. Silakan coba lagi.');
        }
    }

    /**
     * Export stok to CSV
     */
    private function exportStokToCSV($data, $filename)
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
                'No.', 'Kode Barang', 'Nama Barang', 'Unit Usaha', 'Stok Terkini', 
                'Satuan', 'Harga Beli', 'Harga Jual', 'Nilai Stok (H.Beli)'
            ];

            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, $columns);

                foreach ($data as $index => $barang) {
                    $row = [
                        $index + 1,
                        $barang->kode_barang ?? '-',
                        $barang->nama_barang,
                        $barang->unitUsaha->nama_unit_usaha ?? 'N/A',
                        $barang->stok,
                        $barang->satuan,
                        $barang->harga_beli,
                        $barang->harga_jual,
                        $barang->stok * $barang->harga_beli
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
     * Export kartu stok to CSV
     */
    private function exportKartuStokToCSV($barang, $historiStok)
    {
        try {
            $fileName = 'kartu-stok-' . str_replace(' ', '-', $barang->nama_barang) . '-' . date('YmdHis') . '.csv';
            $headers = [
                "Content-type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $columns = [
                'Tanggal & Waktu', 'Tipe Transaksi', 'Jumlah', 'Stok Sebelum', 
                'Stok Sesudah', 'Keterangan', 'Dicatat Oleh'
            ];

            $callback = function() use($barang, $historiStok, $columns) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Add header info
                fputcsv($file, ['Kartu Stok Barang: ' . $barang->nama_barang]);
                fputcsv($file, ['Kode Barang: ' . ($barang->kode_barang ?? '-')]);
                fputcsv($file, ['Unit Usaha: ' . ($barang->unitUsaha->nama_unit_usaha ?? 'N/A')]);
                fputcsv($file, ['Stok Terkini: ' . $barang->stok . ' ' . $barang->satuan]);
                fputcsv($file, []);
                
                fputcsv($file, $columns);

                foreach ($historiStok as $histori) {
                    $row = [
                        Carbon::parse($histori->created_at)->format('d/m/Y H:i:s'),
                        ucfirst(str_replace('_', ' ', $histori->tipe_transaksi)),
                        $histori->jumlah,
                        $histori->stok_sebelum,
                        $histori->stok_sesudah,
                        $histori->keterangan ?? '-',
                        $histori->user->name ?? 'System'
                    ];
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);

        } catch (Exception $e) {
            Log::error('Kartu stok CSV export error: ' . $e->getMessage());
            throw $e;
        }
    }
}
