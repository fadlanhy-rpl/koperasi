@extends('layouts.app')

@section('title', 'Edit Pengguna - Koperasi')
@section('page-title', 'Edit Pengguna')
@section('page-subtitle', 'Perbarui informasi pengguna sistem')

@push('styles')
<style>
    .form-container {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
    }
    
    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px 20px 0 0;
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .form-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    
    .current-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, 0.3);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
        color: white;
        position: relative;
        z-index: 10;
    }
    
    .current-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    
    .form-group {
        position: relative;
        margin-bottom: 1.5rem;
    }
    
    .form-input {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
        font-size: 1rem;
    }
    
    .form-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
        background: white;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
    }
    
    .form-label.required::after {
        content: ' *';
        color: #ef4444;
    }
    
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        z-index: 10;
    }
    
    .form-input.with-icon {
        padding-left: 3rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        padding: 1rem 2rem;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    }
    
    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        padding: 1rem 2rem;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-secondary:hover {
        background: #e5e7eb;
        transform: translateY(-1px);
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .password-section {
        background: rgba(249, 250, 251, 0.8);
        border-radius: 16px;
        padding: 1.5rem;
        border: 2px dashed #d1d5db;
        margin-top: 1rem;
    }
    
    .password-toggle {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: none;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .password-toggle:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto fade-in">
    <div class="form-container">
        <div class="form-header">
            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-6">
                    <div class="current-avatar">
                        @if($user->profile_photo_url && !str_contains($user->profile_photo_url, 'placeholder_avatar.png'))
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-2">Edit Pengguna</h2>
                        <p class="text-indigo-100">Perbarui informasi untuk {{ $user->name }}</p>
                    </div>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-edit text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="p-8">
            <form action="{{ route('admin.manajemen-pengguna.update', $user->id) }}" method="POST" id="editUserForm" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label for="name" class="form-label required">Nama Lengkap</label>
                        <div class="relative">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="form-input with-icon @error('name') border-red-500 @enderror" 
                                   placeholder="Masukkan nama lengkap"
                                   required>
                        </div>
                        @error('name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label required">Alamat Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="form-input with-icon @error('email') border-red-500 @enderror" 
                                   placeholder="contoh@email.com"
                                   required>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Nomor Anggota -->
                    <div class="form-group">
                        <label for="nomor_anggota" class="form-label">Nomor Anggota</label>
                        <div class="relative">
                            <i class="fas fa-id-badge input-icon"></i>
                            <input type="text" 
                                   id="nomor_anggota" 
                                   name="nomor_anggota" 
                                   value="{{ old('nomor_anggota', $user->nomor_anggota) }}"
                                   class="form-input with-icon @error('nomor_anggota') border-red-500 @enderror" 
                                   placeholder="Contoh: KOP001">
                        </div>
                        @error('nomor_anggota')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Role -->
                    <div class="form-group">
                        <label for="role" class="form-label required">Peran (Role)</label>
                        <div class="relative">
                            <i class="fas fa-user-tag input-icon"></i>
                            <select id="role" 
                                    name="role" 
                                    class="form-input with-icon @error('role') border-red-500 @enderror" 
                                    required>
                                @foreach($rolesForForm as $value => $label)
                                    <option value="{{ $value }}" {{ old('role', $user->role) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('role')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                
                <!-- Status -->
                <div class="form-group">
                    <label for="status" class="form-label required">Status Akun</label>
                    <div class="relative">
                        <i class="fas fa-toggle-on input-icon"></i>
                        <select id="status" 
                                name="status" 
                                class="form-input with-icon @error('status') border-red-500 @enderror" 
                                required>
                            @foreach($statusesForForm as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $user->status) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('status')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Password Section -->
                <div class="password-section">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-key mr-2 text-orange-500"></i>
                            Ubah Password
                        </h4>
                        <button type="button" id="togglePasswordSection" class="password-toggle">
                            <i class="fas fa-eye mr-1"></i>
                            Tampilkan Form Password
                        </button>
                    </div>
                    
                    <div id="passwordFields" class="hidden space-y-4">
                        <p class="text-sm text-gray-600 mb-4">
                            <i class="fas fa-info-circle mr-1"></i>
                            Kosongkan jika tidak ingin mengubah password
                        </p>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="password" class="form-label">Password Baru</label>
                                <div class="relative">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="form-input with-icon @error('password') border-red-500 @enderror" 
                                           placeholder="Minimal 8 karakter">
                                </div>
                                @error('password')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <div class="relative">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           class="form-input with-icon @error('password_confirmation') border-red-500 @enderror" 
                                           placeholder="Ulangi password baru">
                                </div>
                                @error('password_confirmation')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.manajemen-pengguna.show', $user->id) }}">
                        <button type="button" class="btn-secondary w-full sm:w-auto">
                            <i class="fas fa-arrow-left"></i>
                            Batal
                        </button>
                    </a>
                    <button type="submit" class="btn-primary w-full sm:w-auto" id="submitBtn">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePasswordBtn = document.getElementById('togglePasswordSection');
    const passwordFields = document.getElementById('passwordFields');
    const submitBtn = document.getElementById('submitBtn');
    
    // Toggle password section
    togglePasswordBtn.addEventListener('click', function() {
        const isHidden = passwordFields.classList.contains('hidden');
        
        if (isHidden) {
            passwordFields.classList.remove('hidden');
            this.innerHTML = '<i class="fas fa-eye-slash mr-1"></i> Sembunyikan Form Password';
        } else {
            passwordFields.classList.add('hidden');
            this.innerHTML = '<i class="fas fa-eye mr-1"></i> Tampilkan Form Password';
            // Clear password fields when hiding
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
        }
    });
    
    // Form submission with loading state
    document.getElementById('editUserForm').addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <i class="fas fa-spinner fa-spin"></i>
            Menyimpan...
        `;
    });
    
    // Password confirmation validation
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    
    function validatePasswordMatch() {
        if (passwordInput.value && passwordConfirmInput.value) {
            if (passwordInput.value !== passwordConfirmInput.value) {
                passwordConfirmInput.setCustomValidity('Password tidak cocok');
            } else {
                passwordConfirmInput.setCustomValidity('');
            }
        }
    }
    
    passwordInput.addEventListener('input', validatePasswordMatch);
    passwordConfirmInput.addEventListener('input', validatePasswordMatch);
});
</script>
@endpush
