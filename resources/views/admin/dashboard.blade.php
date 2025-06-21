@extends('layouts.app')

@section('title', 'Dashboard Admin - Koperasi')

@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Kelola sistem koperasi secara menyeluruh')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header with Real-time Clock -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 rounded-3xl p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-48 translate-x-48"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-32 -translate-x-32"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-6 lg:mb-0">
                <h1 class="text-4xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-blue-100 text-lg">Kelola sistem koperasi dengan mudah dan efisien</p>
                <div class="flex items-center mt-4 space-x-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar-alt text-blue-200"></i>
                        <span class="text-blue-100" id="current-date"></span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-clock text-blue-200"></i>
                        <span class="text-blue-100 font-mono" id="current-time"></span>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col items-center lg:items-end">
                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mb-4 animate-pulse-slow">
                    <i class="fas fa-crown text-4xl text-yellow-300"></i>
                </div>
                <span class="text-blue-100 font-semibold">Administrator</span>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Cards with Animations -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="group relative bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white overflow-hidden transform hover:scale-105 transition-all duration-300 animate-fade-in" style="animation-delay: 0.1s">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users-cog text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold">{{ $totalPengguna }}</div>
                        <div class="text-purple-200 text-sm">+5% dari bulan lalu</div>
                    </div>
                </div>
                <h3 class="font-semibold text-lg">Total Pengguna</h3>
                <div class="mt-3 bg-white/20 rounded-full h-2">
                    <div class="bg-white rounded-full h-2 w-[90%] animate-pulse"></div>
                </div>
            </div>
        </div>

        <div class="group relative bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl p-6 text-white overflow-hidden transform hover:scale-105 transition-all duration-300 animate-fade-in" style="animation-delay: 0.2s">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-store text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold">{{ $unitUsahaAktif }}</div>
                        <div class="text-indigo-200 text-sm">Semua aktif</div>
                    </div>
                </div>
                <h3 class="font-semibold text-lg">Unit Usaha Aktif</h3>
                <div class="mt-3 bg-white/20 rounded-full h-2">
                    <div class="bg-white rounded-full h-2 w-full animate-pulse"></div>
                </div>
            </div>
        </div>

        <div class="group relative bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-white overflow-hidden transform hover:scale-105 transition-all duration-300 animate-fade-in" style="animation-delay: 0.3s">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-receipt text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold">{{ number_format($totalTransaksi) }}</div>
                        <div class="text-emerald-200 text-sm">+18% dari bulan lalu</div>
                    </div>
                </div>
                <h3 class="font-semibold text-lg">Total Transaksi</h3>
                <div class="mt-3 bg-white/20 rounded-full h-2">
                    <div class="bg-white rounded-full h-2 w-[82%] animate-pulse"></div>
                </div>
            </div>
        </div>

        <div class="group relative bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl p-6 text-white overflow-hidden transform hover:scale-105 transition-all duration-300 animate-fade-in" style="animation-delay: 0.4s">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold">{{ 'Rp ' . number_format($totalPendapatanKotor, 0, ',', '.') }}</div>
                        <div class="text-amber-200 text-sm">+22% dari bulan lalu</div>
                    </div>
                </div>
                <h3 class="font-semibold text-lg">Pendapatan Total</h3>
                <div class="mt-3 bg-white/20 rounded-full h-2">
                    <div class="bg-white rounded-full h-2 w-[95%] animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Quick Actions -->
    <div class="bg-white/80 backdrop-blur-lg rounded-3xl p-8 shadow-xl border border-white/20">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Aksi Cepat</h2>
                <p class="text-gray-600">Akses fitur utama dengan satu klik</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-bolt text-white text-xl"></i>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('admin.manajemen-pengguna.create') }}" class="group relative bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-500 hover:to-blue-600 rounded-2xl p-6 transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-blue-500 group-hover:bg-white/20 rounded-2xl flex items-center justify-center mb-4 transition-all duration-300">
                        <i class="fas fa-user-plus text-white group-hover:text-white text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-blue-900 group-hover:text-white transition-colors duration-300">Tambah Pengguna</h3>
                    <p class="text-blue-700 group-hover:text-blue-100 text-sm mt-2 transition-colors duration-300">Daftarkan pengguna baru</p>
                </div>
            </a>
            
            <a href="{{ route('pengurus.laporan.penjualan.umum') }}" class="group relative bg-gradient-to-br from-green-50 to-green-100 hover:from-green-500 hover:to-green-600 rounded-2xl p-6 transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-green-500 group-hover:bg-white/20 rounded-2xl flex items-center justify-center mb-4 transition-all duration-300">
                        <i class="fas fa-chart-bar text-white group-hover:text-white text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-green-900 group-hover:text-white transition-colors duration-300">Lihat Laporan</h3>
                    <p class="text-green-700 group-hover:text-green-100 text-sm mt-2 transition-colors duration-300">Analisis kinerja sistem</p>
                </div>
            </a>
            
            <a href="{{ route('admin.profile.index') }}" class="group relative bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-500 hover:to-purple-600 rounded-2xl p-6 transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-purple-500 group-hover:bg-white/20 rounded-2xl flex items-center justify-center mb-4 transition-all duration-300">
                        <i class="fas fa-user text-white group-hover:text-white text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-purple-900 group-hover:text-white transition-colors duration-300">profile</h3>
                    <p class="text-purple-700 group-hover:text-purple-100 text-sm mt-2 transition-colors duration-300">Kelola Profile</p>
                </div>
            </a>
            
            <a href="#" class="group relative bg-gradient-to-br from-orange-50 to-orange-100 hover:from-orange-500 hover:to-orange-600 rounded-2xl p-6 transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-orange-500 group-hover:bg-white/20 rounded-2xl flex items-center justify-center mb-4 transition-all duration-300">
                        <i class="fas fa-database text-white group-hover:text-white text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg text-orange-900 group-hover:text-white transition-colors duration-300">Backup Data</h3>
                    <p class="text-orange-700 group-hover:text-orange-100 text-sm mt-2 transition-colors duration-300">Cadangkan data sistem</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Enhanced System Overview and Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- System Activities -->
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl p-8 shadow-xl border border-white/20">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Aktivitas Sistem</h3>
                    <p class="text-gray-600">Ringkasan aktivitas real-time</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-activity text-white text-xl"></i>
                </div>
            </div>
            
            <div class="space-y-6">
                @forelse($aktivitasSistem as $index => $aktivitas)
                <div class="group flex items-center justify-between p-6 bg-gradient-to-r from-gray-50 to-gray-100 hover:from-{{ $aktivitas->icon_bg }}-50 hover:to-{{ $aktivitas->icon_bg }}-100 rounded-2xl transition-all duration-300 transform hover:scale-105 animate-fade-in" style="animation-delay: {{ ($index + 1) * 0.1 }}s">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-{{ $aktivitas->icon_bg }}-100 group-hover:bg-{{ $aktivitas->icon_bg }}-500 rounded-2xl flex items-center justify-center transition-all duration-300">
                            <i class="fas fa-{{ $aktivitas->icon }} text-{{ $aktivitas->icon_bg }}-600 group-hover:text-white text-xl transition-colors duration-300"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-lg">{{ $aktivitas->judul }}</p>
                            <p class="text-gray-600">{{ $aktivitas->deskripsi }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-bold text-{{ $aktivitas->icon_bg }}-600">{{ $aktivitas->nilai }}</span>
                        <div class="w-16 h-2 bg-{{ $aktivitas->icon_bg }}-200 rounded-full mt-2">
                            <div class="h-2 bg-{{ $aktivitas->icon_bg }}-500 rounded-full animate-pulse" style="width: {{ min(($aktivitas->nilai / 100) * 100, 100) }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-gray-400 text-3xl"></i>
                    </div>
                    <p class="text-gray-500 text-lg">Belum ada aktivitas sistem yang tercatat</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- User Distribution Chart -->
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl p-8 shadow-xl border border-white/20">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Distribusi Pengguna</h3>
                    <p class="text-gray-600">Berdasarkan peran dalam sistem</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
            
            <div class="relative">
                <canvas id="userDistributionChart" class="max-h-80"></canvas>
                
                <!-- Legend -->
                <div class="mt-6 grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="w-4 h-4 bg-purple-500 rounded-full mx-auto mb-2"></div>
                        <p class="text-sm font-semibold text-gray-700">Admin</p>
                        <p class="text-xs text-gray-500">{{ $dataDistribusiPengguna['data'][0] ?? 0 }} orang</p>
                    </div>
                    <div class="text-center">
                        <div class="w-4 h-4 bg-blue-500 rounded-full mx-auto mb-2"></div>
                        <p class="text-sm font-semibold text-gray-700">Pengurus</p>
                        <p class="text-xs text-gray-500">{{ $dataDistribusiPengguna['data'][1] ?? 0 }} orang</p>
                    </div>
                    <div class="text-center">
                        <div class="w-4 h-4 bg-green-500 rounded-full mx-auto mb-2"></div>
                        <p class="text-sm font-semibold text-gray-700">Anggota</p>
                        <p class="text-xs text-gray-500">{{ $dataDistribusiPengguna['data'][2] ?? 0 }} orang</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities Timeline -->
    <div class="bg-white/80 backdrop-blur-lg rounded-3xl p-8 shadow-xl border border-white/20">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Aktivitas Terbaru</h3>
                <p class="text-gray-600">Timeline aktivitas sistem hari ini</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-history text-white text-xl"></i>
            </div>
        </div>
        
        <div class="relative">
            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-500 to-purple-500"></div>
            
            <div class="space-y-8">
                <div class="relative flex items-center space-x-6 animate-fade-in">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-plus text-white text-xl"></i>
                    </div>
                    <div class="flex-1 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6">
                        <h4 class="font-bold text-gray-800">Pengguna Baru Terdaftar</h4>
                        <p class="text-gray-600">3 pengguna baru bergabung hari ini</p>
                        <p class="text-sm text-gray-500 mt-2">2 jam yang lalu</p>
                    </div>
                </div>
                
                <div class="relative flex items-center space-x-6 animate-fade-in" style="animation-delay: 0.2s">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-shopping-cart text-white text-xl"></i>
                    </div>
                    <div class="flex-1 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6">
                        <h4 class="font-bold text-gray-800">Transaksi Berhasil</h4>
                        <p class="text-gray-600">{{ $transaksiHariIni }} transaksi berhasil diproses</p>
                        <p class="text-sm text-gray-500 mt-2">4 jam yang lalu</p>
                    </div>
                </div>
                
                <div class="relative flex items-center space-x-6 animate-fade-in" style="animation-delay: 0.4s">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                    <div class="flex-1 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6">
                        <h4 class="font-bold text-gray-800">Peringatan Stok</h4>
                        <p class="text-gray-600">{{ $stokMenipisCount }} item barang memerlukan restok</p>
                        <p class="text-sm text-gray-500 mt-2">6 jam yang lalu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time clock
    function updateDateTime() {
        const now = new Date();
        const dateOptions = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        const timeOptions = { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: false
        };
        
        document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', dateOptions);
        document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID', timeOptions);
    }
    
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Enhanced User Distribution Chart
    const userDistCtx = document.getElementById('userDistributionChart');
    if(userDistCtx) {
        const chart = new Chart(userDistCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($dataDistribusiPengguna['labels'] ?? []),
                datasets: [{
                    label: 'Jumlah Pengguna',
                    data: @json($dataDistribusiPengguna['data'] ?? []),
                    backgroundColor: [
                        'rgba(147, 51, 234, 0.8)', // Purple for Admin
                        'rgba(59, 130, 246, 0.8)',  // Blue for Pengurus
                        'rgba(16, 185, 129, 0.8)'   // Green for Anggota
                    ],
                    borderColor: [
                        'rgb(147, 51, 234)',
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)'
                    ],
                    borderWidth: 3,
                    hoverOffset: 15,
                    cutout: '60%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: false // We're using custom legend
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed.toLocaleString('id-ID') + ' orang';
                                    
                                    // Calculate percentage
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    label += ` (${percentage}%)`;
                                }
                                return label;
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 2000,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Add hover effects
        userDistCtx.addEventListener('mousemove', function(e) {
            const points = chart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
            if (points.length) {
                userDistCtx.style.cursor = 'pointer';
            } else {
                userDistCtx.style.cursor = 'default';
            }
        });
    }

    // Animate numbers on scroll
    const animateNumbers = () => {
        const numbers = document.querySelectorAll('[data-animate-number]');
        numbers.forEach(number => {
            const target = parseInt(number.textContent.replace(/[^\d]/g, ''));
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                number.textContent = Math.floor(current).toLocaleString('id-ID');
            }, 30);
        });
    };

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-slide-up');
                
                // Animate numbers if they have the data attribute
                if (entry.target.hasAttribute('data-animate-number')) {
                    animateNumbers();
                }
            }
        });
    }, observerOptions);

    // Observe all animated elements
    document.querySelectorAll('.animate-fade-in, [data-animate-number]').forEach(el => {
        observer.observe(el);
    });

    // Add loading states to quick action buttons
    document.querySelectorAll('a[href*="route"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const icon = this.querySelector('i');
            const originalClass = icon.className;
            icon.className = 'fas fa-spinner fa-spin text-2xl';
            
            setTimeout(() => {
                icon.className = originalClass;
            }, 2000);
        });
    });
});
</script>
@endpush