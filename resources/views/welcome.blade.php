<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - {{ config('app.name', 'Koperasi Management System') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    },
                    // Anda bisa menambahkan warna custom jika diperlukan di halaman ini
                    // colors: {
                    //     primary: '#3B82F6',
                    //     secondary: '#6366F1',
                    // },
                    animation: { // Animasi sederhana untuk halaman welcome
                        'bounce-in': 'bounceIn 0.8s ease-out forwards',
                        'float': 'float 3s ease-in-out infinite'
                    },
                    keyframes: {
                         bounceIn: { '0%': { transform: 'scale(0.3)', opacity: '0' }, '50%': { transform: 'scale(1.05)' }, '70%': { transform: 'scale(0.9)' }, '100%': { transform: 'scale(1)', opacity: '1' } },
                         float: { '0%, 100%': { transform: 'translateY(0px)' }, '50%': { transform: 'translateY(-8px)' } }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-100 via-purple-50 to-pink-100 font-inter flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/40 p-8 md:p-12 text-center animate-bounce-in">
        <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg animate-float">
            <i class="fas fa-handshake text-white text-4xl"></i>
        </div>
        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-2">
            {{ config('app.name', 'KoperasiKu') }}
        </h1>
        <p class="text-gray-600 mb-8 text-lg">Sistem Manajemen Koperasi Modern</p>
        
        <div class="space-y-5">
            @auth
                {{-- Jika pengguna sudah login, arahkan ke dashboard mereka --}}
                <a href="{{ route('home') }}" 
                   class="w-full block bg-gradient-to-r from-green-500 to-teal-600 text-white py-3.5 px-6 rounded-xl font-semibold hover:from-green-600 hover:to-teal-700 focus:ring-4 focus:ring-green-300 transition-all duration-300 transform hover:scale-105 shadow-xl flex items-center justify-center text-lg">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Masuk ke Dashboard
                </a>
            @else
                {{-- Jika pengguna belum login, tampilkan tombol Login dan Register --}}
                <a href="{{ route('login') }}" 
                   class="w-full block bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3.5 px-6 rounded-xl font-semibold hover:from-blue-600 hover:to-purple-700 focus:ring-4 focus:ring-blue-300 transition-all duration-300 transform hover:scale-105 shadow-xl flex items-center justify-center text-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login ke Sistem
                </a>
                
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" 
                       class="w-full block bg-gray-200 text-gray-800 py-3.5 px-6 rounded-xl font-semibold hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 transition-colors duration-300 flex items-center justify-center text-lg">
                        <i class="fas fa-user-plus mr-2"></i>
                        Daftar Anggota Baru
                    </a>
                @endif
            @endauth
        </div>
        
        <div class="mt-10 text-center">
            <p class="text-sm text-gray-500">
                © {{ date('Y') }} {{ config('app.name', 'KoperasiKu') }}. Didukung oleh Teknologi Terkini. {{-- Pastikan baris ini lengkap --}}
            </p>
        </div>
    </div>
</body>
</html>