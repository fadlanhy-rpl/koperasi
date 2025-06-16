@extends('layouts.app')

@section('title', 'Laporan Stok Barang Terkini - Koperasi')

@section('page-title', 'Laporan Stok Barang Terkini')
@section('page-subtitle', 'Pantau jumlah dan nilai stok barang koperasi')

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
        <form method="GET" action="{{ route('pengurus.laporan.stok.daftarTerkini') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="search_barang_stok" class="block text-sm font-medium text-gray-700 mb-1">Cari Barang:</label>
                    <input type="text" id="search_barang_stok" name="search_barang" value="{{ request('search_barang') }}" placeholder="Nama atau Kode Barang..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="unit_usaha_id_stok" class="block text-sm font-medium text-gray-700 mb-1">Unit Usaha:</label>
                    <select name="unit_usaha_id" id="unit_usaha_id_stok" class="w-full select2-filter-stok">
                        <option value="">Semua Unit Usaha</option>
                        @foreach($filters['unit_usahas'] as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_usaha_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit_usaha }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="stok_kurang_dari_filter" class="block text-sm font-medium text-gray-700 mb-1">Stok Kurang Dari (Opsional):</label>
                    <input type="number" id="stok_kurang_dari_filter" name="stok_kurang_dari" value="{{ request('stok_kurang_dari') }}" placeholder="Contoh: 10"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
            </div>
            <div class="flex justify-start items-end space-x-3 mt-4">
                <x-forms.button type="submit" variant="primary" size="md" icon="filter">
                    Terapkan Filter
                </x-forms.button>
                 <a href="{{ route('pengurus.laporan.stok.daftarTerkini') }}">
                    <x-forms.button type="button" variant="secondary" size="md" icon="undo">
                        Reset Filter
                    </x-forms.button>
                </a>
            </div>
        </form>
    </div>

    <!-- Ringkasan Nilai Stok -->
    <div class="mb-6 bg-gradient-to-br from-teal-500 to-cyan-600 p-6 rounded-2xl shadow-xl text-white">
        <p class="text-sm text-teal-100 mb-1">Estimasi Total Nilai Stok (Harga Beli)</p>
        <p class="text-3xl font-bold">@rupiah($total_nilai_stok_estimasi)</p>
        <p class="text-xs text-teal-200 mt-1">* Berdasarkan filter yang diterapkan (jika ada).</p>
    </div>

    <!-- Tabel Daftar Stok Terkini -->
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Daftar Stok Barang Terkini</h3>
            @if(request()->hasAny(['search_barang', 'unit_usaha_id', 'stok_kurang_dari']))
                <p class="text-sm text-gray-500">Menampilkan hasil filter.</p>
            @endif
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-xs uppercase text-gray-500">
                            <th class="py-2.5 px-3 text-left">No.</th>
                            <th class="py-2.5 px-3 text-left">Kode Barang</th>
                            <th class="py-2.5 px-3 text-left">Nama Barang</th>
                            <th class="py-2.5 px-3 text-left">Unit Usaha</th>
                            <th class="py-2.5 px-3 text-center">Stok Terkini</th>
                            <th class="py-2.5 px-3 text-right">Harga Beli</th>
                            <th class="py-2.5 px-3 text-right">Harga Jual</th>
                            <th class="py-2.5 px-3 text-right">Nilai Stok (H.Beli)</th>
                            <th class="py-2.5 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftar_stok as $index => $barang)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="py-2.5 px-3 text-gray-700">{{ $daftar_stok->firstItem() + $index }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $barang->kode_barang ?? '-' }}</td>
                                <td class="py-2.5 px-3 font-medium text-gray-800">{{ $barang->nama_barang }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}</td>
                                <td class="py-2.5 px-3 text-center font-semibold {{ $barang->stok <= 10 && $barang->stok > 0 ? 'text-red-600' : ($barang->stok == 0 ? 'text-gray-400' : 'text-green-600') }}">
                                    {{ $barang->stok }} <span class="text-xs text-gray-500">{{ $barang->satuan }}</span>
                                </td>
                                <td class="py-2.5 px-3 text-right text-gray-700">@rupiah($barang->harga_beli)</td>
                                <td class="py-2.5 px-3 text-right text-gray-700">@rupiah($barang->harga_jual)</td>
                                <td class="py-2.5 px-3 text-right font-semibold text-blue-600">@rupiah($barang->stok * $barang->harga_beli)</td>
                                <td class="py-2.5 px-3 text-center">
                                    <a href="{{ route('pengurus.laporan.stok.kartuStok', $barang->id) }}" class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-lg transition-all duration-300" title="Lihat Kartu Stok">
                                        <i class="fas fa-clipboard-list fa-fw"></i>
                                    </a>
                                    <a href="{{ route('pengurus.barang.show', $barang->id) }}" class="text-emerald-600 hover:text-emerald-800 p-1.5 hover:bg-emerald-50 rounded-lg transition-all duration-300" title="Lihat Detail Barang">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-10 text-gray-500">
                                     <div class="flex flex-col items-center">
                                        <i class="fas fa-boxes text-4xl mb-3 text-gray-300"></i>
                                        Tidak ada data stok barang ditemukan untuk filter ini.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($daftar_stok->hasPages())
                <div class="mt-6">
                    {{ $daftar_stok->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>
    <div class="mt-8 flex justify-start">
        <a href="{{ route('pengurus.dashboard') }}"> 
            <x-forms.button type="button" variant="secondary" icon="arrow-left">
                Kembali ke Dashboard Pengurus
            </x-forms.button>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.select2-filter-stok').select2({
            placeholder: "Pilih Unit Usaha",
            width: '100%',
            allowClear: true
        });
    });
</script>
@endpush