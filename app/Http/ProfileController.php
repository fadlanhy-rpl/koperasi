<?php

namespace App\Http\Controllers; // Pastikan namespace ini benar untuk proyek Anda

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule; // Alias jika ada Password model
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller // Pastikan extends Controller yang benar
{
    public function __construct()
    {
        // Jika controller ini untuk semua user yang login, cukup auth
        // Jika spesifik untuk anggota, tambahkan middleware role
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        // Logika untuk 'anggota.profil.show' jika ini controller untuk anggota
        // atau 'admin.settings.index' dengan section profil jika untuk admin
        // Sesuaikan dengan nama view yang benar
        return view('profile.show', compact('user')); // Ganti 'profile.show' dengan path view yang benar
    }

    public function edit()
    {
        $user = Auth::user();
        // Sesuaikan dengan nama view yang benar
        return view('profile.edit', compact('user')); // Ganti 'profile.edit' dengan path view yang benar
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'profile_image' => ['nullable', 'string'], // Menerima base64 string dari input hidden 'cropped_image_data'
        ]);

        $profileDataToUpdate = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
        ];

        if ($request->filled('profile_image') && Str::startsWith($request->profile_image, 'data:image')) {
            // Hapus foto lama jika ada
            if ($user->profile_image_path && Storage::disk('public')->exists($user->profile_image_path)) {
                Storage::disk('public')->delete($user->profile_image_path);
            }

            $imageData = $request->input('profile_image'); // Ini adalah base64 dari Cropper.js

            // Ekstrak tipe gambar dan data base64
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $imageType = strtolower($type[1]); // jpg, png, gif, webp

                if (!in_array($imageType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return back()->withErrors(['profile_image' => 'Tipe gambar tidak valid. Hanya JPG, PNG, GIF, WEBP.'])->withInput();
                }
                $decodedImageData = base64_decode($imageData);
                if ($decodedImageData === false) {
                    return back()->withErrors(['profile_image' => 'Gagal decode data gambar base64.'])->withInput();
                }
            } else {
                return back()->withErrors(['profile_image' => 'Format data URI gambar tidak valid.'])->withInput();
            }

            $fileName = 'profile-photos/' . $user->id . '_' . time() . '.' . $imageType; // Folder di storage/app/public/
            Storage::disk('public')->put($fileName, $decodedImageData);
            $profileDataToUpdate['profile_image_path'] = $fileName;
        }

        $user->update($profileDataToUpdate);

        // Tentukan route redirect berdasarkan peran pengguna atau konteks
        $redirectRoute = 'profile.show'; // Default
        if ($user->isAdmin()) {
            // $redirectRoute = 'admin.settings.index'; // dengan parameter section
        } elseif ($user->isAnggota()) {
             // $redirectRoute = 'anggota.profil.show'; // Jika ada route ini
        }
        // Atau selalu redirect ke profile.show jika itu halaman profil umum
        return redirect()->route($redirectRoute)->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Password saat ini yang Anda masukkan salah.');
                }
            }],
            'password' => ['required', 'string', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $user->update([
            'password' => $request->password, // Hashing otomatis oleh model User
        ]);
        
        // Tentukan route redirect
        $redirectRoute = 'profile.edit'; // Default
         if ($user->isAdmin()) {
            // $redirectRoute = 'admin.settings.index'; // dengan parameter section
        } elseif ($user->isAnggota()) {
             // $redirectRoute = 'anggota.profil.edit'; // Jika ada route ini
        }
        return redirect()->route($redirectRoute)->with('success', 'Password berhasil diperbarui.');
    }

    // Method untuk menghapus foto profil (jika ada tombolnya)
    public function deleteProfilePhoto(Request $request)
    {
        $user = Auth::user();
        if ($user->profile_image_path) {
            Storage::disk('public')->delete($user->profile_image_path);
            $user->profile_image_path = null;
            $user->save();
            return back()->with('success', 'Foto profil berhasil dihapus.');
        }
        return back()->with('info', 'Tidak ada foto profil untuk dihapus.');
    }
}