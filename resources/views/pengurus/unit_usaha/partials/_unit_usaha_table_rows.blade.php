@forelse($unitUsahas as $index => $unit)
    <tr class="table-row hover:bg-gradient-to-r hover:from-emerald-50 hover:to-transparent transition-all duration-300 group transform hover:scale-[1.01] border-b border-gray-50" 
        style="animation: slideInUp 0.5s ease-out {{ $index * 0.1 }}s both;">
        <td class="py-6 px-6">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-bold text-gray-800 text-lg group-hover:text-emerald-700 transition-colors duration-200">
                        {{ $unit->nama_unit_usaha }}
                    </div>
                    <div class="text-sm text-gray-500">Unit Bisnis Koperasi</div>
                </div>
            </div>
        </td>
        <td class="py-6 px-6">
            <div class="max-w-xs">
                <p class="text-gray-600 group-hover:text-gray-800 transition-colors duration-200 line-clamp-2">
                    {{ $unit->deskripsi ? Str::limit($unit->deskripsi, 100) : 'Tidak ada deskripsi' }}
                </p>
                @if($unit->deskripsi && strlen($unit->deskripsi) > 100)
                    <button class="text-emerald-600 hover:text-emerald-800 text-sm font-medium mt-1 transition-colors duration-200">
                        Lihat selengkapnya...
                    </button>
                @endif
            </div>
        </td>
        <td class="py-8 px-8 text-center">
            <div class="inline-flex items-center justify-center">
                <div class="bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800 px-6 py-3 rounded-2xl shadow-sm group-hover:shadow-md transition-all duration-300">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM9 9a1 1 0 012 0v4a1 1 0 11-2 0V9z"/>
                        </svg>
                        <span class="text-lg font-bold">{{ $unit->barangs_count ?? $unit->barangs()->count() }}</span>
                        <span class="text-sm font-medium opacity-80">item</span>
                    </div>
                </div>
            </div>
        </td>
        <td class="py-6 px-6">
            <div class="flex items-center space-x-3">
                <div class="w-3 h-3 bg-gradient-to-r from-indigo-400 to-indigo-500 rounded-full group-hover:scale-125 transition-transform duration-200 shadow-sm"></div>
                <div>
                    <div class="text-sm font-semibold text-gray-900 group-hover:text-indigo-700 transition-colors duration-200">
                        {{ $unit->created_at->format('d M Y') }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $unit->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </td>
        <td class="py-8 px-8">
            <div class="flex items-center justify-center space-x-4">
                <a href="{{ route('pengurus.unit-usaha.edit', $unit->id) }}" 
                   class="action-btn bg-blue-500 hover:bg-blue-600 text-white shadow-lg hover:shadow-xl" 
                   title="Edit Unit Usaha">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                </a>
                <button onclick="confirmDelete('{{ route('pengurus.unit-usaha.destroy', $unit->id) }}', '{{ ($unit->nama_unit_usaha) }}')" 
                        class="action-btn bg-red-500 hover:bg-red-600 text-white shadow-lg hover:shadow-xl" 
                        title="Hapus Unit Usaha">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"/>
                    </svg>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="py-16">
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Tidak ada unit usaha ditemukan</h3>
                <p class="text-gray-500 text-base max-w-md mx-auto mb-6">
                    Tidak ada data yang sesuai dengan pencarian Anda. Coba ubah kata kunci atau tambah unit usaha baru.
                </p>
                <a href="{{ route('pengurus.unit-usaha.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                    </svg>
                    Tambah Unit Usaha
                </a>
            </div>
        </td>
    </tr>
@endforelse

<style>
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.action-btn {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    border: none;
    cursor: pointer;
    z-index: 10;
}

.action-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.action-btn:hover::before {
    opacity: 1;
}

.action-btn:hover {
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.25);
}
</style>
