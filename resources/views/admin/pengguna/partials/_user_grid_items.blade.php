@forelse($users as $index => $user)
    <div class="user-card rounded-3xl shadow-lg slide-in-up" 
         style="animation-delay: {{ $index * 0.1 }}s">
        <div class="user-card-content">
            <!-- Header Section -->
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center space-x-4 flex-1 min-w-0">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl flex items-center justify-center text-white shadow-lg flex-shrink-0">
                        @if($user->profile_photo_url && !str_contains($user->profile_photo_url, 'placeholder_avatar.png'))
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover rounded-3xl">
                        @else
                            <span class="text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-xl font-bold text-gray-800 mb-2 truncate" title="{{ $user->name }}">
                            {{ $user->name }}
                        </h3>
                        <p class="text-gray-600 text-sm truncate" title="{{ $user->email }}">{{ $user->email }}</p>
                        @if($user->nomor_anggota)
                            <p class="text-gray-500 text-xs mt-1 truncate" title="ID: {{ $user->nomor_anggota }}">
                                <i class="fas fa-id-badge mr-1"></i>{{ $user->nomor_anggota }}
                            </p>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-col items-end space-y-2 flex-shrink-0 ml-4">
                    <span class="role-badge role-{{ $user->role }} px-3 py-1 rounded-full text-xs whitespace-nowrap">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="status-badge status-{{ $user->status }} px-3 py-1 rounded-full text-xs whitespace-nowrap">
                        <i class="fas fa-circle text-xs mr-1"></i>
                        {{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            
            <!-- Info Grid Section -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-center p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl">
                    <div class="text-lg font-bold text-indigo-600 mb-1">
                        {{ $user->created_at->format('M Y') }}
                    </div>
                    <div class="text-sm text-gray-600 font-medium uppercase tracking-wider">Bergabung</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-2xl">
                    <div class="text-lg font-bold text-emerald-600 mb-1">
                        {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                    </div>
                    <div class="text-sm text-gray-600 font-medium uppercase tracking-wider">Status Email</div>
                </div>
            </div>
            
            <!-- Footer Section -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-100 mt-auto">
                <div class="flex items-center space-x-2 text-gray-500 flex-1 min-w-0">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                    <span class="text-sm font-medium truncate" title="{{ $user->created_at->diffForHumans() }}">
                        {{ $user->created_at->diffForHumans() }}
                    </span>
                </div>
                <div class="user-card-actions flex items-center space-x-2 flex-shrink-0 ml-4">
                    <a href="{{ route('admin.manajemen-pengguna.show', $user->id) }}" 
                       class="action-btn bg-emerald-500 hover:bg-emerald-600 text-white" 
                       title="Lihat Detail">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </a>
                    <a href="{{ route('admin.manajemen-pengguna.edit', $user->id) }}" 
                       class="action-btn bg-blue-500 hover:bg-blue-600 text-white" 
                       title="Edit Pengguna">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                    </a>
                    @if(Auth::id() !== $user->id)
                        <button onclick="confirmDelete('{{ route('admin.manajemen-pengguna.destroy', $user->id) }}', '{{ $user->name }}')"
                                class="action-btn bg-red-500 hover:bg-red-600 text-white" 
                                title="Hapus Pengguna">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-span-full">
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-600 mb-4">Tidak Ada Pengguna Ditemukan</h3>
            <p class="text-gray-500 text-base max-w-md mx-auto mb-6 leading-relaxed">
                Tidak ada pengguna yang sesuai dengan pencarian Anda. Coba ubah kata kunci atau tambah pengguna baru.
            </p>
            <a href="{{ route('admin.manajemen-pengguna.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                </svg>
                Tambah Pengguna
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
    display: inline-flex;
    align-items: center;
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
    width: 40px;
    height: 40px;
    border-radius: 12px;
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
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 8px 20px -5px rgba(0, 0, 0, 0.25);
}
</style>