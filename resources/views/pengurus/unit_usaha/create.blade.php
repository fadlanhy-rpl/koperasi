@extends('layouts.app')

@section('title', 'Tambah Unit Usaha - Koperasi')
@section('page-title', 'Tambah Unit Usaha Baru')
@section('page-subtitle', 'Buat entri untuk unit bisnis koperasi')

@push('styles')
<style>
    .form-container {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(249, 250, 251, 0.95) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .form-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 40%, rgba(16, 185, 129, 0.05) 50%, transparent 60%);
        transform: translateX(-100%);
        animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .form-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    }
    
    .form-input {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(16, 185, 129, 0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    
    .form-input:focus {
        background: rgba(255, 255, 255, 1);
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1), 0 10px 25px -5px rgba(16, 185, 129, 0.2);
        transform: translateY(-2px);
    }
    
    .form-label {
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        font-size: 15px;
    }
    
    .form-label svg {
        margin-right: 8px;
        color: #10b981;
    }
    
    .submit-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        color: white;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }
    
    .submit-btn:hover::before {
        left: 100%;
    }
    
    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px -5px rgba(16, 185, 129, 0.4);
    }
    
    .cancel-btn {
        background: rgba(107, 114, 128, 0.1);
        border: 2px solid rgba(107, 114, 128, 0.2);
        color: #6b7280;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .cancel-btn:hover {
        background: rgba(107, 114, 128, 0.1);
        border-color: #6b7280;
        color: #374151;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -8px rgba(107, 114, 128, 0.3);
    }
    
    .floating-label {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.9);
        padding: 0 8px;
        color: #6b7280;
        font-size: 16px;
        font-weight: 500;
        pointer-events: none;
        transition: all 0.3s ease;
        border-radius: 4px;
    }
    
    .form-input:focus + .floating-label,
    .form-input:not(:placeholder-shown) + .floating-label {
        top: 0;
        font-size: 12px;
        color: #10b981;
        font-weight: 600;
    }
    
    .character-count {
        position: absolute;
        bottom: 12px;
        right: 16px;
        font-size: 12px;
        color: #6b7280;
        background: rgba(255, 255, 255, 0.9);
        padding: 4px 8px;
        border-radius: 8px;
        backdrop-filter: blur(10px);
    }
    
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .bounce-in {
        animation: bounceIn 0.8s ease-out;
    }
    
    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3); }
        50% { opacity: 1; transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { opacity: 1; transform: scale(1); }
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto" x-data="createUnitForm()" x-init="init()">
    <div class="form-container rounded-3xl shadow-2xl overflow-hidden fade-in">
        <!-- Enhanced Header -->
        <div class="form-header p-8 text-white">
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Tambah Unit Usaha Baru</h1>
                    <p class="text-emerald-100 text-lg">Buat entri untuk unit bisnis koperasi</p>
                </div>
                <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Enhanced Form -->
        <div class="p-8 relative z-10">
            <form action="{{ route('pengurus.unit-usaha.store') }}" method="POST" class="space-y-8" @submit="handleSubmit">
                @csrf
                
                <!-- Unit Name Input -->
                <div class="space-y-2 bounce-in" style="animation-delay: 0.1s">
                    <label class="form-label">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"/>
                        </svg>
                        Nama Unit Usaha
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                               name="nama_unit_usaha" 
                               x-model="formData.nama_unit_usaha"
                               placeholder=" "
                               required 
                               maxlength="255"
                               class="form-input w-full px-6 py-4 rounded-2xl text-lg font-medium placeholder-transparent">
                        <label class="floating-label">Contoh: Kantin Sekolah, Toko ATK</label>
                        <div class="character-count" x-text="`${formData.nama_unit_usaha.length}/255`"></div>
                    </div>
                    @error('nama_unit_usaha')
                        <p class="text-red-500 text-sm flex items-center mt-2">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Description Input -->
                <div class="space-y-2 bounce-in" style="animation-delay: 0.2s">
                    <label class="form-label">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"/>
                        </svg>
                        Deskripsi (Opsional)
                    </label>
                    <div class="relative">
                        <textarea name="deskripsi" 
                                  x-model="formData.deskripsi"
                                  rows="5" 
                                  maxlength="1000"
                                  class="form-input w-full px-6 py-4 rounded-2xl text-lg font-medium resize-none" 
                                  placeholder="Jelaskan tentang unit usaha ini, produk yang dijual, target pasar, atau informasi penting lainnya..."></textarea>
                        <div class="character-count" x-text="`${formData.deskripsi.length}/1000`"></div>
                    </div>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm flex items-center mt-2">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-6 pt-8 bounce-in" style="animation-delay: 0.3s">
                    <a href="{{ route('pengurus.unit-usaha.index') }}" 
                       class="cancel-btn px-8 py-4 rounded-2xl text-center font-semibold transition-all duration-300 flex items-center justify-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Batal</span>
                    </a>
                    <button type="submit" 
                            :disabled="isSubmitting"
                            class="submit-btn px-8 py-4 rounded-2xl font-semibold transition-all duration-300 flex items-center justify-center space-x-3 min-w-[200px]">
                        <template x-if="!isSubmitting">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                <span>Simpan Unit Usaha</span>
                            </div>
                        </template>
                        <template x-if="isSubmitting">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                                <span>Menyimpan...</span>
                            </div>
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function createUnitForm() {
    return {
        formData: {
            nama_unit_usaha: '{{ old('nama_unit_usaha') }}',
            deskripsi: '{{ old('deskripsi') }}'
        },
        isSubmitting: false,
        
        init() {
            // Auto-focus on first input
            setTimeout(() => {
                const firstInput = document.querySelector('input[name="nama_unit_usaha"]');
                if (firstInput) firstInput.focus();
            }, 500);
            
            // Add keyboard shortcuts
            document.addEventListener('keydown', (event) => {
                // Ctrl/Cmd + Enter to submit
                if ((event.ctrlKey || event.metaKey) && event.key === 'Enter') {
                    event.preventDefault();
                    this.handleSubmit();
                }
                
                // Escape to cancel
                if (event.key === 'Escape') {
                    const cancelBtn = document.querySelector('.cancel-btn');
                    if (cancelBtn) cancelBtn.click();
                }
            });
        },
        
        handleSubmit(event) {
            if (this.isSubmitting) {
                if (event) event.preventDefault();
                return false;
            }
            
            // Validate required fields
            if (!this.formData.nama_unit_usaha.trim()) {
                this.showNotification('Nama unit usaha harus diisi', 'error');
                if (event) event.preventDefault();
                return false;
            }
            
            this.isSubmitting = true;
            this.showNotification('Menyimpan unit usaha...', 'info');
            
            // Let the form submit naturally
            return true;
        },
        
        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-6 right-6 z-50 max-w-sm p-4 rounded-2xl shadow-2xl transform translate-x-full transition-transform duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            
            const iconPath = type === 'success' ? 
                'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' :
                type === 'error' ?
                'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' :
                'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
            
            notification.innerHTML = `
                <div class='flex items-center space-x-3'>
                    <svg class='w-6 h-6 flex-shrink-0' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='${iconPath}'/>
                    </svg>
                    <p class='font-medium'>${message}</p>
                </div>
            `;
            
            document.body.appendChild(notification);
            setTimeout(() => notification.classList.remove('translate-x-full'), 100);
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    }
}
</script>
@endsection
