<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Koperasi XYZ') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> {{-- Hasil kompilasi Tailwind --}}

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script> {{-- Untuk Alpine.js atau JS custom --}}
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Jika menggunakan Vite --}}

    @stack('styles') {{-- Untuk CSS spesifik per halaman --}}
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-30 flex-shrink-0 w-64 overflow-y-auto transition-transform duration-300 ease-in-out transform bg-white shadow-lg dark:bg-gray-800 lg:static lg:inset-auto lg:translate-x-0"
            :class="{'-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen}"
            @click.away="sidebarOpen = false"
            x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform -translate-x-full"
            aria-label="Sidebar"
        >
            <div class="flex items-center justify-center h-20 px-6 bg-green-600 dark:bg-green-700">
                <a href="{{ route('home') }}" class="text-2xl font-semibold text-white">
                    Koperasi XYZ
                </a>
            </div>
            <nav class="mt-4">
                @auth
                    @if(Auth::user()->isAdmin())
                        @include('layouts.partials._sidebar_admin')
                    @elseif(Auth::user()->isPengurus())
                        @include('layouts.partials._sidebar_pengurus')
                    @elseif(Auth::user()->isAnggota())
                        @include('layouts.partials._sidebar_anggota')
                    @endif
                @endauth
            </nav>
        </aside>

        <!-- Backdrop untuk mobile sidebar -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-20 bg-black opacity-50 lg:hidden" @click="sidebarOpen = false"></div>

        <div class="flex flex-col flex-1 w-full overflow-y-auto">
            <!-- Header -->
            @include('layouts.partials._header')

            <!-- Main content -->
            <main class="flex-1 p-6">
                @if (session('success'))
                    <div class="px-4 py-3 mb-4 text-sm text-green-700 bg-green-100 border border-green-400 rounded dark:bg-green-700 dark:text-green-100" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="px-4 py-3 mb-4 text-sm text-red-700 bg-red-100 border border-red-400 rounded dark:bg-red-700 dark:text-red-100" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="px-4 py-3 mb-4 text-sm text-red-700 bg-red-100 border border-red-400 rounded dark:bg-red-700 dark:text-red-100" role="alert">
                        <p class="font-bold">Oops! Ada beberapa kesalahan:</p>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer (opsional) -->
            <footer class="py-4 text-center text-gray-600 dark:text-gray-400">
                © {{ date('Y') }} {{ config('app.name', 'Koperasi XYZ') }}. All rights reserved.
                @include('layouts.partials._footer') {{-- Jika ada konten footer spesifik --}}
            </footer>
        </div>
    </div>

    @stack('modals') {{-- Untuk modal yang mungkin digunakan di berbagai halaman --}}
    @stack('scripts') {{-- Untuk JS spesifik per halaman --}}
</body>
</html>