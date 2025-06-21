@extends('layouts.app')

@section('title', 'Detail Transaksi: ' . $pembelian->kode_pembelian)

@push('styles')
<style>
    .detail-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.95) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .detail-card::before {
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

    .detail-card:hover::before {
        opacity: 1;
    }

    .detail-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: rgba(59, 130, 246, 0.05);
        margin: 0 -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        border-radius: 8px;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #6b7280;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-value {
        font-weight: 600;
        color: #1f2937;
    }

    .payment-status-card {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        position: relative;
        overflow: hidden;
    }

    .payment-status-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    .item-card {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border-radius: 16px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .item-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
        transition: left 0.5s;
    }

    .item-card:hover::before {
        left: 100%;
    }

    .item-card:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .cicilan-timeline {
        position: relative;
        padding-left: 2rem;
    }

    .cicilan-timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
    }

    .cicilan-item {
        position: relative;
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .cicilan-item::before {
        content: '';
        position: absolute;
        left: -2.25rem;
        top: 1.5rem;
        width: 12px;
        height: 12px;
        background: #3b82f6;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #3b82f6;
    }

    .cicilan-item:hover {
        border-color: #3b82f6;
        transform: translateX(4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .total-summary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
    }

    .action-button-enhanced {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .action-button-enhanced::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .action-button-enhanced:hover::before {
        left: 100%;
    }

    .action-button-enhanced:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.4);
    }

    .header-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 24px;
        padding: 3rem 2rem;
        color: white;
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .header-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 8s ease-in-out infinite;
    }

    .floating-action {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 1000;
    }

    .floating-action button {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        box-shadow: 0 8px 32px rgba(59, 130, 246, 0.4);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .floating-action button:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 12px 40px rgba(59, 130, 246, 0.6);
    }

    @media print {
        .floating-action,
        .action-button-enhanced {
            display: none !important;
        }
        
        .detail-card {
            box-shadow: none !important;
            border: 1px solid #e5e7eb !important;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header Section -->
    <div class="header-section">
        <h1 class="text-4xl font-bold mb-2">
            <i class="fas fa-receipt mr-3"></i>
            Detail Transaksi
        </h1>
        <p class="text-xl opacity-90">{{ $pembelian->kode_pembelian }}</p>
        <div class="mt-4 flex justify-center space-x-4 text-sm opacity-75">
            <span><i class="fas fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('DD MMMM YYYY') }}</span>
            <span><i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('HH:mm') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Transaction & Payment Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Transaction Info -->
            <div class="detail-card p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-info-circle text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Informasi Transaksi</h3>
                </div>
                
                <div class="space-y-1">
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-barcode text-blue-500"></i>
                            Kode Transaksi
                        </span>
                        <span class="info-value">{{ $pembelian->kode_pembelian }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-calendar-alt text-green-500"></i>
                            Tanggal & Waktu
                        </span>
                        <span class="info-value">
                            {{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('DD MMMM YYYY, HH:mm') }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-user-circle text-purple-500"></i>
                            Anggota
                        </span>
                        <span class="info-value">
                            {{ $pembelian->user->name ?? 'N/A' }}
                            <div class="text-sm text-gray-500">({{ $pembelian->user->nomor_anggota ?? '-' }})</div>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-user-tie text-orange-500"></i>
                            Kasir
                        </span>
                        <span class="info-value">{{ $pembelian->kasir->name ?? 'Sistem' }}</span>
                    </div>
                    @if($pembelian->catatan)
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-sticky-note text-pink-500"></i>
                            Catatan
                        </span>
                        <span class="info-value">
                            <div class="bg-gray-50 p-3 rounded-lg text-sm">{{ $pembelian->catatan }}</div>
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Info -->
            <div class="detail-card p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-credit-card text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Informasi Pembayaran</h3>
                </div>

                <div class="space-y-1 mb-6">
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-shopping-cart text-blue-500"></i>
                            Total Belanja
                        </span>
                        <span class="info-value text-lg">@rupiah($pembelian->total_harga)</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-credit-card text-purple-500"></i>
                            Metode Bayar
                        </span>
                        <span class="info-value">{{ ucfirst(str_replace('_', ' ', $pembelian->metode_pembayaran)) }}</span>
                    </div>
                    @if($pembelian->metode_pembayaran == 'tunai' || $pembelian->total_bayar > 0)
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-coins text-yellow-500"></i>
                            Dibayar Saat Transaksi
                        </span>
                        <span class="info-value">@rupiah($pembelian->total_bayar)</span>
                    </div>
                    @endif
                    @if($pembelian->metode_pembayaran == 'tunai' && $pembelian->kembalian > 0)
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-hand-holding-usd text-green-500"></i>
                            Kembalian
                        </span>
                        <span class="info-value">@rupiah($pembelian->kembalian)</span>
                    </div>
                    @endif
                </div>

                <!-- Payment Status -->
                <div class="payment-status-card">
                    <div class="relative z-10">
                        <div class="text-sm opacity-90 mb-2">Status Pembayaran</div>
                        <div class="text-2xl font-bold mb-2">
                            @if($pembelian->status_pembayaran == 'lunas')
                                <i class="fas fa-check-circle mr-2"></i>LUNAS
                            @elseif($pembelian->status_pembayaran == 'cicilan')
                                <i class="fas fa-clock mr-2"></i>CICILAN
                            @else
                                <i class="fas fa-exclamation-circle mr-2"></i>BELUM LUNAS
                            @endif
                        </div>
                        @if($pembelian->status_pembayaran !== 'lunas')
                            <div class="text-lg font-semibold text-red-200">
                                Sisa Tagihan: @rupiah($sisaTagihan)
                            </div>
                        @endif
                    </div>
                </div>

                @if($pembelian->status_pembayaran === 'cicilan' && $sisaTagihan > 0)
                    <div class="mt-6">
                        <a href="{{ route('pengurus.pembayaran-cicilan.create', $pembelian->id) }}" 
                           class="action-button-enhanced w-full justify-center">
                            <i class="fas fa-plus-circle"></i>
                            Catat Pembayaran Cicilan
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Items & Payment History -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items Detail -->
            <div class="detail-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-shopping-basket text-white text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Detail Barang</h3>
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $pembelian->detailPembelians->count() }} item
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($pembelian->detailPembelians as $detail)
                        <div class="item-card p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h5 class="font-bold text-gray-800 text-lg">{{ $detail->barang->nama_barang ?? 'Barang Dihapus' }}</h5>
                                    <p class="text-sm text-gray-500 mb-2">
                                        <i class="fas fa-barcode mr-1"></i>
                                        {{ $detail->barang->kode_barang ?? '-' }}
                                    </p>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                        <span>
                                            <i class="fas fa-calculator mr-1 text-blue-500"></i>
                                            {{ $detail->jumlah }} × @rupiah($detail->harga_satuan)
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gray-800">@rupiah($detail->subtotal)</div>
                                    <div class="text-sm text-gray-500">Subtotal</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Tidak ada detail barang</p>
                        </div>
                    @endforelse
                </div>

                <!-- Total Summary -->
                <div class="total-summary mt-6">
                    <div class="text-sm opacity-90= mb-1">TOTAL KESELURUHAN</div>
                    <div class="text-3xl font-bold">@rupiah($pembelian->total_harga)</div>
                </div>
            </div>

            <!-- Payment History (Cicilan) -->
            @if($pembelian->status_pembayaran === 'cicilan' || $pembelian->cicilans->isNotEmpty())
            <div class="detail-card p-6">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-history text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Riwayat Pembayaran Cicilan</h3>
                </div>

                @if($pembelian->cicilans->isNotEmpty())
                    <div class="cicilan-timeline">
                        @foreach($pembelian->cicilans->sortByDesc('tanggal_bayar') as $cicilan)
                            <div class="cicilan-item">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="font-bold text-gray-800 text-lg">
                                            @rupiah($cicilan->jumlah_bayar)
                                        </div>
                                        <div class="text-sm text-gray-600 mb-2">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ \Carbon\Carbon::parse($cicilan->tanggal_bayar)->isoFormat('DD MMMM YYYY') }}
                                        </div>
                                        @if($cicilan->keterangan)
                                            <div class="text-sm text-gray-500">
                                                <i class="fas fa-sticky-note mr-1"></i>
                                                {{ $cicilan->keterangan }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500">Dicatat oleh</div>
                                        <div class="font-semibold text-gray-700">{{ $cicilan->pengurus->name ?? 'Sistem' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-clock text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg font-medium">Belum ada pembayaran cicilan</p>
                        <p class="text-gray-400">Cicilan akan muncul setelah anggota melakukan pembayaran</p>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-wrap justify-center gap-4 pt-8">
        <a href="{{ route('pengurus.transaksi-pembelian.index') }}" 
           class="action-button-enhanced bg-gradient-to-r from-gray-500 to-gray-600">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Daftar
        </a>
        <button onclick="window.print()" 
                class="action-button-enhanced bg-gradient-to-r from-green-500 to-emerald-600">
            <i class="fas fa-print"></i>
            Cetak Struk
        </button>
    </div>
</div>

<!-- Floating Action Button -->
<div class="floating-action">
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
            title="Kembali ke Atas">
        <i class="fas fa-arrow-up text-xl"></i>
    </button>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced animations on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(20px)';
                entry.target.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 100);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.detail-card').forEach(card => {
        observer.observe(card);
    });

    // Enhanced hover effects for info items
    document.querySelectorAll('.info-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(4px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // Smooth scroll for floating action button
    const floatingBtn = document.querySelector('.floating-action button');
    if (floatingBtn) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                floatingBtn.style.opacity = '1';
                floatingBtn.style.transform = 'scale(1)';
            } else {
                floatingBtn.style.opacity = '0';
                floatingBtn.style.transform = 'scale(0.8)';
            }
        });
    }

    // Print functionality enhancement
    window.addEventListener('beforeprint', function() {
        document.body.classList.add('printing');
    });

    window.addEventListener('afterprint', function() {
        document.body.classList.remove('printing');
    });
});
</script>
@endpush
