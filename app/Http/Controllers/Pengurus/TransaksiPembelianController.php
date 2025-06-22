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
        // 1. Validasi input dengan field yang benar
        $validated = $request->validate([
            'anggota_id' => 'required|exists:users,id',
            'tanggal_pembelian' => 'required|date',
            'metode_pembayaran' => 'required|in:tunai,saldo_sukarela,hutang',
            'barangs' => 'required|array|min:1',
            'barangs.*.id' => 'required|exists:barangs,id',
            'barangs.*.jumlah' => 'required|integer|min:1',
            'total_bayar_manual' => 'nullable|numeric|min:0',
            'uang_muka' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $totalHarga = 0;
            $detailsToInsert = [];
        
            // Ambil semua barang yang diminta dalam satu query untuk efisiensi
            $requestedBarangs = collect($validated['barangs']);
            $barangIds = $requestedBarangs->pluck('id');
            $barangsFromDB = Barang::whereIn('id', $barangIds)->lockForUpdate()->get()->keyBy('id');

            // 2. Kalkulasi total harga dan periksa stok
            foreach ($requestedBarangs as $barang) {
                $item = $barangsFromDB->get($barang['id']);
            
                if (!$item) {
                    throw new \Exception("Barang dengan ID {$barang['id']} tidak ditemukan.");
                }
            
                if ($item->stok < $barang['jumlah']) {
                    throw new \Exception("Stok untuk barang '{$item->nama_barang}' tidak mencukupi. Sisa stok: {$item->stok}.");
                }

                // Gunakan bcmath untuk kalkulasi presisi
                $subTotal = bcmul($item->harga_jual, $barang['jumlah'], 2);
                $totalHarga = bcadd($totalHarga, $subTotal, 2);

                // Siapkan data untuk detail pembelian
                $detailsToInsert[] = [
                    'barang_id' => $item->id,
                    'jumlah' => $barang['jumlah'],
                    'harga_satuan' => $item->harga_jual,
                    'subtotal' => $subTotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            
                // Kurangi stok pada object, akan di-save nanti
                $item->stok -= $barang['jumlah'];
            }

            // Generate kode pembelian
            $kodePembelian = $this->generateUniqueTransactionCode($validated['tanggal_pembelian']);

            // Tentukan status pembayaran dan total bayar
            $statusPembayaran = 'belum_lunas';
            $totalBayar = 0;
            $kembalian = 0;

            if ($validated['metode_pembayaran'] === 'tunai') {
                $totalBayar = $validated['total_bayar_manual'] ?? 0;
                if ($totalBayar >= $totalHarga) {
                    $statusPembayaran = 'lunas';
                    $kembalian = $totalBayar - $totalHarga;
                }
            } elseif ($validated['metode_pembayaran'] === 'saldo_sukarela') {
                // Cek saldo sukarela
                $anggota = User::find($validated['anggota_id']);
                $transaksiTerakhir = $anggota->simpananSukarelas()
                                            ->latest('tanggal_transaksi')
                                            ->latest('created_at')
                                            ->first();
            
                $saldoSukarela = $transaksiTerakhir ? $transaksiTerakhir->saldo_sesudah : 0;
            
                if ($saldoSukarela >= $totalHarga) {
                    $statusPembayaran = 'lunas';
                    $totalBayar = $totalHarga;
                
                    // Kurangi saldo sukarela
                    SimpananSukarela::create([
                        'user_id' => $anggota->id,
                        'tipe_transaksi' => 'tarik',
                        'jumlah' => $totalHarga,
                        'saldo_sebelum' => $saldoSukarela,
                        'saldo_sesudah' => $saldoSukarela - $totalHarga,
                        'tanggal_transaksi' => $validated['tanggal_pembelian'],
                        'pengurus_id' => Auth::id(),
                        'keterangan' => "Pembayaran transaksi {$kodePembelian}",
                    ]);
                } else {
                    throw new \Exception("Saldo sukarela tidak mencukupi. Saldo tersedia: Rp " . number_format($saldoSukarela, 0, ',', '.'));
                }
            } elseif ($validated['metode_pembayaran'] === 'hutang') {
                $statusPembayaran = 'cicilan';
                $totalBayar = $validated['uang_muka'] ?? 0;
            }

            // 3. Buat entri pembelian
            $pembelian = Pembelian::create([
                'kode_pembelian' => $kodePembelian,
                'user_id' => $validated['anggota_id'],
                'kasir_id' => Auth::id(),
                'tanggal_pembelian' => $validated['tanggal_pembelian'],
                'total_harga' => $totalHarga,
                'total_bayar' => $totalBayar,
                'kembalian' => $kembalian,
                'status_pembayaran' => $statusPembayaran,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            // Tambahkan pembelian_id ke setiap detail
            $detailsWithPembelianId = array_map(function ($detail) use ($pembelian) {
                $detail['pembelian_id'] = $pembelian->id;
                return $detail;
            }, $detailsToInsert);

            // 4. Insert semua detail pembelian dalam satu query
            DetailPembelian::insert($detailsWithPembelianId);

            // 5. Update stok semua barang yang berubah
            foreach ($barangsFromDB as $item) {
                $item->save();
            }

            DB::commit();
            return redirect()->route('pengurus.transaksi-pembelian.index')->with('success', 'Transaksi berhasil ditambahkan dengan kode: ' . $kodePembelian);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating transaction: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage())->withInput();
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
