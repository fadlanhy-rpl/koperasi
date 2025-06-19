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
    
    <!-- jQuery (Required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- SweetAlert2 for better notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Tailwind Config & Styles -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#1E40AF',
                        accent: '#F59E0B',
                        success: '#10B981',
                        danger: '#EF4444',
                        'gradient-blue-start': '#3B82F6',
                        'gradient-blue-end': '#60A5FA',
                        'gradient-purple-start': '#8B5CF6',
                        'gradient-purple-end': '#A78BFA',
                        'gradient-green-start': '#10B981',
                        'gradient-green-end': '#34D399',
                        'gradient-yellow-start': '#F59E0B',
                        'gradient-yellow-end': '#FBBF24',
                        'gradient-orange-start': '#F97316',
                        'gradient-orange-end': '#FB923C',
                        'gradient-red-start': '#EF4444',
                        'gradient-red-end': '#F87171',
                        'gradient-indigo-start': '#6366F1',
                        'gradient-indigo-end': '#818CF8',
                        'gradient-emerald-start': '#059669',
                        'gradient-emerald-end': '#10B981',
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out forwards',
                        'slide-in': 'slideIn 0.3s ease-out forwards',
                        'bounce-in': 'bounceIn 0.6s ease-out forwards',
                        'scale-in': 'scaleIn 0.3s ease-out forwards',
                        'pulse-slow': 'pulse 3s infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideIn: {
                            '0%': { transform: 'translateX(-100%)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' },
                        },
                        bounceIn: {
                            '0%': { transform: 'scale(0.3)', opacity: '0' },
                            '50%': { transform: 'scale(1.05)' },
                            '70%': { transform: 'scale(0.9)' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.8)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        glow: {
                            'from': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.3)' },
                            'to': { boxShadow: '0 0 30px rgba(59, 130, 246, 0.6)' },
                        },
                        pulse: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '.5' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Enhanced Product Image Styles -->
    <style>
        /* Product Image Styles */
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-image:hover {
            border-color: #3b82f6;
            transform: scale(1.05);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        /* Placeholder for missing images */
        .product-image-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            border: 2px dashed #cbd5e1;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .product-image-placeholder:hover {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            transform: scale(1.05);
        }
        
        .product-image-placeholder i {
            color: #9ca3af;
            font-size: 24px;
        }
        
        .product-image-placeholder:hover i {
            color: #3b82f6;
        }
        
        /* Handle broken/loading images */
        .product-image[src=""],
        .product-image:not([src]) {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 2px dashed #cbd5e1;
        }
        
        .product-image[src=""]:before,
        .product-image:not([src]):before {
            content: '\f03e';
            font-family: 'Font Awesome 6 Free';
            font-weight: 400;
            font-size: 24px;
            color: #9ca3af;
        }
        
        /* Stock indicators */
        .stock-indicator {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .stock-empty {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .stock-low {
            background-color: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        
        .stock-good {
            background-color: #dcfce7;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        
        /* Action buttons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        
        .action-btn.view {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        
        .action-btn.edit {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }
        
        .action-btn.delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        /* Table row hover effects */
        .table-row:hover .product-image {
            border-color: #3b82f6;
        }
        
        .table-row:hover .product-image-placeholder {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        }
        
        /* Line clamp utility */
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Animation classes */
        .animate-scale-in {
            animation: scaleIn 0.3s ease-out;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            height: 48px !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
            background: white !important;
            transition: all 0.3s ease !important;
        }
        
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            outline: none !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151 !important;
            font-weight: 500 !important;
            line-height: 32px !important;
            padding-left: 8px !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af !important;
            font-weight: 400 !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            right: 8px !important;
        }
        
        .select2-dropdown {
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }
        
        .select2-container--default .select2-results__option {
            padding: 12px 16px !important;
            color: #1f2937 !important;
            font-weight: 500 !important;
            border-bottom: 1px solid #f3f4f6 !important;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6 !important;
            color: white !important;
        }
        
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #eff6ff !important;
            color: #1e40af !important;
            font-weight: 600 !important;
        }
        
        .select2-search--dropdown .select2-search__field {
            border: 2px solid #e5e7eb !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
            margin: 8px !important;
            width: calc(100% - 16px) !important;
        }
        
        .select2-search--dropdown .select2-search__field:focus {
            border-color: #3b82f6 !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }
        
        /* Enhanced dropdown with icons */
        .select2-results__option {
            display: flex !important;
            align-items: center !important;
        }
        
        .select2-results__option::before {
            content: "👤" !important;
            margin-right: 8px !important;
            font-size: 16px !important;
        }
    </style>

    <!-- Custom CSS from app.css -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>

<body class="bg-gradient-to-br from-gray-50 to-blue-50 font-inter text-gray-800">
    <!-- Loading Screen -->
    @include('layouts.partials._loading')

    <div class="flex h-screen overflow-hidden">
        @auth
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
        <div class="flex-1 {{ Auth::check() ? 'lg:ml-64' : '' }} overflow-y-auto transition-all duration-300 ease-in-out">
            @auth
                <!-- Header (Navbar) -->
                @include('layouts.partials.header')
            @endauth

            <!-- Page Content -->
            <main class="p-6 {{ Auth::check() ? 'mt-1 md:mt-2' : '' }}">
                @include('layouts.partials._alerts')
                @yield('content')
            </main>

            @auth
                {{-- Footer bisa diletakkan di sini jika hanya untuk halaman yang terautentikasi --}}
            @endauth
        </div>
    </div>

    @guest
        {{-- Footer untuk halaman guest bisa diletakkan di sini jika berbeda --}}
    @endguest

    <!-- Global JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Global notification function using SweetAlert2
        window.showNotification = function(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        };

        // Global delete confirmation
        window.confirmDelete = function(url, itemName) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Yakin ingin menghapus "${itemName}"? Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        };

        // Enhanced image error handling
        window.handleImageError = function(img) {
            console.log('Image failed to load:', img.src);
            
            // Create placeholder div
            const placeholder = document.createElement('div');
            placeholder.className = 'product-image-placeholder';
            placeholder.innerHTML = '<i class="fas fa-image"></i>';
            placeholder.title = 'Gambar tidak tersedia';
            
            // Replace image with placeholder
            img.parentNode.replaceChild(placeholder, img);
        };

        // Preload and validate images
        window.validateImageUrl = function(url, callback) {
            const img = new Image();
            img.onload = function() {
                callback(true);
            };
            img.onerror = function() {
                callback(false);
            };
            img.src = url;
        };

        // Loading screen logic
        window.addEventListener('load', function() {
            const loadingScreen = document.getElementById('loading-screen');
            if (loadingScreen) {
                setTimeout(() => {
                    loadingScreen.style.opacity = '0';
                    setTimeout(() => {
                        loadingScreen.style.display = 'none';
                    }, 300);
                }, 100);
            }
        });

        // Counter animation logic
        function animateCounter(element, target) {
            let current = 0;
            const isCurrency = element.textContent.startsWith('Rp');
            const cleanTarget = typeof target === 'string' ? parseFloat(target.replace(/[^0-9.-]+/g, "")) : target;

            if (isNaN(cleanTarget)) {
                element.textContent = target;
                return;
            }

            const increment = cleanTarget / 100;
            const duration = 20;

            const timer = setInterval(() => {
                current += increment;
                if ((increment > 0 && current >= cleanTarget) || (increment < 0 && current <= cleanTarget) || increment === 0) {
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
            // Counter animation
            const counters = document.querySelectorAll('.stat-number[data-target]');
            counters.forEach(counter => {
                const targetValue = counter.getAttribute('data-target');
                if (counter.textContent.startsWith('Rp')) {
                    counter.textContent = 'Rp 0';
                } else {
                    counter.textContent = '0';
                }
                animateCounter(counter, targetValue);
            });

            // Enhanced image error handling
            const images = document.querySelectorAll('.product-image');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    handleImageError(this);
                });
                
                // Also check if image src is empty or invalid
                if (!img.src || img.src === window.location.href || img.src.includes('undefined')) {
                    handleImageError(img);
                }
            });

            // Auto-show notifications from session flash messages
            @if (session('success'))
                showNotification("{{ session('success') }}", 'success');
            @endif

            @if (session('error'))
                showNotification("{{ session('error') }}", 'error');
            @endif

            @if (session('warning'))
                showNotification("{{ session('warning') }}", 'warning');
            @endif

            @if (session('info'))
                showNotification("{{ session('info') }}", 'info');
            @endif
        });

        // Theme preference logic
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
            localStorage.setItem('theme_preference', initialTheme);

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
                if (localStorage.getItem('theme_preference') === 'system') {
                    applyThemePreference('system');
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>