@extends('layouts.app')

@section('title', 'Daftar Pembayaran Cicilan - Koperasi')

@section('page-title', 'Pembayaran Cicilan Anggota')
@section('page-subtitle', 'Kelola pembayaran angsuran transaksi anggota dengan mudah')

@push('styles')
<style>
    .payment-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .payment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-color: #3b82f6;
    }
    .status-badge {
        animation: pulse 2s infinite;
    }
    .search-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .stats-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    .progress-bar {
        background: linear-gradient(90deg, #4ade80, #22c55e);
        transition: width 0.5s ease;
    }
</style>
@endpush

@section('content')
<div class="animate-fade-in space-y-6">
    <!-- Header Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stats-card text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold opacity-90">Total Transaksi</h3>
                    <p class="text-3xl font-bold">{{ $pembelians->total() }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-400 to-green-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold opacity-90">Belum Lunas</h3>
                    <p class="text-3xl font-bold">{{ $pembelians->where('status_pembayaran', '!=', 'lunas')->count() }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-400 to-blue-600 text-white rounded-2xl p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold opacity-90">Sudah Lunas</h3>
                    <p class="text-3xl font-bold">{{ $pembelians->where('status_pembayaran', 'lunas')->count() }}</p>
                </div>
                <div class="bg-white/20 p-3 rounded-full">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-container rounded-2xl p-6 shadow-lg">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-4">
            <div class="text-white">
                <h3 class="text-xl font-bold">Cari Transaksi</h3>
                <p class="opacity-90">Temukan transaksi berdasarkan kode atau nama anggota</p>
            </div>
            <form method="GET" action="{{ route('pengurus.pembayaran-cicilan.index') }}" class="flex gap-3 w-full lg:w-auto lg:min-w-[400px]">
                <div class="relative flex-1">
                    <input type="text" 
                           name="search_pembelian_cicilan" 
                           value="{{ request('search_pembelian_cicilan') }}" 
                           placeholder="Masukkan kode transaksi atau nama anggota..." 
                           class="w-full pl-12 pr-4 py-3 border-0 rounded-xl focus:ring-2 focus:ring-white/50 text-gray-700 placeholder-gray-400 shadow-lg">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <button type="submit" class="bg-white text-purple-600 px-6 py-3 rounded-xl hover:bg-gray-100 transition-colors shadow-lg font-semibold">
                    <i class="fas fa-search mr-2"></i>Cari
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-credit-card mr-3 text-blue-600"></i>
                Daftar Transaksi Cicilan
            </h3>
            <p class="text-gray-600 mt-1">Kelola pembayaran angsuran untuk setiap transaksi</p>
        </div>
        
        <div class="p-6">
            @if($pembelians->count() > 0)
                <div class="grid gap-4">
                    @foreach($pembelians as $pembelian)
                        <div class="payment-card rounded-xl p-6 bg-white border hover:shadow-lg">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <!-- Transaction Info -->
                                <div class="flex-1">
                                    <div class="flex items-start gap-4">
                                        <div class="bg-blue-100 p-3 rounded-full">
                                            <i class="fas fa-shopping-cart text-blue-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <a href="{{ route('pengurus.transaksi-pembelian.show', $pembelian->id) }}" 
                                                   class="text-lg font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                                    {{ $pembelian->kode_pembelian }}
                                                </a>
                                                <span class="status-badge px-3 py-1 rounded-full text-xs font-semibold
                                                    @if($pembelian->status_pembayaran == 'lunas') bg-green-100 text-green-700
                                                    @elseif($pembelian->status_pembayaran == 'cicilan') bg-yellow-100 text-yellow-700
                                                    @else bg-red-100 text-red-700 @endif">
                                                    {{ ucfirst($pembelian->status_pembayaran) }}
                                                </span>
                                            </div>
                                            
                                            <div class="text-gray-600 mb-3">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <i class="fas fa-user text-sm"></i>
                                                    <span class="font-medium">{{ $pembelian->user->name ?? 'N/A' }}</span>
                                                    <span class="text-sm text-gray-500">({{ $pembelian->user->nomor_anggota ?? '-' }})</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-sm">
                                                    <i class="fas fa-calendar text-sm"></i>
                                                    <span>{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('DD MMMM YYYY') }}</span>
                                                </div>
                                            </div>

                                            <!-- Payment Progress -->
                                            @php
                                                $totalBayar = $pembelian->total_bayar + $pembelian->cicilans->sum('jumlah_bayar');
                                                $progress = ($totalBayar / $pembelian->total_harga) * 100;
                                            @endphp
                                            <div class="mb-3">
                                                <div class="flex justify-between text-sm mb-1">
                                                    <span class="text-gray-600">Progress Pembayaran</span>
                                                    <span class="font-semibold">{{ number_format($progress, 1) }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="progress-bar h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Details -->
                                <div class="lg:w-80">
                                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Total Tagihan:</span>
                                            <span class="font-semibold">@rupiah($pembelian->total_harga)</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Sudah Dibayar:</span>
                                            <span class="font-semibold text-green-600">@rupiah($totalBayar)</span>
                                        </div>
                                        <hr class="my-2">
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-700">Sisa Tagihan:</span>
                                            <span class="font-bold text-lg {{ $pembelian->sisa_tagihan_aktual > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                @rupiah($pembelian->sisa_tagihan_aktual)
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Button -->
                                    <div class="mt-4">
                                        @if($pembelian->sisa_tagihan_aktual > 0)
                                            <a href="{{ route('pengurus.pembayaran-cicilan.create', $pembelian->id) }}" 
                                               class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-3 rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-300 flex items-center justify-center gap-2 font-semibold shadow-lg hover:shadow-xl">
                                                <i class="fas fa-plus-circle"></i>
                                                Bayar Cicilan
                                            </a>
                                        @else
                                            <div class="w-full bg-green-100 text-green-700 px-4 py-3 rounded-lg flex items-center justify-center gap-2 font-semibold">
                                                <i class="fas fa-check-double"></i>
                                                Sudah Lunas
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($pembelians->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $pembelians->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <div class="bg-green-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-double text-4xl text-green-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Semua Transaksi Sudah Lunas!</h3>
                    <p class="text-gray-500">Tidak ada transaksi yang memerlukan pembayaran cicilan saat ini.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-start">
        <a href="{{ route('pengurus.dashboard') }}" 
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl transition-colors flex items-center gap-2 font-semibold shadow-md">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Dashboard
        </a>
    </div>
</div>

@push('scripts')
<script>
    // Auto refresh setiap 30 detik untuk update real-time
    setTimeout(function() {
        location.reload();
    }, 30000);

    // Smooth scroll untuk pagination
    document.addEventListener('DOMContentLoaded', function() {
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    });
</script>
@endpush
@endsection