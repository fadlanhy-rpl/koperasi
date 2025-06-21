<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display the profile page
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\-\+$$$$\s]*$/'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'date_of_birth.date' => 'Format tanggal lahir tidak valid.',
            'date_of_birth.before' => 'Tanggal lahir harus sebelum hari ini.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.mimes' => 'Foto profil harus berformat JPEG, PNG, atau JPG.',
            'profile_photo.max' => 'Ukuran foto profil maksimal 2MB.',
        ]);

        DB::beginTransaction();
        try {
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $photoPath = $this->handleProfilePhotoUpload($request->file('profile_photo'), $user);
                $validatedData['profile_photo_path'] = $photoPath;
            }

            // Update user data
            $user->update($validatedData);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui.',
                ]);
            }

            return redirect()->route('admin.profile.index')
                ->with('success', 'Profil berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating profile: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui profil.'
                ], 500);
            }

            return redirect()->back()->withInput()
                ->with('error', 'Gagal memperbarui profil.');
        }
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Password saat ini tidak sesuai.');
                }
            }],
            'password' => [
                'required', 
                'string', 
                PasswordRule::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(), 
                'confirmed'
            ],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        DB::beginTransaction();
        try {
            // Update password
            $user->password = Hash::make($validatedData['password']);
            $user->password_changed_at = now();
            $user->save();

            // Log password change
            Log::info('User password changed', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password berhasil diperbarui.',
                ]);
            }

            return redirect()->route('admin.profile.index')
                ->with('success', 'Password berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating password: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui password.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal memperbarui password.');
        }
    }

    /**
     * Handle profile photo upload
     */
    private function handleProfilePhotoUpload($file, $user): string
    {
        try {
            // Delete old photo if exists
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Generate unique filename
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = 'users/profiles/' . $filename;

            // Store the file
            $file->storeAs('users/profiles', $filename, 'public');

            return $path;
        } catch (\Exception $e) {
            Log::error('Error uploading profile photo: ' . $e->getMessage());
            throw new \Exception('Gagal mengupload foto profil.');
        }
    }
}
