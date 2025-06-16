{{-- resources/views/pengurus/transaksi_pembelian/partials/_transaksi_table_rows.blade.php --}}
@forelse($pembelians as $pembelian)
    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-300">
        <td class="py-3 px-4">
            <a href="{{ route('pengurus.transaksi-pembelian.show', $pembelian->id) }}" class="font-medium text-blue-600 hover:text-blue-800">
                {{ $pembelian->kode_pembelian }}
            </a>
            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('DD MMM YYYY, HH:mm') }}</div>
        </td>
        <td class="py-3 px-4">
            <div>{{ $pembelian->user->name ?? 'N/A' }}</div>
            <div class="text-xs text-gray-500">{{ $pembelian->user->nomor_anggota ?? '-' }}</div>
        </td>
        <td class="py-3 px-4 text-gray-600">{{ $pembelian->kasir->name ?? 'Sistem' }}</td>
        <td class="py-3 px-4 text-gray-800 font-semibold text-right">@rupiah($pembelian->total_harga)</td>
        <td class="py-3 px-4 text-center">
            <span class="px-3 py-1 rounded-full text-xs font-semibold
                @if($pembelian->status_pembayaran == 'lunas') bg-green-100 text-green-700
                @elseif($pembelian->status_pembayaran == 'cicilan') bg-yellow-100 text-yellow-700
                @else bg-red-100 text-red-700 @endif">
                {{ ucfirst($pembelian->status_pembayaran) }}
            </span>
        </td>
        <td class="py-3 px-4 text-gray-600">{{ ucfirst($pembelian->metode_pembayaran) }}</td>
        <td class="py-3 px-4 text-center">
             <a href="{{ route('pengurus.transaksi-pembelian.show', $pembelian->id) }}" class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-lg transition-all duration-300" title="Lihat Detail">
                <i class="fas fa-eye fa-fw"></i>
            </a>
            {{-- Tombol aksi lain jika perlu, misal cetak struk, batalkan (jika ada logicnya) --}}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-8 text-gray-500">
            <div class="flex flex-col items-center">
                <i class="fas fa-receipt text-4xl mb-3 text-gray-300"></i>
                Tidak ada data transaksi pembelian ditemukan.
            </div>
        </td>
    </tr>
@endforelse