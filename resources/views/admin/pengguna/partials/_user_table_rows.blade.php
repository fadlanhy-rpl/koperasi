@forelse($users as $index => $user)
    <tr class="table-row hover:bg-gradient-to-r hover:from-indigo-50 hover:to-transparent transition-all duration-300 group transform hover:scale-[1.01] border-b border-gray-50" 
        style="animation: slideInUp 0.5s ease-out {{ $index * 0.1 }}s both;">
        <td class="py-6 px-6">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-200">
                    @if($user->profile_photo_url && !str_contains($user->profile_photo_url, 'placeholder_avatar.png'))
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover rounded-2xl">
                    @else
                        <span class="text-lg font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    @endif
                </div>
                <div>
                    <div class="font-bold text-gray-800 text-lg group-hover:text-indigo-700 transition-colors duration-200">
                        {{ $user->name }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $user->nomor_anggota ? 'ID: ' . $user->nomor_anggota : 'Pengguna Sistem' }}
                    </div>
                </div>
            </div>
        </td>
        <td class="py-6 px-6">
            <div class="max-w-xs">
                <p class="text-gray-600 group-hover:text-gray-800 transition-colors duration-200 font-medium">
                    {{ $user->email }}
                </p>
                <div class="text-sm text-gray-500 mt-1">
                    {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}
                </div>
            </div>
        </td>
        <td class="py-6 px-6">
            <div class="inline-flex items-center justify-center">
                <div class="role-badge role-{{ $user->role }} px-4 py-2 rounded-2xl shadow-sm group-hover:shadow-md transition-all duration-300">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            @if($user->role === 'admin')
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"/>
                            @elseif($user->role === 'pengurus')
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            @else
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            @endif
                        </svg>
                        <span class="text-sm font-bold">{{ ucfirst($user->role) }}</span>
                    </div>
                </div>
            </div>
        </td>
        <td class="py-6 px-6">
            <div class="inline-flex items-center justify-center">
                <div class="status-badge status-{{ $user->status }} px-4 py-2 rounded-2xl shadow-sm group-hover:shadow-md transition-all duration-300">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 rounded-full {{ $user->status === 'active' ? 'bg-green-400' : 'bg-red-400' }}"></div>
                        <span class="text-sm font-bold">{{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}</span>
                    </div>
                </div>
            </div>
        </td>
        <td class="py-6 px-6">
            <div class="flex items-center space-x-3">
                <div class="w-3 h-3 bg-gradient-to-r from-indigo-400 to-indigo-500 rounded-full group-hover:scale-125 transition-transform duration-200 shadow-sm"></div>
                <div>
                    <div class="text-sm font-semibold text-gray-900 group-hover:text-indigo-700 transition-colors duration-200">
                        {{ $user->created_at->format('d M Y') }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $user->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </td>
        <td class="py-4 px-4">
            <div class="flex items-center justify-center space-x-4">
                <a href="{{ route('admin.manajemen-pengguna.show', $user->id) }}" 
                   class="action-btn bg-emerald-500 hover:bg-emerald-600 text-white shadow-lg hover:shadow-xl" 
                   title="Lihat Detail">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </a>
                <a href="{{ route('admin.manajemen-pengguna.edit', $user->id) }}" 
                   class="action-btn bg-blue-500 hover:bg-blue-600 text-white shadow-lg hover:shadow-xl" 
                   title="Edit Pengguna">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                </a>
                @if(Auth::id() !== $user->id)
                    <button onclick="confirmDelete('{{ route('admin.manajemen-pengguna.destroy', $user->id) }}', '{{ $user->name }}')" 
                            class="action-btn bg-red-500 hover:bg-red-600 text-white shadow-lg hover:shadow-xl" 
                            title="Hapus Pengguna">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"/>
                        </svg>
                    </button>
                @endif
                
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="py-16">
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Tidak ada pengguna ditemukan</h3>
                <p class="text-gray-500 text-base max-w-md mx-auto mb-6">
                    Tidak ada data yang sesuai dengan pencarian Anda. Coba ubah kata kunci atau tambah pengguna baru.
                </p>
                <a href="{{ route('admin.manajemen-pengguna.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                    </svg>
                    Tambah Pengguna
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

.role-badge {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-admin {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.role-pengurus {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.role-anggota {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.status-badge {
    font-weight: 600;
}

.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: #15803d;
    border: 1px solid rgba(34, 197, 94, 0.2);
}

.status-inactive {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, 0.2);
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


