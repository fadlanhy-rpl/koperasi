<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Koperasi Management System') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = { /* Salin konfigurasi Tailwind dari app.blade.php jika perlu konsistensi warna/font */
             theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    },
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
<body class="bg-gradient-to-br from-blue-100 to-purple-100 font-inter flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/30 p-8 md:p-10">
        <div class="text-center mb-8">
            <a href="{{ route('welcome') }}" class="inline-block mb-4">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl flex items-center justify-center mx-auto shadow-lg animate-float">
                    <i class="fas fa-handshake text-white text-3xl"></i>
                </div>
            </a>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-1">
                Login Anggota
            </h1>
            <p class="text-gray-600">Masuk untuk mengakses akun koperasi Anda.</p>
        </div>
        
        @include('layouts.partials._alerts') {{-- Memanggil partials alerts --}}
        
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 placeholder-gray-400" 
                           placeholder="contoh@email.com" required>
                </div>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                 <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-lock text-gray-400"></i>
                    </span>
                    <input type="password" id="password" name="password" 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 placeholder-gray-400" 
                           placeholder="Masukkan password" required>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat Saya</label>
                </div>
                
                @if (Route::has('password.request')) {{-- Anda perlu membuat fitur lupa password jika ini diaktifkan --}}
                    {{-- <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Lupa password?
                    </a> --}}
                @endif
            </div>
            
            <x-forms.button type="submit" variant="primary" size="md" class="w-full text-lg py-3.5" icon="sign-in-alt">
                Masuk
            </x-forms.button>
        </form>
        
        @if (Route::has('register'))
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-800">
                    Daftar sekarang
                </a>
            </p>
        </div>
        @endif
    </div>
</body>
</html>