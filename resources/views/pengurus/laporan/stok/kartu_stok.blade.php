@extends('layouts.app')

@section('title', 'Kartu Stok: ' . $barang->nama_barang)
@section('page-title', 'Kartu Stok Barang')
@section('page-subtitle', 'Detail pergerakan stok untuk: ' . $barang->nama_barang . ' (' . ($barang->kode_barang ?? 'N/A') . ')')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

    .btn-success {
        background: var(--success-gradient);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-soft);
        color: white;
        text-decoration: none;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-danger:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-soft);
        color: white;
        text-decoration: none;
    }

    .btn-neutral {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-neutral:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-soft);
        color: white;
        text-decoration: none;
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
    }

    .status-masuk {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .status-keluar {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .status-penyesuaian {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
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

    /* Info Card */
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: var(--border-radius);
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(50px, -50px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .info-card {
            padding: 1.5rem;
        }
        
        .table-header {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="animate-slide-in-up">
    <!-- Enhanced Info Barang & Stok Terkini -->
    <div class="info-card">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between relative z-10">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <i class="fas fa-box text-3xl mr-4"></i>
                    <div>
                        <h2 class="text-2xl font-bold">{{ $barang->nama_barang }}</h2>
                        <p class="text-blue-100 text-sm">
                            <i class="fas fa-barcode mr-2"></i>
                            Kode: {{ $barang->kode_barang ?? '-' }} | 
                            <i class="fas fa-store mr-2"></i>
                            Unit Usaha: {{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="bg-white/20 rounded-lg p-3">
                        <div class="text-xs text-blue-100 mb-1">Harga Beli</div>
                        <div class="font-bold">@rupiah($barang->harga_beli)</div>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <div class="text-xs text-blue-100 mb-1">Harga Jual</div>
                        <div class="font-bold">@rupiah($barang->harga_jual)</div>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <div class="text-xs text-blue-100 mb-1">Nilai Stok</div>
                        <div class="font-bold">@rupiah($barang->stok * $barang->harga_beli)</div>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <div class="text-xs text-blue-100 mb-1">Satuan</div>
                        <div class="font-bold">{{ $barang->satuan }}</div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 md:mt-0 md:ml-8 text-center">
                <p class="text-xs text-blue-100 uppercase tracking-wide mb-2">Stok Terkini</p>
                <div class="flex items-center justify-center">
                    <div class="text-center">
                        <p class="text-5xl font-bold {{ $barang->stok <= 10 && $barang->stok > 0 ? 'text-yellow-300' : ($barang->stok == 0 ? 'text-red-300' : 'text-green-300') }}">
                            {{ number_format($barang->stok, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-blue-100 mt-1">{{ $barang->satuan }}</p>
                        @if($barang->stok == 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-500 text-white mt-2">
                                <i class="fas fa-times-circle mr-1"></i>
                                Stok Habis
                            </span>
                        @elseif($barang->stok <= 10)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-500 text-white mt-2">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Stok Rendah
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500 text-white mt-2">
                                <i class="fas fa-check-circle mr-1"></i>
                                Stok Aman
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Actions -->
    <div class="mb-6 flex flex-wrap gap-3 justify-end">
        <button type="button" class="btn-primary" onclick="exportKartuStokToPDF()">
            <i class="fas fa-file-pdf"></i>
            Export PDF
        </button>
        <button type="button" class="btn-primary" onclick="exportKartuStokToExcel()">
            <i class="fas fa-file-excel"></i>
            Export Excel
        </button>
        <button type="button" class="btn-primary" onclick="printKartuStok()">
            <i class="fas fa-print"></i>
            Print
        </button>
    </div>

    <!-- Enhanced Tabel Histori Stok -->
    <div class="enhanced-table">
        <div class="table-header">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">
                        <i class="fas fa-history text-indigo-500 mr-3"></i>
                        Histori Pergerakan Stok
                    </h3>
                    <p class="text-gray-600">Riwayat lengkap transaksi masuk, keluar, dan penyesuaian stok</p>
                </div>
                
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Tampilkan:</label>
                    <select id="perPageSelectKartu" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="10">10</option>
                        <option value="15" selected>15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-sm" id="historiStokTableKartu">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                            <th class="py-4 px-4 text-left font-bold text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                    Tanggal & Waktu
                                </div>
                            </th>
                            <th class="py-4 px-4 text-left font-bold text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-exchange-alt mr-2 text-green-500"></i>
                                    Tipe Transaksi
                                </div>
                            </th>
                            <th class="py-4 px-4 text-center font-bold text-gray-700">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-hashtag mr-2 text-purple-500"></i>
                                    Jumlah
                                </div>
                            </th>
                            <th class="py-4 px-4 text-center font-bold text-gray-700">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-arrow-left mr-2 text-orange-500"></i>
                                    Stok Sebelum
                                </div>
                            </th>
                            <th class="py-4 px-4 text-center font-bold text-gray-700">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-arrow-right mr-2 text-red-500"></i>
                                    Stok Sesudah
                                </div>
                            </th>
                            <th class="py-4 px-4 text-left font-bold text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-sticky-note mr-2 text-indigo-500"></i>
                                    Keterangan
                                </div>
                            </th>
                            <th class="py-4 px-4 text-left font-bold text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-user mr-2 text-teal-500"></i>
                                    Dicatat Oleh
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="historiStokTableBodyKartu">
                        @include('pengurus.barang.partials._histori_stok_rows', ['historiStoks' => $kartu_stok])
                    </tbody>
                </table>
            </div>
            
            <div id="paginationLinksHistoriKartu" class="mt-8">
                {{ $kartu_stok->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    <!-- Enhanced Action Buttons -->
    <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
        <a href="{{ route('pengurus.laporan.stok.daftarTerkini') }}"> 
            <button type="button" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Laporan Stok
            </button>
        </a>
        
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('pengurus.stok.formBarangMasuk', ['barang' => $barang->id]) }}">
                <button type="button" class="btn-success">
                    <i class="fas fa-plus-circle mr-1"></i>
                    Stok Masuk
                </button>
            </a>
            <a href="{{ route('pengurus.stok.formBarangKeluar', ['barang' => $barang->id]) }}">
                <button type="button" class="btn-danger">
                    <i class="fas fa-minus-circle mr-1"></i>
                    Stok Keluar
                </button>
            </a>
            <a href="{{ route('pengurus.stok.formPenyesuaianStok', ['barang' => $barang->id]) }}">
                <button type="button" class="btn-neutral">
                    <i class="fas fa-exchange-alt mr-1"></i>
                    Penyesuaian
                </button>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    class KartuStokManager {
        constructor() {
            this.currentRequestController = null;
            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            // Per page selection
            const perPageSelect = document.getElementById('perPageSelectKartu');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', () => {
                    this.updatePerPage();
                });
            }

            // Pagination handling
            const paginationContainer = document.getElementById('paginationLinksHistoriKartu');
            if (paginationContainer) {
                paginationContainer.addEventListener('click', (event) => {
                    const target = event.target.closest('a');
                    if (target && target.href && !target.classList.contains('disabled')) {
                        event.preventDefault();
                        this.fetchKartuStokData(target.href);
                        
                        // Smooth scroll to table
                        const tableElement = document.getElementById('historiStokTableKartu');
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

        updatePerPage() {
            const perPageSelect = document.getElementById('perPageSelectKartu');
            if (!perPageSelect) return;
            
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('per_page', perPageSelect.value);
            currentUrl.searchParams.set('page_kartu_stok', '1');
            
            this.fetchKartuStokData(currentUrl.toString());
        }

        fetchKartuStokData(url) {
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
                const tbody = document.getElementById('historiStokTableBodyKartu');
                if (tbody && data.html_histori) {
                    tbody.style.opacity = '0';
                    
                    setTimeout(() => {
                        tbody.innerHTML = data.html_histori;
                        tbody.style.opacity = '1';
                        tbody.style.transition = 'opacity 0.3s ease';
                    }, 150);
                }

                // Update pagination
                const paginationContainer = document.getElementById('paginationLinksHistoriKartu');
                if (paginationContainer && data.pagination_histori) {
                    paginationContainer.innerHTML = data.pagination_histori;
                }

                // Update URL
                window.history.pushState({path:url},'',url);

                this.showNotification('Data berhasil diperbarui', 'success');
            })
            .catch(error => {
                if (error.name !== 'AbortError') {
                    console.error('Error fetching kartu stok data:', error);
                    this.showNotification('Gagal memuat data kartu stok. Silakan coba lagi.', 'error');
                }
            })
            .finally(() => {
                this.hideLoading();
                this.currentRequestController = null;
            });
        }

        showLoading() {
            const tbody = document.getElementById('historiStokTableBodyKartu');
            if (tbody) {
                tbody.style.opacity = '0.5';
                tbody.style.pointerEvents = 'none';
            }
        }

        hideLoading() {
            const tbody = document.getElementById('historiStokTableBodyKartu');
            if (tbody) {
                tbody.style.opacity = '1';
                tbody.style.pointerEvents = 'auto';
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
    window.exportKartuStokToPDF = function() {
        try {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('export', 'pdf');
            window.open(currentUrl.toString());
        } catch (error) {
            console.error('Export PDF failed:', error);
            kartuStokManager.showNotification('Gagal mengekspor PDF. Silakan coba lagi.', 'error');
        }
    };

    window.exportKartuStokToExcel = function() {
        try {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('export', 'excel');
            window.open(currentUrl.toString());
        } catch (error) {
            console.error('Export Excel failed:', error);
            kartuStokManager.showNotification('Gagal mengekspor Excel. Silakan coba lagi.', 'error');
        }
    };

    window.printKartuStok = function() {
        try {
            window.print();
        } catch (error) {
            console.error('Print failed:', error);
            kartuStokManager.showNotification('Gagal mencetak kartu stok. Silakan coba lagi.', 'error');
        }
    };

    // Initialize the kartu stok manager
    let kartuStokManager;
    document.addEventListener('DOMContentLoaded', function() {
        kartuStokManager = new KartuStokManager();
    });
</script>
@endpush
