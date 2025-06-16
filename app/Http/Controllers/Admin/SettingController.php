<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth; // Penting untuk Auth::user()
use Illuminate\Support\Facades\Hash; // Penting untuk Hash::check()
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule; // Alias agar tidak bentrok nama class Password

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        $adminUser = Auth::user(); // Ambil data admin yang login untuk form profil

        $currentSettings = [
            'theme_preference' => $settings->get('theme_preference', 'system'),
            'sidebar_transparent' => filter_var($settings->get('sidebar_transparent', '1'), FILTER_VALIDATE_BOOLEAN),
            'sidebar_feature' => $settings->get('sidebar_feature', 'menu_lengkap'),
            'table_view' => $settings->get('table_view', 'default'),
            'koperasi_nama' => $settings->get('koperasi_nama', config('app.name', 'Koperasi Sekolah')),
            'koperasi_alamat' => $settings->get('koperasi_alamat', ''),
            'koperasi_telepon' => $settings->get('koperasi_telepon', ''),
            'koperasi_email' => $settings->get('koperasi_email', ''),
            'default_simpanan_pokok' => $settings->get('default_simpanan_pokok', 100000),
            'default_simpanan_wajib' => $settings->get('default_simpanan_wajib', 25000),
        ];

        return view('admin.settings.index', compact('currentSettings', 'adminUser'));
    }

    public function updateGeneral(Request $request)
    {
        $validatedData = $request->validate([
            'koperasi_nama' => ['required', 'string', 'max:255'],
            'koperasi_alamat' => ['nullable', 'string', 'max:500'],
            'koperasi_telepon' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\-\+\(\)\s]*$/'],
            'koperasi_email' => ['nullable', 'email', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            foreach ($validatedData as $key => $value) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
            }
            DB::commit();
            Artisan::call('config:clear');
            return redirect()->route('admin.settings.index', ['section' => 'profil_koperasi'])->with('success', 'Pengaturan umum koperasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating general settings: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengaturan umum.');
        }
    }
    
    public function updateSimpananDefaults(Request $request)
    {
        $validatedData = $request->validate([
            'default_simpanan_pokok' => ['required', 'numeric', 'min:0'],
            'default_simpanan_wajib' => ['required', 'numeric', 'min:0'],
        ]);

        DB::beginTransaction();
        try {
            foreach ($validatedData as $key => $value) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
            DB::commit();
            return redirect()->route('admin.settings.index', ['section' => 'simpanan'])->with('success', 'Pengaturan default simpanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating default simpanan settings: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengaturan default simpanan.');
        }
    }

    public function updateAppearance(Request $request)
    {
        $validatedData = $request->validate([
            'theme_preference' => ['required', Rule::in(['system', 'light', 'dark'])],
            'sidebar_feature' => ['required', Rule::in(['perubahan_terbaru', 'menu_lengkap', 'menu_ringkas'])],
            'table_view' => ['required', Rule::in(['default', 'compact'])],
        ]);
        
        $sidebarTransparentValue = $request->has('sidebar_transparent') ? '1' : '0';

        DB::beginTransaction();
        try {
            Setting::updateOrCreate(['key' => 'theme_preference'], ['value' => $validatedData['theme_preference']]);
            Setting::updateOrCreate(['key' => 'sidebar_transparent'], ['value' => $sidebarTransparentValue]);
            Setting::updateOrCreate(['key' => 'sidebar_feature'], ['value' => $validatedData['sidebar_feature']]);
            Setting::updateOrCreate(['key' => 'table_view'], ['value' => $validatedData['table_view']]);
            
            DB::commit();
            return redirect()->route('admin.settings.index', ['section' => 'appearance'])->with('success', 'Pengaturan tampilan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating appearance settings: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengaturan tampilan.');
        }
    }

    // Method untuk Profil Admin yang Login
    public function updateMyProfile(Request $request)
    {
        $admin = Auth::user();
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
        ]);

        try {
            $admin->name = $validatedData['name'];
            $admin->email = $validatedData['email'];
            $admin->save();
            return redirect()->route('admin.settings.index', ['section' => 'my_profile'])->with('success', 'Profil Anda berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating admin profile: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil.');
        }
    }

    public function updateMyPassword(Request $request)
    {
        $admin = Auth::user();
        $validatedData = $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) use ($admin) {
                if (!Hash::check($value, $admin->password)) {
                    $fail('Password saat ini tidak sesuai.');
                }
            }],
            'password' => ['required', 'string', PasswordRule::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ]);

        try {
            $admin->password = $validatedData['password']; // Di-hash otomatis oleh model User
            $admin->save();
            return redirect()->route('admin.settings.index', ['section' => 'my_password'])->with('success', 'Password Anda berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating admin password: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui password.');
        }
    }
}