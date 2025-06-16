{{-- Kode sidebar_anggota.blade.php SAMA SEPERTI SEBELUMNYA --}}

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
    <nav class="mt-6 flex-grow">
        <div class="mb-4">
            <div class="px-6 py-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">MAIN</p>
            </div>
            
            <a href="{{ route('anggota.dashboard') }}" class="nav-item {{ request()->routeIs('anggota.dashboard') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('anggota.dashboard') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i class="fas fa-tachometer-alt w-5 h-5 mr-3 {{ request()->routeIs('anggota.dashboard') ? '' : 'text-gray-400' }}"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            
            <a href="{{ route('anggota.profil.show') }}" class="nav-item {{ request()->routeIs('anggota.profil.*') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('anggota.profil.*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i class="fas fa-user-circle w-5 h-5 mr-3 {{ request()->routeIs('anggota.profil.*') ? '' : 'text-gray-400' }}"></i>
                <span>Profil Saya</span>
            </a>
            
            <div class="px-6 py-2 mt-6">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">FINANSIAL</p>
            </div>
            
            <a href="{{ route('anggota.simpanan.show') }}" class="nav-item {{ request()->routeIs('anggota.simpanan.show') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('anggota.simpanan.show') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i class="fas fa-wallet w-5 h-5 mr-3 {{ request()->routeIs('anggota.simpanan.show') ? '' : 'text-gray-400' }}"></i>
                <span>Informasi Simpanan</span>
            </a>
            
            <div class="px-6 py-2 mt-6">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">TRANSAKSI</p>
            </div>
            
            <a href="{{ route('anggota.pembelian.katalog') }}" class="nav-item {{ request()->routeIs('anggota.pembelian.katalog') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('anggota.pembelian.katalog') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i class="fas fa-store-alt w-5 h-5 mr-3 {{ request()->routeIs('anggota.pembelian.katalog') ? '' : 'text-gray-400' }}"></i>
                <span>Katalog Barang</span>
            </a>
            
            <a href="{{ route('anggota.pembelian.riwayat') }}" class="nav-item {{ request()->routeIs('anggota.pembelian.riwayat') || request()->routeIs('anggota.pembelian.detail') ? 'active' : '' }} flex items-center px-6 py-3 {{ request()->routeIs('anggota.pembelian.riwayat') || request()->routeIs('anggota.pembelian.detail') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                <i class="fas fa-receipt w-5 h-5 mr-3 {{ request()->routeIs('anggota.pembelian.riwayat') || request()->routeIs('anggota.pembelian.detail') ? '' : 'text-gray-400' }}"></i>
                <span>Riwayat Pembelian</span>
            </a>
        </div>
        
        <!-- Quick Stats Info Simpanan -->
        <div class="mx-6 mt-auto mb-6 p-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl text-white animate-float flex-shrink-0">
            <h3 class="font-semibold mb-3 flex items-center">
                <i class="fas fa-info-circle mr-2 text-yellow-300"></i>
                Info Simpanan Anda
            </h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="opacity-90">Simpanan Pokok</span>
                    <span class="font-bold">@rupiah($sidebarSimpananPokok ?? 0)</span>
                </div>
                <div class="flex justify-between">
                    <span class="opacity-90">Total Simp. Wajib</span>
                    <span class="font-bold">@rupiah($sidebarTotalSimpananWajib ?? 0)</span>
                </div>
                <div class="flex justify-between">
                    <span class="opacity-90">Saldo Simp. Sukarela</span>
                    <span class="font-bold">@rupiah($sidebarSaldoSimpananSukarela ?? 0)</span>
                </div>
            </div>
        </div>

        <!-- Logout -->
        <div class="px-6 pt-3 pb-6 border-t border-gray-200 flex-shrink-0">
             <a href="{{ route('logout') }}" 
                onclick="event.preventDefault(); document.getElementById('logout-form-sidebar-anggota').submit();" 
                class="nav-item flex items-center px-6 py-3 text-gray-700 hover:text-red-600 hover:bg-red-50 rounded-lg">
                <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-gray-400 group-hover:text-red-500"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form-sidebar-anggota" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </nav>
</div>