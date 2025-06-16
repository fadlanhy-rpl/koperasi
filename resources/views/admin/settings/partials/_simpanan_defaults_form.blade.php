{{-- resources/views/admin/settings/partials/_simpanan_defaults_form.blade.php --}}
<div class="card-hover bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Pengaturan Default Simpanan</h2>
                <p class="text-gray-600 mt-1">Atur nominal standar untuk simpanan pokok dan wajib.</p>
            </div>
            <i class="fas fa-coins text-2xl text-gray-300"></i>
        </div>
    </div>
    
    <form action="{{ route('admin.settings.simpanan.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="p-6 space-y-6">
            <x-forms.input 
                type="number"
                name="default_simpanan_pokok" 
                label="Nominal Default Simpanan Pokok" 
                :value="$currentSettings['default_simpanan_pokok'] ?? 100000" 
                placeholder="Masukkan nominal"
                :required="true"
                min="0" {{-- Atribut diteruskan secara individual --}}
                step="1000" {{-- Atribut diteruskan secara individual --}}
                helpText="Nominal ini akan disarankan saat pengurus mencatat simpanan pokok baru."
            />
            <x-forms.input 
                type="number"
                name="default_simpanan_wajib" 
                label="Nominal Default Simpanan Wajib (per Bulan)" 
                :value="$currentSettings['default_simpanan_wajib'] ?? 25000" 
                placeholder="Masukkan nominal"
                :required="true"
                min="0" {{-- Atribut diteruskan secara individual --}}
                step="1000" {{-- Atribut diteruskan secara individual --}}
                helpText="Nominal ini akan disarankan saat pengurus mencatat simpanan wajib baru."
            />
        </div>
        
        <div class="p-6 border-t border-gray-100 flex justify-end">
            <x-forms.button type="submit" variant="primary" icon="save">
                Simpan Pengaturan Simpanan
            </x-forms.button>
        </div>
    </form>
</div>