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
use Carbon\Carbon;
use Illuminate\Validation\Rule; // Pastikan Rule di-import

class ManajemenSimpananController extends Controller
{
    public function __construct()
    {
        // Middleware sudah diterapkan pada level route group
        // $this->middleware(['auth', 'role:admin,pengurus']);
    }

    // == SIMPANAN POKOK ==

    public function indexPokok(Request $request)
    {
        $query = User::where('role', 'anggota')
                     ->withSum('simpananPokoks as total_simpanan_pokok', 'jumlah')
                     ->withCount('simpananPokoks as jumlah_setoran_pokok');

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
        $anggotaBelumBayarPokok = User::where('role', 'anggota')
                                     ->whereDoesntHave('simpananPokoks') // Asumsi pokok hanya sekali
                                     ->orderBy('name')
                                     ->get(['id', 'name', 'nomor_anggota']);

        return view('pengurus.simpanan.pokok.index', compact('anggotas', 'anggotaBelumBayarPokok'));
    }

    public function storePokok(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', 'anggota')],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'tanggal_bayar' => ['required', 'date_format:Y-m-d'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        // Kebijakan: Simpanan pokok hanya sekali per anggota.
        $existingPokok = SimpananPokok::where('user_id', $validatedData['user_id'])->first();
        if ($existingPokok) {
             return redirect()->back()->withInput()->with('error', 'Anggota tersebut sudah memiliki simpanan pokok.');
        }

        try {
            SimpananPokok::create([
                'user_id' => $validatedData['user_id'],
                'jumlah' => $validatedData['jumlah'],
                'tanggal_bayar' => $validatedData['tanggal_bayar'],
                'pengurus_id' => Auth::id(),
                'keterangan' => $validatedData['keterangan'],
            ]);
            return redirect()->route('pengurus.simpanan.pokok.index')->with('success', 'Simpanan pokok berhasil dicatat.');
        } catch (\Exception $e) {
            // Log error $e->getMessage()
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat simpanan pokok. Silakan coba lagi.');
        }
    }

    // == SIMPANAN WAJIB ==
    public function indexWajib(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

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

        if ($request->filled('status_bayar_wajib')) {
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
        
        // Transformasi data untuk view
        $anggotas->getCollection()->transform(function ($anggota) {
            $anggota->sudah_bayar_wajib_periode_ini = $anggota->simpananWajibs->isNotEmpty();
            $anggota->detail_pembayaran_wajib = $anggota->simpananWajibs->first(); // Ambil detail jika sudah bayar
            return $anggota;
        });
        
        // Ambil semua anggota untuk dropdown di form
        $semuaAnggota = User::where('role', 'anggota')->orderBy('name')->get(['id', 'name', 'nomor_anggota']);

        return view('pengurus.simpanan.wajib.index', compact('anggotas', 'bulan', 'tahun', 'semuaAnggota'));
    }

    public function storeWajib(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', 'anggota')],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'tanggal_bayar' => ['required', 'date_format:Y-m-d'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'digits:4', 'gte:' . (Carbon::now()->year - 5), 'lte:' . (Carbon::now()->year + 1)], // Batasan tahun
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $exists = SimpananWajib::where('user_id', $validatedData['user_id'])
                               ->where('bulan', $validatedData['bulan'])
                               ->where('tahun', $validatedData['tahun'])
                               ->exists();
        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Anggota sudah membayar simpanan wajib untuk periode (bulan/tahun) tersebut.');
        }

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
            return redirect()->route('pengurus.simpanan.wajib.index', ['bulan' => $validatedData['bulan'], 'tahun' => $validatedData['tahun']])
                             ->with('success', 'Simpanan wajib berhasil dicatat.');
        } catch (\Exception $e) {
            // Log error $e->getMessage()
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
                                        ->orderBy('created_at', 'desc')->first(); // Order by created_at sbg tie-breaker
            $anggota->saldo_sukarela_terkini = $transaksiTerakhir ? $transaksiTerakhir->saldo_sesudah : 0;
            return $anggota;
        });
        
        // Ambil semua anggota untuk dropdown di form
        $semuaAnggota = User::where('role', 'anggota')->orderBy('name')->get(['id', 'name', 'nomor_anggota']);

        return view('pengurus.simpanan.sukarela.index', compact('anggotas', 'semuaAnggota'));
    }

    public function storeSetoranSukarela(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', 'anggota')],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'tanggal_transaksi' => ['required', 'date_format:Y-m-d'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $user = User::find($validatedData['user_id']);
            $transaksiTerakhir = SimpananSukarela::where('user_id', $user->id)
                                                ->orderBy('tanggal_transaksi', 'desc')
                                                ->orderBy('created_at', 'desc')
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
            // Log error $e->getMessage()
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat setoran sukarela. Silakan coba lagi.');
        }
    }

    public function storePenarikanSukarela(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', 'anggota')],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'tanggal_transaksi' => ['required', 'date_format:Y-m-d'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $user = User::find($validatedData['user_id']);
            $transaksiTerakhir = SimpananSukarela::where('user_id', $user->id)
                                                ->orderBy('tanggal_transaksi', 'desc')
                                                ->orderBy('created_at', 'desc')
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
            // Log error $e->getMessage()
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat penarikan sukarela. Silakan coba lagi.');
        }
    }

    // == RIWAYAT SIMPANAN PER ANGGOTA ==
    public function riwayatSimpananAnggota(Request $request, User $anggota) // $anggota dari route model binding
    {
        if ($anggota->role !== 'anggota') {
            abort(404, 'Anggota tidak ditemukan.');
        }

        $data = ['anggota' => $anggota];
        
        $data['riwayat_pokok'] = $anggota->simpananPokoks()->with('pengurus:id,name')->orderBy('tanggal_bayar', 'desc')->get();
        $data['total_pokok'] = $data['riwayat_pokok']->sum('jumlah');

        $data['riwayat_wajib'] = $anggota->simpananWajibs()->with('pengurus:id,name')->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->paginate(10, ['*'], 'page_wajib')->withQueryString();
        $data['total_wajib'] = $anggota->simpananWajibs()->sum('jumlah'); // Total keseluruhan, bukan hanya yang dipaginasi
        
        $data['riwayat_sukarela'] = $anggota->simpananSukarelas()->with('pengurus:id,name')->orderBy('tanggal_transaksi', 'desc')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page_sukarela')->withQueryString();
        $transaksiTerakhirSukarela = $anggota->simpananSukarelas()->orderBy('tanggal_transaksi', 'desc')->orderBy('created_at', 'desc')->first();
        $data['saldo_sukarela_terkini'] = $transaksiTerakhirSukarela ? $transaksiTerakhirSukarela->saldo_sesudah : 0;

        // Handling AJAX untuk paginasi di dalam tab (jika desain frontend menggunakannya)
        if ($request->ajax()) {
            $viewHtml = '';
            $paginationHtml = '';

            if ($request->has('page_wajib') || $request->input('tab') === 'wajib') {
                $viewHtml = view('pengurus.simpanan.partials._riwayat_wajib_table', ['riwayat_wajib' => $data['riwayat_wajib'], 'anggota' => $anggota])->render();
                $paginationHtml = (string) $data['riwayat_wajib']->links();
                return response()->json(['html' => $viewHtml, 'pagination' => $paginationHtml, 'tab' => 'wajib']);
            } elseif ($request->has('page_sukarela') || $request->input('tab') === 'sukarela') {
                $viewHtml = view('pengurus.simpanan.partials._riwayat_sukarela_table', ['riwayat_sukarela' => $data['riwayat_sukarela'], 'anggota' => $anggota])->render();
                $paginationHtml = (string) $data['riwayat_sukarela']->links();
                 return response()->json(['html' => $viewHtml, 'pagination' => $paginationHtml, 'tab' => 'sukarela']);
            }
            // Default response jika tidak ada parameter tab/page spesifik di AJAX
            return response()->json(['message' => 'Data tidak ditemukan untuk request AJAX ini.']);
        }

        return view('pengurus.simpanan.riwayat_anggota', $data);
    }
}