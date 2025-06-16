<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\HistoriStok;
use App\Models\UnitUsaha; // <-- TAMBAHKAN IMPORT INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PencatatanStokController extends Controller
{
    public function __construct()
    {
        // Middleware sudah dihandle di route group, bisa dikomentari/dihapus
        // $this->middleware(['auth', 'role:admin,pengurus']);
    }

    public function index(Request $request)
    {
        $query = Barang::with('unitUsaha:id,nama_unit_usaha');

        if ($request->filled('search_stok')) {
            $searchTerm = $request->search_stok;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama_barang', 'like', '%' . $searchTerm . '%')
                    ->orWhere('kode_barang', 'like', '%' . $searchTerm . '%');
            });
        }
        if ($request->filled('unit_usaha_stok') && $request->unit_usaha_stok != '') {
            $query->where('unit_usaha_id', $request->unit_usaha_stok);
        }

        $barangs = $query->orderBy('nama_barang')->paginate(15)->withQueryString();
        
        // Sekarang 'UnitUsaha' akan dikenali
        $unitUsahas = UnitUsaha::orderBy('nama_unit_usaha')->get(['id', 'nama_unit_usaha']);

        return view('pengurus.stok.index', compact('barangs', 'unitUsahas'));
    }

    /**
     * Menampilkan form untuk menambah stok (barang masuk).
     */
    public function showFormBarangMasuk(Barang $barang)
    {
        return view('pengurus.stok.form_barang_masuk', compact('barang'));
    }

    /**
     * Menyimpan data barang masuk dan mengupdate stok.
     */
    public function storeBarangMasuk(Request $request, Barang $barang)
    {
        $validatedData = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $stokSebelum = $barang->stok;
            $stokSesudah = $stokSebelum + $validatedData['jumlah'];

            $barang->stok = $stokSesudah;
            $barang->save();

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
            return redirect()->route('pengurus.barang.show', $barang->id)->with('success', "Stok barang {$barang->nama_barang} berhasil ditambahkan.");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Gagal mencatat barang masuk: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat barang masuk: Terjadi kesalahan sistem.');
        }
    }

    /**
     * Menampilkan form untuk mengurangi stok (barang keluar/rusak).
     */
    public function showFormBarangKeluar(Barang $barang)
    {
        return view('pengurus.stok.form_barang_keluar', compact('barang'));
    }

    /**
     * Menyimpan data barang keluar (rusak, hilang) dan mengupdate stok.
     */
    public function storeBarangKeluar(Request $request, Barang $barang)
    {
        $validatedData = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1', 'max:' . $barang->stok], // Jumlah keluar tidak boleh > stok ada
            'keterangan' => ['required', 'string', 'max:255'], // Keterangan wajib untuk barang keluar
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
                'keterangan' => $validatedData['keterangan'],
            ]);

            DB::commit();
            return redirect()->route('pengurus.barang.show', $barang->id)->with('success', "Stok barang {$barang->nama_barang} berhasil dikurangi (keluar).");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Gagal mencatat barang keluar: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat barang keluar: Terjadi kesalahan sistem.');
        }
    }

    /**
     * Menampilkan form untuk penyesuaian stok.
     */
    public function showFormPenyesuaianStok(Barang $barang)
    {
        return view('pengurus.stok.form_penyesuaian_stok', compact('barang'));
    }

    /**
     * Menyimpan data penyesuaian stok (hasil stock opname) dan mengupdate stok.
     */
    public function storePenyesuaianStok(Request $request, Barang $barang)
    {
        $validatedData = $request->validate([
            'stok_fisik' => ['required', 'integer', 'min:0'], // Stok fisik hasil perhitungan
            'keterangan' => ['required', 'string', 'max:255'], // Keterangan wajib untuk penyesuaian
        ]);

        DB::beginTransaction();
        try {
            $stokSebelum = $barang->stok;
            $stokFisik = $validatedData['stok_fisik'];
            $jumlahPenyesuaian = $stokFisik - $stokSebelum; // Bisa positif (nambah) atau negatif (kurang)

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
            return redirect()->route('pengurus.barang.show', $barang->id)->with('success', "Stok barang {$barang->nama_barang} berhasil disesuaikan.");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Gagal melakukan penyesuaian stok: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal melakukan penyesuaian stok: Terjadi kesalahan sistem.');
        }
    }

    /**
     * Menampilkan histori stok untuk satu barang.
     * Method ini bisa jadi redundan jika fungsionalitasnya sudah ada di ManajemenBarangController@show
     * atau LaporanStokController@kartuStokBarang.
     * Jika tetap ingin digunakan, pastikan ada route yang mengarah ke sini.
     */
    // public function historiStokBarang(Request $request, Barang $barang)
    // {
    //     // ... (logika yang sudah ada sebelumnya) ...
    // }
}