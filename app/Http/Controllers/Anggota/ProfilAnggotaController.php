<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\User; // Model User koperasi kita
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
// Carbon tidak perlu diimport di sini jika sudah ada di model User untuk accessor 'age'
// dan casting 'date' pada 'date_of_birth'

class ProfilAnggotaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:anggota']);
    }

    public function showProfilSaya()
    {
        $user = Auth::user();
        return view('anggota.profil.show', compact('user'));
    }

    public function editProfilSaya()
    {
        $user = Auth::user();
        return view('anggota.profil.edit', compact('user'));
    }

    public function updateProfilSaya(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'], // Validasi untuk tanggal lahir
            'cropped_profile_photo' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (!empty($value) && !Str::startsWith($value, 'data:image')) {
                    return $fail('Format data foto profil tidak valid.');
                }
                if (!empty($value) && Str::startsWith($value, 'data:image')) {
                    $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $value);
                    if ((strlen($base64Data) * 0.75) > (2 * 1024 * 1024)) { // Max 2MB
                         return $fail('Ukuran file foto terlalu besar (Maksimal 2MB).');
                    }
                }
            }],
        ],[
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan.',
            'date_of_birth.date' => 'Format tanggal lahir tidak valid.',
            'date_of_birth.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
        ]);
        
        DB::beginTransaction();
        try {
            $updateData = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'date_of_birth' => $validatedData['date_of_birth'] ?? null, // Simpan tanggal lahir
            ];

            if ($request->filled('cropped_profile_photo') && Str::startsWith($request->input('cropped_profile_photo'), 'data:image')) {
                if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $imageData = $request->input('cropped_profile_photo');
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $fileExtension = strtolower($type[1]);
                    if (!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                        DB::rollBack(); return redirect()->back()->withInput()->with('error', 'Tipe file gambar tidak valid.');
                    }
                    $imageData = base64_decode($imageData);
                    if ($imageData === false) {
                        DB::rollBack(); return redirect()->back()->withInput()->with('error', 'Gagal decode data gambar base64.');
                    }
                } else {
                    DB::rollBack(); return redirect()->back()->withInput()->with('error', 'Format data URI gambar tidak valid.');
                }
                $directory = 'profile-photos';
                if (!Storage::disk('public')->exists($directory)) Storage::disk('public')->makeDirectory($directory);
                $fileName = $directory . '/' . $user->id . '_' . time() . '_' . Str::random(10) . '.' . $fileExtension;
                Storage::disk('public')->put($fileName, $imageData);
                $updateData['profile_photo_path'] = $fileName;
            }

            $user->update($updateData);
            DB::commit();
            return redirect()->route('anggota.profil.show')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal update profil anggota #{$user->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function updatePasswordSaya(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) { $fail('Password saat ini yang Anda masukkan salah.'); }
            }],
            'password' => ['required', 'string', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
        ]);
        try {
            $user->update(['password' => $request->password]);
            return redirect()->route('anggota.profil.edit')->with('success', 'Password berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Gagal update password anggota #{$user->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui password.');
        }
    }

    public function deleteProfilePhoto()
    {
        $user = Auth::user();
        if ($user->profile_photo_path) {
            DB::beginTransaction();
            try {
                if (Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $user->profile_photo_path = null;
                $user->save();
                DB::commit();
                return redirect()->route('anggota.profil.edit')->with('success', 'Foto profil berhasil dihapus.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Gagal hapus foto profil untuk user #{$user->id}: " . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal menghapus foto profil.');
            }
        }
        return redirect()->route('anggota.profil.edit')->with('info', 'Tidak ada foto profil untuk dihapus.');
    }
}