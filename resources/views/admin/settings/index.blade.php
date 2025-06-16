{{-- resources/views/admin/settings/index.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- ... (Head content sama seperti yang saya berikan sebelumnya, termasuk Tailwind config, Font Awesome, Google Fonts, app.css, dan style custom) ... --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengaturan Sistem - {{ $currentSettings['koperasi_nama'] ?? config('app.name', 'Koperasi') }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#3B82F6', secondary: '#1E40AF', accent: '#F59E0B', success: '#10B981', danger: '#EF4444' },
                    fontFamily: { 'inter': ['Inter', 'sans-serif'] },
                    animation: { 'fade-in': 'fadeIn 0.5s ease-in-out forwards', 'slide-in': 'slideIn 0.3s ease-out forwards', 'bounce-in': 'bounceIn 0.6s ease-out forwards', 'scale-in': 'scaleIn 0.3s ease-out forwards' },
                    keyframes: { 
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        slideIn: { '0%': { transform: 'translateX(-30px)', opacity: '0' }, '100%': { transform: 'translateX(0)', opacity: '1' } }, // Adjust slideIn
                        bounceIn: { '0%': { transform: 'scale(0.3)', opacity: '0' }, '50%': { transform: 'scale(1.05)' }, '70%': { transform: 'scale(0.9)' }, '100%': { transform: 'scale(1)', opacity: '1' } },
                        scaleIn: { '0%': { transform: 'scale(0.95)', opacity: '0' }, '100%': { transform: 'scale(1)', opacity: '1' } }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .nav-item.active { background-color: #DBEAFE; /* blue-100 */ border-left: 4px solid #3B82F6; /* blue-500 */ color: #2563EB; /* blue-600 */ font-weight: 600; }
        .nav-item:not(.active):hover { background-color: #F3F4F6; /* gray-100 */ color: #1D4ED8; /* blue-700 */ }
        .theme-card.selected { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5); }
        .toggle-switch { position: relative; display: inline-block; width: 50px; height: 28px; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 28px; }
        .slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #3B82F6; }
        input:checked + .slider:before { transform: translateX(22px); }
    </style>
</head>
<body class="bg-gray-100 font-inter text-gray-800">
    @include('layouts.partials._loading')

    <div class="flex h-screen overflow-hidden">
        <!-- Settings Sidebar -->
        <div class="w-72 bg-white shadow-xl fixed inset-y-0 left-0 z-30 h-full overflow-y-auto border-r border-gray-200 animate-slide-in hidden lg:flex flex-col">
            <div class="p-6 border-b border-gray-200 flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-handshake text-white text-lg"></i>
                    </div>
                    <div>
                        <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Koperasi</span>
                        <p class="text-xs text-gray-500">Pengaturan Sistem</p>
                    </div>
                </a>
            </div>
            
            <nav class="mt-4 flex-grow px-3 space-y-1 text-sm">
                <span class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider block">UMUM</span>
                <a href="#profil_koperasi" class="nav-item flex items-center px-4 py-2.5 rounded-lg" onclick="showSection('profil_koperasi', this)">
                    <i class="fas fa-landmark w-5 h-5 mr-3 text-gray-400"></i> <span>Profil Koperasi</span>
                </a>
                 <a href="#simpanan" class="nav-item flex items-center px-4 py-2.5 rounded-lg" onclick="showSection('simpanan', this)">
                    <i class="fas fa-coins w-5 h-5 mr-3 text-gray-400"></i> <span>Default Simpanan</span>
                </a>
                <a href="#appearance" class="nav-item flex items-center px-4 py-2.5 rounded-lg" onclick="showSection('appearance', this)">
                    <i class="fas fa-palette w-5 h-5 mr-3 text-gray-400"></i> <span>Tampilan</span>
                </a>

                <span class="px-4 py-2 mt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider block">AKUN SAYA</span>
                 <a href="#my_profile" class="nav-item flex items-center px-4 py-2.5 rounded-lg" onclick="showSection('my_profile', this)">
                    <i class="fas fa-user-edit w-5 h-5 mr-3 text-gray-400"></i> <span>Edit Profil Saya</span>
                </a>
                <a href="#my_password" class="nav-item flex items-center px-4 py-2.5 rounded-lg" onclick="showSection('my_password', this)">
                    <i class="fas fa-key w-5 h-5 mr-3 text-gray-400"></i> <span>Ubah Password</span>
                </a>
                
                {{-- Placeholder untuk menu lain --}}
                {{-- <span class="px-4 py-2 mt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider block">LANJUTAN</span>
                <a href="#team" class="nav-item flex items-center px-4 py-2.5 rounded-lg" onclick="showSection('team', this)">
                    <i class="fas fa-users w-5 h-5 mr-3 text-gray-400"></i> <span>Manajemen Tim</span>
                </a> --}}

                <div class="mt-auto border-t border-gray-200 mx-[-12px]"> {{-- mt-auto pushes to bottom --}}
                    <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center px-7 py-3 text-gray-600 hover:text-blue-600">
                        <i class="fas fa-arrow-left w-5 h-5 mr-3 text-gray-400"></i> <span>Kembali ke Dashboard</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-72 overflow-y-auto">
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20 animate-fade-in">
                {{-- ... (Header content sama seperti yang Anda berikan di file settings_page.html, dengan data user dinamis) ... --}}
                <div class="flex items-center justify-between px-6 py-3.5"> {{-- Adjusted padding --}}
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800" id="settings-page-title">Pengaturan Umum</h1>
                        <p class="text-sm text-gray-500" id="settings-page-subtitle">Kelola preferensi dan konfigurasi sistem.</p>
                    </div>
                     <div class="flex items-center space-x-4">
                        @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 bg-gray-100/80 rounded-full pl-1 pr-3 py-1 hover:bg-gray-200/80 transition-colors">
                                <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&color=fff&size=32&rounded=true&font-size=0.33&bold=true' }}"
                                alt="Foto Profil {{ Auth::user()->name }}"
                                class="w-8 h-8 md:w-9 md:h-9 rounded-full ring-1 ring-blue-300 object-cover">
                                <span class="text-sm font-medium text-gray-700 hidden md:inline">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-gray-500 text-xs transform transition-transform" :class="{'rotate-180': open}"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl z-30 py-1 border border-gray-200" x-transition>
                                <div class="px-4 py-3 border-b flex border-gray-100">
                            <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&color=fff&size=32&rounded=true&font-size=0.33&bold=true' }}"
                                alt="Foto Profil {{ Auth::user()->name }}"
                                class="w-8 h-8 md:w-9 md:h-9 rounded-full ring-1 ring-blue-300 object-cover">
                            <div class="translate-x-2">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600">Dashboard Utama</a>
                                <a href="#my_profile" onclick="showSection('my_profile', document.querySelector('.nav-item[href=\'#my_profile\']')); open = false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600">Profil Saya</a>
                                <div class="border-t border-gray-100"></div>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-settings').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
                                <form id="logout-form-settings" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </header>

            <div class="p-6 md:p-8">
                @include('layouts.partials._alerts')

                <div id="profil_koperasi" class="settings-section animate-fade-in">
                    @include('admin.settings.partials._profil_koperasi_form', ['currentSettings' => $currentSettings])
                </div>
                <div id="simpanan" class="settings-section hidden animate-fade-in">
                    @include('admin.settings.partials._simpanan_defaults_form', ['currentSettings' => $currentSettings])
                </div>
                <div id="appearance" class="settings-section hidden animate-fade-in">
                    @include('admin.settings.partials._appearance_form', ['currentSettings' => $currentSettings])
                </div>
                <div id="my_profile" class="settings-section hidden animate-fade-in">
                    {{-- Variabel $adminUser dikirim dari SettingController@index --}}
                    @include('admin.settings.partials._my_profile_form', ['adminUser' => $adminUser])
                </div>

                <!-- SECTION: Ubah Password (Admin) -->
                <div id="my_password" class="settings-section hidden animate-fade-in">
                    @include('admin.settings.partials._my_password_form')
                </div>
            </div>
            </div>
            {{-- @include('layouts.partials.footer', ['isSettingsPage' => true]) --}}
        </div>
    </div>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.10/dist/cdn.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- Kode JavaScript untuk showSection, selectTheme, selectTableView, dan initial load section SAMA seperti yang sudah kita bahas di chat sebelumnya untuk halaman settings --}}
    <script>
        // Fungsi applyThemePreference HARUS SUDAH ADA (bisa di-copy dari layouts/app.blade.php atau dibuat global)
        // Misalnya:
        function applyThemePreference(theme) {
            if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            const initialTheme = window.koperasiSettings?.themePreference || localStorage.getItem('theme_preference') || 'system';
            applyThemePreference(initialTheme);
        });


        let activeSectionId = 'profil_koperasi'; 

        function showSection(sectionId, clickedElement) {
            document.querySelectorAll('.settings-section').forEach(section => {
                section.classList.add('hidden');
                section.classList.remove('animate-fade-in');
            });
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.classList.remove('hidden');
                void targetSection.offsetWidth; 
                targetSection.classList.add('animate-fade-in');
                activeSectionId = sectionId;

                const titles = {
                    'profil_koperasi': 'Profil Koperasi', 'simpanan': 'Default Simpanan', 
                    'appearance': 'Pengaturan Tampilan', 'my_profile': 'Edit Profil Saya', 'my_password': 'Ubah Password Saya'
                };
                const subtitles = {
                    'profil_koperasi': 'Informasi dasar koperasi Anda.', 'simpanan': 'Atur nominal default untuk simpanan.', 
                    'appearance': 'Kelola preferensi tampilan dan tema.', 'my_profile': 'Perbarui nama dan email login Anda.', 
                    'my_password': 'Pastikan menggunakan password yang kuat.'
                };
                document.getElementById('settings-page-title').textContent = titles[sectionId] || 'Pengaturan';
                document.getElementById('settings-page-subtitle').textContent = subtitles[sectionId] || 'Kelola konfigurasi sistem.';
            }

            document.querySelectorAll('.nav-item').forEach(link => {
                link.classList.remove('active');
            });
            if (clickedElement) {
                clickedElement.classList.add('active');
            } else { 
                const navItemToActivate = document.querySelector(`.nav-item[href="#${sectionId}"]`);
                if (navItemToActivate) navItemToActivate.classList.add('active');
            }
            // Update URL dengan query parameter untuk persistensi state antar reload
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('section', sectionId);
            window.history.replaceState({}, '', currentUrl); // Ganti history agar back button tidak aneh
        }

        function selectTheme(element, themeKey) {
            document.querySelectorAll('.theme-card').forEach(card => {
                card.classList.remove('selected', 'border-blue-500');
                card.classList.add('border-gray-200');
            });
            element.classList.add('selected', 'border-blue-500');
            element.classList.remove('border-gray-200');
            document.getElementById('theme_preference_input').value = themeKey;
        }

        function selectTableView(element, viewKey) {
            element.closest('.grid').querySelectorAll('.theme-card').forEach(card => {
                card.classList.remove('selected', 'border-blue-500');
                card.classList.add('border-gray-200');
            });
            element.classList.add('selected', 'border-blue-500');
            element.classList.remove('border-gray-200');
            document.getElementById('table_view_input').value = viewKey;
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            // Loading screen (jika masih digunakan di halaman ini)
            const loadingScreen = document.getElementById('loading-screen');
            if(loadingScreen) {
                setTimeout(() => {
                    loadingScreen.style.opacity = '0';
                    setTimeout(() => { loadingScreen.style.display = 'none'; }, 300);
                }, 500); // Delay lebih pendek untuk settings
            }

            const urlParams = new URLSearchParams(window.location.search);
            let sectionFromUrl = urlParams.get('section');
            
            if (!sectionFromUrl || !document.getElementById(sectionFromUrl)) {
                sectionFromUrl = 'profil_koperasi'; 
            }
            
            const initialElement = document.querySelector(`.nav-item[href="#${sectionFromUrl}"]`);
            showSection(sectionFromUrl, initialElement);

            if (!initialElement && !document.querySelector('.nav-item.active')) {
                 const defaultNavItem = document.querySelector('.nav-item[href="#profil_koperasi"]');
                 if(defaultNavItem) {
                    defaultNavItem.classList.add('active');
                     if(!document.querySelector('.settings-section:not(.hidden)')) {
                        showSection('profil_koperasi', defaultNavItem);
                    }
                 }
            }
        });
    </script>
</body>
</html>