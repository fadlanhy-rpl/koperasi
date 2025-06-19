@extends('layouts.app')

@section('title', 'Manajemen Unit Usaha - Koperasi')
@section('page-title', 'Unit Usaha Koperasi')
@section('page-subtitle', 'Kelola dan pantau unit-unit bisnis koperasi')

@push('styles')
<style>
    .business-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .business-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        animation: float 20s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .stats-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.7s ease;
    }
    
    .stats-card:hover::before {
        left: 100%;
    }
    
    .stats-card:hover {
        transform: translateY(-12px) scale(1.03);
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.25);
    }
    
    .stats-icon {
        width: 80px;
        height: 80px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .stats-icon::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.3) 50%, transparent 70%);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }
    
    .stats-card:hover .stats-icon::before {
        transform: translateX(100%);
    }
    
    .unit-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .unit-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .unit-card:hover::after {
        opacity: 1;
    }
    
    .unit-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border-color: rgba(16, 185, 129, 0.3);
    }
    
    .search-section {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #d1fae5 100%);
        border: 2px solid rgba(16, 185, 129, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .search-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.3) 50%, transparent 60%);
        transform: translateX(-100%);
        animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .search-input {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(16, 185, 129, 0.2);
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        background: rgba(255, 255, 255, 1);
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1), 0 10px 25px -5px rgba(16, 185, 129, 0.2);
        transform: translateY(-2px);
    }
    
    .view-toggle {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(16, 185, 129, 0.2);
        border-radius: 16px;
        padding: 6px;
        display: flex;
        position: relative;
    }
    
    .view-toggle::before {
        content: '';
        position: absolute;
        top: 6px;
        left: 6px;
        width: calc(50% - 6px);
        height: calc(100% - 12px);
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 12px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px -2px rgba(16, 185, 129, 0.4);
    }
    
    .view-toggle[data-mode="table"]::before {
        transform: translateX(100%);
    }
    
    .view-toggle button {
        padding: 12px 20px;
        border-radius: 12px;
        transition: all 0.3s ease;
        font-weight: 600;
        position: relative;
        z-index: 2;
        flex: 1;
        text-align: center;
    }
    
    .view-toggle button.active {
        color: white;
    }
    
    .view-toggle button:not(.active) {
        color: #6b7280;
    }
    
    .action-btn {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: none;
        cursor: pointer;
        z-index: 10; /* Pastikan tombol di atas elemen lain */
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .action-btn:hover::before {
        opacity: 1;
    }
    
    .action-btn:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.25);
    }
    
    .table-row {
        transition: all 0.3s ease;
        position: relative;
    }
    
    .table-row::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .table-row:hover::before {
        transform: scaleY(1);
    }
    
    .table-row:hover {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.05) 100%);
        transform: translateX(8px);
        box-shadow: 0 8px 25px -8px rgba(16, 185, 129, 0.2);
    }
    
    .empty-state {
        text-align: center;
        padding: 100px 20px;
        position: relative;
    }
    
    .empty-state-icon {
        width: 160px;
        height: 160px;
        margin: 0 auto 32px;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .empty-state-icon::before {
        content: '';
        position: absolute;
        inset: 0;
        background: conic-gradient(from 0deg, transparent, rgba(16, 185, 129, 0.2), transparent);
        animation: rotate 3s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 12px;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .pulse-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #10b981;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }
    
    .slide-in-up {
        animation: slideInUp 0.6s ease-out both;
    }
    
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
    
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .bounce-in {
        animation: bounceIn 0.8s ease-out;
    }
    
    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3); }
        50% { opacity: 1; transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { opacity: 1; transform: scale(1); }
    }
    
    .notification {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 1000;
        max-width: 400px;
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .progress-bar {
        height: 4px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
        overflow: hidden;
        margin-top: 8px;
    }
    
    .progress-fill {
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 2px;
        transition: width 0.1s linear;
    }

    /* Pastikan area action buttons dapat diklik */
    .unit-card-actions {
        position: relative;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="space-y-8" x-data="businessUnitsManager()" x-init="init()">
    <!-- Enhanced Hero Section -->
    <div class="business-hero rounded-3xl p-8 text-white shadow-2xl">
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="space-y-4">
                    <h1 class="text-4xl lg:text-5xl font-bold leading-tight">
                        Unit Usaha Koperasi
                        <span class="block text-2xl lg:text-3xl font-normal opacity-90 mt-2">
                            Kelola Bisnis dengan Efisien
                        </span>
                    </h1>
                    <p class="text-lg opacity-80 max-w-2xl">
                        Pantau dan kelola semua unit bisnis koperasi Anda dalam satu dashboard yang terintegrasi
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="pulse-dot"></div>
                    <span class="text-sm font-medium opacity-90">Live Data</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Dashboard -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Total Unit Usaha -->
        <div class="stats-card rounded-3xl p-8 shadow-xl bounce-in" style="animation-delay: 0.1s">
            <div class="flex items-center justify-between">
                <div class="space-y-3">
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Unit</p>
                    <p class="text-4xl font-bold text-gray-800" x-text="stats.total">{{ $unitUsahas->total() }}</p>
                    <div class="flex items-center text-emerald-600 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        Unit Aktif
                    </div>
                </div>
                <div class="stats-icon bg-gradient-to-r from-blue-500 to-blue-600">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Barang -->
        <div class="stats-card rounded-3xl p-8 shadow-xl bounce-in" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between">
                <div class="space-y-3">
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Barang</p>
                    <p class="text-4xl font-bold text-gray-800" x-text="stats.totalBarang">{{ $unitUsahas->sum(function($unit) { return $unit->barangs_count ?? $unit->barangs()->count(); }) }}</p>
                    <div class="flex items-center text-emerald-600 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM9 9a1 1 0 012 0v4a1 1 0 11-2 0V9z"/>
                        </svg>
                        Produk Tersedia
                    </div>
                </div>
                <div class="stats-icon bg-gradient-to-r from-emerald-500 to-green-600">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM9 9a1 1 0 012 0v4a1 1 0 11-2 0V9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Unit Terbaru -->
        <div class="stats-card rounded-3xl p-8 shadow-xl bounce-in" style="animation-delay: 0.3s">
            <div class="flex items-center justify-between">
                <div class="space-y-3">
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Unit Terbaru</p>
                    <p class="text-4xl font-bold text-gray-800">{{ $unitUsahas->where('created_at', '>=', now()->subDays(30))->count() }}</p>
                    <div class="flex items-center text-emerald-600 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                        </svg>
                        30 Hari Terakhir
                    </div>
                </div>
                <div class="stats-icon bg-gradient-to-r from-purple-500 to-purple-600">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rata-rata Barang -->
        <div class="stats-card rounded-3xl p-8 shadow-xl bounce-in" style="animation-delay: 0.4s">
            <div class="flex items-center justify-between">
                <div class="space-y-3">
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Rata-rata</p>
                    <p class="text-4xl font-bold text-gray-800">{{ $unitUsahas->count() > 0 ? number_format($unitUsahas->sum(function($unit) { return $unit->barangs_count ?? $unit->barangs()->count(); }) / $unitUsahas->count(), 1) : 0 }}</p>
                    <div class="flex items-center text-emerald-600 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Barang per Unit
                    </div>
                </div>
                <div class="stats-icon bg-gradient-to-r from-amber-500 to-orange-600">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Search and Filter Section -->
    <div class="search-section rounded-3xl p-8 shadow-xl">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
            <!-- Enhanced Search Input -->
            <div class="flex-1 max-w-2xl">
                <div class="relative">
                    <input type="text" 
                           x-model="searchTerm" 
                           @input.debounce.500ms="fetchData()"
                           placeholder="Cari unit usaha berdasarkan nama atau deskripsi..." 
                           class="search-input w-full pl-16 pr-6 py-5 rounded-2xl text-lg font-medium placeholder-gray-500">
                    <div class="absolute left-5 top-1/2 transform -translate-y-1/2">
                        <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div x-show="isLoading" class="absolute right-5 top-1/2 transform -translate-y-1/2">
                        <svg class="w-6 h-6 text-emerald-500 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Enhanced Controls -->
            <div class="flex items-center space-x-6">
                <!-- View Toggle -->
                <div class="view-toggle" :data-mode="viewMode">
                    <button @click="setViewMode('grid')" :class="{ 'active': viewMode === 'grid' }" class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Grid</span>
                    </button>
                    <button @click="setViewMode('table')" :class="{ 'active': viewMode === 'table' }" class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        <span>Table</span>
                    </button>
                </div>

                <!-- Add Button -->
                <a href="{{ route('pengurus.unit-usaha.create') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 group">
                    <svg class="w-6 h-6 mr-3 group-hover:rotate-90 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                    </svg>
                    Tambah Unit Usaha
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Content Area -->
    <div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        <!-- Loading State -->
        <div x-show="isLoading" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-show="viewMode === 'grid'">
                <template x-for="i in 6">
                    <div class="loading-skeleton h-64"></div>
                </template>
            </div>
            <div x-show="viewMode === 'table'" class="space-y-4">
                <template x-for="i in 8">
                    <div class="loading-skeleton h-16"></div>
                </template>
            </div>
        </div>

        <!-- Grid View -->
        <div x-show="viewMode === 'grid' && !isLoading" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform scale-95" 
             x-transition:enter-end="opacity-100 transform scale-100">
            <div class="p-8">
                <div id="unitUsahaGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($unitUsahas as $index => $unit)
                        <div class="unit-card rounded-3xl p-8 shadow-lg slide-in-up" 
                             style="animation-delay: {{ $index * 0.1 }}s">
                            <!-- HAPUS @click dari sini untuk menghindari konflik -->
                            <div class="flex items-start justify-between mb-6">
                                <div class="flex-1">
                                    <h3 class="text-2xl font-bold text-gray-800 mb-3 group-hover:text-emerald-700 transition-colors duration-200">
                                        {{ $unit->nama_unit_usaha }}
                                    </h3>
                                    <p class="text-gray-600 text-base leading-relaxed line-clamp-3">
                                        {{ $unit->deskripsi ?: 'Tidak ada deskripsi tersedia untuk unit usaha ini.' }}
                                    </p>
                                </div>
                                <div class="ml-6">
                                    <div class="w-20 h-20 bg-gradient-to-r from-emerald-500 to-green-600 rounded-3xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-6 mb-8">
                                <div class="text-center p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl">
                                    <div class="text-3xl font-bold text-emerald-600 mb-1">
                                        {{ $unit->barangs_count ?? $unit->barangs()->count() }}
                                    </div>
                                    <div class="text-sm text-gray-600 font-medium uppercase tracking-wider">Barang</div>
                                </div>
                                <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl">
                                    <div class="text-xl font-bold text-blue-600 mb-1">
                                        {{ $unit->created_at->format('M Y') }}
                                    </div>
                                    <div class="text-sm text-gray-600 font-medium uppercase tracking-wider">Dibuat</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                                <div class="flex items-center space-x-2 text-gray-500">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                    </svg>
                                    <span class="text-sm font-medium">{{ $unit->created_at->diffForHumans() }}</span>
                                </div>
                                <!-- Area tombol action dengan z-index tinggi -->
                                <div class="unit-card-actions flex items-center space-x-3">
                                    <a href="{{ route('pengurus.unit-usaha.edit', $unit->id) }}" 
                                       class="action-btn bg-blue-500 hover:bg-blue-600 text-white" 
                                       title="Edit Unit Usaha">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </a>
                                    <button @click.stop="confirmDelete('{{ route('pengurus.unit-usaha.destroy', $unit->id) }}', '{{ $unit->nama_unit_usaha }}')" 
                                            class="action-btn bg-red-500 hover:bg-red-600 text-white" 
                                            title="Hapus Unit Usaha">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($unitUsahas->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-600 mb-4">Belum Ada Unit Usaha</h3>
                        <p class="text-gray-500 text-lg max-w-md mx-auto mb-8 leading-relaxed">
                            Mulai dengan menambahkan unit usaha pertama untuk mengelola bisnis koperasi Anda dengan lebih efektif.
                        </p>
                        <a href="{{ route('pengurus.unit-usaha.create') }}" 
                           class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                            </svg>
                            Tambah Unit Usaha Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Table View -->
        <div x-show="viewMode === 'table' && !isLoading" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform scale-95" 
             x-transition:enter-end="opacity-100 transform scale-100">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px]" id="unitUsahaTable">
                    <thead class="bg-gradient-to-r from-gray-50 via-white to-gray-50">
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-8 px-8 font-bold text-gray-700 uppercase text-sm tracking-wider">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"/>
                                    </svg>
                                    <span>Nama Unit Usaha</span>
                                </div>
                            </th>
                            <th class="text-left py-8 px-8 font-bold text-gray-700 uppercase text-sm tracking-wider">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                    </svg>
                                    <span>Deskripsi</span>
                                </div>
                            </th>
                            <th class="text-center py-8 px-8 font-bold text-gray-700 uppercase text-sm tracking-wider">
                                <div class="flex items-center justify-center space-x-3">
                                    <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5z"/>
                                    </svg>
                                    <span>Jumlah Barang</span>
                                </div>
                            </th>
                            <th class="text-left py-8 px-8 font-bold text-gray-700 uppercase text-sm tracking-wider">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/>
                                    </svg>
                                    <span>Dibuat Pada</span>
                                </div>
                            </th>
                            <th class="text-center py-8 px-8 font-bold text-gray-700 uppercase text-sm tracking-wider">
                                <div class="flex items-center justify-center space-x-3">
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947z"/>
                                    </svg>
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="unitUsahaTableBody" class="bg-white divide-y divide-gray-100">
                        @include('pengurus.unit_usaha.partials._unit_usaha_table_rows', ['unitUsahas' => $unitUsahas])
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Enhanced Pagination -->
        <div id="paginationLinksUnitUsaha" class="p-8 border-t border-gray-100 bg-gradient-to-r from-gray-50 via-white to-gray-50">
            {{ $unitUsahas->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>

<!-- Notification Container -->
<div id="notificationContainer"></div>

<script>
function businessUnitsManager() {
    return {
        searchTerm: '{{ request('search') }}',
        viewMode: 'grid',
        isLoading: false,
        stats: {
            total: {{ $unitUsahas->total() }},
            totalBarang: {{ $unitUsahas->sum(function($unit) { return $unit->barangs_count ?? $unit->barangs()->count(); }) }},
        },
        
        init() {
            // Initialize view mode from localStorage
            this.viewMode = localStorage.getItem('unitUsahaViewMode') || 'grid';
            
            // Watch for view mode changes
            this.$watch('viewMode', (value) => {
                localStorage.setItem('unitUsahaViewMode', value);
            });
            
            // Initialize tooltips and other interactive elements
            this.initializeInteractiveElements();
        },
        
        setViewMode(mode) {
            this.viewMode = mode;
            this.showNotification(`Tampilan diubah ke ${mode === 'grid' ? 'Grid' : 'Tabel'}`, 'info');
        },
        
        async fetchData() {
            this.isLoading = true;
            
            try {
                const url = new URL("{{ route('pengurus.unit-usaha.index') }}");
                if (this.searchTerm) url.searchParams.append('search', this.searchTerm);
                url.searchParams.append('page', '1');
                
                const response = await fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (this.viewMode === 'table') {
                    document.getElementById('unitUsahaTableBody').innerHTML = data.html;
                } else {
                    document.getElementById('unitUsahaGrid').innerHTML = data.gridHtml || data.html;
                }
                
                document.getElementById('paginationLinksUnitUsaha').innerHTML = data.pagination;
                
                // Update stats
                this.stats = data.stats || this.stats;
                
                this.showNotification('Data berhasil dimuat', 'success');
                
            } catch (error) {
                console.error('Error fetching data:', error);
                this.showNotification('Gagal memuat data', 'error');
            } finally {
                this.isLoading = false;
            }
        },
        
        confirmDelete(deleteUrl, unitName) {
            if (confirm(`Apakah Anda yakin ingin menghapus unit usaha "${unitName}"?\n\nSemua barang terkait juga akan terhapus jika tidak ada transaksi yang berjalan.`)) {
                this.deleteUnit(deleteUrl, unitName);
            }
        },
        
        async deleteUnit(deleteUrl, unitName) {
            try {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.style.display = 'none';

                const csrfTokenInput = document.createElement('input');
                csrfTokenInput.type = 'hidden';
                csrfTokenInput.name = '_token';
                csrfTokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfTokenInput);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                
                this.showNotification(`Menghapus unit usaha "${unitName}"...`, 'info');
                form.submit();
                
            } catch (error) {
                console.error('Error deleting unit:', error);
                this.showNotification('Gagal menghapus unit usaha', 'error');
            }
        },
        
        showNotification(message, type = 'info', duration = 4000) {
            const notification = document.createElement('div');
            notification.className = `notification bg-white border-l-4 rounded-lg shadow-xl p-6 ${
                type === 'success' ? 'border-green-500' : 
                type === 'error' ? 'border-red-500' : 
                type === 'warning' ? 'border-yellow-500' :
                'border-blue-500'
            }`;
            
            const iconColor = type === 'success' ? 'text-green-500' : 
                             type === 'error' ? 'text-red-500' : 
                             type === 'warning' ? 'text-yellow-500' :
                             'text-blue-500';
            
            const iconPath = type === 'success' ? 
                'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' :
                type === 'error' ?
                'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' :
                'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
            
            notification.innerHTML = `
                <div class='flex items-start space-x-4'>
                    <svg class='w-6 h-6 ${iconColor} flex-shrink-0 mt-0.5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='${iconPath}'/>
                    </svg>
                    <div class='flex-1'>
                        <p class='text-gray-800 font-medium'>${message}</p>
                        <div class='progress-bar mt-3'>
                            <div class='progress-fill' style='width: 100%'></div>
                        </div>
                    </div>
                    <button onclick='this.parentElement.parentElement.remove()' class='text-gray-400 hover:text-gray-600 transition-colors duration-200'>
                        <svg class='w-5 h-5' fill='currentColor' viewBox='0 0 20 20'>
                            <path fill-rule='evenodd' d='M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z'/>
                        </svg>
                    </button>
                </div>
            `;
            
            document.getElementById('notificationContainer').appendChild(notification);
            
            // Show notification
            setTimeout(() => notification.classList.add('show'), 100);
            
            // Auto hide
            const progressFill = notification.querySelector('.progress-fill');
            let width = 100;
            const interval = setInterval(() => {
                width -= (100 / (duration / 50));
                progressFill.style.width = width + '%';
                if (width <= 0) {
                    clearInterval(interval);
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 300);
                }
            }, 50);
        },
        
        initializeInteractiveElements() {
            // Add any additional interactive element initialization here
            console.log('Interactive elements initialized');
        }
    }
}
</script>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced pagination handling
    document.getElementById('paginationLinksUnitUsaha').addEventListener('click', function(event) {
        const target = event.target.closest('a');
        if (target && target.href && !target.classList.contains('disabled') && !target.querySelector('span[aria-disabled="true"]')) {
            event.preventDefault();
            
            // Show loading state
            const tableBody = document.getElementById('unitUsahaTableBody');
            const gridContainer = document.getElementById('unitUsahaGrid');
            const viewMode = localStorage.getItem('unitUsahaViewMode') || 'grid';
            
            if (viewMode === 'table' && tableBody) {
                tableBody.innerHTML = '<tr><td colspan="5" class="py-16 text-center"><div class="loading-skeleton h-16 mx-auto max-w-md"></div></td></tr>';
            } else if (gridContainer) {
                gridContainer.innerHTML = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"><div class="loading-skeleton h-64"></div><div class="loading-skeleton h-64"></div><div class="loading-skeleton h-64"></div></div>';
            }
            
            const url = new URL(target.href);
            const searchInput = document.querySelector('input[x-model="searchTerm"]');
            const search = searchInput ? searchInput.value : '';

            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');

            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (viewMode === 'table' && tableBody) {
                    tableBody.innerHTML = data.html;
                } else if (gridContainer) {
                    gridContainer.innerHTML = data.gridHtml || data.html;
                }
                
                document.getElementById('paginationLinksUnitUsaha').innerHTML = data.pagination;
                
                // Smooth scroll to content
                const scrollTarget = viewMode === 'table' ? 
                    document.getElementById('unitUsahaTable') : 
                    document.getElementById('unitUsahaGrid');
                    
                if (scrollTarget) {
                    scrollTarget.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start',
                        inline: 'nearest'
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching paginated data:', error);
                // Show error state
                const errorHtml = '<div class="text-center py-16"><div class="text-red-500 text-lg font-medium">Gagal memuat data. Silakan coba lagi.</div></div>';
                if (viewMode === 'table' && tableBody) {
                    tableBody.innerHTML = `<tr><td colspan="5">${errorHtml}</td></tr>`;
                } else if (gridContainer) {
                    gridContainer.innerHTML = errorHtml;
                }
            });
        }
    });
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        // Ctrl/Cmd + K to focus search
        if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
            event.preventDefault();
            const searchInput = document.querySelector('input[x-model="searchTerm"]');
            if (searchInput) {
                searchInput.focus();
                searchInput.select();
            }
        }
        
        // Ctrl/Cmd + G to toggle grid view
        if ((event.ctrlKey || event.metaKey) && event.key === 'g') {
            event.preventDefault();
            const alpineComponent = document.querySelector('[x-data]');
            if (alpineComponent && alpineComponent.__x) {
                alpineComponent.__x.$data.setViewMode('grid');
            }
        }
        
        // Ctrl/Cmd + T to toggle table view
        if ((event.ctrlKey || event.metaKey) && event.key === 't') {
            event.preventDefault();
            const alpineComponent = document.querySelector('[x-data]');
            if (alpineComponent && alpineComponent.__x) {
                alpineComponent.__x.$data.setViewMode('table');
            }
        }
    });
});

// Success/Error notifications from server
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        const alpineComponent = document.querySelector('[x-data]');
        if (alpineComponent && alpineComponent.__x) {
            setTimeout(() => {
                alpineComponent.__x.$data.showNotification('{{ session('success') }}', 'success');
            }, 500);
        }
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        const alpineComponent = document.querySelector('[x-data]');
        if (alpineComponent && alpineComponent.__x) {
            setTimeout(() => {
                alpineComponent.__x.$data.showNotification('{{ session('error') }}', 'error');
            }, 500);
        }
    });
@endif

@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        const alpineComponent = document.querySelector('[x-data]');
        if (alpineComponent
        const alpineComponent = document.querySelector('[x-data]');
        if (alpineComponent && alpineComponent.__x) {
            setTimeout(() => {
                alpineComponent.__x.$data.showNotification('Terdapat kesalahan dalam input data', 'error');
            }, 500);
        }
    });
@endif
</script>
@endpush