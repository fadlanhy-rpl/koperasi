@extends('layouts.app')

@section('title', 'Katalog Barang Koperasi - Koperasi')

@section('page-title', 'Katalog Barang Koperasi')
@section('page-subtitle', 'Lihat barang-barang yang tersedia di unit usaha kami')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{-- Style Select2 --}}
<style>
    .select2-container .select2-selection--single { height: 42px !important; border-radius: 0.75rem !important; border: 1px solid #D1D5DB !important; padding-top: 0.45rem !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; }
</style>
@endpush

@section('content')
<div class="animate-fade-in">
    <!-- Filter Section -->
    <div class="mb-6 bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
        <form method="GET" action="{{ route('anggota.pembelian.katalog') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="search_barang_katalog_anggota" class="block text-sm font-medium text-gray-700 mb-1">Cari Barang:</label>
                    <input type="text" id="search_barang_katalog_anggota" name="search_barang_katalog" value="{{ request('search_barang_katalog') }}" placeholder="Nama atau Kode Barang..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="unit_usaha_katalog_anggota" class="block text-sm font-medium text-gray-700 mb-1">Unit Usaha:</label>
                    <select name="unit_usaha_katalog" id="unit_usaha_katalog_anggota" class="w-full select2-katalog">
                        <option value="">Semua Unit Usaha</option>
                        @foreach($unitUsahas as $unit) {{-- Variabel $unitUsahas dari controller --}}
                            <option value="{{ $unit->id }}" {{ request('unit_usaha_katalog') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit_usaha }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <x-forms.button type="submit" variant="primary" size="md" icon="filter" class="w-full sm:w-auto py-2.5">
                        Cari Barang
                    </x-forms.button>
                </div>
            </div>
        </form>
    </div>

    <!-- Daftar Barang Katalog -->
    @if($barangs->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($barangs as $barang)
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 overflow-hidden flex flex-col group transform hover:scale-105 transition-transform duration-300">
                {{-- Gambar Barang (Placeholder) --}}
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-4xl"></i> 
                    {{-- Anda bisa mengganti ini dengan <img src="{{ $barang->foto_url ?? asset('img/placeholder_barang.png') }}"> jika ada foto --}}
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="text-lg font-semibold text-gray-800 mb-1 truncate group-hover:text-blue-600 transition-colors">{{ $barang->nama_barang }}</h3>
                    <p class="text-xs text-gray-500 mb-2">{{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}</p>
                    
                    <div class="mt-auto">
                        <p class="text-xl font-bold text-blue-600 mb-2">@rupiah($barang->harga_jual)</p>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-600">Stok: <span class="font-medium {{ $barang->stok > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $barang->stok > 0 ? $barang->stok . ' ' . $barang->satuan : 'Habis' }}</span></span>
                            @if($barang->kode_barang)
                            <span class="text-gray-400">#{{ $barang->kode_barang }}</span>
                            @endif
                        </div>
                    </div>
                     {{-- Tombol Beli (Untuk masa depan jika ada keranjang belanja online) --}}
                    {{-- <div class="mt-4 pt-4 border-t border-gray-100">
                        <x-forms.button type="button" variant="primary" size="sm" icon="cart-plus" class="w-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            Tambah ke Keranjang
                        </x-forms.button>
                    </div> --}}
                </div>
            </div>
            @endforeach
        </div>
        @if($barangs->hasPages())
            <div class="mt-8">
                {{ $barangs->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    @else
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-10 text-center">
            <div class="flex flex-col items-center text-gray-500">
                <i class="fas fa-store-slash text-5xl mb-4 text-gray-300"></i>
                <h3 class="text-xl font-semibold mb-2">Oops! Barang Tidak Ditemukan</h3>
                <p>Tidak ada barang yang sesuai dengan filter pencarian Anda saat ini.</p>
                <a href="{{ route('anggota.pembelian.katalog') }}" class="mt-6">
                    <x-forms.button type="button" variant="secondary" icon="undo">
                        Lihat Semua Barang
                    </x-forms.button>
                </a>
            </div>
        </div>
    @endif
    <div class="mt-8 flex justify-start">
        <a href="{{ route('anggota.dashboard') }}"> 
            <x-forms.button type="button" variant="secondary" icon="arrow-left">
                Kembali ke Dashboard
            </x-forms.button>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.select2-katalog').select2({
            placeholder: "Pilih Unit Usaha",
            width: '100%',
            allowClear: true
        });
    });
</script>
@endpush