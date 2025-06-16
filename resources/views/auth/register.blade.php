<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota - {{ config('app.name', 'Koperasi Management System') }}</title>
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
<body class="bg-gradient-to-br from-blue-100 to-purple-100 font-inter flex items-center justify-center min-h-screen p-4 py-8">
    <div class="max-w-lg w-full bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/30 p-8 md:p-10">
        <div class="text-center mb-8">
             <a href="{{ route('welcome') }}" class="inline-block mb-4">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl flex items-center justify-center mx-auto shadow-lg animate-float">
                    <i class="fas fa-user-plus text-white text-3xl"></i>
                </div>
            </a>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-1">
                Pendaftaran Anggota Baru
            </h1>
            <p class="text-gray-600">Isi form untuk bergabung dengan koperasi.</p>
        </div>
        
        @include('layouts.partials._alerts') {{-- Memanggil partials alerts --}}
            
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            
            <x-forms.input type="text" name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap Anda" :required="true" />
            
            <x-forms.input type="email" name="email" label="Alamat Email" placeholder="contoh@email.com" :required="true" />
            
            {{-- Nomor Anggota di backend kita adalah 'nomor_anggota' dan opsional saat registrasi --}}
            <x-forms.input type="text" name="nomor_anggota" label="No. Anggota (Opsional)" placeholder="Kosongkan jika belum ada" /> 
            
            <x-forms.input type="password" name="password" label="Password" placeholder="Minimal 8 karakter" :required="true" />
            
            <x-forms.input type="password" name="password_confirmation" label="Konfirmasi Password" placeholder="Ulangi password" :required="true" />
            
            <x-forms.button type="submit" variant="primary" size="md" class="w-full text-lg py-3.5" icon="user-plus">
                Daftar Sekarang
            </x-forms.button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-800">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</body>
</html>