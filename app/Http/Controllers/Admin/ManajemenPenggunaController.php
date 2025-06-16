<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule; // Alias untuk Rule Password
use Illuminate\Support\Facades\Hash; // Jika Anda perlu Hash::make() secara manual
use Illuminate\Support\Facades\Log; // Untuk logging
use Illuminate\Support\Facades\Auth; 


class ManajemenPenggunaController extends Controller
{
    public function __construct()
    {
        // Middleware sudah diterapkan pada level route (di web.php)
    }

    public function index(Request $request)
    {
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

        $users = $query->orderBy('name')->paginate(10)->withQueryString();
        $roles = ['admin', 'pengurus', 'anggota'];
        $statuses = ['active', 'inactive'];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.pengguna.partials._user_table_rows', compact('users'))->render(),
                'pagination' => (string) $users->links('vendor.pagination.tailwind-ajax')
            ]);
        }
        
        return view('admin.pengguna.index', compact('users', 'roles', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengembalikan view Blade untuk form tambah pengguna
        $rolesForForm = ['admin' => 'Admin', 'pengurus' => 'Pengurus', 'anggota' => 'Anggota']; // Untuk select options
        $statusesForForm = ['active' => 'Aktif', 'inactive' => 'Nonaktif']; // Untuk select options
        return view('admin.pengguna.create', compact('rolesForForm', 'statusesForForm'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'nomor_anggota' => ['nullable', 'string', 'max:50', Rule::unique('users', 'nomor_anggota')->whereNull('deleted_at')],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
            'role' => ['required', 'string', Rule::in(['admin', 'pengurus', 'anggota'])],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])], // Tambah validasi status
        ]);

        try {
            User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'nomor_anggota' => $validatedData['nomor_anggota'],
                'password' => $validatedData['password'], // Otomatis hash oleh model User
                'role' => $validatedData['role'],
                'status' => $validatedData['status'], // Simpan status
                'email_verified_at' => now(), // Admin yang membuat, anggap verified
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
    public function show(User $user) // Route model binding
    {
        return view('admin.pengguna.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user) // Route model binding
    {
        // Mengembalikan view Blade untuk form edit pengguna
        $rolesForForm = ['admin' => 'Admin', 'pengurus' => 'Pengurus', 'anggota' => 'Anggota'];
        $statusesForForm = ['active' => 'Aktif', 'inactive' => 'Nonaktif'];
        return view('admin.pengguna.edit', compact('user', 'rolesForForm', 'statusesForForm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user) // Route model binding
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)->whereNull('deleted_at')],
            'nomor_anggota' => ['nullable', 'string', 'max:50', Rule::unique('users', 'nomor_anggota')->ignore($user->id)->whereNull('deleted_at')],
            'role' => ['required', 'string', Rule::in(['admin', 'pengurus', 'anggota'])],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])], // Tambah validasi status
            'password' => ['nullable', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        try {
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->nomor_anggota = $validatedData['nomor_anggota'];
            $user->role = $validatedData['role'];
            $user->status = $validatedData['status']; // Update status

            if (!empty($validatedData['password'])) {
                $user->password = $validatedData['password'];
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
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
             return redirect()->route('admin.manajemen-pengguna.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        if ($user->isAdmin() && User::where('role', 'admin')->whereNull('deleted_at')->count() === 1) { // Cek admin aktif
            return redirect()->route('admin.manajemen-pengguna.index')->with('error', 'Tidak dapat menghapus admin aktif terakhir.');
        }

        try {
            // Jika model User menggunakan SoftDeletes, ini akan melakukan soft delete.
            // Laravel secara otomatis akan menambahkan `deleted_at` dengan timestamp.
            $user->delete(); 
            return redirect()->route('admin.manajemen-pengguna.index')->with('success', 'Pengguna berhasil dihapus (dimasukkan ke arsip).');
        } 
        // QueryException biasanya tidak akan terjadi dengan soft delete kecuali ada masalah fundamental lain.
        // Tapi tetap baik untuk ada sebagai fallback.
        catch (\Illuminate\Database\QueryException $e) { 
            Log::error("Gagal hapus pengguna (QueryException) #{$user->id}: " . $e->getMessage());
            if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                 return redirect()->route('admin.manajemen-pengguna.index')->with('error', 'Tidak dapat menghapus pengguna karena masih memiliki data terkait aktif. Coba non-aktifkan atau arsipkan.');
            }
            return redirect()->route('admin.manajemen-pengguna.index')->with('error', 'Gagal menghapus pengguna. Terjadi kesalahan database.');
        } catch (\Exception $e) {
            Log::error("Gagal hapus pengguna #{$user->id}: " . $e->getMessage());
            return redirect()->route('admin.manajemen-pengguna.index')->with('error', 'Gagal menghapus pengguna. Silakan coba lagi.');
        }
    }
}