@extends('layouts.app')

@section('title', 'Manajemen Stok Barang - Koperasi')
@section('page-title', 'Manajemen Stok Barang')
@section('page-subtitle', 'Kelola pergerakan masuk, keluar, dan penyesuaian stok dengan sistem terintegrasi')

@push('styles')
    <style>
        .stock-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .stock-hero::before {
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

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
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
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.7s ease;
        }

        .stats-card:hover::before {
            left: 100%;
        }

        .stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .filter-container {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.05) 100%);
            border: 2px solid rgba(16, 185, 129, 0.1);
            backdrop-filter: blur(10px);
        }

        .table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .stock-table {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .stock-row {
            transition: all 0.3s ease;
            position: relative;
        }

        .stock-row::before {
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

        .stock-row:hover::before {
            transform: scaleY(1);
        }

        .stock-row:hover {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.05) 100%);
            transform: translateX(8px);
            box-shadow: 0 8px 25px -8px rgba(16, 185, 129, 0.2);
        }

        .stock-badge {
            font-weight: 700;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stock-normal {
            @apply bg-green-100 text-green-800 border border-green-200;
        }

        .stock-low {
            @apply bg-yellow-100 text-yellow-800 border border-yellow-200;
        }

        .stock-out {
            @apply bg-red-100 text-red-800 border border-red-200;
        }

        .action-btn {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .action-btn:hover::before {
            opacity: 1;
        }

        .action-btn:hover {
            transform: translateY(-2px) scale(1.1);
            box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.25);
        }

        .quick-action-modal {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3); }
        50% { opacity: 1; transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { opacity: 1; transform: scale(1); }
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
    
    .bounce-in {
        animation: bounceIn 0.8s ease-out;
    }   

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }
    </style>
@endpush

@section('content')
    <div class="space-y-8" x-data="stockManagement()" x-init="init()">
        <!-- Enhanced Hero Section -->
        <div class="stock-hero rounded-3xl p-8 text-white shadow-2xl">
            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <div class="space-y-4">
                        <h1 class="text-4xl lg:text-5xl font-bold leading-tight">
                            Manajemen Stok Barang
                            <span class="block text-2xl lg:text-3xl font-normal opacity-90 mt-2">
                                Kontrol Inventori Real-time
                            </span>
                        </h1>
                        <p class="text-lg opacity-80 max-w-2xl">
                            Kelola pergerakan stok dengan sistem terintegrasi dan monitoring real-time untuk efisiensi
                            maksimal
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium opacity-90">Live Monitoring</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Statistics Dashboard -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Total Items -->
            <div class="stats-card rounded-3xl p-8 shadow-xl bounce-in" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div class="space-y-3">
                        <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Item</p>
                        <p class="text-4xl font-bold text-gray-800" x-text="stats.total_items">{{ $stats['total_items'] }}
                        </p>
                        <div class="flex items-center text-blue-600 text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z" />
                            </svg>
                            Produk Terdaftar
                        </div>
                    </div>
                    <div
                        class="w-20 h-20 bg-gradient-to-r from-blue-500 to-blue-600 rounded-3xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="stats-card rounded-3xl p-8 shadow-xl bounce-in" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div class="space-y-3">
                        <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Stok Rendah</p>
                        <p class="text-4xl font-bold text-gray-800" x-text="stats.low_stock">{{ $stats['low_stock'] }}</p>
                        <div class="flex items-center text-yellow-600 text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" />
                            </svg>
                            Perlu Perhatian
                        </div>
                    </div>
                    <div
                        class="w-20 h-20 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-3xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Out of Stock -->
            <div class="stats-card rounded-3xl p-8 shadow-xl bounce-in" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div class="space-y-3">
                        <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Stok Habis</p>
                        <p class="text-4xl font-bold text-gray-800" x-text="stats.out_of_stock">{{ $stats['out_of_stock'] }}
                        </p>
                        <div class="flex items-center text-red-600 text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" />
                            </svg>
                            Segera Restock
                        </div>
                    </div>
                    <div
                        class="w-20 h-20 bg-gradient-to-r from-red-500 to-red-600 rounded-3xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Value -->
            <div class="stats-card rounded-3xl p-8 shadow-xl bounce-in" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between">
                    <div class="space-y-3">
                        <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Nilai Total</p>
                        <p class="text-2xl font-bold text-gray-800">@rupiah($stats['total_value'])</p>
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582z" />
                            </svg>
                            Estimasi Inventori
                        </div>
                    </div>
                    <div
                        class="w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-600 rounded-3xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Filter Section -->
        <div class="filter-container rounded-3xl p-8 fade-in" style="animation-delay: 0.4s">
            <form method="GET" action="{{ route('pengurus.stok.index') }}" class="space-y-6" @submit="handleFilterSubmit">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" />
                        </svg>
                        Filter & Pencarian
                    </h3>
                    <div class="flex space-x-3">
                        <button type="button" @click="resetFilters()"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors duration-200 font-medium">
                            Reset Filter
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl hover:from-emerald-600 hover:to-green-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl">
                            Terapkan Filter
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Search Input -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pencarian</label>
                        <div class="relative">
                            <input type="text" name="search_stok" value="{{ request('search_stok') }}"
                                x-model="filters.search" placeholder="Cari nama barang, kode, atau deskripsi..."
                                class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 bg-white/80 backdrop-blur-sm">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Unit Usaha Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Unit Usaha</label>
                        <select name="unit_usaha_stok" x-model="filters.unit_usaha"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 bg-white/80 backdrop-blur-sm">
                            <option value="">Semua Unit Usaha</option>
                            @foreach ($unitUsahas as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ request('unit_usaha_stok') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama_unit_usaha }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Stock Level Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Level Stok</label>
                        <select name="stock_level" x-model="filters.stock_level"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 bg-white/80 backdrop-blur-sm">
                            <option value="">Semua Level</option>
                            <option value="normal" {{ request('stock_level') == 'normal' ? 'selected' : '' }}>Stok Normal
                            </option>
                            <option value="low" {{ request('stock_level') == 'low' ? 'selected' : '' }}>Stok Rendah
                            </option>
                            <option value="out" {{ request('stock_level') == 'out' ? 'selected' : '' }}>Stok Habis
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Advanced Options -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-200">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Urutkan Berdasarkan</label>
                        <select name="sort_by" x-model="filters.sort_by"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 bg-white/80 backdrop-blur-sm">
                            <option value="nama_barang" {{ request('sort_by') == 'nama_barang' ? 'selected' : '' }}>Nama
                                Barang</option>
                            <option value="stok" {{ request('sort_by') == 'stok' ? 'selected' : '' }}>Jumlah Stok
                            </option>
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal
                                Dibuat</option>
                            <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>Terakhir
                                Diupdate</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Urutan</label>
                        <select name="sort_order" x-model="filters.sort_order"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 bg-white/80 backdrop-blur-sm">
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>A-Z /
                                Rendah-Tinggi</option>
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Z-A /
                                Tinggi-Rendah</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Per Halaman</label>
                        <select name="per_page" x-model="filters.per_page"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-200 bg-white/80 backdrop-blur-sm">
                            <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Data Table Section -->
        <div class="table-container rounded-3xl shadow-2xl overflow-hidden fade-in" style="animation-delay: 0.5s">
            <div class="p-8 pb-0">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Daftar Barang</h2>
                        <p class="text-emerald-600 font-medium">Manajemen stok real-time dengan aksi cepat</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Total: <span
                                class="font-bold text-gray-800">{{ $barangs->total() }}</span> item</span>
                        <button @click="refreshData()"
                            class="action-btn px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl flex items-center space-x-2">
                            <svg class="w-4 h-4" :class="{ 'animate-spin': isRefreshing }" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" />
                            </svg>
                            <span>Refresh</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto" id="table-container">
                @include('pengurus.stok.partials._stock_table', ['barangs' => $barangs])
            </div>
        </div>

        <!-- Quick Action Modal -->
        <div x-show="showQuickModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeQuickModal()"></div>

                <div class="inline-block align-bottom bg-white rounded-3xl px-8 pt-8 pb-6 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Aksi Cepat Stok</h3>
                        <button @click="closeQuickModal()"
                            class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="selectedItem" class="mb-6">
                        <h4 class="font-bold text-lg text-gray-800" x-text="selectedItem?.nama_barang"></h4>
                        <p class="text-gray-600"
                            x-text="`Stok saat ini: ${selectedItem?.stok} ${selectedItem?.satuan || 'unit'}`"></p>
                    </div>

                    <form @submit.prevent="submitQuickAction()" class="space-y-6">
                        <!-- Action Type -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Aksi</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label
                                    class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-300 transition-colors duration-200"
                                    :class="{ 'border-green-500 bg-green-50': quickAction.action === 'add' }">
                                    <input type="radio" x-model="quickAction.action" value="add" class="sr-only">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center"
                                            :class="{ 'border-green-500 bg-green-500': quickAction.action === 'add' }">
                                            <div class="w-2 h-2 bg-white rounded-full"
                                                x-show="quickAction.action === 'add'"></div>
                                        </div>
                                        <span class="font-medium text-green-700">+ Tambah</span>
                                    </div>
                                </label>

                                <label
                                    class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-300 transition-colors duration-200"
                                    :class="{ 'border-red-500 bg-red-50': quickAction.action === 'subtract' }">
                                    <input type="radio" x-model="quickAction.action" value="subtract" class="sr-only">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center"
                                            :class="{ 'border-red-500 bg-red-500': quickAction.action === 'subtract' }">
                                            <div class="w-2 h-2 bg-white rounded-full"
                                                x-show="quickAction.action === 'subtract'"></div>
                                        </div>
                                        <span class="font-medium text-red-700">- Kurangi</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah</label>
                            <input type="number" x-model="quickAction.quantity" min="1"
                                :max="quickAction.action === 'subtract' ? selectedItem?.stok : null" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200"
                                placeholder="Masukkan jumlah">
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea x-model="quickAction.note" rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-200 resize-none"
                                placeholder="Alasan perubahan stok..."></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 pt-4">
                            <button type="button" @click="closeQuickModal()"
                                class="px-6 py-3 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-xl transition-colors duration-200 font-medium">
                                Batal
                            </button>
                            <button type="submit" :disabled="isSubmittingQuick"
                                class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-semibold shadow-lg hover:shadow-xl flex items-center space-x-2"
                                :class="{ 'opacity-50 cursor-not-allowed': isSubmittingQuick }">
                                <template x-if="!isSubmittingQuick">
                                    <span>Simpan</span>
                                </template>
                                <template x-if="isSubmittingQuick">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4" />
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                        </svg>
                                        <span>Menyimpan...</span>
                                    </div>
                                </template>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function stockManagement() {
            return {
                stats: {
                    total_items: {{ $stats['total_items'] }},
                    low_stock: {{ $stats['low_stock'] }},
                    out_of_stock: {{ $stats['out_of_stock'] }},
                    total_value: {{ $stats['total_value'] }}
                },
                filters: {
                    search: '{{ request('search_stok') }}',
                    unit_usaha: '{{ request('unit_usaha_stok') }}',
                    stock_level: '{{ request('stock_level') }}',
                    sort_by: '{{ request('sort_by', 'nama_barang') }}',
                    sort_order: '{{ request('sort_order', 'asc') }}',
                    per_page: '{{ request('per_page', '15') }}'
                },
                showQuickModal: false,
                selectedItem: null,
                quickAction: {
                    action: '',
                    quantity: '',
                    note: ''
                },
                isRefreshing: false,
                isSubmittingQuick: false,

                init() {
                    // Auto-refresh stats every 30 seconds
                    setInterval(() => {
                        this.refreshStats();
                    }, 30000);
                },

                openQuickModal(item) {
                    this.selectedItem = item;
                    this.quickAction = {
                        action: '',
                        quantity: '',
                        note: ''
                    };
                    this.showQuickModal = true;
                },

                closeQuickModal() {
                    this.showQuickModal = false;
                    this.selectedItem = null;
                    this.quickAction = {
                        action: '',
                        quantity: '',
                        note: ''
                    };
                },

                async submitQuickAction() {
                    if (this.isSubmittingQuick) return;

                    if (!this.quickAction.action || !this.quickAction.quantity) {
                        this.showNotification('Pilih aksi dan masukkan jumlah', 'error');
                        return;
                    }

                    if (this.quickAction.action === 'subtract' && this.quickAction.quantity > this.selectedItem.stok) {
                        this.showNotification('Jumlah tidak boleh melebihi stok saat ini', 'error');
                        return;
                    }

                    this.isSubmittingQuick = true;

                    try {
                        const response = await fetch(`/pengurus/stok/${this.selectedItem.id}/quick-update`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                action: this.quickAction.action,
                                quantity: parseInt(this.quickAction.quantity),
                                note: this.quickAction.note
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.showNotification(data.message, 'success');
                            this.closeQuickModal();
                            this.refreshData();
                        } else {
                            this.showNotification(data.message, 'error');
                        }
                    } catch (error) {
                        this.showNotification('Terjadi kesalahan sistem', 'error');
                    } finally {
                        this.isSubmittingQuick = false;
                    }
                },

                async refreshData() {
                    this.isRefreshing = true;

                    try {
                        const params = new URLSearchParams(this.filters);
                        const response = await fetch(`{{ route('pengurus.stok.index') }}?${params}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();

                        if (data.html) {
                            document.getElementById('table-container').innerHTML = data.html;
                            this.stats = data.stats;
                        }
                    } catch (error) {
                        this.showNotification('Gagal memuat data', 'error');
                    } finally {
                        this.isRefreshing = false;
                    }
                },

                async refreshStats() {
                    try {
                        const response = await fetch('{{ route('pengurus.stok.index') }}', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const data = await response.json();
                        if (data.stats) {
                            this.stats = data.stats;
                        }
                    } catch (error) {
                        // Silent fail for background refresh
                    }
                },

                resetFilters() {
                    this.filters = {
                        search: '',
                        unit_usaha: '',
                        stock_level: '',
                        sort_by: 'nama_barang',
                        sort_order: 'asc',
                        per_page: '15'
                    };
                },

                handleFilterSubmit(event) {
                    // Let the form submit naturally
                    return true;
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value);
                },

                showNotification(message, type = 'info') {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-6 right-6 z-50 max-w-sm p-4 rounded-2xl shadow-2xl transform translate-x-full transition-transform duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;

                    const iconPath = type === 'success' ?
                        'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' :
                        type === 'error' ?
                        'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' :
                        'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';

                    notification.innerHTML = `
                <div class='flex items-center space-x-3'>
                    <svg class='w-6 h-6 flex-shrink-0' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='${iconPath}'/>
                    </svg>
                    <p class='font-medium'>${message}</p>
                </div>
            `;

                    document.body.appendChild(notification);
                    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
                    setTimeout(() => {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => notification.remove(), 300);
                    }, 3000);
                }
            }
        }

        // Global function for quick actions from table
        function openQuickStockModal(item) {
            // Find the Alpine component and call its method
            const component = document.querySelector('[x-data*="stockManagement"]').__x.$data;
            component.openQuickModal(item);
        }
    </script>
@endsection
