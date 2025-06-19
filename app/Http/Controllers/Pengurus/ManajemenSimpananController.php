<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SimpananPokok;
use App\Models\SimpananWajib;
use App\Models\SimpananSukarela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; // Menggunakan Illuminate\Support\Carbon
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ManajemenSimpananController extends Controller
{
    public function __construct()
    {
        // Middleware sudah diterapkan pada level route group
    }

    // == SIMPANAN POKOK ==
    public function indexPokok(Request $request)
    {
        $query = User::where('role', 'anggota')
                     ->withSum('simpananPokoks as total_simpanan_pokok', 'jumlah')
                     ->withCount('simpananPokoks as jumlah_setoran_pokok'); // Untuk mengetahui apakah sudah bayar

        if ($request->filled('search_anggota')) {
            $searchTerm = $request->search_anggota;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $searchTerm . '%');
            });
        }
        
        if ($request->filled('status_bayar_pokok')) {
            if ($request->status_bayar_pokok == 'sudah') {
                $query->whereHas('simpananPokoks');
            } elseif ($request->status_bayar_pokok == 'belum') {
                $query->whereDoesntHave('simpananPokoks');
            }
        }

        $anggotas = $query->orderBy('name')->paginate(15)->withQueryString();
        
        // Ambil daftar anggota yang belum punya simpanan pokok untuk dropdown di form
        // Dengan asumsi simpanan pokok hanya sekali per anggota
        $anggotaBelumBayarPokok = User::where('role', 'anggota')
                                     ->whereDoesntHave('simpananPokoks')
                                     ->orderBy('name')
                                     ->get(); // Ambil semua field untuk accessor jika digunakan

        return view('pengurus.simpanan.pokok.index', compact('anggotas', 'anggotaBelumBayarPokok'));
    }

    public function storePokok(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', 'anggota')],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'tanggal_bayar' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        // Kebijakan: Simpanan pokok hanya sekali per anggota.
        $existingPokok = SimpananPokok::where('user_id', $validatedData['user_id'])->first();
        if ($existingPokok) { // Anda bisa menambahkan config untuk ini jika kebijakan bisa berubah
             return redirect()->back()->withInput()->with('error', 'Anggota (' . User::find($validatedData['user_id'])->name . ') sudah memiliki simpanan pokok.');
        }

        DB::beginTransaction();
        try {
            SimpananPokok::create([
                'user_id' => $validatedData['user_id'],
                'jumlah' => $validatedData['jumlah'],
                'tanggal_bayar' => $validatedData['tanggal_bayar'],
                'pengurus_id' => Auth::id(),
                'keterangan' => $validatedData['keterangan'],
            ]);
            DB::commit();
            return redirect()->route('pengurus.simpanan.pokok.index')->with('success', 'Simpanan pokok berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal mencatat simpanan pokok: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat simpanan pokok. Silakan coba lagi.');
        }
    }

    // == SIMPANAN WAJIB ==
    public function indexWajib(Request $request)
    {
        $bulan = (int) $request->input('bulan', Carbon::now()->month);
        $tahun = (int) $request->input('tahun', Carbon::now()->year);

        if ($bulan < 1 || $bulan > 12) $bulan = Carbon::now()->month;
        if ($tahun < (Carbon::now()->year - 10) || $tahun > (Carbon::now()->year + 2)) $tahun = Carbon::now()->year;

        $anggotaQuery = User::where('role', 'anggota')
                            ->with(['simpananWajibs' => function($query) use ($bulan, $tahun) {
                                $query->where('bulan', $bulan)->where('tahun', $tahun);
                            }]);

        if ($request->filled('search_anggota')) {
            $searchTerm = $request->search_anggota;
             $anggotaQuery->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $searchTerm . '%');
            });
        }
        if ($request->filled('status_bayar_wajib') && $request->status_bayar_wajib !== 'all') {
            if ($request->status_bayar_wajib == 'sudah') {
                $anggotaQuery->whereHas('simpananWajibs', function ($query) use ($bulan, $tahun) {
                    $query->where('bulan', $bulan)->where('tahun', $tahun);
                });
            } elseif ($request->status_bayar_wajib == 'belum') {
                $anggotaQuery->whereDoesntHave('simpananWajibs', function ($query) use ($bulan, $tahun) {
                    $query->where('bulan', $bulan)->where('tahun', $tahun);
                });
            }
        }

        $anggotas = $anggotaQuery->orderBy('name')->paginate(15)->withQueryString();
        
        $anggotas->getCollection()->transform(function ($anggota) {
            $anggota->sudah_bayar_wajib_periode_ini = $anggota->simpananWajibs->isNotEmpty();
            $anggota->detail_pembayaran_wajib = $anggota->simpananWajibs->first();
            return $anggota;
        });
        
        $semuaAnggota = User::where('role', 'anggota')->orderBy('name')->get();

        return view('pengurus.simpanan.wajib.index', compact('anggotas', 'bulan', 'tahun', 'semuaAnggota'));
    }

    public function storeWajib(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', 'anggota')],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'tanggal_bayar' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'digits:4', 'gte:' . (Carbon::now()->year - 5), 'lte:' . (Carbon::now()->year + 1)],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $exists = SimpananWajib::where('user_id', $validatedData['user_id'])
                               ->where('bulan', $validatedData['bulan'])
                               ->where('tahun', $validatedData['tahun'])
                               ->exists();
        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Anggota sudah membayar simpanan wajib untuk periode (bulan/tahun) tersebut.');
        }

        DB::beginTransaction();
        try {
            SimpananWajib::create([
                'user_id' => $validatedData['user_id'],
                'jumlah' => $validatedData['jumlah'],
                'tanggal_bayar' => $validatedData['tanggal_bayar'],
                'bulan' => $validatedData['bulan'],
                'tahun' => $validatedData['tahun'],
                'pengurus_id' => Auth::id(),
                'keterangan' => $validatedData['keterangan'],
            ]);
            DB::commit();
            return redirect()->route('pengurus.simpanan.wajib.index', ['bulan' => $validatedData['bulan'], 'tahun' => $validatedData['tahun']])
                             ->with('success', 'Simpanan wajib berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal mencatat simpanan wajib: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat simpanan wajib. Silakan coba lagi.');
        }
    }

    // == SIMPANAN SUKARELA ==
    public function indexSukarela(Request $request)
    {
        $anggotaQuery = User::where('role', 'anggota');

        if ($request->filled('search_anggota')) {
             $searchTerm = $request->search_anggota;
             $anggotaQuery->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $searchTerm . '%');
            });
        }
        $anggotas = $anggotaQuery->orderBy('name')->paginate(15)->withQueryString();
        
        $anggotas->getCollection()->transform(function ($anggota) {
            $transaksiTerakhir = $anggota->simpananSukarelas()
                                        ->orderBy('tanggal_transaksi', 'desc')
                                        ->orderBy('created_at', 'desc')->orderBy('id', 'desc')->first();
            $anggota->saldo_sukarela_terkini = $transaksiTerakhir ? $transaksiTerakhir->saldo_sesudah : 0;
            return $anggota;
        });
        
        $semuaAnggota = User::where('role', 'anggota')->orderBy('name')->get();

        return view('pengurus.simpanan.sukarela.index', compact('anggotas', 'semuaAnggota'));
    }

    public function storeSetoranSukarela(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', 'anggota')],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'tanggal_transaksi' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $user = User::find($validatedData['user_id']);
            $transaksiTerakhir = SimpananSukarela::where('user_id', $user->id)
                                                ->orderBy('tanggal_transaksi', 'desc')
                                                ->orderBy('created_at', 'desc')->orderBy('id', 'desc')
                                                ->first();
            $saldoSebelum = $transaksiTerakhir ? $transaksiTerakhir->saldo_sesudah : 0;
            $saldoSesudah = $saldoSebelum + $validatedData['jumlah'];

            SimpananSukarela::create([
                'user_id' => $user->id,
                'tipe_transaksi' => 'setor',
                'jumlah' => $validatedData['jumlah'],
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSesudah,
                'tanggal_transaksi' => $validatedData['tanggal_transaksi'],
                'pengurus_id' => Auth::id(),
                'keterangan' => $validatedData['keterangan'],
            ]);

            DB::commit();
            return redirect()->route('pengurus.simpanan.sukarela.index')->with('success', 'Setoran simpanan sukarela berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal mencatat setoran sukarela: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat setoran sukarela. Silakan coba lagi.');
        }
    }

    public function storePenarikanSukarela(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', 'anggota')],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'tanggal_transaksi' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $user = User::find($validatedData['user_id']);
            $transaksiTerakhir = SimpananSukarela::where('user_id', $user->id)
                                                ->orderBy('tanggal_transaksi', 'desc')
                                                ->orderBy('created_at', 'desc')->orderBy('id', 'desc')
                                                ->first();
            $saldoSebelum = $transaksiTerakhir ? $transaksiTerakhir->saldo_sesudah : 0;

            if ($validatedData['jumlah'] > $saldoSebelum) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('error', 'Jumlah penarikan (' . number_format($validatedData['jumlah']) . ') melebihi saldo sukarela yang tersedia (' . number_format($saldoSebelum) . ').');
            }

            $saldoSesudah = $saldoSebelum - $validatedData['jumlah'];

            SimpananSukarela::create([
                'user_id' => $user->id,
                'tipe_transaksi' => 'tarik',
                'jumlah' => $validatedData['jumlah'],
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSesudah,
                'tanggal_transaksi' => $validatedData['tanggal_transaksi'],
                'pengurus_id' => Auth::id(),
                'keterangan' => $validatedData['keterangan'],
            ]);

            DB::commit();
            return redirect()->route('pengurus.simpanan.sukarela.index')->with('success', 'Penarikan simpanan sukarela berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal mencatat penarikan sukarela: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat penarikan sukarela. Silakan coba lagi.');
        }
    }

    // == RIWAYAT SIMPANAN PER ANGGOTA ==
    public function riwayatSimpananAnggota(Request $request, User $anggota)
    {
        if ($anggota->role !== 'anggota') {
            abort(404, 'Anggota tidak ditemukan.');
        }

        $data = ['anggota' => $anggota];
        
        $data['riwayat_pokok'] = $anggota->simpananPokoks()->with('pengurus:id,name')->orderBy('tanggal_bayar', 'desc')->get();
        $data['total_pokok'] = $data['riwayat_pokok']->sum('jumlah');

        $riwayat_wajib = $anggota->simpananWajibs()->with('pengurus:id,name')->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->paginate(10, ['*'], 'page_wajib')->withQueryString();
        $data['riwayat_wajib'] = $riwayat_wajib;
        $data['total_wajib'] = $anggota->simpananWajibs()->sum('jumlah');
        
        $riwayat_sukarela = $anggota->simpananSukarelas()->with('pengurus:id,name')->orderBy('tanggal_transaksi', 'desc')->orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate(10, ['*'], 'page_sukarela')->withQueryString();
        $data['riwayat_sukarela'] = $riwayat_sukarela;
        $transaksiTerakhirSukarela = $anggota->simpananSukarelas()->orderBy('tanggal_transaksi', 'desc')->orderBy('created_at', 'desc')->orderBy('id', 'desc')->first();
        $data['saldo_sukarela_terkini'] = $transaksiTerakhirSukarela ? $transaksiTerakhirSukarela->saldo_sesudah : 0;

        if ($request->ajax()) {
            $viewHtml = ''; $paginationHtml = ''; $tab = '';
            if ($request->has('page_wajib')) $tab = 'wajib';
            elseif ($request->has('page_sukarela')) $tab = 'sukarela';
            else $tab = $request->input('tab');

            if ($tab === 'wajib') {
                $viewHtml = view('pengurus.simpanan.partials._riwayat_wajib_table', ['riwayat_wajib' => $data['riwayat_wajib'], 'anggota' => $anggota])->render();
                $paginationHtml = (string) $data['riwayat_wajib']->links('vendor.pagination.tailwind-ajax');
            } elseif ($tab === 'sukarela') {
                $viewHtml = view('pengurus.simpanan.partials._riwayat_sukarela_table', ['riwayat_sukarela' => $data['riwayat_sukarela'], 'anggota' => $anggota])->render();
                $paginationHtml = (string) $data['riwayat_sukarela']->links('vendor.pagination.tailwind-ajax');
            } else {
                return response()->json(['message' => 'Tab tidak valid untuk request AJAX.'], 400);
            }
            return response()->json(['html' => $viewHtml, 'pagination' => $paginationHtml, 'tab' => $tab]);
        }

        return view('pengurus.simpanan.riwayat_anggota', $data);
    }
}