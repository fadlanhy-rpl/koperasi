@extends('layouts.app')

@section('title', 'Rincian Simpanan per Anggota - Koperasi')
@section('page-title', 'Laporan Rincian Simpanan per Anggota')
@section('page-subtitle', 'Analisis detail simpanan untuk setiap anggota koperasi dengan visualisasi interaktif')

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

    .stats-card.info {
        background: var(--info-gradient);
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
        
        .filter-section {
            padding: 1rem;
        }
        
        .stats-card {
            padding: 1rem;
            min-height: 100px;
        }
    }
</style>
@endpush

@section('content')
<div class="animate-slide-in-up">
    <!-- Action Buttons -->
    <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                <i class="fas fa-users text-blue-500 mr-3"></i>
                Rincian Simpanan per Anggota
            </h1>
            <p class="text-gray-600">Monitoring detail simpanan setiap anggota koperasi</p>
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
    <div class="stats-grid" id="ringkasanContainer">
        <div class="stats-card">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-money-check-alt text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-1">Total Simpanan Pokok</p>
                <p class="text-2xl font-bold" id="totalPokok">Rp {{ number_format($ringkasan['total_pokok'] ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <div class="stats-card success">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-1">Total Simpanan Wajib</p>
                <p class="text-2xl font-bold" id="totalWajib">Rp {{ number_format($ringkasan['total_wajib'] ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <div class="stats-card warning">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-hand-holding-heart text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-1">Total Saldo Sukarela</p>
                <p class="text-2xl font-bold" id="totalSukarela">Rp {{ number_format($ringkasan['total_sukarela'] ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
        
        <div class="stats-card info">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <i class="fas fa-chart-pie text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm opacity-90 mb-1">Grand Total</p>
                <p class="text-2xl font-bold" id="grandTotal">Rp {{ number_format($ringkasan['grand_total'] ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">
                    <i class="fas fa-search text-blue-500 mr-2"></i>
                    Pencarian & Filter
                </h2>
                <p class="text-gray-600">Cari anggota berdasarkan nama atau nomor anggota</p>
            </div>
        </div>

        <form method="GET" action="{{ route('pengurus.laporan.simpanan.rincianPerAnggota') }}" id="filterForm" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 items-end">
                <div class="space-y-2">
                    <label for="search_anggota" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-user-search text-blue-500 mr-2"></i>
                        Cari Anggota
                    </label>
                    <input type="text" name="search_anggota" id="search_anggota" 
                           value="{{ request('search_anggota') }}" 
                           placeholder="Nama atau nomor anggota..." 
                           class="form-input">
                </div>
                
                <div class="space-y-2">
                    <label for="per_page" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-list text-purple-500 mr-2"></i>
                        Tampilkan per Halaman
                    </label>
                    <select name="per_page" id="per_page" class="form-input">
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary flex-1">
                        <i class="fas fa-search mr-2"></i>
                        Cari
                    </button>
                    <a href="{{ route('pengurus.laporan.simpanan.rincianPerAnggota') }}" class="btn-secondary">
                        <i class="fas fa-undo mr-2"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
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
                        Data Rincian Simpanan
                    </h3>
                    <p class="text-gray-600">
                        Menampilkan {{ $laporan_per_anggota->count() }} dari {{ $laporan_per_anggota->total() }} anggota
                        @if(request('search_anggota'))
                            dengan pencarian: <span class="font-semibold text-blue-600">"{{ request('search_anggota') }}"</span>
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
                <table class="w-full min-w-[1000px] text-sm" id="rincianTable">
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
                            <th class="py-4 px-4 text-right font-bold text-gray-700">
                                <div class="flex items-center justify-end">
                                    <i class="fas fa-money-check-alt mr-2 text-blue-500"></i>
                                    Simp. Pokok
                                </div>
                            </th>
                            <th class="py-4 px-4 text-right font-bold text-gray-700">
                                <div class="flex items-center justify-end">
                                    <i class="fas fa-calendar-check mr-2 text-green-500"></i>
                                    Simp. Wajib
                                </div>
                            </th>
                            <th class="py-4 px-4 text-right font-bold text-gray-700">
                                <div class="flex items-center justify-end">
                                    <i class="fas fa-hand-holding-heart mr-2 text-yellow-500"></i>
                                    Saldo Sukarela
                                </div>
                            </th>
                            <th class="py-4 px-4 text-right font-bold text-gray-700">
                                <div class="flex items-center justify-end">
                                    <i class="fas fa-chart-pie mr-2 text-indigo-500"></i>
                                    Total Semua
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
                    <tbody id="rincianTableBody">
                        @include('pengurus.laporan.simpanan.partials._rincian_rows', ['laporan_per_anggota' => $laporan_per_anggota])
                    </tbody>
                </table>
            </div>
            
            <!-- Enhanced Pagination -->
            <div id="paginationLinks" class="mt-8">
                {{ $laporan_per_anggota->links('vendor.pagination.tailwind') }}
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
        
        <a href="{{ route('pengurus.laporan.simpanan.wajibBelumBayar') }}" class="modern-card p-6 hover:scale-105 transition-transform">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-xl mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Wajib Belum Bayar</h4>
                    <p class="text-sm text-gray-600">Cek anggota yang belum bayar</p>
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
    class RincianSimpananManager {
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

            // Auto search on input
            let searchTimeout;
            const searchInput = document.getElementById('search_anggota');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        this.filterData();
                    }, 500);
                });
            }

            // Per page change
            const perPageSelect = document.getElementById('per_page');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', () => {
                    this.filterData();
                });
            }

            // Pagination handling
            const paginationContainer = document.getElementById('paginationLinks');
            if (paginationContainer) {
                paginationContainer.addEventListener('click', (event) => {
                    const target = event.target.closest('a');
                    if (target && target.href && !target.classList.contains('disabled')) {
                        event.preventDefault();
                        this.fetchData(target.href);
                        
                        // Smooth scroll to table
                        const tableElement = document.getElementById('rincianTable');
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
            const url = "{{ route('pengurus.laporan.simpanan.rincianPerAnggota') }}?" + params.toString();
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
                const tbody = document.getElementById('rincianTableBody');
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
                if (data.ringkasan) {
                    this.updateStatistics(data.ringkasan);
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

        updateStatistics(ringkasan) {
            const elements = {
                'totalPokok': ringkasan.total_pokok,
                'totalWajib': ringkasan.total_wajib,
                'totalSukarela': ringkasan.total_sukarela,
                'grandTotal': ringkasan.grand_total
            };

            Object.entries(elements).forEach(([id, value]) => {
                const element = document.getElementById(id);
                if (element) {
                    element.style.transform = 'scale(1.1)';
                    element.style.transition = 'transform 0.2s ease';
                    
                    setTimeout(() => {
                        element.textContent = 'Rp ' + value.toLocaleString('id-ID');
                        element.style.transform = 'scale(1)';
                    }, 100);
                }
            });
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
        rincianSimpananManager.refreshData();
    };

    window.exportToPDF = function() {
        try {
            const filterForm = document.getElementById('filterForm');
            if (!filterForm) return;
            const params = new URLSearchParams(new FormData(filterForm));
            params.set('export', 'pdf');
            window.open("{{ route('pengurus.laporan.simpanan.rincianPerAnggota') }}?" + params.toString());
        } catch (error) {
            console.error('Export PDF failed:', error);
            rincianSimpananManager.showNotification('Gagal mengekspor PDF. Silakan coba lagi.', 'error');
        }
    };

    window.exportToExcel = function() {
        try {
            const filterForm = document.getElementById('filterForm');
            if (!filterForm) return;
            const params = new URLSearchParams(new FormData(filterForm));
            params.set('export', 'excel');
            window.open("{{ route('pengurus.laporan.simpanan.rincianPerAnggota') }}?" + params.toString());
        } catch (error) {
            console.error('Export Excel failed:', error);
            rincianSimpananManager.showNotification('Gagal mengekspor Excel. Silakan coba lagi.', 'error');
        }
    };

    window.printReport = function() {
        try {
            window.print();
        } catch (error) {
            console.error('Print failed:', error);
            rincianSimpananManager.showNotification('Gagal mencetak laporan. Silakan coba lagi.', 'error');
        }
    };

    // Initialize the manager
    let rincianSimpananManager;
    document.addEventListener('DOMContentLoaded', function() {
        rincianSimpananManager = new RincianSimpananManager();
    });
</script>
@endpush
