@extends('layouts.app')

@section('title', 'Riwayat Pembelian Saya - Koperasi')

@section('page-title', 'Riwayat Pembelian Saya')
@section('page-subtitle', 'Daftar semua transaksi pembelian yang telah Anda lakukan')

@section('content')
<div class="animate-fade-in">
    <!-- Filter Section -->
    <div class="mb-6 bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
        <form method="GET" action="{{ route('anggota.pembelian.riwayat') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label for="search_kode_riwayat" class="block text-sm font-medium text-gray-700 mb-1">Cari Kode Transaksi:</label>
                    <input type="text" id="search_kode_riwayat" name="search_kode" value="{{ request('search_kode') }}" placeholder="Contoh: INV/..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="status_pembayaran_filter_riwayat" class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran:</label>
                    <select id="status_pembayaran_filter_riwayat" name="status_pembayaran_filter" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ request('status_pembayaran_filter', 'all') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tanggal_mulai_filter_riwayat" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal:</label>
                    <input type="date" id="tanggal_mulai_filter_riwayat" name="tanggal_mulai_filter" value="{{ request('tanggal_mulai_filter') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="tanggal_selesai_filter_riwayat" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal:</label>
                    <input type="date" id="tanggal_selesai_filter_riwayat" name="tanggal_selesai_filter" value="{{ request('tanggal_selesai_filter') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
            </div>
             <div class="flex justify-start items-end space-x-3 mt-4">
                <x-forms.button type="submit" variant="primary" size="md" icon="filter">
                    Tampilkan Riwayat
                </x-forms.button>
                 <a href="{{ route('anggota.pembelian.riwayat') }}">
                    <x-forms.button type="button" variant="secondary" size="md" icon="undo">
                        Reset Filter
                    </x-forms.button>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Riwayat Pembelian -->
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Daftar Transaksi Pembelian Anda</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-xs uppercase text-gray-500">
                            <th class="py-2.5 px-3 text-left">Kode Transaksi</th>
                            <th class="py-2.5 px-3 text-left">Tanggal</th>
                            <th class="py-2.5 px-3 text-center">Jumlah Item</th>
                            <th class="py-2.5 px-3 text-right">Total Belanja</th>
                            <th class="py-2.5 px-3 text-center">Status Pembayaran</th>
                            <th class="py-2.5 px-3 text-left">Metode Bayar</th>
                            <th class="py-2.5 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembelians as $pembelian)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="py-2.5 px-3 font-medium text-blue-600 hover:underline">
                                    <a href="{{ route('anggota.pembelian.detail', $pembelian->id) }}">{{ $pembelian->kode_pembelian }}</a>
                                </td>
                                <td class="py-2.5 px-3 text-gray-700">{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('DD MMMM YYYY, HH:mm') }}</td>
                                <td class="py-2.5 px-3 text-center text-gray-700">{{ $pembelian->jumlah_item }} item</td>
                                <td class="py-2.5 px-3 text-right font-semibold text-gray-800">@rupiah($pembelian->total_harga)</td>
                                <td class="py-2.5 px-3 text-center">
                                     <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                        @if($pembelian->status_pembayaran == 'lunas') bg-green-100 text-green-700
                                        @elseif($pembelian->status_pembayaran == 'cicilan') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($pembelian->status_pembayaran) }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-gray-600">{{ ucfirst(str_replace('_', ' ', $pembelian->metode_pembayaran)) }}</td>
                                <td class="py-2.5 px-3 text-center">
                                    <a href="{{ route('anggota.pembelian.detail', $pembelian->id) }}" class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-lg transition-all duration-300" title="Lihat Detail Transaksi">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500">
                                     <div class="flex flex-col items-center">
                                        <i class="fas fa-shopping-bag text-4xl mb-3 text-gray-300"></i>
                                        Anda belum memiliki riwayat pembelian.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($pembelians->hasPages())
                <div class="mt-6">
                    {{ $pembelians->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>
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
{{-- Tidak ada JS spesifik untuk halaman ini, filter via GET request biasa --}}
@endpush