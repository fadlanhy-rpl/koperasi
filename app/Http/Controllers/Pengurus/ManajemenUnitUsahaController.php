<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\UnitUsaha;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManajemenUnitUsahaController extends Controller
{
    public function __construct()
    {
        // Proteksi semua method di controller ini hanya untuk admin dan pengurus
        $this->middleware(['auth', 'role:admin,pengurus']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UnitUsaha::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm){
                $q->where('nama_unit_usaha', 'like', '%' . $searchTerm . '%')
                  ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%');
            });
        }

        $unitUsahas = $query->orderBy('nama_unit_usaha')->paginate(10)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('pengurus.unit_usaha.partials._unit_usaha_table_rows', compact('unitUsahas'))->render(),
                'pagination' => (string) $unitUsahas->links()
            ]);
        }

        return view('pengurus.unit_usaha.index', compact('unitUsahas'));
    }

    /**
     * Show the form for creating a new resource.
     * (Untuk API, endpoint ini mungkin tidak terlalu relevan)
     */
    public function create()
    {

        // Jika menggunakan Blade:
        return view('pengurus.unit_usaha.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_unit_usaha' => ['required', 'string', 'max:255', 'unique:unit_usahas,nama_unit_usaha'],
            'deskripsi' => ['nullable', 'string', 'max:1000'], // Tingkatkan max jika perlu
        ]);

        UnitUsaha::create($validatedData);

        return redirect()->route('pengurus.unit-usaha.index')->with('success', 'Unit usaha baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource. (Tidak digunakan jika tidak ada view show terpisah)
     */
    // public function show(UnitUsaha $unitUsaha)
    // {
    //     // $unitUsaha->load('barangs');
    //     // return view('pengurus.unit_usaha.show', compact('unitUsaha'));
    // }
    /**
     * Show the form for editing the specified resource.
     * (Untuk API, endpoint ini mungkin tidak terlalu relevan)
     */
    public function edit(UnitUsaha $unitUsaha) // Route model binding akan mencari $unitUsaha
    {
        return view('pengurus.unit_usaha.edit', compact('unitUsaha'));
    }

    public function update(Request $request, UnitUsaha $unitUsaha)
    {
        $validatedData = $request->validate([
            'nama_unit_usaha' => ['required', 'string', 'max:255', Rule::unique('unit_usahas', 'nama_unit_usaha')->ignore($unitUsaha->id)],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ]);

        $unitUsaha->update($validatedData);

        return redirect()->route('pengurus.unit-usaha.index')->with('success', 'Data unit usaha berhasil diperbarui.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitUsaha $unitUsaha)
    {
        // Ingat: onDelete('cascade') di migrasi barangs akan menghapus barang terkait.
        // Tambahkan konfirmasi atau logika pencegahan jika masih ada barang aktif.
        if ($unitUsaha->barangs()->exists()) { // Cek jika ada barang terkait
             return redirect()->route('pengurus.unit-usaha.index')->with('error', 'Tidak dapat menghapus unit usaha karena masih memiliki barang terkait. Hapus atau pindahkan barang terlebih dahulu.');
        }

        try {
            $unitUsaha->delete();
            return redirect()->route('pengurus.unit-usaha.index')->with('success', 'Unit usaha berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('pengurus.unit-usaha.index')->with('error', 'Gagal menghapus unit usaha. Terjadi kesalahan.');
        }
    }
}