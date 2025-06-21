@extends('layouts.app')

@section('title', 'Tambah Pengguna - Koperasi')
@section('page-title', 'Tambah Pengguna Baru')
@section('page-subtitle', 'Daftarkan pengguna baru ke dalam sistem koperasi')

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
    
    .password-strength {
        margin-top: 0.5rem;
        padding: 0.75rem;
        border-radius: 8px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
    }
    
    .strength-bar {
        height: 4px;
        border-radius: 2px;
        background: #e5e7eb;
        margin: 0.5rem 0;
        overflow: hidden;
    }
    
    .strength-fill {
        height: 100%;
        transition: all 0.3s ease;
        border-radius: 2px;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .success-message {
        color: #10b981;
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
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto fade-in">
    <div class="form-container">
        <div class="form-header">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Formulir Pengguna Baru</h2>
                    <p class="text-indigo-100">Lengkapi informasi di bawah untuk mendaftarkan pengguna baru</p>
                </div>
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="p-8">
            <form action="{{ route('admin.manajemen-pengguna.store') }}" method="POST" id="userForm" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="form-group">
                        <label for="name" class="form-label required">Nama Lengkap</label>
                        <div class="relative">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
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
                                   value="{{ old('email') }}"
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
                                   value="{{ old('nomor_anggota') }}"
                                   class="form-input with-icon @error('nomor_anggota') border-red-500 @enderror" 
                                   placeholder="Contoh: KOP001 (opsional)">
                        </div>
                        @error('nomor_anggota')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                        <p class="text-sm text-gray-600 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Kosongkan jika akan diisi otomatis oleh sistem
                        </p>
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
                                <option value="">Pilih peran pengguna</option>
                                @foreach($rolesForForm as $value => $label)
                                    <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
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
                    
                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label required">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-input with-icon @error('password') border-red-500 @enderror" 
                                   placeholder="Minimal 8 karakter"
                                   required>
                            <button type="button" 
                                    id="togglePassword" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="passwordStrength" class="password-strength hidden">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium">Kekuatan Password:</span>
                                <span id="strengthText" class="text-sm"></span>
                            </div>
                            <div class="strength-bar">
                                <div id="strengthFill" class="strength-fill"></div>
                            </div>
                            <ul class="text-xs text-gray-600 mt-2 space-y-1">
                                <li id="length" class="flex items-center">
                                    <i class="fas fa-times text-red-500 mr-2"></i>
                                    Minimal 8 karakter
                                </li>
                                <li id="uppercase" class="flex items-center">
                                    <i class="fas fa-times text-red-500 mr-2"></i>
                                    Huruf besar
                                </li>
                                <li id="lowercase" class="flex items-center">
                                    <i class="fas fa-times text-red-500 mr-2"></i>
                                    Huruf kecil
                                </li>
                                <li id="number" class="flex items-center">
                                    <i class="fas fa-times text-red-500 mr-2"></i>
                                    Angka
                                </li>
                                <li id="symbol" class="flex items-center">
                                    <i class="fas fa-times text-red-500 mr-2"></i>
                                    Simbol
                                </li>
                            </ul>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Konfirmasi Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label required">Konfirmasi Password</label>
                        <div class="relative">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="form-input with-icon @error('password_confirmation') border-red-500 @enderror" 
                                   placeholder="Ulangi password"
                                   required>
                            <button type="button" 
                                    id="togglePasswordConfirm" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="passwordMatch" class="mt-2 hidden"></div>
                        @error('password_confirmation')
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
                                <option value="{{ $value }}" {{ old('status', 'active') == $value ? 'selected' : '' }}>
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
                
                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.manajemen-pengguna.index') }}">
                        <button type="button" class="btn-secondary w-full sm:w-auto">
                            <i class="fas fa-arrow-left"></i>
                            Batal
                        </button>
                    </a>
                    <button type="submit" class="btn-primary w-full sm:w-auto" id="submitBtn">
                        <i class="fas fa-save"></i>
                        Simpan Pengguna
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
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const passwordStrength = document.getElementById('passwordStrength');
    const strengthFill = document.getElementById('strengthFill');
    const strengthText = document.getElementById('strengthText');
    const passwordMatch = document.getElementById('passwordMatch');
    const submitBtn = document.getElementById('submitBtn');
    
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        if (password.length > 0) {
            passwordStrength.classList.remove('hidden');
            
            const checks = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password),
                symbol: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };
            
            // Update visual indicators
            Object.keys(checks).forEach(check => {
                const element = document.getElementById(check);
                const icon = element.querySelector('i');
                if (checks[check]) {
                    icon.className = 'fas fa-check text-green-500 mr-2';
                    element.classList.add('text-green-600');
                    element.classList.remove('text-gray-600');
                } else {
                    icon.className = 'fas fa-times text-red-500 mr-2';
                    element.classList.add('text-gray-600');
                    element.classList.remove('text-green-600');
                }
            });
            
            // Calculate strength
            const score = Object.values(checks).filter(Boolean).length;
            const percentage = (score / 5) * 100;
            
            strengthFill.style.width = percentage + '%';
            
            if (score <= 2) {
                strengthFill.style.background = '#ef4444';
                strengthText.textContent = 'Lemah';
                strengthText.className = 'text-sm text-red-600';
            } else if (score <= 3) {
                strengthFill.style.background = '#f59e0b';
                strengthText.textContent = 'Sedang';
                strengthText.className = 'text-sm text-yellow-600';
            } else if (score <= 4) {
                strengthFill.style.background = '#3b82f6';
                strengthText.textContent = 'Baik';
                strengthText.className = 'text-sm text-blue-600';
            } else {
                strengthFill.style.background = '#10b981';
                strengthText.textContent = 'Sangat Kuat';
                strengthText.className = 'text-sm text-green-600';
            }
        } else {
            passwordStrength.classList.add('hidden');
        }
    });
    
    // Password confirmation checker
    passwordConfirmInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;
        
        if (confirmPassword.length > 0) {
            passwordMatch.classList.remove('hidden');
            
            if (password === confirmPassword) {
                passwordMatch.innerHTML = `
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        Password cocok
                    </div>
                `;
            } else {
                passwordMatch.innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        Password tidak cocok
                    </div>
                `;
            }
        } else {
            passwordMatch.classList.add('hidden');
        }
    });
    
    // Form submission with loading state
    document.getElementById('userForm').addEventListener('submit', function() {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <i class="fas fa-spinner fa-spin"></i>
            Menyimpan...
        `;
    });
});
</script>
@endpush
