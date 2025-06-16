@extends('layouts.app')

@section('title', 'Laporan Laba Rugi Penjualan - Koperasi')

@section('page-title', 'Laporan Estimasi Laba Rugi')
@section('page-subtitle', 'Analisis profitabilitas penjualan barang koperasi')

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
        <form method="GET" action="{{ route('pengurus.laporan.penjualan.labaRugi') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label for="tanggal_mulai_lr" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal:</label>
                    <input type="date" id="tanggal_mulai_lr" name="tanggal_mulai" value="{{ request('tanggal_mulai', $tanggalMulai) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="tanggal_selesai_lr" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal:</label>
                    <input type="date" id="tanggal_selesai_lr" name="tanggal_selesai" value="{{ request('tanggal_selesai', $tanggalSelesai) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="unit_usaha_id_lr" class="block text-sm font-medium text-gray-700 mb-1">Unit Usaha:</label>
                    <select name="unit_usaha_id" id="unit_usaha_id_lr" class="w-full select2-filter-lr">
                        <option value="">Semua Unit Usaha</option>
                        @foreach($filters['unit_usahas'] as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_usaha_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit_usaha }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="barang_id_lr" class="block text-sm font-medium text-gray-700 mb-1">Barang (Opsional):</label>
                    <select name="barang_id" id="barang_id_lr" class="w-full select2-filter-lr">
                        <option value="">Semua Barang</option>
                        @foreach($filters['barangs'] as $barang)
                            <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>{{ $barang->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
             <div class="flex justify-start items-end space-x-3 mt-4">
                <x-forms.button type="submit" variant="primary" size="md" icon="filter">
                    Terapkan Filter
                </x-forms.button>
                 <a href="{{ route('pengurus.laporan.penjualan.labaRugi') }}">
                    <x-forms.button type="button" variant="secondary" size="md" icon="undo">
                        Reset Filter
                    </x-forms.button>
                </a>
            </div>
        </form>
    </div>

    <!-- Ringkasan Laba Rugi -->
    <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-sky-500 to-cyan-500 p-6 rounded-2xl shadow-xl text-white">
            <p class="text-sm text-sky-100 mb-1">Total Pendapatan</p>
            <p class="text-3xl font-bold">@rupiah($totalPendapatanKeseluruhan)</p>
        </div>
        <div class="bg-gradient-to-br from-rose-500 to-pink-500 p-6 rounded-2xl shadow-xl text-white">
            <p class="text-sm text-rose-100 mb-1">Total Estimasi HPP</p>
            <p class="text-3xl font-bold">@rupiah($totalHppEstimasiKeseluruhan)</p>
        </div>
        <div class="bg-gradient-to-br from-lime-500 to-emerald-500 p-6 rounded-2xl shadow-xl text-white">
            <p class="text-sm text-lime-100 mb-1">Total Estimasi Laba Kotor</p>
            <p class="text-3xl font-bold">@rupiah($totalEstimasiLabaKotorKeseluruhan)</p>
        </div>
    </div>

    <!-- Tabel Laporan Laba Rugi per Barang -->
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Rincian Estimasi Laba Rugi per Barang</h3>
            <p class="text-sm text-gray-500">Periode: <span class="font-semibold">{{ \Carbon\Carbon::parse($tanggalMulai)->isoFormat('DD MMMM YYYY') }}</span> - <span class="font-semibold">{{ \Carbon\Carbon::parse($tanggalSelesai)->isoFormat('DD MMMM YYYY') }}</span></p>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-xs uppercase text-gray-500">
                            <th class="py-2.5 px-3 text-left">Kode Barang</th>
                            <th class="py-2.5 px-3 text-left">Nama Barang</th>
                            <th class="py-2.5 px-3 text-center">Total Terjual</th>
                            <th class="py-2.5 px-3 text-right">Total Pendapatan</th>
                            <th class="py-2.5 px-3 text-right">Total Estimasi HPP</th>
                            <th class="py-2.5 px-3 text-right">Estimasi Laba Kotor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanLabaRugiItems as $item)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="py-2.5 px-3 text-gray-600">{{ $item->kode_barang ?? '-' }}</td>
                                <td class="py-2.5 px-3 font-medium text-gray-800">{{ $item->nama_barang }}</td>
                                <td class="py-2.5 px-3 text-center text-gray-700">{{ number_format($item->total_terjual, 0, ',', '.') }} <span class="text-xs text-gray-500">{{ $item->satuan ?? '' }}</span></td>
                                <td class="py-2.5 px-3 text-right text-gray-700">@rupiah($item->total_pendapatan)</td>
                                <td class="py-2.5 px-3 text-right text-gray-700">@rupiah($item->total_hpp_estimasi)</td>
                                <td class="py-2.5 px-3 text-right font-semibold {{ $item->estimasi_laba_kotor >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    @rupiah($item->estimasi_laba_kotor)
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500">
                                     <div class="flex flex-col items-center">
                                        <i class="fas fa-calculator text-4xl mb-3 text-gray-300"></i>
                                        Tidak ada data laba rugi ditemukan untuk filter ini.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             @if($laporanLabaRugiItems->hasPages())
                <div class="mt-6">
                    {{ $laporanLabaRugiItems->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.select2-filter-lr').select2({
            placeholder: "Pilih Opsi",
            width: '100%',
            allowClear: true
        });
    });
</script>
@endpush