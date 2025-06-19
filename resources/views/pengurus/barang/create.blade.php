@extends('layouts.app')

@section('title', 'Tambah Barang Baru - Koperasi')
@section('page-title', 'Tambah Barang Baru')
@section('page-subtitle', 'Masukkan detail barang untuk unit usaha koperasi')

@push('styles')
<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .slide-in {
        animation: slideIn 0.5s ease-out;
    }
    @keyframes slideIn {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    @keyframes fadeInUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .gradient-border {
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(45deg, #3b82f6, #8b5cf6, #06b6d4) border-box;
        border: 2px solid transparent;
    }

    /* Enhanced image upload area */
    .image-upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .image-upload-area:hover {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        transform: scale(1.02);
    }
    .image-upload-area.has-image {
        border-color: #10b981;
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    }

    .image-preview-container {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        overflow: hidden;
        border: 3px solid #e5e7eb;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
    }
    .image-preview-container:hover {
        border-color: #3b82f6;
        transform: scale(1.05);
    }
    .image-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .placeholder-icon {
        font-size: 2rem;
        color: #9ca3af;
    }

    /* Cropper modal styling */
    .img-container-cropper {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        margin-bottom: 1rem;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    #imageToCropBarang {
        display: block;
        max-width: 100%;
        max-height: 100%;
    }
    .preview-barang-container {
        width: 150px;
        height: 150px;
        overflow: hidden;
        border: 3px solid #3b82f6;
        border-radius: 12px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        background: white;
        margin-top: 0.5rem;
    }

    .upload-button-barang {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .upload-button-barang:hover {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .modal-overlay {
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(8px);
    }
    .modal-content {
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 600px;
        width: 95%;
        max-height: 90vh;
        overflow-y: auto;
    }

    input[type="file"].visually-hidden {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }

    /* Enhanced form styling */
    .form-group {
        position: relative;
    }
    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }
    .form-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: white;
    }
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    /* Select2 enhanced styling */
    .select2-container--default .select2-selection--single {
        height: 52px !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 12px !important;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        padding-top: 8px !important;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }
    .select2-dropdown {
        border: 2px solid #3b82f6 !important;
        border-radius: 12px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 card-hover gradient-border slide-in">
            <!-- Header -->
            <div class="p-8 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-3xl">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-500 p-3 rounded-xl">
                        <i class="fas fa-box-open text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Tambah Barang Baru</h1>
                        <p class="text-gray-600 mt-1">Masukkan detail barang untuk unit usaha koperasi</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-8">
                <form id="createBarangForm" action="{{ route('pengurus.barang.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" data-validate>
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Informasi Dasar
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-tag text-blue-500 mr-2"></i>Nama Barang
                                </label>
                                <input type="text" name="nama_barang" class="form-input" placeholder="Contoh: Pulpen Pilot G2 Hitam" value="{{ old('nama_barang') }}" required>
                                @error('nama_barang')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-barcode text-green-500 mr-2"></i>Kode Barang
                                </label>
                                <input type="text" name="kode_barang" class="form-input" placeholder="Otomatis jika kosong" value="{{ old('kode_barang') }}">
                                <p class="text-xs text-gray-500 mt-1">Jika dikosongkan, sistem akan membuat kode unik</p>
                                @error('kode_barang')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-building text-purple-500 mr-2"></i>Unit Usaha
                                </label>
                                <select name="unit_usaha_id" class="form-input select2-basic" required>
                                    <option value="">Pilih unit usaha</option>
                                    @foreach($unitUsahas as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_usaha_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->nama_unit_usaha }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('unit_usaha_id')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-weight text-orange-500 mr-2"></i>Satuan Barang
                                </label>
                                <select name="satuan" class="form-input select2-basic" required>
                                    <option value="">Pilih satuan</option>
                                    @foreach($satuans as $satuan)
                                        <option value="{{ $satuan }}" {{ old('satuan') == $satuan ? 'selected' : '' }}>
                                            {{ ucfirst($satuan) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('satuan')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Stock -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>
                            Harga & Stok
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-shopping-cart text-red-500 mr-2"></i>Harga Beli Satuan
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                    <input type="number" name="harga_beli" class="form-input pl-12" placeholder="0" value="{{ old('harga_beli') }}" required min="0" step="any">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Harga modal pembelian</p>
                                @error('harga_beli')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-tag text-blue-500 mr-2"></i>Harga Jual Satuan
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                    <input type="number" name="harga_jual" class="form-input pl-12" placeholder="0" value="{{ old('harga_jual') }}" required min="0" step="any">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Harga jual ke pembeli</p>
                                @error('harga_jual')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-boxes text-purple-500 mr-2"></i>Stok Awal
                                </label>
                                <input type="number" name="stok" class="form-input" placeholder="0" value="{{ old('stok') }}" required min="0" step="1">
                                <p class="text-xs text-gray-500 mt-1">Jumlah stok awal</p>
                                @error('stok')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-align-left text-yellow-500 mr-2"></i>
                            Deskripsi Barang
                        </h3>
                        
                        <div class="form-group">
                            <label for="deskripsi_barang_create" class="form-label">Deskripsi (Opsional)</label>
                            <textarea id="deskripsi_barang_create" name="deskripsi" rows="4" class="form-input resize-none" placeholder="Jelaskan detail barang, spesifikasi, atau informasi penting lainnya...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-camera text-indigo-500 mr-2"></i>
                            Gambar Barang
                        </h3>
                        
                        <div class="flex flex-col md:flex-row items-start space-y-4 md:space-y-0 md:space-x-6">
                            <div class="image-preview-container" id="gambarBarangPreviewContainer">
                                <img id="gambarBarangPreview" src="{{ asset('img/placeholder_barang.png') }}" alt="Pratinjau Gambar Barang" class="{{ old('cropped_gambar_barang') ? '' : 'hidden' }}">
                                <i class="fas fa-image placeholder-icon {{ old('cropped_gambar_barang') ? 'hidden' : '' }}"></i>
                            </div>
                            
                            <div class="flex-1">
                                <div class="image-upload-area p-6 text-center">
                                    <input type="file" id="gambar_barang_input_original" accept="image/png,image/jpeg,image/jpg,image/webp" class="visually-hidden">
                                    <label for="gambar_barang_input_original" class="upload-button-barang cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt mr-2"></i>
                                        Pilih Gambar
                                    </label>
                                    <p class="text-sm text-gray-600 mt-3">Format: JPG, PNG, WEBP</p>
                                    <p class="text-xs text-gray-500">Maksimal 2MB</p>
                                    
                                    <span id="fileNameDisplayBarang" class="text-sm text-blue-600 block mt-2 font-medium"></span>
                                    
                                    <button type="button" id="removeGambarBarangBtn" class="mt-3 text-sm text-red-500 hover:text-red-700 hover:underline {{ old('cropped_gambar_barang') ? '' : 'hidden' }}">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Hapus Gambar
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="cropped_gambar_barang" id="cropped_gambar_barang_data" value="{{ old('cropped_gambar_barang') }}">
                        @error('cropped_gambar_barang')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6">
                        <a href="{{ route('pengurus.barang.index') }}" class="w-full sm:w-auto">
                            <button type="button" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-8 rounded-xl transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </button>
                        </a>
                        <button type="submit" id="submitBtn" class="w-full sm:w-auto bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Barang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cropping Modal -->
<div id="cropGambarBarangModal" class="fixed inset-0 modal-overlay hidden items-center justify-center z-50 p-4">
    <div class="modal-content animate-scale-in">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-crop-alt mr-3 text-blue-500"></i>
                    Sesuaikan Gambar Barang
                </h3>
                <button type="button" id="closeCropBarangModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors text-2xl">
                    ×
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="img-container-cropper mb-6">
                <img id="imageToCropBarang" src="#" alt="Pratinjau Crop Barang">
            </div>
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-center order-last sm:order-first">
                    <p class="text-sm font-medium text-gray-700 mb-2">Pratinjau:</p>
                    <div class="preview-barang-container"></div>
                </div>
                
                <div class="flex space-x-3 w-full sm:w-auto">
                    <button type="button" id="cancelCropBarangModalBtn" class="flex-1 sm:flex-none bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-3 px-6 rounded-lg transition-all duration-300">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="button" id="applyCropBarangBtn" class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-300">
                        <i class="fas fa-check mr-2"></i>Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2-basic').select2({
        width: '100%',
        placeholder: function() {
            return $(this).data('placeholder');
        }
    });

    // Image cropping functionality
    const originalGambarInput = document.getElementById('gambar_barang_input_original');
    const gambarPreviewEl = document.getElementById('gambarBarangPreview');
    const placeholderIconEl = document.querySelector('#gambarBarangPreviewContainer .placeholder-icon');
    const removeGambarBtn = document.getElementById('removeGambarBarangBtn');
    const cropModalBarangEl = document.getElementById('cropGambarBarangModal');
    const imageToCropBarangEl = document.getElementById('imageToCropBarang');
    const closeCropBarangModalBtn = document.getElementById('closeCropBarangModalBtn');
    const cancelCropBarangModalBtn = document.getElementById('cancelCropBarangModalBtn');
    const applyCropBarangBtn = document.getElementById('applyCropBarangBtn');
    const croppedGambarBarangInput = document.getElementById('cropped_gambar_barang_data');
    const fileNameDisplayBarang = document.getElementById('fileNameDisplayBarang');
    const uploadArea = document.querySelector('.image-upload-area');

    let cropperBarangInstance;
    let originalBarangFileDetails = null;

    function openBarangCropModal() {
        if (cropModalBarangEl) {
            cropModalBarangEl.classList.remove('hidden');
            cropModalBarangEl.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function hideBarangCropModal() {
        if (cropModalBarangEl) {
            cropModalBarangEl.classList.add('hidden');
            cropModalBarangEl.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
        if (cropperBarangInstance) {
            cropperBarangInstance.destroy();
            cropperBarangInstance = null;
        }
        if (originalGambarInput) originalGambarInput.value = '';
        if (fileNameDisplayBarang) fileNameDisplayBarang.textContent = '';
    }

    function resetBarangImagePreview() {
        if (gambarPreviewEl) {
            gambarPreviewEl.src = "{{ asset('img/placeholder_barang.png') }}";
            gambarPreviewEl.classList.add('hidden');
        }
        if (placeholderIconEl) placeholderIconEl.classList.remove('hidden');
        if (removeGambarBtn) removeGambarBtn.classList.add('hidden');
        if (croppedGambarBarangInput) croppedGambarBarangInput.value = '';
        if (fileNameDisplayBarang) fileNameDisplayBarang.textContent = '';
        if (uploadArea) uploadArea.classList.remove('has-image');
    }

    if (originalGambarInput) {
        originalGambarInput.addEventListener('change', function(event) {
            const files = event.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                
                // Validate file size
                if (file.size > 2 * 1024 * 1024) {
                    showNotification('Ukuran file maksimal 2MB', 'error');
                    this.value = '';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    showNotification('Format file harus JPG, PNG, atau WEBP', 'error');
                    this.value = '';
                    return;
                }

                originalBarangFileDetails = {
                    name: file.name,
                    type: file.type
                };
                
                if (fileNameDisplayBarang) {
                    fileNameDisplayBarang.textContent = `File: ${originalBarangFileDetails.name}`;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (imageToCropBarangEl) imageToCropBarangEl.src = e.target.result;
                    openBarangCropModal();
                    
                    if (cropperBarangInstance) cropperBarangInstance.destroy();
                    
                    if (imageToCropBarangEl && imageToCropBarangEl.src && imageToCropBarangEl.src !== '#') {
                        cropperBarangInstance = new Cropper(imageToCropBarangEl, {
                            aspectRatio: 4/3,
                            viewMode: 1,
                            preview: '.preview-barang-container',
                            responsive: true,
                            restore: false,
                            guides: true,
                            center: true,
                            highlight: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                        });
                    } else {
                        hideBarangCropModal();
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (closeCropBarangModalBtn) closeCropBarangModalBtn.addEventListener('click', hideBarangCropModal);
    if (cancelCropBarangModalBtn) cancelCropBarangModalBtn.addEventListener('click', hideBarangCropModal);

    if (applyCropBarangBtn) {
        applyCropBarangBtn.addEventListener('click', function() {
            if (cropperBarangInstance && originalBarangFileDetails) {
                const canvas = cropperBarangInstance.getCroppedCanvas({
                    width: 800,
                    height: 600,
                    imageSmoothingQuality: 'high'
                });
                
                if (canvas) {
                    const croppedImageDataURL = canvas.toDataURL(originalBarangFileDetails.type || 'image/jpeg', 0.9);
                    
                    if (croppedGambarBarangInput) croppedGambarBarangInput.value = croppedImageDataURL;
                    if (gambarPreviewEl) {
                        gambarPreviewEl.src = croppedImageDataURL;
                        gambarPreviewEl.classList.remove('hidden');
                    }
                    if (placeholderIconEl) placeholderIconEl.classList.add('hidden');
                    if (removeGambarBtn) removeGambarBtn.classList.remove('hidden');
                    if (uploadArea) uploadArea.classList.add('has-image');
                    
                    hideBarangCropModal();
                    showNotification('Gambar berhasil disesuaikan', 'success');
                } else {
                    showNotification('Gagal memproses gambar', 'error');
                }
            } else {
                showNotification('Pilih gambar terlebih dahulu', 'error');
            }
        });
    }

    if (removeGambarBtn) {
        removeGambarBtn.addEventListener('click', function() {
            resetBarangImagePreview();
            showNotification('Gambar dihapus', 'info');
        });
    }

    // Form submission
    const form = document.getElementById('createBarangForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const originalHtml = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            submitBtn.disabled = true;
            
            // Re-enable after timeout as fallback
            setTimeout(() => {
                submitBtn.innerHTML = originalHtml;
                submitBtn.disabled = false;
            }, 10000);
        });
    }

    // Initialize with old data if exists
    const oldCroppedData = "{{ old('cropped_gambar_barang') }}";
    if (oldCroppedData && gambarPreviewEl) {
        gambarPreviewEl.src = oldCroppedData;
        gambarPreviewEl.classList.remove('hidden');
        if (placeholderIconEl) placeholderIconEl.classList.add('hidden');
        if (removeGambarBtn) removeGambarBtn.classList.remove('hidden');
        if (uploadArea) uploadArea.classList.add('has-image');
    }
});

// Notification function
function showNotification(message, type = 'info') {
    if (window.showNotification) {
        window.showNotification(message, type);
    } else {
        alert(message);
    }
}
</script>
@endpush