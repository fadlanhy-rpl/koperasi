{{-- resources/views/layouts/partials/header.blade.php --}}
<header class="bg-white/80 backdrop-blur-lg shadow-md border-b border-gray-200/80 sticky top-0 z-30 animate-fade-in">
    <div class="flex items-center justify-between px-6 py-3.5">
        <div class="flex items-center space-x-4">
            <!-- Mobile Toggle Button -->
            <button id="mobile-toggle" onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
                <i class="fas fa-bars text-gray-600 text-lg"></i>
            </button>
            
            <div>
                <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                <p class="text-sm text-gray-500">@yield('page-subtitle', 'Selamat datang kembali!')</p>
            </div>
        </div>

        <div class="flex items-center space-x-4">
            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center space-x-2 bg-gray-100/50 rounded-full pl-1 pr-2 py-1 hover:bg-gray-200/70 transition-colors duration-300 cursor-pointer">
                        <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&color=fff&size=32&rounded=true&font-size=0.33&bold=true' }}"
                            alt="Foto Profil {{ Auth::user()->name }}"
                            class="w-8 h-8 md:w-9 md:h-9 rounded-full ring-1 ring-blue-300 object-cover">
                        <span
                            class="text-sm font-medium text-gray-700 hidden md:inline">{{ Str::limit(Auth::user()->name, 15) }}</span>
                        <i class="fas fa-chevron-down text-gray-500 text-xs transform transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl z-40 py-1 border border-gray-200"
                        style="display: none;">
                        <div class="px-4 py-3 border-b flex border-gray-100">
                            <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&color=fff&size=32&rounded=true&font-size=0.33&bold=true' }}"
                                alt="Foto Profil {{ Auth::user()->name }}"
                                class="w-8 h-8 md:w-9 md:h-9 rounded-full ring-1 ring-blue-300 object-cover">
                            <div class="translate-x-2">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600">Dashboard
                                Admin</a>
                            <a href="{{ route('admin.profile.index', ['section' => 'my_profile']) }}"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600">Profil
                                Saya</a>
                        @elseif(Auth::user()->isPengurus())
                            <a href="{{ route('pengurus.dashboard') }}"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600">Dashboard
                                Pengurus</a>
                        @elseif(Auth::user()->isAnggota())
                            <a href="{{ route('anggota.dashboard') }}"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600">Dashboard
                                Anggota</a>
                            <a href="{{ route('anggota.profil.show') }}"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-blue-600">Profil
                                Saya</a>
                        @endif
                        <div class="border-t border-gray-100"></div>
                        <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf</form>
                        <button onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();"
                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:font-medium">
                            Logout
                        </button>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>
