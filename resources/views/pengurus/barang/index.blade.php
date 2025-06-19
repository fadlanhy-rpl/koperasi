@extends('layouts.app')

@section('title', 'Manajemen Barang - Koperasi')
@section('page-title', 'Manajemen Barang Koperasi')
@section('page-subtitle', 'Kelola daftar inventaris barang di semua unit usaha')

@push('styles')
<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .slide-in {
        animation: slideIn 0.5s ease-out;
    }
    @keyframes slideIn {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    @keyframes fadeInUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    /* Enhanced table styling */
    .enhanced-table {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .table-row {
        transition: all 0.3s ease;
    }
    .table-row:hover {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        transform: scale(1.01);
    }

    /* Product image styling */
    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        transition: all 0.3s ease;
    }
    .product-image:hover {
        transform: scale(1.1);
        border-color: #3b82f6;
        box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.1);
    }

    /* Action buttons */
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .action-btn.view {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .action-btn.edit {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
    }
    .action-btn.delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    /* Stock status indicators */
    .stock-indicator {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
        min-width: 60px;
    }
    .stock-empty {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #dc2626;
    }
    .stock-low {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #d97706;
    }
    .stock-good {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #16a34a;
    }

    /* Filter section */
    .filter-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    /* Enhanced Select2 styling */
    .select2-container--default .select2-selection--single {
        height: 48px !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 12px !important;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        padding-top: 8px !important;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }
    .select2-dropdown {
        border: 2px solid #3b82f6 !important;
        border-radius: 12px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
    }

    /* Search input styling */
    .search-input {
        position: relative;
    }
    .search-input input {
        padding-left: 3rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        height: 48px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        transition: all 0.3s ease;
    }
    .search-input input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: white;
    }
    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        z-index: 10;
    }

    /* Loading state */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        backdrop-filter: blur(4px);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen  py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header Section -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 slide-in mb-8">
            <div class="p-8 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-3xl">
                <div class="flex flex-col lg:flex-row justify-between lg:items-center gap-6">
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-500 p-3 rounded-xl">
                            <i class="fas fa-boxes text-white text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">Manajemen Barang</h1>
                            <p class="text-gray-600 mt-1">Kelola inventaris barang di semua unit usaha</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('pengurus.barang.create') }}" class="w-full lg:w-auto">
                        <button class="w-full lg:w-auto bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Tambah Barang Baru
                        </button>
                    </a>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="p-8">
                <form id="filterBarangForm" method="GET" action="{{ route('pengurus.barang.index') }}" class="filter-section">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="unit_usaha_filter_barang" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-building text-blue-500 mr-2"></i>Filter Unit Usaha
                            </label>
                            <select id="unit_usaha_filter_barang" name="unit_usaha_filter" class="w-full select2-basic">
                                <option value="">Semua Unit Usaha</option>
                                @foreach($unitUsahas as $unit)
                                    <option value="{{ $unit->id }}" {{ request('unit_usaha_filter') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->nama_unit_usaha }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="search_input_barang" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-search text-green-500 mr-2"></i>Pencarian Barang
                            </label>
                            <div class="search-input">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="search_input_barang" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode barang..." class="w-full">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 fade-in-up">
            <div class="p-8 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-slate-50 rounded-t-3xl">
                <div class="flex items-center space-x-3">
                    <div class="bg-gray-700 p-2 rounded-xl">
                        <i class="fas fa-list text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Daftar Barang</h3>
                        <p class="text-gray-600 text-sm">Total: {{ $barangs->total() }} barang</p>
                    </div>
                </div>
            </div>
            
            <div class="p-4 relative" id="tableContainer">
                <div class="border">
                    <table class="w-full" id="barangTable">
                        <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                            <tr>
                                <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-image mr-2 text-blue-500"></i>Gambar
                                </th>
                                <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-2 text-green-500"></i>Nama Barang
                                </th>
                                <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-building mr-2 text-purple-500"></i>Unit Usaha
                                </th>
                                <th class="py-4 px-6 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-shopping-cart mr-2 text-red-500"></i>Harga Beli
                                </th>
                                <th class="py-4 px-6 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-money-bill mr-2 text-blue-500"></i>Harga Jual
                                </th>
                                <th class="py-4 px-6 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-boxes mr-2 text-orange-500"></i>Stok
                                </th>
                                <th class="py-4 px-6 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-align-left mr-2 text-indigo-500"></i>Deskripsi
                                </th>
                                <th class="py-4 px-6 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-cogs mr-2 text-gray-500"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody id="barangTableBody" class="bg-white divide-y divide-gray-100">
                            @include('pengurus.barang.partials._barang_table_rows', ['barangs' => $barangs])
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div id="paginationLinksBarang" class="mt-8">
                    {{ $barangs->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('#unit_usaha_filter_barang').select2({
        placeholder: "Pilih Unit Usaha",
        width: '100%',
        allowClear: true
    });

    const unitUsahaFilter = document.getElementById('unit_usaha_filter_barang');
    const searchInput = document.getElementById('search_input_barang');
    const tableBody = document.getElementById('barangTableBody');
    const paginationContainer = document.getElementById('paginationLinksBarang');
    const tableContainer = document.getElementById('tableContainer');
    let debounceTimerBarang;
    let currentRequestController = null;

    function showLoading() {
        if (!document.getElementById('loadingOverlay')) {
            const loadingOverlay = document.createElement('div');
            loadingOverlay.id = 'loadingOverlay';
            loadingOverlay.className = 'loading-overlay';
            loadingOverlay.innerHTML = `
                <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                    <p class="mt-2 text-sm text-gray-600">Memuat data...</p>
                </div>
            `;
            tableContainer.style.position = 'relative';
            tableContainer.appendChild(loadingOverlay);
        }
    }

    function hideLoading() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.remove();
        }
    }

    function fetchBarangData(url) {
        if (currentRequestController) {
            currentRequestController.abort();
        }
        currentRequestController = new AbortController();
        const signal = currentRequestController.signal;

        showLoading();

        fetch(url, { 
            signal, 
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            } 
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (signal.aborted) return;
            
            if (data.html) tableBody.innerHTML = data.html;
            if (data.pagination) paginationContainer.innerHTML = data.pagination;
            
            // Add animation to new rows
            const rows = tableBody.querySelectorAll('.table-row');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 50);
            });
            
            hideLoading();
        })
        .catch(error => {
            hideLoading();
            if (error.name === 'AbortError') {
                console.log('Fetch barang aborted');
            } else {
                console.error('Error fetching barang:', error);
                showNotification('Gagal memuat data barang', 'error');
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-exclamation-triangle text-5xl mb-3 text-red-300"></i>
                                <p class="text-lg text-red-600">Gagal memuat data</p>
                                <p class="text-sm text-gray-500">Silakan coba lagi</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
        })
        .finally(() => {
            currentRequestController = null;
        });
    }
    
    function applyFiltersAndSearch() {
        const unitUsaha = unitUsahaFilter.value;
        const search = searchInput.value;
        const url = new URL("{{ route('pengurus.barang.index') }}");

        if (unitUsaha) url.searchParams.set('unit_usaha_filter', unitUsaha);
        if (search) url.searchParams.set('search', search);
        url.searchParams.set('page', '1');
        
        fetchBarangData(url.toString());
        window.history.pushState({path: url.toString()}, '', url.toString());
    }

    if (unitUsahaFilter) {
        $(unitUsahaFilter).on('change', function() {
            applyFiltersAndSearch();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimerBarang);
            debounceTimerBarang = setTimeout(() => {
                applyFiltersAndSearch();
            }, 500);
        });
    }

    if (paginationContainer) {
        paginationContainer.addEventListener('click', function(event) {
            const target = event.target.closest('a');
            if (target && target.href && !target.classList.contains('disabled') && !target.querySelector('span[aria-disabled="true"]')) {
                event.preventDefault();
                fetchBarangData(target.href);
                window.scrollTo({ 
                    top: document.getElementById('barangTable').offsetTop - 100, 
                    behavior: 'smooth' 
                });
            }
        });
    }
});

// Global delete confirmation function
function confirmDelete(deleteUrl, itemName) {
    if (window.Swal) {
        Swal.fire({
            title: 'Hapus Barang?',
            text: `Yakin ingin menghapus "${itemName}"? Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    } else {
        if (confirm(`Yakin ingin menghapus "${itemName}"?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
}

// Notification function
function showNotification(message, type = 'info') {
    if (window.showNotification) {
        window.showNotification(message, type);
    } else {
        alert(message);
    }
}
</script>
@endpush