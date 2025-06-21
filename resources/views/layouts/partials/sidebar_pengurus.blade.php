{{-- Kode sidebar_pengurus.blade.php dengan Toggle Button --}}
<style>
    .nav-item {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
        transition: left 0.5s;
    }

    .nav-item:hover::before {
        left: 100%;
    }

    /* Mobile Sidebar Styles */
    .mobile-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
    }

    .mobile-sidebar.open {
        transform: translateX(0);
    }

    /* Overlay Styles */
    .sidebar-overlay {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease-in-out;
    }

    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }
</style>

@push('style')
@endpush

<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="sidebar-overlay fixed inset-0 z-30 lg:hidden" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<div id="sidebar-pengurus"
    class="mobile-sidebar w-64 bg-white/80 backdrop-blur-lg shadow-2xl fixed inset-y-0 left-0 z-40 h-full overflow-y-auto border-r border-white/20 animate-slide-in hidden lg:flex flex-col">
    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-100 flex-shrink-0">
        <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
            <div
                class="w-8 h-8 bg-transparent flex items-center justify-center hover:scale-110 transition-transform duration-300">
                {{-- <i class="fas fa-handshake text-white text-lg"></i> --}}
                <img src="{{ asset('img/koperasi_logo.svg') }}" class="" alt="">
            </div>
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                {{-- {{ config('app.name', 'KoperasiKu') }} --}}
                KoperasiKu
            </span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 flex-grow">
        <div>
            <div class="px-6 py-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">MAIN</p>
            </div>

            <a href="{{ route('pengurus.dashboard') }}"
                class="nav-item {{ request()->routeIs('pengurus.dashboard') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.dashboard') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i
                    class="fas fa-chart-pie w-5 h-5 mr-3 {{ request()->routeIs('pengurus.dashboard') ? '' : 'text-gray-400' }}"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('pengurus.unit-usaha.index') }}"
                class="nav-item {{ request()->routeIs('pengurus.unit-usaha.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.unit-usaha.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i
                    class="fas fa-store w-5 h-5 mr-3 {{ request()->routeIs('pengurus.unit-usaha.*') ? '' : 'text-gray-400' }}"></i>
                <span>Unit Usaha</span>
            </a>

            <a href="{{ route('pengurus.barang.index') }}"
                class="nav-item {{ request()->routeIs('pengurus.barang.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.barang.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i
                    class="fas fa-boxes w-5 h-5 mr-3 {{ request()->routeIs('pengurus.barang.*') ? '' : 'text-gray-400' }}"></i>
                <span>Manajemen Barang</span>
            </a>

            <a href="{{ route('pengurus.stok.index') }}"
                class="nav-item {{ request()->routeIs('pengurus.stok.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.stok.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i
                    class="fas fa-archive w-5 h-5 mr-3 {{ request()->routeIs('pengurus.stok.*') ? '' : 'text-gray-400' }}"></i>
                <span>Pencatatan Stok</span>
            </a>

            <div class="px-6 py-2 mt-6">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">SIMPANAN</p>
            </div>

            <a href="{{ route('pengurus.simpanan.pokok.index') }}"
                class="nav-item {{ request()->routeIs('pengurus.simpanan.pokok.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.simpanan.pokok.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i
                    class="fas fa-money-check-alt w-5 h-5 mr-3 {{ request()->routeIs('pengurus.simpanan.pokok.*') ? '' : 'text-gray-400' }}"></i>
                <span>Simpanan Pokok</span>
            </a>

            <a href="{{ route('pengurus.simpanan.wajib.index') }}"
                class="nav-item {{ request()->routeIs('pengurus.simpanan.wajib.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.simpanan.wajib.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i
                    class="fas fa-calendar-alt w-5 h-5 mr-3 {{ request()->routeIs('pengurus.simpanan.wajib.*') ? '' : 'text-gray-400' }}"></i>
                <span>Simpanan Wajib</span>
            </a>

            <a href="{{ route('pengurus.simpanan.sukarela.index') }}"
                class="nav-item {{ request()->routeIs('pengurus.simpanan.sukarela.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.simpanan.sukarela.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i
                    class="fas fa-hand-holding-heart w-5 h-5 mr-3 {{ request()->routeIs('pengurus.simpanan.sukarela.*') ? '' : 'text-gray-400' }}"></i>
                <span>Simpanan Sukarela</span>
            </a>

            <div class="px-6 py-2 mt-6">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">TRANSAKSI</p>
            </div>

            <a href="{{ route('pengurus.transaksi-pembelian.index') }}"
                class="nav-item {{ request()->routeIs('pengurus.transaksi-pembelian.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.transaksi-pembelian.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i
                    class="fas fa-cash-register w-5 h-5 mr-3 {{ request()->routeIs('pengurus.transaksi-pembelian.*') ? '' : 'text-gray-400' }}"></i>
                <span>Transaksi Pembelian</span>
            </a>

            <a href="{{ route('pengurus.pembayaran-cicilan.index') }}"
                class="nav-item {{ request()->routeIs('pengurus.pembayaran-cicilan.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.pembayaran-cicilan.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i
                    class="fas fa-credit-card w-5 h-5 mr-3 {{ request()->routeIs('pengurus.pembayaran-cicilan.*') ? '' : 'text-gray-400' }}"></i>
                <span>Pembayaran Cicilan</span>
            </a>

            <div class="px-6 py-2 mt-6">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">LAPORAN</p>
            </div>

            <a href="{{ route('pengurus.laporan.penjualan.umum') }}"
                class="nav-item {{ request()->routeIs('pengurus.laporan.penjualan.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.laporan.penjualan.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i
                    class="fas fa-file-invoice-dollar w-5 h-5 mr-3 {{ request()->routeIs('pengurus.laporan.penjualan.*') ? '' : 'text-gray-400' }}"></i>
                <span>Laporan Penjualan</span>
            </a>
        </div>

        <!-- Quick Stats -->
        <div
            class="mx-6 mt-auto mb-6 p-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl text-white animate-float flex-shrink-0">
            <h3 class="font-semibold mb-3 flex items-center">
                <i class="fas fa-bolt mr-2 text-yellow-300"></i>
                Quick Stats
            </h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="opacity-90">Total Anggota</span>
                    <span class="font-bold">{{ $sidebarTotalAnggota ?? '0' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="opacity-90">Total Simpanan</span>
                    <span class="font-bold">@rupiah($sidebarTotalSimpanan ?? 0)</span>
                </div>
                <div class="flex justify-between">
                    <span class="opacity-90">Penjualan Hari Ini</span>
                    <span class="font-bold text-green-300">@rupiah($sidebarPenjualanHariIni ?? 0)</span>
                </div>
            </div>
        </div>

        <!-- Logout -->
        <div class="px-6 pt-3 pb-6 border-t border-gray-200 flex-shrink-0">
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form-sidebar-pengurus').submit();"
                class="nav-item flex items-center px-6 py-3 text-gray-700 hover:text-red-600 hover:bg-red-50 rounded-lg">
                <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-gray-400 group-hover:text-red-500"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form-sidebar-pengurus" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </nav>
</div>
