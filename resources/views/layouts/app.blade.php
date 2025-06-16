<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Koperasi Management System')</title>

    

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Alpine.js CDN (Pastikan ini ada dan sebelum akhir </head> atau gunakan defer) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> {{-- Ganti 3.x.x dengan versi terbaru --}}

    {{-- <link rel="stylesheet" href="{{ asset('css/profile-styles.css') }}"> --}}
    {{-- <script src="{{ asset('js/profile-image-handler.js') }}"></script> --}}
    <!-- Custom Tailwind Config & Styles -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6', // blue-500
                        secondary: '#1E40AF', // blue-800 (lebih gelap dari default)
                        accent: '#F59E0B', // amber-500
                        success: '#10B981', // green-500
                        danger: '#EF4444', // red-500
                        // Warna gradient dari desain Anda
                        'gradient-blue-start': '#3B82F6',
                        'gradient-blue-end': '#60A5FA', // blue-400
                        'gradient-purple-start': '#8B5CF6', // purple-500
                        'gradient-purple-end': '#A78BFA', // purple-400
                        'gradient-green-start': '#10B981',
                        'gradient-green-end': '#34D399', // green-400
                        'gradient-yellow-start': '#F59E0B',
                        'gradient-yellow-end': '#FBBF24', // amber-400
                        'gradient-orange-start': '#F97316', // orange-500
                        'gradient-orange-end': '#FB923C', // orange-400
                        'gradient-red-start': '#EF4444',
                        'gradient-red-end': '#F87171', // red-400
                        'gradient-indigo-start': '#6366F1', // indigo-500
                        'gradient-indigo-end': '#818CF8', // indigo-400
                        'gradient-emerald-start': '#059669', // emerald-600
                        'gradient-emerald-end': '#10B981', // emerald-500
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out forwards', // Added forwards
                        'slide-in': 'slideIn 0.3s ease-out forwards', // Added forwards
                        'bounce-in': 'bounceIn 0.6s ease-out forwards', // Added forwards
                        'scale-in': 'scaleIn 0.3s ease-out forwards', // Added forwards
                        'pulse-slow': 'pulse 3s infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate'
                    },
                    keyframes: { // Defining keyframes directly in Tailwind config
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        slideIn: {
                            '0%': {
                                transform: 'translateX(-100%)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateX(0)',
                                opacity: '1'
                            },
                        },
                        bounceIn: {
                            '0%': {
                                transform: 'scale(0.3)',
                                opacity: '0'
                            },
                            '50%': {
                                transform: 'scale(1.05)'
                            },
                            '70%': {
                                transform: 'scale(0.9)'
                            },
                            '100%': {
                                transform: 'scale(1)',
                                opacity: '1'
                            },
                        },
                        scaleIn: {
                            '0%': {
                                transform: 'scale(0.8)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'scale(1)',
                                opacity: '1'
                            },
                        },
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            },
                        },
                        glow: {
                            'from': {
                                boxShadow: '0 0 20px rgba(59, 130, 246, 0.3)'
                            },
                            'to': {
                                boxShadow: '0 0 30px rgba(59, 130, 246, 0.6)'
                            },
                        },
                        pulse: { // Tailwind's default pulse uses opacity
                            '0%, 100%': {
                                opacity: '1'
                            },
                            '50%': {
                                opacity: '.5'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <!-- Custom CSS from app.css -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles') <!-- For page-specific styles -->
</head>

<body class="bg-gradient-to-br from-gray-50 to-blue-50 font-inter text-gray-800">
    <!-- Loading Screen -->
    @include('layouts.partials._loading')

    <div class="flex h-screen overflow-hidden">
        @auth {{-- Hanya tampilkan sidebar jika user terautentikasi --}}
            <!-- Sidebar -->
            @if (Auth::user()->isAdmin())
                @include('layouts.partials.sidebar_admin')
            @elseif(Auth::user()->isPengurus())
                @include('layouts.partials.sidebar_pengurus')
            @elseif(Auth::user()->isAnggota())
                @include('layouts.partials.sidebar_anggota')
            @endif
        @endauth

        <!-- Main Content -->
        <div
            class="flex-1 {{ Auth::check() ? 'lg:ml-64' : '' }} overflow-y-auto transition-all duration-300 ease-in-out">
            @auth
                <!-- Header (Navbar) -->
                @include('layouts.partials.header')
            @endauth

            <!-- Page Content -->
            <main class="p-6 {{ Auth::check() ? 'mt-1 md:mt-2' : '' }}"> {{-- Add margin-top if header is fixed/sticky --}}
                @include('layouts.partials._alerts')
                @yield('content')
            </main>

            @auth
                {{-- Footer bisa diletakkan di sini jika hanya untuk halaman yang terautentikasi --}}
                {{-- @include('layouts.partials.footer') --}}
            @endauth
        </div>
    </div>

    @guest
        {{-- Footer untuk halaman guest bisa diletakkan di sini jika berbeda --}}
    @endguest

    <!-- Global JavaScript from app.js -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Loading screen logic (dari kode Anda sebelumnya)
        window.addEventListener('load', function() {
            const loadingScreen = document.getElementById('loading-screen');
            if (loadingScreen) {
                setTimeout(() => {
                    loadingScreen.style.opacity = '0';
                    setTimeout(() => {
                        loadingScreen.style.display = 'none';
                    }, 300); // Match opacity transition duration
                }, 100); // Reduced delay for faster perceived load
            }
        });

        // Counter animation logic (dari kode Anda sebelumnya)
        function animateCounter(element, target) {
            let current = 0;
            const isCurrency = element.textContent.startsWith('Rp');
            const cleanTarget = typeof target === 'string' ? parseFloat(target.replace(/[^0-9.-]+/g, "")) : target;

            if (isNaN(cleanTarget)) {
                element.textContent = target; // If target is not a number, display as is
                return;
            }

            const increment = cleanTarget / 100; // Animate in 100 steps
            const duration = 20; // ms per step

            const timer = setInterval(() => {
                current += increment;
                if ((increment > 0 && current >= cleanTarget) || (increment < 0 && current <= cleanTarget) ||
                    increment === 0) {
                    current = cleanTarget;
                    clearInterval(timer);
                }
                if (isCurrency) {
                    element.textContent = 'Rp ' + Math.floor(current).toLocaleString('id-ID');
                } else {
                    element.textContent = Math.floor(current).toLocaleString('id-ID');
                }
            }, duration);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.stat-number[data-target]');
            counters.forEach(counter => {
                const targetValue = counter.getAttribute('data-target');
                // Initialize with 0 or placeholder before animation for currency
                if (counter.textContent.startsWith('Rp')) {
                    counter.textContent = 'Rp 0';
                } else {
                    counter.textContent = '0';
                }
                animateCounter(counter, targetValue);
            });
        });

        // Di dalam layouts/app.blade.php, setelah KoperasiApp didefinisikan atau di akhir @push('scripts')

        document.addEventListener('DOMContentLoaded', function() {
            // ... (kode JS lain yang sudah ada seperti counter, loading screen) ...

            // Auto-show notifications from session flash messages
            @if (session('success'))
                KoperasiApp.showNotification("{{ session('success') }}", 'success');
            @endif

            @if (session('error'))
                KoperasiApp.showNotification("{{ session('error') }}", 'error');
            @endif

            @if (session('warning'))
                KoperasiApp.showNotification("{{ session('warning') }}", 'warning');
                // Anda mungkin perlu menambahkan style untuk 'warning' di KoperasiApp.showNotification
            @endif

            @if (session('info'))
                KoperasiApp.showNotification("{{ session('info') }}", 'info');
                // Anda mungkin perlu menambahkan style untuk 'info' di KoperasiApp.showNotification
            @endif

            // Optional: Jika ingin menampilkan error validasi form sebagai notifikasi juga (selain di bawah field)
            // @if ($errors->any())
            //     KoperasiApp.showNotification("Terdapat beberapa kesalahan input, silakan periksa form Anda.", 'error');
            // @endif
        });

        // Di dalam script tag di layouts/app.blade.php atau settings
        function applyThemePreference(theme) {
            if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Panggil saat halaman load untuk menerapkan tema dari setting (jika ada) atau localStorage
        document.addEventListener('DOMContentLoaded', () => {
            // Ambil preferensi tema dari backend (jika di-pass ke JS) atau localStorage
            // Contoh jika di-pass ke JS via variabel global atau data attribute
            const initialTheme = window.koperasiSettings?.themePreference || localStorage.getItem(
                'theme_preference') || 'system';
            applyThemePreference(initialTheme);
            localStorage.setItem('theme_preference', initialTheme); // Simpan ke localStorage juga

            // Listener jika tema sistem berubah
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
                if (localStorage.getItem('theme_preference') === 'system') {
                    applyThemePreference('system');
                }
            });
        });

        // Saat tema diubah di halaman settings dan disimpan:
        // Setelah berhasil menyimpan ke backend, update localStorage dan panggil applyThemePreference()
        // Di dalam fungsi selectTheme() di settings_page.blade.php:
        // function selectTheme(element, themeKey) {
        //     // ... (logika update UI pilihan) ...
        //     document.getElementById('theme_preference_input').value = themeKey;
        //     // Untuk efek langsung (opsional, karena akan disimpan ke DB lalu dibaca saat reload)
        //     // localStorage.setItem('theme_preference', themeKey);
        //     // applyThemePreference(themeKey);
        // }
    </script>
    @stack('scripts') <!-- For page-specific scripts -->
</body>

</html>
