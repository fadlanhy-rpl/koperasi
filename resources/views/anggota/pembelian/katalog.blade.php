@extends('layouts.app')

@section('title', 'Katalog Barang Koperasi - Koperasi')
@section('page-title', 'Katalog Produk Koperasi')
@section('page-subtitle', 'Temukan produk terbaik dari berbagai unit usaha kami')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* Enhanced Select2 Styling */
.select2-container .select2-selection--single {
    height: 50px !important;
    border-radius: 16px !important;
    border: 2px solid #e5e7eb !important;
    padding-top: 10px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    background: rgba(255, 255, 255, 0.9) !important;
    backdrop-filter: blur(10px) !important;
}

.select2-container--default .select2-selection--single:focus,
.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 48px !important;
    right: 15px !important;
}

.select2-dropdown {
    border-radius: 16px !important;
    border: 2px solid #e5e7eb !important;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
    backdrop-filter: blur(20px) !important;
}

/* Advanced Product Card Styling */
.product-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.92) 100%);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 24px;
    overflow: hidden;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(147, 51, 234, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
    z-index: 1;
}

.product-card:hover::before {
    opacity: 1;
}

.product-card:hover {
    transform: translateY(-12px) scale(1.03);
    box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.2);
    border-color: rgba(59, 130, 246, 0.4);
}

.product-image-container {
    position: relative;
    height: 280px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    overflow: hidden;
    border-radius: 20px 20px 0 0;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.product-image:hover {
    transform: scale(1.15) rotate(2deg);
}

.product-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    color: #9ca3af;
    font-size: 3.5rem;
    cursor: pointer;
    transition: all 0.4s ease;
    position: relative;
}

.product-image-placeholder::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100px;
    height: 100px;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0);
    transition: transform 0.3s ease;
}

.product-image-placeholder:hover::before {
    transform: translate(-50%, -50%) scale(2);
}

.product-image-placeholder:hover {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: #3b82f6;
    transform: scale(1.05);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.4) 100%);
    opacity: 0;
    transition: all 0.4s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    backdrop-filter: blur(2px);
}

.product-image-container:hover .product-overlay {
    opacity: 1;
}

.stock-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 700;
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    z-index: 10;
}

.stock-available {
    background: rgba(34, 197, 94, 0.95);
    color: white;
    box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3);
}

.stock-low {
    background: rgba(251, 191, 36, 0.95);
    color: white;
    box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
}

.stock-empty {
    background: rgba(239, 68, 68, 0.95);
    color: white;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.price-tag {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    padding: 12px 20px;
    border-radius: 16px;
    font-weight: 800;
    font-size: 1.25rem;
    display: inline-block;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    position: relative;
    overflow: hidden;
}

.price-tag::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.price-tag:hover::before {
    left: 100%;
}

.unit-badge {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
    padding: 6px 16px;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
}

.filter-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.9) 100%);
    backdrop-filter: blur(25px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 28px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.12);
}

.search-input {
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 14px 20px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    font-size: 1rem;
}

.search-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    background: rgba(255, 255, 255, 1);
    transform: scale(1.02);
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out;
}

.animate-bounce-in {
    animation: bounceIn 0.6s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.grid-view-toggle {
    display: flex;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 16px;
    padding: 6px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.grid-view-btn {
    padding: 12px 16px;
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    border: none;
    background: transparent;
    position: relative;
    overflow: hidden;
}

.grid-view-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.grid-view-btn.active::before {
    opacity: 1;
}

.grid-view-btn.active {
    color: white;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    transform: scale(1.05);
}

.grid-view-btn:not(.active) {
    color: #6b7280;
}

.grid-view-btn:not(.active):hover {
    background: #f3f4f6;
    color: #374151;
    transform: scale(1.02);
}

.grid-view-btn i {
    position: relative;
    z-index: 1;
}

/* Enhanced Responsive Grid */
.grid-2 { grid-template-columns: repeat(2, 1fr); }
.grid-3 { grid-template-columns: repeat(3, 1fr); }
.grid-4 { grid-template-columns: repeat(4, 1fr); }

@media (max-width: 640px) {
    .grid-2, .grid-3, .grid-4 { 
        grid-template-columns: repeat(1, 1fr); 
    }
    .product-image-container {
        height: 220px;
    }
}

@media (min-width: 641px) and (max-width: 1024px) {
    .grid-3, .grid-4 { 
        grid-template-columns: repeat(2, 1fr); 
    }
}

@media (min-width: 1025px) and (max-width: 1279px) {
    .grid-4 { 
        grid-template-columns: repeat(3, 1fr); 
    }
}

@media (min-width: 1280px) {
    .grid-4 { 
        grid-template-columns: repeat(4, 1fr); 
    }
}

/* Enhanced Modal Styles */
.modal-overlay {
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(8px);
}

.modal-content {
    background: white;
    border-radius: 24px;
    box-shadow: 0 35px 70px -12px rgba(0, 0, 0, 0.3);
    max-width: 95vw;
    max-height: 95vh;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.modal-image {
    max-width: 100%;
    max-height: 75vh;
    object-fit: contain;
    border-radius: 16px;
}

/* Enhanced Button Styles */
.btn-primary {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border: none;
    border-radius: 16px;
    padding: 14px 28px;
    color: white;
    font-weight: 700;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    position: relative;
    overflow: hidden;
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.btn-primary:hover::before {
    left: 100%;
}

.btn-primary:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 12px 35px rgba(59, 130, 246, 0.5);
}

.btn-secondary {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    border: none;
    border-radius: 16px;
    padding: 12px 24px;
    color: white;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 6px 20px rgba(107, 114, 128, 0.3);
}

.btn-secondary:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 10px 30px rgba(107, 114, 128, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    border-radius: 16px;
    padding: 12px 24px;
    color: white;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
}

/* Quick Filter Buttons */
.quick-filter-btn {
    padding: 10px 20px;
    border-radius: 25px;
    border: 2px solid #e5e7eb;
    background: rgba(255, 255, 255, 0.9);
    color: #6b7280;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    backdrop-filter: blur(10px);
}

.quick-filter-btn:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
    color: #374151;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.quick-filter-btn.active {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-color: #3b82f6;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
}

/* Loading States */
.loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 16px;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Utility Classes */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Notification Toast */
.notification-toast {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

/* Search Loading Indicator */
.search-loading {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #3b82f6;
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Enhanced Header Section -->
        <div class="text-center mb-12 animate-fade-in">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-6 animate-bounce-in">
                <i class="fas fa-store text-3xl text-white"></i>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold text-gray-800 mb-4 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Katalog Produk
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Jelajahi koleksi produk berkualitas dari berbagai unit usaha koperasi kami dengan pengalaman berbelanja yang menyenangkan
            </p>
        </div>

        <!-- Enhanced Filter Section -->
        <div class="filter-section p-8 mb-12 animate-slide-up">
            <form method="GET" action="{{ route('anggota.pembelian.katalog') }}" id="filterForm">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
                    <!-- Enhanced Search Input -->
                    <div class="lg:col-span-4">
                        <label for="search_barang_katalog_anggota" class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-search mr-2 text-blue-500"></i>
                            Cari Produk Favorit Anda
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="search_barang_katalog_anggota" 
                                   name="search_barang_katalog" 
                                   value="{{ request('search_barang_katalog') }}" 
                                   placeholder="Ketik nama atau kode produk yang Anda cari..."
                                   class="search-input w-full pl-12 pr-12">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <div id="searchLoading" class="search-loading hidden">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Unit Usaha Filter -->
                    <div class="lg:col-span-3">
                        <label for="unit_usaha_katalog_anggota" class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-building mr-2 text-purple-500"></i>
                            Pilih Unit Usaha
                        </label>
                        <select name="unit_usaha_katalog" id="unit_usaha_katalog_anggota" class="select2-katalog w-full">
                            <option value="">Semua Unit Usaha</option>
                            @foreach($unitUsahas as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_usaha_katalog') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama_unit_usaha }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Enhanced Grid View Toggle -->
                    <div class="lg:col-span-3">
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            <i class="fas fa-th mr-2 text-green-500"></i>
                            Tampilan Grid
                        </label>
                        <div class="grid-view-toggle">
                            <button type="button" class="grid-view-btn active" data-grid="2" title="2 Kolom">
                                <i class="fas fa-th-large"></i>
                            </button>
                            <button type="button" class="grid-view-btn" data-grid="3" title="3 Kolom">
                                <i class="fas fa-th"></i>
                            </button>
                            <button type="button" class="grid-view-btn" data-grid="4" title="4 Kolom">
                                <i class="fas fa-grip-horizontal"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Enhanced Search Button -->
                    <div class="lg:col-span-2">
                        <button type="submit" class="btn-primary w-full" id="searchButton">
                            <i class="fas fa-search mr-2"></i>
                            Cari Produk
                        </button>
                    </div>
                </div>

                <!-- Enhanced Quick Filters -->
                <div class="mt-8 flex flex-wrap gap-4 justify-center">
                    <span class="text-sm font-bold text-gray-600 flex items-center">
                        <i class="fas fa-filter mr-2"></i>
                        Filter Cepat:
                    </span>
                    <button type="button" class="quick-filter-btn" data-filter="available">
                        <i class="fas fa-check-circle mr-2"></i>
                        Produk Tersedia
                    </button>
                    <button type="button" class="quick-filter-btn" data-filter="low-stock">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Stok Terbatas
                    </button>
                    <button type="button" class="quick-filter-btn" data-filter="new">
                        <i class="fas fa-star mr-2"></i>
                        Produk Terbaru
                    </button>
                    <button type="button" class="quick-filter-btn" data-filter="popular">
                        <i class="fas fa-fire mr-2"></i>
                        Terpopuler
                    </button>
                </div>
            </form>
        </div>

        <!-- Enhanced Results Info -->
        @if($barangs->isNotEmpty())
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 animate-slide-up bg-white/80 backdrop-blur-lg rounded-2xl p-6 shadow-lg border border-white/20">
                <div class="text-gray-600 mb-4 md:mb-0">
                    <span class="text-2xl font-bold text-blue-600">{{ $barangs->total() }}</span> 
                    <span class="text-lg">produk ditemukan</span>
                    @if(request('search_barang_katalog') || request('unit_usaha_katalog'))
                        <span class="ml-4">
                            <a href="{{ route('anggota.pembelian.katalog') }}" 
                               class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-full text-sm font-medium transition-all duration-300">
                                <i class="fas fa-times mr-2"></i>Reset Filter
                            </a>
                        </span>
                    @endif
                </div>
                
                <!-- Enhanced Sort Options -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-600">
                        <i class="fas fa-sort mr-2"></i>Urutkan:
                    </span>
                    <select id="sortSelect" class="text-sm border-2 border-gray-300 rounded-xl px-4 py-2 bg-white/90 backdrop-blur-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300">
                        <option value="name">Nama A-Z</option>
                        <option value="name-desc">Nama Z-A</option>
                        <option value="price-low">Harga Terendah</option>
                        <option value="price-high">Harga Tertinggi</option>
                        <option value="stock">Stok Terbanyak</option>
                        <option value="newest">Terbaru</option>
                    </select>
                </div>
            </div>
        @endif

        <!-- Enhanced Product Grid -->
        @if($barangs->isNotEmpty())
            <div id="productGrid" class="grid grid-2 gap-8 mb-12">
                @foreach($barangs as $barang)
                    <div class="product-card animate-slide-up" 
                         data-name="{{ strtolower($barang->nama_barang) }}"
                         data-price="{{ $barang->harga_jual }}"
                         data-stock="{{ $barang->stok }}"
                         data-created="{{ $barang->created_at->timestamp }}"
                         style="animation-delay: {{ ($loop->index % 8) * 0.1 }}s">
                        
                        <!-- Enhanced Product Image -->
                        <div class="product-image-container">
                            @if($barang->gambar_path)
                                <img src="{{ asset('storage/' . $barang->gambar_path) }}" 
                                     alt="{{ $barang->nama_barang }}" 
                                     class="product-image"
                                     onclick="openImageModal('{{ asset('storage/' . $barang->gambar_path) }}', '{{ addslashes($barang->nama_barang) }}')"
                                     onerror="this.parentElement.innerHTML='<div class=\'product-image-placeholder\' onclick=\'openImageModal(\'https://ui-avatars.com/api/?name={{ urlencode($barang->nama_barang) }}&background=random&color=fff&size=400&font-size=0.33&bold=true&rounded=false\', \'{{ addslashes($barang->nama_barang) }}\')\' title=\'Klik untuk memperbesar\'><i class=\'fas fa-image\'></i><p class=\'text-sm mt-3 font-medium\'>{{ $barang->nama_barang }}</p></div>'">
                            @else
                                <div class="product-image-placeholder" 
                                     onclick="openImageModal('https://ui-avatars.com/api/?name={{ urlencode($barang->nama_barang) }}&background=random&color=fff&size=400&font-size=0.33&bold=true&rounded=false', '{{ addslashes($barang->nama_barang) }}')"
                                     title="Klik untuk memperbesar">
                                    <i class="fas fa-image"></i>
                                    <p class="text-sm mt-3 font-medium">{{ $barang->nama_barang }}</p>
                                </div>
                            @endif
                            
                            <div class="product-overlay">
                                <i class="fas fa-search-plus"></i>
                            </div>

                            <!-- Enhanced Stock Badge -->
                            <div class="stock-badge 
                                @if($barang->stok == 0) stock-empty
                                @elseif($barang->stok <= 10) stock-low
                                @else stock-available @endif">
                                @if($barang->stok == 0)
                                    <i class="fas fa-times mr-2"></i>Habis
                                @elseif($barang->stok <= 10)
                                    <i class="fas fa-exclamation-triangle mr-2"></i>{{ $barang->stok }} {{ $barang->satuan }}
                                @else
                                    <i class="fas fa-check mr-2"></i>{{ $barang->stok }} {{ $barang->satuan }}
                                @endif
                            </div>
                        </div>

                        <!-- Enhanced Product Info -->
                        <div class="p-6 relative z-10">
                            <!-- Product Name & Unit -->
                            <div class="mb-4">
                                <a href="{{ route('anggota.pembelian.barang.detail', $barang->id) }}" class="block group">
                                    <h3 class="text-xl font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors duration-300">
                                        {{ $barang->nama_barang }}
                                    </h3>
                                </a>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="unit-badge">
                                        <i class="fas fa-building mr-2"></i>
                                        {{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}
                                    </span>
                                    @if($barang->kode_barang)
                                        <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                                            #{{ $barang->kode_barang }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Enhanced Price -->
                            <div class="mb-4">
                                <div class="price-tag">
                                    @rupiah($barang->harga_jual)
                                </div>
                                <p class="text-sm text-gray-500 mt-2">per {{ $barang->satuan }}</p>
                            </div>

                            <!-- Product Description -->
                            @if($barang->deskripsi)
                                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                    <p class="text-sm text-gray-700 line-clamp-3 leading-relaxed">
                                        {{ Str::limit($barang->deskripsi, 120) }}
                                    </p>
                                    @if(strlen($barang->deskripsi) > 120)
                                        <button onclick="showDescription('{{ addslashes($barang->nama_barang) }}', '{{ addslashes($barang->deskripsi) }}')" 
                                                class="text-xs text-blue-600 hover:text-blue-800 font-medium mt-2 hover:underline">
                                            <i class="fas fa-expand-alt mr-1"></i>Baca selengkapnya
                                        </button>
                                    @endif
                                </div>
                            @endif

                            <!-- Enhanced Stock Info -->
                            <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600 font-medium">
                                        <i class="fas fa-boxes mr-2"></i>Ketersediaan:
                                    </span>
                                    <span class="font-bold 
                                        @if($barang->stok == 0) text-red-600
                                        @elseif($barang->stok <= 10) text-yellow-600
                                        @else text-green-600 @endif">
                                        @if($barang->stok == 0)
                                            <i class="fas fa-times-circle mr-1"></i>Stok Habis
                                        @elseif($barang->stok <= 10)
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Stok Terbatas ({{ $barang->stok }} {{ $barang->satuan }})
                                        @else
                                            <i class="fas fa-check-circle mr-1"></i>Tersedia ({{ $barang->stok }} {{ $barang->satuan }})
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <!-- Enhanced Action Buttons -->
                            <div class="flex space-x-3">
                                <a href="{{ route('anggota.pembelian.barang.detail', $barang->id) }}" class="flex-1">
                                    <button class="btn-secondary w-full">
                                        <i class="fas fa-eye mr-2"></i>
                                        Lihat Detail
                                    </button>
                                </a>
                                @if($barang->stok > 0)
                                    <a href="{{ route('anggota.pembelian.barang.detail', $barang->id) }}#form-pembelian-anggota" class="flex-1">
                                        <button class="btn-success w-full">
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                            Beli Sekarang
                                        </button>
                                    </a>
                                @else
                                    <button class="btn-secondary w-full opacity-50 cursor-not-allowed" disabled>
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Enhanced Pagination -->
            @if($barangs->hasPages())
                <div class="flex justify-center mt-16">
                    <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl border border-white/30 p-6">
                        {{ $barangs->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            @endif
        @else
            <!-- Enhanced Empty State -->
            <div class="text-center py-20 animate-fade-in">
                <div class="bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl border border-white/30 p-16 max-w-3xl mx-auto">
                    <div class="mb-8">
                        <div class="bg-gradient-to-r from-gray-100 to-gray-200 w-40 h-40 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce-in">
                            <i class="fas fa-store-slash text-7xl text-gray-400"></i>
                        </div>
                        <h3 class="text-4xl font-bold text-gray-800 mb-6">Produk Tidak Ditemukan</h3>
                        <p class="text-xl text-gray-600 mb-10 leading-relaxed">
                            Maaf, tidak ada produk yang sesuai dengan pencarian Anda saat ini. Coba gunakan kata kunci yang berbeda atau jelajahi semua produk kami.
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-6 justify-center">
                        <a href="{{ route('anggota.pembelian.katalog') }}" class="btn-primary">
                            <i class="fas fa-undo mr-3"></i>
                            Lihat Semua Produk
                        </a>
                        <a href="{{ route('anggota.dashboard') }}" class="btn-secondary">
                            <i class="fas fa-home mr-3"></i>
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Enhanced Back to Dashboard -->
        <div class="mt-16 text-center">
            <a href="{{ route('anggota.dashboard') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-3"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Enhanced Image Modal -->
<div id="imageModal" class="fixed inset-0 modal-overlay hidden items-center justify-center z-50 p-4" onclick="closeImageModal()">
    <div class="modal-content p-8 relative max-w-5xl animate-bounce-in">
        <button onclick="closeImageModal()" 
                class="absolute -top-6 -right-6 bg-white rounded-full w-16 h-16 flex items-center justify-center text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-all duration-300 shadow-2xl z-10 hover:scale-110">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <img id="modalImage" src="#" alt="Product Image" class="modal-image mx-auto">
        <div id="modalImageCaption" class="text-center mt-6">
            <p class="text-2xl font-bold text-gray-800"></p>
        </div>
    </div>
</div>

<!-- Enhanced Description Modal -->
<div id="descriptionModal" class="fixed inset-0 modal-overlay hidden items-center justify-center z-50 p-4">
    <div class="modal-content max-w-3xl w-full max-h-96 overflow-hidden animate-bounce-in">
        <div class="p-8 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-purple-50">
            <div class="flex justify-between items-center">
                <h3 id="descriptionModalTitle" class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-align-left mr-4 text-blue-500"></i>
                    Deskripsi Produk
                </h3>
                <button onclick="closeDescriptionModal()" class="text-gray-400 hover:text-gray-600 transition-colors text-3xl hover:scale-110">
                    ×
                </button>
            </div>
        </div>
        <div class="p-8 overflow-y-auto max-h-64">
            <p id="descriptionModalContent" class="text-gray-700 leading-relaxed whitespace-pre-wrap text-lg"></p>
        </div>
        <div class="p-6 border-t border-gray-200 bg-gray-50 flex justify-end">
            <button onclick="closeDescriptionModal()" class="btn-secondary">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
        </div>
    </div>
</div>

<!-- Enhanced Notification Toast -->
<div id="notificationToast" class="fixed top-6 right-6 z-50 hidden">
    <div class="notification-toast p-6 max-w-sm animate-bounce-in">
        <div class="flex items-center">
            <div id="toastIcon" class="mr-4 text-2xl"></div>
            <div>
                <p id="toastMessage" class="text-sm font-medium text-gray-800"></p>
            </div>
            <button onclick="hideNotification()" class="ml-4 text-gray-400 hover:text-gray-600 text-xl">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 with enhanced styling
    $('.select2-katalog').select2({
        placeholder: "Pilih Unit Usaha",
        width: '100%',
        allowClear: true,
        dropdownCssClass: 'select2-dropdown-enhanced'
    });

    // Enhanced Grid view toggle
    const gridButtons = document.querySelectorAll('.grid-view-btn');
    const productGrid = document.getElementById('productGrid');
    
    gridButtons.forEach(button => {
        button.addEventListener('click', function() {
            gridButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const gridSize = this.dataset.grid;
            productGrid.className = `grid grid-${gridSize} gap-8 mb-12`;
            
            // Add animation to grid change
            productGrid.style.opacity = '0.7';
            setTimeout(() => {
                productGrid.style.opacity = '1';
            }, 150);
        });
    });

    // Enhanced Quick filters
    const quickFilterBtns = document.querySelectorAll('.quick-filter-btn');
    quickFilterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Toggle active state
            this.classList.toggle('active');
            
            const filter = this.dataset.filter;
            applyQuickFilter(filter, this.classList.contains('active'));
        });
    });

    // Enhanced Sort functionality
    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            sortProducts(this.value);
            showNotification('Produk berhasil diurutkan', 'success');
        });
    }

    // Enhanced Unit Usaha filter with immediate submission
    document.getElementById('unit_usaha_katalog_anggota').addEventListener('change', function() {
        showNotification('Memfilter produk berdasarkan unit usaha...', 'info');
        showSearchLoading();
        
        // Submit form immediately when unit usaha changes
        setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });

    // Enhanced search input with real-time functionality
    const searchInput = document.getElementById('search_barang_katalog_anggota');
    const searchButton = document.getElementById('searchButton');
    const searchLoading = document.getElementById('searchLoading');
    let searchTimeout;
    
    // Search input event listeners
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        if (query.length === 0) {
            hideSearchLoading();
            return;
        }
        
        if (query.length >= 2) {
            showSearchLoading();
            showNotification('Mencari produk...', 'info');
            
            // Debounce search
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 800);
        }
    });

    // Search on Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length > 0) {
                showSearchLoading();
                performSearch(query);
            }
        }
    });

    // Search button click
    searchButton.addEventListener('click', function(e) {
        e.preventDefault();
        const query = searchInput.value.trim();
        
        if (query.length > 0) {
            showSearchLoading();
            performSearch(query);
        } else {
            // If empty search, submit form to show all products
            document.getElementById('filterForm').submit();
        }
    });

    // Clear search when input is cleared
    searchInput.addEventListener('keyup', function() {
        if (this.value.trim() === '') {
            clearTimeout(searchTimeout);
            hideSearchLoading();
        }
    });

    // Functions for search functionality
    function performSearch(query) {
        showNotification(`Mencari "${query}"...`, 'info');
        
        // Submit the form
        setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 300);
    }

    function showSearchLoading() {
        const loading = document.getElementById('searchLoading');
        if (loading) {
            loading.classList.remove('hidden');
        }
    }

    function hideSearchLoading() {
        const loading = document.getElementById('searchLoading');
        if (loading) {
            loading.classList.add('hidden');
        }
    }

    // Auto-focus search input if there's a search query
    if (searchInput.value.trim() !== '') {
        searchInput.focus();
        searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
    }
});

// Enhanced Image Modal Functions
function openImageModal(imageSrc, imageAlt) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalCaption = document.getElementById('modalImageCaption');
    
    if (modal && modalImage && modalCaption) {
        modalImage.src = imageSrc;
        modalImage.alt = imageAlt;
        modalCaption.querySelector('p').textContent = imageAlt;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        // Add loading state
        modalImage.style.opacity = '0';
        modalImage.onload = function() {
            this.style.opacity = '1';
        };
    }
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Enhanced Description Modal Functions
function showDescription(productName, description) {
    const modal = document.getElementById('descriptionModal');
    const title = document.getElementById('descriptionModalTitle');
    const content = document.getElementById('descriptionModalContent');
    
    if (modal && title && content) {
        title.innerHTML = `<i class="fas fa-align-left mr-4 text-blue-500"></i>Deskripsi: ${productName}`;
        content.textContent = description;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeDescriptionModal() {
    const modal = document.getElementById('descriptionModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Enhanced Quick Filter Functions
function applyQuickFilter(filter, isActive) {
    const products = document.querySelectorAll('.product-card');
    let visibleCount = 0;
    
    products.forEach(product => {
        const stock = parseInt(product.dataset.stock);
        const created = parseInt(product.dataset.created);
        const now = Math.floor(Date.now() / 1000);
        const isNew = (now - created) < (30 * 24 * 60 * 60); // 30 days
        
        let show = true;
        
        if (isActive) {
            switch(filter) {
                case 'available':
                    show = stock > 0;
                    break;
                case 'low-stock':
                    show = stock > 0 && stock <= 10;
                    break;
                case 'new':
                    show = isNew;
                    break;
                case 'popular':
                    // This would need additional data
                    show = true;
                    break;
            }
        }
        
        if (show) {
            product.style.display = 'block';
            visibleCount++;
        } else {
            product.style.display = 'none';
        }
    });
    
    // Show notification with count
    if (isActive) {
        showNotification(`${visibleCount} produk ditemukan dengan filter ini`, 'info');
    } else {
        showNotification('Filter dibatalkan, menampilkan semua produk', 'info');
    }
}

// Enhanced Sort Functions
function sortProducts(sortBy) {
    const grid = document.getElementById('productGrid');
    const products = Array.from(grid.children);
    
    products.sort((a, b) => {
        switch(sortBy) {
            case 'name':
                return a.dataset.name.localeCompare(b.dataset.name);
            case 'name-desc':
                return b.dataset.name.localeCompare(a.dataset.name);
            case 'price-low':
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            case 'price-high':
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            case 'stock':
                return parseInt(b.dataset.stock) - parseInt(a.dataset.stock);
            case 'newest':
                return parseInt(b.dataset.created) - parseInt(a.dataset.created);
            default:
                return 0;
        }
    });
    
    // Add fade effect during sort
    grid.style.opacity = '0.7';
    
    // Re-append sorted products
    products.forEach((product, index) => {
        product.style.animationDelay = `${index * 0.05}s`;
        grid.appendChild(product);
    });
    
    setTimeout(() => {
        grid.style.opacity = '1';
    }, 200);
}

// Enhanced Notification Functions
function showNotification(message, type = 'info') {
    const toast = document.getElementById('notificationToast');
    const icon = document.getElementById('toastIcon');
    const messageEl = document.getElementById('toastMessage');
    
    if (toast && icon && messageEl) {
        // Set icon based on type
        let iconClass = 'fas fa-info-circle text-blue-500';
        if (type === 'success') iconClass = 'fas fa-check-circle text-green-500';
        if (type === 'error') iconClass = 'fas fa-exclamation-circle text-red-500';
        if (type === 'warning') iconClass = 'fas fa-exclamation-triangle text-yellow-500';
        
        icon.innerHTML = `<i class="${iconClass}"></i>`;
        messageEl.textContent = message;
        
        toast.classList.remove('hidden');
        
        // Auto hide after 4 seconds
        setTimeout(() => {
            hideNotification();
        }, 4000);
    }
}

function hideNotification() {
    const toast = document.getElementById('notificationToast');
    if (toast) {
        toast.style.opacity = '0';
        setTimeout(() => {
            toast.classList.add('hidden');
            toast.style.opacity = '1';
        }, 300);
    }
}

// Enhanced Modal Controls
document.addEventListener('click', function(event) {
    const imageModal = document.getElementById('imageModal');
    const descriptionModal = document.getElementById('descriptionModal');
    
    if (event.target === imageModal) {
        closeImageModal();
    }
    
    if (event.target === descriptionModal) {
        closeDescriptionModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
        closeDescriptionModal();
        hideNotification();
    }
});

// Add smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Enhanced form submission with loading states
document.getElementById('filterForm').addEventListener('submit', function() {
    const searchButton = document.getElementById('searchButton');
    const originalText = searchButton.innerHTML;
    
    searchButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...';
    searchButton.disabled = true;
    
    // Show loading notification
    showNotification('Memproses pencarian...', 'info');
});
</script>
@endpush