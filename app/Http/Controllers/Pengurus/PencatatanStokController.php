<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\HistoriStok;
use App\Models\UnitUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PencatatanStokController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,pengurus']);
    }

    public function index(Request $request)
    {
        $query = Barang::with('unitUsaha:id,nama_unit_usaha');

        // Enhanced search functionality
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

        // Stock level filter
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

        // Sort options
        $sortBy = $request->get('sort_by', 'nama_barang');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $validSorts = ['nama_barang', 'stok', 'created_at', 'updated_at'];
        if (in_array($sortBy, $validSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('nama_barang', 'asc');
        }

        $perPage = $request->get('per_page', 15);
        $barangs = $query->paginate($perPage)->withQueryString();
        
        $unitUsahas = UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']);

        // Statistics for dashboard
        $stats = [
            'total_items' => Barang::count(),
            'low_stock' => Barang::where('stok', '<=', 10)->where('stok', '>', 0)->count(),
            'out_of_stock' => Barang::where('stok', '=', 0)->count(),
            'total_value' => Barang::sum(DB::raw('stok * harga_beli')),
        ];

        // Handle AJAX requests for dynamic loading
        if ($request->ajax()) {
            return response()->json([
                'html' => view('pengurus.stok.partials._stock_table', compact('barangs'))->render(),
                'pagination' => (string) $barangs->links('vendor.pagination.tailwind-ajax'),
                'stats' => $stats
            ]);
        }

        return view('pengurus.stok.index', compact('barangs', 'unitUsahas', 'stats'));
    }

    public function showFormBarangMasuk(Barang $barang)
    {
        $barang->load('unitUsaha:id,nama_unit_usaha');
        return view('pengurus.stok.form_barang_masuk', compact('barang'));
    }

    public function storeBarangMasuk(Request $request, Barang $barang)
    {
        $validatedData = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'harga_beli_baru' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::beginTransaction();
        try {
            $stokSebelum = $barang->stok;
            $stokSesudah = $stokSebelum + $validatedData['jumlah'];

            // Update stock
            $barang->stok = $stokSesudah;
            
            // Update purchase price if provided
            if (isset($validatedData['harga_beli_baru']) && $validatedData['harga_beli_baru'] > 0) {
                $barang->harga_beli = $validatedData['harga_beli_baru'];
            }
            
            $barang->save();

            // Create stock history
            HistoriStok::create([
                'barang_id' => $barang->id,
                'user_id' => Auth::id(),
                'tipe' => 'masuk',
                'jumlah' => $validatedData['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $validatedData['keterangan'] ?? 'Pencatatan barang masuk',
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Stok barang {$barang->nama_barang} berhasil ditambahkan.",
                    'new_stock' => $stokSesudah,
                    'redirect' => route('pengurus.stok.index')
                ]);
            }

            return redirect()->route('pengurus.stok.index')->with('success', "Stok barang {$barang->nama_barang} berhasil ditambahkan.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mencatat barang masuk: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mencatat barang masuk: Terjadi kesalahan sistem.'
                ], 422);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mencatat barang masuk: Terjadi kesalahan sistem.');
        }
    }

    public function showFormBarangKeluar(Barang $barang)
    {
        $barang->load('unitUsaha:id,nama_unit_usaha');
        return view('pengurus.stok.form_barang_keluar', compact('barang'));
    }

    public function storeBarangKeluar(Request $request, Barang $barang)
    {
        $validatedData = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1', 'max:' . $barang->stok],
            'keterangan' => ['required', 'string', 'max:255'],
            'tipe_keluar' => ['required', 'in:rusak,hilang,digunakan,lainnya'],
        ]);

        DB::beginTransaction();
        try {
            $stokSebelum = $barang->stok;
            $stokSesudah = $stokSebelum - $validatedData['jumlah'];

            $barang->stok = $stokSesudah;
            $barang->save();

            HistoriStok::create([
                'barang_id' => $barang->id,
                'user_id' => Auth::id(),
                'tipe' => 'keluar',
                'jumlah' => $validatedData['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $validatedData['keterangan'] . ' (' . ucfirst($validatedData['tipe_keluar']) . ')',
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Stok barang {$barang->nama_barang} berhasil dikurangi.",
                    'new_stock' => $stokSesudah,
                    'redirect' => route('pengurus.stok.index')
                ]);
            }

            return redirect()->route('pengurus.stok.index')->with('success', "Stok barang {$barang->nama_barang} berhasil dikurangi.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mencatat barang keluar: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mencatat barang keluar: Terjadi kesalahan sistem.'
                ], 422);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal mencatat barang keluar: Terjadi kesalahan sistem.');
        }
    }

    public function showFormPenyesuaianStok(Barang $barang)
    {
        $barang->load('unitUsaha:id,nama_unit_usaha');
        return view('pengurus.stok.form_penyesuaian_stok', compact('barang'));
    }

    public function storePenyesuaianStok(Request $request, Barang $barang)
    {
        $validatedData = $request->validate([
            'stok_fisik' => ['required', 'integer', 'min:0'],
            'keterangan' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $stokSebelum = $barang->stok;
            $stokFisik = $validatedData['stok_fisik'];
            $jumlahPenyesuaian = $stokFisik - $stokSebelum;

            $barang->stok = $stokFisik;
            $barang->save();

            HistoriStok::create([
                'barang_id' => $barang->id,
                'user_id' => Auth::id(),
                'tipe' => 'penyesuaian',
                'jumlah' => $jumlahPenyesuaian,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokFisik,
                'keterangan' => $validatedData['keterangan'],
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Stok barang {$barang->nama_barang} berhasil disesuaikan.",
                    'new_stock' => $stokFisik,
                    'redirect' => route('pengurus.stok.index')
                ]);
            }

            return redirect()->route('pengurus.stok.index')->with('success', "Stok barang {$barang->nama_barang} berhasil disesuaikan.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal melakukan penyesuaian stok: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal melakukan penyesuaian stok: Terjadi kesalahan sistem.'
                ], 422);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal melakukan penyesuaian stok: Terjadi kesalahan sistem.');
        }
    }

    public function quickStockUpdate(Request $request, Barang $barang)
    {
        $validatedData = $request->validate([
            'action' => ['required', 'in:add,subtract'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $stokSebelum = $barang->stok;
            
            if ($validatedData['action'] === 'add') {
                $stokSesudah = $stokSebelum + $validatedData['quantity'];
                $tipe = 'masuk';
                $keterangan = $validatedData['note'] ?? 'Quick add stock';
            } else {
                if ($validatedData['quantity'] > $stokSebelum) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jumlah yang dikurangi tidak boleh melebihi stok saat ini.'
                    ], 422);
                }
                $stokSesudah = $stokSebelum - $validatedData['quantity'];
                $tipe = 'keluar';
                $keterangan = $validatedData['note'] ?? 'Quick subtract stock';
            }

            $barang->stok = $stokSesudah;
            $barang->save();

            HistoriStok::create([
                'barang_id' => $barang->id,
                'user_id' => Auth::id(),
                'tipe' => $tipe,
                'jumlah' => $validatedData['quantity'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $keterangan,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil diperbarui.',
                'new_stock' => $stokSesudah,
                'stock_status' => $this->getStockStatus($stokSesudah)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal quick update stok: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui stok: Terjadi kesalahan sistem.'
            ], 422);
        }
    }

    private function getStockStatus($stock)
    {
        if ($stock == 0) {
            return ['status' => 'out', 'class' => 'text-red-600', 'label' => 'Habis'];
        } elseif ($stock <= 10) {
            return ['status' => 'low', 'class' => 'text-yellow-600', 'label' => 'Rendah'];
        } else {
            return ['status' => 'normal', 'class' => 'text-green-600', 'label' => 'Normal'];
        }
    }
}
