@extends('layouts.app')

@section('title', 'Dashboard Anggota - Koperasi')

@section('page-title', 'Dashboard Saya')
@section('page-subtitle', 'Informasi simpanan dan aktivitas Anda')

@section('content')
    <!-- Anggota Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <x-cards.stats_card 
            title="Simpanan Pokok"
            :value="'Rp ' . number_format($totalSimpananPokok, 0, ',', '.')"
            icon="money-check-alt"
            color="blue"
            trend="Lunas"
            delay="0.1s"
        />
        <x-cards.stats_card 
            title="Total Simpanan Wajib"
            :value="'Rp ' . number_format($totalSimpananWajib, 0, ',', '.')"
            icon="calendar-alt"
            color="green"
            :trend="$jumlahBulanBayarWajib . ' bulan terbayar'"
            delay="0.2s"
        />
        <x-cards.stats_card 
            title="Saldo Simpanan Sukarela"
            :value="'Rp ' . number_format($saldoSimpananSukarela, 0, ',', '.')"
            icon="hand-holding-usd"
            color="yellow"
            trend="Dapat ditarik"
            delay="0.3s"
        />
    </div>

    <!-- Quick Actions for Anggota -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- <a href="#" class="card-simple bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl shadow-lg text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-300 flex flex-col justify-between items-center text-center h-full"> --}}
            {{-- Ganti # dengan route('anggota.pembelian.katalog') jika ada --}}
            {{-- <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-store-alt text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Belanja Barang</h3>
                <p class="text-blue-100 text-sm mt-1">Lihat katalog produk</p>
            </div>
        </a> --}}
        
        <a href="{{ route('anggota.simpanan.show') }}" class="card-simple bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-2xl shadow-lg text-white hover:from-green-600 hover:to-green-700 transition-all duration-300 flex flex-col justify-between items-center text-center h-full">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-wallet text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Rincian Simpanan</h3>
                <p class="text-green-100 text-sm mt-1">Lihat detail simpanan Anda</p>
            </div>
        </a>
        
        <a href="{{ route('anggota.pembelian.riwayat') }}" class="card-simple bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-2xl shadow-lg text-white hover:from-purple-600 hover:to-purple-700 transition-all duration-300 flex flex-col justify-between items-center text-center h-full">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-receipt text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Riwayat Pembelian</h3>
                <p class="text-purple-100 text-sm mt-1">Cek transaksi belanja</p>
            </div>
        </a>
        
        <a href="{{ route('anggota.profil.show') }}" class="card-simple bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-2xl shadow-lg text-white hover:from-orange-600 hover:to-orange-700 transition-all duration-300 flex flex-col justify-between items-center text-center h-full">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-user-edit text-white text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Profil Saya</h3>
                <p class="text-orange-100 text-sm mt-1">Kelola akun Anda</p>
            </div>
        </a>
    </div>

    <!-- Savings Overview and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 animate-fade-in">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Grafik Simpanan (6 Bulan Terakhir)</h3>
            <div class="h-64 md:h-72 relative">
                <canvas id="memberSavingsChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 animate-fade-in">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Aktivitas Terbaru Anda</h3>
            <div class="space-y-3 max-h-72 overflow-y-auto">
                @forelse($aktivitasTerbaru as $aktivitas)
                    <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-{{ $aktivitas->icon_bg }}-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-{{ $aktivitas->icon }} text-{{ $aktivitas->icon_bg }}-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800">{{ $aktivitas->deskripsi }}</p>
                            <p class="text-sm text-gray-500">{{ $aktivitas->tanggal_format }}</p>
                        </div>
                        <span class="font-semibold text-{{ $aktivitas->icon_bg }}-600">
                            @rupiah($aktivitas->jumlah)
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Belum ada aktivitas terbaru.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Summary Information -->
    <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg border border-white/30 animate-fade-in">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Ringkasan Keanggotaan</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-4 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 ring-4 ring-blue-200">
                        <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $lamaKeanggotaanBulan }}</p>
                    <p class="text-sm text-gray-600 mt-1">Bulan Bergabung</p>
                </div>
                
                <div class="text-center p-4 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3 ring-4 ring-green-200">
                        <i class="fas fa-shopping-bag text-green-600 text-2xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPembelianCount }}</p>
                    <p class="text-sm text-gray-600 mt-1">Total Pembelian</p>
                </div>
                
                <div class="text-center p-4 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3 ring-4 ring-yellow-200">
                        <i class="fas fa-coins text-yellow-600 text-2xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">@rupiah($totalSemuaSimpanan)</p>
                    <p class="text-sm text-gray-600 mt-1">Total Simpanan</p>
                </div>
                
                <div class="text-center p-4 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 ring-4 ring-purple-200">
                        <i class="fas fa-user-check text-purple-600 text-2xl"></i>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $statusKeanggotaan }}</p>
                    <p class="text-sm text-gray-600 mt-1">Status Keanggotaan</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Member Savings Chart
        const memberSavingsCtx = document.getElementById('memberSavingsChart');
        if(memberSavingsCtx) {
            new Chart(memberSavingsCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($dataSavingsChart['labels'] ?? []),
                    datasets: [
                        {
                            label: 'Simpanan Wajib',
                            data: @json($dataSavingsChart['wajib'] ?? []),
                            borderColor: tailwind.theme.colors.green[500],
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: tailwind.theme.colors.green[500],
                            pointBorderColor: '#ffffff',
                        },
                        {
                            label: 'Setoran Simp. Sukarela',
                            data: @json($dataSavingsChart['sukarela'] ?? []),
                            borderColor: tailwind.theme.colors.amber[500],
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: tailwind.theme.colors.amber[500],
                            pointBorderColor: '#ffffff',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 15, color: '#6B7280'} } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.03)'}, ticks: { color: '#6B7280', callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } },
                        x: { grid: { display: false }, ticks: { color: '#6B7280'} }
                    }
                }
            });
        }
    });
</script>
@endpush