@extends('layouts.app')

@section('title', 'Dashboard Admin - Koperasi')

@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Kelola sistem koperasi secara menyeluruh')

@section('content')
    <!-- Admin Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-cards.stats_card 
            title="Total Pengguna"
            :value="$totalPengguna"
            icon="users-cog"
            color="purple"
            trend="+5%" {{-- Placeholder, buat dinamis jika ada data pembanding --}}
            progress="90"
            delay="0.1s"
        />
        <x-cards.stats_card 
            title="Unit Usaha Aktif"
            :value="$unitUsahaAktif"
            icon="store"
            color="indigo"
            trend="Semua aktif" {{-- Ini teks statis, bisa disesuaikan --}}
            progress="100"
            delay="0.2s"
        />
        <x-cards.stats_card 
            title="Total Transaksi"
            :value="$totalTransaksi"
            icon="receipt"
            color="emerald"
            trend="+18%"
            progress="82"
            delay="0.3s"
        />
        <x-cards.stats_card 
            title="Pendapatan Total (Omset)"
            :value="'Rp ' . number_format($totalPendapatanKotor, 0, ',', '.')"
            icon="chart-line"
            color="amber"
            trend="+22%"
            progress="95"
            delay="0.4s"
        />
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('admin.manajemen-pengguna.create') }}" class="card-simple bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl shadow-lg text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-300 flex flex-col justify-between items-center text-center h-full">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-user-plus text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Tambah Pengguna</h3>
                <p class="text-blue-100 text-sm mt-1">Daftarkan pengguna baru</p>
            </div>
        </a>
        
        {{-- Pastikan route 'pengurus.laporan.penjualan.umum' bisa diakses admin --}}
        <a href="{{ route('pengurus.laporan.penjualan.umum') }}" class="card-simple bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-2xl shadow-lg text-white hover:from-green-600 hover:to-green-700 transition-all duration-300 flex flex-col justify-between items-center text-center h-full">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-chart-bar text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Lihat Laporan</h3>
                <p class="text-green-100 text-sm mt-1">Analisis kinerja sistem</p>
            </div>
        </a>
        
        <a href="{{ route('admin.settings.index') }}" class="card-simple bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-2xl shadow-lg text-white hover:from-purple-600 hover:to-purple-700 transition-all duration-300 flex flex-col justify-between items-center text-center h-full"> {{-- Ganti # dengan route('admin.settings.index') jika ada --}}
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-cogs text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Pengaturan Sistem</h3>
                <p class="text-purple-100 text-sm mt-1">Konfigurasi aplikasi</p>
            </div>
        </a>
        
        <a href="#" class="card-simple bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-2xl shadow-lg text-white hover:from-orange-600 hover:to-orange-700 transition-all duration-300 flex flex-col justify-between items-center text-center h-full"> {{-- Ganti # dengan route('admin.backup.index') jika ada --}}
             <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-database text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Backup Data</h3>
                <p class="text-orange-100 text-sm mt-1">Cadangkan data sistem</p>
            </div>
        </a>
    </div>

    <!-- System Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 animate-fade-in">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Ringkasan Aktivitas Sistem</h3>
            <div class="space-y-4">
                @forelse($aktivitasSistem as $aktivitas)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-{{ $aktivitas->icon_bg }}-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-{{ $aktivitas->icon }} text-{{ $aktivitas->icon_bg }}-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $aktivitas->judul }}</p>
                            <p class="text-sm text-gray-500">{{ $aktivitas->deskripsi }}</p>
                        </div>
                    </div>
                    <span class="text-2xl font-bold text-{{ $aktivitas->icon_bg }}-600">{{ $aktivitas->nilai }}</span>
                </div>
                @empty
                <p class="text-gray-500">Belum ada aktivitas sistem yang tercatat.</p>
                @endforelse
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 animate-fade-in">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Distribusi Peran Pengguna</h3>
            <div class="h-64 md:h-72 relative">
                <canvas id="userDistributionChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User Distribution Chart
        const userDistCtx = document.getElementById('userDistributionChart');
        if(userDistCtx) {
            new Chart(userDistCtx.getContext('2d'), {
                type: 'pie', // atau 'doughnut' jika lebih disukai
                data: {
                    labels: @json($dataDistribusiPengguna['labels'] ?? []),
                    datasets: [{
                        label: 'Jumlah Pengguna',
                        data: @json($dataDistribusiPengguna['data'] ?? []),
                        backgroundColor: [
                            tailwind.theme.colors.purple[500], // Admin
                            tailwind.theme.colors.blue[500],   // Pengurus
                            tailwind.theme.colors.green[500]   // Anggota
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom', 
                            labels: { padding: 15, usePointStyle: true, color: '#6B7280'}
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += context.parsed.toLocaleString('id-ID');
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush