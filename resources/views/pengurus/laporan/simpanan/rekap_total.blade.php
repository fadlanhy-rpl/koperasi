@extends('layouts.app')

@section('title', 'Rekapitulasi Total Simpanan - Koperasi')
@section('page-title', 'Rekapitulasi Total Simpanan')
@section('page-subtitle', 'Analisis komprehensif dana simpanan dengan visualisasi data interaktif')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --info-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        --shadow-soft: 0 10px 25px rgba(0, 0, 0, 0.1);
        --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15);
        --border-radius: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    /* Modern Card Design */
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: var(--transition);
        overflow: hidden;
    }

    .modern-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: var(--primary-gradient);
        color: white;
        padding: 2rem;
        border-radius: var(--border-radius);
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30px, -30px);
    }

    .stats-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: var(--shadow-hover);
    }

    .stats-card.success {
        background: var(--success-gradient);
    }

    .stats-card.warning {
        background: var(--warning-gradient);
    }

    .stats-card.info {
        background: var(--info-gradient);
    }

    .stats-card.grand-total {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        grid-column: span 3;
        min-height: 200px;
    }

    /* Charts Container */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(255, 255, 255, 0.2);
        min-height: 400px;
    }

    .chart-wrapper {
        position: relative;
        height: 300px;
        width: 100%;
    }

    /* Buttons */
    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        color: white;
        text-decoration: none;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        color: white;
        text-decoration: none;
    }

    /* Loading States */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        border-radius: var(--border-radius);
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f4f6;
        border-top: 4px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-in-up {
        animation: slideInUp 0.6s ease-out forwards;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .charts-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-card {
            padding: 1.5rem;
            min-height: 140px;
        }
        
        .stats-card.grand-total {
            grid-column: span 1;
            min-height: 160px;
        }
        
        .chart-container {
            padding: 1rem;
            min-height: 350px;
        }
        
        .chart-wrapper {
            height: 250px;
        }
    }

    /* Enhanced Table */
    .enhanced-table {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .table-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-row {
        transition: var(--transition);
        border-left: 4px solid transparent;
    }

    .table-row:hover {
        background: linear-gradient(90deg, rgba(59, 130, 246, 0.05) 0%, rgba(147, 197, 253, 0.05) 100%);
        border-left-color: #3b82f6;
        transform: translateX(2px);
    }
</style>
@endpush

@section('content')
<div class="animate-slide-in-up">
    <!-- Action Buttons -->
    <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-chart-pie text-blue-500 mr-3"></i>
                Dashboard Simpanan
            </h1>
            <p class="text-gray-600">Monitoring dan analisis dana simpanan koperasi secara real-time</p>
        </div>
        
        <div class="flex flex-wrap gap-2">
            <button type="button" class="btn-primary" onclick="refreshData()">
                <i class="fas fa-sync-alt"></i>
                <span class="hidden sm:inline">Refresh Data</span>
            </button>
            <button type="button" class="btn-primary" onclick="exportToPDF()">
                <i class="fas fa-file-pdf"></i>
                <span class="hidden sm:inline">Export PDF</span>
            </button>
            <button type="button" class="btn-primary" onclick="exportToExcel()">
                <i class="fas fa-file-excel"></i>
                <span class="hidden sm:inline">Export Excel</span>
            </button>
            <button type="button" class="btn-primary" onclick="printReport()">
                <i class="fas fa-print"></i>
                <span class="hidden sm:inline">Print</span>
            </button>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="stats-grid" id="rekapitulasiContainer">
        @include('pengurus.laporan.simpanan.partials._rekap_cards', ['rekapitulasi' => $rekapitulasi, 'statistikTambahan' => $statistikTambahan])
    </div>

    <!-- Charts Section -->
    <div class="charts-grid">
        <div class="chart-container">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-doughnut text-blue-500 mr-2"></i>
                Komposisi Simpanan
            </h3>
            <div class="chart-wrapper">
                <canvas id="komposisiChart"></canvas>
            </div>
        </div>
        
        <div class="chart-container">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-line text-green-500 mr-2"></i>
                Trend Pertumbuhan
            </h3>
            <div class="chart-wrapper">
                <canvas id="pertumbuhanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Enhanced Detail Table -->
    <div class="enhanced-table relative">
        <div id="loadingOverlay" class="loading-overlay hidden">
            <div class="text-center">
                <div class="spinner mb-4"></div>
                <p class="text-gray-600 font-medium">Memuat data...</p>
            </div>
        </div>

        <div class="table-header">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-table text-indigo-500 mr-3"></i>
                        Detail Rekapitulasi Simpanan
                    </h3>
                    <p class="text-gray-600">
                        Ringkasan lengkap semua jenis simpanan koperasi
                    </p>
                </div>
                
                <div class="text-sm text-gray-500 bg-gray-100 px-4 py-2 rounded-lg">
                    <i class="fas fa-clock mr-2"></i>
                    Terakhir diperbarui: <span id="lastUpdated">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                            <th class="py-4 px-6 text-left font-bold text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-list mr-2 text-blue-500"></i>
                                    Jenis Simpanan
                                </div>
                            </th>
                            <th class="py-4 px-6 text-right font-bold text-gray-700">
                                <div class="flex items-center justify-end">
                                    <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>
                                    Total (Rupiah)
                                </div>
                            </th>
                            <th class="py-4 px-6 text-center font-bold text-gray-700">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-percentage mr-2 text-purple-500"></i>
                                    Persentase
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-row border-b border-gray-100">
                            <td class="py-4 px-6 font-medium text-gray-800">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                    Total Simpanan Pokok Terkumpul
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right font-semibold text-gray-800">
                                Rp {{ number_format($rekapitulasi['total_simpanan_pokok'], 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $rekapitulasi['grand_total_simpanan'] > 0 ? number_format(($rekapitulasi['total_simpanan_pokok'] / $rekapitulasi['grand_total_simpanan']) * 100, 1) : 0 }}%
                                </span>
                            </td>
                        </tr>
                        <tr class="table-row border-b border-gray-100">
                            <td class="py-4 px-6 font-medium text-gray-800">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    Total Simpanan Wajib Terkumpul
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right font-semibold text-gray-800">
                                Rp {{ number_format($rekapitulasi['total_simpanan_wajib'], 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $rekapitulasi['grand_total_simpanan'] > 0 ? number_format(($rekapitulasi['total_simpanan_wajib'] / $rekapitulasi['grand_total_simpanan']) * 100, 1) : 0 }}%
                                </span>
                            </td>
                        </tr>
                        <tr class="table-row border-b border-gray-100">
                            <td class="py-4 px-6 font-medium text-gray-800">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                    Total Saldo Aktif Simpanan Sukarela
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right font-semibold text-gray-800">
                                Rp {{ number_format($rekapitulasi['total_simpanan_sukarela_aktif'], 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">
                                    {{ $rekapitulasi['grand_total_simpanan'] > 0 ? number_format(($rekapitulasi['total_simpanan_sukarela_aktif'] / $rekapitulasi['grand_total_simpanan']) * 100, 1) : 0 }}%
                                </span>
                            </td>
                        </tr>
                        <tr class="border-t-2 border-gray-200 bg-gradient-to-r from-blue-50 to-purple-50">
                            <td class="py-4 px-6 font-bold text-gray-800 text-base">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mr-3"></div>
                                    GRAND TOTAL SEMUA SIMPANAN
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right font-bold text-blue-600 text-base">
                                Rp {{ number_format($rekapitulasi['grand_total_simpanan'], 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="bg-gradient-to-r from-blue-500 to-purple-500 text-white px-4 py-2 rounded-full text-xs font-bold">
                                    100%
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6 text-xs text-gray-500 italic bg-gray-50 p-4 rounded-lg">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Catatan:</strong> Total saldo aktif simpanan sukarela dihitung berdasarkan saldo akhir dari setiap anggota yang memiliki transaksi simpanan sukarela.
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('pengurus.laporan.simpanan.rincianPerAnggota') }}" class="modern-card p-6 hover:scale-105 transition-transform">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-xl mr-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Rincian per Anggota</h4>
                    <p class="text-sm text-gray-600">Lihat detail simpanan setiap anggota</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('pengurus.laporan.simpanan.wajibBelumBayar') }}" class="modern-card p-6 hover:scale-105 transition-transform">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-xl mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Wajib Belum Bayar</h4>
                    <p class="text-sm text-gray-600">Anggota yang belum bayar simpanan wajib</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('pengurus.dashboard') }}" class="modern-card p-6 hover:scale-105 transition-transform">
            <div class="flex items-center">
                <div class="p-3 bg-gray-100 rounded-xl mr-4">
                    <i class="fas fa-arrow-left text-gray-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Kembali</h4>
                    <p class="text-sm text-gray-600">Ke dashboard pengurus</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    class SimpananReportManager {
        constructor() {
            this.currentRequestController = null;
            this.charts = {};
            this.init();
        }

        init() {
            this.initializeCharts();
            this.bindEvents();
        }

        bindEvents() {
            // Auto refresh every 5 minutes
            setInterval(() => {
                this.refreshData();
            }, 300000);
        }

        refreshData() {
            if (this.currentRequestController) {
                this.currentRequestController.abort();
            }
            this.currentRequestController = new AbortController();
            const signal = this.currentRequestController.signal;

            this.showLoading();

            fetch(window.location.href, { 
                signal, 
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                } 
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (signal.aborted) return;

                // Update cards
                const container = document.getElementById('rekapitulasiContainer');
                if (container && data.html) {
                    container.innerHTML = data.html;
                }

                // Update charts
                if (data.rekapitulasi) {
                    this.updateCharts(data.rekapitulasi, data.statistik_tambahan);
                }

                // Update last updated time
                const lastUpdatedElement = document.getElementById('lastUpdated');
                if (lastUpdatedElement) {
                    lastUpdatedElement.textContent = new Date().toLocaleString('id-ID');
                }

                this.showNotification('Data berhasil diperbarui', 'success');
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Error refreshing data:', error);
                    this.showNotification('Gagal memperbarui data. Silakan coba lagi.', 'error');
                }
            })
            .finally(() => {
                this.hideLoading();
                this.currentRequestController = null;
            });
        }

        initializeCharts() {
            try {
                this.initKomposisiChart();
                this.initPertumbuhanChart();
            } catch (error) {
                console.error('Chart initialization failed:', error);
                this.showNotification('Gagal memuat grafik', 'warning');
            }
        }

        initKomposisiChart() {
            const canvas = document.getElementById('komposisiChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const rekapitulasi = @json($rekapitulasi);
            
            this.charts.komposisi = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Simpanan Pokok', 'Simpanan Wajib', 'Simpanan Sukarela'],
                    datasets: [{
                        data: [
                            rekapitulasi.total_simpanan_pokok,
                            rekapitulasi.total_simpanan_wajib,
                            rekapitulasi.total_simpanan_sukarela_aktif
                        ],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)'
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return context.label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        initPertumbuhanChart() {
            const canvas = document.getElementById('pertumbuhanChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const statistik = @json($statistikTambahan);
            
            this.charts.pertumbuhan = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Bulan Lalu', 'Bulan Ini'],
                    datasets: [{
                        label: 'Simpanan Wajib (Juta Rupiah)',
                        data: [
                            statistik.total_bulan_lalu / 1000000,
                            statistik.total_bulan_ini / 1000000
                        ],
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + (context.parsed.y * 1000000).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value + 'Jt';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        updateCharts(rekapitulasi, statistikTambahan) {
            // Update komposisi chart
            if (this.charts.komposisi) {
                this.charts.komposisi.data.datasets[0].data = [
                    rekapitulasi.total_simpanan_pokok,
                    rekapitulasi.total_simpanan_wajib,
                    rekapitulasi.total_simpanan_sukarela_aktif
                ];
                this.charts.komposisi.update();
            }

            // Update pertumbuhan chart
            if (this.charts.pertumbuhan && statistikTambahan) {
                this.charts.pertumbuhan.data.datasets[0].data = [
                    statistikTambahan.total_bulan_lalu / 1000000,
                    statistikTambahan.total_bulan_ini / 1000000
                ];
                this.charts.pertumbuhan.update();
            }
        }

        showLoading() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                loadingOverlay.classList.remove('hidden');
            }
        }

        hideLoading() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                loadingOverlay.classList.add('hidden');
            }
        }

        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                warning: 'bg-yellow-500 text-white',
                info: 'bg-blue-500 text-white'
            };
            
            notification.className += ` ${colors[type] || colors.info}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : type === 'warning' ? 'exclamation' : 'info'}-circle mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    }

    // Global functions for export
    window.refreshData = function() {
        simpananReportManager.refreshData();
    };

    window.exportToPDF = function() {
        try {
            window.open("{{ route('pengurus.laporan.simpanan.rekapTotal') }}?export=pdf");
        } catch (error) {
            console.error('Export PDF failed:', error);
            simpananReportManager.showNotification('Gagal mengekspor PDF. Silakan coba lagi.', 'error');
        }
    };

    window.exportToExcel = function() {
        try {
            window.open("{{ route('pengurus.laporan.simpanan.rekapTotal') }}?export=excel");
        } catch (error) {
            console.error('Export Excel failed:', error);
            simpananReportManager.showNotification('Gagal mengekspor Excel. Silakan coba lagi.', 'error');
        }
    };

    window.printReport = function() {
        try {
            window.print();
        } catch (error) {
            console.error('Print failed:', error);
            simpananReportManager.showNotification('Gagal mencetak laporan. Silakan coba lagi.', 'error');
        }
    };

    // Initialize the simpanan report manager
    let simpananReportManager;
    document.addEventListener('DOMContentLoaded', function() {
        simpananReportManager = new SimpananReportManager();
    });
</script>
@endpush
