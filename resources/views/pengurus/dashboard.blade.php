@extends('layouts.app')

@section('title', 'Dashboard Pengurus - Koperasi')

@section('page-title', 'Dashboard Pengurus')
@section('page-subtitle', 'Kelola operasional koperasi sehari-hari')

@section('content')
    <!-- Pengurus Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-cards.stats_card 
            title="Transaksi Hari Ini"
            :value="$transaksiHariIni"
            icon="cash-register"
            color="blue"
            trend="+8% dari kemarin" {{-- Placeholder --}}
            progress="78"
            delay="0.1s"
        />
        <x-cards.stats_card 
            title="Stok Menipis"
            :value="$stokMenipisCount"
            icon="boxes"
            color="yellow"
            trend="Perlu restok"
            progress="65"
            delay="0.2s"
        />
        <x-cards.stats_card 
            title="Simpanan Bulan Ini"
            :value="'Rp ' . number_format($totalSimpananMasukBulanIni, 0, ',', '.')"
            icon="piggy-bank"
            color="green"
            trend="+12% dari bulan lalu"
            progress="82"
            delay="0.3s"
        />
        <x-cards.stats_card 
            title="Cicilan Perlu Tindakan" {{-- Mengganti 'Jatuh Tempo' --}}
            :value="$cicilanJatuhTempoCount"
            icon="calendar-times"
            color="red"
            trend="Segera cek" {{-- Placeholder --}}
            progress="45"
            delay="0.4s"
        />
    </div>

    <!-- Quick Actions for Pengurus -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('pengurus.transaksi-pembelian.create') }}" class="card-simple bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl shadow-lg text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-300 flex flex-col justify-between items-center text-center h-full">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-cash-register text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">POS Kasir</h3>
                <p class="text-blue-100 text-sm mt-1">Transaksi pembelian</p>
            </div>
        </a>
        
        {{-- Link ke form barang masuk, perlu parameter barang jika spesifik, atau halaman umum input stok --}}
        <a href="{{ route('pengurus.stok.formBarangMasuk', ['barang' => App\Models\Barang::first()?->id ?? 0 ]) }}" class="card-simple bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-2xl shadow-lg text-white hover:from-green-600 hover:to-green-700 transition-all duration-300 flex flex-col justify-between items-center text-center h-full">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-plus-circle text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Input Barang Masuk</h3>
                <p class="text-green-100 text-sm mt-1">Tambah stok barang</p>
            </div>
        </a>
        
        <a href="{{ route('pengurus.simpanan.wajib.index') }}" class="card-simple bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-2xl shadow-lg text-white hover:from-purple-600 hover:to-purple-700 transition-all duration-300 flex flex-col justify-between items-center text-center h-full">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-hand-holding-usd text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Catat Simpanan</h3>
                <p class="text-purple-100 text-sm mt-1">Input simpanan anggota</p>
            </div>
        </a>
        
        <a href="{{ route('pengurus.laporan.penjualan.umum') }}" class="card-simple bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-2xl shadow-lg text-white hover:from-orange-600 hover:to-orange-700 transition-all duration-300 flex flex-col justify-between items-center text-center h-full">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-file-alt text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Laporan Penjualan</h3>
                <p class="text-orange-100 text-sm mt-1">Lihat rekap penjualan</p>
            </div>
        </a>
    </div>

    <!-- Operational Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 animate-fade-in">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Barang Stok Menipis (Top 5)</h3>
            <div class="space-y-3 max-h-72 overflow-y-auto">
                @forelse($stokMenipisDetail as $item)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-box-open text-red-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-sm">{{ Str::limit($item->nama_barang, 25) }}</p>
                                <p class="text-xs text-gray-500">{{ $item->unitUsaha->nama_unit_usaha ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-bold text-red-600">{{ $item->stok }}</span>
                            <p class="text-xs text-gray-500">unit</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Tidak ada barang dengan stok menipis.</p>
                @endforelse
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 animate-fade-in">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Penjualan Hari Ini per Unit Usaha</h3>
            <div class="h-64 md:h-72 relative">
                <canvas id="dailySalesPerUnitChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 animate-fade-in">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-800">Transaksi Terbaru (Hari Ini)</h3>
                <a href="{{ route('pengurus.transaksi-pembelian.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                    Lihat Semua Transaksi
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-3 font-semibold text-gray-600">Waktu</th>
                            <th class="text-left py-3 px-3 font-semibold text-gray-600">Anggota</th>
                            <th class="text-left py-3 px-3 font-semibold text-gray-600">Unit Usaha</th>
                            <th class="text-right py-3 px-3 font-semibold text-gray-600">Total</th>
                            <th class="text-center py-3 px-3 font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerbaruFormatted as $transaksi)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-3 text-gray-700">{{ $transaksi->waktu }}</td>
                                <td class="py-3 px-3 font-medium text-gray-800">{{ Str::limit($transaksi->anggota_name, 20) }}</td>
                                <td class="py-3 px-3 text-gray-600">{{ $transaksi->unit_usaha }}</td>
                                <td class="py-3 px-3 font-semibold text-gray-800 text-right">@rupiah($transaksi->total)</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($transaksi->status_pembayaran == 'lunas') bg-green-100 text-green-700
                                        @elseif($transaksi->status_pembayaran == 'cicilan') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($transaksi->status_pembayaran) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 px-3 text-center text-gray-500">Belum ada transaksi hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Daily Sales Per Unit Chart
        const dailySalesUnitCtx = document.getElementById('dailySalesPerUnitChart');
        if (dailySalesUnitCtx) {
            new Chart(dailySalesUnitCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($dataPenjualanHarianChart['labels'] ?? []),
                    datasets: [{
                        label: 'Penjualan (Rupiah)',
                        data: @json($dataPenjualanHarianChart['data'] ?? []),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.7)',  // blue
                            'rgba(16, 185, 129, 0.7)',  // green
                            'rgba(245, 158, 11, 0.7)', // amber
                            'rgba(139, 92, 246, 0.7)', // purple
                            'rgba(239, 68, 68, 0.7)'   // red
                        ],
                        borderColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(245, 158, 11)',
                            'rgb(139, 92, 246)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y', // Membuat bar menjadi horizontal jika label banyak
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' Rp ' + context.parsed.x.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: { // Untuk bar horizontal, x adalah sumbu nilai
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.03)'},
                            ticks: { color: '#6B7280', callback: function(value) { return 'Rp ' + (value/1000).toLocaleString('id-ID') + 'K'; } }
                        },
                        y: { // Untuk bar horizontal, y adalah sumbu kategori/label
                            grid: { display: false },
                            ticks: { color: '#6B7280', autoSkip: false } // Tampilkan semua label unit usaha
                        }
                    }
                }
            });
        }
    });
</script>
@endpush