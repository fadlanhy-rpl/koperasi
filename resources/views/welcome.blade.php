<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - {{ config('app.name', 'Koperasi Management System') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        },
                        secondary: {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            200: '#e9d5ff',
                            300: '#d8b4fe',
                            400: '#c084fc',
                            500: '#a855f7',
                            600: '#9333ea',
                            700: '#7c3aed',
                            800: '#6b21a8',
                            900: '#581c87'
                        }
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                        'fade-in-down': 'fadeInDown 0.6s ease-out forwards',
                        'fade-in-left': 'fadeInLeft 0.8s ease-out forwards',
                        'fade-in-right': 'fadeInRight 0.8s ease-out forwards',
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out infinite 2s',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'wiggle': 'wiggle 1s ease-in-out infinite',
                        'scale-in': 'scaleIn 0.5s ease-out forwards',
                        'slide-in-bottom': 'slideInBottom 0.8s ease-out forwards',
                        'rotate-slow': 'rotate 20s linear infinite',
                        'gradient-x': 'gradient-x 15s ease infinite',
                        'text-shimmer': 'text-shimmer 2.5s ease-out infinite alternate'
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { transform: 'translateY(30px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        fadeInDown: {
                            '0%': { transform: 'translateY(-30px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        fadeInLeft: {
                            '0%': { transform: 'translateX(-30px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' }
                        },
                        fadeInRight: {
                            '0%': { transform: 'translateX(30px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' }
                        },
                        wiggle: {
                            '0%, 100%': { transform: 'rotate(-3deg)' },
                            '50%': { transform: 'rotate(3deg)' }
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.8)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        },
                        slideInBottom: {
                            '0%': { transform: 'translateY(100px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        'gradient-x': {
                            '0%, 100%': {
                                'background-size': '200% 200%',
                                'background-position': 'left center'
                            },
                            '50%': {
                                'background-size': '200% 200%',
                                'background-position': 'right center'
                            }
                        },
                        'text-shimmer': {
                            '0%': { 'background-position': '0% 50%' },
                            '100%': { 'background-position': '100% 50%' }
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .glass-dark {
            background: rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .text-shimmer {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899, #3b82f6);
            background-size: 200% auto;
            color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: text-shimmer 3s linear infinite;
        }

        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .parallax-bg {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .typing-animation {
            overflow: hidden;
            border-right: 3px solid #3b82f6;
            white-space: nowrap;
            animation: typing 3.5s steps(40, end), blink-caret 0.75s step-end infinite;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: #3b82f6 }
        }

        .morphing-blob {
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: morph 8s ease-in-out infinite;
        }

        @keyframes morph {
            0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            25% { border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%; }
            50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
            75% { border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%; }
            100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card-hover:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .nav-blur {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.9);
        }

        .scroll-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            z-index: 9999;
            transition: width 0.3s ease;
        }
    </style>
</head>

<body class="bg-gray-50 font-inter overflow-x-hidden">
    <!-- Scroll Progress Indicator -->
    <div class="scroll-indicator" id="scrollIndicator"></div>

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 nav-blur border-b border-gray-200/50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center" data-aos="fade-right">
                    <div class="w-8 h-8 bg-transparent flex items-center justify-center mr-3 hover:scale-110 transition-transform duration-300">
                        {{-- <i class="fas fa-handshake text-white text-lg"></i> --}}
                        <img src="{{ asset('img/koperasi_logo.svg') }}" class="" alt="">
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        {{-- {{ config('app.name', 'KoperasiKu') }} --}}
                        KoperasiKu
                    </span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8" data-aos="fade-down">
                    <a href="#home" class="text-gray-600 hover:text-blue-600 transition-all duration-300 font-medium relative group">
                        Home
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#features" class="text-gray-600 hover:text-blue-600 transition-all duration-300 font-medium relative group">
                        Fitur
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                    <a href="#about" class="text-gray-600 hover:text-blue-600 transition-all duration-300 font-medium relative group">
                        Tentang
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-4" data-aos="fade-left">
                    @auth
                        <a href="{{ route('home') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 transition-all duration-300 font-medium">
                            Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <i class="fas fa-user-plus mr-2"></i>
                                Daftar
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="pt-20 pb-16 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 min-h-screen flex items-center relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full morphing-blob animate-float"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-br from-purple-400/20 to-pink-400/20 rounded-full morphing-blob animate-float-delayed"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-br from-yellow-400/10 to-orange-400/10 rounded-full animate-pulse-slow"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <div data-aos="fade-up" data-aos-delay="100">
                        <div class="inline-flex items-center px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full text-sm font-medium text-blue-600 border border-blue-200 mb-6 hover:bg-white transition-all duration-300">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            Sistem Terpercaya & Aman
                        </div>
                        
                        <h1 class="text-5xl lg:text-7xl font-black text-gray-900 mb-6 leading-tight">
                            Sistem Koperasi
                            <span class="text-shimmer block">
                                Modern
                            </span>
                            <span class="text-4xl lg:text-5xl font-bold bg-gradient-to-r from-gray-600 to-gray-800 bg-clip-text text-transparent">
                                untuk Masa Depan
                            </span>
                        </h1>
                    </div>
                    
                    <div data-aos="fade-up" data-aos-delay="200">
                        <p class="text-xl lg:text-2xl text-gray-600 mb-8 leading-relaxed font-light">
                            Kelola koperasi Anda dengan mudah dan efisien. Sistem manajemen terintegrasi untuk anggota, 
                            simpanan, pinjaman, dan laporan keuangan.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mb-12" data-aos="fade-up" data-aos-delay="300">
                        @auth
                            <a href="{{ route('home') }}" class="group bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-2xl font-bold hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:ring-blue-300 transition-all duration-300 transform hover:scale-105 shadow-2xl flex items-center justify-center text-lg relative overflow-hidden">
                                <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                <i class="fas fa-tachometer-alt mr-3 text-xl"></i>
                                Masuk ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-2xl font-bold hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:ring-blue-300 transition-all duration-300 transform hover:scale-105 shadow-2xl flex items-center justify-center text-lg relative overflow-hidden">
                                <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                <i class="fas fa-rocket mr-3 text-xl"></i>
                                Mulai Sekarang
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="group bg-white text-gray-800 px-8 py-4 rounded-2xl font-bold hover:bg-gray-50 border-2 border-gray-200 hover:border-gray-300 transition-all duration-300 flex items-center justify-center text-lg shadow-xl hover:shadow-2xl transform hover:scale-105">
                                    <i class="fas fa-user-plus mr-3 text-xl text-blue-600"></i>
                                    Daftar Gratis
                                </a>
                            @endif
                        @endauth
                    </div>

                    <!-- Enhanced Trust Indicators -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="flex items-center p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-white/20 hover:bg-white/80 transition-all duration-300 hover-lift">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Aman & Terpercaya</div>
                                <div class="text-sm text-gray-600">SSL Encryption</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-white/20 hover:bg-white/80 transition-all duration-300 hover-lift">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">1000+ Koperasi</div>
                                <div class="text-sm text-gray-600">Aktif Menggunakan</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center p-4 bg-white/60 backdrop-blur-sm rounded-xl border border-white/20 hover:bg-white/80 transition-all duration-300 hover-lift">
                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-star text-yellow-600 text-xl"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Rating 4.9/5</div>
                                <div class="text-sm text-gray-600">Kepuasan Pengguna</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Dashboard Preview -->
                <div class="relative" data-aos="fade-left" data-aos-delay="200">
                    <div class="relative">
                        <!-- Main Dashboard Card -->
                        <div class="glass-effect rounded-3xl p-8 shadow-2xl animate-float hover:shadow-3xl transition-all duration-500 border border-white/30">
                            <!-- Dashboard Header -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-3">
                                        <i class="fas fa-chart-line text-white"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900">Dashboard Koperasi</h3>
                                </div>
                                <div class="flex space-x-2">
                                    <div class="w-3 h-3 bg-red-400 rounded-full animate-pulse"></div>
                                    <div class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse" style="animation-delay: 0.2s;"></div>
                                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse" style="animation-delay: 0.4s;"></div>
                                </div>
                            </div>

                            <!-- Stats Cards -->
                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl text-white transform hover:scale-105 transition-all duration-300 shadow-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <i class="fas fa-users text-2xl opacity-80"></i>
                                        <div class="text-right">
                                            <div class="text-3xl font-black">1,234</div>
                                            <div class="text-blue-100 text-sm font-medium">Total Anggota</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center text-blue-100 text-sm">
                                        <i class="fas fa-arrow-up mr-1"></i>
                                        <span>+12% bulan ini</span>
                                    </div>
                                </div>
                                
                                <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-2xl text-white transform hover:scale-105 transition-all duration-300 shadow-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <i class="fas fa-piggy-bank text-2xl opacity-80"></i>
                                        <div class="text-right">
                                            <div class="text-3xl font-black">2.5M</div>
                                            <div class="text-green-100 text-sm font-medium">Total Simpanan</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center text-green-100 text-sm">
                                        <i class="fas fa-arrow-up mr-1"></i>
                                        <span>+8% bulan ini</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Activity Feed -->
                            <div class="space-y-4">
                                <h4 class="font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h4>
                                
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl hover:from-blue-100 hover:to-purple-100 transition-all duration-300 transform hover:scale-102">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-4 shadow-lg">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Ahmad Suryadi</div>
                                            <div class="text-sm text-gray-600">Anggota baru bergabung</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-green-600 font-bold">+Rp 500K</div>
                                        <div class="text-xs text-gray-500">2 menit lalu</div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl hover:from-purple-100 hover:to-pink-100 transition-all duration-300 transform hover:scale-102">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mr-4 shadow-lg">
                                            <i class="fas fa-chart-bar text-white"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">Laporan Bulanan</div>
                                            <div class="text-sm text-gray-600">Siap untuk review</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-blue-600 font-bold">Lihat</div>
                                        <div class="text-xs text-gray-500">5 menit lalu</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Decorative Elements -->
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-br from-pink-400 to-red-400 rounded-full opacity-20 animate-bounce-slow"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-400 rounded-full opacity-20 animate-bounce-slow" style="animation-delay: 1s;"></div>
                        <div class="absolute top-1/2 -right-4 w-16 h-16 bg-gradient-to-br from-green-400 to-blue-400 rounded-full opacity-20 animate-pulse-slow"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Features Section -->
    <section id="features" class="py-24 bg-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, #3b82f6 1px, transparent 0); background-size: 20px 20px;"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20" data-aos="fade-up">
                <div class="inline-flex items-center px-4 py-2 bg-blue-50 rounded-full text-sm font-medium text-blue-600 border border-blue-200 mb-6">
                    <i class="fas fa-star mr-2"></i>
                    Fitur Unggulan
                </div>
                <h2 class="text-5xl lg:text-6xl font-black text-gray-900 mb-6">
                    Fitur Lengkap untuk
                    <span class="text-shimmer block">
                        Koperasi Modern
                    </span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Semua yang Anda butuhkan untuk mengelola koperasi dengan efisien dan profesional dalam satu platform terintegrasi
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature Cards with Enhanced Animations -->
                <div class="card-hover p-8 bg-gradient-to-br from-blue-50 to-blue-100 rounded-3xl border border-blue-200/50 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Manajemen Anggota</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Kelola data anggota, registrasi, dan status keanggotaan dengan sistem yang mudah dan terintegrasi</p>
                    <div class="flex items-center text-blue-600 font-semibold group-hover:text-blue-700 transition-colors">
                        <span>Pelajari lebih lanjut</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>

                <div class="card-hover p-8 bg-gradient-to-br from-green-50 to-green-100 rounded-3xl border border-green-200/50 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fas fa-piggy-bank text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Simpan Pinjam</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Sistem simpanan dan pinjaman terintegrasi dengan perhitungan bunga otomatis dan laporan real-time</p>
                    <div class="flex items-center text-green-600 font-semibold group-hover:text-green-700 transition-colors">
                        <span>Pelajari lebih lanjut</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>

                <div class="card-hover p-8 bg-gradient-to-br from-purple-50 to-purple-100 rounded-3xl border border-purple-200/50 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fas fa-chart-bar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Laporan Keuangan</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Laporan keuangan real-time dan analisis performa koperasi dengan visualisasi data yang menarik</p>
                    <div class="flex items-center text-purple-600 font-semibold group-hover:text-purple-700 transition-colors">
                        <span>Pelajari lebih lanjut</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>

                <div class="card-hover p-8 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-3xl border border-yellow-200/50 group" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Mobile Friendly</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Akses sistem dari mana saja dengan tampilan responsif di semua perangkat mobile dan desktop</p>
                    <div class="flex items-center text-yellow-600 font-semibold group-hover:text-yellow-700 transition-colors">
                        <span>Pelajari lebih lanjut</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>

                <div class="card-hover p-8 bg-gradient-to-br from-red-50 to-red-100 rounded-3xl border border-red-200/50 group" data-aos="fade-up" data-aos-delay="500">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Keamanan Tinggi</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Enkripsi data dan sistem keamanan berlapis untuk melindungi informasi sensitif koperasi</p>
                    <div class="flex items-center text-red-600 font-semibold group-hover:text-red-700 transition-colors">
                        <span>Pelajari lebih lanjut</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>

                <div class="card-hover p-8 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-3xl border border-indigo-200/50 group" data-aos="fade-up" data-aos-delay="600">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                        <i class="fas fa-headset text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Support 24/7</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">Tim support profesional siap membantu Anda kapan saja dengan respon cepat dan solusi terbaik</p>
                    <div class="flex items-center text-indigo-600 font-semibold group-hover:text-indigo-700 transition-colors">
                        <span>Pelajari lebih lanjut</span>
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced About Section -->
    <section id="about" class="py-24 bg-gradient-to-br from-gray-50 to-blue-50 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-32 h-32 bg-blue-500 rounded-full animate-float"></div>
            <div class="absolute bottom-20 right-20 w-24 h-24 bg-purple-500 rounded-full animate-float-delayed"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- About Header -->
            <div class="text-center mb-20" data-aos="fade-up">
                <h2 class="text-5xl lg:text-6xl font-black text-gray-900 mb-6">Tentang KoperasiKu</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Kami berkomitmen untuk memajukan koperasi Indonesia melalui teknologi digital yang inovatif dan mudah digunakan
                </p>
            </div>

            <!-- Mission & Vision -->
            <div class="grid md:grid-cols-2 gap-12 mb-20">
                <div class="glass-effect p-10 rounded-3xl shadow-xl hover-lift" data-aos="fade-right">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl flex items-center justify-center mb-8 shadow-lg">
                        <i class="fas fa-eye text-white text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-6">Visi Kami</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Menjadi platform digital terdepan yang memberdayakan koperasi di seluruh Indonesia untuk 
                        berkembang dan memberikan manfaat maksimal bagi anggotanya melalui teknologi modern.
                    </p>
                </div>
                
                <div class="glass-effect p-10 rounded-3xl shadow-xl hover-lift" data-aos="fade-left">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-3xl flex items-center justify-center mb-8 shadow-lg">
                        <i class="fas fa-bullseye text-white text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-6">Misi Kami</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Menyediakan solusi teknologi yang mudah, aman, dan terjangkau untuk membantu koperasi dalam 
                        mengelola operasional dan meningkatkan kesejahteraan anggota secara berkelanjutan.
                    </p>
                </div>
            </div>

            <!-- Values -->
            <div class="text-center mb-16" data-aos="fade-up">
                <h3 class="text-4xl font-bold text-gray-900 mb-4">Nilai-Nilai Kami</h3>
                <p class="text-xl text-gray-600">Prinsip yang memandu setiap langkah kami</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-8 group-hover:scale-110 transition-transform duration-300 shadow-xl">
                        <i class="fas fa-handshake text-white text-4xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">Kekeluargaan</h4>
                    <p class="text-gray-600 leading-relaxed">Membangun hubungan yang kuat dan saling mendukung dengan semua stakeholder dalam ekosistem koperasi.</p>
                </div>
                
                <div class="text-center group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-8 group-hover:scale-110 transition-transform duration-300 shadow-xl">
                        <i class="fas fa-shield-alt text-white text-4xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">Kepercayaan</h4>
                    <p class="text-gray-600 leading-relaxed">Menjaga kepercayaan melalui transparansi dan keamanan data yang tinggi serta layanan yang konsisten.</p>
                </div>
                
                <div class="text-center group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-8 group-hover:scale-110 transition-transform duration-300 shadow-xl">
                        <i class="fas fa-rocket text-white text-4xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">Inovasi</h4>
                    <p class="text-gray-600 leading-relaxed">Terus berinovasi untuk memberikan solusi terbaik bagi koperasi modern dengan teknologi terdepan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Stats Section -->
    <section class="py-24 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 opacity-20">
            <div class="absolute top-10 left-10 w-40 h-40 bg-white rounded-full animate-pulse-slow"></div>
            <div class="absolute bottom-10 right-10 w-32 h-32 bg-white rounded-full animate-pulse-slow" style="animation-delay: 1s;"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-4">Dipercaya Ribuan Koperasi</h2>
                <p class="text-xl text-white/80">Bergabunglah dengan komunitas koperasi yang terus berkembang</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 text-center text-white">
                <div class="p-8 glass-dark rounded-3xl hover-lift" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-6xl font-black mb-4 text-shimmer">1000+</div>
                    <div class="text-2xl font-semibold mb-2">Koperasi Terdaftar</div>
                    <div class="text-white/70">Aktif menggunakan sistem kami</div>
                </div>
                
                <div class="p-8 glass-dark rounded-3xl hover-lift" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-6xl font-black mb-4 text-shimmer">50K+</div>
                    <div class="text-2xl font-semibold mb-2">Anggota Aktif</div>
                    <div class="text-white/70">Terdaftar dalam sistem</div>
                </div>
                
                <div class="p-8 glass-dark rounded-3xl hover-lift" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-6xl font-black mb-4 text-shimmer">99.9%</div>
                    <div class="text-2xl font-semibold mb-2">Uptime System</div>
                    <div class="text-white/70">Keandalan sistem terjamin</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced CTA Section -->
    <section class="py-24 bg-gray-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>
        
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8 relative z-10">
            <div data-aos="fade-up">
                <h2 class="text-5xl lg:text-6xl font-black text-white mb-6">
                    Siap Memulai Transformasi Digital
                    <span class="text-shimmer block">
                        Koperasi Anda?
                    </span>
                </h2>
                <p class="text-2xl text-gray-300 mb-12 leading-relaxed">
                    Bergabunglah dengan ribuan koperasi yang telah mempercayai sistem kami untuk masa depan yang lebih cerah
                </p>
            </div>

            @auth
                <div data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ route('home') }}" class="group inline-flex items-center bg-gradient-to-r from-blue-500 to-purple-600 text-white px-12 py-6 rounded-2xl font-bold hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:ring-blue-300 transition-all duration-300 transform hover:scale-105 shadow-2xl text-xl relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                        <i class="fas fa-tachometer-alt mr-3 text-2xl"></i>
                        Masuk ke Dashboard
                    </a>
                </div>
            @else
                <div class="flex flex-col sm:flex-row gap-6 justify-center" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ route('register') }}" class="group inline-flex items-center bg-gradient-to-r from-blue-500 to-purple-600 text-white px-12 py-6 rounded-2xl font-bold hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:ring-blue-300 transition-all duration-300 transform hover:scale-105 shadow-2xl text-xl relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                        <i class="fas fa-user-plus mr-3 text-2xl"></i>
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="group inline-flex items-center bg-white text-gray-900 px-12 py-6 rounded-2xl font-bold hover:bg-gray-100 transition-all duration-300 text-xl shadow-2xl hover:shadow-3xl transform hover:scale-105">
                        <i class="fas fa-sign-in-alt mr-3 text-2xl text-blue-600"></i>
                        Login
                    </a>
                </div>
            @endauth
        </div>
    </section>

    <!-- Enhanced Footer -->
    <footer class="bg-white border-t border-gray-200 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid md:grid-cols-4 gap-12">
                <div class="col-span-2" data-aos="fade-up">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-transparent] flex items-center justify-center mr-4 ">
                            {{-- <i class="fas fa-handshake text-white text-xl"></i> --}}
                            <img src="{{ asset('img/koperasi_logo.svg') }}" class="" alt="">
                            
                        </div>
                        <span class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            {{-- {{ config('app.name', 'KoperasiKu') }} --}}
                            KoperasiKu
                        </span>
                    </div>
                    <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                        Sistem manajemen koperasi modern yang membantu Anda mengelola koperasi dengan lebih efisien, 
                        profesional, dan menguntungkan untuk semua anggota.
                    </p>
                    <div class="flex space-x-6">
                        <a href="#" class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 hover:bg-blue-500 hover:text-white transition-all duration-300 transform hover:scale-110">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 hover:bg-blue-500 hover:text-white transition-all duration-300 transform hover:scale-110">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 hover:bg-blue-500 hover:text-white transition-all duration-300 transform hover:scale-110">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 hover:bg-blue-500 hover:text-white transition-all duration-300 transform hover:scale-110">
                            <i class="fab fa-linkedin-in text-lg"></i>
                        </a>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Produk</h3>
                    <ul class="space-y-4 text-gray-600">
                        <li><a href="#" class="hover:text-blue-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>Manajemen Anggota</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>Simpan Pinjam</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>Laporan Keuangan</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>Mobile App</a></li>
                    </ul>
                </div>

                <div data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Support</h3>
                    <ul class="space-y-4 text-gray-600">
                        <li><a href="#" class="hover:text-blue-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>Help Center</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>Kontak</a></li>
                        <li><a href="#" class="hover:text-blue-600 transition-colors flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i>Status System</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-12 pt-8 text-center text-gray-500">
                <p class="text-lg">© {{ date('Y') }} {{ config('app.name', 'KoperasiKu') }}. Semua hak dilindungi. Didukung oleh Teknologi Terkini.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript for Enhanced Interactions -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 100
        });

        // Scroll Progress Indicator
        window.addEventListener('scroll', () => {
            const scrolled = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
            document.getElementById('scrollIndicator').style.width = scrolled + '%';
        });

        // Navbar Background on Scroll
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('bg-white/95', 'shadow-lg');
                navbar.classList.remove('bg-white/80');
            } else {
                navbar.classList.add('bg-white/80');
                navbar.classList.remove('bg-white/95', 'shadow-lg');
            }
        });

        // Smooth Scrolling for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add hover effects to cards
        document.querySelectorAll('.card-hover').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Parallax effect for background elements
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.morphing-blob');
            
            parallaxElements.forEach(element => {
                const speed = 0.5;
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // Counter animation for stats
        function animateCounters() {
            const counters = document.querySelectorAll('.text-6xl');
            
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
                const increment = target / 100;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = counter.textContent.replace(/\d+/, target);
                        clearInterval(timer);
                    } else {
                        counter.textContent = counter.textContent.replace(/\d+/, Math.floor(current));
                    }
                }, 20);
            });
        }

        // Trigger counter animation when stats section is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        });

        const statsSection = document.querySelector('.text-6xl');
        if (statsSection) {
            observer.observe(statsSection.closest('section'));
        }
    </script>
</body>

</html>