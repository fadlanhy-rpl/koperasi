@extends('layouts.app')

@section('title', 'Laporan Simpanan Wajib Belum Bayar - Koperasi')
@section('page-title', 'Simpanan Wajib Belum Dibayar')
@section('page-subtitle', 'Monitoring anggota yang belum melunasi simpanan wajib dengan analisis interaktif')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        --shadow-soft: 0 10px 25px rgba(0, 0, 0, 0.1);
        --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15);
        --border-radius: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-card {
        background: var(--primary-gradient);
        color: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(25px, -25px);
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

    .stats-card.danger {
        background: var(--danger-gradient);
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
        background: linear-gradient(90deg, rgba(239, 68, 68, 0.05) 0%, rgba(252, 165, 165, 0.05) 100%);
        border-left-color: #ef4444;
        transform: translateX(2px);
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

    .btn-success {
        background: var(--success-gradient);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        cursor: pointer;
        font-size: 12px;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(79, 172, 254, 0.3);
        color: white;
        text-decoration: none;
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
        border-top: 4px solid #ef4444;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Progress Bar */
    .progress-bar {
        width: 100%;
        height: 8px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
        overflow: hidden;
        margin-top: 1rem;
    }

    .progress-fill {
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 4px;
        transition: width 1s ease-in-out;
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
        
        .filter-section {
            padding: 1rem;
        }
        
        .stats-card {
            padding: 1rem;
            min-height: 100px;
        }
    }

    /* Alert Styles */
    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border: 1px solid #6ee7b7;
        color: #065f46;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 1px solid #f59e0b;
        color: #92400e;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<div class="animate-slide-in-up">
    <!-- Action Buttons -->
    <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                Simpanan Wajib Belum Bayar
            </h1>
            <p class="text-gray-600">Monitoring dan tindak lanjut anggota yang belum melunasi simpanan wajib</p>
        </div>
        
        <div class="flex flex-wrap gap-2">
            <button type="button" class="btn-primary" onclick="refreshData()">
                <i class="fas fa-sync-alt"></i>
                <span class="hidden sm:inline">Refresh</span>
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

    <!-- Summary Statistics -->
    <div class="stats-grid" id="statistikContainer">
        <div class="stats-card success">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-1">Total Anggota</p>
                <p class="text-2xl font-bold" id="totalAnggota">{{ number_format($statistik['total_anggota'] ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <div class="stats-card success">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-1">Sudah Bayar</p>
                <p class="text-2xl font-bold" id="sudahBayar">{{ number_format($statistik['sudah_bayar'] ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $statistik['persentase_bayar'] ?? 0 }}%"></div>
            </div>
        </div>
        
        <div class="stats-card danger">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-1">Belum Bayar</p>
                <p class="text-2xl font-bold" id="belumBayar">{{ number_format($statistik['belum_bayar'] ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ 100 - ($statistik['persentase_bayar'] ?? 0) }}%"></div>
            </div>
        </div>
        
        <div class="stats-card warning">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-percentage text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-1">Tingkat Kepatuhan</p>
                <p class="text-2xl font-bold" id="persentaseBayar">{{ number_format($statistik['persentase_bayar'] ?? 0, 1) }}%</p>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $statistik['persentase_bayar'] ?? 0 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                    Filter Periode
                </h2>
                <p class="text-gray-600">Pilih bulan dan tahun untuk melihat data simpanan wajib yang belum dibayar</p>
            </div>
        </div>

        <form method="GET" action="{{ route('pengurus.laporan.simpanan.wajibBelumBayar') }}" id="filterForm" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <div class="space-y-2">
                    <label for="bulan" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-calendar text-blue-500 mr-2"></i>
                        Pilih Bulan
                    </label>
                    <select name="bulan" id="bulan" class="form-input">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ ($periode['bulan'] ?? date('n')) == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label for="tahun" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>
                        Pilih Tahun
                    </label>
                    <select name="tahun" id="tahun" class="form-input">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ ($periode['tahun'] ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label for="per_page" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-list text-green-500 mr-2"></i>
                        Per Halaman
                    </label>
                    <select name="per_page" id="per_page" class="form-input">
                        <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 25) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 25) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-search mr-2"></i>
                        Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Alert for current period -->
    <div class="mb-6">
        @if($anggota_belum_bayar_wajib->total() == 0)
            <div class="alert-success">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-2xl mr-4"></i>
                    <div>
                        <h4 class="font-bold text-lg">Excellent! Semua Anggota Sudah Bayar</h4>
                        <p>Semua anggota telah melunasi simpanan wajib untuk periode {{ \Carbon\Carbon::create()->month($periode['bulan'])->translatedFormat('F') }} {{ $periode['tahun'] }}.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="alert-warning">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-2xl mr-4"></i>
                    <div>
                        <h4 class="font-bold text-lg">Perhatian: {{ $anggota_belum_bayar_wajib->total() }} Anggota Belum Bayar</h4>
                        <p>Terdapat {{ $anggota_belum_bayar_wajib->total() }} anggota yang belum melunasi simpanan wajib untuk periode {{ \Carbon\Carbon::create()->month($periode['bulan'])->translatedFormat('F') }} {{ $periode['tahun'] }}. Segera lakukan tindak lanjut.</p>
                    </div>
                </div>
            </div>
        @endif
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
                        <i class="fas fa-table text-red-500 mr-3"></i>
                        Daftar Anggota Belum Bayar
                    </h3>
                    <p class="text-gray-600">
                        Periode: <span class="font-semibold text-red-600">{{ \Carbon\Carbon::create()->month($periode['bulan'])->translatedFormat('F') }} {{ $periode['tahun'] }}</span>
                        @if($anggota_belum_bayar_wajib->total() > 0)
                            • Menampilkan {{ $anggota_belum_bayar_wajib->count() }} dari {{ $anggota_belum_bayar_wajib->total() }} anggota
                        @endif
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
                <table class="w-full min-w-[800px] text-sm" id="belumBayarTable">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                            <th class="py-4 px-4 text-left font-bold text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-hashtag mr-2 text-blue-500"></i>
                                    No.
                                </div>
                            </th>
                            <th class="py-4 px-4 text-left font-bold text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-user mr-2 text-green-500"></i>
                                    Nama Anggota
                                </div>
                            </th>
                            <th class="py-4 px-4 text-left font-bold text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-id-card mr-2 text-purple-500"></i>
                                    No. Anggota
                                </div>
                            </th>
                            <th class="py-4 px-4 text-left font-bold text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope mr-2 text-orange-500"></i>
                                    Email
                                </div>
                            </th>
                            <th class="py-4 px-4 text-center font-bold text-gray-700">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-cogs mr-2 text-red-500"></i>
                                    Aksi Cepat
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="belumBayarTableBody">
                        @include('pengurus.laporan.simpanan.partials._wajib_belum_bayar_rows', ['anggota_belum_bayar_wajib' => $anggota_belum_bayar_wajib])
                    </tbody>
                </table>
            </div>
            
            <!-- Enhanced Pagination -->
            <div id="paginationLinks" class="mt-8">
                {{ $anggota_belum_bayar_wajib->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('pengurus.laporan.simpanan.rekapTotal') }}" class="modern-card p-6 hover:scale-105 transition-transform">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-xl mr-4">
                    <i class="fas fa-chart-pie text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Rekapitulasi Total</h4>
                    <p class="text-sm text-gray-600">Lihat ringkasan total simpanan</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('pengurus.laporan.simpanan.rincianPerAnggota') }}" class="modern-card p-6 hover:scale-105 transition-transform">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-xl mr-4">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Rincian per Anggota</h4>
                    <p class="text-sm text-gray-600">Detail simpanan setiap anggota</p>
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
<script>
    class WajibBelumBayarManager {
        constructor() {
            this.currentRequestController = null;
            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            // Form submission with AJAX
            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.filterData();
                });
            }

            // Auto filter on select change
            const bulanSelect = document.getElementById('bulan');
            const tahunSelect = document.getElementById('tahun');
            const perPageSelect = document.getElementById('per_page');
            
            [bulanSelect, tahunSelect, perPageSelect].forEach(select => {
                if (select) {
                    select.addEventListener('change', () => {
                        this.filterData();
                    });
                }
            });

            // Pagination handling
            const paginationContainer = document.getElementById('paginationLinks');
            if (paginationContainer) {
                paginationContainer.addEventListener('click', (event) => {
                    const target = event.target.closest('a');
                    if (target && target.href && !target.classList.contains('disabled')) {
                        event.preventDefault();
                        this.fetchData(target.href);
                        
                        // Smooth scroll to table
                        const tableElement = document.getElementById('belumBayarTable');
                        if (tableElement) {
                            tableElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            }
        }

        filterData() {
            const filterForm = document.getElementById('filterForm');
            if (!filterForm) return;
            
            const formData = new FormData(filterForm);
            formData.set('page', '1');
            const params = new URLSearchParams(formData);
            const url = "{{ route('pengurus.laporan.simpanan.wajibBelumBayar') }}?" + params.toString();
            this.fetchData(url);
        }

        fetchData(url) {
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

                // Update table content
                const tbody = document.getElementById('belumBayarTableBody');
                if (tbody && data.html) {
                    tbody.style.opacity = '0';
                    
                    setTimeout(() => {
                        tbody.innerHTML = data.html;
                        tbody.style.opacity = '1';
                        tbody.style.transition = 'opacity 0.3s ease';
                    }, 150);
                }

                // Update pagination
                const paginationContainer = document.getElementById('paginationLinks');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }
                
                // Update statistics
                if (data.statistik) {
                    this.updateStatistics(data.statistik);
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
                    console.error('Error fetching data:', error);
                    this.showNotification('Gagal memuat data. Silakan coba lagi.', 'error');
                }
            })
            .finally(() => {
                this.hideLoading();
                this.currentRequestController = null;
            });
        }

        updateStatistics(statistik) {
            const elements = {
                'totalAnggota': statistik.total_anggota,
                'sudahBayar': statistik.sudah_bayar,
                'belumBayar': statistik.belum_bayar,
                'persentaseBayar': statistik.persentase_bayar.toFixed(1) + '%'
            };

            Object.entries(elements).forEach(([id, value]) => {
                const element = document.getElementById(id);
                if (element) {
                    element.style.transform = 'scale(1.1)';
                    element.style.transition = 'transform 0.2s ease';
                    
                    setTimeout(() => {
                        if (id === 'persentaseBayar') {
                            element.textContent = value;
                        } else {
                            element.textContent = value.toLocaleString('id-ID');
                        }
                        element.style.transform = 'scale(1)';
                    }, 100);
                }
            });

            // Update progress bars
            const progressBars = document.querySelectorAll('.progress-fill');
            if (progressBars.length >= 2) {
                progressBars[0].style.width = statistik.persentase_bayar + '%';
                progressBars[1].style.width = (100 - statistik.persentase_bayar) + '%';
                progressBars[2].style.width = statistik.persentase_bayar + '%';
            }
        }

        refreshData() {
            this.fetchData(window.location.href);
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
        wajibBelumBayarManager.refreshData();
    };

    window.exportToPDF = function() {
        try {
            const filterForm = document.getElementById('filterForm');
            if (!filterForm) return;
            const params = new URLSearchParams(new FormData(filterForm));
            params.set('export', 'pdf');
            window.open("{{ route('pengurus.laporan.simpanan.wajibBelumBayar') }}?" + params.toString());
        } catch (error) {
            console.error('Export PDF failed:', error);
            wajibBelumBayarManager.showNotification('Gagal mengekspor PDF. Silakan coba lagi.', 'error');
        }
    };

    window.exportToExcel = function() {
        try {
            const filterForm = document.getElementById('filterForm');
            if (!filterForm) return;
            const params = new URLSearchParams(new FormData(filterForm));
            params.set('export', 'excel');
            window.open("{{ route('pengurus.laporan.simpanan.wajibBelumBayar') }}?" + params.toString());
        } catch (error) {
            console.error('Export Excel failed:', error);
            wajibBelumBayarManager.showNotification('Gagal mengekspor Excel. Silakan coba lagi.', 'error');
        }
    };

    window.printReport = function() {
        try {
            window.print();
        } catch (error) {
            console.error('Print failed:', error);
            wajibBelumBayarManager.showNotification('Gagal mencetak laporan. Silakan coba lagi.', 'error');
        }
    };

    // Initialize the manager
    let wajibBelumBayarManager;
    document.addEventListener('DOMContentLoaded', function() {
        wajibBelumBayarManager = new WajibBelumBayarManager();
    });
</script>
@endpush
