@extends('layouts.app')

@section('title', 'Dashboard Utama - Koperasi')

@section('page-title', 'Dashboard Overview')
@section('page-subtitle', 'Ringkasan aktivitas koperasi terkini')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-cards.stats_card 
            title="Total Anggota"
            :value="$totalAnggota"
            icon="users"
            color="blue"
            trend="+12% dari bulan lalu" {{-- Placeholder trend, bisa dibuat dinamis --}}
            progress="75"
            delay="0.1s"
        />
        <x-cards.stats_card 
            title="Total Simpanan"
            :value="'Rp ' . number_format($totalSimpanan, 0, ',', '.')"
            icon="piggy-bank"
            color="green"
            trend="+8% dari bulan lalu"
            progress="60"
            delay="0.2s"
        />
        <x-cards.stats_card 
            title="Penjualan Bulan Ini"
            :value="'Rp ' . number_format($penjualanBulanIni, 0, ',', '.')"
            icon="chart-line"
            color="yellow"
            trend="+15% dari bulan lalu"
            progress="85"
            delay="0.3s"
        />
        <x-cards.stats_card 
            title="Cicilan Aktif"
            :value="$cicilanAktif"
            icon="calendar-check"
            color="red"
            trend="-3% dari bulan lalu"
            trendDirection="down"
            progress="45"
            delay="0.4s"
        />
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 animate-scale-in" style="animation-delay: 0.5s">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800">Penjualan Unit Usaha (6 Bulan Terakhir)</h3>
                {{-- Tombol mini bisa dihilangkan atau diberi fungsi --}}
            </div>
            <div class="h-64 md:h-72 relative">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 animate-scale-in" style="animation-delay: 0.6s">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800">Distribusi Simpanan</h3>
            </div>
            <div class="h-64 md:h-72 relative">
                <canvas id="savingsDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">Aktivitas Terbaru</h3>
                {{-- <button class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                    Lihat Semua
                </button> --}}
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4 max-h-96 overflow-y-auto">
                @forelse($aktivitasTerbaru as $aktivitas)
                    <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 bg-{{ $aktivitas->icon_bg ?? 'blue' }}-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-{{ $aktivitas->icon ?? 'info-circle' }} text-{{ $aktivitas->icon_bg ?? 'blue' }}-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $aktivitas->judul }}</p>
                            <p class="text-sm text-gray-500">{{ $aktivitas->deskripsi }}</p>
                        </div>
                        {{-- <div class="w-2 h-2 bg-{{ $aktivitas->status_color ?? 'green' }}-400 rounded-full"></div> --}}
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Belum ada aktivitas terbaru.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            new Chart(salesCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($dataPenjualanUnitUsaha['labels'] ?? []),
                    datasets: [{
                        label: 'Penjualan (Juta Rupiah)', // Sesuaikan label
                        data: @json($dataPenjualanUnitUsaha['data'] ?? []),
                        borderColor: tailwind.theme.colors.primary,
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: tailwind.theme.colors.primary,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)'}, ticks: { color: '#6B7280', callback: function(value) { return value + ' Jt'; } } },
                        x: { grid: { display: false }, ticks: { color: '#6B7280'} }
                    }
                }
            });
        }

        // Savings Distribution Chart
        const savingsDistCtx = document.getElementById('savingsDistributionChart');
        if (savingsDistCtx) {
            new Chart(savingsDistCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($dataDistribusiSimpanan['labels'] ?? []),
                    datasets: [{
                        data: @json($dataDistribusiSimpanan['data'] ?? []),
                        backgroundColor: [
                            tailwind.theme.colors.blue[500],    // Simpanan Pokok
                            tailwind.theme.colors.green[500],  // Simpanan Wajib
                            tailwind.theme.colors.amber[500]   // Simpanan Sukarela
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
                        legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true, color: '#6B7280'} }
                    }
                }
            });
        }
    });
</script>
@endpush