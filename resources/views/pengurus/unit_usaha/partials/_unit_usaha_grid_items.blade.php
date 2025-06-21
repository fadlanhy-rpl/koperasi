@forelse($unitUsahas as $index => $unit)
    <div class="unit-card rounded-3xl p-8 shadow-lg slide-in-up" 
         style="animation-delay: {{ $index * 0.1 }}s">
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <h3 class="text-2xl font-bold text-gray-800 mb-3 group-hover:text-emerald-700 transition-colors duration-200">
                    {{ $unit->nama_unit_usaha }}
                </h3>
                <p class="text-gray-600 text-base leading-relaxed line-clamp-3">
                    {{ $unit->deskripsi ?: 'Tidak ada deskripsi tersedia untuk unit usaha ini.' }}
                </p>
            </div>
            <div class="ml-6">
                <div class="w-20 h-20 bg-gradient-to-r from-emerald-500 to-green-600 rounded-3xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="text-center p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl">
                <div class="text-3xl font-bold text-emerald-600 mb-1">
                    {{ $unit->barangs_count ?? $unit->barangs()->count() }}
                </div>
                <div class="text-sm text-gray-600 font-medium uppercase tracking-wider">Barang</div>
            </div>
            <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl">
                <div class="text-xl font-bold text-blue-600 mb-1">
                    {{ $unit->created_at->format('M Y') }}
                </div>
                <div class="text-sm text-gray-600 font-medium uppercase tracking-wider">Dibuat</div>
            </div>
        </div>
        
        <div class="flex items-center justify-between pt-6 border-t border-gray-100">
            <div class="flex items-center space-x-2 text-gray-500">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                </svg>
                <span class="text-sm font-medium">{{ $unit->created_at->diffForHumans() }}</span>
            </div>
            <div class="unit-card-actions flex items-center space-x-3">
                <a href="{{ route('pengurus.unit-usaha.edit', $unit->id) }}" 
                   class="action-btn bg-blue-500 hover:bg-blue-600 text-white" 
                   title="Edit Unit Usaha">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                </a>
                <button onclick="confirmDelete('{{ route('pengurus.unit-usaha.destroy', $unit->id) }}', '{{ ($unit->nama_unit_usaha) }}')" 
                        class="action-btn bg-red-500 hover:bg-red-600 text-white" 
                        title="Hapus Unit Usaha">
                    <svg class="w-5 h-5" fill="currentColor"z viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
@empty
    <div class="col-span-full">
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
            </div>
            <h3 class="text-3xl font-bold text-gray-600 mb-4">Tidak Ada Unit Usaha Ditemukan</h3>
            <p class="text-gray-500 text-lg max-w-md mx-auto mb-8 leading-relaxed">
                Tidak ada unit usaha yang sesuai dengan pencarian Anda. Coba ubah kata kunci atau tambah unit usaha baru.
            </p>
            <a href="{{ route('pengurus.unit-usaha.create') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                </svg>
                Tambah Unit Usaha
            </a>
        </div>
    </div>
@endforelse

<style>
.slide-in-up {
    animation: slideInUp 0.6s ease-out both;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.empty-state {
    text-align: center;
    padding: 100px 20px;
    position: relative;
}

.empty-state-icon {
    width: 160px;
    height: 160px;
    margin: 0 auto 32px;
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.empty-state-icon::before {
    content: '';
    position: absolute;
    inset: 0;
    background: conic-gradient(from 0deg, transparent, rgba(16, 185, 129, 0.2), transparent);
    animation: rotate 3s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.unit-card-actions {
    position: relative;
    z-index: 10;
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
