@forelse($laporan_per_anggota as $index => $anggota)
    <tr class="table-row border-b border-gray-100">
        <td class="py-4 px-4 text-gray-700 font-medium">
            {{ $laporan_per_anggota->firstItem() + $index }}
        </td>
        <td class="py-4 px-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                    {{ strtoupper(substr($anggota->name, 0, 2)) }}
                </div>
                <div>
                    <div class="font-semibold text-gray-800">{{ $anggota->name }}</div>
                    <div class="text-xs text-gray-500">{{ $anggota->email ?? 'Email tidak tersedia' }}</div>
                </div>
            </div>
        </td>
        <td class="py-4 px-4 text-gray-600 font-mono">
            <span class="bg-gray-100 px-2 py-1 rounded text-xs">
                {{ $anggota->nomor_anggota ?? '-' }}
            </span>
        </td>
        <td class="py-4 px-4 text-right">
            <span class="font-semibold text-blue-600">
                Rp {{ number_format($anggota->total_simpanan_pokok_view, 0, ',', '.') }}
            </span>
        </td>
        <td class="py-4 px-4 text-right">
            <span class="font-semibold text-green-600">
                Rp {{ number_format($anggota->total_simpanan_wajib_view, 0, ',', '.') }}
            </span>
        </td>
        <td class="py-4 px-4 text-right">
            <span class="font-semibold text-yellow-600">
                Rp {{ number_format($anggota->saldo_simpanan_sukarela_view, 0, ',', '.') }}
            </span>
        </td>
        <td class="py-4 px-4 text-right">
            <span class="font-bold text-indigo-600 text-lg">
                Rp {{ number_format($anggota->total_simpanan_pokok_view + $anggota->total_simpanan_wajib_view + $anggota->saldo_simpanan_sukarela_view, 0, ',', '.') }}
            </span>
        </td>
        <td class="py-4 px-4 text-center">
            <div class="flex items-center justify-center space-x-2">
                <a href="{{ route('pengurus.simpanan.riwayatAnggota', $anggota->id) }}" 
                   class="bg-blue-100 hover:bg-blue-200 text-blue-600 p-2 rounded-lg transition-all duration-300 hover:scale-110" 
                   title="Lihat Riwayat Detail {{ $anggota->name }}">
                    <i class="fas fa-eye"></i>
                </a>
                <button type="button" 
                        onclick="showAnggotaDetail({{ $anggota->id }})"
                        class="bg-green-100 hover:bg-green-200 text-green-600 p-2 rounded-lg transition-all duration-300 hover:scale-110" 
                        title="Detail Cepat {{ $anggota->name }}">
                    <i class="fas fa-info-circle"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-16 text-gray-500">
            <div class="flex flex-col items-center">
                <i class="fas fa-users-slash text-6xl mb-4 text-gray-300"></i>
                <h3 class="text-xl font-semibold mb-2">Tidak ada data anggota</h3>
                <p class="text-gray-400">
                    @if(request('search_anggota'))
                        Tidak ditemukan anggota dengan pencarian "{{ request('search_anggota') }}"
                    @else
                        Belum ada data anggota yang terdaftar
                    @endif
                </p>
                @if(request('search_anggota'))
                    <a href="{{ route('pengurus.laporan.simpanan.rincianPerAnggota') }}" 
                       class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-undo mr-2"></i>
                        Tampilkan Semua Data
                    </a>
                @endif
            </div>
        </td>
    </tr>
@endforelse

<script>
function showAnggotaDetail(anggotaId) {
    // TODO: Implement modal or popup for quick member details
    console.log('Show detail for member ID:', anggotaId);
    // This could open a modal with detailed member information
}
</script>
