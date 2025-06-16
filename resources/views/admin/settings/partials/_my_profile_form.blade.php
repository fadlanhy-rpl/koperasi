{{-- resources/views/admin/settings/partials/_my_password_form.blade.php --}}
<div class="card-hover bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 mt-6 lg:mt-0"> {{-- Tambah margin top jika perlu di layout yang sama --}}
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800">Ubah Password Saya</h2>
        <p class="text-gray-600 mt-1">Pastikan Anda menggunakan password yang kuat dan unik.</p>
    </div>
    <form action="{{ route('admin.settings.mypassword.update') }}" method="POST" data-validate>
        @csrf
        @method('PUT')
        <div class="p-6 space-y-6">
            <x-forms.input 
                type="password" 
                name="current_password" 
                label="Password Saat Ini" 
                placeholder="Masukkan password Anda saat ini" 
                :required="true" 
                icon="lock"
            />
            <x-forms.input 
                type="password" 
                name="password" 
                label="Password Baru" 
                placeholder="Minimal 8 karakter, kombinasi huruf & angka" 
                :required="true" 
                icon="key"
            />
            <x-forms.input 
                type="password" 
                name="password_confirmation" 
                label="Konfirmasi Password Baru" 
                placeholder="Ulangi password baru Anda" 
                :required="true" 
                icon="key"
            />
        </div>
        <div class="p-6 border-t border-gray-100 flex justify-end">
            <x-forms.button type="submit" variant="primary" icon="shield-alt">
                Update Password
            </x-forms.button>
        </div>
    </form>
</div>