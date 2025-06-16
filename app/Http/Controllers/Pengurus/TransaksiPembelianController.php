<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Barang;
use App\Models\User;
use App\Models\HistoriStok;
use App\Models\SimpananSukarela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\Rule; // Pastikan Rule di-import
use Illuminate\Support\Facades\Log; // Untuk logging error

class TransaksiPembelianController extends Controller
{
    public function __construct()
    {
        // Middleware sudah diterapkan pada level route group
        // $this->middleware(['auth', 'role:admin,pengurus']);
    }

    /**
     * Menampilkan daftar transaksi pembelian yang pernah terjadi.
     */
    public function index(Request $request)
    {
        $query = Pembelian::with(['user:id,name,nomor_anggota', 'kasir:id,name'])
                            ->orderBy('tanggal_pembelian', 'desc');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('kode_pembelian', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', '%' . $searchTerm . '%')
                                ->orWhere('nomor_anggota', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->filled('status_pembayaran') && $request->status_pembayaran != 'all') {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pembelian', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pembelian', '<=', $request->tanggal_selesai);
        }

        $pembelians = $query->paginate(15)->withQueryString();
        $statuses = ['lunas', 'belum_lunas', 'cicilan']; // Untuk filter dropdown

        if($request->ajax()){
             return response()->json([
                'html' => view('pengurus.transaksi_pembelian.partials._transaksi_table_rows', compact('pembelians'))->render(),
                'pagination' => (string) $pembelians->links('vendor.pagination.tailwind-ajax') // Gunakan view pagination AJAX
            ]);
        }

        return view('pengurus.transaksi_pembelian.index', compact('pembelians', 'statuses'));
    }

    /**
     * Menampilkan form untuk membuat transaksi pembelian baru (Point of Sale).
     */
    public function create()
    {
        $anggota = User::where('role', 'anggota')->orderBy('name')->get(['id', 'name', 'nomor_anggota']);
        $barangs = Barang::where('stok', '>', 0) // Hanya barang yang ada stok
                         ->orderBy('nama_barang')
                         ->get(['id', 'nama_barang', 'kode_barang', 'harga_jual', 'stok', 'satuan']);
        
        return view('pengurus.transaksi_pembelian.create', compact('anggota', 'barangs'));
    }

    /**
     * Menyimpan transaksi pembelian baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', 'anggota')],
            'tanggal_pembelian' => ['required', 'date'], // Di-parse ke Y-m-d H:i:s nanti
            'metode_pembayaran' => ['required', Rule::in(['tunai', 'saldo_sukarela', 'hutang'])],
            'items' => ['required', 'array', 'min:1'],
            'items.*.barang_id' => ['required', 'exists:barangs,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
            // Harga satuan diambil dari DB, tidak dari request untuk keamanan
            'total_bayar_manual' => ['nullable', 'numeric', 'min:0'], // Untuk pembayaran tunai
            'uang_muka' => ['nullable', 'numeric', 'min:0'], // Untuk pembayaran hutang/cicilan jika ada DP
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $kasirId = Auth::id();
        $totalHargaKeseluruhan = 0;
        $detailItemsData = []; // Untuk menyimpan data item yang akan dibuat

        DB::beginTransaction();
        try {
            // 1. Validasi stok dan hitung total harga dari DB
            foreach ($validatedData['items'] as $item) {
                $barang = Barang::find($item['barang_id']);
                if (!$barang) {
                    throw new \Exception("Barang dengan ID {$item['barang_id']} tidak ditemukan.");
                }
                if ($item['jumlah'] > $barang->stok) {
                    throw new \Exception("Stok barang {$barang->nama_barang} tidak mencukupi. Sisa stok: {$barang->stok}");
                }
                
                $hargaSatuan = $barang->harga_jual; // Ambil harga jual aktual dari DB
                $subtotal = $hargaSatuan * $item['jumlah'];
                $totalHargaKeseluruhan += $subtotal;

                $detailItemsData[] = [
                    'barang' => $barang, // Simpan instance barang untuk update stok nanti
                    'data' => [
                        'barang_id' => $barang->id,
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $hargaSatuan,
                        'subtotal' => $subtotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ];
            }

            // 2. Tentukan status pembayaran & proses pembayaran
            $statusPembayaran = 'lunas';
            $totalBayarAwal = 0; // Total yang dibayar saat transaksi ini (bisa DP atau lunas)
            $kembalian = 0;
            $simpananSukarelaRecordId = null; // Untuk update keterangan nanti

            if ($validatedData['metode_pembayaran'] == 'tunai') {
                $totalBayarAwal = $validatedData['total_bayar_manual'] ?? 0;
                if ($totalBayarAwal < $totalHargaKeseluruhan) {
                    throw new \Exception('Jumlah pembayaran tunai kurang dari total harga.');
                }
                $kembalian = $totalBayarAwal - $totalHargaKeseluruhan;
            } elseif ($validatedData['metode_pembayaran'] == 'saldo_sukarela') {
                $anggota = User::find($validatedData['user_id']);
                $transaksiTerakhirSukarela = $anggota->simpananSukarelas()->latest('tanggal_transaksi')->latest('created_at')->first();
                $saldoSukarela = $transaksiTerakhirSukarela ? $transaksiTerakhirSukarela->saldo_sesudah : 0;

                if ($totalHargaKeseluruhan > $saldoSukarela) {
                    throw new \Exception("Saldo simpanan sukarela anggota tidak mencukupi. Saldo tersedia: Rp " . number_format($saldoSukarela));
                }
                
                $penarikan = SimpananSukarela::create([
                    'user_id' => $anggota->id,
                    'tipe_transaksi' => 'tarik',
                    'jumlah' => $totalHargaKeseluruhan,
                    'saldo_sebelum' => $saldoSukarela,
                    'saldo_sesudah' => $saldoSukarela - $totalHargaKeseluruhan,
                    'tanggal_transaksi' => Carbon::parse($validatedData['tanggal_pembelian'])->format('Y-m-d'),
                    'pengurus_id' => $kasirId,
                    'keterangan' => 'PENDING_KODE_PEMBELIAN', 
                ]);
                $simpananSukarelaRecordId = $penarikan->id;
                $totalBayarAwal = $totalHargaKeseluruhan; // Dianggap lunas dari saldo
            } elseif ($validatedData['metode_pembayaran'] == 'hutang') {
                $statusPembayaran = 'cicilan'; // Default ke cicilan jika hutang
                $totalBayarAwal = $validatedData['uang_muka'] ?? 0; // Ambil uang muka jika ada
                if ($totalBayarAwal >= $totalHargaKeseluruhan) { // Jika DP >= total, maka lunas
                    $statusPembayaran = 'lunas';
                    $kembalian = $totalBayarAwal - $totalHargaKeseluruhan;
                } elseif ($totalBayarAwal > 0 && $totalBayarAwal < $totalHargaKeseluruhan) {
                    $statusPembayaran = 'cicilan'; // Tetap cicilan jika DP < total
                } else { // Jika tidak ada DP atau DP 0
                    $statusPembayaran = 'cicilan'; // Atau bisa 'belum_lunas' jika ada bedanya
                }
            }
            
            // 3. Buat record Pembelian
            $kodePembelian = 'INV/' . Carbon::parse($validatedData['tanggal_pembelian'])->format('Ymd') . '/' . strtoupper(Str::random(5));
            $pembelian = Pembelian::create([
                'kode_pembelian' => $kodePembelian,
                'user_id' => $validatedData['user_id'],
                'kasir_id' => $kasirId,
                'tanggal_pembelian' => Carbon::parse($validatedData['tanggal_pembelian'])->format('Y-m-d H:i:s'),
                'total_harga' => $totalHargaKeseluruhan,
                'total_bayar' => $totalBayarAwal, // Ini adalah total yang dibayar SAAT transaksi ini
                'kembalian' => $kembalian,
                'status_pembayaran' => $statusPembayaran,
                'metode_pembayaran' => $validatedData['metode_pembayaran'],
                'catatan' => $validatedData['catatan'],
            ]);

            if ($simpananSukarelaRecordId) {
                SimpananSukarela::find($simpananSukarelaRecordId)->update(['keterangan' => "Pembayaran pembelian No. {$kodePembelian}"]);
            }

            // 4. Buat record DetailPembelian dan update stok barang + histori stok
            $detailPembelianRecords = [];
            foreach ($detailItemsData as $itemData) {
                $detail = $itemData['data'];
                $detail['pembelian_id'] = $pembelian->id;
                $detailPembelianRecords[] = $detail;
                
                $barang = $itemData['barang']; // Ambil instance Barang yang sudah di-find
                $stokSebelum = $barang->stok;
                $barang->stok -= $detail['jumlah'];
                $barang->save();

                HistoriStok::create([
                    'barang_id' => $barang->id,
                    'user_id' => $kasirId,
                    'tipe' => 'keluar',
                    'jumlah' => $detail['jumlah'],
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $barang->stok,
                    'keterangan' => "Penjualan No. {$pembelian->kode_pembelian}",
                ]);
            }
            DetailPembelian::insert($detailPembelianRecords); // Batch insert

            DB::commit();
            return redirect()->route('pengurus.transaksi-pembelian.show', $pembelian->id)->with('success', "Transaksi pembelian {$pembelian->kode_pembelian} berhasil dicatat.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error saat menyimpan pembelian: " . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat transaksi pembelian: ' . $e->getMessage());
        }
    }

    public function show(Pembelian $pembelian)
    {
        $pembelian->load(['user:id,name,nomor_anggota', 'kasir:id,name', 'detailPembelians.barang', 'cicilans.pengurus:id,name']);
        
        $sisaTagihan = 0;
        if($pembelian->status_pembayaran !== 'lunas') {
            $totalSudahBayarCicilan = $pembelian->cicilans->sum('jumlah_bayar');
            // Total bayar awal (bisa jadi DP atau 0 jika full hutang)
            $pembayaranAwal = $pembelian->total_bayar; 
            $sisaTagihan = $pembelian->total_harga - $pembayaranAwal - $totalSudahBayarCicilan;
        }

        return view('pengurus.transaksi_pembelian.show', compact('pembelian', 'sisaTagihan'));
    }
}