<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\UnitUsaha;
use App\Models\HistoriStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManajemenBarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:pengurus');
    }

    public function index(Request $request)
    {
        $query = Barang::with('unitUsaha:id,nama_unit_usaha');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
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
                'pagination' => $barangs->links()->render()
            ]);
        }
        
        return view('pengurus.barang.index', compact('barangs', 'unitUsahas'));
    }

    public function create()
    {
        $unitUsahas = UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']);
        $satuans = ['pcs', 'lusin', 'kg', 'liter', 'set', 'pak', 'dus', 'rim', 'botol', 'buah'];
        return view('pengurus.barang.create', compact('unitUsahas', 'satuans'));
    }

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
            'cropped_gambar_barang' => ['nullable', 'string'],
        ]);

        // Generate kode barang jika kosong
        if (empty($validatedData['kode_barang'])) {
            $unitUsaha = UnitUsaha::find($validatedData['unit_usaha_id']);
            $prefixUnit = strtoupper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $unitUsaha->nama_unit_usaha ?? 'BRG'), 0, 3));
            $prefixNama = strtoupper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $validatedData['nama_barang']), 0, 3));
            
            do {
                $randomSuffix = strtoupper(Str::random(4));
                $generatedCode = $prefixUnit . '-' . $prefixNama . '-' . $randomSuffix;
            } while (Barang::where('kode_barang', $generatedCode)->exists());
            
            $validatedData['kode_barang'] = $generatedCode;
        }

        DB::beginTransaction();
        try {
            // Handle gambar jika ada
            $gambarPath = null;
            if ($request->filled('cropped_gambar_barang')) {
                $gambarPath = $this->handleImageUpload($request->input('cropped_gambar_barang'));
            }
            
            $validatedData['gambar_path'] = $gambarPath;
            unset($validatedData['cropped_gambar_barang']);

            $barang = Barang::create($validatedData);

            // Buat histori stok awal jika stok > 0
            if ($barang->stok > 0) {
                HistoriStok::create([
                    'barang_id' => $barang->id,
                    'user_id' => Auth::id(),
                    'tipe' => 'masuk',
                    'jumlah' => $barang->stok,
                    'stok_sebelum' => 0,
                    'stok_sesudah' => $barang->stok,
                    'keterangan' => 'Stok awal saat penambahan barang',
                ]);
            }

            DB::commit();
            return redirect()->route('pengurus.barang.index')->with('success', 'Barang baru berhasil ditambahkan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            if ($gambarPath && Storage::disk('public')->exists($gambarPath)) {
                Storage::disk('public')->delete($gambarPath);
            }
            Log::error("Gagal menambah barang: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage());
        }
    }

    public function show(Barang $barang, Request $request)
    {
        $barang->load('unitUsaha:id,nama_unit_usaha');
        $historiStoks = $barang->historiStoks()
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'page_histori')
            ->withQueryString();
            
        if($request->ajax() && $request->has('page_histori')){
            return response()->json([
                'html' => view('pengurus.barang.partials._histori_stok_rows', compact('historiStoks'))->render(),
                'pagination' => $historiStoks->links()->render()
            ]);
        }
        
        return view('pengurus.barang.show', compact('barang', 'historiStoks'));
    }

    public function edit(Barang $barang)
    {
        $unitUsahas = UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']);
        $satuans = ['pcs', 'lusin', 'kg', 'liter', 'set', 'pak', 'dus', 'rim', 'botol', 'buah'];
        return view('pengurus.barang.edit', compact('barang', 'unitUsahas', 'satuans'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validatedData = $request->validate([
            'unit_usaha_id' => ['required', 'exists:unit_usahas,id'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'kode_barang' => ['nullable', 'string', 'max:50', Rule::unique('barangs', 'kode_barang')->ignore($barang->id)->whereNull('deleted_at')],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0', 'gte:harga_beli'],
            'satuan' => ['required', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'cropped_gambar_barang' => ['nullable', 'string'],
            'hapus_gambar_sekarang' => ['nullable', 'boolean'],
        ]);

        // Handle kode barang
        if (empty($validatedData['kode_barang']) && !$barang->kode_barang) {
            $unitUsaha = UnitUsaha::find($validatedData['unit_usaha_id']);
            $prefixUnit = strtoupper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $unitUsaha->nama_unit_usaha ?? 'BRG'), 0, 3));
            $prefixNama = strtoupper(Str::substr(preg_replace('/[^A-Za-z0-9]/', '', $validatedData['nama_barang']), 0, 3));
            
            do {
                $randomSuffix = strtoupper(Str::random(4));
                $generatedCode = $prefixUnit . '-' . $prefixNama . '-' . $randomSuffix;
            } while (Barang::where('kode_barang', $generatedCode)->where('id', '!=', $barang->id)->exists());
            
            $validatedData['kode_barang'] = $generatedCode;
        } elseif(empty($validatedData['kode_barang']) && $barang->kode_barang) {
            unset($validatedData['kode_barang']);
        }

        DB::beginTransaction();
        try {
            $gambarPathBaru = $barang->gambar_path;

            // Handle penghapusan gambar jika dicentang
            if ($request->boolean('hapus_gambar_sekarang') && $barang->gambar_path) {
                if (Storage::disk('public')->exists($barang->gambar_path)) {
                    Storage::disk('public')->delete($barang->gambar_path);
                }
                $gambarPathBaru = null;
            }

            // Handle upload gambar baru jika ada
            if ($request->filled('cropped_gambar_barang')) {
                // Hapus gambar lama jika ada dan akan diganti dengan yang baru
                if ($barang->gambar_path && Storage::disk('public')->exists($barang->gambar_path)) {
                    Storage::disk('public')->delete($barang->gambar_path);
                }
                $gambarPathBaru = $this->handleImageUpload($request->input('cropped_gambar_barang'));
            }

            $validatedData['gambar_path'] = $gambarPathBaru;
            unset($validatedData['cropped_gambar_barang'], $validatedData['hapus_gambar_sekarang']);

            $barang->update($validatedData);
            
            DB::commit();
            return redirect()->route('pengurus.barang.index')->with('success', 'Data barang berhasil diperbarui.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal update barang #{$barang->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data barang: ' . $e->getMessage());
        }
    }

    public function destroy(Barang $barang)
    {
        if ($barang->detailPembelians()->exists()) {
            return redirect()->route('pengurus.barang.index')->with('error', 'Barang tidak dapat dihapus karena sudah digunakan dalam transaksi pembelian.');
        }

        DB::beginTransaction();
        try {
            // Hapus gambar dari storage sebelum menghapus record barang
            if ($barang->gambar_path && Storage::disk('public')->exists($barang->gambar_path)) {
                Storage::disk('public')->delete($barang->gambar_path);
            }
            
            $barang->delete();
            
            DB::commit();
            return redirect()->route('pengurus.barang.index')->with('success', 'Barang berhasil dihapus.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal hapus barang #{$barang->id}: " . $e->getMessage());
            return redirect()->route('pengurus.barang.index')->with('error', 'Gagal menghapus barang.');
        }
    }

    /**
     * Handle image upload from base64 data
     */
    private function handleImageUpload($base64Data)
    {
        if (!$base64Data || !Str::startsWith($base64Data, 'data:image')) {
            return null;
        }

        // Extract image data
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $imageData = substr($base64Data, strpos($base64Data, ',') + 1);
            $fileExtension = strtolower($type[1]);

            // Validate file extension
            if (!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp'])) {
                throw new \Exception('Tipe file gambar tidak valid.');
            }

            // Decode base64
            $imageData = base64_decode($imageData);
            if ($imageData === false) {
                throw new \Exception('Gagal decode data gambar base64.');
            }

            // Validate file size (max 2MB)
            if (strlen($imageData) > (2 * 1024 * 1024)) {
                throw new \Exception('Ukuran file gambar terlalu besar (Maksimal 2MB).');
            }

        } else {
            throw new \Exception('Format data URI gambar tidak valid.');
        }

        // Create directory if not exists
        $directory = 'barang-photos';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Generate unique filename
        $fileName = time() . '_' . Str::random(10) . '.' . $fileExtension;
        $gambarPath = $directory . '/' . $fileName;

        // Store the image
        Storage::disk('public')->put($gambarPath, $imageData);

        return $gambarPath;
    }
}