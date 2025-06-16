@extends('layouts.app')

@section('title', 'Daftar Pembayaran Cicilan - Koperasi')

@section('page-title', 'Pembayaran Cicilan Anggota')
@section('page-subtitle', 'Pilih transaksi pembelian untuk mencatat pembayaran angsuran')

@section('content')
<div class="animate-fade-in">
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <h3 class="text-xl font-bold text-gray-800">Daftar Transaksi dengan Cicilan/Belum Lunas</h3>
                <form method="GET" action="{{ route('pengurus.pembayaran-cicilan.index') }}" class="flex gap-2 w-full sm:w-auto sm:max-w-md">
                    <input type="text" name="search_pembelian_cicilan" value="{{ request('search_pembelian_cicilan') }}" placeholder="Cari kode trx/anggota..." 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                    <x-forms.button type="submit" variant="secondary" size="sm" class="py-2.5">
                        <i class="fas fa-search"></i>
                    </x-forms.button>
                </form>
            </div>
        </div>
        
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-xs uppercase text-gray-500">
                            <th class="py-2.5 px-3 text-left">Kode Transaksi</th>
                            <th class="py-2.5 px-3 text-left">Anggota</th>
                            <th class="py-2.5 px-3 text-right">Total Tagihan</th>
                            <th class="py-2.5 px-3 text-right">Dibayar Awal</th>
                            <th class="py-2.5 px-3 text-right font-semibold">Sisa Tagihan</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                            <th class="py-2.5 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembelians as $pembelian)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="py-2.5 px-3">
                                    <a href="{{ route('pengurus.transaksi-pembelian.show', $pembelian->id) }}" class="font-medium text-blue-600 hover:underline">
                                        {{ $pembelian->kode_pembelian }}
                                    </a>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('DD MMM YYYY') }}</div>
                                </td>
                                <td class="py-2.5 px-3 text-gray-700">{{ $pembelian->user->name ?? 'N/A' }} ({{ $pembelian->user->nomor_anggota ?? '-' }})</td>
                                <td class="py-2.5 px-3 text-right text-gray-700">@rupiah($pembelian->total_harga)</td>
                                <td class="py-2.5 px-3 text-right text-gray-700">@rupiah($pembelian->total_bayar)</td> {{-- Ini adalah DP/pembayaran awal --}}
                                <td class="py-2.5 px-3 text-right font-semibold {{ $pembelian->sisa_tagihan_aktual > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    @rupiah($pembelian->sisa_tagihan_aktual)
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                        @if($pembelian->status_pembayaran == 'lunas') bg-green-100 text-green-700
                                        @elseif($pembelian->status_pembayaran == 'cicilan') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($pembelian->status_pembayaran) }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    @if($pembelian->sisa_tagihan_aktual > 0)
                                    <a href="{{ route('pengurus.pembayaran-cicilan.create', $pembelian->id) }}" >
                                        <x-forms.button type="button" variant="success" size="sm" icon="plus-circle" class="text-xs">
                                            Bayar Cicilan
                                        </x-forms.button>
                                    </a>
                                    @else
                                     <span class="text-xs text-green-600 italic px-2 py-1 bg-green-50 rounded-md">Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-check-double text-4xl mb-3 text-green-400"></i>
                                        Tidak ada transaksi yang memerlukan pembayaran cicilan saat ini.
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
        <a href="{{ route('pengurus.dashboard') }}"> 
            <x-forms.button type="button" variant="secondary" icon="arrow-left">
                Kembali ke Dashboard
            </x-forms.button>
        </a>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Tidak ada JS spesifik untuk AJAX di sini, filter via GET standar --}}
@endpush