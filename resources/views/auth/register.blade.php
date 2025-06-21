@extends('layouts.app')

@section('title', 'Daftar - Sistem Koperasi')

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
        <div class="flex flex-col lg:flex-row min-h-[700px]">
            <!-- Left Side - Decorative -->
            <div class="lg:w-1/2 bg-gradient-to-br from-gray-900 via-green-900 to-teal-900 relative overflow-hidden flex items-center justify-center p-8">
                <!-- Animated Background Elements -->
                <div class="absolute inset-0">
                    <div class="absolute top-10 left-10 w-20 h-20 bg-green-500 rounded-full opacity-20 animate-pulse"></div>
                    <div class="absolute top-32 right-20 w-16 h-16 bg-teal-500 rounded-full opacity-20 animate-bounce"></div>
                    <div class="absolute bottom-20 left-32 w-24 h-24 bg-blue-500 rounded-full opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
                    <div class="absolute bottom-40 right-10 w-12 h-12 bg-emerald-500 rounded-full opacity-20 animate-bounce" style="animation-delay: 2s;"></div>
                </div>

                <!-- Floating Cards -->
                <div class="relative z-10 space-y-6">
                    <!-- Benefits Card -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-white font-medium">Keuntungan Bergabung</span>
                        </div>
                        <div class="space-y-2 text-white/90 text-sm">
                            <div>• Simpan pinjam mudah</div>
                            <div>• Belanja di toko koperasi</div>
                            <div>• Bagi hasil tahunan</div>
                        </div>
                    </div>

                    <!-- Statistics Card -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 transform -rotate-2 hover:rotate-0 transition-transform duration-300">
                        <div class="text-white/80 text-sm mb-2">Statistik Koperasi</div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-2xl font-bold text-white">1,247</div>
                                <div class="text-white/70 text-xs">Anggota</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-white">125M</div>
                                <div class="text-white/70 text-xs">Total Aset</div>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Card -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 transform rotate-1 hover:rotate-0 transition-transform duration-300">
                        <div class="flex items-center space-x-2 mb-3">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <span class="text-white font-medium">Terpercaya</span>
                        </div>
                        <div class="text-white/90 text-sm">
                            Berdiri sejak 1995, melayani ribuan anggota dengan komitmen tinggi
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Register Form -->
            <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
                <div class="max-w-md mx-auto w-full">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="flex items-center justify-center space-x-3 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h4M9 7h6m-6 4h6m-6 4h6"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">KOPERASI</span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">BERGABUNG DENGAN</h1>
                        <h2 class="text-3xl font-bold text-gray-900">KOPERASI KAMI</h2>
                    </div>

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <!-- Full Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input id="name" 
                                   type="text" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="name"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                                   placeholder="Masukkan nama lengkap">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                                   placeholder="anggota@email.com">
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
                                   autocomplete="new-password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror"
                                   placeholder="Buat password yang kuat">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <input id="password_confirmation" 
                                   type="password" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                                   placeholder="Ulangi password">
                        </div>

                        <!-- Terms Agreement -->
                        <div class="flex items-start space-x-3">
                            <input id="terms" 
                                   type="checkbox" 
                                   required
                                   class="mt-1 rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            <label for="terms" class="text-sm text-gray-600">
                                Saya setuju dengan <a href="#" class="text-green-600 hover:text-green-500">syarat dan ketentuan</a> serta <a href="#" class="text-green-600 hover:text-green-500">kebijakan privasi</a> koperasi
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-green-500 to-teal-600 text-white py-3 px-4 rounded-xl font-semibold hover:from-green-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200">
                            Daftar Sekarang
                        </button>
                    </form>

                    <!-- Login Link -->
                    <div class="text-center mt-8">
                        <span class="text-gray-600">Sudah punya akun? </span>
                        <a href="{{ route('login') }}" class="text-green-600 hover:text-green-500 font-medium">
                            Masuk di sini
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