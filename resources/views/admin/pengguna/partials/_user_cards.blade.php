{{-- resources/views/admin/pengguna/partials/_user_cards.blade.php
@forelse($users as $user)
    <div class="user-card p-6 fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-4">
                <div class="avatar-container">
                    @if($user->profile_photo_url && !str_contains($user->profile_photo_url, 'placeholder_avatar.png'))
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 text-lg">{{ $user->name }}</h4>
                    <p class="text-gray-600 text-sm">{{ $user->email }}</p>
                    @if($user->nomor_anggota)
                        <p class="text-gray-500 text-xs mt-1">
                            <i class="fas fa-id-badge mr-1"></i>{{ $user->nomor_anggota }}
                        </p>
                    @endif
                </div>
            </div>
            
            <div class="flex flex-col items-end space-y-2">
                <span class="role-badge role-{{ $user->role }}">
                    {{ ucfirst($user->role) }}
                </span>
                <span class="status-badge status-{{ $user->status }}">
                    <i class="fas fa-circle text-xs mr-1"></i>
                    {{ $user->status == 'active' ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
        </div>
        
        <div class="border-t border-gray-100 pt-4">
            <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                <span class="flex items-center">
                    <i class="fas fa-calendar-plus mr-2 text-gray-400"></i>
                    Bergabung {{ $user->created_at->format('d M Y') }}
                </span>
                @if($user->last_login_at)
                    <span class="flex items-center">
                        <i class="fas fa-clock mr-2 text-gray-400"></i>
                        {{ $user->last_login_at->diffForHumans() }}
                    </span>
                @endif
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <a href="{{ route('admin.manajemen-pengguna.show', $user->id) }}" 
                       class="action-btn btn-view" 
                       title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.manajemen-pengguna.edit', $user->id) }}" 
                       class="action-btn btn-edit" 
                       title="Edit Pengguna">
                        <i class="fas fa-edit"></i>
                    </a>
                    @if(Auth::id() !== $user->id)
                        <button type="button" 
                                onclick="confirmDelete('{{ route('admin.manajemen-pengguna.destroy', $user->id) }}', '{{ $user->name }}')" 
                                class="action-btn btn-delete" 
                                title="Hapus Pengguna">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </div>
                
                <div class="text-xs text-gray-500">
                    ID: #{{ $user->id }}
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-span-full text-center py-16">
        <div class="max-w-md mx-auto">
            <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak Ada Pengguna</h3>
            <p class="text-gray-500 mb-6">Belum ada data pengguna yang sesuai dengan filter yang dipilih.</p>
            <a href="{{ route('admin.manajemen-pengguna.create') }}">
                <button class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-indigo-600 hover:to-purple-700 transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i>Tambah Pengguna Pertama
                </button>
            </a>
        </div>
    </div>
@endforelse --}}
