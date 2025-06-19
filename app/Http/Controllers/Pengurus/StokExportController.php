<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\HistoriStok;
use App\Models\UnitUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class StokExportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,pengurus']);
    }

    public function exportStok(Request $request)
    {
        $format = $request->get('format', 'excel');
        $query = $this->buildStockQuery($request);
        
        $barangs = $query->get();
        $filters = $this->getAppliedFilters($request);
        
        switch ($format) {
            case 'pdf':
                return $this->exportToPdf($barangs, $filters);
            case 'csv':
                return $this->exportToCsv($barangs, $filters);
            case 'excel':
            default:
                return $this->exportToExcel($barangs, $filters);
        }
    }

    public function exportHistoriStok(Request $request)
    {
        $format = $request->get('format', 'excel');
        $barangId = $request->get('barang_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = HistoriStok::with(['barang', 'user'])
            ->orderBy('created_at', 'desc');

        if ($barangId) {
            $query->where('barang_id', $barangId);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $histori = $query->get();
        $filters = [
            'barang_id' => $barangId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        switch ($format) {
            case 'pdf':
                return $this->exportHistoriToPdf($histori, $filters);
            case 'csv':
                return $this->exportHistoriToCsv($histori, $filters);
            case 'excel':
            default:
                return $this->exportHistoriToExcel($histori, $filters);
        }
    }

    public function exportLaporanNilai(Request $request)
    {
        $format = $request->get('format', 'excel');
        $unitUsahaId = $request->get('unit_usaha_id');

        $query = Barang::with('unitUsaha');
        
        if ($unitUsahaId) {
            $query->where('unit_usaha_id', $unitUsahaId);
        }

        $barangs = $query->get();
        $totalNilai = $barangs->sum(function ($barang) {
            return $barang->stok * ($barang->harga_beli ?? 0);
        });

        $data = [
            'barangs' => $barangs,
            'total_nilai' => $totalNilai,
            'unit_usaha_id' => $unitUsahaId,
            'generated_at' => now()
        ];

        switch ($format) {
            case 'pdf':
                return $this->exportNilaiToPdf($data);
            case 'csv':
                return $this->exportNilaiToCsv($data);
            case 'excel':
            default:
                return $this->exportNilaiToExcel($data);
        }
    }

    private function buildStockQuery(Request $request)
    {
        $query = Barang::with('unitUsaha:id,nama_unit_usaha');

        if ($request->filled('search_stok')) {
            $searchTerm = $request->search_stok;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_barang', 'like', '%' . $searchTerm . '%')
                    ->orWhere('kode_barang', 'like', '%' . $searchTerm . '%')
                    ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('unit_usaha_stok') && $request->unit_usaha_stok != '') {
            $query->where('unit_usaha_id', $request->unit_usaha_stok);
        }

        if ($request->filled('stock_level')) {
            switch ($request->stock_level) {
                case 'low':
                    $query->where('stok', '<=', 10);
                    break;
                case 'out':
                    $query->where('stok', '=', 0);
                    break;
                case 'normal':
                    $query->where('stok', '>', 10);
                    break;
            }
        }

        $sortBy = $request->get('sort_by', 'nama_barang');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $validSorts = ['nama_barang', 'stok', 'created_at', 'updated_at'];
        if (in_array($sortBy, $validSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query;
    }

    private function getAppliedFilters(Request $request)
    {
        return [
            'search' => $request->get('search_stok'),
            'unit_usaha' => $request->get('unit_usaha_stok'),
            'stock_level' => $request->get('stock_level'),
            'sort_by' => $request->get('sort_by', 'nama_barang'),
            'sort_order' => $request->get('sort_order', 'asc')
        ];
    }

    private function exportToExcel($barangs, $filters)
    {
        $filename = 'laporan-stok-' . date('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($barangs, $filters) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, [
                'LAPORAN STOK BARANG',
                '',
                '',
                '',
                '',
                '',
                'Generated: ' . date('d/m/Y H:i:s')
            ]);
            
            fputcsv($file, []); // Empty row
            
            // Filters info
            if (!empty(array_filter($filters))) {
                fputcsv($file, ['FILTER YANG DITERAPKAN:']);
                if ($filters['search']) {
                    fputcsv($file, ['Pencarian:', $filters['search']]);
                }
                if ($filters['unit_usaha']) {
                    $unitUsaha = UnitUsaha::find($filters['unit_usaha']);
                    fputcsv($file, ['Unit Usaha:', $unitUsaha ? $unitUsaha->nama_unit_usaha : 'N/A']);
                }
                if ($filters['stock_level']) {
                    $levelText = [
                        'low' => 'Stok Rendah',
                        'out' => 'Stok Habis',
                        'normal' => 'Stok Normal'
                    ];
                    fputcsv($file, ['Level Stok:', $levelText[$filters['stock_level']] ?? $filters['stock_level']]);
                }
                fputcsv($file, []); // Empty row
            }
            
            // Table headers
            fputcsv($file, [
                'No',
                'Kode Barang',
                'Nama Barang',
                'Unit Usaha',
                'Stok',
                'Satuan',
                'Harga Beli',
                'Harga Jual',
                'Nilai Stok',
                'Status Stok',
                'Terakhir Update'
            ]);
            
            // Data rows
            foreach ($barangs as $index => $barang) {
                $nilaiStok = $barang->stok * ($barang->harga_beli ?? 0);
                $statusStok = $barang->stok == 0 ? 'Habis' : ($barang->stok <= 10 ? 'Rendah' : 'Normal');
                
                fputcsv($file, [
                    $index + 1,
                    $barang->kode_barang ?? '-',
                    $barang->nama_barang,
                    $barang->unitUsaha->nama_unit_usaha ?? 'N/A',
                    $barang->stok,
                    $barang->satuan,
                    number_format($barang->harga_beli ?? 0, 0, ',', '.'),
                    number_format($barang->harga_jual ?? 0, 0, ',', '.'),
                    number_format($nilaiStok, 0, ',', '.'),
                    $statusStok,
                    $barang->updated_at->format('d/m/Y H:i')
                ]);
            }
            
            // Summary
            fputcsv($file, []); // Empty row
            fputcsv($file, ['RINGKASAN:']);
            fputcsv($file, ['Total Item:', count($barangs)]);
            fputcsv($file, ['Total Nilai Inventori:', 'Rp ' . number_format($barangs->sum(function($b) { return $b->stok * ($b->harga_beli ?? 0); }), 0, ',', '.')]);
            fputcsv($file, ['Stok Rendah:', $barangs->where('stok', '<=', 10)->where('stok', '>', 0)->count()]);
            fputcsv($file, ['Stok Habis:', $barangs->where('stok', 0)->count()]);
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportToCsv($barangs, $filters)
    {
        return $this->exportToExcel($barangs, $filters); // Same implementation for CSV
    }

    private function exportToPdf($barangs, $filters)
    {
        $data = [
            'barangs' => $barangs,
            'filters' => $filters,
            'generated_at' => now(),
            'total_items' => count($barangs),
            'total_value' => $barangs->sum(function($b) { return $b->stok * ($b->harga_beli ?? 0); }),
            'low_stock' => $barangs->where('stok', '<=', 10)->where('stok', '>', 0)->count(),
            'out_of_stock' => $barangs->where('stok', 0)->count()
        ];

        $html = View::make('pengurus.stok.exports.pdf-stok', $data)->render();
        
        // Simple HTML to PDF conversion (you can use libraries like DomPDF or wkhtmltopdf)
        $filename = 'laporan-stok-' . date('Y-m-d-H-i-s') . '.html';
        
        return Response::make($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    private function exportHistoriToExcel($histori, $filters)
    {
        $filename = 'histori-stok-' . date('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($histori, $filters) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['HISTORI PERGERAKAN STOK']);
            fputcsv($file, []);
            
            fputcsv($file, [
                'No',
                'Tanggal',
                'Barang',
                'Tipe',
                'Jumlah',
                'Stok Sebelum',
                'Stok Sesudah',
                'Keterangan',
                'User'
            ]);
            
            foreach ($histori as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item->created_at->format('d/m/Y H:i'),
                    $item->barang->nama_barang ?? 'N/A',
                    ucfirst($item->tipe),
                    $item->jumlah,
                    $item->stok_sebelum,
                    $item->stok_sesudah,
                    $item->keterangan,
                    $item->user->name ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportHistoriToCsv($histori, $filters)
    {
        return $this->exportHistoriToExcel($histori, $filters);
    }

    private function exportHistoriToPdf($histori, $filters)
    {
        $data = [
            'histori' => $histori,
            'filters' => $filters,
            'generated_at' => now()
        ];

        $html = View::make('pengurus.stok.exports.pdf-histori', $data)->render();
        
        return Response::make($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="histori-stok-' . date('Y-m-d-H-i-s') . '.html"'
        ]);
    }

    private function exportNilaiToExcel($data)
    {
        $filename = 'laporan-nilai-inventori-' . date('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['LAPORAN NILAI INVENTORI']);
            fputcsv($file, ['Generated:', $data['generated_at']->format('d/m/Y H:i:s')]);
            fputcsv($file, []);
            
            fputcsv($file, [
                'No',
                'Kode Barang',
                'Nama Barang',
                'Unit Usaha',
                'Stok',
                'Harga Beli',
                'Nilai Total'
            ]);
            
            foreach ($data['barangs'] as $index => $barang) {
                $nilaiTotal = $barang->stok * ($barang->harga_beli ?? 0);
                
                fputcsv($file, [
                    $index + 1,
                    $barang->kode_barang ?? '-',
                    $barang->nama_barang,
                    $barang->unitUsaha->nama_unit_usaha ?? 'N/A',
                    $barang->stok,
                    number_format($barang->harga_beli ?? 0, 0, ',', '.'),
                    number_format($nilaiTotal, 0, ',', '.')
                ]);
            }
            
            fputcsv($file, []);
            fputcsv($file, ['TOTAL NILAI INVENTORI:', 'Rp ' . number_format($data['total_nilai'], 0, ',', '.')]);
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportNilaiToCsv($data)
    {
        return $this->exportNilaiToExcel($data);
    }

    private function exportNilaiToPdf($data)
    {
        $html = View::make('pengurus.stok.exports.pdf-nilai', $data)->render();
        
        return Response::make($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="laporan-nilai-inventori-' . date('Y-m-d-H-i-s') . '.html"'
        ]);
    }
}
