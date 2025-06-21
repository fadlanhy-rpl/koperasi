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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

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
        try {
            $query = Pembelian::with(['user:id,name,nomor_anggota', 'kasir:id,name'])
                              ->orderBy('tanggal_pembelian', 'desc');

            // Apply search filter
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

            // Apply status filter
            if ($request->filled('status_pembayaran') && $request->status_pembayaran != 'all') {
                $query->where('status_pembayaran', $request->status_pembayaran);
            }

            // Apply date filters
            if ($request->filled('tanggal_mulai')) {
                $query->whereDate('tanggal_pembelian', '>=', $request->tanggal_mulai);
            }
            if ($request->filled('tanggal_selesai')) {
                $query->whereDate('tanggal_pembelian', '<=', $request->tanggal_selesai);
            }

            $pembelians = $query->paginate(15)->withQueryString();
            
            $statuses = [
                'all' => 'Semua Status',
                'lunas' => 'Lunas', 
                'belum_lunas' => 'Belum Lunas', 
                'cicilan' => 'Cicilan'
            ];

            // Handle AJAX requests
            if ($request->ajax()) {
                try {
                    $html = view('pengurus.transaksi_pembelian.partials._transaksi_table_rows', compact('pembelians'))->render();
                    $pagination = $pembelians->hasPages() ? $pembelians->links('vendor.pagination.tailwind')->render() : '';
                    
                    return response()->json([
                        'success' => true,
                        'html' => $html,
                        'pagination' => $pagination,
                        'total' => $pembelians->total(),
                        'page_info' => $pembelians->currentPage() . '/' . $pembelians->lastPage()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error rendering AJAX response: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal memuat data transaksi',
                        'error' => $e->getMessage()
                    ], 500);
                }
            }

            return view('pengurus.transaksi_pembelian.index', compact('pembelians', 'statuses'));
            
        } catch (\Exception $e) {
            Log::error('Error in TransaksiPembelianController@index: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat data transaksi',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal memuat data transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk membuat transaksi pembelian baru (Point of Sale).
     */
    public function create()
    {
        $anggota = User::where('role', 'anggota')->orderBy('name')->get(['id', 'name', 'nomor_anggota']);
        $barangs = Barang::where('stok', '>', 0)
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
            'tanggal_pembelian' => ['required', 'date'],
            'metode_pembayaran' => ['required', Rule::in(['tunai', 'saldo_sukarela', 'hutang'])],
            'items' => ['required', 'array', 'min:1'],
            'items.*.barang_id' => ['required', 'exists:barangs,id'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
            'total_bayar_manual' => ['nullable', 'numeric', 'min:0'],
            'uang_muka' => ['nullable', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $kasirId = Auth::id();
        $totalHargaKeseluruhan = 0;
        $detailItemsData = [];

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
                
                $hargaSatuan = $barang->harga_jual;
                $subtotal = $hargaSatuan * $item['jumlah'];
                $totalHargaKeseluruhan += $subtotal;

                $detailItemsData[] = [
                    'barang' => $barang,
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
            $totalBayarAwal = 0;
            $kembalian = 0;
            $simpananSukarelaRecordId = null;

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
                $totalBayarAwal = $totalHargaKeseluruhan;
            } elseif ($validatedData['metode_pembayaran'] == 'hutang') {
                $statusPembayaran = 'cicilan';
                $totalBayarAwal = $validatedData['uang_muka'] ?? 0;
                if ($totalBayarAwal >= $totalHargaKeseluruhan) {
                    $statusPembayaran = 'lunas';
                    $kembalian = $totalBayarAwal - $totalHargaKeseluruhan;
                } elseif ($totalBayarAwal > 0 && $totalBayarAwal < $totalHargaKeseluruhan) {
                    $statusPembayaran = 'cicilan';
                } else {
                    $statusPembayaran = 'cicilan';
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
                'total_bayar' => $totalBayarAwal,
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
                
                $barang = $itemData['barang'];
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
            DetailPembelian::insert($detailPembelianRecords);

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
            $pembayaranAwal = $pembelian->total_bayar; 
            $sisaTagihan = $pembelian->total_harga - $pembayaranAwal - $totalSudahBayarCicilan;
        }

        return view('pengurus.transaksi_pembelian.show', compact('pembelian', 'sisaTagihan'));
    }
}
