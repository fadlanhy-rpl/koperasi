<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\UnitUsaha;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManajemenUnitUsahaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,pengurus']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UnitUsaha::withCount('barangs');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm){
                $q->where('nama_unit_usaha', 'like', '%' . $searchTerm . '%')
                  ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%');
            });
        }

        $unitUsahas = $query->orderBy('nama_unit_usaha')->paginate(12)->withQueryString();

        // Calculate statistics
        $stats = [
            'total' => UnitUsaha::count(),
            'totalBarang' => UnitUsaha::withCount('barangs')->get()->sum('barangs_count'),
            'recentUnits' => UnitUsaha::where('created_at', '>=', now()->subDays(30))->count(),
            'averageBarang' => UnitUsaha::count() > 0 ? 
                round(UnitUsaha::withCount('barangs')->get()->avg('barangs_count'), 1) : 0
        ];

        if ($request->ajax()) {
            $viewMode = $request->get('view_mode', 'grid');
            
            if ($viewMode === 'table') {
                $html = view('pengurus.unit_usaha.partials._unit_usaha_table_rows', compact('unitUsahas'))->render();
            } else {
                $html = view('pengurus.unit_usaha.partials._unit_usaha_grid_cards', compact('unitUsahas'))->render();
            }

            return response()->json([
                'html' => $html,
                'pagination' => (string) $unitUsahas->links('vendor.pagination.tailwind'),
                'stats' => $stats,
                'success' => true
            ]);
        }

        return view('pengurus.unit_usaha.index', compact('unitUsahas', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengurus.unit_usaha.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama_unit_usaha' => [
                    'required', 
                    'string', 
                    'max:255', 
                    'unique:unit_usahas,nama_unit_usaha'
                ],
                'deskripsi' => ['nullable', 'string', 'max:1000'],
            ], [
                'nama_unit_usaha.required' => 'Nama unit usaha harus diisi.',
                'nama_unit_usaha.unique' => 'Nama unit usaha sudah digunakan.',
                'nama_unit_usaha.max' => 'Nama unit usaha maksimal 255 karakter.',
                'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
            ]);

            DB::beginTransaction();
            
            $unitUsaha = UnitUsaha::create($validatedData);
            
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unit usaha berhasil ditambahkan.',
                    'data' => $unitUsaha,
                    'redirect' => route('pengurus.unit-usaha.index')
                ]);
            }

            return redirect()->route('pengurus.unit-usaha.index')
                ->with('success', 'Unit usaha "' . $unitUsaha->nama_unit_usaha . '" berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid.',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating unit usaha: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data.'
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitUsaha $unitUsaha)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $unitUsaha,
                'html' => view('pengurus.unit_usaha.partials._edit_modal', compact('unitUsaha'))->render()
            ]);
        }

        return view('pengurus.unit_usaha.edit', compact('unitUsaha'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitUsaha $unitUsaha)
    {
        try {
            $validatedData = $request->validate([
                'nama_unit_usaha' => [
                    'required', 
                    'string', 
                    'max:255', 
                    Rule::unique('unit_usahas', 'nama_unit_usaha')->ignore($unitUsaha->id)
                ],
                'deskripsi' => ['nullable', 'string', 'max:1000'],
            ], [
                'nama_unit_usaha.required' => 'Nama unit usaha harus diisi.',
                'nama_unit_usaha.unique' => 'Nama unit usaha sudah digunakan.',
                'nama_unit_usaha.max' => 'Nama unit usaha maksimal 255 karakter.',
                'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
            ]);

            DB::beginTransaction();
            
            $oldName = $unitUsaha->nama_unit_usaha;
            $unitUsaha->update($validatedData);
            
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unit usaha berhasil diperbarui.',
                    'data' => $unitUsaha->fresh(),
                    'redirect' => route('pengurus.unit-usaha.index')
                ]);
            }

            return redirect()->route('pengurus.unit-usaha.index')
                ->with('success', 'Unit usaha "' . $unitUsaha->nama_unit_usaha . '" berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid.',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating unit usaha: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data.'
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitUsaha $unitUsaha)
    {
        try {
            DB::beginTransaction();
            
            // Check if unit has related items
            $barangCount = $unitUsaha->barangs()->count();
            
            if ($barangCount > 0) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Tidak dapat menghapus unit usaha karena masih memiliki {$barangCount} barang terkait."
                    ], 422);
                }
                
                return redirect()->route('pengurus.unit-usaha.index')
                    ->with('error', "Tidak dapat menghapus unit usaha karena masih memiliki {$barangCount} barang terkait.");
            }

            $unitName = $unitUsaha->nama_unit_usaha;
            $unitUsaha->delete();
            
            DB::commit();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Unit usaha \"{$unitName}\" berhasil dihapus.",
                    'redirect' => route('pengurus.unit-usaha.index')
                ]);
            }

            return redirect()->route('pengurus.unit-usaha.index')
                ->with('success', "Unit usaha \"{$unitName}\" berhasil dihapus.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting unit usaha: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data.'
                ], 500);
            }
            
            return redirect()->route('pengurus.unit-usaha.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    /**
     * Get unit usaha details for quick view
     */
    public function show(UnitUsaha $unitUsaha)
    {
        $unitUsaha->load(['barangs' => function($query) {
            $query->latest()->take(5);
        }]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $unitUsaha,
                'html' => view('pengurus.unit_usaha.partials._detail_modal', compact('unitUsaha'))->render()
            ]);
        }

        return view('pengurus.unit_usaha.show', compact('unitUsaha'));
    }
}
