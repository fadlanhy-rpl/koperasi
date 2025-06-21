@extends('layouts.app')

@section('title', 'Profil Saya - Admin')
@section('page-title', 'Profil Saya')
@section('page-subtitle', 'Kelola informasi profil dan keamanan akun administrator')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    .img-container-cropper { 
        width: 100%; 
        height: 350px; 
        background-color: #f3f4f6; 
        margin-bottom: 1rem; 
        border: 2px dashed #cbd5e1; 
        border-radius: 0.5rem; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        overflow: hidden; 
    }
    #imageToCropInModal { 
        display: block; 
        max-width: 100%; 
        max-height: 100%; 
    }
    .preview-circle-container { 
        width: 120px; 
        height: 120px; 
        overflow: hidden; 
        border-radius: 50%; 
        border: 3px solid #3b82f6; 
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2); 
        background-color: #f9fafb; 
        margin: 0 auto; 
    }
    .cropper-view-box, .cropper-face { 
        border-radius: 50%; 
    }
    input[type="file"].visually-hidden { 
        position: absolute; 
        width: 1px; 
        height: 1px; 
        padding: 0; 
        margin: -1px; 
        overflow: hidden; 
        clip: rect(0,0,0,0); 
        border: 0; 
    }
    .profile-photo-current { 
        width: 120px; 
        height: 120px; 
        border-radius: 50%; 
        object-fit: cover; 
        border: 4px solid #e5e7eb; 
        box-shadow: 0 4px 12px rgba(0,0,0,.15); 
        transition: all 0.3s ease;
    }
    .profile-photo-current:hover {
        border-color: #3b82f6;
        transform: scale(1.05);
    }
    .modal-overlay { 
        background: rgba(0,0,0,.7); 
        backdrop-filter: blur(3px); 
    }
    .modal-content { 
        background: #fff; 
        border-radius: .75rem; 
        box-shadow: 0 10px 25px rgba(0,0,0,.2); 
        max-width: 500px; 
        width: 90%; 
    }
    .info-card { 
        background: linear-gradient(135deg,rgba(255,255,255,.95) 0%,rgba(255,255,255,.9) 100%); 
        backdrop-filter: blur(10px); 
        border: 1px solid rgba(255,255,255,.2); 
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    .upload-button { 
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); 
        color: white; 
        border: none; 
        padding: 0.75rem 1.5rem; 
        border-radius: 0.75rem; 
        font-weight: 600; 
        cursor: pointer; 
        transition: all 0.3s ease; 
        display: inline-flex; 
        align-items: center; 
        gap: 0.5rem; 
    }
    .upload-button:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4); 
    }
    .admin-badge {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
</style>
@endpush

@section('content')
<div class="animate-fade-in max-w-6xl mx-auto space-y-8">
    
    <!-- Header Section -->
    <div class="info-card rounded-2xl shadow-xl p-8">
        <div class="flex flex-col lg:flex-row items-center lg:items-start space-y-6 lg:space-y-0 lg:space-x-8">
            <!-- Profile Photo Section -->
            <div class="flex-shrink-0 text-center">
                <img id="currentProfileImage" 
                     src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=3b82f6&color=fff&size=120&font-size=0.33&bold=true&rounded=true' }}"
                     alt="Foto Profil Admin" 
                     class="profile-photo-current mx-auto">
                
                <div class="mt-4">
                    <input type="file" id="profile_image_input" name="profile_image_original_unused" accept="image/png,image/jpeg,image/jpg,image/webp" class="visually-hidden">
                    <label for="profile_image_input" class="upload-button">
                        <i class="fas fa-camera"></i> Ganti Foto
                    </label>
                    <p class="text-xs text-gray-500 mt-2">JPG, PNG, WEBP (Maks 2MB)</p>
                    <span id="fileNameDisplay" class="text-xs text-blue-600 block mt-1 truncate" style="max-width: 200px;"></span>
                </div>
            </div>
            
            <!-- Profile Info -->
            <div class="flex-1 text-center lg:text-left">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $user->name }}</h1>
                        <div class="flex flex-col lg:flex-row lg:items-center lg:space-x-4 space-y-2 lg:space-y-0">
                            <span class="admin-badge">
                                <i class="fas fa-crown mr-2"></i>Administrator
                            </span>
                            <span class="text-gray-600">
                                <i class="fas fa-envelope mr-2"></i>{{ $user->email }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                    <div class="bg-blue-50 rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600">#{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-sm text-blue-800">ID Admin</div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $user->created_at->format('Y') }}</div>
                        <div class="text-sm text-green-800">Bergabung</div>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-purple-600">
                            @if($user->date_of_birth)
                                {{ $user->age }}
                            @else
                                -
                            @endif
                        </div>
                        <div class="text-sm text-purple-800">Usia</div>
                    </div>
                    <div class="bg-orange-50 rounded-xl p-4 text-center">
                        <div class="text-2xl font-bold text-orange-600">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="text-sm text-orange-800">Akses Penuh</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Profile Update Form -->
        <div class="lg:col-span-2">
            <form id="profileUpdateForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" data-validate>
                @csrf
                @method('PUT')
                <div class="info-card rounded-2xl shadow-xl">
                    <div class="p-6 border-b border-gray-200/80">
                        <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-user-edit mr-3 text-blue-500"></i>Informasi Profil
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Perbarui informasi pribadi dan data akun administrator</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Hidden input for cropped photo -->
                        <input type="hidden" name="cropped_profile_photo" id="cropped_profile_photo_data">
                        
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-gray-400"></i>Nama Lengkap *
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white"
                                   placeholder="Masukkan nama lengkap"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>Alamat Email *
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white"
                                   placeholder="Masukkan alamat email"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>Nomor Telepon
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone ?? '') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white"
                                   placeholder="Contoh: 08123456789">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth Field -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar mr-2 text-gray-400"></i>Tanggal Lahir
                            </label>
                            <input type="date" 
                                   id="date_of_birth" 
                                   name="date_of_birth" 
                                   value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                                   max="{{ now()->toDateString() }}"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 bg-white">
                            @error('date_of_birth')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="p-6 bg-gray-50/70 rounded-b-2xl border-t border-gray-200/80 flex justify-end">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-300 flex items-center space-x-2 hover:transform hover:scale-105">
                            <i class="fas fa-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Account Security -->
            <div class="info-card rounded-2xl shadow-xl">
                <div class="p-6 border-b border-gray-200/80">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-shield-alt mr-3 text-red-500"></i>Keamanan Akun
                    </h3>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4" data-validate>
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password Saat Ini *
                            </label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300"
                                   placeholder="Password saat ini"
                                   required>
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password Baru *
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300"
                                   placeholder="Password baru (min. 8 karakter)"
                                   required>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Konfirmasi Password *
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300"
                                   placeholder="Ulangi password baru"
                                   required>
                        </div>

                        <button type="submit" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center space-x-2 hover:transform hover:scale-105">
                            <i class="fas fa-key"></i>
                            <span>Update Password</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Account Information -->
            <div class="info-card rounded-2xl shadow-xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Informasi Akun
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Status:</span>
                        <span class="admin-badge text-xs">Administrator</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Terdaftar:</span>
                        <span class="text-gray-900 font-semibold">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Terakhir Login:</span>
                        <span class="text-gray-900 font-semibold">
                            {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : 'Belum tercatat' }}
                        </span>
                    </div>
                    @if($user->phone)
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 font-medium">Telepon:</span>
                        <span class="text-gray-900 font-semibold">{{ $user->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="info-card rounded-2xl shadow-xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                    Aksi Cepat
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center justify-center space-x-2">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard Admin</span>
                    </a>
                    {{-- <a href="{{ route('admin.users.index') }}" 
                       class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center justify-center space-x-2">
                        <i class="fas fa-users"></i>
                        <span>Kelola Pengguna</span>
                    </a> --}}
                    {{-- <a href="{{ route('admin.settings.index') }}" 
                       class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 flex items-center justify-center space-x-2">
                        <i class="fas fa-cog"></i>
                        <span>Pengaturan Sistem</span>
                    </a> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Crop Image Modal -->
    <div id="cropImageModal" class="fixed inset-0 modal-overlay hidden items-center justify-center z-[100] p-4 animate-fade-in">
        <div class="modal-content animate-scale-in w-full">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-crop-alt mr-3 text-blue-500"></i>Sesuaikan Foto Profil
                    </h3>
                    <button type="button" id="closeCropModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors text-2xl">×</button>
                </div>
            </div>
            <div class="p-6">
                <div class="img-container-cropper mb-6">
                    <img id="imageToCropInModal" src="#" alt="Image to crop">
                </div>
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-center order-last sm:order-first mt-4 sm:mt-0">
                        <p class="text-sm font-medium text-gray-700 mb-2">Pratinjau:</p>
                        <div class="preview-circle-container"></div>
                    </div>
                    <div class="flex space-x-3 w-full sm:w-auto">
                        <button type="button" id="cancelCropModalBtn" class="flex-1 sm:flex-none bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button type="button" id="applyCropBtn" class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            <i class="fas fa-check mr-2"></i>Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const originalPhotoInput = document.getElementById('profile_image_input');
    const currentProfileImageEl = document.getElementById('currentProfileImage');
    const cropModalEl = document.getElementById('cropImageModal');
    const imageToCropEl = document.getElementById('imageToCropInModal');
    const closeCropModalBtn = document.getElementById('closeCropModalBtn');
    const cancelCropModalBtn = document.getElementById('cancelCropModalBtn');
    const applyCropBtn = document.getElementById('applyCropBtn');
    const croppedImageDataInput = document.getElementById('cropped_profile_photo_data');
    const fileNameDisplay = document.getElementById('fileNameDisplay');

    let cropperInstance;
    let originalFileDetails = null;

    function openModalForCropper() { 
        if (cropModalEl) { 
            cropModalEl.classList.remove('hidden'); 
            cropModalEl.classList.add('flex'); 
            document.body.style.overflow = 'hidden';
        } 
    }

    function closeModalForCropper() { 
        if (cropModalEl) { 
            cropModalEl.classList.add('hidden'); 
            cropModalEl.classList.remove('flex'); 
            document.body.style.overflow = 'auto';
        } 
        if (cropperInstance) { 
            cropperInstance.destroy(); 
            cropperInstance = null; 
        } 
        if(originalPhotoInput) originalPhotoInput.value = ''; 
        if(fileNameDisplay) fileNameDisplay.textContent = '';
    }
    
    if (originalPhotoInput) {
        originalPhotoInput.addEventListener('change', function (event) {
            const files = event.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                
                // Validate file size
                if (file.size > 2 * 1024 * 1024) { 
                    showNotification('Ukuran file terlalu besar. Maksimal 2MB.', 'error'); 
                    this.value = ''; 
                    if(fileNameDisplay) fileNameDisplay.textContent = ''; 
                    return; 
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) { 
                    showNotification('Tipe file tidak didukung. Gunakan JPG, PNG, atau WEBP.', 'error'); 
                    this.value = ''; 
                    if(fileNameDisplay) fileNameDisplay.textContent = ''; 
                    return; 
                }
                
                originalFileDetails = { name: file.name, type: file.type };
                if(fileNameDisplay) fileNameDisplay.textContent = `File: ${originalFileDetails.name}`;
                
                const reader = new FileReader();
                reader.onload = function (e) {
                    if(imageToCropEl) imageToCropEl.src = e.target.result;
                    openModalForCropper();
                    
                    if (cropperInstance) cropperInstance.destroy();
                    if(imageToCropEl && imageToCropEl.src && imageToCropEl.src !== '#') {
                        cropperInstance = new Cropper(imageToCropEl, {
                            aspectRatio: 1, 
                            viewMode: 1, 
                            dragMode: 'move', 
                            background: false,
                            preview: '.preview-circle-container', 
                            responsive: true, 
                            autoCropArea: 0.85, 
                            checkOrientation: false, 
                            modal: true, 
                            guides: true, 
                            center: true, 
                            highlight: false,
                            cropBoxMovable: true, 
                            cropBoxResizable: true, 
                            toggleDragModeOnDblclick: false,
                            minCropBoxWidth: 100, 
                            minCropBoxHeight: 100,
                        });
                    } else {
                        closeModalForCropper();
                    }
                };
                reader.onerror = function() { 
                    showNotification("Gagal membaca file gambar.", 'error'); 
                    closeModalForCropper(); 
                }
                reader.readAsDataURL(file);
            }
        });
    }

    if (closeCropModalBtn) closeCropModalBtn.addEventListener('click', closeModalForCropper);
    if (cancelCropModalBtn) cancelCropModalBtn.addEventListener('click', closeModalForCropper);

    if (applyCropBtn) {
        applyCropBtn.addEventListener('click', function () {
            if (cropperInstance && originalFileDetails) {
                const canvas = cropperInstance.getCroppedCanvas({ 
                    width: 300, 
                    height: 300, 
                    imageSmoothingEnabled: true, 
                    imageSmoothingQuality: 'high' 
                });
                
                if (canvas) {
                    const croppedImageDataURL = canvas.toDataURL(originalFileDetails.type || 'image/jpeg', 0.9);
                    if (croppedImageDataInput) croppedImageDataInput.value = croppedImageDataURL;
                    if (currentProfileImageEl) currentProfileImageEl.src = croppedImageDataURL;
                    closeModalForCropper();
                    showNotification('Foto telah di-crop. Klik "Simpan Perubahan" untuk menyimpan.', 'success');
                } else {
                    showNotification('Gagal melakukan proses crop.', 'error');
                }
            } else {
                showNotification('Silakan pilih gambar dan pastikan area crop sudah muncul sebelum menerapkan.', 'warning');
            }
        });
    }

    // Close modal when clicking outside
    if (cropModalEl) { 
        cropModalEl.addEventListener('click', function(event) { 
            if (event.target === this) { 
                closeModalForCropper(); 
            } 
        }); 
    }

    // Form validation
    const profileForm = document.getElementById('profileUpdateForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (!name) {
                e.preventDefault();
                showNotification('Nama lengkap wajib diisi.', 'error');
                return;
            }
            
            if (!email) {
                e.preventDefault();
                showNotification('Alamat email wajib diisi.', 'error');
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                submitBtn.disabled = true;
                
                // Re-enable button after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000);
            }
        });
    }
});
</script>
@endpush