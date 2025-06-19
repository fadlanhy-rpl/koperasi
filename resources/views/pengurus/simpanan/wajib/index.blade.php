@extends('layouts.app')

@section('title', 'Manajemen Simpanan Wajib - Koperasi')
@section('page-title', 'Simpanan Wajib Anggota')
@section('page-subtitle', 'Kelola dan catat simpanan wajib anggota per periode')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .stats-card {
        background: linear-gradient(135deg, var(--tw-gradient-from), var(--tw-gradient-to));
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .stats-card:hover::before {
        opacity: 1;
    }
    
    .stats-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .form-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .table-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .member-select .select2-container--default .select2-selection--single {
        height: 52px !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 16px !important;
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        transition: all 0.3s ease !important;
    }
    
    .member-select .select2-container--default .select2-selection--single:hover {
        border-color: #10b981 !important;
        box-shadow: 0 8px 15px -3px rgba(16, 185, 129, 0.1) !important;
    }
    
    .member-select .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1f2937 !important;
        font-weight: 600 !important;
        font-size: 15px !important;
        line-height: 48px !important;
        padding-left: 16px !important;
    }
    
    .member-select .select2-dropdown {
        border: 2px solid #10b981 !important;
        border-radius: 16px !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        backdrop-filter: blur(20px) !important;
    }
    
    .member-select .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: #ffffff !important;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.3s ease;
    }
    
    .status-paid {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 2px solid #10b981;
    }
    
    .status-unpaid {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 2px solid #ef4444;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: all 0.3s ease;
    }
    
    .action-btn:hover::before {
        width: 100px;
        height: 100px;
    }
    
    .action-btn:hover {
        transform: translateY(-2px) scale(1.1);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
    }
    
    .filter-section {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        border: 2px solid #d1fae5;
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .table-row {
        transition: all 0.3s ease;
        border-radius: 12px;
        margin: 4px 0;
    }
    
    .table-row:hover {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        transform: translateX(8px);
        box-shadow: 0 4px 12px -2px rgba(16, 185, 129, 0.15);
    }
    
    .avatar {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        color: white;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 4px 12px -2px rgba(16, 185, 129, 0.4);
    }
    
    .currency-input {
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        padding: 16px 16px 16px 48px;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .currency-input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        background: #ffffff;
    }
    
    .form-input {
        background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%);
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        padding: 16px;
        font-size: 15px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .form-input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        background: #ffffff;
    }
    
    .submit-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 16px;
        padding: 16px 32px;
        color: white;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }
    
    .submit-btn:hover::before {
        left: 100%;
    }
    
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px -5px rgba(16, 185, 129, 0.4);
    }
    
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #6b7280;
    }
    
    .empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 24px;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #9ca3af;
    }
</style>
@endpush

@section('content')
<div class="space-y-8">
    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Anggota -->
        <div class="stats-card from-emerald-500 to-green-600 rounded-3xl p-8 text-white shadow-xl">
            <div class="flex items-center justify-between">
                <div class="space-y-2">
                    <p class="text-emerald-100 text-sm font-semibold uppercase tracking-wider">Total Anggota</p>
                    <p class="text-4xl font-bold">{{ $anggotas->total() }}</p>
                    <div class="flex items-center text-emerald-200 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Terdaftar Aktif
                    </div>
                </div>
                <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Sudah Bayar -->
        <div class="stats-card from-blue-500 to-blue-600 rounded-3xl p-8 text-white shadow-xl">
            <div class="flex items-center justify-between">
                <div class="space-y-2">
                    <p class="text-blue-100 text-sm font-semibold uppercase tracking-wider">Sudah Bayar</p>
                    <p class="text-4xl font-bold">{{ $anggotas->filter(fn($a) => $a->sudah_bayar_wajib_periode_ini)->count() }}</p>
                    <div class="flex items-center text-blue-200 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        Pembayaran Lunas
                    </div>
                </div>
                <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Belum Bayar -->
        <div class="stats-card from-red-500 to-red-600 rounded-3xl p-8 text-white shadow-xl">
            <div class="flex items-center justify-between">
                <div class="space-y-2">
                    <p class="text-red-100 text-sm font-semibold uppercase tracking-wider">Belum Bayar</p>
                    <p class="text-4xl font-bold">{{ $anggotas->filter(fn($a) => !$a->sudah_bayar_wajib_periode_ini)->count() }}</p>
                    <div class="flex items-center text-red-200 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                        </svg>
                        Perlu Tindak Lanjut
                    </div>
                </div>
                <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Periode -->
        <div class="stats-card from-purple-500 to-purple-600 rounded-3xl p-8 text-white shadow-xl">
            <div class="flex items-center justify-between">
                <div class="space-y-2">
                    <p class="text-purple-100 text-sm font-semibold uppercase tracking-wider">Periode</p>
                    <p class="text-2xl font-bold">{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</p>
                    <div class="flex items-center text-purple-200 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                        </svg>
                        Periode Aktif
                    </div>
                </div>
                <div class="bg-white/20 p-4 rounded-2xl backdrop-blur-sm">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Enhanced Form Section -->
        <div class="xl:col-span-1">
            <div class="form-card rounded-3xl shadow-2xl overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-emerald-50 via-green-50 to-teal-50 p-8 border-b border-emerald-100">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-r from-emerald-500 to-green-600 p-3 rounded-2xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">Catat Simpanan Wajib</h3>
                            <p class="text-emerald-600 font-medium">Tambahkan pembayaran baru</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-8">
                    <form action="{{ route('pengurus.simpanan.wajib.store') }}" method="POST" id="simpananWajibForm" class="space-y-6">
                        @csrf
                        
                        <!-- Member Selection -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                </svg>
                                Pilih Anggota
                            </label>
                            <div class="member-select">
                                <select name="user_id" id="memberSelectWajib" class="w-full" required>
                                    <option value="">Pilih anggota...</option>
                                    @foreach($semuaAnggota as $anggota)
                                        <option value="{{ $anggota->id }}" {{ old('user_id') == $anggota->id ? 'selected' : '' }}>
                                            {{ $anggota->name }} - {{ $anggota->nomor_anggota ?? 'No ID' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('user_id')
                                <p class="text-red-500 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Amount Input -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
                                </svg>
                                Jumlah Simpanan
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-lg">Rp</span>
                                <input type="text" name="jumlah" id="jumlahWajibInput" 
                                       placeholder="50.000" value="{{ old('jumlah') }}" required 
                                       class="currency-input w-full"
                                       oninput="formatCurrency(this)">
                            </div>
                            @error('jumlah')
                                <p class="text-red-500 text-sm flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                </svg>
                                Tanggal Bayar
                            </label>
                            <input type="date" name="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" 
                                   required class="form-input w-full">
                        </div>

                        <!-- Period Selection -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                    </svg>
                                    Bulan
                                </label>
                                <select name="bulan" required class="form-input w-full">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ (old('bulan', $bulan) == $m) ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="space-y-3">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-2 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                    </svg>
                                    Tahun
                                </label>
                                <input type="number" name="tahun" value="{{ old('tahun', $tahun) }}" 
                                       min="{{ date('Y') - 10 }}" max="{{ date('Y') + 2 }}" 
                                       required class="form-input w-full">
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="space-y-3">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"/>
                                </svg>
                                Keterangan (Opsional)
                            </label>
                            <textarea name="keterangan" rows="3" 
                                      placeholder="Catatan tambahan untuk pembayaran ini..."
                                      class="form-input w-full resize-none">{{ old('keterangan') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6">
                            <button type="submit" id="submitWajibBtn" class="submit-btn w-full">
                                <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Simpan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Enhanced Table Section -->
        <div class="xl:col-span-2">
            <div class="table-card rounded-3xl shadow-2xl overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gradient-to-r from-emerald-50 via-green-50 to-teal-50 p-8 border-b border-emerald-100">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="bg-gradient-to-r place-items-center place-content-center from-emerald-500 to-green-600 p-3 rounded-2xl shadow-lg">
                                <svg class="w-8 h-7 object-cover -translate-x-0.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">Status Pembayaran</h3>
                                <p class="text-emerald-600 font-medium">{{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}</p>
                            </div>
                        </div>

                        <!-- Enhanced Filter Form -->
                        <form method="GET" action="{{ route('pengurus.simpanan.wajib.index') }}" class="flex flex-wrap gap-3">
                            <select name="bulan" class="form-input text-sm min-w-[120px]">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create(null, $m)->translatedFormat('M') }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <select name="tahun" class="form-input text-sm min-w-[100px]">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            
                            <select name="status_bayar_wajib" class="form-input text-sm min-w-[140px]">
                                <option value="">Semua Status</option>
                                <option value="sudah" {{ request('status_bayar_wajib') == 'sudah' ? 'selected' : '' }}>Sudah Bayar</option>
                                <option value="belum" {{ request('status_bayar_wajib') == 'belum' ? 'selected' : '' }}>Belum Bayar</option>
                            </select>
                            
                            <input type="text" name="search_anggota" value="{{ request('search_anggota') }}" 
                                   placeholder="Cari anggota..." class="form-input text-sm min-w-[200px]">
                            
                            <button type="submit" class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-emerald-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"/>
                                </svg>
                                Filter
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-100">
                                    <th class="text-left py-6 px-4 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                            </svg>
                                            <span>Nama Anggota</span>
                                        </div>
                                    </th>
                                    <th class="text-left py-6 px-4 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zM8 6a2 2 0 114 0v1H8V6z"/>
                                            </svg>
                                            <span>No. Anggota</span>
                                        </div>
                                    </th>
                                    <th class="text-center py-6 px-4 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                        <div class="flex items-center justify-center space-x-2">
                                            <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                            </svg>
                                            <span>Status</span>
                                        </div>
                                    </th>
                                    <th class="text-right py-6 px-4 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                        <div class="flex items-center justify-end space-x-2">
                                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
                                            </svg>
                                            <span>Jumlah</span>
                                        </div>
                                    </th>
                                    <th class="text-left py-6 px-4 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                            </svg>
                                            <span>Tanggal</span>
                                        </div>
                                    </th>
                                    <th class="text-center py-6 px-4 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                        <div class="flex items-center justify-center space-x-2">
                                            <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"/>
                                            </svg>
                                            <span>Aksi</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($anggotas as $anggota)
                                    <tr class="table-row">
                                        <td class="py-6 px-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="avatar">
                                                    {{ strtoupper(substr($anggota->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800 text-lg">{{ $anggota->name }}</p>
                                                    <p class="text-sm text-gray-500">Anggota Koperasi</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-6 px-4">
                                            <span class="bg-gray-100 px-4 py-2 rounded-xl text-sm font-semibold text-gray-700">
                                                {{ $anggota->nomor_anggota ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-6 px-4 text-center">
                                            @if($anggota->sudah_bayar_wajib_periode_ini)
                                                <span class="status-badge status-paid">
                                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                                    </svg>
                                                    Sudah Bayar
                                                </span>
                                            @else
                                                <span class="status-badge status-unpaid">
                                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                                    </svg>
                                                    Belum Bayar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-6 px-4 text-right">
                                            @if($anggota->sudah_bayar_wajib_periode_ini && $anggota->detail_pembayaran_wajib)
                                                <span class="text-2xl font-bold text-gray-800">
                                                    @rupiah($anggota->detail_pembayaran_wajib->jumlah)
                                                </span>
                                            @else
                                                <span class="text-gray-400 font-medium text-lg">-</span>
                                            @endif
                                        </td>
                                        <td class="py-6 px-4">
                                            @if($anggota->sudah_bayar_wajib_periode_ini && $anggota->detail_pembayaran_wajib)
                                                <span class="bg-blue-100 text-blue-800 px-3 py-2 rounded-xl text-sm font-semibold">
                                                    {{ \Carbon\Carbon::parse($anggota->detail_pembayaran_wajib->tanggal_bayar)->format('d M Y') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 font-medium">-</span>
                                            @endif
                                        </td>
                                        <td class="py-6 px-4 text-center">
                                            <a href="{{ route('pengurus.simpanan.riwayatAnggota', $anggota->id) }}?tab=wajib" 
                                               class="action-btn bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-lg" 
                                               title="Lihat Riwayat">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-16">
                                            <div class="empty-state">
                                                <div class="empty-state-icon">
                                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zM8 6a2 2 0 114 0v1H8V6z"/>
                                                    </svg>
                                                </div>
                                                <h3 class="text-2xl font-bold text-gray-600 mb-2">Tidak Ada Data</h3>
                                                <p class="text-gray-500 max-w-md mx-auto">
                                                    Belum ada anggota yang terdaftar untuk periode ini atau filter yang Anda gunakan tidak menemukan hasil.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($anggotas->hasPages())
                        <div class="mt-8 flex justify-center">
                            {{ $anggotas->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 with enhanced styling
        $('#memberSelectWajib').select2({
            placeholder: 'Ketik untuk mencari anggota...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Tidak ada anggota yang ditemukan";
                },
                searching: function() {
                    return "Mencari anggota...";
                },
                inputTooShort: function() {
                    return "Ketik minimal 1 karakter untuk mencari";
                }
            },
            templateResult: function(option) {
                if (!option.id) {
                    return option.text;
                }
                
                var $option = $(
                    '<div class="flex items-center space-x-3 p-2">' +
                        '<div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center text-white text-sm font-bold shadow-lg">' +
                            option.text.charAt(0).toUpperCase() +
                        '</div>' +
                        '<div>' +
                            '<div class="font-bold text-gray-800">' + option.text.split(' - ')[0] + '</div>' +
                            '<div class="text-sm text-gray-500">ID: ' + (option.text.split(' - ')[1] || 'Tidak ada') + '</div>' +
                        '</div>' +
                    '</div>'
                );
                return $option;
            },
            templateSelection: function(option) {
                if (!option.id) {
                    return option.text;
                }
                return option.text.split(' - ')[0];
            }
        });

        // Enhanced currency formatting
        window.formatCurrency = function(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value) {
                let formatted = parseInt(value).toLocaleString('id-ID');
                input.value = formatted;
            }
        };
        
        // Enhanced form submission
        $('#simpananWajibForm').on('submit', function(e) {
            const submitBtn = $('#submitWajibBtn');
            const originalHtml = submitBtn.html();
            
            // Validate required fields
            const memberId = $('#memberSelectWajib').val();
            const jumlah = $('#jumlahWajibInput').val();
            
            if (!memberId) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Anggota',
                    text: 'Silakan pilih anggota terlebih dahulu!',
                    confirmButtonColor: '#10b981'
                });
                return false;
            }
            
            if (!jumlah || jumlah.trim() === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Masukkan Jumlah',
                    text: 'Silakan masukkan jumlah simpanan wajib!',
                    confirmButtonColor: '#10b981'
                });
                $('#jumlahWajibInput').focus();
                return false;
            }
            
            // Show loading state with enhanced animation
            submitBtn.html(`
                <svg class="w-5 h-5 inline mr-2 animate-spin" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"/>
                </svg>
                Menyimpan...
            `);
            submitBtn.prop('disabled', true);
            
            // Convert formatted currency back to number
            const cleanValue = jumlah.replace(/[^\d]/g, '');
            $('#jumlahWajibInput').val(cleanValue);
            
            // Re-enable after timeout as fallback
            setTimeout(() => {
                submitBtn.html(originalHtml);
                submitBtn.prop('disabled', false);
            }, 10000);
        });
        
        // Auto-submit search with debounce
        let searchTimeout;
        $('input[name="search_anggota"]').on('input', function() {
            clearTimeout(searchTimeout);
            const form = $(this).closest('form');
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 800);
        });
        
        // Initialize currency formatting on page load
        const jumlahInput = $('#jumlahWajibInput');
        if (jumlahInput.val()) {
            formatCurrency(jumlahInput[0]);
        }

        // Enhanced hover effects
        $('.table-row').hover(
            function() {
                $(this).addClass('shadow-lg');
            },
            function() {
                $(this).removeClass('shadow-lg');
            }
        );
    });
    
    // Success/Error notifications with enhanced styling
    @if(session('success'))
        $(document).ready(function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#10b981',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        });
    @endif
    
    @if($errors->any())
        $(document).ready(function() {
            let errorMessages = '';
            @foreach($errors->all() as $error)
                errorMessages += '<p class="mb-2 text-left">• {{ $error }}</p>';
            @endforeach
            
            Swal.fire({
                icon: 'error',
                title: 'Terdapat Kesalahan!',
                html: '<div class="text-left">' + errorMessages + '</div>',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Perbaiki'
            });
        });
    @endif
</script>
@endpush
