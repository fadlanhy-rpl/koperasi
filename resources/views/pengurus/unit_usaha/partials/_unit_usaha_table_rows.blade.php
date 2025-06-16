{{-- resources/views/pengurus/unit_usaha/partials/_unit_usaha_table_rows.blade.php --}}
@forelse($unitUsahas as $unit)
    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-300">
        <td class="py-3 px-4 font-medium text-gray-800">{{ $unit->nama_unit_usaha }}</td>
        <td class="py-3 px-4 text-gray-600">{{ Str::limit($unit->deskripsi, 70) }}</td>
        <td class="py-3 px-4 text-gray-600">{{ $unit->barangs_count ?? $unit->barangs()->count() }}</td> {{-- Tampilkan jumlah barang --}}
        <td class="py-3 px-4 text-gray-600">{{ $unit->created_at->format('d M Y') }}</td>
        <td class="py-3 px-4">
            <div class="flex items-center space-x-2">
                <a href="{{ route('pengurus.unit-usaha.edit', $unit->id) }}" class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-lg transition-all duration-300" title="Edit">
                    <i class="fas fa-edit fa-fw"></i>
                </a>
                <button type="button" onclick="confirmDelete('{{ route('pengurus.unit-usaha.destroy', $unit->id) }}', '{{ $unit->nama_unit_usaha }}')" class="text-red-600 hover:text-red-800 p-1.5 hover:bg-red-50 rounded-lg transition-all duration-300" title="Hapus">
                    <i class="fas fa-trash fa-fw"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-8 text-gray-500">
            <div class="flex flex-col items-center">
                <i class="fas fa-store-slash text-4xl mb-3 text-gray-300"></i>
                Tidak ada data unit usaha yang ditemukan.
            </div>
        </td>
    </tr>
@endforelse