<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\UnitUsaha;
use App\Models\HistoriStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManajemenBarangController extends Controller
{
    public function __construct()
    {
        // Middleware sudah diterapkan pada level route group
        // $this->middleware(['auth', 'role:admin,pengurus']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Barang::with('unitUsaha:id,nama_unit_usaha');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_barang', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kode_barang', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('unit_usaha_filter') && $request->unit_usaha_filter != '') {
            $query->where('unit_usaha_id', $request->unit_usaha_filter);
        }

        $barangs = $query->orderBy('nama_barang')->paginate(15)->withQueryString();
        $unitUsahas = UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('pengurus.barang.partials._barang_table_rows', compact('barangs'))->render(),
                'pagination' => (string) $barangs->links('vendor.pagination.tailwind-ajax') // Pastikan view ini ada atau gunakan default
            ]);
        }
        
        return view('pengurus.barang.index', compact('barangs', 'unitUsahas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unitUsahas = UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']);
        // Daftar satuan yang umum, bisa juga diambil dari tabel/config jika lebih dinamis
        $satuans = ['pcs', 'lusin', 'kg', 'liter', 'set', 'pak', 'dus', 'rim', 'botol', 'buah', 'meter', 'roll'];
        return view('pengurus.barang.create', compact('unitUsahas', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'unit_usaha_id' => ['required', 'exists:unit_usahas,id'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'kode_barang' => ['nullable', 'string', 'max:50', Rule::unique('barangs', 'kode_barang')->whereNull('deleted_at')],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0', 'gte:harga_beli'],
            'stok' => ['required', 'integer', 'min:0'],
            'satuan' => ['required', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ]);

        // Generate kode_barang jika kosong
        if (empty($validatedData['kode_barang'])) {
            $unitUsaha = UnitUsaha::find($validatedData['unit_usaha_id']);
            // Ambil 3 huruf awal dari nama unit usaha (alphanumeric)
            $prefixUnit = strtoupper(Str::limit(preg_replace('/[^A-Za-z0-9]/', '', $unitUsaha->nama_unit_usaha ?? 'BRG'), 3, ''));
            // Ambil 3 huruf awal dari nama barang (alphanumeric)
            $prefixNama = strtoupper(Str::limit(preg_replace('/[^A-Za-z0-9]/', '', $validatedData['nama_barang']), 3, ''));
            
            do {
                $randomSuffix = strtoupper(Str::random(4)); // Suffix random
                $generatedCode = $prefixUnit . $prefixNama . $randomSuffix;
                // Pastikan kode unik, jika tidak, generate lagi
            } while (Barang::where('kode_barang', $generatedCode)->exists());
            $validatedData['kode_barang'] = $generatedCode;
        }

        DB::beginTransaction();
        try {
            $barang = Barang::create($validatedData);

            // Jika stok awal > 0, catat di histori stok
            if ($barang->stok > 0) {
                HistoriStok::create([
                    'barang_id' => $barang->id,
                    'user_id' => Auth::id(), // User yang melakukan input
                    'tipe' => 'masuk',
                    'jumlah' => $barang->stok,
                    'stok_sebelum' => 0,
                    'stok_sesudah' => $barang->stok,
                    'keterangan' => 'Stok awal saat penambahan barang',
                ]);
            }
            DB::commit();
            return redirect()->route('pengurus.barang.index')->with('success', "Barang '{$barang->nama_barang}' berhasil ditambahkan.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menambah barang: " . $e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan barang: Terjadi kesalahan sistem.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang, Request $request)
    {
        $barang->load('unitUsaha:id,nama_unit_usaha');
        $historiStoks = $barang->historiStoks()
                                ->with('user:id,name') // Eager load user yang mencatat histori
                                ->orderBy('created_at', 'desc') // Urutkan dari terbaru
                                ->paginate(10, ['*'], 'page_histori') // Nama parameter paginasi unik
                                ->withQueryString();
        
        if($request->ajax() && $request->has('page_histori')){ // Cek jika AJAX untuk paginasi histori
            return response()->json([
                'html_histori' => view('pengurus.barang.partials._histori_stok_rows', compact('historiStoks'))->render(),
                'pagination_histori' => (string) $historiStoks->links('vendor.pagination.tailwind-ajax')
            ]);
        }

        return view('pengurus.barang.show', compact('barang', 'historiStoks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        $unitUsahas = UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']);
        $satuans = ['pcs', 'lusin', 'kg', 'liter', 'set', 'pak', 'dus', 'rim', 'botol', 'buah', 'meter', 'roll'];
        return view('pengurus.barang.edit', compact('barang', 'unitUsahas', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $validatedData = $request->validate([
            'unit_usaha_id' => ['required', 'exists:unit_usahas,id'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'kode_barang' => ['nullable', 'string', 'max:50', Rule::unique('barangs', 'kode_barang')->ignore($barang->id)->whereNull('deleted_at')],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0', 'gte:harga_beli'],
            // Stok tidak diupdate langsung di sini, tapi melalui Penyesuaian Stok.
            // 'stok' => ['sometimes', 'required', 'integer', 'min:0'], 
            'satuan' => ['required', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ]);

        // Jika kode barang dikosongkan saat update, dan sebelumnya sudah ada, biarkan kode lama.
        // Jika kode barang dikosongkan dan sebelumnya juga kosong, generate baru.
        if (empty($validatedData['kode_barang'])) {
            if (!$barang->kode_barang) { // Jika kode barang sebelumnya juga kosong
                $unitUsaha = UnitUsaha::find($validatedData['unit_usaha_id']);
                $prefixUnit = strtoupper(Str::limit(preg_replace('/[^A-Za-z0-9]/', '', $unitUsaha->nama_unit_usaha ?? 'BRG'), 3, ''));
                $prefixNama = strtoupper(Str::limit(preg_replace('/[^A-Za-z0-9]/', '', $validatedData['nama_barang']), 3, ''));
                do {
                    $generatedCode = $prefixUnit . $prefixNama . strtoupper(Str::random(4));
                } while (Barang::where('kode_barang', $generatedCode)->where('id', '!=', $barang->id)->exists());
                $validatedData['kode_barang'] = $generatedCode;
            } else {
                // Jika dikosongkan tapi sebelumnya ada, jangan ubah (tetap gunakan kode lama)
                unset($validatedData['kode_barang']);
            }
        }
        
        // Stok diupdate melalui menu Penyesuaian Stok, bukan dari form edit barang ini.
        // Jika Anda ingin memperbolehkan update stok dari sini, ini harus dicatat sebagai 'penyesuaian'.
        // if ($request->has('stok') && is_numeric($request->stok) && $request->stok != $barang->stok) {
        //     // ... logika pencatatan HistoriStok untuk penyesuaian ...
        //     $validatedData['stok'] = $request->stok;
        // }

        DB::beginTransaction();
        try {
            $barang->update($validatedData);
            DB::commit();
            return redirect()->route('pengurus.barang.index')->with('success', "Data barang '{$barang->nama_barang}' berhasil diperbarui.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal update barang #{$barang->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data barang: Terjadi kesalahan sistem.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        if ($barang->detailPembelians()->exists()) {
            return redirect()->route('pengurus.barang.index')->with('error', "Tidak dapat menghapus barang '{$barang->nama_barang}' karena sudah tercatat dalam transaksi pembelian. Anda bisa mengubah stoknya menjadi 0 jika tidak digunakan lagi.");
        }
        
        DB::beginTransaction();
        try {
            // Jika model Barang menggunakan SoftDeletes, ini akan melakukan soft delete.
            $barang->delete(); 
            DB::commit();
            return redirect()->route('pengurus.barang.index')->with('success', "Barang '{$barang->nama_barang}' berhasil dihapus (dimasukkan ke arsip).");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal hapus barang #{$barang->id}: " . $e->getMessage());
            return redirect()->route('pengurus.barang.index')->with('error', 'Gagal menghapus barang: Terjadi kesalahan sistem.');
        }
    }
}