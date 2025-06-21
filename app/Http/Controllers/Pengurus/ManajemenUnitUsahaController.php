<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\UnitUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManajemenUnitUsahaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $viewMode = $request->input('view_mode', 'grid'); // Tambahkan view mode
        
        $query = UnitUsaha::withCount('barangs');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_unit_usaha', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'nama_unit_usaha');
        $sortOrder = $request->input('sort_order', 'asc');
        
        $validSortColumns = ['nama_unit_usaha', 'created_at', 'barangs_count'];
        if (in_array($sortBy, $validSortColumns)) {
            if ($sortBy === 'barangs_count') {
                $query->orderBy('barangs_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('nama_unit_usaha', 'asc');
        }

        $unitUsahas = $query->paginate($perPage)->appends($request->except('page'));

        // Statistik global
        $allUnits = UnitUsaha::withCount('barangs')->get();
        $stats = [
            'total' => $allUnits->count(),
            'totalBarang' => $allUnits->sum('barangs_count'),
            'unitTerbaru' => UnitUsaha::where('created_at', '>=', now()->subDays(30))->count(),
            'rataRataBarang' => $allUnits->count() > 0 ? round($allUnits->sum('barangs_count') / $allUnits->count(), 1) : 0
        ];
        
        if ($request->ajax()) {
            $html = '';
            $gridHtml = '';
            
            // Generate HTML berdasarkan view mode yang diminta
            if ($viewMode === 'table') {
                $html = view('pengurus.unit_usaha.partials._unit_usaha_table_rows', compact('unitUsahas'))->render();
            } else {
                $gridHtml = view('pengurus.unit_usaha.partials._unit_usaha_grid_items', compact('unitUsahas'))->render();
            }
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'gridHtml' => $gridHtml,
                'pagination' => $unitUsahas->links('vendor.pagination.tailwind')->render(),
                'stats' => $stats,
                'total' => $unitUsahas->total(),
                'currentPage' => $unitUsahas->currentPage(),
                'lastPage' => $unitUsahas->lastPage(),
                'viewMode' => $viewMode
            ]);
        }

        return view('pengurus.unit_usaha.index', compact('unitUsahas', 'search', 'stats'));
    }

    public function create()
    {
        return view('pengurus.unit_usaha.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_unit_usaha' => 'required|string|max:255|unique:unit_usahas,nama_unit_usaha',
            'deskripsi' => 'nullable|string|max:1000',
        ], [
            'nama_unit_usaha.required' => 'Nama unit usaha harus diisi.',
            'nama_unit_usaha.unique' => 'Nama unit usaha sudah digunakan.',
            'nama_unit_usaha.max' => 'Nama unit usaha maksimal 255 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.'
        ]);

        try {
            UnitUsaha::create($validatedData);
            return redirect()->route('pengurus.unit-usaha.index')
                           ->with('success', 'Unit usaha berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating unit usaha: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan saat menambahkan unit usaha.');
        }
    }

    public function edit(UnitUsaha $unitUsaha)
    {
        return view('pengurus.unit_usaha.edit', compact('unitUsaha'));
    }

    public function update(Request $request, UnitUsaha $unitUsaha)
    {
        $validatedData = $request->validate([
            'nama_unit_usaha' => 'required|string|max:255|unique:unit_usahas,nama_unit_usaha,' . $unitUsaha->id,
            'deskripsi' => 'nullable|string|max:1000',
        ], [
            'nama_unit_usaha.required' => 'Nama unit usaha harus diisi.',
            'nama_unit_usaha.unique' => 'Nama unit usaha sudah digunakan.',
            'nama_unit_usaha.max' => 'Nama unit usaha maksimal 255 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.'
        ]);

        try {
            $unitUsaha->update($validatedData);
            return redirect()->route('pengurus.unit-usaha.index')
                           ->with('success', 'Unit usaha berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating unit usaha: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan saat memperbarui unit usaha.');
        }
    }
    
    public function destroy(UnitUsaha $unitUsaha)
    {
        try {
            // Check if unit has related barangs
            if ($unitUsaha->barangs()->exists()) {
                return redirect()->route('pengurus.unit-usaha.index')
                               ->with('error', 'Gagal menghapus! Unit usaha masih memiliki barang terkait.');
            }

            $unitUsaha->delete();

            return redirect()->route('pengurus.unit-usaha.index')
                           ->with('success', 'Unit usaha berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Error deleting unit usaha: ' . $e->getMessage());
            return redirect()->route('pengurus.unit-usaha.index')
                           ->with('error', 'Terjadi kesalahan saat menghapus unit usaha.');
        }
    }
}
