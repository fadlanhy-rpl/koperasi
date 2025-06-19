@extends('layouts.app')

@section('title', 'Detail Pembelian: ' . $pembelian->kode_pembelian)
@section('page-title', 'Detail Transaksi Pembelian')
@section('page-subtitle', 'Rincian untuk transaksi Anda #' . $pembelian->kode_pembelian)

@push('styles')
<style>
    .detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }

    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #f59e0b, #10b981);
        background-size: 400% 100%;
        animation: gradient-flow 3s ease infinite;
    }

    @keyframes gradient-flow {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        background: linear-gradient(135deg, rgba(249, 250, 251, 0.8) 0%, rgba(243, 244, 246, 0.6) 100%);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1f2937;
        display: flex;
        align-items: center;
        margin: 0;
    }

    .card-content {
        padding: 2rem;
    }

    .info-grid {
        display: grid;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: rgba(249, 250, 251, 0.6);
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1px solid rgba(229, 231, 235, 0.3);
    }

    .info-item:hover {
        background: rgba(59, 130, 246, 0.05);
        border-color: rgba(59, 130, 246, 0.2);
        transform: translateX(4px);
    }

    .info-label {
        font-weight: 600;
        color: #6b7280;
        display: flex;
        align-items: center;
        font-size: 0.9rem;
    }

    .info-value {
        font-weight: 700;
        color: #1f2937;
        text-align: right;
        font-size: 0.95rem;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .status-lunas {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .status-cicilan {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .status-belum-lunas {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .price-highlight {
        font-size: 1.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .table-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .table-header th {
        padding: 1rem;
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    .table-row {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f3f4f6;
    }

    .table-row:hover {
        background: rgba(59, 130, 246, 0.03);
        transform: scale(1.01);
    }

    .table-cell {
        padding: 1rem;
        vertical-align: middle;
    }

    .product-name {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .product-code {
        font-size: 0.8rem;
        color: #6b7280;
        background: rgba(107, 114, 128, 0.1);
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
    }

    .quantity-badge {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }

    .total-row {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
        border-top: 2px solid #3b82f6;
    }

    .total-cell {
        padding: 1.5rem 1rem;
        font-weight: 900;
        font-size: 1.1rem;
    }

    .installment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: rgba(249, 250, 251, 0.6);
        border-radius: 12px;
        margin-bottom: 0.75rem;
        transition: all 0.3s ease;
        border-left: 4px solid #10b981;
    }

    .installment-item:hover {
        background: rgba(16, 185, 129, 0.05);
        transform: translateX(4px);
    }

    .installment-date {
        font-weight: 600;
        color: #374151;
    }

    .installment-amount {
        font-weight: 800;
        color: #10b981;
        font-size: 1.1rem;
    }

    .installment-note {
        font-size: 0.85rem;
        color: #6b7280;
        font-style: italic;
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
    }

    .back-button:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 12px 35px rgba(107, 114, 128, 0.4);
        color: white;
        text-decoration: none;
    }

    .note-section {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #f59e0b;
        border-radius: 16px;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .note-title {
        color: #92400e;
        font-weight: 800;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .note-content {
        color: #78350f;
        line-height: 1.6;
    }

    .animate-fade-in {
        animation: fadeIn 0.8s ease-out;
    }

    .animate-slide-up {
        animation: slideUp 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 1024px) {
        .detail-container {
            padding: 0.5rem;
        }
        
        .card-content {
            padding: 1.5rem;
        }
        
        .modern-table {
            font-size: 0.9rem;
        }
        
        .table-cell {
            padding: 0.75rem;
        }
    }

    @media (max-width: 768px) {
        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .info-value {
            text-align: left;
        }
        
        .installment-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="detail-container animate-fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Transaction Information Column -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Transaction Info Card -->
            <div class="modern-card animate-slide-up">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-receipt mr-3 text-blue-500"></i>
                        Informasi Transaksi
                    </h3>
                </div>
                <div class="card-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-hashtag mr-2 text-blue-500"></i>
                                Kode Transaksi
                            </span>
                            <span class="info-value">{{ $pembelian->kode_pembelian }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-calendar mr-2 text-green-500"></i>
                                Tanggal
                            </span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('DD MMMM YYYY, HH:mm') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-user-tie mr-2 text-purple-500"></i>
                                Kasir
                            </span>
                            <span class="info-value">{{ $pembelian->kasir->name ?? 'Sistem' }}</span>
                        </div>
                    </div>
                    
                    @if($pembelian->catatan)
                        <div class="note-section">
                            <div class="note-title">
                                <i class="fas fa-sticky-note mr-2"></i>
                                Catatan dari Koperasi
                            </div>
                            <p class="note-content">{{ $pembelian->catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Info Card -->
            <div class="modern-card animate-slide-up" style="animation-delay: 0.1s">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-credit-card mr-3 text-green-500"></i>
                        Informasi Pembayaran
                    </h3>
                </div>
                <div class="card-content">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-money-bill-wave mr-2 text-blue-500"></i>
                                Total Belanja
                            </span>
                            <span class="info-value price-highlight">@rupiah($pembelian->total_harga)</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-wallet mr-2 text-purple-500"></i>
                                Metode Bayar
                            </span>
                            <span class="info-value">{{ ucfirst(str_replace('_', ' ', $pembelian->metode_pembayaran)) }}</span>
                        </div>
                        
                        @if($pembelian->metode_pembayaran == 'tunai' || ($pembelian->total_bayar > 0 && $pembelian->status_pembayaran != 'cicilan'))
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-hand-holding-usd mr-2 text-green-500"></i>
                                Dibayar Saat Transaksi
                            </span>
                            <span class="info-value">@rupiah($pembelian->total_bayar)</span>
                        </div>
                        @endif
                        
                        @if($pembelian->metode_pembayaran == 'tunai' && $pembelian->kembalian > 0)
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-coins mr-2 text-yellow-500"></i>
                                Kembalian Diterima
                            </span>
                            <span class="info-value">@rupiah($pembelian->kembalian)</span>
                        </div>
                        @endif
                        
                        <div class="info-item">
                            <span class="info-label">
                                <i class="fas fa-check-circle mr-2 text-indigo-500"></i>
                                Status Pembayaran
                            </span>
                            <span class="status-badge 
                                @if($pembelian->status_pembayaran == 'lunas') status-lunas
                                @elseif($pembelian->status_pembayaran == 'cicilan') status-cicilan
                                @else status-belum-lunas @endif">
                                @if($pembelian->status_pembayaran == 'lunas')
                                    <i class="fas fa-check-circle mr-1"></i>
                                @elseif($pembelian->status_pembayaran == 'cicilan')
                                    <i class="fas fa-clock mr-1"></i>
                                @else
                                    <i class="fas fa-times-circle mr-1"></i>
                                @endif
                                {{ ucfirst($pembelian->status_pembayaran) }}
                            </span>
                        </div>
                        
                        @if($pembelian->status_pembayaran !== 'lunas' && $sisaTagihan > 0)
                        <div class="info-item" style="border-left: 4px solid #ef4444;">
                            <span class="info-label text-red-600">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Sisa Tagihan Anda
                            </span>
                            <span class="info-value text-red-600 font-black text-lg">@rupiah($sisaTagihan)</span>
                        </div>
                        @endif
                    </div>
                    
                    @if($pembelian->status_pembayaran !== 'lunas' && $sisaTagihan > 0)
                        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-700 font-medium">
                                <i class="fas fa-info-circle mr-2"></i>
                                Silakan lakukan pembayaran cicilan di koperasi.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items and Installments Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items Table Card -->
            <div class="modern-card animate-slide-up" style="animation-delay: 0.2s">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shopping-bag mr-3 text-orange-500"></i>
                        Barang yang Dibeli
                    </h3>
                </div>
                <div class="card-content">
                    <div class="overflow-x-auto">
                        <table class="modern-table">
                            <thead class="table-header">
                                <tr>
                                    <th class="text-left">
                                        <i class="fas fa-box mr-2"></i>
                                        Nama Barang
                                    </th>
                                    <th class="text-center">
                                        <i class="fas fa-sort-numeric-up mr-2"></i>
                                        Jumlah
                                    </th>
                                    <th class="text-right">
                                        <i class="fas fa-tag mr-2"></i>
                                        Harga Satuan
                                    </th>
                                    <th class="text-right">
                                        <i class="fas fa-calculator mr-2"></i>
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembelian->detailPembelians as $detail)
                                <tr class="table-row">
                                    <td class="table-cell">
                                        <div class="product-name">{{ $detail->barang->nama_barang ?? 'Barang Dihapus' }}</div>
                                        <div class="product-code">{{ $detail->barang->kode_barang ?? '-' }}</div>
                                    </td>
                                    <td class="table-cell text-center">
                                        <span class="quantity-badge">
                                            <i class="fas fa-cubes mr-1"></i>
                                            {{ $detail->jumlah }} {{ $detail->barang->satuan ?? '' }}
                                        </span>
                                    </td>
                                    <td class="table-cell text-right font-semibold text-gray-700">
                                        @rupiah($detail->harga_satuan)
                                    </td>
                                    <td class="table-cell text-right font-bold text-gray-800">
                                        @rupiah($detail->subtotal)
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="table-cell text-center py-8 text-gray-400">
                                        <i class="fas fa-inbox text-4xl mb-3"></i>
                                        <div>Tidak ada detail barang.</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="3" class="total-cell text-right text-gray-800">
                                        <i class="fas fa-equals mr-2"></i>
                                        TOTAL BELANJA
                                    </td>
                                    <td class="total-cell text-right">
                                        <span class="price-highlight">@rupiah($pembelian->total_harga)</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Installment History Card -->
            @if($pembelian->cicilans->isNotEmpty())
            <div class="modern-card animate-slide-up" style="animation-delay: 0.3s">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history mr-3 text-indigo-500"></i>
                        Riwayat Pembayaran Cicilan Anda
                    </h3>
                </div>
                <div class="card-content">
                    @foreach($pembelian->cicilans as $cicilan)
                        <div class="installment-item">
                            <div class="flex-1">
                                <div class="installment-date">
                                    <i class="fas fa-calendar-check mr-2 text-green-500"></i>
                                    {{ \Carbon\Carbon::parse($cicilan->tanggal_bayar)->isoFormat('DD MMMM YYYY') }}
                                </div>
                                @if($cicilan->keterangan)
                                    <div class="installment-note">{{ $cicilan->keterangan }}</div>
                                @endif
                            </div>
                            <div class="installment-amount">
                                <i class="fas fa-money-bill mr-2"></i>
                                @rupiah($cicilan->jumlah_bayar)
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-8 flex justify-start animate-slide-up" style="animation-delay: 0.4s">
        <a href="{{ route('anggota.pembelian.riwayat') }}" class="back-button">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Riwayat Pembelian
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for better UX
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

    // Add loading state for back button
    const backButton = document.querySelector('.back-button');
    if (backButton) {
        backButton.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Kembali...';
            
            // Restore after navigation (fallback)
            setTimeout(() => {
                this.innerHTML = originalText;
            }, 2000);
        });
    }

    // Enhanced table row interactions
    const tableRows = document.querySelectorAll('.table-row');
    tableRows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.1}s`;
    });

    // Add intersection observer for staggered animations
    const animatedElements = document.querySelectorAll('.animate-slide-up');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
        observer.observe(el);
    });
});
</script>
@endpush
