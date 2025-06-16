@extends('layouts.app')

@section('title', 'Detail Transaksi: ' . $pembelian->kode_pembelian)

@section('page-title', 'Detail Transaksi Pembelian')
@section('page-subtitle', 'Informasi lengkap untuk transaksi #' . $pembelian->kode_pembelian)

@section('content')
<div class="animate-fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Info Transaksi & Pembayaran -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-3">Informasi Transaksi</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kode Transaksi:</span>
                        <span class="font-semibold text-gray-800">{{ $pembelian->kode_pembelian }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal:</span>
                        <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('DD MMMM YYYY, HH:mm') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Anggota:</span>
                        <span class="font-semibold text-gray-800">{{ $pembelian->user->name ?? 'N/A' }} ({{ $pembelian->user->nomor_anggota ?? '-' }})</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kasir:</span>
                        <span class="font-semibold text-gray-800">{{ $pembelian->kasir->name ?? 'Sistem' }}</span>
                    </div>
                    @if($pembelian->catatan)
                    <div class="pt-2">
                        <span class="text-gray-600 block mb-1">Catatan:</span>
                        <p class="text-gray-700 bg-gray-50 p-2 rounded-md">{{ $pembelian->catatan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-3">Informasi Pembayaran</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Belanja:</span>
                        <span class="font-semibold text-gray-800">@rupiah($pembelian->total_harga)</span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-600">Metode Bayar:</span>
                        <span class="font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $pembelian->metode_pembayaran)) }}</span>
                    </div>
                    @if($pembelian->metode_pembayaran == 'tunai' || $pembelian->total_bayar > 0 && $pembelian->status_pembayaran != 'cicilan')
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dibayar Saat Transaksi:</span>
                        <span class="font-semibold text-gray-800">@rupiah($pembelian->total_bayar)</span>
                    </div>
                    @endif
                    @if($pembelian->metode_pembayaran == 'tunai' && $pembelian->kembalian > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kembalian:</span>
                        <span class="font-semibold text-gray-800">@rupiah($pembelian->kembalian)</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t mt-2">
                        <span class="text-gray-600 font-medium">Status Pembayaran:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($pembelian->status_pembayaran == 'lunas') bg-green-100 text-green-700
                            @elseif($pembelian->status_pembayaran == 'cicilan') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst($pembelian->status_pembayaran) }}
                        </span>
                    </div>
                    @if($pembelian->status_pembayaran !== 'lunas')
                    <div class="flex justify-between text-red-600">
                        <span class="font-medium">Sisa Tagihan:</span>
                        <span class="font-bold">@rupiah($sisaTagihan)</span>
                    </div>
                    @endif
                </div>
                 @if($pembelian->status_pembayaran === 'cicilan' && $sisaTagihan > 0)
                    <div class="mt-6">
                        <a href="{{ route('pengurus.pembayaran-cicilan.create', $pembelian->id) }}">
                            <x-forms.button type="button" variant="success" icon="plus-circle" class="w-full">
                                Catat Pembayaran Cicilan
                            </x-forms.button>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Detail Barang & Riwayat Cicilan -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-3">Detail Barang Dibeli</h3>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[500px] text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-gray-50 text-xs uppercase text-gray-500">
                                <th class="py-2 px-3 text-left">Nama Barang</th>
                                <th class="py-2 px-3 text-center">Jumlah</th>
                                <th class="py-2 px-3 text-right">Harga Satuan</th>
                                <th class="py-2 px-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pembelian->detailPembelians as $detail)
                            <tr class="border-b border-gray-100">
                                <td class="py-2.5 px-3">
                                    <p class="font-medium text-gray-800">{{ $detail->barang->nama_barang ?? 'Barang Dihapus' }}</p>
                                    <p class="text-xs text-gray-500">{{ $detail->barang->kode_barang ?? '-' }}</p>
                                </td>
                                <td class="py-2.5 px-3 text-center text-gray-600">{{ $detail->jumlah }}</td>
                                <td class="py-2.5 px-3 text-right text-gray-600">@rupiah($detail->harga_satuan)</td>
                                <td class="py-2.5 px-3 text-right font-semibold text-gray-800">@rupiah($detail->subtotal)</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-400 italic">Tidak ada detail barang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-gray-200">
                                <td colspan="3" class="py-3 px-3 text-right font-bold text-gray-800 text-base">TOTAL KESELURUHAN</td>
                                <td class="py-3 px-3 text-right font-bold text-blue-600 text-base">@rupiah($pembelian->total_harga)</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($pembelian->status_pembayaran === 'cicilan' || $pembelian->cicilans->isNotEmpty())
            <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-3">Riwayat Pembayaran Cicilan</h3>
                @if($pembelian->cicilans->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[500px] text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-gray-50 text-xs uppercase text-gray-500">
                                <th class="py-2 px-3 text-left">Tanggal Bayar</th>
                                <th class="py-2 px-3 text-right">Jumlah Bayar</th>
                                <th class="text-left py-2 px-3">Keterangan</th>
                                <th class="text-left py-2 px-3">Dicatat Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembelian->cicilans->sortByDesc('tanggal_bayar') as $cicilan)
                            <tr class="border-b border-gray-100">
                                <td class="py-2.5 px-3 text-gray-700">{{ \Carbon\Carbon::parse($cicilan->tanggal_bayar)->isoFormat('DD MMMM YYYY') }}</td>
                                <td class="py-2.5 px-3 text-right font-semibold text-green-600">@rupiah($cicilan->jumlah_bayar)</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $cicilan->keterangan ?: '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-500">{{ $cicilan->pengurus->name ?? 'Sistem' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-gray-500 italic text-center py-4">Belum ada pembayaran cicilan untuk transaksi ini.</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <div class="mt-8 flex justify-start space-x-3">
        <a href="{{ route('pengurus.transaksi-pembelian.index') }}">
            <x-forms.button type="button" variant="secondary" icon="arrow-left">
                Kembali ke Daftar Transaksi
            </x-forms.button>
        </a>
        {{-- Tombol cetak struk jika ada --}}
        {{-- <x-forms.button type="button" variant="neutral" icon="print" onclick="window.print()">
            Cetak Struk
        </x-forms.button> --}}
    </div>
</div>
@endsection