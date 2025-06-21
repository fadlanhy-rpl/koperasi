@extends('layouts.app')

@section('title', 'Detail Pengguna - Koperasi')
@section('page-title', 'Detail Pengguna')
@section('page-subtitle', 'Informasi lengkap pengguna sistem')

@push('styles')
<style>
    .profile-container {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
    }
    
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px 20px 0 0;
        padding: 3rem 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="50" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="30" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    
    .avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: bold;
        color: white;
        position: relative;
        z-index: 10;
    }
    
    .avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    
    .info-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    
    .info-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        background: rgba(249, 250, 251, 0.8);
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .info-item:hover {
        background: rgba(243, 244, 246, 0.9);
        transform: translateX(5px);
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
        font-size: 1rem;
    }
    
    .icon-user { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
    .icon-email { background: linear-gradient(135deg, #10b981, #059669); }
    .icon-id { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .icon-calendar { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .icon-status { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .icon-clock { background: linear-gradient(135deg, #ef4444, #dc2626); }
    
    .role-badge-large {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .status-badge-large {
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-active-large {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    }
    
    .status-inactive-large {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .btn-action {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }
    
    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }
    
    .btn-back {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }
    
    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    
    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    }
    
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.25rem;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-8 fade-in">
    <!-- Profile Header -->
    <div class="profile-container">
        <div class="profile-header">
            <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6 relative z-10">
                <div class="avatar-large">
                    @if($user->profile_photo_url && !str_contains($user->profile_photo_url, 'placeholder_avatar.png'))
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                
                <div class="flex-1 text-center lg:text-left">
                    <h1 class="text-3xl lg:text-4xl font-bold mb-2">{{ $user->name }}</h1>
                    <p class="text-xl text-indigo-100 mb-4">{{ $user->email }}</p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-6">
                        <span class="role-badge-large">{{ ucfirst($user->role) }}</span>
                        <span class="status-badge-large status-{{ $user->status }}-large">
                            <i class="fas fa-circle text-xs"></i>
                            {{ $user->status == 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    
                    @if($user->nomor_anggota)
                        <p class="text-indigo-100">
                            <i class="fas fa-id-badge mr-2"></i>
                            Nomor Anggota: {{ $user->nomor_anggota }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="p-6">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon icon-calendar">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Bergabung</h3>
                    <p class="text-gray-600">{{ $user->created_at->isoFormat('DD MMMM YYYY') }}</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon icon-clock">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Login Terakhir</h3>
                    <p class="text-gray-600">
                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum tercatat' }}
                    </p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon icon-status">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Status Email</h3>
                    <p class="text-gray-600">
                        {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detailed Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Personal Information -->
        <div class="info-card p-6">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Informasi Personal</h3>
            </div>
            
            <div class="space-y-4">
                <div class="info-item">
                    <div class="info-icon icon-user">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Nama Lengkap</p>
                        <p class="text-gray-800 font-semibold">{{ $user->name }}</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon icon-email">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Alamat Email</p>
                        <p class="text-gray-800 font-semibold">{{ $user->email }}</p>
                    </div>
                </div>
                
                @if($user->nomor_anggota)
                <div class="info-item">
                    <div class="info-icon icon-id">
                        <i class="fas fa-id-badge"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Nomor Anggota</p>
                        <p class="text-gray-800 font-semibold">{{ $user->nomor_anggota }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- System Information -->
        <div class="info-card p-6">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-cog text-white text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Informasi Sistem</h3>
            </div>
            
            <div class="space-y-4">
                <div class="info-item">
                    <div class="info-icon icon-calendar">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Tanggal Bergabung</p>
                        <p class="text-gray-800 font-semibold">{{ $user->created_at->isoFormat('DD MMMM YYYY, HH:mm') }}</p>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon icon-status">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Status Akun</p>
                        <span class="status-badge-large status-{{ $user->status }}-large">
                            <i class="fas fa-circle text-xs"></i>
                            {{ $user->status == 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-icon icon-clock">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Login Terakhir</p>
                        <p class="text-gray-800 font-semibold">
                            {{ $user->last_login_at ? $user->last_login_at->isoFormat('DD MMMM YYYY, HH:mm') : 'Belum tercatat' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="info-card p-6">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-tools mr-3 text-indigo-600"></i>
                Aksi Pengguna
            </h3>
            
            <div class="action-buttons">
                <a href="{{ route('admin.manajemen-pengguna.index') }}" class="btn-action btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
                
                <a href="{{ route('admin.manajemen-pengguna.edit', $user->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i>
                    Edit Pengguna
                </a>
                
                @if(Auth::id() !== $user->id)
                    <button type="button" 
                            onclick="confirmDelete('{{ route('admin.manajemen-pengguna.destroy', $user->id) }}', '{{ $user->name }}')" 
                            class="btn-action btn-delete">
                        <i class="fas fa-trash"></i>
                        Hapus Pengguna
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 max-w-md mx-4 transform transition-all duration-300 scale-95">
        <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6" id="deleteMessage">Apakah Anda yakin ingin menghapus pengguna ini?</p>
            <div class="flex space-x-4">
                <button id="cancelDelete" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                    Batal
                </button>
                <button id="confirmDelete" class="flex-1 bg-red-500 text-white py-3 rounded-xl font-semibold hover:bg-red-600 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let deleteUrl = '';

    // Delete modal functions
    window.confirmDelete = function(url, userName) {
        deleteUrl = url;
        document.getElementById('deleteMessage').textContent = 
            `Apakah Anda yakin ingin menghapus pengguna "${userName}"? Tindakan ini tidak dapat diurungkan.`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    };

    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;
        form.style.display = 'none';
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        
        document.body.appendChild(form);
        form.submit();
    });

    // Close modal on outside click
    document.getElementById('deleteModal').addEventListener('click', function(event) {
        if (event.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });
});
</script>
@endpush
