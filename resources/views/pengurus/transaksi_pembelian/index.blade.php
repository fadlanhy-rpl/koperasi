@extends('layouts.app')

@section('title', 'Daftar Transaksi Pembelian - Koperasi')

@push('styles')
<style>
    .transaction-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.9) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .transaction-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899, #f59e0b);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .transaction-card:hover::before {
        opacity: 1;
    }

    .transaction-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .filter-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        z-index: 10;
    }

    .filter-input {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .filter-input:focus {
        background: rgba(255, 255, 255, 1);
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        transform: translateY(-1px);
    }

    .transaction-row {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 12px;
        margin-bottom: 12px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
    }

    .transaction-row:hover {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }

    /* Fixed grid layout for transaction rows */
    .transaction-grid {
        display: grid;
        grid-template-columns: 1fr 200px 120px 140px 120px 100px 80px;
        gap: 1rem;
        align-items: center;
        min-height: 80px;
    }

    @media (max-width: 1200px) {
        .transaction-grid {
            grid-template-columns: 1fr 180px 100px 120px 100px 80px 60px;
            gap: 0.75rem;
        }
    }

    @media (max-width: 1024px) {
        .transaction-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .transaction-mobile {
            display: block;
        }
        
        .transaction-desktop {
            display: none;
        }
    }

    @media (min-width: 1025px) {
        .transaction-mobile {
            display: none;
        }
        
        .transaction-desktop {
            display: grid;
        }
    }

    .status-badge-enhanced {
        position: relative;
        overflow: hidden;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 8px 16px;
        border-radius: 20px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .status-badge-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }

    .status-badge-enhanced:hover::before {
        left: 100%;
    }

    .status-lunas {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .status-cicilan {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .status-belum-lunas {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .action-button {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .action-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }

    .action-button:hover::before {
        left: 100%;
    }

    .action-button:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .action-view {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
    }

    /* Fixed floating stats positioning */
    .floating-stats {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 50;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 1rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        min-width: 200px;
        max-width: 250px;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .stat-item:last-child {
        border-bottom: none;
    }

    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
        border-radius: 8px;
    }

    .error-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #ef4444;
    }

    .error-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.7;
    }

    .error-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .error-state p {
        color: #6b7280;
        margin-bottom: 1.5rem;
    }

    .retry-button {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .retry-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
    }

    /* Transaction code styling */
    .transaction-code {
        font-weight: 700;
        color: #3b82f6;
        font-size: 0.9rem;
        line-height: 1.2;
        margin-bottom: 0.25rem;
        word-break: break-all;
    }

    .transaction-date {
        font-size: 0.75rem;
        color: #6b7280;
        line-height: 1.2;
    }

    /* Member info styling */
    .member-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 0;
    }

    .member-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .member-details {
        min-width: 0;
        flex: 1;
    }

    .member-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 0.9rem;
        line-height: 1.2;
        margin-bottom: 0.25rem;
        word-break: break-word;
    }

    .member-id {
        font-size: 0.75rem;
        color: #6b7280;
        line-height: 1.2;
    }

    /* Amount styling */
    .amount-display {
        text-align: right;
    }

    .amount-value {
        font-weight: 700;
        color: #1f2937;
        font-size: 1rem;
        line-height: 1.2;
        margin-bottom: 0.25rem;
    }

    .amount-method {
        font-size: 0.75rem;
        color: #6b7280;
        line-height: 1.2;
    }

    /* Items info */
    .items-info {
        font-size: 0.85rem;
        color: #6b7280;
        line-height: 1.2;
    }

    /* Mobile layout */
    .mobile-transaction-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .mobile-transaction-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .mobile-transaction-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    @media (max-width: 1400px) {
        .floating-stats {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .filter-card {
            padding: 1.5rem;
        }
        
        .transaction-row {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header Section -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">
            <i class="fas fa-receipt mr-3 text-blue-500"></i>
            Riwayat Transaksi Pembelian
        </h1>
        <p class="text-gray-600 text-lg">Kelola dan pantau semua transaksi pembelian anggota</p>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold mb-2">
                    <i class="fas fa-filter mr-2"></i>
                    Filter & Pencarian
                </h3>
                <p class="opacity-90">Temukan transaksi dengan mudah</p>
            </div>
            <a href="{{ route('pengurus.transaksi-pembelian.create') }}" 
               class="bg-white text-purple-600 px-6 py-3 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="fas fa-plus-circle mr-2"></i>
                Buat Transaksi Baru
            </a>
        </div>

        <form method="GET" action="{{ route('pengurus.transaksi-pembelian.index') }}" id="filterTransaksiForm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-2 opacity-90">
                        <i class="fas fa-search mr-1"></i>
                        Cari Transaksi
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Kode/Nama Anggota..." 
                           class="filter-input w-full text-gray-800" id="searchInput">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 opacity-90">
                        <i class="fas fa-tags mr-1"></i>
                        Status Pembayaran
                    </label>
                    <select name="status_pembayaran" class="filter-input w-full text-gray-800" id="statusFilter">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ request('status_pembayaran', 'all') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 opacity-90">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Tanggal Mulai
                    </label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" 
                           class="filter-input w-full text-gray-800" id="startDateFilter">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2 opacity-90">
                        <i class="fas fa-calendar-check mr-1"></i>
                        Tanggal Selesai
                    </label>
                    <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" 
                           class="filter-input w-full text-gray-800" id="endDateFilter">
                </div>
                <div class="flex items-end">
                    <div class="w-full space-y-2">
                        <button type="button" id="applyTransaksiFilterBtn" 
                                class="w-full bg-white text-purple-600 px-4 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-search mr-2"></i>
                            Terapkan Filter
                        </button>
                        <a href="{{ route('pengurus.transaksi-pembelian.index') }}" 
                           class="block w-full bg-purple-800 text-white px-4 py-2 rounded-xl font-semibold hover:bg-purple-900 transition-all duration-300 text-center">
                            <i class="fas fa-undo mr-2"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="transaction-card">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-list mr-2 text-green-500"></i>
                    Daftar Transaksi
                </h3>
                <div class="text-sm text-gray-600">
                    Total: <span class="font-semibold text-blue-600" id="totalTransaksiCount">{{ $pembelians->total() }}</span> transaksi
                </div>
            </div>

            <!-- Desktop Table Header -->
            <div class="transaction-desktop transaction-grid mb-4 pb-3 border-b-2 border-gray-200 font-semibold text-gray-700 text-sm">
                <div>Transaksi & Tanggal</div>
                <div>Anggota</div>
                <div>Kasir</div>
                <div>Total Harga</div>
                <div>Status</div>
                <div>Items</div>
                <div>Aksi</div>
            </div>

            <div class="overflow-x-auto">
                <div class="min-w-full" id="transaksiTableContainer">
                    <div class="space-y-2" id="transaksiTableBody">
                        @if($pembelians->count() > 0)
                            @include('pengurus.transaksi_pembelian.partials._transaksi_table_rows', ['pembelians' => $pembelians])
                        @else
                            <div class="text-center py-16">
                                <div class="mb-6">
                                    <i class="fas fa-receipt text-6xl text-gray-300"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada transaksi ditemukan</h3>
                                <p class="text-gray-500 mb-6">Belum ada data transaksi pembelian atau sesuaikan filter pencarian</p>
                                <a href="{{ route('pengurus.transaksi-pembelian.create') }}" 
                                   class="inline-flex items-center bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-full font-semibold hover:from-blue-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Buat Transaksi Pertama
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div id="paginationLinksTransaksi" class="mt-8">
                @if($pembelians->hasPages())
                    {{ $pembelians->links('vendor.pagination.tailwind') }}
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Floating Stats (Desktop Only) -->
{{-- <div class="floating-stats">
    <h4 class="font-bold text-gray-800 mb-3 text-center">
        <i class="fas fa-chart-bar mr-1"></i>
        Statistik
    </h4>
    <div class="stat-item">
        <span class="text-sm text-gray-600">Total Transaksi</span>
        <span class="font-bold text-blue-600" id="floatingTotalCount">{{ $pembelians->total() }}</span>
    </div>
    <div class="stat-item">
        <span class="text-sm text-gray-600">Halaman</span>
        <span class="font-bold text-purple-600" id="floatingPageInfo">{{ $pembelians->currentPage() }}/{{ $pembelians->lastPage() }}</span>
    </div>
</div> --}}
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterTransaksiForm');
    const tableBody = document.getElementById('transaksiTableBody');
    const paginationContainer = document.getElementById('paginationLinksTransaksi');
    const applyFilterBtn = document.getElementById('applyTransaksiFilterBtn');
    const searchInput = document.getElementById('searchInput');
    const totalCountEl = document.getElementById('totalTransaksiCount');
    const floatingTotalEl = document.getElementById('floatingTotalCount');
    const floatingPageEl = document.getElementById('floatingPageInfo');
    
    let currentRequestController = null;
    let searchDebounceTimer;

    function showLoadingSkeleton() {
        const skeletonHTML = Array(5).fill(0).map(() => `
            <div class="transaction-row">
                <div class="transaction-desktop transaction-grid">
                    <div class="loading-skeleton h-4 w-32"></div>
                    <div class="loading-skeleton h-4 w-24"></div>
                    <div class="loading-skeleton h-4 w-20"></div>
                    <div class="loading-skeleton h-4 w-28"></div>
                    <div class="loading-skeleton h-6 w-16 rounded-full"></div>
                    <div class="loading-skeleton h-4 w-20"></div>
                    <div class="loading-skeleton h-8 w-8 rounded-lg"></div>
                </div>
            </div>
        `).join('');
        tableBody.innerHTML = skeletonHTML;
    }

    function showErrorState() {
        tableBody.innerHTML = `
            <div class="error-state">
                <i class="fas fa-exclamation-triangle text-red-400"></i>
                <h3>Gagal memuat data transaksi</h3>
                <p>Terjadi kesalahan saat mengambil data. Silakan coba lagi.</p>
                <button onclick="retryLoadData()" class="retry-button">
                    <i class="fas fa-redo mr-2"></i>Coba Lagi
                </button>
            </div>
        `;
    }

    function fetchTransaksiData(url) {
        // Cancel previous request if exists
        if (currentRequestController) {
            currentRequestController.abort();
        }
        
        currentRequestController = new AbortController();
        const signal = currentRequestController.signal;

        showLoadingSkeleton();

        // Build URL with current form data
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        
        // Add form data to params
        for (let [key, value] of formData.entries()) {
            if (value.trim() !== '') {
                params.append(key, value);
            }
        }
        
        // Extract page from URL if exists
        const urlObj = new URL(url, window.location.origin);
        if (urlObj.searchParams.has('page')) {
            params.set('page', urlObj.searchParams.get('page'));
        }

        const finalUrl = `{{ route('pengurus.transaksi-pembelian.index') }}?${params.toString()}`;

        fetch(finalUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            signal: signal
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (signal.aborted) return;
            
            if (data.html) {
                tableBody.innerHTML = data.html;
                
                // Animate new rows
                tableBody.querySelectorAll('.transaction-row').forEach((row, index) => {
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        row.style.transition = 'all 0.4s ease';
                        row.style.opacity = '1';
                        row.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            }
            
            if (data.pagination) {
                paginationContainer.innerHTML = data.pagination;
            }
            
            // Update counts if provided
            if (data.total !== undefined) {
                if (totalCountEl) totalCountEl.textContent = data.total;
                if (floatingTotalEl) floatingTotalEl.textContent = data.total;
            }
            
            if (data.page_info) {
                if (floatingPageEl) floatingPageEl.textContent = data.page_info;
            }
            
            // Update URL without page reload
            if (finalUrl !== window.location.href) {
                window.history.pushState({path: finalUrl}, '', finalUrl);
            }
        })
        .catch(error => {
            if (error.name !== 'AbortError') {
                console.error('Fetch error:', error);
                showErrorState();
                
                // Show notification
                if (window.showNotification) {
                    window.showNotification('Gagal memuat data transaksi', 'error');
                }
            }
        })
        .finally(() => {
            currentRequestController = null;
        });
    }

    // Global retry function
    window.retryLoadData = function() {
        const currentUrl = window.location.href;
        fetchTransaksiData(currentUrl);
    };

    // Apply filter button
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            fetchTransaksiData(window.location.href);
        });
    }

    // Live search with debounce
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(() => {
                fetchTransaksiData(window.location.href);
            }, 800);
        });
    }

    // Filter change handlers
    ['statusFilter', 'startDateFilter', 'endDateFilter'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', function() {
                fetchTransaksiData(window.location.href);
            });
        }
    });

    // Pagination handling
    if (paginationContainer) {
        paginationContainer.addEventListener('click', function(event) {
            const target = event.target.closest('a');
            if (target && target.href && !target.classList.contains('disabled')) {
                event.preventDefault();
                fetchTransaksiData(target.href);
                
                // Smooth scroll to top of table
                document.getElementById('transaksiTableContainer').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    }

    // Enhanced form interactions
    const filterInputs = document.querySelectorAll('.filter-input');
    filterInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + F to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            if (searchInput) searchInput.focus();
        }
        
        // Ctrl/Cmd + Enter to apply filter
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            if (applyFilterBtn) applyFilterBtn.click();
        }
    });

    // Handle browser back/forward
    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.path) {
            fetchTransaksiData(e.state.path);
        } else {
            location.reload();
        }
    });
});
</script>
@endpush
