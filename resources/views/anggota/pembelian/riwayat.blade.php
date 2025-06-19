@extends('layouts.app')

@section('title', 'Riwayat Pembelian Saya - Koperasi')
@section('page-title', 'Riwayat Pembelian Saya')
@section('page-subtitle', 'Daftar semua transaksi pembelian yang telah Anda lakukan')

@push('styles')
<style>
    .riwayat-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .riwayat-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        opacity: 0.3;
        pointer-events: none;
    }

    .riwayat-content {
        position: relative;
        z-index: 1;
        padding: 2rem 1rem;
    }

    .page-header {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 32px;
        padding: 3rem 2rem;
        margin-bottom: 3rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
        animation: float 8s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .page-title {
        font-size: 3rem;
        font-weight: 900;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1rem;
        line-height: 1.1;
        position: relative;
        z-index: 1;
    }

    .page-subtitle {
        font-size: 1.25rem;
        color: #6b7280;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    .filter-container {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .filter-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #f59e0b, #10b981);
        background-size: 400% 100%;
        animation: gradient 3s ease infinite;
    }

    @keyframes gradient {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .filter-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .filter-group {
        position: relative;
        z-index: 1;
    }

    .filter-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .filter-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
    }

    .filter-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        background: rgba(255, 255, 255, 1);
        transform: scale(1.02);
        outline: none;
    }

    .filter-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 12px 24px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
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
        color: white;
        text-decoration: none;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 12px 24px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 6px 20px rgba(107, 114, 128, 0.3);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-secondary:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 10px 30px rgba(107, 114, 128, 0.4);
        color: white;
        text-decoration: none;
    }

    .table-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        background: rgba(249, 250, 251, 0.8);
    }

    .table-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #1f2937;
        display: flex;
        align-items: center;
    }

    .table-content {
        padding: 1.5rem;
        overflow-x: auto;
    }

    .enhanced-table {
        width: 100%;
        min-width: 800px;
        border-collapse: collapse;
    }

    .table-head {
        background: #f8fafc;
    }

    .table-head th {
        padding: 1rem;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    .table-row {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f3f4f6;
    }

    .table-row:hover {
        background: rgba(59, 130, 246, 0.05);
        transform: scale(1.01);
    }

    .table-cell {
        padding: 1rem;
        vertical-align: middle;
    }

    .transaction-code {
        font-weight: 700;
        color: #3b82f6;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
    }

    .transaction-code::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: #3b82f6;
        transition: width 0.3s ease;
    }

    .transaction-code:hover::after {
        width: 100%;
    }

    .transaction-code:hover {
        color: #1d4ed8;
        text-decoration: none;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .status-badge:hover::before {
        left: 100%;
    }

    .status-lunas {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    }

    .status-cicilan {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
    }

    .status-belum-lunas {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
    }

    .action-button {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .action-button:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
        color: white;
        text-decoration: none;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
    }

    .empty-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        position: relative;
        overflow: hidden;
    }

    .empty-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #374151;
        margin-bottom: 1rem;
    }

    .empty-description {
        font-size: 1rem;
        color: #6b7280;
        margin-bottom: 2rem;
    }

    .back-section {
        margin-top: 3rem;
        display: flex;
        justify-content: flex-start;
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

    @media (max-width: 768px) {
        .riwayat-content {
            padding: 1rem;
        }
        
        .page-header {
            padding: 2rem 1rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-buttons {
            flex-direction: column;
        }
        
        .table-content {
            padding: 1rem;
        }
        
        .enhanced-table {
            min-width: 600px;
        }
    }

    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 8px;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
@endpush

@section('content')
{{-- <div class="riwayat-container"> --}}
        <!-- Enhanced Page Header -->
        <div class="page-header animate-fade-in">
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center animate-bounce-in">
                    <i class="fas fa-receipt text-3xl text-white"></i>
                </div>
            </div>
            <h1 class="page-title">Riwayat Pembelian Saya</h1>
            <p class="page-subtitle">Daftar semua transaksi pembelian yang telah Anda lakukan</p>
        </div>

        <!-- Enhanced Filter Section -->
        <div class="filter-container animate-slide-up">
            <h3 class="filter-title">
                <i class="fas fa-filter mr-3 text-blue-500"></i>
                Filter Transaksi
            </h3>
            <form method="GET" action="{{ route('anggota.pembelian.riwayat') }}">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="search_kode_riwayat" class="filter-label">
                            <i class="fas fa-search mr-2 text-blue-500"></i>
                            Cari Kode Transaksi:
                        </label>
                        <input type="text" 
                               id="search_kode_riwayat" 
                               name="search_kode" 
                               value="{{ request('search_kode') }}" 
                               placeholder="Contoh: INV/2024/001"
                               class="filter-input">
                    </div>
                    
                    <div class="filter-group">
                        <label for="status_pembayaran_filter_riwayat" class="filter-label">
                            <i class="fas fa-credit-card mr-2 text-green-500"></i>
                            Status Pembayaran:
                        </label>
                        <select id="status_pembayaran_filter_riwayat" 
                                name="status_pembayaran_filter" 
                                class="filter-input">
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ request('status_pembayaran_filter', 'all') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="tanggal_mulai_filter_riwayat" class="filter-label">
                            <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                            Dari Tanggal:
                        </label>
                        <input type="date" 
                               id="tanggal_mulai_filter_riwayat" 
                               name="tanggal_mulai_filter" 
                               value="{{ request('tanggal_mulai_filter') }}"
                               class="filter-input">
                    </div>
                    
                    <div class="filter-group">
                        <label for="tanggal_selesai_filter_riwayat" class="filter-label">
                            <i class="fas fa-calendar-check mr-2 text-orange-500"></i>
                            Sampai Tanggal:
                        </label>
                        <input type="date" 
                               id="tanggal_selesai_filter_riwayat" 
                               name="tanggal_selesai_filter" 
                               value="{{ request('tanggal_selesai_filter') }}"
                               class="filter-input">
                    </div>
                </div>
                
                <div class="filter-buttons">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-search mr-2"></i>
                        Tampilkan Riwayat
                    </button>
                    <a href="{{ route('anggota.pembelian.riwayat') }}" class="btn-secondary">
                        <i class="fas fa-undo mr-2"></i>
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>

        <!-- Enhanced Table Container -->
        <div class="table-container animate-slide-up" style="animation-delay: 0.2s">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-list mr-3 text-indigo-500"></i>
                    Daftar Transaksi Pembelian Anda
                </h3>
            </div>
            <div class="table-content">
                @if($pembelians->isNotEmpty())
                    <table class="enhanced-table">
                        <thead class="table-head">
                            <tr>
                                <th class="text-left">
                                    <i class="fas fa-hashtag mr-2"></i>
                                    Kode Transaksi
                                </th>
                                <th class="text-left">
                                    <i class="fas fa-calendar mr-2"></i>
                                    Tanggal
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-shopping-bag mr-2"></i>
                                    Jumlah Item
                                </th>
                                <th class="text-right">
                                    <i class="fas fa-money-bill-wave mr-2"></i>
                                    Total Belanja
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Status Pembayaran
                                </th>
                                <th class="text-left">
                                    <i class="fas fa-wallet mr-2"></i>
                                    Metode Bayar
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-cogs mr-2"></i>
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembelians as $pembelian)
                                <tr class="table-row">
                                    <td class="table-cell">
                                        <a href="{{ route('anggota.pembelian.detail', $pembelian->id) }}" 
                                           class="transaction-code">
                                            {{ $pembelian->kode_pembelian }}
                                        </a>
                                    </td>
                                    <td class="table-cell text-gray-700 font-medium">
                                        {{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('DD MMMM YYYY, HH:mm') }}
                                    </td>
                                    <td class="table-cell text-center">
                                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-semibold">
                                            <i class="fas fa-box mr-1"></i>
                                            {{ $pembelian->jumlah_item }} item
                                        </span>
                                    </td>
                                    <td class="table-cell text-right">
                                        <span class="text-2xl font-bold text-gray-800">
                                            @rupiah($pembelian->total_harga)
                                        </span>
                                    </td>
                                    <td class="table-cell text-center">
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
                                    </td>
                                    <td class="table-cell">
                                        <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 rounded-full font-medium">
                                            <i class="fas fa-credit-card mr-2"></i>
                                            {{ ucfirst(str_replace('_', ' ', $pembelian->metode_pembayaran)) }}
                                        </span>
                                    </td>
                                    <td class="table-cell text-center">
                                        <a href="{{ route('anggota.pembelian.detail', $pembelian->id) }}" 
                                           class="action-button" 
                                           title="Lihat Detail Transaksi">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($pembelians->hasPages())
                        <div class="mt-8 flex justify-center">
                            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-white/30 p-4">
                                {{ $pembelians->links('vendor.pagination.tailwind') }}
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Enhanced Empty State -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-shopping-bag text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="empty-title">Belum Ada Riwayat Pembelian</h3>
                        <p class="empty-description">
                            Anda belum memiliki riwayat pembelian. Mulai berbelanja sekarang untuk melihat transaksi Anda di sini.
                        </p>
                        <a href="{{ route('anggota.pembelian.katalog') }}" class="btn-primary">
                            <i class="fas fa-store mr-2"></i>
                            Mulai Berbelanja
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Enhanced Back Section -->
        <div class="back-section animate-slide-up" style="animation-delay: 0.4s">
            <a href="{{ route('anggota.dashboard') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Dashboard
            </a>
        </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced form submission with loading states
    const filterForm = document.querySelector('form');
    const submitButton = filterForm.querySelector('button[type="submit"]');
    
    filterForm.addEventListener('submit', function(e) {
        if (submitButton) {
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            submitButton.disabled = true;
            
            // Re-enable after 5 seconds (fallback)
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 5000);
        }
    });

    // Enhanced table row interactions
    const tableRows = document.querySelectorAll('.table-row');
    tableRows.forEach((row, index) => {
        // Add staggered animation delay
        row.style.animationDelay = `${index * 0.05}s`;
        
        // Add click to view detail functionality
        const detailLink = row.querySelector('.transaction-code');
        if (detailLink) {
            row.addEventListener('click', function(e) {
                // Don't trigger if clicking on action button
                if (!e.target.closest('.action-button')) {
                    window.location.href = detailLink.href;
                }
            });
            
            // Add cursor pointer to indicate clickable
            row.style.cursor = 'pointer';
        }
    });

    // Enhanced intersection observer for animations
    const animatedElements = document.querySelectorAll('.animate-slide-up, .animate-fade-in');
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

    // Enhanced filter input interactions
    const filterInputs = document.querySelectorAll('.filter-input');
    filterInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

    // Add loading skeleton for table while filtering
    function showLoadingSkeleton() {
        const tableBody = document.querySelector('tbody');
        if (tableBody) {
            const skeletonRows = Array.from({length: 5}, () => {
                return `
                    <tr class="table-row">
                        <td class="table-cell"><div class="loading-skeleton h-4 w-24"></div></td>
                        <td class="table-cell"><div class="loading-skeleton h-4 w-32"></div></td>
                        <td class="table-cell text-center"><div class="loading-skeleton h-6 w-16 mx-auto rounded-full"></div></td>
                        <td class="table-cell text-right"><div class="loading-skeleton h-6 w-20 ml-auto"></div></td>
                        <td class="table-cell text-center"><div class="loading-skeleton h-6 w-20 mx-auto rounded-full"></div></td>
                        <td class="table-cell"><div class="loading-skeleton h-6 w-24 rounded-full"></div></td>
                        <td class="table-cell text-center"><div class="loading-skeleton h-8 w-8 mx-auto rounded"></div></td>
                    </tr>
                `;
            }).join('');
            
            tableBody.innerHTML = skeletonRows;
        }
    }

    // Add parallax effect to background
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector('.riwayat-container::before');
        if (parallax) {
            const speed = scrolled * 0.5;
            parallax.style.transform = `translateY(${speed}px)`;
        }
    });
});
</script>
@endpush
