@extends('layouts.app')

@section('title', 'Laporan Penjualan Umum - Koperasi')
@section('page-title', 'Laporan Penjualan Umum')
@section('page-subtitle', 'Analisis komprehensif penjualan dengan visualisasi data interaktif')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --info-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

    /* Enhanced Select2 Styling */
    .select2-container .select2-selection--single {
        height: 48px !important;
        border-radius: var(--border-radius) !important;
        border: 2px solid #e5e7eb !important;
        background: white !important;
        transition: var(--transition) !important;
    }

    .select2-container .select2-selection--single:hover {
        border-color: #3b82f6 !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15) !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 44px !important;
        padding-left: 16px !important;
        color: #374151 !important;
        font-weight: 500 !important;
    }

    .select2-dropdown {
        border-radius: var(--border-radius) !important;
        border: 2px solid #e5e7eb !important;
        box-shadow: var(--shadow-soft) !important;
        z-index: 9999 !important;
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

    /* Filter Section */
    .filter-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(255, 255, 255, 0.2);
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

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: var(--transition);
    }

    .status-badge:hover {
        transform: scale(1.05);
    }

    .status-lunas {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .status-cicilan {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .status-belum-lunas {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
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
        
        .filter-section {
            padding: 1rem;
        }
        
        .stats-card {
            padding: 1.5rem;
            min-height: 140px;
        }
        
        .chart-container {
            padding: 1rem;
            min-height: 350px;
        }
        
        .chart-wrapper {
            height: 250px;
        }
    }

    /* Form Inputs */
    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        transition: var(--transition);
        background: white;
    }

    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 1rem;
    }

    .quick-action-btn {
        padding: 8px 16px;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.2);
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
    }

    .quick-action-btn:hover {
        background: rgba(59, 130, 246, 0.2);
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="animate-slide-in-up">
    <!-- Enhanced Filter Section -->
    <div class="filter-section">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-filter text-blue-500 mr-3"></i>
                    Filter & Analisis Data
                </h2>
                <p class="text-gray-600">Gunakan filter di bawah untuk menganalisis data penjualan sesuai kebutuhan</p>
            </div>
            
            <div class="flex flex-wrap gap-2">
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

        <!-- Quick Actions -->
        <div class="quick-actions">
            <button type="button" class="quick-action-btn" onclick="setDateRange('today')">Hari Ini</button>
            <button type="button" class="quick-action-btn" onclick="setDateRange('week')">7 Hari Terakhir</button>
            <button type="button" class="quick-action-btn" onclick="setDateRange('month')">Bulan Ini</button>
            <button type="button" class="quick-action-btn" onclick="setDateRange('lastMonth')">Bulan Lalu</button>
        </div>

        <form method="GET" action="{{ route('pengurus.laporan.penjualan.umum') }}" id="filterLaporanForm" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <div class="space-y-2">
                    <label for="daterange" class="form-label">
                        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                        Periode Tanggal
                    </label>
                    <input type="text" id="daterange" name="daterange" 
                           class="form-input"
                           placeholder="Pilih rentang tanggal">
                    <input type="hidden" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai', $tanggalMulai) }}">
                    <input type="hidden" name="tanggal_selesai" id="tanggal_selesai" value="{{ request('tanggal_selesai', $tanggalSelesai) }}">
                </div>
                
                <div class="space-y-2">
                    <label for="anggota_id" class="form-label">
                        <i class="fas fa-users text-green-500 mr-2"></i>
                        Anggota
                    </label>
                    <select name="anggota_id" id="anggota_id" class="select2-filter">
                        <option value="">🔍 Semua Anggota</option>
                        @foreach($filters['anggotas'] as $anggota)
                            <option value="{{ $anggota->id }}" {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                👤 {{ $anggota->name }} ({{ $anggota->nomor_anggota ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label for="unit_usaha_id" class="form-label">
                        <i class="fas fa-store text-purple-500 mr-2"></i>
                        Unit Usaha
                    </label>
                    <select name="unit_usaha_id" id="unit_usaha_id" class="select2-filter">
                        <option value="">🏪 Semua Unit Usaha</option>
                        @foreach($filters['unit_usahas'] as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_usaha_id') == $unit->id ? 'selected' : '' }}>
                                🏢 {{ $unit->nama_unit_usaha }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label for="status_pembayaran" class="form-label">
                        <i class="fas fa-credit-card text-orange-500 mr-2"></i>
                        Status Pembayaran
                    </label>
                    <select name="status_pembayaran" id="status_pembayaran" class="form-input">
                        @foreach($filters['status_pembayaran_options'] as $value => $label)
                            <option value="{{ $value }}" {{ request('status_pembayaran', 'all') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="quickSearch" class="form-label">
                        <i class="fas fa-search text-indigo-500 mr-2"></i>
                        Pencarian Cepat
                    </label>
                    <input type="text" id="quickSearch" placeholder="Cari barang, kode, anggota..."
                           class="form-input">
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pt-6 border-t border-gray-200 gap-4">
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-filter mr-2"></i>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('pengurus.laporan.penjualan.umum') }}" class="btn-secondary">
                        <i class="fas fa-undo mr-2"></i>
                        Reset Filter
                    </a>
                </div>
                
                <div class="text-sm text-gray-500 bg-gray-100 px-4 py-2 rounded-lg">
                    <i class="fas fa-info-circle mr-2"></i>
                    Terakhir diperbarui: <span id="lastUpdated">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </form>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="stats-grid" id="ringkasanPenjualanContainer">
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-80">vs bulan lalu</div>
                    <div class="text-lg font-bold text-green-200">+12.5%</div>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-2">Total Omset Penjualan</p>
                <p class="text-3xl font-bold mb-2" id="totalOmset">@rupiah($totalOmset)</p>
            </div>
            <div class="flex items-center text-sm opacity-80">
                <i class="fas fa-trending-up mr-2"></i>
                Trend positif
            </div>
        </div>
        
        <div class="stats-card success">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <i class="fas fa-boxes text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-80">vs bulan lalu</div>
                    <div class="text-lg font-bold text-green-200">+8.3%</div>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-2">Total Item Terjual</p>
                <p class="text-3xl font-bold mb-2" id="totalItemTerjual">{{ number_format($totalItemTerjual, 0, ',', '.') }}</p>
            </div>
            <div class="flex items-center text-sm opacity-80">
                <i class="fas fa-arrow-up mr-2"></i>
                Performa baik
            </div>
        </div>
        
        <div class="stats-card warning">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-80">vs bulan lalu</div>
                    <div class="text-lg font-bold text-green-200">+15.7%</div>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-2">Jumlah Transaksi</p>
                <p class="text-3xl font-bold mb-2" id="jumlahTransaksi">{{ number_format($jumlahTransaksi, 0, ',', '.') }}</p>
            </div>
            <div class="flex items-center text-sm opacity-80">
                <i class="fas fa-chart-bar mr-2"></i>
                Aktivitas tinggi
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-grid">
        <div class="chart-container">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
                Distribusi Penjualan per Unit Usaha
            </h3>
            <div class="chart-wrapper">
                <canvas id="unitUsahaChart"></canvas>
            </div>
        </div>
        
        <div class="chart-container">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-bar text-green-500 mr-2"></i>
                Trend Penjualan Harian
            </h3>
            <div class="chart-wrapper">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Enhanced Table Section -->
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
                        Rincian Item Penjualan
                    </h3>
                    <p class="text-gray-600">
                        Menampilkan item per transaksi dari 
                        <span class="font-semibold text-blue-600">{{ \Carbon\Carbon::parse($tanggalMulai)->isoFormat('DD MMMM YYYY') }}</span> 
                        sampai 
                        <span class="font-semibold text-blue-600">{{ \Carbon\Carbon::parse($tanggalSelesai)->isoFormat('DD MMMM YYYY') }}</span>
                    </p>
                </div>
                
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Tampilkan:</label>
                        <select id="perPageSelect" class="form-input w-auto">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    
                    <button type="button" id="toggleView" class="btn-secondary">
                        <i class="fas fa-th-list mr-2"></i>
                        <span class="hidden sm:inline">Tampilan Kartu</span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] text-sm" id="detailPenjualanTable">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                            <th class="py-4 px-4 text-left font-bold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" data-sort="kode">
                                <div class="flex items-center">
                                    <i class="fas fa-barcode mr-2 text-blue-500"></i>
                                    Kode & Tanggal
                                    <i class="fas fa-sort ml-2 text-gray-400"></i>
                                </div>
                            </th>
                            <th class="py-4 px-4 text-left font-bold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" data-sort="anggota">
                                <div class="flex items-center">
                                    <i class="fas fa-user mr-2 text-green-500"></i>
                                    Anggota
                                    <i class="fas fa-sort ml-2 text-gray-400"></i>
                                </div>
                            </th>
                            <th class="py-4 px-4 text-left font-bold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" data-sort="barang">
                                <div class="flex items-center">
                                    <i class="fas fa-box mr-2 text-purple-500"></i>
                                    Barang (Unit Usaha)
                                    <i class="fas fa-sort ml-2 text-gray-400"></i>
                                </div>
                            </th>
                            <th class="py-4 px-4 text-center font-bold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" data-sort="jumlah">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-hashtag mr-2 text-orange-500"></i>
                                    Jumlah
                                    <i class="fas fa-sort ml-2 text-gray-400"></i>
                                </div>
                            </th>
                            <th class="py-4 px-4 text-right font-bold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" data-sort="harga">
                                <div class="flex items-center justify-end">
                                    <i class="fas fa-tag mr-2 text-red-500"></i>
                                    Harga Satuan
                                    <i class="fas fa-sort ml-2 text-gray-400"></i>
                                </div>
                            </th>
                            <th class="py-4 px-4 text-right font-bold text-gray-700 cursor-pointer hover:bg-gray-200 transition-colors" data-sort="subtotal">
                                <div class="flex items-center justify-end">
                                    <i class="fas fa-calculator mr-2 text-indigo-500"></i>
                                    Subtotal
                                    <i class="fas fa-sort ml-2 text-gray-400"></i>
                                </div>
                            </th>
                            <th class="py-4 px-4 text-center font-bold text-gray-700">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-credit-card mr-2 text-teal-500"></i>
                                    Status Bayar
                                </div>
                            </th>
                            <th class="py-4 px-4 text-left font-bold text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-money-bill mr-2 text-yellow-500"></i>
                                    Metode Bayar
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="detailPenjualanTableBody">
                        @include('pengurus.laporan.penjualan.partials._penjualan_umum_rows', ['detailPembelians' => $detailPembelians])
                    </tbody>
                </table>
            </div>
            
            <!-- Enhanced Pagination -->
            <div id="paginationLinksDetailPenjualan" class="mt-8">
                {{ $detailPembelians->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    class SalesReportManager {
        constructor() {
            this.currentRequestController = null;
            this.charts = {};
            this.init();
        }

        init() {
            this.initializeComponents();
            this.bindEvents();
            this.initializeCharts();
        }

        initializeComponents() {
            try {
                // Initialize Select2
                $('.select2-filter').select2({
                    placeholder: "Pilih opsi",
                    width: '100%',
                    allowClear: true,
                    dropdownParent: $('body')
                });

                // Initialize Date Range Picker
                this.initializeDateRangePicker();
            } catch (error) {
                console.warn('Component initialization failed:', error);
                this.showNotification('Gagal menginisialisasi komponen', 'warning');
            }
        }

        initializeDateRangePicker() {
            try {
                $('#daterange').daterangepicker({
                    startDate: moment('{{ $tanggalMulai }}'),
                    endDate: moment('{{ $tanggalSelesai }}'),
                    locale: {
                        format: 'DD/MM/YYYY',
                        separator: ' - ',
                        applyLabel: 'Terapkan',
                        cancelLabel: 'Batal',
                        fromLabel: 'Dari',
                        toLabel: 'Sampai',
                        customRangeLabel: 'Custom',
                        weekLabel: 'W',
                        daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                        monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                        firstDay: 1
                    },
                    ranges: {
                       'Hari Ini': [moment(), moment()],
                       'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                       '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                       '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                       'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                       'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    }
                }, (start, end, label) => {
                    $('#tanggal_mulai').val(start.format('YYYY-MM-DD'));
                    $('#tanggal_selesai').val(end.format('YYYY-MM-DD'));
                });
            } catch (error) {
                console.warn('DateRangePicker initialization failed:', error);
                this.showNotification('Gagal menginisialisasi date picker', 'warning');
            }
        }

        bindEvents() {
            // Form submission
            const filterForm = document.getElementById('filterLaporanForm');
            if (filterForm) {
                filterForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.filterData();
                });
            }

            // Quick search
            let searchTimeout;
            const quickSearchInput = document.getElementById('quickSearch');
            if (quickSearchInput) {
                quickSearchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        this.filterData();
                    }, 500);
                });
            }

            // Per page selection
            const perPageSelect = document.getElementById('perPageSelect');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', () => {
                    this.filterData();
                });
            }

            // Pagination handling
            const paginationContainer = document.getElementById('paginationLinksDetailPenjualan');
            if (paginationContainer) {
                paginationContainer.addEventListener('click', (event) => {
                    const target = event.target.closest('a');
                    if (target && target.href && !target.classList.contains('disabled')) {
                        event.preventDefault();
                        this.fetchLaporanData(target.href);
                        
                        // Smooth scroll to table
                        const tableElement = document.getElementById('detailPenjualanTable');
                        if (tableElement) {
                            tableElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            }

            // Table sorting
            document.querySelectorAll('[data-sort]').forEach(header => {
                header.addEventListener('click', () => {
                    const sortBy = header.dataset.sort;
                    this.sortTable(sortBy);
                });
            });
        }

        filterData() {
            const filterForm = document.getElementById('filterLaporanForm');
            if (!filterForm) return;
            
            const formData = new FormData(filterForm);
            const quickSearch = document.getElementById('quickSearch');
            if (quickSearch && quickSearch.value) {
                formData.append('search', quickSearch.value);
            }
            
            const perPageSelect = document.getElementById('perPageSelect');
            if (perPageSelect) {
                formData.set('per_page', perPageSelect.value);
            }
            
            formData.set('page', '1');
            const params = new URLSearchParams(formData);
            const url = "{{ route('pengurus.laporan.penjualan.umum') }}?" + params.toString();
            this.fetchLaporanData(url);
        }

        fetchLaporanData(url) {
            if (this.currentRequestController) {
                this.currentRequestController.abort();
            }
            this.currentRequestController = new AbortController();
            const signal = this.currentRequestController.signal;

            this.showLoading();

            fetch(url, { 
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

                // Update table content with animation
                const tbody = document.getElementById('detailPenjualanTableBody');
                if (tbody) {
                    tbody.style.opacity = '0';
                    
                    setTimeout(() => {
                        tbody.innerHTML = data.html || '';
                        tbody.style.opacity = '1';
                        tbody.style.transition = 'opacity 0.3s ease';
                    }, 150);
                }

                // Update pagination
                const paginationContainer = document.getElementById('paginationLinksDetailPenjualan');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }
                
                // Update statistics with animation
                if (data.ringkasan) {
                    this.updateStatsWithAnimation(data.ringkasan);
                }

                // Update URL
                window.history.pushState({path:url},'',url);

                // Update last updated time
                const lastUpdatedElement = document.getElementById('lastUpdated');
                if (lastUpdatedElement) {
                    lastUpdatedElement.textContent = new Date().toLocaleString('id-ID');
                }

                this.showNotification('Data berhasil diperbarui', 'success');
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Error fetching laporan:', error);
                    this.showNotification('Gagal memuat data laporan. Silakan coba lagi.', 'error');
                }
            })
            .finally(() => {
                this.hideLoading();
                this.currentRequestController = null;
            });
        }

        updateStatsWithAnimation(ringkasan) {
            // Animate counter updates
            if (ringkasan.total_omset_formatted) {
                this.animateCounter('totalOmset', ringkasan.total_omset_formatted);
            }
            if (ringkasan.total_item_terjual) {
                this.animateCounter('totalItemTerjual', ringkasan.total_item_terjual);
            }
            if (ringkasan.jumlah_transaksi) {
                this.animateCounter('jumlahTransaksi', ringkasan.jumlah_transaksi);
            }
        }

        animateCounter(elementId, newValue) {
            const element = document.getElementById(elementId);
            if (element) {
                element.style.transform = 'scale(1.1)';
                element.style.transition = 'transform 0.2s ease';
                
                setTimeout(() => {
                    element.textContent = newValue;
                    element.style.transform = 'scale(1)';
                }, 100);
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

        initializeCharts() {
            try {
                this.initUnitUsahaChart();
                this.initTrendChart();
            } catch (error) {
                console.error('Chart initialization failed:', error);
                this.showNotification('Gagal memuat grafik', 'warning');
            }
        }

        initUnitUsahaChart() {
            const canvas = document.getElementById('unitUsahaChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            this.charts.unitUsaha = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($filters['unit_usahas']->pluck('nama_unit_usaha')->take(5)),
                    datasets: [{
                        data: [30, 25, 25, 15, 5], // TODO: Replace with real data
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
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
                                    return context.label + ': ' + context.parsed + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        initTrendChart() {
            const canvas = document.getElementById('trendChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            this.charts.trend = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                    datasets: [{
                        label: 'Penjualan Harian (Juta Rupiah)',
                        data: [12, 19, 15, 25, 22, 30, 28], // TODO: Replace with real data
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
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + context.parsed.y + ' Juta';
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
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        }

        sortTable(sortBy) {
            // TODO: Implement table sorting logic
            console.log('Sort by:', sortBy);
            this.showNotification('Fitur sorting akan segera tersedia', 'info');
        }

        showNotification(message, type = 'info') {
            // Create notification element
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
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove
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
    window.exportToPDF = function() {
        try {
            const filterForm = document.getElementById('filterLaporanForm');
            if (!filterForm) return;
            const params = new URLSearchParams(new FormData(filterForm));
            params.set('export', 'pdf');
            window.open("{{ route('pengurus.laporan.penjualan.umum') }}?" + params.toString());
        } catch (error) {
            console.error('Export PDF failed:', error);
            salesReportManager.showNotification('Gagal mengekspor PDF. Silakan coba lagi.', 'error');
        }
    };

    window.exportToExcel = function() {
        try {
            const filterForm = document.getElementById('filterLaporanForm');
            if (!filterForm) return;
            const params = new URLSearchParams(new FormData(filterForm));
            params.set('export', 'excel');
            window.open("{{ route('pengurus.laporan.penjualan.umum') }}?" + params.toString());
        } catch (error) {
            console.error('Export Excel failed:', error);
            salesReportManager.showNotification('Gagal mengekspor Excel. Silakan coba lagi.', 'error');
        }
    };

    window.printReport = function() {
        try {
            window.print();
        } catch (error) {
            console.error('Print failed:', error);
            salesReportManager.showNotification('Gagal mencetak laporan. Silakan coba lagi.', 'error');
        }
    };

    window.setDateRange = function(range) {
        const daterangePicker = $('#daterange').data('daterangepicker');
        if (!daterangePicker) return;

        let startDate, endDate;
        
        switch(range) {
            case 'today':
                startDate = endDate = moment();
                break;
            case 'week':
                startDate = moment().subtract(6, 'days');
                endDate = moment();
                break;
            case 'month':
                startDate = moment().startOf('month');
                endDate = moment().endOf('month');
                break;
            case 'lastMonth':
                startDate = moment().subtract(1, 'month').startOf('month');
                endDate = moment().subtract(1, 'month').endOf('month');
                break;
        }
        
        if (startDate && endDate) {
            daterangePicker.setStartDate(startDate);
            daterangePicker.setEndDate(endDate);
            $('#tanggal_mulai').val(startDate.format('YYYY-MM-DD'));
            $('#tanggal_selesai').val(endDate.format('YYYY-MM-DD'));
        }
    };

    // Initialize the sales report manager
    let salesReportManager;
    document.addEventListener('DOMContentLoaded', function() {
        salesReportManager = new SalesReportManager();
    });
</script>
@endpush
