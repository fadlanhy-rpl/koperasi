@extends('layouts.app')

@section('title', 'Detail Barang: ' . $barang->nama_barang)
@section('page-title', 'Detail Produk Koperasi')
@section('page-subtitle', 'Informasi lengkap mengenai ' . $barang->nama_barang)

@push('styles')
<style>
    .product-detail-container {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.92) 100%);
        backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        position: relative;
    }

    .product-detail-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.03) 0%, rgba(147, 51, 234, 0.03) 100%);
        pointer-events: none;
        z-index: 1;
    }

    .product-image-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        position: relative;
        overflow: hidden;
    }

    .product-detail-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 24px;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 3px solid rgba(255, 255, 255, 0.8);
    }

    .product-detail-image:hover {
        transform: scale(1.05) rotate(1deg);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
        border-color: rgba(59, 130, 246, 0.5);
    }

    .product-image-placeholder {
        width: 100%;
        height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        color: #9ca3af;
        font-size: 4rem;
        cursor: pointer;
        transition: all 0.4s ease;
        border-radius: 24px;
        border: 3px dashed #cbd5e1;
        position: relative;
        overflow: hidden;
    }

    .product-image-placeholder::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(-50%, -50%) scale(0);
        transition: transform 0.4s ease;
    }

    .product-image-placeholder:hover::before {
        transform: translate(-50%, -50%) scale(2);
    }

    .product-image-placeholder:hover {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        color: #3b82f6;
        transform: scale(1.02);
        border-color: #3b82f6;
    }

    .product-info-section {
        position: relative;
        z-index: 2;
    }

    .unit-badge {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
        display: inline-block;
        position: relative;
        overflow: hidden;
    }

    .unit-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .unit-badge:hover::before {
        left: 100%;
    }

    .product-title {
        font-size: 3rem;
        font-weight: 900;
        background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1.1;
        margin-bottom: 1rem;
    }

    .product-code {
        background: rgba(107, 114, 128, 0.1);
        color: #6b7280;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    .price-display {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        padding: 20px 32px;
        border-radius: 20px;
        font-weight: 900;
        font-size: 2.5rem;
        display: inline-block;
        box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
        position: relative;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .price-display::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease;
    }

    .price-display:hover::before {
        left: 100%;
    }

    .price-unit {
        color: #6b7280;
        font-size: 1rem;
        font-weight: 500;
        margin-left: 8px;
    }

    .description-section {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #f59e0b;
        border-radius: 20px;
        padding: 24px;
        margin: 24px 0;
        position: relative;
        overflow: hidden;
    }

    .description-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(180deg); }
    }

    .description-title {
        color: #92400e;
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
    }

    .description-text {
        color: #78350f;
        font-size: 1rem;
        line-height: 1.7;
        white-space: pre-line;
        position: relative;
        z-index: 1;
    }

    .stock-info-container {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 20px;
        padding: 24px;
        margin: 24px 0;
        border: 2px solid #d1d5db;
        position: relative;
        overflow: hidden;
    }

    .stock-info-badge {
        padding: 12px 24px;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .stock-info-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .stock-info-badge:hover::before {
        left: 100%;
    }

    .stock-available {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .stock-low {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
    }

    .stock-empty {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    }

    .purchase-form {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(249, 250, 251, 0.9) 100%);
        border: 2px solid #e5e7eb;
        border-radius: 24px;
        padding: 32px;
        margin: 32px 0;
        backdrop-filter: blur(10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .purchase-form::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ef4444, #10b981);
        background-size: 400% 100%;
        animation: gradient 3s ease infinite;
    }

    @keyframes gradient {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .quantity-input-container {
        display: flex;
        align-items: center;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        padding: 4px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .quantity-input-container:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        transform: scale(1.02);
    }

    .quantity-btn {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        border: none;
        border-radius: 12px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 700;
    }

    .quantity-btn:hover {
        background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
        transform: scale(1.1);
    }

    .quantity-btn:active {
        transform: scale(0.95);
    }

    .quantity-input {
        border: none;
        background: transparent;
        text-align: center;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        width: 80px;
        padding: 8px;
        outline: none;
    }

    .balance-info {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border: 2px solid #3b82f6;
        border-radius: 20px;
        padding: 20px;
        margin: 20px 0;
        position: relative;
        overflow: hidden;
    }

    .balance-info::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .balance-text {
        color: #1e40af;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }

    .total-text {
        color: #1e40af;
        font-weight: 800;
        font-size: 1.1rem;
        position: relative;
        z-index: 1;
    }

    .purchase-button {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 20px;
        padding: 16px 32px;
        font-size: 1.25rem;
        font-weight: 800;
        width: 100%;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .purchase-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease;
    }

    .purchase-button:hover::before {
        left: 100%;
    }

    .purchase-button:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 25px 50px rgba(16, 185, 129, 0.5);
    }

    .purchase-button:active {
        transform: translateY(-2px) scale(1.01);
    }

    .purchase-button:disabled {
        background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
        cursor: not-allowed;
        transform: none;
        box-shadow: 0 4px 15px rgba(156, 163, 175, 0.3);
    }

    .out-of-stock-message {
        background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
        border: 2px solid #ef4444;
        border-radius: 20px;
        padding: 24px;
        text-align: center;
        color: #991b1b;
        font-weight: 700;
        font-size: 1.1rem;
        position: relative;
        overflow: hidden;
    }

    .out-of-stock-message::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .back-button {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 12px 24px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .back-button:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 12px 35px rgba(107, 114, 128, 0.4);
        color: white;
        text-decoration: none;
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

    .animate-scale-in {
        animation: scaleIn 0.5s ease-out;
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

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

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
        max-height: 80vh;
        object-fit: contain;
        border-radius: 16px;
    }

    @media (max-width: 768px) {
        .product-title {
            font-size: 2rem;
        }
        
        .price-display {
            font-size: 2rem;
            padding: 16px 24px;
        }
        
        .product-detail-image {
            height: 300px;
        }
        
        .product-image-placeholder {
            height: 300px;
            font-size: 3rem;
        }
    }

    .loading {
        position: relative;
        overflow: hidden;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% { left: -100%; }
        100% { left: 100%; }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Enhanced Product Detail Container -->
        <div class="product-detail-container animate-fade-in">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                <!-- Enhanced Product Image Section -->
                <div class="product-image-section p-8 lg:p-12 flex items-center justify-center">
                    <div class="w-full animate-scale-in">
                        @if($barang->gambar_path)
                            <img src="{{ asset('storage/' . $barang->gambar_path) }}" 
                                 alt="Gambar {{ $barang->nama_barang }}" 
                                 class="product-detail-image"
                                 onclick="openImageModal('{{ asset('storage/' . $barang->gambar_path) }}', '{{ addslashes($barang->nama_barang) }}')"
                                 onerror="this.parentElement.innerHTML='<div class=\'product-image-placeholder\' onclick=\'openImageModal(\'https://ui-avatars.com/api/?name={{ urlencode($barang->nama_barang) }}&background=random&color=fff&size=600&font-size=0.33&bold=true&rounded=false\', \'{{ addslashes($barang->nama_barang) }}\')\' title=\'Klik untuk memperbesar\'><i class=\'fas fa-image\'></i><p class=\'text-lg mt-4 font-bold\'>{{ $barang->nama_barang }}</p><p class=\'text-sm mt-2 opacity-75\'>Klik untuk memperbesar</p></div>'">
                        @else
                            <div class="product-image-placeholder" 
                                 onclick="openImageModal('https://ui-avatars.com/api/?name={{ urlencode($barang->nama_barang) }}&background=random&color=fff&size=600&font-size=0.33&bold=true&rounded=false', '{{ addslashes($barang->nama_barang) }}')"
                                 title="Klik untuk memperbesar">
                                <i class="fas fa-image"></i>
                                <p class="text-lg mt-4 font-bold">{{ $barang->nama_barang }}</p>
                                <p class="text-sm mt-2 opacity-75">Klik untuk memperbesar</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Enhanced Product Information Section -->
                <div class="product-info-section p-8 lg:p-12">
                    <!-- Unit Badge -->
                    <div class="mb-6 animate-slide-up">
                        <span class="unit-badge">
                            <i class="fas fa-building mr-2"></i>
                            {{ $barang->unitUsaha->nama_unit_usaha ?? 'Kategori Umum' }}
                        </span>
                    </div>

                    <!-- Product Title -->
                    <h1 class="product-title animate-slide-up" style="animation-delay: 0.1s">
                        {{ $barang->nama_barang }}
                    </h1>

                    <!-- Product Code -->
                    @if($barang->kode_barang)
                        <div class="mb-6 animate-slide-up" style="animation-delay: 0.2s">
                            <span class="product-code">
                                <i class="fas fa-barcode mr-2"></i>
                                Kode Produk: #{{ $barang->kode_barang }}
                            </span>
                        </div>
                    @endif

                    <!-- Enhanced Price Display -->
                    <div class="mb-8 animate-slide-up" style="animation-delay: 0.3s">
                        <div class="price-display">
                            @rupiah($barang->harga_jual)
                        </div>
                        <span class="price-unit">per {{ $barang->satuan }}</span>
                    </div>

                    <!-- Enhanced Description -->
                    @if($barang->deskripsi)
                        <div class="description-section animate-slide-up" style="animation-delay: 0.4s">
                            <h3 class="description-title">
                                <i class="fas fa-align-left mr-3"></i>
                                Deskripsi Produk
                            </h3>
                            <p class="description-text">{{ $barang->deskripsi }}</p>
                        </div>
                    @endif

                    <!-- Enhanced Stock Information -->
                    <div class="stock-info-container animate-slide-up" style="animation-delay: 0.5s">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-700">
                                <i class="fas fa-boxes mr-2"></i>
                                Ketersediaan Stok:
                            </span>
                            <span class="stock-info-badge 
                                @if($barang->stok == 0) stock-empty
                                @elseif($barang->stok <= 10) stock-low
                                @else stock-available @endif">
                                @if($barang->stok == 0)
                                    <i class="fas fa-times-circle mr-2"></i>Stok Habis
                                @elseif($barang->stok <= 10)
                                    <i class="fas fa-exclamation-triangle mr-2"></i>Stok Terbatas ({{ $barang->stok }} {{ $barang->satuan }})
                                @else
                                    <i class="fas fa-check-circle mr-2"></i>Tersedia ({{ $barang->stok }} {{ $barang->satuan }})
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Enhanced Purchase Form -->
                    @if($barang->stok > 0)
                        <form action="{{ route('anggota.pembelian.barang.beliSaldo', $barang->id) }}" 
                              method="POST" 
                              class="purchase-form animate-slide-up" 
                              style="animation-delay: 0.6s"
                              data-validate>
                            @csrf
                            
                            <!-- Enhanced Quantity Input -->
                            <div class="mb-6">
                                <label for="jumlah_beli" class="block text-lg font-bold text-gray-700 mb-4">
                                    <i class="fas fa-shopping-cart mr-2 text-blue-500"></i>
                                    Jumlah Pembelian:
                                </label>
                                <div class="quantity-input-container">
                                    <button type="button" class="quantity-btn" onclick="decreaseQuantity()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" 
                                           name="jumlah_beli" 
                                           id="jumlah_beli" 
                                           value="{{ old('jumlah_beli', 1) }}" 
                                           min="1" 
                                           max="{{ $barang->stok }}" 
                                           step="1" 
                                           required
                                           class="quantity-input">
                                    <button type="button" class="quantity-btn" onclick="increaseQuantity()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <span class="ml-4 text-lg font-semibold text-gray-600">{{ $barang->satuan }}</span>
                                </div>
                                @error('jumlah_beli') 
                                    <p class="text-red-500 text-sm mt-2 font-medium">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p> 
                                @enderror
                            </div>
                            
                            <!-- Enhanced Balance Information -->
                            <div class="balance-info">
                                <div class="flex flex-col space-y-3">
                                    <p class="balance-text">
                                        <i class="fas fa-wallet mr-2"></i>
                                        Saldo Simpanan Sukarela Anda: 
                                        <span class="text-xl">@rupiah($saldoSukarelaAnggota)</span>
                                    </p>
                                    <p class="total-text">
                                        <i class="fas fa-calculator mr-2"></i>
                                        Total yang akan dibayar: 
                                        <span class="text-2xl" id="totalAkanBayar">@rupiah($barang->harga_jual)</span>
                                    </p>
                                    <div id="saldoStatus" class="text-sm font-medium"></div>
                                </div>
                            </div>

                            <!-- Enhanced Purchase Button -->
                            <button type="submit" class="purchase-button" id="purchaseBtn">
                                <i class="fas fa-shopping-cart mr-3"></i>
                                Beli dengan Saldo Sukarela
                            </button>
                        </form>
                    @else
                        <!-- Enhanced Out of Stock Message -->
                        <div class="out-of-stock-message animate-bounce-in">
                            <i class="fas fa-times-circle text-3xl mb-4"></i>
                            <p>Maaf, stok barang ini sedang habis.</p>
                            <p class="text-sm mt-2 opacity-75">Silakan cek kembali nanti atau hubungi admin untuk informasi lebih lanjut.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Enhanced Back Button -->
        <div class="mt-12 flex justify-start animate-fade-in" style="animation-delay: 0.8s">
            <a href="{{ route('anggota.pembelian.katalog') }}" class="back-button">
                <i class="fas fa-arrow-left mr-3"></i>
                Kembali ke Katalog
            </a>
        </div>
    </div>
</div>

<!-- Enhanced Image Modal -->
<div id="imageModal" class="fixed inset-0 modal-overlay hidden items-center justify-center z-50 p-4" onclick="closeImageModal()">
    <div class="modal-content p-8 relative max-w-6xl animate-bounce-in">
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

<!-- Enhanced Notification Toast -->
<div id="notificationToast" class="fixed top-6 right-6 z-50 hidden">
    <div class="bg-white/95 backdrop-blur-md border border-gray-200 rounded-2xl shadow-2xl p-6 max-w-sm animate-bounce-in">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahBeliInput = document.getElementById('jumlah_beli');
    const totalAkanBayarEl = document.getElementById('totalAkanBayar');
    const saldoStatusEl = document.getElementById('saldoStatus');
    const purchaseBtn = document.getElementById('purchaseBtn');
    const hargaSatuan = {{ $barang->harga_jual }};
    const maxStok = {{ $barang->stok }};
    const saldoSukarela = {{ $saldoSukarelaAnggota }};

    function updateTotalBayar() {
        if (jumlahBeliInput && totalAkanBayarEl) {
            let jumlah = parseInt(jumlahBeliInput.value) || 1;
            
            // Validate quantity
            if (jumlah < 1) jumlah = 1;
            if (jumlah > maxStok) jumlah = maxStok;
            jumlahBeliInput.value = jumlah;
            
            const total = jumlah * hargaSatuan;
            
            // Format currency
            totalAkanBayarEl.textContent = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(total);
            
            // Check if balance is sufficient
            if (saldoStatusEl && purchaseBtn) {
                if (total > saldoSukarela) {
                    saldoStatusEl.innerHTML = '<i class="fas fa-exclamation-triangle text-red-500 mr-2"></i><span class="text-red-600">Saldo tidak mencukupi!</span>';
                    purchaseBtn.disabled = true;
                    purchaseBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    const sisaSaldo = saldoSukarela - total;
                    saldoStatusEl.innerHTML = `<i class="fas fa-check-circle text-green-500 mr-2"></i><span class="text-green-600">Sisa saldo setelah pembelian: ${new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(sisaSaldo)}</span>`;
                    purchaseBtn.disabled = false;
                    purchaseBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        }
    }

    // Quantity control functions
    window.increaseQuantity = function() {
        const currentValue = parseInt(jumlahBeliInput.value) || 1;
        if (currentValue < maxStok) {
            jumlahBeliInput.value = currentValue + 1;
            updateTotalBayar();
            showNotification('Jumlah ditambah', 'success');
        } else {
            showNotification('Jumlah maksimal tercapai', 'warning');
        }
    };

    window.decreaseQuantity = function() {
        const currentValue = parseInt(jumlahBeliInput.value) || 1;
        if (currentValue > 1) {
            jumlahBeliInput.value = currentValue - 1;
            updateTotalBayar();
            showNotification('Jumlah dikurangi', 'success');
        } else {
            showNotification('Jumlah minimal adalah 1', 'warning');
        }
    };

    // Event listeners
    if (jumlahBeliInput) {
        jumlahBeliInput.addEventListener('input', updateTotalBayar);
        jumlahBeliInput.addEventListener('change', updateTotalBayar);
        
        // Initialize
        updateTotalBayar();
    }

    // Form submission with loading state
    const form = document.querySelector('form[data-validate]');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (purchaseBtn && !purchaseBtn.disabled) {
                purchaseBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Memproses Pembelian...';
                purchaseBtn.disabled = true;
                showNotification('Memproses pembelian...', 'info');
            }
        });
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
        
        showNotification('Gambar diperbesar', 'info');
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
        
        // Auto hide after 3 seconds
        setTimeout(() => {
            hideNotification();
        }, 3000);
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
    if (event.target === imageModal) {
        closeImageModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
        hideNotification();
    }
});

// Add smooth scrolling for better UX
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const parallax = document.querySelector('.product-detail-container');
    if (parallax) {
        const speed = scrolled * 0.5;
        parallax.style.transform = `translateY(${speed}px)`;
    }
});

// Add intersection observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe all animated elements
document.querySelectorAll('.animate-slide-up, .animate-fade-in').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    observer.observe(el);
});
</script>
@endpush
