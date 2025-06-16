{{-- resources/views/admin/settings/partials/_appearance_form.blade.php --}}
<div class="card-hover bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Pengaturan Tampilan</h2>
                <p class="text-gray-600 mt-1">Ubah tampilan dan nuansa dashboard koperasi Anda.</p>
            </div>
            <i class="fas fa-palette text-2xl text-gray-300"></i>
        </div>
    </div>
    
    <form action="{{ route('admin.settings.appearance.update') }}" method="POST">
        @csrf
        @method('PUT') {{-- Atau POST --}}
        <div class="p-6 space-y-8">
            <!-- Interface Theme -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Tema Interface</h3>
                <p class="text-sm text-gray-600 mb-6">Pilih atau sesuaikan tema UI Anda.</p>
                <input type="hidden" name="theme_preference" id="theme_preference_input" value="{{ old('theme_preference', $currentSettings['theme_preference'] ?? 'system') }}">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="theme-card bg-white border-2 rounded-xl p-4 {{ ($currentSettings['theme_preference'] ?? 'system') == 'system' ? 'selected border-blue-500' : 'border-gray-200' }}" 
                         onclick="selectTheme(this, 'system'); document.getElementById('theme_preference_input').value = 'system';">
                        {{-- ... (Konten visualisasi tema system dari HTML Anda) ... --}}
                         <div class="bg-gray-100 rounded-lg h-24 mb-3 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-purple-600 opacity-20"></div>
                            <div class="absolute top-2 left-2 w-2 h-2 bg-red-400 rounded-full"></div><div class="absolute top-2 left-5 w-2 h-2 bg-yellow-400 rounded-full"></div><div class="absolute top-2 left-8 w-2 h-2 bg-green-400 rounded-full"></div>
                            <div class="absolute bottom-2 left-2 right-2 h-1 bg-gray-300 rounded"></div><div class="absolute bottom-4 left-2 right-2 h-1 bg-gray-300 rounded"></div>
                        </div>
                        <h4 class="font-medium text-gray-800">System Preference</h4>
                        <p class="text-sm text-gray-500">Mengikuti pengaturan sistem</p>
                    </div>
                    
                    <div class="theme-card bg-white border-2 rounded-xl p-4 {{ ($currentSettings['theme_preference'] ?? 'system') == 'light' ? 'selected border-blue-500' : 'border-gray-200' }}" 
                         onclick="selectTheme(this, 'light'); document.getElementById('theme_preference_input').value = 'light';">
                        {{-- ... (Konten visualisasi tema light dari HTML Anda) ... --}}
                        <div class="bg-white border rounded-lg h-24 mb-3 relative overflow-hidden">
                            <div class="absolute top-2 left-2 w-2 h-2 bg-red-400 rounded-full"></div><div class="absolute top-2 left-5 w-2 h-2 bg-yellow-400 rounded-full"></div><div class="absolute top-2 left-8 w-2 h-2 bg-green-400 rounded-full"></div>
                            <div class="absolute bottom-2 left-2 right-2 h-1 bg-gray-200 rounded"></div><div class="absolute bottom-4 left-2 right-2 h-1 bg-gray-200 rounded"></div>
                        </div>
                        <h4 class="font-medium text-gray-800">Light</h4>
                        <p class="text-sm text-gray-500">Tema terang</p>
                    </div>
                    
                    <div class="theme-card bg-white border-2 rounded-xl p-4 {{ ($currentSettings['theme_preference'] ?? 'system') == 'dark' ? 'selected border-blue-500' : 'border-gray-200' }}" 
                         onclick="selectTheme(this, 'dark'); document.getElementById('theme_preference_input').value = 'dark';">
                        {{-- ... (Konten visualisasi tema dark dari HTML Anda) ... --}}
                        <div class="bg-gray-800 rounded-lg h-24 mb-3 relative overflow-hidden">
                            <div class="absolute top-2 left-2 w-2 h-2 bg-red-400 rounded-full"></div><div class="absolute top-2 left-5 w-2 h-2 bg-yellow-400 rounded-full"></div><div class="absolute top-2 left-8 w-2 h-2 bg-green-400 rounded-full"></div>
                            <div class="absolute bottom-2 left-2 right-2 h-1 bg-gray-600 rounded"></div><div class="absolute bottom-4 left-2 right-2 h-1 bg-gray-600 rounded"></div>
                        </div>
                        <h4 class="font-medium text-gray-800">Dark</h4>
                        <p class="text-sm text-gray-500">Tema gelap</p>
                    </div>
                </div>
                @error('theme_preference') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div class="flex items-center justify-between py-4 border-b border-gray-100">
                <div>
                    <h4 class="font-medium text-gray-800">Sidebar Transparan</h4>
                    <p class="text-sm text-gray-600">Buat sidebar desktop transparan (efek blur)</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="sidebar_transparent" {{ ($currentSettings['sidebar_transparent'] ?? false) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
                 @error('sidebar_transparent') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div class="py-4 border-b border-gray-100">
                <label for="sidebar_feature" class="block text-lg font-semibold text-gray-800 mb-2">Fitur Sidebar</label>
                <p class="text-sm text-gray-600 mb-4">Apa yang ditampilkan di sidebar desktop</p>
                <select id="sidebar_feature" name="sidebar_feature" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none">
                    <option value="menu_lengkap" {{ ($currentSettings['sidebar_feature'] ?? 'menu_lengkap') == 'menu_lengkap' ? 'selected' : '' }}>Menu Lengkap</option>
                    <option value="perubahan_terbaru" {{ ($currentSettings['sidebar_feature'] ?? 'menu_lengkap') == 'perubahan_terbaru' ? 'selected' : '' }}>Perubahan Terbaru (Contoh)</option>
                    <option value="menu_ringkas" {{ ($currentSettings['sidebar_feature'] ?? 'menu_lengkap') == 'menu_ringkas' ? 'selected' : '' }}>Menu Ringkas (Contoh)</option>
                </select>
                 @error('sidebar_feature') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Tampilan Tabel</h4>
                <p class="text-sm text-gray-600 mb-6">Bagaimana tabel data ditampilkan dalam aplikasi</p>
                <input type="hidden" name="table_view" id="table_view_input" value="{{ old('table_view', $currentSettings['table_view'] ?? 'default') }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="theme-card bg-white border-2 rounded-xl p-4 {{ ($currentSettings['table_view'] ?? 'default') == 'default' ? 'selected border-blue-500' : 'border-gray-200' }}" 
                         onclick="selectTableView(this, 'default'); document.getElementById('table_view_input').value = 'default';">
                        {{-- ... (Konten visualisasi tabel default dari HTML Anda) ... --}}
                        <div class="bg-gray-50 rounded-lg h-20 mb-3 relative overflow-hidden p-2"><div class="space-y-1"><div class="flex space-x-2"><div class="w-3 h-2 bg-gray-300 rounded"></div><div class="w-8 h-2 bg-gray-300 rounded"></div><div class="w-6 h-2 bg-gray-300 rounded"></div></div><div class="flex space-x-2"><div class="w-3 h-2 bg-gray-300 rounded"></div><div class="w-8 h-2 bg-gray-300 rounded"></div><div class="w-6 h-2 bg-gray-300 rounded"></div></div><div class="flex space-x-2"><div class="w-3 h-2 bg-gray-300 rounded"></div><div class="w-8 h-2 bg-gray-300 rounded"></div><div class="w-6 h-2 bg-gray-300 rounded"></div></div></div></div>
                        <h4 class="font-medium text-gray-800">Default</h4>
                    </div>
                    <div class="theme-card bg-white border-2 rounded-xl p-4 {{ ($currentSettings['table_view'] ?? 'default') == 'compact' ? 'selected border-blue-500' : 'border-gray-200' }}" 
                         onclick="selectTableView(this, 'compact'); document.getElementById('table_view_input').value = 'compact';">
                        {{-- ... (Konten visualisasi tabel compact dari HTML Anda) ... --}}
                        <div class="bg-gray-50 rounded-lg h-20 mb-3 relative overflow-hidden p-2"><div class="space-y-0.5"><div class="flex space-x-2"><div class="w-2 h-1.5 bg-gray-300 rounded"></div><div class="w-6 h-1.5 bg-gray-300 rounded"></div><div class="w-4 h-1.5 bg-gray-300 rounded"></div></div><div class="flex space-x-2"><div class="w-2 h-1.5 bg-gray-300 rounded"></div><div class="w-6 h-1.5 bg-gray-300 rounded"></div><div class="w-4 h-1.5 bg-gray-300 rounded"></div></div><div class="flex space-x-2"><div class="w-2 h-1.5 bg-gray-300 rounded"></div><div class="w-6 h-1.5 bg-gray-300 rounded"></div><div class="w-4 h-1.5 bg-gray-300 rounded"></div></div><div class="flex space-x-2"><div class="w-2 h-1.5 bg-gray-300 rounded"></div><div class="w-6 h-1.5 bg-gray-300 rounded"></div><div class="w-4 h-1.5 bg-gray-300 rounded"></div></div></div></div>
                        <h4 class="font-medium text-gray-800">Compact</h4>
                    </div>
                </div>
                 @error('table_view') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        
        <div class="p-6 border-t border-gray-100 flex justify-end">
            <x-forms.button type="submit" variant="primary" icon="save">
                Simpan Pengaturan Tampilan
            </x-forms.button>
        </div>
    </form>
</div>