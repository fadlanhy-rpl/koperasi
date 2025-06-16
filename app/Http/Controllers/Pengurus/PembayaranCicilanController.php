<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\Cicilan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // Untuk Carbon jika digunakan

class PembayaranCicilanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,pengurus']);
    }

    /**
     * Menampilkan daftar transaksi pembelian yang memiliki cicilan atau belum lunas.
     */
    public function index(Request $request)
    {
        $query = Pembelian::whereIn('status_pembayaran', ['cicilan', 'belum_lunas'])
                            ->with(['user:id,name,nomor_anggota', 'cicilans']) // Eager load user dan cicilans
                            ->orderBy('tanggal_pembelian', 'desc');

        if ($request->filled('search_pembelian_cicilan')) { // Nama parameter search yang unik
            $searchTerm = $request->search_pembelian_cicilan;
            $query->where(function($q) use ($searchTerm) {
                $q->where('kode_pembelian', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('nomor_anggota', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $pembelians = $query->paginate(15)->withQueryString();

        // Menambahkan sisa tagihan ke setiap item pembelian untuk ditampilkan di view
        $pembelians->getCollection()->transform(function ($pembelian) {
            $totalCicilanDibayar = $pembelian->cicilans->sum('jumlah_bayar');
            $pembayaranAwal = $pembelian->total_bayar; // Ini bisa jadi DP atau 0
            $pembelian->sisa_tagihan_aktual = max(0, ($pembelian->total_harga - $pembayaranAwal - $totalCicilanDibayar));
            return $pembelian;
        });
        
        // Filter lagi hanya yang sisa tagihannya > 0
        // Ini dilakukan setelah paginasi, jadi mungkin tidak ideal untuk performa besar.
        // Alternatif: subquery atau HAVING clause di query utama jika memungkinkan.
        // Untuk sekarang, kita filter di collection.
        // $pembelians = $pembelians->filter(function($pembelian) {
        //     return $pembelian->sisa_tagihan_aktual > 0;
        // });
        // Paginasi ulang setelah filter collection akan rumit. Jadi, lebih baik jika sisa_tagihan dihitung di query.
        // Untuk penyederhanaan, kita tampilkan semua yang statusnya cicilan/belum_lunas, dan view akan menampilkan sisa tagihan.

        return view('pengurus.pembayaran_cicilan.index', compact('pembelians'));
    }

    /**
     * Menampilkan form untuk mencatat pembayaran cicilan untuk sebuah pembelian.
     */
    public function showFormBayarCicilan(Pembelian $pembelian)
    {
        // ... (Method showFormBayarCicilan SAMA seperti versi finish sebelumnya) ...
        if ($pembelian->status_pembayaran === 'lunas') {
            return redirect()->route('pengurus.transaksi-pembelian.show', $pembelian->id)
                             ->with('info', 'Pembelian ini sudah lunas, tidak ada cicilan yang perlu dibayar.');
        }
        $totalSudahBayarCicilan = $pembelian->cicilans()->sum('jumlah_bayar');
        $pembayaranAwalDiHeader = $pembelian->total_bayar; 
        $sisaTagihan = $pembelian->total_harga - $pembayaranAwalDiHeader - $totalSudahBayarCicilan;
        if ($sisaTagihan <= 0 && $pembelian->status_pembayaran !== 'lunas') {
            DB::beginTransaction(); try { $pembelian->status_pembayaran = 'lunas'; $pembelian->total_bayar = $pembelian->total_harga; $pembelian->kembalian = ($pembayaranAwalDiHeader + $totalSudahBayarCicilan) - $pembelian->total_harga; $pembelian->save(); DB::commit(); return redirect()->route('pengurus.transaksi-pembelian.show', $pembelian->id)->with('success', 'Status pembelian telah otomatis diupdate menjadi lunas.'); } catch (\Exception $e) { DB::rollBack(); Log::error("Error auto-update status lunas: " . $e->getMessage()); $sisaTagihan = 0; }
        }
        return view('pengurus.pembayaran_cicilan.create', compact('pembelian', 'sisaTagihan'));
    }

    /**
     * Menyimpan pembayaran cicilan baru untuk sebuah pembelian.
     */
    public function storePembayaranCicilan(Request $request, Pembelian $pembelian)
    {
        // ... (Method storePembayaranCicilan SAMA seperti versi finish sebelumnya) ...
        if ($pembelian->status_pembayaran === 'lunas') { return redirect()->route('pengurus.transaksi-pembelian.show', $pembelian->id)->with('info', 'Pembelian ini sudah lunas.');}
        $totalSudahBayarCicilanSebelumnya = $pembelian->cicilans()->sum('jumlah_bayar');
        $pembayaranAwalDiHeader = $pembelian->total_bayar;
        $sisaTagihanSebelumPembayaranIni = $pembelian->total_harga - $pembayaranAwalDiHeader - $totalSudahBayarCicilanSebelumnya;
        $maxBayar = max(0.01, $sisaTagihanSebelumPembayaranIni);
        $validatedData = $request->validate([ 'jumlah_bayar' => ['required', 'numeric', 'min:0.01', 'max:' . $maxBayar], 'tanggal_bayar' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'], 'keterangan' => ['nullable', 'string', 'max:255'], ]);
        DB::beginTransaction(); try { Cicilan::create([ 'pembelian_id' => $pembelian->id, 'jumlah_bayar' => $validatedData['jumlah_bayar'], 'tanggal_bayar' => $validatedData['tanggal_bayar'], 'pengurus_id' => Auth::id(), 'keterangan' => $validatedData['keterangan'], ]); $totalPembayaranCicilanSaatIni = $pembelian->cicilans()->sum('jumlah_bayar'); $totalSemuaPembayaran = $pembayaranAwalDiHeader + $totalPembayaranCicilanSaatIni; if ($totalSemuaPembayaran >= $pembelian->total_harga) { $pembelian->status_pembayaran = 'lunas'; $pembelian->total_bayar = $totalSemuaPembayaran; $pembelian->kembalian = $totalSemuaPembayaran - $pembelian->total_harga; } $pembelian->save(); DB::commit(); return redirect()->route('pengurus.transaksi-pembelian.show', $pembelian->id)->with('success', "Pembayaran cicilan berhasil dicatat."); } catch (\Exception $e) { DB::rollBack(); Log::error("Error store cicilan: " . $e->getMessage()); return redirect()->back()->withInput()->with('error', 'Gagal mencatat pembayaran cicilan.'); }
    }
}