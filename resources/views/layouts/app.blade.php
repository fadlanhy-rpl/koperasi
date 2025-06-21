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
                        'glow': 'glow 2s ease-in-out infinite alternate',
                        'shimmer': 'shimmer 2s linear infinite',
                        'slide-up': 'slideUp 0.4s ease-out forwards',
                        'zoom-in': 'zoomIn 0.3s ease-out forwards'
                    },
                    keyframes: {
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
                        shimmer: {
                            '0%': {
                                backgroundPosition: '-200% 0'
                            },
                            '100%': {
                                backgroundPosition: '200% 0'
                            }
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(20px)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            }
                        },
                        zoomIn: {
                            '0%': {
                                transform: 'scale(0.95)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'scale(1)',
                                opacity: '1'
                            }
                        }
                    }
                }
            }
        }
    </script>

    <!-- Enhanced Styles -->
    <style>
        /* Enhanced Product Image Styles */
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 16px;
            border: 3px solid #e5e7eb;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .product-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .product-image:hover::before {
            left: 100%;
        }

        .product-image:hover {
            border-color: #3b82f6;
            transform: scale(1.08) rotate(2deg);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Enhanced Select2 Styling */
        .select2-container--default .select2-selection--single {
            height: 52px !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 16px !important;
            padding: 8px 16px !important;
            font-size: 15px !important;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1), 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
            outline: none !important;
            transform: translateY(-1px) !important;
        }

        .select2-dropdown {
            border: 2px solid #e5e7eb !important;
            border-radius: 16px !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            backdrop-filter: blur(16px) !important;
            background: rgba(255, 255, 255, 0.95) !important;
        }

        .select2-container--default .select2-results__option {
            padding: 16px 20px !important;
            color: #1f2937 !important;
            font-weight: 500 !important;
            border-bottom: 1px solid #f3f4f6 !important;
            transition: all 0.2s ease !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
            color: white !important;
            transform: translateX(4px) !important;
        }

        /* Enhanced Card Styles */
        .enhanced-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .enhanced-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899, #f59e0b);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .enhanced-card:hover::before {
            opacity: 1;
        }

        .enhanced-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Enhanced Button Styles */
        .btn-enhanced {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 16px;
            font-weight: 600;
            letter-spacing: 0.025em;
        }

        .btn-enhanced::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-enhanced:hover::before {
            left: 100%;
        }

        .btn-enhanced:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        /* Enhanced Table Styles */
        .enhanced-table {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .enhanced-table thead {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .enhanced-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .enhanced-table tbody tr:hover {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Loading Animation */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        /* Status Badge Enhancements */
        .status-badge {
            position: relative;
            overflow: hidden;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 8px 16px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .status-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .status-badge:hover::before {
            left: 100%;
        }

        /* Cart Animation */
        .cart-item {
            animation: slideUp 0.4s ease-out;
        }

        .cart-item.removing {
            animation: slideOut 0.3s ease-in forwards;
        }

        @keyframes slideOut {
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* Enhanced Input Styles */
        .enhanced-input {
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 12px 16px;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .enhanced-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1), 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            outline: none;
            transform: translateY(-1px);
        }

        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border: none;
            box-shadow: 0 8px 32px rgba(59, 130, 246, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }

        .fab:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 12px 40px rgba(59, 130, 246, 0.6);
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 font-inter text-gray-800 min-h-screen">
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
        <div
            class="flex-1 {{ Auth::check() ? 'lg:ml-64' : '' }} overflow-y-auto transition-all duration-300 ease-in-out">
            @auth
                <!-- Header (Navbar) -->
                @include('layouts.partials.header')
            @endauth

            <!-- Page Content -->
            <main class="p-6 {{ Auth::check() ? 'mt-1 md:mt-2' : '' }}">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Global JavaScript -->
    <script>
        // Sidebar Toggle Function
        function toggleSidebar() {
            const userRole = '{{ Auth::check() ? Auth::user()->role : '' }}';
            let sidebarId = '';

            // Determine sidebar ID based on user role
            if (userRole === 'admin') {
                sidebarId = 'sidebar-admin';
            } else if (userRole === 'pengurus') {
                sidebarId = 'sidebar-pengurus';
            } else if (userRole === 'anggota') {
                sidebarId = 'sidebar-anggota';
            }

            if (sidebarId) {
                const sidebar = document.getElementById(sidebarId);
                const overlay = document.getElementById('sidebar-overlay');
                const toggleBtn = document.getElementById('mobile-toggle');

                if (sidebar && overlay) {
                    // Toggle sidebar visibility for mobile
                    if (window.innerWidth < 1024) { // lg breakpoint
                        sidebar.classList.toggle('hidden');
                        sidebar.classList.toggle('flex');
                        sidebar.classList.toggle('open');
                        overlay.classList.toggle('active');

                        // Toggle button icon
                        const icon = toggleBtn.querySelector('i');
                        if (sidebar.classList.contains('open')) {
                            icon.classList.remove('fa-bars');
                            icon.classList.add('fa-times');
                        } else {
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    }
                }
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const userRole = '{{ Auth::check() ? Auth::user()->role : '' }}';
            let sidebarId = '';

            if (userRole === 'admin') {
                sidebarId = 'sidebar-admin';
            } else if (userRole === 'pengurus') {
                sidebarId = 'sidebar-pengurus';
            } else if (userRole === 'anggota') {
                sidebarId = 'sidebar-anggota';
            }

            if (sidebarId && window.innerWidth < 1024) {
                const sidebar = document.getElementById(sidebarId);
                const overlay = document.getElementById('sidebar-overlay');
                const toggleBtn = document.getElementById('mobile-toggle');

                if (sidebar && overlay && sidebar.classList.contains('open')) {
                    if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                        sidebar.classList.add('hidden');
                        sidebar.classList.remove('flex', 'open');
                        overlay.classList.remove('active');

                        // Reset button icon
                        const icon = toggleBtn.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const userRole = '{{ Auth::check() ? Auth::user()->role : '' }}';
            let sidebarId = '';

            if (userRole === 'admin') {
                sidebarId = 'sidebar-admin';
            } else if (userRole === 'pengurus') {
                sidebarId = 'sidebar-pengurus';
            } else if (userRole === 'anggota') {
                sidebarId = 'sidebar-anggota';
            }

            if (sidebarId) {
                const sidebar = document.getElementById(sidebarId);
                const overlay = document.getElementById('sidebar-overlay');
                const toggleBtn = document.getElementById('mobile-toggle');

                if (sidebar && overlay) {
                    if (window.innerWidth >= 1024) {
                        // Desktop: Show sidebar, hide overlay
                        sidebar.classList.remove('hidden', 'open');
                        sidebar.classList.add('flex');
                        overlay.classList.remove('active');

                        // Reset button icon
                        if (toggleBtn) {
                            const icon = toggleBtn.querySelector('i');
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    } else {
                        // Mobile: Hide sidebar by default
                        if (!sidebar.classList.contains('open')) {
                            sidebar.classList.add('hidden');
                            sidebar.classList.remove('flex');
                        }
                    }
                }
            }
        });

        // Enhanced notification function
        window.showNotification = function(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: 'rgba(255, 255, 255, 0.95)',
                customClass: {
                    popup: 'backdrop-blur-sm'
                },
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

        // Enhanced delete confirmation
        window.confirmDelete = function(url, itemName) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Yakin ingin menghapus "${itemName}"? Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.4)',
                customClass: {
                    popup: 'backdrop-blur-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `
                        @csrf
                        @yield('title', 'Koperasi Management System')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        };

        // Enhanced currency formatting
        window.formatCurrency = function(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        };

        // Enhanced loading states
        window.showLoading = function(element, text = 'Memuat...') {
            element.innerHTML = `
                <div class="flex items-center justify-center space-x-2">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                    <span>${text}</span>
                </div>
            `;
            element.disabled = true;
        };

        window.hideLoading = function(element, originalText) {
            element.innerHTML = originalText;
            element.disabled = false;
        };

        // Enhanced AJAX helper
        window.makeRequest = function(url, options = {}) {
            const defaultOptions = {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            };

            return fetch(url, {
                    ...defaultOptions,
                    ...options
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                });
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

        document.addEventListener('DOMContentLoaded', function() {
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

            // Enhanced animations on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-slide-up');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.enhanced-card').forEach(card => {
                observer.observe(card);
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
