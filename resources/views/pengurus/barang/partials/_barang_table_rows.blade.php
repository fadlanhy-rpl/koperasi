{{-- resources/views/pengurus/barang/partials/_barang_table_rows.blade.php --}}
@forelse($barangs as $item)
    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-300">
        <td class="py-3 px-4">
            <div class="font-medium text-gray-800">{{ $item->nama_barang }}</div>
            <div class="text-xs text-gray-500">{{ $item->kode_barang ?? '-' }}</div>
        </td>
        <td class="py-3 px-4 text-gray-600">{{ $item->unitUsaha->nama_unit_usaha ?? 'N/A' }}</td>
        <td class="py-3 px-4 text-gray-600 text-right">@rupiah($item->harga_beli)</td>
        <td class="py-3 px-4 text-gray-600 text-right">@rupiah($item->harga_jual)</td>
        <td class="py-3 px-4 text-center font-semibold {{ $item->stok <= 10 && $item->stok > 0 ? 'text-red-600' : ($item->stok == 0 ? 'text-gray-400' : 'text-green-600') }}">
            {{ $item->stok }} <span class="text-xs text-gray-500 font-normal">{{ $item->satuan }}</span>
        </td>
        <td class="py-3 px-4 text-gray-600">{{ Str::limit($item->deskripsi, 40) }}</td>
        <td class="py-3 px-4">
            <div class="flex items-center space-x-2">
                <a href="{{ route('pengurus.barang.show', $item->id) }}" class="text-emerald-600 hover:text-emerald-800 p-1.5 hover:bg-emerald-50 rounded-lg transition-all duration-300" title="Lihat Detail & Histori Stok">
                    <i class="fas fa-eye fa-fw"></i>
                </a>
                <a href="{{ route('pengurus.barang.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-lg transition-all duration-300" title="Edit">
                    <i class="fas fa-edit fa-fw"></i>
                </a>
                <button type="button" onclick="confirmDelete('{{ route('pengurus.barang.destroy', $item->id) }}', '{{ $item->nama_barang }}')" class="text-red-600 hover:text-red-800 p-1.5 hover:bg-red-50 rounded-lg transition-all duration-300" title="Hapus">
                    <i class="fas fa-trash fa-fw"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-8 text-gray-500">
            <div class="flex flex-col items-center">
                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                Tidak ada data barang yang ditemukan.
            </div>
        </td>
    </tr>
@endforelse