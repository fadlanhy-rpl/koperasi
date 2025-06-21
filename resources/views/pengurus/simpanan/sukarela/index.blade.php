@extends('layouts.app')

@section('title', 'Manajemen Simpanan Sukarela - Koperasi')
@section('page-title', 'Simpanan Sukarela Anggota')
@section('page-subtitle', 'Kelola setoran dan penarikan simpanan sukarela')

@push('styles')
<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .slide-in {
        animation: slideIn 0.5s ease-out;
    }
    @keyframes slideIn {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    @keyframes fadeInUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .gradient-border-blue {
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(45deg, #3b82f6, #1d4ed8, #1e40af) border-box;
        border: 2px solid transparent;
    }
    .gradient-border-red {
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(45deg, #ef4444, #dc2626, #b91c1c) border-box;
        border: 2px solid transparent;
    }
    
    /* Enhanced Select2 styling */
    .member-select .select2-container--default .select2-selection--single {
        height: 52px !important;
        border: 2px solid #d1d5db !important;
        border-radius: 12px !important;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    }
    
    .member-select .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1f2937 !important;
        font-weight: 600 !important;
        font-size: 15px !important;
        line-height: 48px !important;
        padding-left: 16px !important;
    }
    
    .member-select-deposit .select2-dropdown {
        border: 2px solid #3b82f6 !important;
        border-radius: 12px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    }
    
    .member-select-withdraw .select2-dropdown {
        border: 2px solid #ef4444 !important;
        border-radius: 12px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    }
    
    .member-select-deposit .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
        color: white !important;
    }
    
    .member-select-withdraw .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
    }
</style>
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Anggota</p>
                <p class="text-3xl font-bold">{{ $anggotas->total() }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Total Saldo</p>
                <p class="text-2xl font-bold">@rupiah($anggotas->sum('saldo_sukarela_terkini'))</p>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-wallet text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-6 text-white card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Rata-rata Saldo</p>
                <p class="text-xl font-bold">@rupiah($anggotas->count() > 0 ? $anggotas->sum('saldo_sukarela_terkini') / $anggotas->count() : 0)</p>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl p-6 text-white card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Aktif Menabung</p>
                <p class="text-3xl font-bold">{{ $anggotas->where('saldo_sukarela_terkini', '>', 0)->count() }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-piggy-bank text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Enhanced Form Transaksi Simpanan Sukarela -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Form Setoran -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 card-hover gradient-border-blue slide-in">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-3xl">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-500 p-2 rounded-xl">
                        <i class="fas fa-plus-circle text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Catat Setoran Sukarela</h3>
                        <p class="text-sm text-gray-600">Tambahkan setoran simpanan sukarela</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <form action="{{ route('pengurus.simpanan.sukarela.storeSetoran') }}" method="POST" class="space-y-6" data-validate id="setoranForm">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-user-plus text-blue-500 mr-2"></i>Pilih Anggota
                        </label>
                        <div class="member-select member-select-deposit">
                            <select name="user_id" id="memberSelectSetoran" class="w-full" required>
                                <option value="">Pilih anggota untuk setoran...</option>
                                @foreach($semuaAnggota as $anggota)
                                    <option value="{{ $anggota->id }}" {{ old('user_id') == $anggota->id ? 'selected' : '' }}>
                                        {{ $anggota->name }} - {{ $anggota->nomor_anggota ?? 'No ID' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('user_id')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>Jumlah Setoran
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="text" name="jumlah" id="jumlahSetoranInput" 
                                   placeholder="0" 
                                   value="{{ old('jumlah') }}"
                                   required 
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 placeholder-gray-400 font-medium text-gray-800"
                                   oninput="formatCurrency(this)">
                        </div>
                        @error('jumlah')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>Tanggal Setor
                        </label>
                        <input type="date" name="tanggal_transaksi" 
                               value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" 
                               required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 font-medium text-gray-800">
                        @error('tanggal_transaksi')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="keterangan_setor_sukarela" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sticky-note text-yellow-500 mr-2"></i>Keterangan (Opsional)
                        </label>
                        <textarea id="keterangan_setor_sukarela" name="keterangan" rows="2" 
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 placeholder-gray-400 resize-none" 
                                  placeholder="Catatan setoran...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <button type="submit" id="submitSetoranBtn" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-2">
                            <i class="fas fa-plus-circle"></i>
                            <span>Simpan Setoran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Penarikan -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 card-hover gradient-border-red slide-in">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-red-50 to-pink-50 rounded-t-3xl">
                <div class="flex items-center space-x-3">
                    <div class="bg-red-500 p-2 rounded-xl">
                        <i class="fas fa-minus-circle text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Catat Penarikan Sukarela</h3>
                        <p class="text-sm text-gray-600">Proses penarikan simpanan sukarela</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <form action="{{ route('pengurus.simpanan.sukarela.storePenarikan') }}" method="POST" class="space-y-6" data-validate id="penarikanForm">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-user-minus text-red-500 mr-2"></i>Pilih Anggota
                        </label>
                        <div class="member-select member-select-withdraw">
                            <select name="user_id" id="memberSelectPenarikan" class="w-full" required>
                                <option value="">Pilih anggota untuk penarikan...</option>
                                @foreach($semuaAnggota as $anggota)
                                    <option value="{{ $anggota->id }}" data-saldo="{{ $anggota->saldo_sukarela_terkini ?? 0 }}" {{ old('user_id') == $anggota->id ? 'selected' : '' }}>
                                        {{ $anggota->name }} - Saldo: @rupiah($anggota->saldo_sukarela_terkini ?? 0)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="saldoInfo" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <p class="text-sm text-blue-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                Saldo tersedia: <span id="saldoAmount" class="font-bold">Rp 0</span>
                            </p>
                        </div>
                        @error('user_id')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave text-red-500 mr-2"></i>Jumlah Penarikan
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="text" name="jumlah" id="jumlahPenarikanInput" 
                                   placeholder="0" 
                                   value="{{ old('jumlah') }}"
                                   required 
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300 placeholder-gray-400 font-medium text-gray-800"
                                   oninput="formatCurrency(this); validatePenarikan()">
                        </div>
                        <p class="text-xs text-gray-500 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Pastikan saldo anggota mencukupi
                        </p>
                        @error('jumlah')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>Tanggal Tarik
                        </label>
                        <input type="date" name="tanggal_transaksi" 
                               value="{{ old('tanggal_transaksi', date('Y-m-d')) }}" 
                               required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300 font-medium text-gray-800">
                        @error('tanggal_transaksi')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="keterangan_tarik_sukarela" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sticky-note text-yellow-500 mr-2"></i>Keterangan (Opsional)
                        </label>
                        <textarea id="keterangan_tarik_sukarela" name="keterangan" rows="2" 
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300 placeholder-gray-400 resize-none" 
                                  placeholder="Catatan penarikan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <button type="submit" id="submitPenarikanBtn" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-2">
                            <i class="fas fa-minus-circle"></i>
                            <span>Simpan Penarikan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Enhanced Daftar Anggota & Saldo Simpanan Sukarela -->
    <div class="lg:col-span-2">
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 card-hover fade-in-up">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-t-3xl">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-indigo-500 p-2 rounded-xl">
                            <i class="fas fa-piggy-bank text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Saldo Simpanan Sukarela</h3>
                            <p class="text-sm text-gray-600">Pantau saldo terkini anggota</p>
                        </div>
                    </div>
                    
                    <!-- Enhanced Search -->
                    <form method="GET" action="{{ route('pengurus.simpanan.sukarela.index') }}" class="flex gap-3 w-full sm:w-auto">
                        <input type="text" name="search_anggota" value="{{ request('search_anggota') }}" 
                               placeholder="🔍 Cari nama anggota..." 
                               class="w-full sm:w-64 px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all duration-300 text-gray-700">
                        
                        <button type="submit" class="bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white px-6 py-2.5 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full min-w-[600px]">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <th class="text-left py-4 px-6 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                    <i class="fas fa-user mr-2 text-indigo-500"></i>Nama Anggota
                                </th>
                                <th class="text-left py-4 px-6 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                    <i class="fas fa-id-card mr-2 text-blue-500"></i>No. Anggota
                                </th>
                                <th class="text-right py-4 px-6 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                    <i class="fas fa-wallet mr-2 text-green-500"></i>Saldo Terkini
                                </th>
                                <th class="text-center py-4 px-6 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                    <i class="fas fa-cogs mr-2 text-red-500"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($anggotas as $anggota)
                                <tr class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-purple-50 transition-all duration-300 group">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="bg-gradient-to-r from-indigo-400 to-indigo-500 w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($anggota->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors">{{ $anggota->name }}</p>
                                                <p class="text-xs text-gray-500">Anggota Koperasi</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="bg-gray-100 px-3 py-1 rounded-full text-sm font-medium text-gray-700">
                                            {{ $anggota->nomor_anggota ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex flex-col items-end">
                                            <span class="text-lg font-bold text-gray-800">@rupiah($anggota->saldo_sukarela_terkini ?? 0)</span>
                                            @if(($anggota->saldo_sukarela_terkini ?? 0) > 0)
                                                <span class="text-xs text-green-600 font-medium">Aktif</span>
                                            @else
                                                <span class="text-xs text-gray-400 font-medium">Kosong</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('pengurus.simpanan.riwayatAnggota', $anggota->id) }}?tab=sukarela" 
                                           class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-300" 
                                           title="Lihat Riwayat Simpanan">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12">
                                        <div class="flex flex-col items-center space-y-4">
                                            <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center">
                                                <i class="fas fa-users-slash text-3xl text-gray-400"></i>
                                            </div>
                                            <div>
                                                <p class="text-lg font-semibold text-gray-600">Tidak ada data anggota</p>
                                                <p class="text-sm text-gray-500">Belum ada anggota yang terdaftar</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($anggotas->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $anggotas->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="mt-6 bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 card-hover fade-in-up">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-slate-50 rounded-t-3xl">
                <div class="flex items-center space-x-3">
                    <div class="bg-gray-700 p-2 rounded-xl">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Transaksi Terbaru</h3>
                        <p class="text-sm text-gray-600">5 transaksi simpanan sukarela terakhir</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full min-w-[600px]">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Tanggal</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Anggota</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Jenis</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                // Get recent transactions from database
                                $recentTransactions = \App\Models\SimpananSukarela::with('user')
                                    ->orderBy('tanggal_transaksi', 'desc')
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp
                            
                            @forelse($recentTransactions as $transaksi)
                                <tr class="hover:bg-gray-50 transition-colors duration-300">
                                    <td class="py-3 px-4 text-gray-600">
                                        {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}
                                    </td>
                                    <td class="py-3 px-4 font-medium text-gray-800">
                                        {{ $transaksi->user->name ?? 'Anggota' }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @if($transaksi->tipe_transaksi == 'setor')
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                                <i class="fas fa-arrow-up mr-1"></i>Setoran
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                <i class="fas fa-arrow-down mr-1"></i>Penarikan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right font-semibold">
                                        @if($transaksi->tipe_transaksi == 'setor')
                                            <span class="text-green-600">+Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-red-600">-Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-gray-500">
                                        Belum ada transaksi simpanan sukarela
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 for member selection - Setoran
        $('#memberSelectSetoran').select2({
            placeholder: 'Ketik untuk mencari anggota...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Tidak ada anggota yang ditemukan";
                },
                searching: function() {
                    return "Mencari anggota...";
                }
            },
            templateResult: function(option) {
                if (!option.id) {
                    return option.text;
                }
                
                var $option = $(
                    '<div class="flex items-center space-x-3">' +
                        '<div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">' +
                            option.text.charAt(0).toUpperCase() +
                        '</div>' +
                        '<div>' +
                            '<div class="font-semibold text-gray-800">' + option.text.split(' - ')[0] + '</div>' +
                            '<div class="text-xs text-gray-500">ID: ' + (option.text.split(' - ')[1] || 'Tidak ada') + '</div>' +
                        '</div>' +
                    '</div>'
                );
                return $option;
            }
        });
        
        // Initialize Select2 for member selection - Penarikan
        $('#memberSelectPenarikan').select2({
            placeholder: 'Ketik untuk mencari anggota...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Tidak ada anggota yang ditemukan";
                },
                searching: function() {
                    return "Mencari anggota...";
                }
            },
            templateResult: function(option) {
                if (!option.id) {
                    return option.text;
                }
                
                // Extract name and saldo
                var parts = option.text.split(' - Saldo: ');
                var name = parts[0];
                var saldo = parts[1] || 'Rp 0';
                
                var $option = $(
                    '<div class="flex items-center space-x-3">' +
                        '<div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold">' +
                            name.charAt(0).toUpperCase() +
                        '</div>' +
                        '<div>' +
                            '<div class="font-semibold text-gray-800">' + name + '</div>' +
                            '<div class="text-xs text-green-600 font-medium">Saldo: ' + saldo + '</div>' +
                        '</div>' +
                    '</div>'
                );
                return $option;
            },
            templateSelection: function(option) {
                if (!option.id) {
                    return option.text;
                }
                return option.text.split(' - Saldo: ')[0];
            }
        }).on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const saldo = selectedOption.data('saldo') || 0;
            
            if (saldo > 0) {
                $('#saldoInfo').removeClass('hidden');
                $('#saldoAmount').text('Rp ' + saldo.toLocaleString('id-ID'));
            } else {
                $('#saldoInfo').addClass('hidden');
            }
        });

        // Currency formatting function
        window.formatCurrency = function(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value) {
                let formatted = parseInt(value).toLocaleString('id-ID');
                input.value = formatted;
            }
        };
        
        // Validate penarikan amount against available balance
        window.validatePenarikan = function() {
            const selectedOption = $('#memberSelectPenarikan').find('option:selected');
            const saldo = selectedOption.data('saldo') || 0;
            const penarikanInput = $('#jumlahPenarikanInput');
            const penarikanValue = parseInt(penarikanInput.val().replace(/[^\d]/g, '') || 0);
            
            if (penarikanValue > saldo) {
                penarikanInput.addClass('border-red-500 bg-red-50');
                $('#submitPenarikanBtn').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                
                // Show warning if not already shown
                if (!$('#saldoWarning').length) {
                    $('#saldoInfo').after('<div id="saldoWarning" class="mt-2 bg-red-50 border border-red-200 rounded-lg p-3"><p class="text-sm text-red-700"><i class="fas fa-exclamation-triangle mr-1"></i>Jumlah penarikan melebihi saldo tersedia!</p></div>');
                }
            } else {
                penarikanInput.removeClass('border-red-500 bg-red-50');
                $('#submitPenarikanBtn').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                $('#saldoWarning').remove();
            }
        };
        
        // Form submission with enhanced loading state - Setoran
        $('#setoranForm').on('submit', function(e) {
            const submitBtn = $('#submitSetoranBtn');
            const originalHtml = submitBtn.html();
            
            // Validate required fields
            const memberId = $('#memberSelectSetoran').val();
            const jumlah = $('#jumlahSetoranInput').val();
            
            if (!memberId) {
                e.preventDefault();
                showNotification('Silakan pilih anggota terlebih dahulu!', 'error');
                return false;
            }
            
            if (!jumlah || jumlah.trim() === '') {
                e.preventDefault();
                showNotification('Silakan masukkan jumlah setoran!', 'error');
                $('#jumlahSetoranInput').focus();
                return false;
            }
            
            // Show loading state
            submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
            submitBtn.prop('disabled', true);
            
            // Convert formatted currency back to number for submission
            const cleanValue = jumlah.replace(/[^\d]/g, '');
            $('#jumlahSetoranInput').val(cleanValue);
        });
        
        // Form submission with enhanced loading state - Penarikan
        $('#penarikanForm').on('submit', function(e) {
            const submitBtn = $('#submitPenarikanBtn');
            const originalHtml = submitBtn.html();
            
            // Validate required fields
            const memberId = $('#memberSelectPenarikan').val();
            const jumlah = $('#jumlahPenarikanInput').val();
            
            if (!memberId) {
                e.preventDefault();
                showNotification('Silakan pilih anggota terlebih dahulu!', 'error');
                return false;
            }
            
            if (!jumlah || jumlah.trim() === '') {
                e.preventDefault();
                showNotification('Silakan masukkan jumlah penarikan!', 'error');
                $('#jumlahPenarikanInput').focus();
                return false;
            }
            
            // Validate against available balance
            const selectedOption = $('#memberSelectPenarikan').find('option:selected');
            const saldo = selectedOption.data('saldo') || 0;
            const penarikanValue = parseInt(jumlah.replace(/[^\d]/g, '') || 0);
            
            if (penarikanValue > saldo) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Saldo Tidak Cukup',
                    text: 'Jumlah penarikan melebihi saldo tersedia!',
                    confirmButtonColor: '#ef4444'
                });
                return false;
            }
            
            // Show loading state
            submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
            submitBtn.prop('disabled', true);
            
            // Convert formatted currency back to number for submission
            const cleanValue = jumlah.replace(/[^\d]/g, '');
            $('#jumlahPenarikanInput').val(cleanValue);
        });
        
        // Initialize currency formatting on page load
        const jumlahSetoranInput = $('#jumlahSetoranInput');
        if (jumlahSetoranInput.val()) {
            formatCurrency(jumlahSetoranInput[0]);
        }
        
        const jumlahPenarikanInput = $('#jumlahPenarikanInput');
        if (jumlahPenarikanInput.val()) {
            formatCurrency(jumlahPenarikanInput[0]);
        }
    });
    
    // Success/Error notifications
    @if(session('success'))
        $(document).ready(function() {
            showNotification('{{ session('success') }}', 'success');
        });
    @endif
    
    @if(session('error'))
        $(document).ready(function() {
            showNotification('{{ session('error') }}', 'error');
        });
    @endif
    
    @if($errors->any())
        $(document).ready(function() {
            let errorMessages = '';
            @foreach($errors->all() as $error)
                errorMessages += '<p class="mb-1">• {{ $error }}</p>';
            @endforeach
            
            Swal.fire({
                icon: 'error',
                title: 'Terdapat Kesalahan!',
                html: errorMessages,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Perbaiki'
            });
        });
    @endif
</script>
@endpush