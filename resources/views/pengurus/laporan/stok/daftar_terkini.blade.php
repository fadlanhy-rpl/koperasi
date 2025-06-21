@extends('layouts.app')

@section('title', 'Laporan Stok Barang Terkini - Koperasi')
@section('page-title', 'Laporan Stok Barang Terkini')
@section('page-subtitle', 'Pantau jumlah dan nilai stok barang koperasi secara real-time')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --danger-gradient: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        --shadow-soft: 0 10px 25px rgba(0, 0, 0, 0.1);
        --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15);
        --border-radius: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

    .stats-card.danger {
        background: var(--danger-gradient);
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

    /* Status Indicators */
    .stok-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .stok-aman {
        background: #dcfce7;
        color: #166534;
    }

    .stok-rendah {
        background: #fef3c7;
        color: #92400e;
    }

    .stok-habis {
        background: #fee2e2;
        color: #991b1b;
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
            padding: 1.5rem;
            min-height: 140px;
        }
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
                    Filter & Analisis Stok
                </h2>
                <p class="text-gray-600">Gunakan filter di bawah untuk menganalisis data stok sesuai kebutuhan</p>
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
            <button type="button" class="quick-action-btn" onclick="setStokFilter('rendah')">Stok Rendah (≤10)</button>
            <button type="button" class="quick-action-btn" onclick="setStokFilter('habis')">Stok Habis</button>
            <button type="button" class="quick-action-btn" onclick="setStokFilter('aman')">Stok Aman (>10)</button>
            <button type="button" class="quick-action-btn" onclick="clearFilters()">Reset Semua Filter</button>
        </div>

        <form method="GET" action="{{ route('pengurus.laporan.stok.daftarTerkini') }}" id="filterStokForm" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <div class="space-y-2">
                    <label for="search_barang_stok" class="form-label">
                        <i class="fas fa-search text-blue-500 mr-2"></i>
                        Cari Barang
                    </label>
                    <input type="text" id="search_barang_stok" name="search_barang" 
                           value="{{ request('search_barang') }}" 
                           placeholder="Nama atau Kode Barang..."
                           class="form-input">
                </div>
                
                <div class="space-y-2">
                    <label for="unit_usaha_id_stok" class="form-label">
                        <i class="fas fa-store text-purple-500 mr-2"></i>
                        Unit Usaha
                    </label>
                    <select name="unit_usaha_id" id="unit_usaha_id_stok" class="select2-filter-stok">
                        <option value="">🏪 Semua Unit Usaha</option>
                        @foreach($filters['unit_usahas'] as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_usaha_id') == $unit->id ? 'selected' : '' }}>
                                🏢 {{ $unit->nama_unit_usaha }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label for="stok_kurang_dari_filter" class="form-label">
                        <i class="fas fa-arrow-down text-red-500 mr-2"></i>
                        Stok Kurang Dari
                    </label>
                    <input type="number" id="stok_kurang_dari_filter" name="stok_kurang_dari" 
                           value="{{ request('stok_kurang_dari') }}" 
                           placeholder="Contoh: 10"
                           class="form-input">
                </div>

                <div class="space-y-2">
                    <label for="stok_lebih_dari_filter" class="form-label">
                        <i class="fas fa-arrow-up text-green-500 mr-2"></i>
                        Stok Lebih Dari
                    </label>
                    <input type="number" id="stok_lebih_dari_filter" name="stok_lebih_dari" 
                           value="{{ request('stok_lebih_dari') }}" 
                           placeholder="Contoh: 50"
                           class="form-input">
                </div>

                <div class="space-y-2">
                    <label for="sort_by" class="form-label">
                        <i class="fas fa-sort text-indigo-500 mr-2"></i>
                        Urutkan Berdasarkan
                    </label>
                    <select name="sort_by" id="sort_by" class="form-input">
                        <option value="nama_barang" {{ request('sort_by') == 'nama_barang' ? 'selected' : '' }}>Nama Barang</option>
                        <option value="stok" {{ request('sort_by') == 'stok' ? 'selected' : '' }}>Jumlah Stok</option>
                        <option value="nilai_stok" {{ request('sort_by') == 'nilai_stok' ? 'selected' : '' }}>Nilai Stok</option>
                        <option value="unit_usaha" {{ request('sort_by') == 'unit_usaha' ? 'selected' : '' }}>Unit Usaha</option>
                    </select>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pt-6 border-t border-gray-200 gap-4">
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-filter mr-2"></i>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('pengurus.laporan.stok.daftarTerkini') }}" class="btn-secondary">
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
    <div class="stats-grid" id="ringkasanStokContainer">
        <div class="stats-card">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <i class="fas fa-coins text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-80">Total Items</div>
                    <div class="text-lg font-bold text-green-200" id="totalItems">{{ number_format($total_items, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-2">Total Nilai Stok (Harga Beli)</p>
                <p class="text-3xl font-bold mb-2" id="totalNilaiStok">@rupiah($total_nilai_stok_estimasi)</p>
            </div>
            <div class="flex items-center text-sm opacity-80">
                <i class="fas fa-chart-line mr-2"></i>
                Estimasi berdasarkan harga beli
            </div>
        </div>
        
        <div class="stats-card success">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-80">Status</div>
                    <div class="text-lg font-bold text-green-200">Stok Aman</div>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-2">Barang Stok Aman</p>
                <p class="text-3xl font-bold mb-2" id="stokAmanCount">{{ number_format(($total_items - $stok_rendah_count - $stok_habis_count), 0, ',', '.') }}</p>
            </div>
            <div class="flex items-center text-sm opacity-80">
                <i class="fas fa-shield-alt mr-2"></i>
                Stok > 10 unit
            </div>
        </div>
        
        <div class="stats-card warning">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-80">Perhatian</div>
                    <div class="text-lg font-bold text-yellow-200">Stok Rendah</div>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-2">Barang Stok Rendah</p>
                <p class="text-3xl font-bold mb-2" id="stokRendahCount">{{ number_format($stok_rendah_count, 0, ',', '.') }}</p>
            </div>
            <div class="flex items-center text-sm opacity-80">
                <i class="fas fa-arrow-down mr-2"></i>
                Stok ≤ 10 unit
            </div>
        </div>

        <div class="stats-card danger">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
                <div class="text-right">
                    <div class="text-sm opacity-80">Kritis</div>
                    <div class="text-lg font-bold text-red-200">Stok Habis</div>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-2">Barang Stok Habis</p>
                <p class="text-3xl font-bold mb-2" id="stokHabisCount">{{ number_format($stok_habis_count, 0, ',', '.') }}</p>
            </div>
            <div class="flex items-center text-sm opacity-80">
                <i class="fas fa-ban mr-2"></i>
                Stok = 0 unit
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
                        <i class="fas fa-boxes text-indigo-500 mr-3"></i>
                        Daftar Stok Barang Terkini
                    </h3>
                    <p class="text-gray-600">
                        @if(request()->hasAny(['search_barang', 'unit_usaha_id', 'stok_kurang_dari', 'stok_lebih_dari']))
                            Menampilkan hasil filter yang diterapkan
                        @else
                            Menampilkan semua data stok barang
                        @endif
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
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] text-sm" id="stokTerkiniTable">
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
                                    <i class="fas fa-barcode mr-2 text-green-500"></i>
                                    Kode Barang
                                </div>
                            </th>
                            <th class="py-4 px-4 text-left font-bold text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-box mr-2 text-purple-500"></i>
                                    Nama Barang
                                </div>
                            </th>
                            <th class="py-4 px-4 text-left font-bold text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-store mr-2 text-orange-500"></i>
                                    Unit Usaha
                                </div>
                            </th>
                            <th class="py-4 px-4 text-center font-bold text-gray-700">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-cubes mr-2 text-red-500"></i>
                                    Stok Terkini
                                </div>
                            </th>
                            <th class="py-4 px-4 text-right font-bold text-gray-700">
                                <div class="flex items-center justify-end">
                                    <i class="fas fa-tag mr-2 text-indigo-500"></i>
                                    Harga Beli
                                </div>
                            </th>
                            <th class="py-4 px-4 text-right font-bold text-gray-700">
                                <div class="flex items-center justify-end">
                                    <i class="fas fa-tags mr-2 text-teal-500"></i>
                                    Harga Jual
                                </div>
                            </th>
                            <th class="py-4 px-4 text-right font-bold text-gray-700">
                                <div class="flex items-center justify-end">
                                    <i class="fas fa-calculator mr-2 text-yellow-500"></i>
                                    Nilai Stok
                                </div>
                            </th>
                            <th class="py-4 px-4 text-center font-bold text-gray-700">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-cogs mr-2 text-gray-500"></i>
                                    Aksi
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="stokTerkiniTableBody">
                        @include('pengurus.laporan.stok.partials._stok_terkini_rows', ['daftar_stok' => $daftar_stok])
                    </tbody>
                </table>
            </div>
            
            <!-- Enhanced Pagination -->
            <div id="paginationLinksStokTerkini" class="mt-8">
                {{ $daftar_stok->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8 flex justify-start">
        <a href="{{ route('pengurus.dashboard') }}"> 
            <button type="button" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard Pengurus
            </button>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    class StockReportManager {
        constructor() {
            this.currentRequestController = null;
            this.init();
        }

        init() {
            this.initializeComponents();
            this.bindEvents();
        }

        initializeComponents() {
            try {
                // Initialize Select2
                $('.select2-filter-stok').select2({
                    placeholder: "Pilih Unit Usaha",
                    width: '100%',
                    allowClear: true,
                    dropdownParent: $('body')
                });
            } catch (error) {
                console.warn('Component initialization failed:', error);
                this.showNotification('Gagal menginisialisasi komponen', 'warning');
            }
        }

        bindEvents() {
            // Form submission
            const filterForm = document.getElementById('filterStokForm');
            if (filterForm) {
                filterForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.filterData();
                });
            }

            // Quick search
            let searchTimeout;
            const searchInput = document.getElementById('search_barang_stok');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
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
            const paginationContainer = document.getElementById('paginationLinksStokTerkini');
            if (paginationContainer) {
                paginationContainer.addEventListener('click', (event) => {
                    const target = event.target.closest('a');
                    if (target && target.href && !target.classList.contains('disabled')) {
                        event.preventDefault();
                        this.fetchStokData(target.href);
                        
                        // Smooth scroll to table
                        const tableElement = document.getElementById('stokTerkiniTable');
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
            const filterForm = document.getElementById('filterStokForm');
            if (!filterForm) return;
            
            const formData = new FormData(filterForm);
            const perPageSelect = document.getElementById('perPageSelect');
            if (perPageSelect) {
                formData.set('per_page', perPageSelect.value);
            }
            
            formData.set('page', '1');
            const params = new URLSearchParams(formData);
            const url = "{{ route('pengurus.laporan.stok.daftarTerkini') }}?" + params.toString();
            this.fetchStokData(url);
        }

        fetchStokData(url) {
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
                const tbody = document.getElementById('stokTerkiniTableBody');
                if (tbody) {
                    tbody.style.opacity = '0';
                    
                    setTimeout(() => {
                        tbody.innerHTML = data.html || '';
                        tbody.style.opacity = '1';
                        tbody.style.transition = 'opacity 0.3s ease';
                    }, 150);
                }

                // Update pagination
                const paginationContainer = document.getElementById('paginationLinksStokTerkini');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }
                
                // Update statistics with animation
                if (data.summary) {
                    this.updateStatsWithAnimation(data.summary);
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
                    console.error('Error fetching stok data:', error);
                    this.showNotification('Gagal memuat data stok. Silakan coba lagi.', 'error');
                }
            })
            .finally(() => {
                this.hideLoading();
                this.currentRequestController = null;
            });
        }

        updateStatsWithAnimation(summary) {
            // Animate counter updates
            if (summary.total_nilai_stok_formatted) {
                this.animateCounter('totalNilaiStok', summary.total_nilai_stok_formatted);
            }
            if (summary.total_items) {
                this.animateCounter('totalItems', summary.total_items);
            }
            if (summary.stok_rendah_count) {
                this.animateCounter('stokRendahCount', summary.stok_rendah_count);
            }
            if (summary.stok_habis_count) {
                this.animateCounter('stokHabisCount', summary.stok_habis_count);
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
            const filterForm = document.getElementById('filterStokForm');
            if (!filterForm) return;
            const params = new URLSearchParams(new FormData(filterForm));
            params.set('export', 'pdf');
            window.open("{{ route('pengurus.laporan.stok.daftarTerkini') }}?" + params.toString());
        } catch (error) {
            console.error('Export PDF failed:', error);
            stockReportManager.showNotification('Gagal mengekspor PDF. Silakan coba lagi.', 'error');
        }
    };

    window.exportToExcel = function() {
        try {
            const filterForm = document.getElementById('filterStokForm');
            if (!filterForm) return;
            const params = new URLSearchParams(new FormData(filterForm));
            params.set('export', 'excel');
            window.open("{{ route('pengurus.laporan.stok.daftarTerkini') }}?" + params.toString());
        } catch (error) {
            console.error('Export Excel failed:', error);
            stockReportManager.showNotification('Gagal mengekspor Excel. Silakan coba lagi.', 'error');
        }
    };

    window.printReport = function() {
        try {
            window.print();
        } catch (error) {
            console.error('Print failed:', error);
            stockReportManager.showNotification('Gagal mencetak laporan. Silakan coba lagi.', 'error');
        }
    };

    window.setStokFilter = function(type) {
        const stokKurangDari = document.getElementById('stok_kurang_dari_filter');
        const stokLebihDari = document.getElementById('stok_lebih_dari_filter');
        
        // Clear both fields first
        stokKurangDari.value = '';
        stokLebihDari.value = '';
        
        switch(type) {
            case 'rendah':
                stokKurangDari.value = '10';
                stokLebihDari.value = '1';
                break;
            case 'habis':
                stokKurangDari.value = '0';
                break;
            case 'aman':
                stokLebihDari.value = '10';
                break;
        }
        
        stockReportManager.filterData();
    };

    window.clearFilters = function() {
        document.getElementById('filterStokForm').reset();
        $('.select2-filter-stok').val(null).trigger('change');
        stockReportManager.filterData();
    };

    // Initialize the stock report manager
    let stockReportManager;
    document.addEventListener('DOMContentLoaded', function() {
        stockReportManager = new StockReportManager();
    });
</script>
@endpush
