{{-- resources/views/pengurus/laporan/penjualan/partials/_penjualan_umum_rows.blade.php --}}
@forelse($detailPembelians as $detail)
    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors duration-150">
        <td class="py-2.5 px-3">
            <a href="{{ route('pengurus.transaksi-pembelian.show', $detail->pembelian->id) }}" class="text-blue-600 hover:underline font-medium">
                {{ $detail->pembelian->kode_pembelian }}
            </a>
            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($detail->pembelian->tanggal_pembelian)->isoFormat('DD MMM YYYY, HH:mm') }}</div>
        </td>
        <td class="py-2.5 px-3 text-gray-700">
            {{ $detail->pembelian->user->name ?? 'N/A' }}
            <div class="text-xs text-gray-500">{{ $detail->pembelian->user->nomor_anggota ?? '-' }}</div>
        </td>
        <td class="py-2.5 px-3">
            <p class="text-gray-800">{{ $detail->barang->nama_barang ?? 'Barang Dihapus' }}</p>
            <p class="text-xs text-gray-500">{{ $detail->barang->kode_barang ?? '-' }}</p>
            <p class="text-xs text-blue-500">{{ $detail->barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}</p>
        </td>
        <td class="py-2.5 px-3 text-center text-gray-700">{{ $detail->jumlah }}</td>
        <td class="py-2.5 px-3 text-right text-gray-700">@rupiah($detail->harga_satuan)</td>
        <td class="py-2.5 px-3 text-right font-semibold text-gray-800">@rupiah($detail->subtotal)</td>
        <td class="py-2.5 px-3 text-center">
            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                @if($detail->pembelian->status_pembayaran == 'lunas') bg-green-100 text-green-700
                @elseif($detail->pembelian->status_pembayaran == 'cicilan') bg-yellow-100 text-yellow-700
                @else bg-red-100 text-red-700 @endif">
                {{ ucfirst($detail->pembelian->status_pembayaran) }}
            </span>
        </td>
        <td class="py-2.5 px-3 text-gray-600">{{ ucfirst(str_replace('_', ' ', $detail->pembelian->metode_pembayaran)) }}</td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-10 text-gray-500">
            <div class="flex flex-col items-center">
                <i class="fas fa-file-invoice-dollar text-4xl mb-3 text-gray-300"></i>
                Tidak ada data penjualan ditemukan untuk filter yang diterapkan.
            </div>
        </td>
    </tr>
@endforelse