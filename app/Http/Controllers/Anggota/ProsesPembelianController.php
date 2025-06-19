<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\HistoriStok;
use App\Models\SimpananSukarela; // Fokus pada simpanan sukarela dulu
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProsesPembelianController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:anggota']);
    }

    /**
     * Menampilkan halaman detail barang untuk anggota.
     */
    public function showDetailBarang(Barang $barang)
    {
        if ($barang->stok <= 0) {
            // Bisa juga tetap tampilkan tapi tombol beli nonaktif
            // return redirect()->route('anggota.pembelian.katalog')->with('warning', 'Stok barang '. $barang->nama_barang .' sedang habis.');
        }
        $barang->load('unitUsaha:id,nama_unit_usaha');
        // Ambil saldo sukarela anggota saat ini untuk ditampilkan
        $anggota = Auth::user();
        $transaksiTerakhirSukarela = $anggota->simpananSukarelas()->latest('tanggal_transaksi')->latest('id')->first();
        $saldoSukarelaAnggota = $transaksiTerakhirSukarela ? $transaksiTerakhirSukarela->saldo_sesudah : 0;

        return view('anggota.pembelian.barang_detail', compact('barang', 'saldoSukarelaAnggota'));
    }

    /**
     * Memproses pembelian barang oleh anggota menggunakan saldo simpanan sukarela.
     */
    public function prosesPembelianDenganSaldo(Request $request, Barang $barang)
    {
        $anggota = Auth::user(); // User yang sedang login (anggota)
        
        $request->validate([
            'jumlah_beli' => ['required', 'integer', 'min:1', 'max:' . $barang->stok],
        ], [
            'jumlah_beli.required' => 'Jumlah pembelian wajib diisi.',
            'jumlah_beli.min' => 'Jumlah pembelian minimal 1.',
            'jumlah_beli.max' => "Jumlah pembelian melebihi stok tersedia ({$barang->stok}).",
        ]);

        $jumlahBeli = (int) $request->input('jumlah_beli');
        $totalHargaPembelian = $barang->harga_jual * $jumlahBeli;

        // Cek Saldo Simpanan Sukarela
        $transaksiTerakhirSukarela = $anggota->simpananSukarelas()->latest('tanggal_transaksi')->latest('id')->first();
        $saldoSukarelaSaatIni = $transaksiTerakhirSukarela ? $transaksiTerakhirSukarela->saldo_sesudah : 0;

        if ($totalHargaPembelian > $saldoSukarelaSaatIni) {
            return redirect()->back()->with('error', 'Saldo simpanan sukarela Anda tidak mencukupi untuk transaksi ini. Saldo Anda: ' . number_format($saldoSukarelaSaatIni));
        }

        // Cek Ulang Stok (untuk mencegah race condition, meskipun sudah ada validasi)
        if ($jumlahBeli > $barang->fresh()->stok) { // Ambil stok terbaru dari DB
            return redirect()->back()->with('error', "Stok barang {$barang->nama_barang} tidak mencukupi. Silakan coba lagi.");
        }

        DB::beginTransaction();
        try {
            // 1. Buat record Pembelian
            $kodePembelian = 'INV-ANG/' . Carbon::now()->format('Ymd') . '/' . strtoupper(Str::random(5));
            $pembelian = Pembelian::create([
                'kode_pembelian' => $kodePembelian,
                'user_id' => $anggota->id, // Anggota yang melakukan pembelian
                'kasir_id' => null, // Transaksi dilakukan oleh anggota sendiri, bukan kasir
                'tanggal_pembelian' => Carbon::now(),
                'total_harga' => $totalHargaPembelian,
                'total_bayar' => $totalHargaPembelian, // Langsung lunas dari saldo
                'kembalian' => 0,
                'status_pembayaran' => 'lunas',
                'metode_pembayaran' => 'saldo_sukarela',
                'catatan' => 'Pembelian oleh anggota via saldo sukarela.',
            ]);

            // 2. Buat record DetailPembelian
            DetailPembelian::create([
                'pembelian_id' => $pembelian->id,
                'barang_id' => $barang->id,
                'jumlah' => $jumlahBeli,
                'harga_satuan' => $barang->harga_jual,
                'subtotal' => $totalHargaPembelian,
            ]);

            // 3. Kurangi Stok Barang
            $stokSebelum = $barang->stok;
            $barang->stok -= $jumlahBeli;
            $barang->save();

            // 4. Catat di HistoriStok
            HistoriStok::create([
                'barang_id' => $barang->id,
                'user_id' => $anggota->id, // Dicatat oleh anggota yang melakukan transaksi
                'tipe' => 'keluar',
                'jumlah' => $jumlahBeli,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $barang->stok,
                'keterangan' => "Penjualan ke anggota No. {$pembelian->kode_pembelian}",
            ]);

            // 5. Kurangi Saldo Simpanan Sukarela Anggota
            SimpananSukarela::create([
                'user_id' => $anggota->id,
                'tipe_transaksi' => 'tarik',
                'jumlah' => $totalHargaPembelian,
                'saldo_sebelum' => $saldoSukarelaSaatIni,
                'saldo_sesudah' => $saldoSukarelaSaatIni - $totalHargaPembelian,
                'tanggal_transaksi' => Carbon::now()->format('Y-m-d'),
                'pengurus_id' => null, // Transaksi oleh anggota sendiri
                'keterangan' => "Pembayaran pembelian barang No. {$kodePembelian}",
            ]);
            
            DB::commit();
            return redirect()->route('anggota.pembelian.detail', $pembelian->id)->with('success', "Pembelian barang {$barang->nama_barang} berhasil!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal proses pembelian oleh anggota #{$anggota->id} untuk barang #{$barang->id}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return redirect()->route('anggota.pembelian.katalog')->with('error', 'Gagal memproses pembelian: ' . $e->getMessage());
        }
    }
}