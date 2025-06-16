{{-- resources/views/admin/pengguna/partials/_user_table_rows.blade.php --}}
@forelse($users as $user)
    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-300 user-row" data-role="{{ $user->role }}" data-status="{{ $user->status }}">
        <td class="py-3 px-4 font-medium text-gray-800">{{ $user->name }}</td>
        <td class="py-3 px-4 text-gray-600">{{ $user->email }}</td>
        <td class="py-3 px-4 text-gray-600">{{ $user->nomor_anggota ?? '-' }}</td>
        <td class="py-3 px-4">
            <span class="px-3 py-1 rounded-full text-xs font-semibold
                @if($user->role == 'admin') bg-purple-100 text-purple-700
                @elseif($user->role == 'pengurus') bg-blue-100 text-blue-700
                @else bg-green-100 text-green-700 @endif">
                {{ ucfirst($user->role) }}
            </span>
        </td>
        <td class="py-3 px-4 text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
        <td class="py-3 px-4">
            <span class="px-3 py-1 rounded-full text-xs font-semibold
                {{ $user->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $user->status == 'active' ? 'Aktif' : 'Nonaktif' }}
            </span>
        </td>
        <td class="py-3 px-4">
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.manajemen-pengguna.show', $user->id) }}" class="text-emerald-600 hover:text-emerald-800 p-1.5 hover:bg-emerald-50 rounded-lg transition-all duration-300" title="Lihat Detail">
                    <i class="fas fa-eye fa-fw"></i>
                </a>
                <a href="{{ route('admin.manajemen-pengguna.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-lg transition-all duration-300" title="Edit">
                    <i class="fas fa-edit fa-fw"></i>
                </a>
                {{-- Hanya tampilkan tombol hapus jika bukan user yg sedang login --}}
                @if(Auth::id() !== $user->id)
                <button type="button" onclick="confirmDelete('{{ route('admin.manajemen-pengguna.destroy', $user->id) }}', '{{ $user->name }}')" class="text-red-600 hover:text-red-800 p-1.5 hover:bg-red-50 rounded-lg transition-all duration-300" title="Hapus">
                    <i class="fas fa-trash fa-fw"></i>
                </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-8 text-gray-500">
            <div class="flex flex-col items-center">
                <i class="fas fa-ghost text-4xl mb-3 text-gray-300"></i>
                Tidak ada data pengguna yang ditemukan.
            </div>
        </td>
    </tr>
@endforelse