@extends('layouts.app')

@section('title', 'Catat Barang Keluar - Koperasi')
@section('page-title', 'Pencatatan Barang Keluar')
@section('page-subtitle', 'Kurangi stok untuk: ' . $barang->nama_barang)

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
        background: linear-gradient(45deg, transparent 40%, rgba(239, 68, 68, 0.05) 50%, transparent 60%);
        transform: translateX(-100%);
        animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .form-header {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
        border: 2px solid rgba(239, 68, 68, 0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .form-input:focus {
        background: rgba(255, 255, 255, 1);
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1), 0 10px 25px -5px rgba(239, 68, 68, 0.2);
        transform: translateY(-2px);
    }
    
    .submit-btn {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
        box-shadow: 0 15px 35px -5px rgba(239, 68, 68, 0.4);
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
<div class="max-w-2xl mx-auto" x-data="stockOutForm()" x-init="init()">
    <div class="form-container rounded-3xl shadow-2xl overflow-hidden fade-in">
        <!-- Enhanced Header -->
        <div class="form-header p-8 text-white">
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Pencatatan Barang Keluar</h1>
                    <p class="text-red-100 text-lg">{{ $barang->nama_barang }}</p>
                </div>
                <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Product Info Card -->
        <div class="p-8 pb-0">
            <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-2xl p-6 border-2 border-red-200">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $barang->nama_barang }}</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Stok Saat Ini:</span>
                                <span class="font-bold {{ $barang->stok <= 10 ? 'text-red-600' : 'text-green-600' }} ml-2">
                                    {{ $barang->stok }} {{ $barang->satuan }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500">Unit Usaha:</span>
                                <span class="font-semibold text-gray-700 ml-2">{{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Kode Barang:</span>
                                <span class="font-semibold text-gray-700 ml-2">{{ $barang->kode_barang ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Harga Jual:</span>
                                <span class="font-semibold text-gray-700 ml-2">@rupiah($barang->harga_jual ?? 0)</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($barang->stok <= 10)
                    <div class="mt-4 p-3 bg-yellow-100 border border-yellow-300 rounded-xl">
                        <div class="flex items-center space-x-2 text-yellow-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            <span class="font-medium">Peringatan: Stok barang ini sudah rendah!</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Enhanced Form -->
        <div class="p-8 pt-4 relative z-10">
            <form action="{{ route('pengurus.stok.storeBarangKeluar', $barang->id) }}" method="POST" class="space-y-6" @submit="handleSubmit">
                @csrf
                
                <!-- Quantity Input -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                        </svg>
                        Jumlah Barang Keluar
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                               name="jumlah" 
                               x-model="formData.jumlah"
                               min="1" 
                               max="{{ $barang->stok }}"
                               step="1" 
                               required 
                               class="form-input w-full px-6 py-4 rounded-2xl text-lg font-medium"
                               placeholder="Masukkan jumlah barang yang keluar">
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">
                            {{ $barang->satuan }}
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Maksimal: {{ $barang->stok }} {{ $barang->satuan }}</p>
                    @error('jumlah')
                        <p class="text-red-500 text-sm flex items-center mt-2">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Type of Exit -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3z"/>
                        </svg>
                        Tipe Barang Keluar
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-300 transition-colors duration-200"
                               :class="{ 'border-red-500 bg-red-50': formData.tipe_keluar === 'rusak' }">
                            <input type="radio" name="tipe_keluar" value="rusak" x-model="formData.tipe_keluar" class="sr-only">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center"
                                     :class="{ 'border-red-500 bg-red-500': formData.tipe_keluar === 'rusak' }">
                                    <div class="w-2 h-2 bg-white rounded-full" x-show="formData.tipe_keluar === 'rusak'"></div>
                                </div>
                                <span class="font-medium">Barang Rusak</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-300 transition-colors duration-200"
                               :class="{ 'border-red-500 bg-red-50': formData.tipe_keluar === 'hilang' }">
                            <input type="radio" name="tipe_keluar" value="hilang" x-model="formData.tipe_keluar" class="sr-only">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center"
                                     :class="{ 'border-red-500 bg-red-500': formData.tipe_keluar === 'hilang' }">
                                    <div class="w-2 h-2 bg-white rounded-full" x-show="formData.tipe_keluar === 'hilang'"></div>
                                </div>
                                <span class="font-medium">Barang Hilang</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-300 transition-colors duration-200"
                               :class="{ 'border-red-500 bg-red-50': formData.tipe_keluar === 'digunakan' }">
                            <input type="radio" name="tipe_keluar" value="digunakan" x-model="formData.tipe_keluar" class="sr-only">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center"
                                     :class="{ 'border-red-500 bg-red-500': formData.tipe_keluar === 'digunakan' }">
                                    <div class="w-2 h-2 bg-white rounded-full" x-show="formData.tipe_keluar === 'digunakan'"></div>
                                </div>
                                <span class="font-medium">Digunakan</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-red-300 transition-colors duration-200"
                               :class="{ 'border-red-500 bg-red-50': formData.tipe_keluar === 'lainnya' }">
                            <input type="radio" name="tipe_keluar" value="lainnya" x-model="formData.tipe_keluar" class="sr-only">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 border-2 border-gray-300 rounded-full flex items-center justify-center"
                                     :class="{ 'border-red-500 bg-red-500': formData.tipe_keluar === 'lainnya' }">
                                    <div class="w-2 h-2 bg-white rounded-full" x-show="formData.tipe_keluar === 'lainnya'"></div>
                                </div>
                                <span class="font-medium">Lainnya</span>
                            </div>
                        </label>
                    </div>
                    @error('tipe_keluar')
                        <p class="text-red-500 text-sm flex items-center mt-2">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"/>
                        </svg>
                        Keterangan
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea name="keterangan" 
                              x-model="formData.keterangan"
                              rows="4" 
                              required
                              class="form-input w-full px-6 py-4 rounded-2xl text-lg font-medium resize-none" 
                              placeholder="Jelaskan alasan barang keluar, kondisi barang, atau informasi penting lainnya..."></textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm flex items-center mt-2">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Stock Preview -->
                <div x-show="formData.jumlah > 0" class="bg-yellow-50 rounded-2xl p-6 border-2 border-yellow-200">
                    <h4 class="font-bold text-yellow-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                        </svg>
                        Preview Stok Setelah Pengurangan
                    </h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-yellow-600">Stok Sebelum:</span>
                            <span class="font-bold text-gray-800 ml-2">{{ $barang->stok }} {{ $barang->satuan }}</span>
                        </div>
                        <div>
                            <span class="text-yellow-600">Stok Setelah:</span>
                            <span class="font-bold ml-2" 
                                  :class="({{ $barang->stok }} - parseInt(formData.jumlah || 0)) <= 10 ? 'text-red-600' : 'text-green-600'"
                                  x-text="({{ $barang->stok }} - parseInt(formData.jumlah || 0)) + ' {{ $barang->satuan }}'"></span>
                        </div>
                    </div>
                    <div x-show="({{ $barang->stok }} - parseInt(formData.jumlah || 0)) <= 10" class="mt-3 p-3 bg-red-100 border border-red-300 rounded-xl">
                        <div class="flex items-center space-x-2 text-red-800">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            <span class="text-sm font-medium">Peringatan: Stok akan menjadi rendah setelah pengurangan ini!</span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-6 pt-8">
                    <a href="{{ route('pengurus.stok.index') }}" 
                       class="px-8 py-4 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-2xl transition-colors duration-200 font-semibold text-center">
                        Batal
                    </a>
                    <button type="submit" 
                            :disabled="isSubmitting"
                            class="submit-btn px-8 py-4 rounded-2xl font-semibold transition-all duration-300 flex items-center justify-center space-x-3 min-w-[200px]">
                        <template x-if="!isSubmitting">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                </svg>
                                <span>Kurangi Stok</span>
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
function stockOutForm() {
    return {
        formData: {
            jumlah: '',
            tipe_keluar: '',
            keterangan: ''
        },
        isSubmitting: false,
        
        init() {
            // Auto-focus on quantity input
            setTimeout(() => {
                const quantityInput = document.querySelector('input[name="jumlah"]');
                if (quantityInput) quantityInput.focus();
            }, 500);
        },
        
        handleSubmit(event) {
            if (this.isSubmitting) {
                event.preventDefault();
                return false;
            }
            
            // Validate quantity
            if (!this.formData.jumlah || this.formData.jumlah < 1) {
                this.showNotification('Jumlah barang keluar harus diisi dan minimal 1', 'error');
                event.preventDefault();
                return false;
            }
            
            if (this.formData.jumlah > {{ $barang->stok }}) {
                this.showNotification('Jumlah barang keluar tidak boleh melebihi stok saat ini', 'error');
                event.preventDefault();
                return false;
            }
            
            // Validate type
            if (!this.formData.tipe_keluar) {
                this.showNotification('Pilih tipe barang keluar', 'error');
                event.preventDefault();
                return false;
            }
            
            // Validate notes
            if (!this.formData.keterangan || this.formData.keterangan.trim() === '') {
                this.showNotification('Keterangan harus diisi', 'error');
                event.preventDefault();
                return false;
            }
            
            this.isSubmitting = true;
            this.showNotification('Menyimpan data barang keluar...', 'info');
            
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
