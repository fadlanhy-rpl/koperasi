@extends('layouts.app')

@section('title', 'Penyesuaian Stok - Koperasi')
@section('page-title', 'Penyesuaian Stok Barang')
@section('page-subtitle', 'Sesuaikan jumlah stok fisik untuk: ' . $barang->nama_barang)

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
        background: linear-gradient(45deg, transparent 40%, rgba(245, 158, 11, 0.05) 50%, transparent 60%);
        transform: translateX(-100%);
        animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .form-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
        border: 2px solid rgba(245, 158, 11, 0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .form-input:focus {
        background: rgba(255, 255, 255, 1);
        border-color: #f59e0b;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1), 0 10px 25px -5px rgba(245, 158, 11, 0.2);
        transform: translateY(-2px);
    }
    
    .submit-btn {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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
        box-shadow: 0 15px 35px -5px rgba(245, 158, 11, 0.4);
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
<div class="max-w-2xl mx-auto" x-data="stockAdjustmentForm()" x-init="init()">
    <div class="form-container rounded-3xl shadow-2xl overflow-hidden fade-in">
        <!-- Enhanced Header -->
        <div class="form-header p-8 text-white">
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Penyesuaian Stok Barang</h1>
                    <p class="text-yellow-100 text-lg">{{ $barang->nama_barang }}</p>
                </div>
                <div class="w-20 h-20 bg-white/20 rounded-3xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Product Info Card -->
        <div class="p-8 pb-0">
            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-2xl p-6 border-2 border-yellow-200">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $barang->nama_barang }}</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Stok Tercatat:</span>
                                <span class="font-bold text-yellow-600 ml-2">{{ $barang->stok }} {{ $barang->satuan }}</span>
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
                                <span class="text-gray-500">Terakhir Update:</span>
                                <span class="font-semibold text-gray-700 ml-2">{{ $barang->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 p-4 bg-blue-100 border border-blue-300 rounded-xl">
                    <div class="flex items-start space-x-3 text-blue-800">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-sm">Penyesuaian Stok (Stock Opname)</p>
                            <p class="text-xs mt-1">Masukkan jumlah stok fisik hasil perhitungan manual untuk menyesuaikan dengan data sistem.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Form -->
        <div class="p-8 pt-4 relative z-10">
            <form action="{{ route('pengurus.stok.storePenyesuaianStok', $barang->id) }}" method="POST" class="space-y-6" @submit="handleSubmit">
                @csrf
                
                <!-- Physical Stock Input -->
                <div class="space-y-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <svg class="w-4 h-4 inline mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1z"/>
                        </svg>
                        Jumlah Stok Fisik (Hasil Hitungan)
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" 
                               name="stok_fisik" 
                               x-model="formData.stok_fisik"
                               min="0" 
                               step="1" 
                               required 
                               class="form-input w-full px-6 py-4 rounded-2xl text-lg font-medium"
                               placeholder="Masukkan hasil perhitungan stok fisik"
                               value="{{ old('stok_fisik', $barang->stok) }}">
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">
                            {{ $barang->satuan }}
                        </div>
                    </div>
                    @error('stok_fisik')
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
                        Keterangan Penyesuaian
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <textarea name="keterangan" 
                              x-model="formData.keterangan"
                              rows="4" 
                              required
                              class="form-input w-full px-6 py-4 rounded-2xl text-lg font-medium resize-none" 
                              placeholder="Contoh: Hasil stock opname bulanan, Koreksi kesalahan input, Penyesuaian berdasarkan audit fisik, dll.">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm flex items-center mt-2">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Adjustment Preview -->
                <div x-show="formData.stok_fisik !== '' && formData.stok_fisik !== '{{ $barang->stok }}'" class="rounded-2xl p-6 border-2"
                     :class="(parseInt(formData.stok_fisik || 0) - {{ $barang->stok }}) > 0 ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'">
                    <h4 class="font-bold mb-3 flex items-center"
                        :class="(parseInt(formData.stok_fisik || 0) - {{ $barang->stok }}) > 0 ? 'text-green-800' : 'text-red-800'">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        Preview Penyesuaian Stok
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Stok Tercatat:</span>
                            <div class="font-bold text-gray-800 text-lg">{{ $barang->stok }} {{ $barang->satuan }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Stok Fisik:</span>
                            <div class="font-bold text-lg" 
                                 :class="parseInt(formData.stok_fisik || 0) > {{ $barang->stok }} ? 'text-green-600' : (parseInt(formData.stok_fisik || 0) < {{ $barang->stok }} ? 'text-red-600' : 'text-gray-800')"
                                 x-text="`${formData.stok_fisik || 0} {{ $barang->satuan }}`"></div>
                        </div>
                        <div>
                            <span class="text-gray-600">Selisih:</span>
                            <div class="font-bold text-lg flex items-center" 
                                 :class="(parseInt(formData.stok_fisik || 0) - {{ $barang->stok }}) > 0 ? 'text-green-600' : ((parseInt(formData.stok_fisik || 0) - {{ $barang->stok }}) < 0 ? 'text-red-600' : 'text-gray-800')">
                                <svg x-show="(parseInt(formData.stok_fisik || 0) - {{ $barang->stok }}) > 0" class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                                </svg>
                                <svg x-show="(parseInt(formData.stok_fisik || 0) - {{ $barang->stok }}) < 0" class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                </svg>
                                <span x-text="`${Math.abs(parseInt(formData.stok_fisik || 0) - {{ $barang->stok }})} {{ $barang->satuan }}`"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div x-show="(parseInt(formData.stok_fisik || 0) - {{ $barang->stok }}) !== 0" class="mt-4 p-3 rounded-xl"
                         :class="(parseInt(formData.stok_fisik || 0) - {{ $barang->stok }}) > 0 ? 'bg-green-100 border border-green-300' : 'bg-red-100 border border-red-300'">
                        <div class="flex items-center space-x-2"
                             :class="(parseInt(formData.stok_fisik || 0) - {{ $barang->stok }}) > 0 ? 'text-green-800' : 'text-red-800'">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                            </svg>
                            <span class="text-sm font-medium" 
                                  x-text="(parseInt(formData.stok_fisik || 0) - {{ $barang->stok }}) > 0 ? 'Stok akan bertambah dari hasil penyesuaian' : 'Stok akan berkurang dari hasil penyesuaian'"></span>
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
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                <span>Simpan Penyesuaian</span>
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
function stockAdjustmentForm() {
    return {
        formData: {
            stok_fisik: '{{ old('stok_fisik', $barang->stok) }}',
            keterangan: '{{ old('keterangan') }}'
        },
        isSubmitting: false,
        
        init() {
            // Auto-focus on physical stock input
            setTimeout(() => {
                const stockInput = document.querySelector('input[name="stok_fisik"]');
                if (stockInput) {
                    stockInput.focus();
                    stockInput.select();
                }
            }, 500);
        },
        
        handleSubmit(event) {
            if (this.isSubmitting) {
                event.preventDefault();
                return false;
            }
            
            // Validate physical stock
            if (this.formData.stok_fisik === '' || this.formData.stok_fisik < 0) {
                this.showNotification('Jumlah stok fisik harus diisi dan tidak boleh negatif', 'error');
                event.preventDefault();
                return false;
            }
            
            // Validate notes
            if (!this.formData.keterangan || this.formData.keterangan.trim() === '') {
                this.showNotification('Keterangan penyesuaian harus diisi', 'error');
                event.preventDefault();
                return false;
            }
            
            // Check if there's actually a change
            if (parseInt(this.formData.stok_fisik) === {{ $barang->stok }}) {
                if (!confirm('Stok fisik sama dengan stok tercatat. Apakah Anda yakin ingin melanjutkan penyesuaian?')) {
                    event.preventDefault();
                    return false;
                }
            }
            
            this.isSubmitting = true;
            this.showNotification('Menyimpan penyesuaian stok...', 'info');
            
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
