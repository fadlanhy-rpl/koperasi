{{-- resources/views/admin/settings/partials/_my_password_form.blade.php --}}
<div class="card-hover bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800">Ubah Password Saya</h2>
        <p class="text-gray-600 mt-1">Pastikan menggunakan password yang kuat.</p>
    </div>
    <form action="{{ route('admin.settings.mypassword.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-6">
            <x-forms.input type="password" name="current_password" label="Password Saat Ini" :required="true" />
            <x-forms.input type="password" name="password" label="Password Baru" :required="true" />
            <x-forms.input type="password" name="password_confirmation" label="Konfirmasi Password Baru" :required="true" />
        </div>
        <div class="p-6 border-t border-gray-100 flex justify-end">
            <x-forms.button type="submit" variant="primary" icon="key">Update Password</x-forms.button>
        </div>
    </form>
</div>