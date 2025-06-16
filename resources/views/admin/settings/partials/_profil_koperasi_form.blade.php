{{-- resources/views/admin/settings/partials/_profil_koperasi_form.blade.php --}}
<div class="card-hover bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Profil Koperasi</h2>
                <p class="text-gray-600 mt-1">Kelola informasi dasar dan kontak koperasi Anda.</p>
            </div>
            <i class="fas fa-building text-2xl text-gray-300"></i>
        </div>
    </div>
    
    <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data"> {{-- Tambah enctype untuk upload logo --}}
        @csrf
        @method('PUT') {{-- Atau POST jika route Anda didefinisikan sebagai POST --}}
        <div class="p-6 space-y-6">
            <x-forms.input 
                name="koperasi_nama" 
                label="Nama Koperasi" 
                :value="$currentSettings['koperasi_nama'] ?? ''" 
                placeholder="Masukkan nama resmi koperasi"
                :required="true"
            />
            <div>
                <label for="koperasi_alamat" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Koperasi</label>
                <textarea id="koperasi_alamat" name="koperasi_alamat" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 placeholder-gray-400" 
                          placeholder="Masukkan alamat lengkap koperasi">{{ old('koperasi_alamat', $currentSettings['koperasi_alamat'] ?? '') }}</textarea>
                @error('koperasi_alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-forms.input 
                    type="tel"
                    name="koperasi_telepon" 
                    label="Nomor Telepon Koperasi" 
                    :value="$currentSettings['koperasi_telepon'] ?? ''" 
                    placeholder="Contoh: 021-1234567"
                />
                <x-forms.input 
                    type="email"
                    name="koperasi_email" 
                    label="Email Koperasi" 
                    :value="$currentSettings['koperasi_email'] ?? ''" 
                    placeholder="Contoh: info@koperasi.com"
                />
            </div>

            {{-- Fitur Upload Logo (Opsional) --}}
            {{-- <div>
                <label for="koperasi_logo" class="block text-sm font-medium text-gray-700 mb-1.5">Logo Koperasi</label>
                @if(isset($currentSettings['koperasi_logo_path']) && $currentSettings['koperasi_logo_path'])
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $currentSettings['koperasi_logo_path']) }}" alt="Logo Saat Ini" class="h-20 rounded-md border">
                    </div>
                @endif
                <input type="file" id="koperasi_logo" name="koperasi_logo"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah logo. Max: 1MB (JPG, PNG).</p>
                @error('koperasi_logo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div> --}}

        </div>
        
        <div class="p-6 border-t border-gray-100 flex justify-end">
            <x-forms.button type="submit" variant="primary" icon="save">
                Simpan Informasi Koperasi
            </x-forms.button>
        </div>
    </form>
</div>