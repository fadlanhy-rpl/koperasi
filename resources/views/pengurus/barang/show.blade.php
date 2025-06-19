@extends('layouts.app')

@section('title', 'Detail Barang: ' . $barang->nama_barang)
@section('page-title', 'Detail Barang')
@section('page-subtitle', $barang->nama_barang)

@push('styles')
<style>
.product-detail-image {
    width: 300px;
    height: 300px;
    object-fit: cover;
    border-radius: 16px;
    border: 3px solid #e5e7eb;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.product-detail-image:hover {
    transform: scale(1.02);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    border-color: #3b82f6;
}

.product-detail-placeholder {
    width: 300px;
    height: 300px;
    border-radius: 16px;
    border: 3px dashed #cbd5e1;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    transition: all 0.3s ease;
}

.product-detail-placeholder:hover {
    border-color: #3b82f6;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    transform: scale(1.02);
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 mb-8 p-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Product Image -->
                <div class="lg:w-1/3">
                    <div class="text-center">
                        <img src="{{ $barang->gambar_path ? asset('storage/' . $barang->gambar_path) : 'https://ui-avatars.com/api/?name='.urlencode($barang->nama_barang).'&background=random&color=fff&size=300&font-size=0.33&bold=true&rounded=false' }}" 
                             alt="Gambar {{ $barang->nama_barang }}" 
                             class="product-detail-image mx-auto cursor-pointer"
                             onclick="openImageModal('{{ $barang->gambar_path ? asset('storage/' . $barang->gambar_path) : 'https://ui-avatars.com/api/?name='.urlencode($barang->nama_barang).'&background=random&color=fff&size=800&font-size=0.33&bold=true&rounded=false' }}', '{{ addslashes($barang->nama_barang) }}')"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($barang->nama_barang) }}&background=random&color=fff&size=300&font-size=0.33&bold=true&rounded=false'">
                        
                        <div class="mt-4">
                            @if(!$barang->gambar_path)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-magic mr-2"></i>
                                    Avatar Otomatis
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-image mr-2"></i>
                                    Gambar Custom
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Product Info -->
                <div class="lg:w-2/3">
                    <div class="space-y-6">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $barang->nama_barang }}</h1>
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium">
                                    <i class="fas fa-barcode mr-2"></i>
                                    {{ $barang->kode_barang ?? 'Tanpa Kode' }}
                                </span>
                                <span class="bg-purple-100 text-purple-700 px-4 py-2 rounded-lg font-medium">
                                    <i class="fas fa-building mr-2"></i>
                                    {{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Pricing -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-red-50 p-6 rounded-2xl border border-red-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-red-600 font-medium mb-1">Harga Beli</p>
                                        <p class="text-3xl font-bold text-red-700">@rupiah($barang->harga_beli)</p>
                                    </div>
                                    <div class="bg-red-100 p-3 rounded-xl">
                                        <i class="fas fa-shopping-cart text-red-600 text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 p-6 rounded-2xl border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-green-600 font-medium mb-1">Harga Jual</p>
                                        <p class="text-3xl font-bold text-green-700">@rupiah($barang->harga_jual)</p>
                                    </div>
                                    <div class="bg-green-100 p-3 rounded-xl">
                                        <i class="fas fa-tag text-green-600 text-2xl"></i>
                                    </div>
                                </div>
                                @if($barang->harga_jual > $barang->harga_beli)
                                    @php
                                        $profit = $barang->harga_jual - $barang->harga_beli;
                                        $profitPercent = ($profit / $barang->harga_beli) * 100;
                                    @endphp
                                    <div class="mt-3 text-sm text-green-600 font-medium">
                                        <i class="fas fa-arrow-up mr-1"></i>
                                        Keuntungan: +{{ number_format($profitPercent, 1) }}%
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Stock Info -->
                        <div class="bg-blue-50 p-6 rounded-2xl border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-600 font-medium mb-1">Stok Tersedia</p>
                                    <p class="text-3xl font-bold text-blue-700">{{ number_format($barang->stok) }} {{ ucfirst($barang->satuan) }}</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-xl">
                                    <i class="fas fa-boxes text-blue-600 text-2xl"></i>
                                </div>
                            </div>
                            @if($barang->stok > 0)
                                @php
                                    $stockValue = $barang->stok * $barang->harga_beli;
                                @endphp
                                <div class="mt-3 text-sm text-blue-600 font-medium">
                                    <i class="fas fa-calculator mr-1"></i>
                                    Nilai Stok: @rupiah($stockValue)
                                </div>
                            @endif
                        </div>
                        
                        <!-- Description -->
                        @if($barang->deskripsi)
                            <div class="bg-yellow-50 p-6 rounded-2xl border border-yellow-200">
                                <h3 class="text-lg font-semibold text-yellow-800 mb-3 flex items-center">
                                    <i class="fas fa-align-left mr-2"></i>
                                    Deskripsi
                                </h3>
                                <p class="text-gray-700 leading-relaxed">{{ $barang->deskripsi }}</p>
                            </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('pengurus.barang.edit', $barang->id) }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Barang
                            </a>
                            <a href="{{ route('pengurus.barang.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl transition-all duration-300">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stock History Section -->
        <!-- ... rest of the content ... -->
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-80 hidden items-center justify-center z-50 p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute -top-4 -right-4 bg-white rounded-full w-10 h-10 flex items-center justify-center text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors z-10">
            <i class="fas fa-times text-lg"></i>
        </button>
        <img id="modalImage" src="#" alt="Gambar Barang" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
        <div id="modalImageCaption" class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-70 text-white p-4 rounded-b-lg">
            <p class="text-center font-medium"></p>
        </div>
    </div>
</div>

<script>
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
</script>
@endsection