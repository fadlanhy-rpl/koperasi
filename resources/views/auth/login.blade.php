@extends('layouts.app')

@section('title', 'Masuk - Sistem Koperasi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center p-4">
    <!-- Back to Home Button -->
    <div class="absolute top-6 left-6 z-20">
        <a href="{{ route('welcome') }}" 
           class="inline-flex items-center space-x-2 px-4 py-2 bg-white/80 backdrop-blur-sm text-gray-700 rounded-full hover:bg-white hover:shadow-lg transition-all duration-200 group">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="font-medium">Kembali ke Beranda</span>
        </a>
    </div>

    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="flex flex-col lg:flex-row min-h-[600px]">
            <!-- Left Side - Decorative -->
            <div class="lg:w-1/2 bg-gradient-to-br from-gray-900 via-blue-900 to-indigo-900 relative overflow-hidden flex items-center justify-center p-8">
                <!-- Animated Background Elements -->
                <div class="absolute inset-0">
                    <div class="absolute top-10 left-10 w-20 h-20 bg-blue-500 rounded-full opacity-20 animate-pulse"></div>
                    <div class="absolute top-32 right-20 w-16 h-16 bg-purple-500 rounded-full opacity-20 animate-bounce"></div>
                    <div class="absolute bottom-20 left-32 w-24 h-24 bg-teal-500 rounded-full opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
                    <div class="absolute bottom-40 right-10 w-12 h-12 bg-pink-500 rounded-full opacity-20 animate-bounce" style="animation-delay: 2s;"></div>
                </div>

                <!-- Floating Cards -->
                <div class="relative z-10 space-y-6">
                    <!-- Revenue Card -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-white/80 text-sm">Total Simpanan</span>
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-white">Rp 125.500.000</div>
                        <div class="w-full bg-white/20 rounded-full h-2 mt-3">
                            <div class="bg-green-400 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>

                    <!-- Members Card -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 transform -rotate-2 hover:rotate-0 transition-transform duration-300">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span class="text-white font-medium">Anggota Aktif</span>
                        </div>
                        <div class="text-xl font-bold text-white">1,247 Anggota</div>
                        <div class="flex -space-x-2 mt-3">
                            <div class="w-8 h-8 bg-red-400 rounded-full border-2 border-white"></div>
                            <div class="w-8 h-8 bg-blue-400 rounded-full border-2 border-white"></div>
                            <div class="w-8 h-8 bg-green-400 rounded-full border-2 border-white"></div>
                            <div class="w-8 h-8 bg-purple-400 rounded-full border-2 border-white flex items-center justify-center text-xs text-white font-bold">+</div>
                        </div>
                    </div>

                    <!-- Services Card -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 transform rotate-1 hover:rotate-0 transition-transform duration-300">
                        <div class="text-white/80 text-sm mb-2">Layanan Koperasi</div>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                <span class="text-white text-sm">Simpan Pinjam</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                                <span class="text-white text-sm">Toko Koperasi</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-purple-400 rounded-full"></div>
                                <span class="text-white text-sm">Unit Usaha</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
                <div class="max-w-md mx-auto w-full">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="flex items-center justify-center space-x-3 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">KOPERASI</span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">SELAMAT DATANG</h1>
                        <h2 class="text-3xl font-bold text-gray-900">DI SISTEM KOPERASI</h2>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="email"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                                   placeholder="anggota@koperasi.com">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror"
                                   placeholder="••••••••">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center">
                                <input id="remember_me" 
                                       type="checkbox" 
                                       name="remember" 
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-500">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200">
                            Masuk
                        </button>
                    </form>

                    <!-- Register Link -->
                    <div class="text-center mt-8">
                        <span class="text-gray-600">Belum punya akun? </span>
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                            Daftar sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(1deg); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>
@endsection