
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
</style>
@push('style')

<div class="w-64 bg-white/80 backdrop-blur-lg shadow-2xl fixed inset-y-0 left-0 z-40 h-full overflow-y-auto border-r border-white/20 animate-slide-in hidden lg:flex flex-col">
    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-100 flex-shrink-0">
        <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300 animate-glow">
                <i class="fas fa-handshake text-white text-lg"></i>
            </div>
            <div>
                <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Koperasi</span>
                <p class="text-xs text-gray-500">Management System</p>
            </div>
        </a>
    </div>
    
    <!-- Navigation -->
    <nav class="mt-6 mb-4 flex-grow">
        <div class="px-6 py-2">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">MAIN</p>
        </div>
        
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
            <i class="fas fa-chart-pie w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? '' : 'text-gray-400' }}"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        
        <a href="{{ route('admin.manajemen-pengguna.index') }}" class="nav-item {{ request()->routeIs('admin.manajemen-pengguna.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('admin.manajemen-pengguna.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
            <i class="fas fa-users-cog w-5 h-5 mr-3 {{ request()->routeIs('admin.manajemen-pengguna.*') ? '' : 'text-gray-400' }}"></i>
            <span>Manajemen Pengguna</span>
        </a>
        
        <a href="{{ route('pengurus.unit-usaha.index') }}" class="nav-item {{ request()->routeIs('pengurus.unit-usaha.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.unit-usaha.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
            <i class="fas fa-store w-5 h-5 mr-3 {{ request()->routeIs('pengurus.unit-usaha.*') ? '' : 'text-gray-400' }}"></i>
            <span>Manajemen Unit Usaha</span>
        </a>
         {{-- <a href="{{ route('pengurus.barang.index') }}" class="nav-item {{ request()->routeIs('pengurus.barang.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.barang.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
            <i class="fas fa-boxes w-5 h-5 mr-3 {{ request()->routeIs('pengurus.barang.*') ? '' : 'text-gray-400' }}"></i>
            <span>Manajemen Barang</span>
        </a> --}}
        
        <div class="px-6 py-2 mt-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">LAPORAN</p>
        </div>
        
        <a href="{{ route('pengurus.laporan.penjualan.umum') }}" class="nav-item {{ request()->routeIs('pengurus.laporan.penjualan.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.laporan.penjualan.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
            <i class="fas fa-file-invoice-dollar w-5 h-5 mr-3 {{ request()->routeIs('pengurus.laporan.penjualan.*') ? '' : 'text-gray-400' }}"></i>
            <span>Laporan Penjualan</span>
        </a>
        
        <a href="{{ route('pengurus.laporan.simpanan.rekapTotal') }}" class="nav-item {{ request()->routeIs('pengurus.laporan.simpanan.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.laporan.simpanan.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
            <i class="fas fa-coins w-5 h-5 mr-3 {{ request()->routeIs('pengurus.laporan.simpanan.*') ? '' : 'text-gray-400' }}"></i>
            <span>Laporan Simpanan</span>
        </a>
        
        <a href="{{ route('pengurus.laporan.stok.daftarTerkini') }}" class="nav-item {{ request()->routeIs('pengurus.laporan.stok.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('pengurus.laporan.stok.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
            <i class="fas fa-archive w-5 h-5 mr-3 {{ request()->routeIs('pengurus.laporan.stok.*') ? '' : 'text-gray-400' }}"></i>
            <span>Laporan Stok</span>
        </a>
        
        <div class="px-6 py-2 mt-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">PENGATURAN</p>
        </div>
        
        {{-- Link ke Halaman Pengaturan Sistem BARU --}}
        <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('admin.settings.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
            <i class="fas fa-cogs w-5 h-5 mr-3 {{ request()->routeIs('admin.settings.*') ? '' : 'text-gray-400' }}"></i>
            <span>Pengaturan Sistem</span>
        </a>
    </nav>
    
    <!-- Quick Stats in Sidebar -->
    <div class="mx-6 mt-auto mb-6 p-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl text-white animate-float flex-shrink-0">
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
            onclick="event.preventDefault(); document.getElementById('logout-form-sidebar-admin').submit();" 
            class="nav-item flex items-center px-6 py-3 text-gray-700 hover:text-red-600 hover:bg-red-50 rounded-lg">
            <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-gray-400 group-hover:text-red-500"></i>
            <span>Logout</span>
        </a>
        <form id="logout-form-sidebar-admin" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</div>

