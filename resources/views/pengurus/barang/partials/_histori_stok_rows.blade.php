{{-- resources/views/pengurus/barang/partials/_histori_stok_rows.blade.php --}}
@forelse($historiStoks as $histori)
    <tr class="border-b border-gray-100 text-sm {{ $loop->even ? 'bg-gray-50/50' : '' }}">
        <td class="py-2.5 px-4 text-gray-700">{{ $histori->created_at->format('d M Y, H:i') }}</td>
        <td class="py-2.5 px-4">
            @if($histori->tipe == 'masuk')
                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Masuk</span>
            @elseif($histori->tipe == 'keluar')
                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Keluar</span>
            @else
                <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Penyesuaian</span>
            @endif
        </td>
        <td class="py-2.5 px-4 text-center font-medium {{ $histori->jumlah >= 0 ? 'text-green-600' : 'text-red-600' }}">
            {{ $histori->jumlah >= 0 ? '+' : '' }}{{ $histori->jumlah }}
        </td>
        <td class="py-2.5 px-4 text-center text-gray-600">{{ $histori->stok_sebelum }}</td>
        <td class="py-2.5 px-4 text-center font-semibold text-gray-800">{{ $histori->stok_sesudah }}</td>
        <td class="py-2.5 px-4 text-gray-600">{{ $histori->keterangan }}</td>
        <td class="py-2.5 px-4 text-gray-500">{{ $histori->user->name ?? 'Sistem' }}</td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-6 text-gray-400 italic">
            Belum ada histori stok untuk barang ini.
        </td>
    </tr>
@endforelse