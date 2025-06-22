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
    }

    /**
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $anggota = User::where('role', 'anggota')
                          ->orderBy('name')
                          ->get(['id', 'name', 'nomor_anggota']);
            
            $barangs = Barang::where('stok', '>', 0)
                            ->orderBy('nama_barang')
                            ->get(['id', 'nama_barang', 'kode_barang', 'harga_jual', 'stok', 'satuan']);
            
            return view('pengurus.transaksi_pembelian.create', compact('anggota', 'barangs'));
            
        } catch (\Exception $e) {
            Log::error('Error in TransaksiPembelianController@create: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat halaman POS: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint untuk mendapatkan saldo sukarela anggota
     */
    public function getSaldoSukarela(Request $request)
    {
        try {
            // Validate CSRF token for AJAX requests
            // if ($request->ajax() && !$request->hasValidSignature()) {
            //     // Check CSRF token manually for AJAX requests
            //     $token = $request->header('X-CSRF-TOKEN') ?: $request->input('_token');
            //     if (!hash_equals(session()->token(), $token)) {
            //         return response()->json([
            //             'success' => false,
            //             'message' => 'CSRF token mismatch',
            //             'reload' => true
            //         ], 419);
            //     }
            // }

            $request->validate([
                'user_id' => 'required|exists:users,id'
            ]);

            $anggota = User::find($request->user_id);
            if (!$anggota || $anggota->role !== 'anggota') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anggota tidak ditemukan'
                ], 404);
            }

            // Ambil saldo terakhir dari simpanan sukarela
            $transaksiTerakhir = $anggota->simpananSukarelas()
                                        ->latest('tanggal_transaksi')
                                        ->latest('created_at')
                                        ->first();
            
            $saldoSukarela = $transaksiTerakhir ? $transaksiTerakhir->saldo_sesudah : 0;

            return response()->json([
                'success' => true,
                'saldo' => $saldoSukarela,
                'saldo_formatted' => 'Rp ' . number_format($saldoSukarela, 0, ',', '.'),
                'anggota' => [
                    'id' => $anggota->id,
                    'name' => $anggota->name,
                    'nomor_anggota' => $anggota->nomor_anggota
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error getting saldo sukarela: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data saldo sukarela',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint untuk validasi stok barang
     */
    public function validateStock(Request $request)
    {
        try {
            // Validate CSRF token for AJAX requests
            // if ($request->ajax()) {
            //     $token = $request->header('X-CSRF-TOKEN') ?: $request->input('_token');
            //     if (!hash_equals(session()->token(), $token)) {
            //         return response()->json([
            //             'success' => false,
            //             'message' => 'CSRF token mismatch',
            //             'reload' => true
            //         ], 419);
            //     }
            // }

            $request->validate([
                'items' => 'required|array',
                'items.*.barang_id' => 'required|exists:barangs,id',
                'items.*.jumlah' => 'required|integer|min:1'
            ]);

            $validationResults = [];
            $allValid = true;

            foreach ($request->items as $item) {
                $barang = Barang::find($item['barang_id']);
                $isValid = $barang && $item['jumlah'] <= $barang->stok;
                
                if (!$isValid) {
                    $allValid = false;
                }

                $validationResults[] = [
                    'barang_id' => $item['barang_id'],
                    'nama_barang' => $barang ? $barang->nama_barang : 'Tidak ditemukan',
                    'jumlah_diminta' => $item['jumlah'],
                    'stok_tersedia' => $barang ? $barang->stok : 0,
                    'valid' => $isValid,
                    'message' => $isValid ? 'Stok mencukupi' : 'Stok tidak mencukupi'
                ];
            }

            return response()->json([
                'success' => true,
                'all_valid' => $allValid,
                'items' => $validationResults
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error validating stock: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memvalidasi stok',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Enhanced validation with better error messages
        try {
            $validatedData = $request->validate([
                'user_id' => ['required', Rule::exists('users', 'id')->where('role', 'anggota')],
                'tanggal_pembelian' => ['required', 'date', 'before_or_equal:today'],
                'metode_pembayaran' => ['required', Rule::in(['tunai', 'saldo_sukarela', 'hutang'])],
                'items' => ['required', 'json'],
                'total_bayar_manual' => ['nullable', 'numeric', 'min:0'],
                'uang_muka' => ['nullable', 'numeric', 'min:0'],
                'catatan' => ['nullable', 'string', 'max:1000'],
            ], [
                'user_id.required' => 'Pilih anggota pembeli',
                'user_id.exists' => 'Anggota yang dipilih tidak valid',
                'tanggal_pembelian.required' => 'Tanggal transaksi harus diisi',
                'tanggal_pembelian.before_or_equal' => 'Tanggal transaksi tidak boleh lebih dari hari ini',
                'metode_pembayaran.required' => 'Pilih metode pembayaran',
                'metode_pembayaran.in' => 'Metode pembayaran tidak valid',
                'items.required' => 'Pilih minimal satu barang',
                'items.json' => 'Format data barang tidak valid',
                'total_bayar_manual.numeric' => 'Jumlah pembayaran harus berupa angka',
                'total_bayar_manual.min' => 'Jumlah pembayaran tidak boleh negatif',
                'uang_muka.numeric' => 'Uang muka harus berupa angka',
                'uang_muka.min' => 'Uang muka tidak boleh negatif',
                'catatan.max' => 'Catatan maksimal 1000 karakter',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                           ->withInput()
                           ->withErrors($e->errors())
                           ->with('error', 'Data yang dimasukkan tidak valid. Silakan periksa kembali.');
        }

        // Parse items JSON
        try {
            $items = json_decode($validatedData['items'], true);
            if (!is_array($items) || empty($items)) {
                throw new \Exception('Data barang tidak valid atau kosong');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Format data barang tidak valid: ' . $e->getMessage());
        }

        $kasirId = Auth::id();
        $totalHargaKeseluruhan = 0;
        $detailItemsData = [];

        DB::beginTransaction();
        try {
            // 1. Validasi stok dan hitung total harga dari DB
            foreach ($items as $item) {
                // Validate item structure
                if (!isset($item['barang_id']) || !isset($item['jumlah']) || !isset($item['harga_satuan'])) {
                    throw new \Exception('Struktur data barang tidak lengkap');
                }

                $barang = Barang::lockForUpdate()->find($item['barang_id']);
                if (!$barang) {
                    throw new \Exception("Barang dengan ID {$item['barang_id']} tidak ditemukan.");
                }
                
                if ($item['jumlah'] <= 0) {
                    throw new \Exception("Jumlah barang {$barang->nama_barang} harus lebih dari 0");
                }
                
                if ($item['jumlah'] > $barang->stok) {
                    throw new \Exception("Stok barang {$barang->nama_barang} tidak mencukupi. Sisa stok: {$barang->stok}, diminta: {$item['jumlah']}");
                }
                
                // Use current price from database, not from frontend
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

            if ($totalHargaKeseluruhan <= 0) {
                throw new \Exception('Total harga transaksi tidak valid');
            }

            // 2. Tentukan status pembayaran & proses pembayaran
            $statusPembayaran = 'lunas';
            $totalBayarAwal = 0;
            $kembalian = 0;
            $simpananSukarelaRecordId = null;

            if ($validatedData['metode_pembayaran'] == 'tunai') {
                $totalBayarAwal = floatval($validatedData['total_bayar_manual'] ?? 0);
                if ($totalBayarAwal < $totalHargaKeseluruhan) {
                    throw new \Exception('Jumlah pembayaran tunai kurang dari total harga. Total: Rp ' . number_format($totalHargaKeseluruhan) . ', Dibayar: Rp ' . number_format($totalBayarAwal));
                }
                $kembalian = $totalBayarAwal - $totalHargaKeseluruhan;
                
            } elseif ($validatedData['metode_pembayaran'] == 'saldo_sukarela') {
                $anggota = User::find($validatedData['user_id']);
                $transaksiTerakhirSukarela = $anggota->simpananSukarelas()
                                                   ->latest('tanggal_transaksi')
                                                   ->latest('created_at')
                                                   ->first();
                $saldoSukarela = $transaksiTerakhirSukarela ? $transaksiTerakhirSukarela->saldo_sesudah : 0;

                if ($totalHargaKeseluruhan > $saldoSukarela) {
                    throw new \Exception("Saldo simpanan sukarela anggota tidak mencukupi. Saldo tersedia: Rp " . number_format($saldoSukarela) . ", Dibutuhkan: Rp " . number_format($totalHargaKeseluruhan));
                }
                
                // Create withdrawal record
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
                $totalBayarAwal = floatval($validatedData['uang_muka'] ?? 0);
                
                if ($totalBayarAwal >= $totalHargaKeseluruhan) {
                    $statusPembayaran = 'lunas';
                    $kembalian = $totalBayarAwal - $totalHargaKeseluruhan;
                } elseif ($totalBayarAwal > 0) {
                    $statusPembayaran = 'cicilan';
                } else {
                    $statusPembayaran = 'belum_lunas';
                }
            }
            
            // 3. Generate unique transaction code
            $kodePembelian = $this->generateUniqueTransactionCode($validatedData['tanggal_pembelian']);
            
            // 4. Create Pembelian record
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

            // Update simpanan sukarela record with transaction code
            if ($simpananSukarelaRecordId) {
                SimpananSukarela::find($simpananSukarelaRecordId)->update([
                    'keterangan' => "Pembayaran pembelian No. {$kodePembelian}"
                ]);
            }

            // 5. Create DetailPembelian records and update stock
            $detailPembelianRecords = [];
            foreach ($detailItemsData as $itemData) {
                $detail = $itemData['data'];
                $detail['pembelian_id'] = $pembelian->id;
                $detailPembelianRecords[] = $detail;
                
                $barang = $itemData['barang'];
                $stokSebelum = $barang->stok;
                $barang->stok -= $detail['jumlah'];
                $barang->save();

                // Create stock history
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
            
            // Bulk insert detail pembelian
            DetailPembelian::insert($detailPembelianRecords);

            DB::commit();
            
            return redirect()->route('pengurus.transaksi-pembelian.show', $pembelian->id)
                           ->with('success', "Transaksi pembelian {$pembelian->kode_pembelian} berhasil dicatat dengan total Rp " . number_format($totalHargaKeseluruhan));
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error saat menyimpan pembelian: " . $e->getMessage() . "\nStack Trace:\n" . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat transaksi pembelian: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique transaction code
     */
    private function generateUniqueTransactionCode($tanggal)
    {
        $datePrefix = Carbon::parse($tanggal)->format('Ymd');
        $attempts = 0;
        $maxAttempts = 10;
        
        do {
            $randomSuffix = strtoupper(Str::random(5));
            $kodePembelian = "INV/{$datePrefix}/{$randomSuffix}";
            $exists = Pembelian::where('kode_pembelian', $kodePembelian)->exists();
            $attempts++;
        } while ($exists && $attempts < $maxAttempts);
        
        if ($exists) {
            // Fallback with timestamp
            $kodePembelian = "INV/{$datePrefix}/" . strtoupper(Str::random(3)) . time();
        }
        
        return $kodePembelian;
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembelian $pembelian)
    {
        $pembelian->load(['user:id,name,nomor_anggota', 'kasir:id,name', 'detailPembelians.barang', 'cicilans.pengurus:id,name']);
        
        $sisaTagihan = 0;
        if($pembelian->status_pembayaran !== 'lunas') {
            $totalSudahBayarCicilan = $pembelian->cicilans->sum('jumlah_bayar');
            $pembayaranAwal = $pembelian->total_bayar; 
            $sisaTagihan = $pembelian->total_harga - $pembayaranAwal - $totalSudahBayarCicilan;
            $sisaTagihan = max(0, $sisaTagihan); // Ensure non-negative
        }

        return view('pengurus.transaksi_pembelian.show', compact('pembelian', 'sisaTagihan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembelian $pembelian)
    {
        // Implementation for edit if needed
        return redirect()->route('pengurus.transaksi-pembelian.show', $pembelian->id)
                        ->with('info', 'Edit transaksi belum tersedia');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembelian $pembelian)
    {
        // Implementation for update if needed
        return redirect()->route('pengurus.transaksi-pembelian.show', $pembelian->id)
                        ->with('info', 'Update transaksi belum tersedia');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembelian $pembelian)
    {
        // Implementation for delete if needed (usually not allowed for transactions)
        return redirect()->route('pengurus.transaksi-pembelian.index')
                        ->with('error', 'Hapus transaksi tidak diizinkan');
    }
}
