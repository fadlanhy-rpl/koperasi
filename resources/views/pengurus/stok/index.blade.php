{{-- resources/views/pengurus/stok/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Pencatatan Stok Barang - Koperasi')
@section('page-title', 'Pencatatan Stok Barang')
@section('page-subtitle', 'Kelola pergerakan masuk, keluar, dan penyesuaian stok')

@section('content')
<div class="animate-fade-in">
    <!-- Filter Section -->
    <div class="mb-6 bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
        <form method="GET" action="{{ route('pengurus.stok.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="search_stok_main" class="block text-sm font-medium text-gray-700 mb-1">Cari Barang:</label>
                    <input type="text" id="search_stok_main" name="search_stok" value="{{ request('search_stok') }}" placeholder="Nama atau Kode Barang..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="unit_usaha_stok_main" class="block text-sm font-medium text-gray-700 mb-1">Unit Usaha:</label>
                    <select name="unit_usaha_stok" id="unit_usaha_stok_main" class="w-full select2-basic"> {{-- Pakai class select2 jika sudah ada --}}
                        <option value="">Semua Unit Usaha</option>
                        @foreach($unitUsahas as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_usaha_stok') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit_usaha }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <x-forms.button type="submit" variant="primary" size="md" icon="filter" class="w-full sm:w-auto py-2.5">
                        Filter Barang
                    </x-forms.button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Barang untuk Aksi Stok -->
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Daftar Barang untuk Manajemen Stok</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-xs uppercase text-gray-500">
                            <th class="py-2.5 px-3 text-left">Nama Barang (Kode)</th>
                            <th class="py-2.5 px-3 text-left">Unit Usaha</th>
                            <th class="py-2.5 px-3 text-center">Stok Terkini</th>
                            <th class="py-2.5 px-3 text-center">Aksi Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $barang)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="py-2.5 px-3">
                                    <p class="font-medium text-gray-800">{{ $barang->nama_barang }}</p>
                                    <p class="text-xs text-gray-500">{{ $barang->kode_barang ?? '-' }}</p>
                                </td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}</td>
                                <td class="py-2.5 px-3 text-center font-semibold {{ $barang->stok <= 10 && $barang->stok > 0 ? 'text-red-600' : ($barang->stok == 0 ? 'text-gray-400' : 'text-green-600') }}">
                                    {{ $barang->stok }} <span class="text-xs text-gray-500">{{ $barang->satuan }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ route('pengurus.stok.formBarangMasuk', $barang->id) }}" class="text-green-600 hover:text-green-800 p-1.5 hover:bg-green-50 rounded-lg" title="Stok Masuk">
                                            <i class="fas fa-plus-circle fa-fw"></i>
                                        </a>
                                        <a href="{{ route('pengurus.stok.formBarangKeluar', $barang->id) }}" class="text-red-600 hover:text-red-800 p-1.5 hover:bg-red-50 rounded-lg" title="Stok Keluar">
                                            <i class="fas fa-minus-circle fa-fw"></i>
                                        </a>
                                        <a href="{{ route('pengurus.stok.formPenyesuaianStok', $barang->id) }}" class="text-yellow-600 hover:text-yellow-800 p-1.5 hover:bg-yellow-50 rounded-lg" title="Penyesuaian Stok">
                                            <i class="fas fa-exchange-alt fa-fw"></i>
                                        </a>
                                        <a href="{{ route('pengurus.laporan.stok.kartuStok', $barang->id) }}" class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-lg" title="Lihat Kartu Stok">
                                            <i class="fas fa-history fa-fw"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-gray-500">
                                    <p>Tidak ada barang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($barangs->hasPages())
                <div class="mt-6">
                    {{ $barangs->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Script untuk Select2 jika digunakan --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.select2-basic').select2({ width: '100%', placeholder: 'Pilih Unit Usaha'});
    });
</script> --}}
@endpush