@extends('layouts.app')

@section('title', 'Informasi Simpanan Saya - Koperasi')

@section('page-title', 'Rincian Simpanan Saya')
@section('page-subtitle', 'Lihat semua transaksi dan saldo simpanan Anda')

@section('content')
<div class="animate-fade-in space-y-8" x-data="{ 
    searchTerm: '{{ $filters['search'] ?? '' }}', 
    sortBy: '{{ $filters['sort_by'] ?? 'date' }}', 
    sortOrder: '{{ $filters['sort_order'] ?? 'desc' }}',
    showFilters: false,
    selectedPeriod: '{{ $filters['period'] ?? 'all' }}',
    activeTabSimpanan: 'pokok',
    isLoading: false,
    
    // Method untuk apply filter
    applyFilters() {
        this.isLoading = true;
        const params = new URLSearchParams();
        params.append('search', this.searchTerm);
        params.append('period', this.selectedPeriod);
        params.append('sort_by', this.sortBy);
        params.append('sort_order', this.sortOrder);
        params.append('tab', this.activeTabSimpanan);
        
        fetch(`{{ route('anggota.simpanan.show') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                // Update content berdasarkan tab aktif
                if (this.activeTabSimpanan === 'pokok') {
                    document.getElementById('riwayatAnggotaPokokContent').innerHTML = data.html;
                } else if (this.activeTabSimpanan === 'wajib') {
                    document.getElementById('riwayatAnggotaWajibContent').innerHTML = data.html;
                    if (data.pagination) {
                        document.getElementById('paginationLinksAnggotaWajib').innerHTML = data.pagination;
                    }
                } else if (this.activeTabSimpanan === 'sukarela') {
                    document.getElementById('riwayatAnggotaSukarelaContent').innerHTML = data.html;
                    if (data.pagination) {
                        document.getElementById('paginationLinksAnggotaSukarela').innerHTML = data.pagination;
                    }
                }
                
                // Update URL without page reload
                const newUrl = `{{ route('anggota.simpanan.show') }}?${params.toString()}`;
                window.history.pushState({path: newUrl}, '', newUrl);
                
                this.showSuccessToast('Filter berhasil diterapkan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showErrorToast('Gagal menerapkan filter');
        })
        .finally(() => {
            this.isLoading = false;
        });
    },
    
    // Method untuk reset filter
    resetFilters() {
        this.searchTerm = '';
        this.selectedPeriod = 'all';
        this.sortBy = 'date';
        this.sortOrder = 'desc';
        this.applyFilters();
    },
    
    // Method untuk show toast notification
    showSuccessToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        toast.innerHTML = `
            <div class='flex items-center space-x-2'>
                <svg class='w-5 h-5' fill='currentColor' viewBox='0 0 20 20'>
                    <path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'/>
                </svg>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    },
    
    showErrorToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        toast.innerHTML = `
            <div class='flex items-center space-x-2'>
                <svg class='w-5 h-5' fill='currentColor' viewBox='0 0 20 20'>
                    <path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z'/>
                </svg>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => document.body.removeChild(toast), 300);
        }, 3000);
    }
}">
    <!-- Enhanced Summary Cards with Advanced Animations -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Simpanan Pokok Card -->
        <div class="group relative overflow-hidden bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 p-6 rounded-3xl shadow-xl hover:shadow-2xl transform hover:-translate-y-3 hover:scale-105 transition-all duration-500 text-white cursor-pointer"
             @click="activeTabSimpanan = 'pokok'; applyFilters()">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/5 rounded-full group-hover:scale-125 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="p-4 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 transition-colors duration-300 group-hover:scale-110 transform">
                        <svg class="w-7 h-7 group-hover:rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-blue-100 opacity-80 font-semibold tracking-wider">POKOK</div>
                        <div class="w-8 h-1 bg-blue-200 rounded-full mt-1 group-hover:w-12 transition-all duration-300"></div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <p class="text-sm text-blue-100 font-medium opacity-90">Total Simpanan Pokok</p>
                    <p class="text-4xl font-bold tracking-tight group-hover:scale-105 transition-transform duration-300">
                        @rupiah($simpanan['total_pokok'] ?? 0)
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-blue-100 text-sm">
                            <div class="w-2 h-2 bg-blue-200 rounded-full mr-2 animate-pulse"></div>
                            Simpanan Dasar
                        </div>
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Simpanan Wajib Card -->
        <div class="group relative overflow-hidden bg-gradient-to-br from-emerald-500 via-green-600 to-teal-700 p-6 rounded-3xl shadow-xl hover:shadow-2xl transform hover:-translate-y-3 hover:scale-105 transition-all duration-500 text-white cursor-pointer"
             @click="activeTabSimpanan = 'wajib'; applyFilters()">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/5 rounded-full group-hover:scale-125 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="p-4 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 transition-colors duration-300 group-hover:scale-110 transform">
                        <svg class="w-7 h-7 group-hover:rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10zm-4 1a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm1-4a1 1 0 100 2h.01a1 1 0 100-2H7zm2 0a1 1 0 100 2h.01a1 1 0 100-2H9zm2 0a1 1 0 100 2h.01a1 1 0 100-2H11z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-green-100 opacity-80 font-semibold tracking-wider">WAJIB</div>
                        <div class="w-8 h-1 bg-green-200 rounded-full mt-1 group-hover:w-12 transition-all duration-300"></div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <p class="text-sm text-green-100 font-medium opacity-90">Total Simpanan Wajib</p>
                    <p class="text-4xl font-bold tracking-tight group-hover:scale-105 transition-transform duration-300">
                        @rupiah($simpanan['total_wajib'] ?? 0)
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-green-100 text-sm">
                            <div class="w-2 h-2 bg-green-200 rounded-full mr-2 animate-pulse"></div>
                            Simpanan Rutin
                        </div>
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <svg class="w-5 h-5 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Simpanan Sukarela Card -->
        <div class="group relative overflow-hidden bg-gradient-to-br from-amber-500 via-orange-600 to-red-600 p-6 rounded-3xl shadow-xl hover:shadow-2xl transform hover:-translate-y-3 hover:scale-105 transition-all duration-500 text-white cursor-pointer"
             @click="activeTabSimpanan = 'sukarela'; applyFilters()">
            <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/5 rounded-full group-hover:scale-125 transition-transform duration-500"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="p-4 bg-white/20 rounded-2xl backdrop-blur-sm group-hover:bg-white/30 transition-colors duration-300 group-hover:scale-110 transform">
                        <svg class="w-7 h-7 group-hover:rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-orange-100 opacity-80 font-semibold tracking-wider">SUKARELA</div>
                        <div class="w-8 h-1 bg-orange-200 rounded-full mt-1 group-hover:w-12 transition-all duration-300"></div>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <p class="text-sm text-orange-100 font-medium opacity-90">Saldo Simpanan Sukarela</p>
                    <p class="text-4xl font-bold tracking-tight group-hover:scale-105 transition-transform duration-300">
                        @rupiah($simpanan['saldo_sukarela_terkini'] ?? 0)
                    </p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-orange-100 text-sm">
                            <div class="w-2 h-2 bg-orange-200 rounded-full mr-2 animate-pulse"></div>
                            Simpanan Fleksibel
                        </div>
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <svg class="w-5 h-5 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Tab Navigation with Search and Filters -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
        <!-- Tab Headers with Enhanced Design -->
        <div class="bg-gradient-to-r from-gray-50 via-white to-gray-50 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between p-6 space-y-4 lg:space-y-0">
                <nav class="flex" aria-label="Tabs">
                    <button @click="activeTabSimpanan = 'pokok'; applyFilters()" data-tab="pokok"
                            :class="{ 
                                'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg scale-105': activeTabSimpanan === 'pokok', 
                                'text-gray-600 hover:text-gray-800 hover:bg-gray-100': activeTabSimpanan !== 'pokok' 
                            }"
                            class="flex-1 lg:flex-none px-6 py-3 mx-1 text-center font-semibold text-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-2xl transform hover:scale-105">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                            </svg>
                            <span>Simpanan Pokok</span>
                        </div>
                    </button>
                    <button @click="activeTabSimpanan = 'wajib'; applyFilters()" data-tab="wajib"
                            :class="{ 
                                'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg scale-105': activeTabSimpanan === 'wajib', 
                                'text-gray-600 hover:text-gray-800 hover:bg-gray-100': activeTabSimpanan !== 'wajib' 
                            }"
                            class="flex-1 lg:flex-none px-6 py-3 mx-1 text-center font-semibold text-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded-2xl transform hover:scale-105">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7z"/>
                            </svg>
                            <span>Simpanan Wajib</span>
                        </div>
                    </button>
                    <button @click="activeTabSimpanan = 'sukarela'; applyFilters()" data-tab="sukarela"
                            :class="{ 
                                'bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg scale-105': activeTabSimpanan === 'sukarela', 
                                'text-gray-600 hover:text-gray-800 hover:bg-gray-100': activeTabSimpanan !== 'sukarela' 
                            }"
                            class="flex-1 lg:flex-none px-6 py-3 mx-1 text-center font-semibold text-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 rounded-2xl transform hover:scale-105">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
                            </svg>
                            <span>Simpanan Sukarela</span>
                        </div>
                    </button>
                </nav>

                <!-- Search and Filter Controls -->
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" 
                               x-model="searchTerm" 
                               @input.debounce.500ms="applyFilters()"
                               placeholder="Cari transaksi..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-sm">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    
                    <button @click="showFilters = !showFilters" 
                            :class="{ 'bg-blue-100 text-blue-700': showFilters, 'bg-gray-100 hover:bg-gray-200': !showFilters }"
                            class="flex items-center space-x-2 px-4 py-2 rounded-xl transition-colors duration-200 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                        </svg>
                        <span>Filter</span>
                    </button>
                </div>
            </div>

            <!-- Expandable Filters -->
            <div x-show="showFilters" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="px-6 pb-6 border-t border-gray-100">
                <div class="flex flex-wrap items-center gap-4 mt-4">
                    <select x-model="selectedPeriod" @change="applyFilters()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="all">Semua Periode</option>
                        <option value="thisMonth">Bulan Ini</option>
                        <option value="lastMonth">Bulan Lalu</option>
                        <option value="last3Months">3 Bulan Terakhir</option>
                        <option value="last6Months">6 Bulan Terakhir</option>
                        <option value="thisYear">Tahun Ini</option>
                        <option value="lastYear">Tahun Lalu</option>
                    </select>
                    
                    <select x-model="sortBy" @change="applyFilters()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="date">Urutkan: Tanggal</option>
                        <option value="amount">Urutkan: Jumlah</option>
                    </select>
                    
                    <select x-model="sortOrder" @change="applyFilters()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="desc">Terbaru</option>
                        <option value="asc">Terlama</option>
                    </select>
                    
                    <button @click="resetFilters()" 
                            class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="p-8">
            <!-- Loading Indicator -->
            <div x-show="isLoading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-2 text-gray-600">Memuat data...</span>
            </div>

            <!-- Simpanan Pokok Content -->
            <div x-show="activeTabSimpanan === 'pokok' && !isLoading" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0">
                <div id="riwayatAnggotaPokokContent">
                    @include('anggota.simpanan.partials._riwayat_pokok_table', ['riwayat_pokok' => $simpanan['pokok'] ?? collect()])
                </div>
            </div>

            <!-- Simpanan Wajib Content -->
            <div x-show="activeTabSimpanan === 'wajib' && !isLoading" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0">
                <div id="riwayatAnggotaWajibContent">
                    @include('anggota.simpanan.partials._riwayat_wajib_table', ['riwayat_wajib' => $simpanan['wajib'] ?? collect()])
                </div>
                <div id="paginationLinksAnggotaWajib" class="mt-8">
                    @if(isset($simpanan['wajib']) && $simpanan['wajib']->hasPages())
                        {{ $simpanan['wajib']->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    @endif
                </div>
            </div>

            <!-- Simpanan Sukarela Content -->
            <div x-show="activeTabSimpanan === 'sukarela' && !isLoading" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0">
                <div id="riwayatAnggotaSukarelaContent">
                    @include('anggota.simpanan.partials._riwayat_sukarela_table', ['riwayat_sukarela' => $simpanan['sukarela'] ?? collect()])
                </div>
                <div id="paginationLinksAnggotaSukarela" class="mt-8">
                    @if(isset($simpanan['sukarela']) && $simpanan['sukarela']->hasPages())
                        {{ $simpanan['sukarela']->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Back Button -->
    <div class="flex justify-start">
        <a href="{{ route('anggota.dashboard') }}" class="group inline-flex items-center px-8 py-4 bg-white border-2 border-gray-200 rounded-2xl shadow-lg hover:shadow-xl hover:border-gray-300 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-offset-2 transform hover:-translate-y-1">
            <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-600 mr-4 transition-all duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="text-gray-600 group-hover:text-gray-800 font-semibold transition-colors duration-300">Kembali ke Dashboard</span>
        </a>
    </div>
</div>

<style>
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out;
}

/* Enhanced table styles */
.modern-table-container {
    @apply overflow-hidden rounded-2xl border border-gray-200 shadow-sm;
}

.modern-table {
    @apply w-full min-w-[600px];
}

.table-header {
    @apply bg-gradient-to-r from-gray-50 via-white to-gray-50;
}

.table-header th {
    @apply py-5 px-6 text-xs font-bold uppercase tracking-wider;
}

.table-row {
    @apply hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-300 group transform hover:scale-[1.01];
    animation: slideInUp 0.5s ease-out both;
}

.table-cell {
    @apply py-5 px-6;
}

.transaction-badge {
    @apply inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold shadow-sm;
}

.badge-setor {
    @apply bg-gradient-to-r from-green-100 to-emerald-100 text-green-800;
}

.badge-tarik {
    @apply bg-gradient-to-r from-red-100 to-pink-100 text-red-800;
}

.amount-positive {
    @apply text-green-600 font-bold;
}

.amount-negative {
    @apply text-red-600 font-bold;
}

.amount-neutral {
    @apply text-gray-700 font-medium;
}

.empty-state {
    @apply text-center py-20;
}

.empty-icon {
    @apply w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg;
}

.empty-title {
    @apply text-xl font-bold text-gray-700 mb-2;
}

.empty-description {
    @apply text-gray-500 text-base max-w-md mx-auto;
}
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function handleAnggotaAjaxPagination(containerId, paginationLinksId, tabName, pageParamName) {
            const paginationContainer = document.getElementById(paginationLinksId);
            if (!paginationContainer) return;

            paginationContainer.addEventListener('click', function(event) {
                const target = event.target.closest('a');
                if (target && target.href && !target.classList.contains('disabled') && !target.querySelector('span[aria-disabled="true"]')) {
                    event.preventDefault();
                    
                    const url = new URL(target.href);
                    
                    fetch(url.toString(), { 
                        headers: {'X-Requested-With': 'XMLHttpRequest'} 
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.html && data.pagination) {
                            document.getElementById(containerId).innerHTML = data.html;
                            document.getElementById(paginationLinksId).innerHTML = data.pagination;
                            window.history.pushState({path:url.toString()},'',url.toString());
                            
                            // Add smooth scroll to top of table with enhanced animation
                            document.getElementById(containerId).scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'start' 
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        }

        handleAnggotaAjaxPagination('riwayatAnggotaWajibContent', 'paginationLinksAnggotaWajib', 'wajib', 'page_wajib');
        handleAnggotaAjaxPagination('riwayatAnggotaSukarelaContent', 'paginationLinksAnggotaSukarela', 'sukarela', 'page_sukarela');
        
        // Enhanced tab activation based on URL parameters
        const currentUrlParamsAnggota = new URLSearchParams(window.location.search);
        const currentTabAnggota = currentUrlParamsAnggota.get('tab');
        if (currentTabAnggota && ['pokok', 'wajib', 'sukarela'].includes(currentTabAnggota)) {
            const alpineComponentAnggota = document.querySelector('[x-data]');
            if (alpineComponentAnggota && alpineComponentAnggota.__x) {
                alpineComponentAnggota.__x.$data.activeTabSimpanan = currentTabAnggota;
            } else {
                setTimeout(() => {
                    const alpineCompRetryAnggota = document.querySelector('[x-data]');
                    if(alpineCompRetryAnggota && alpineCompRetryAnggota.__x) {
                        alpineCompRetryAnggota.__x.$data.activeTabSimpanan = currentTabAnggota;
                    }
                }, 150);
            }
        }
    });
</script>
@endpush