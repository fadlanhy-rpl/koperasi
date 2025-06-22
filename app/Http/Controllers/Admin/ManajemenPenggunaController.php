<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManajemenPenggunaController extends Controller
{
    public function __construct()
    {
        // Middleware sudah diterapkan pada level route (di web.php)
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $viewMode = $request->input('view_mode', 'grid'); // Tambahkan view mode
        
        $query = User::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nomor_anggota', 'like', '%' . $searchTerm . '%');
            });
        }

        if ($request->filled('role_filter') && $request->role_filter != 'all') {
            $query->where('role', $request->role_filter);
        }

        if ($request->filled('status_filter') && $request->status_filter != 'all') {
            $query->where('status', $request->status_filter);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        
        $validSortColumns = ['name', 'email', 'created_at', 'role', 'status'];
        if (in_array($sortBy, $validSortColumns)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('name', 'asc');
        }

        $users = $query->paginate($perPage)->appends($request->except('page'));

        $roles = ['admin', 'pengurus', 'anggota'];
        $statuses = ['active', 'inactive'];

        // Statistik global
        $allUsers = User::all();
        $stats = [
            'total' => $allUsers->count(),
            'totalAdmin' => $allUsers->where('role', 'admin')->count(),
            'totalActive' => $allUsers->where('status', 'active')->count(),
            'userBaru' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        if ($request->ajax()) {
            $html = '';
            $gridHtml = '';
            
            // Generate HTML berdasarkan view mode yang diminta
            if ($viewMode === 'table') {
                $html = view('admin.pengguna.partials._user_table_rows', compact('users'))->render();
            } else {
                $gridHtml = view('admin.pengguna.partials._user_grid_items', compact('users'))->render();
            }
            
            // Fix: Gunakan toHtml() instead of render() untuk pagination
            $paginationHtml = $users->appends($request->except('page'))->links('vendor.pagination.tailwind')->toHtml();
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'gridHtml' => $gridHtml,
                'pagination' => $paginationHtml,
                'stats' => $stats,
                'total' => $users->total(),
                'currentPage' => $users->currentPage(),
                'lastPage' => $users->lastPage(),
                'viewMode' => $viewMode
            ]);
        }
        
        return view('admin.pengguna.index', compact('users', 'roles', 'statuses', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rolesForForm = ['admin' => 'Admin', 'pengurus' => 'Pengurus', 'anggota' => 'Anggota'];
        $statusesForForm = ['active' => 'Aktif', 'inactive' => 'Nonaktif'];
        return view('admin.pengguna.create', compact('rolesForForm', 'statusesForForm'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'nomor_anggota' => ['nullable', 'string', 'max:50', 'unique:users,nomor_anggota'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
            'role' => ['required', 'string', Rule::in(['admin', 'pengurus', 'anggota'])],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
        ]);

        try {
            User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'nomor_anggota' => $validatedData['nomor_anggota'],
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'],
                'status' => $validatedData['status'],
                'email_verified_at' => now(),
            ]);

            return redirect()->route('admin.manajemen-pengguna.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error("Gagal menambah pengguna: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pengguna. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.pengguna.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $rolesForForm = ['admin' => 'Admin', 'pengurus' => 'Pengurus', 'anggota' => 'Anggota'];
        $statusesForForm = ['active' => 'Aktif', 'inactive' => 'Nonaktif'];
        return view('admin.pengguna.edit', compact('user', 'rolesForForm', 'statusesForForm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'nomor_anggota' => ['nullable', 'string', 'max:50', Rule::unique('users', 'nomor_anggota')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(['admin', 'pengurus', 'anggota'])],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
            'password' => ['nullable', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        try {
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->nomor_anggota = $validatedData['nomor_anggota'];
            $user->role = $validatedData['role'];
            $user->status = $validatedData['status'];

            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }
            $user->save();

            return redirect()->route('admin.manajemen-pengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Gagal update pengguna #{$user->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data pengguna. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * FIXED: Menggunakan hard delete untuk menghapus data sepenuhnya dari database
     */
    public function destroy(User $user)
    {
        // Cek apakah user mencoba menghapus dirinya sendiri
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.manajemen-pengguna.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        
        // Cek apakah ini admin terakhir
        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
            return redirect()->route('admin.manajemen-pengguna.index')
                ->with('error', 'Tidak dapat menghapus admin aktif terakhir.');
        }

        try {
            // Mulai database transaction untuk memastikan konsistensi data
            DB::beginTransaction();
            
            // Simpan informasi user untuk logging
            $userName = $user->name;
            $userEmail = $user->email;
            $userId = $user->id;
            
            // HARD DELETE: Hapus data sepenuhnya dari database
            // Ini akan menghapus record secara permanen, bukan soft delete
            $deleted = $user->forceDelete();
            
            if (!$deleted) {
                throw new \Exception('Gagal menghapus data user dari database');
            }
            
            // Commit transaction jika berhasil
            DB::commit();
            
            // Log aktivitas penghapusan
            Log::info("User berhasil dihapus - ID: {$userId}, Nama: {$userName}, Email: {$userEmail}", [
                'deleted_by' => Auth::user()->name,
                'deleted_at' => now(),
                'action' => 'hard_delete_user'
            ]);
            
            return redirect()->route('admin.manajemen-pengguna.index')
                ->with('success', "Pengguna '{$userName}' berhasil dihapus secara permanen dari sistem.");
                
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback transaction jika ada error
            DB::rollback();
            
            Log::error("Gagal hapus pengguna (QueryException) #{$user->id}: " . $e->getMessage(), [
                'user_id' => $user->id,
                'error_code' => $e->getCode(),
                'sql_state' => $e->errorInfo[0] ?? null
            ]);
            
            // Handle foreign key constraint errors
            if (str_contains($e->getMessage(), 'foreign key constraint fails') || 
                str_contains($e->getMessage(), 'FOREIGN KEY constraint failed')) {
                return redirect()->route('admin.manajemen-pengguna.index')
                    ->with('error', 'Tidak dapat menghapus pengguna karena masih memiliki data terkait di sistem. Silakan hapus data terkait terlebih dahulu.');
            }
            
            // Handle other database errors
            return redirect()->route('admin.manajemen-pengguna.index')
                ->with('error', 'Gagal menghapus pengguna karena terjadi kesalahan database. Silakan coba lagi atau hubungi administrator.');
                
        } catch (\Exception $e) {
            // Rollback transaction jika ada error
            DB::rollback();
            
            Log::error("Gagal hapus pengguna #{$user->id}: " . $e->getMessage(), [
                'user_id' => $user->id,
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.manajemen-pengguna.index')
                ->with('error', 'Gagal menghapus pengguna. Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Method tambahan untuk membersihkan data user yang sudah soft deleted
     * Bisa dipanggil secara manual jika diperlukan
     */
    public function cleanupSoftDeletedUsers()
    {
        try {
            // Hapus semua user yang sudah di-soft delete
            $deletedCount = User::onlyTrashed()->forceDelete();
            
            Log::info("Cleanup soft deleted users completed", [
                'deleted_count' => $deletedCount,
                'cleaned_by' => Auth::user()->name,
                'cleaned_at' => now()
            ]);
            
            return redirect()->route('admin.manajemen-pengguna.index')
                ->with('success', "Berhasil membersihkan {$deletedCount} data pengguna yang sudah dihapus.");
                
        } catch (\Exception $e) {
            Log::error("Gagal cleanup soft deleted users: " . $e->getMessage());
            
            return redirect()->route('admin.manajemen-pengguna.index')
                ->with('error', 'Gagal membersihkan data. Silakan coba lagi.');
        }
    }
}
