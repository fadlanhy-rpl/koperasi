{{-- resources/views/anggota/profil/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Profil Saya - Koperasi')
@section('page-title', 'Edit Profil Akun')
@section('page-subtitle', 'Perbarui informasi nama, password, dan foto profil Anda')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .img-container-cropper {
            width: 100%;
            height: 350px;
            background-color: #f3f4f6;
            margin-bottom: 1rem;
            border: 2px dashed #d1d5db;
            border-radius: 0.75rem;
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
            width: 150px;
            height: 150px;
            overflow: hidden;
            border-radius: 50%;
            border: 3px solid #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            background-color: #f9fafb;
            margin: 0 auto;
        }

        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
        }

        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        .profile-photo-current-edit {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e5e7eb;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .form-input-style {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .form-input-style:focus {
            border-color: #3b82f6;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        .modal-overlay {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(3px);
        }

        .modal-content {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
    </style>
@endpush

@section('content')
    <div class="animate-fade-in max-w-3xl mx-auto space-y-8">

        <form id="profileUpdateForm" action="{{ route('anggota.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl border border-gray-200/80">
                <div class="p-6 border-b border-gray-200/80">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center"><i
                            class="fas fa-user-edit mr-3 text-blue-500"></i>Edit Informasi Profil</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}"
                            required class="form-input-style @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Profil</label>
                        <div class="flex items-center space-x-6">
                            <img id="currentProfileImage" 
     src="{{ Auth::user()->profile_photo_url }}" {{-- Menggunakan accessor --}}
     alt="Foto Profil Saat Ini" 
     class="profile-photo-current">
                            <div id="profileInitialDivOnEdit"
                                class="profile-photo-current-edit bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold"
                                style="{{ Auth::user()->profile_photo_path && !str_contains(Auth::user()->profile_photo_url, 'placeholder_avatar.png') ? 'display:none;' : 'display:flex;' }}">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <input type="file" id="profile_photo_original_input_for_js"
                                    accept="image/png,image/jpeg,image/jpg,image/webp" class="visually-hidden">
                                <label for="profile_photo_original_input_for_js"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                                    <i class="fas fa-upload mr-2 text-gray-500"></i> Pilih Foto...
                                </label>
                                <span id="fileNameDisplay" class="text-xs text-gray-500 ml-3 block mt-1">Tidak ada file
                                    dipilih.</span>
                                @if (Auth::user()->profile_photo_path && !str_contains(Auth::user()->profile_photo_url, 'placeholder_avatar.png'))
                                    <button type="button" id="deleteProfilePhotoButton"
                                        class="mt-2 text-xs text-red-600 hover:text-red-800 hover:underline">
                                        Hapus Foto Saat Ini
                                    </button>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="cropped_profile_photo" id="cropped_profile_photo_data">
                        @error('cropped_profile_photo')
                            <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="p-6 bg-gray-50/70 rounded-b-2xl border-t border-gray-200/80 flex justify-end">
                    <x-forms.button type="submit" variant="primary" icon="save">Simpan Perubahan Profil</x-forms.button>
                </div>
            </div>
        </form>
        <form id="deleteActualProfilePhotoForm" action="{{ route('anggota.profil.photo.delete') }}" method="POST"
            class="hidden">@csrf @method('DELETE')</form>

        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl border border-gray-200/80">
            <div class="p-6 border-b border-gray-200/80">
                <h3 class="text-xl font-bold text-gray-800 flex items-center"><i
                        class="fas fa-key mr-3 text-blue-500"></i>Ubah Password</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('anggota.profil.updatePassword') }}" method="POST" class="space-y-6" data-validate>
                    @csrf @method('PUT')
                    <x-forms.input type="password" name="current_password" label="Password Saat Ini" :required="true" />
                    <x-forms.input type="password" name="password" label="Password Baru" placeholder="Minimal 8 karakter"
                        :required="true" />
                    <x-forms.input type="password" name="password_confirmation" label="Konfirmasi Password Baru"
                        :required="true" />
                    <div class="flex justify-end pt-2">
                        <x-forms.button type="submit" variant="primary" icon="key">Update Password</x-forms.button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 flex justify-start">
            <a href="{{ route('anggota.profil.show') }}">
                <x-forms.button type="button" variant="secondary" icon="arrow-left">Kembali ke Profil Saya</x-forms.button>
            </a>
        </div>

        <div id="cropImageModal"
            class="fixed inset-0 modal-overlay hidden items-center justify-center z-[100] p-4 animate-fade-in">
            <div class="modal-content animate-scale-in">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-gray-800 flex items-center"><i
                                class="fas fa-crop-alt mr-3 text-blue-500"></i> Sesuaikan Foto Profil</h3><button
                            type="button" id="closeCropModalBtn"
                            class="text-gray-400 hover:text-gray-600 transition-colors text-2xl"><i
                                class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="img-container-cropper mb-6"><img id="imageToCropInModal" src="#"
                            alt="Pratinjau Crop"></div>
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                        <div class="text-center order-2 lg:order-1">
                            <p class="text-sm font-semibold text-gray-700 mb-3">Pratinjau Hasil:</p>
                            <div class="preview-circle-container"></div>
                        </div>
                        <div class="flex space-x-3 order-1 lg:order-2"><button type="button" id="cancelCropModalBtn"
                                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300"><i
                                    class="fas fa-times mr-2"></i>Batal</button><button type="button" id="applyCropBtn"
                                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300"><i
                                    class="fas fa-check mr-2"></i>Terapkan & Gunakan</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Kode JavaScript untuk Cropper.js SAMA seperti yang sudah Anda berikan dan saya sempurnakan sedikit di chat sebelumnya --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        // Kode JS untuk cropper, open/close modal, apply crop, delete photo SAMA SEPERTI YANG SUDAH KITA BUAT SEBELUMNYA
        // Pastikan KoperasiApp.openModal, KoperasiApp.closeModal, dan KoperasiApp.showNotification
        // didefinisikan di app.js atau di-fallback di sini jika belum.
        document.addEventListener('DOMContentLoaded', function() {
            const originalPhotoInput = document.getElementById('profile_photo_original_input_for_js');
            const currentProfileImageEl = document.getElementById(
            'currentProfileImageOnEdit'); // Pastikan ID ini ada di img atau div inisial
            const profileInitialDivOnEdit = document.getElementById('profileInitialDivOnEdit');
            const cropModalEl = document.getElementById('cropImageModal');
            const imageToCropEl = document.getElementById('imageToCropInModal');
            const closeCropModalBtn = document.getElementById('closeCropModalBtn');
            const cancelCropModalBtn = document.getElementById('cancelCropModalBtn');
            const applyCropBtn = document.getElementById('applyCropBtn');
            // const profileUpdateForm = document.getElementById('profileUpdateForm'); // Tidak perlu submit dari JS ini
            const croppedImageDataInput = document.getElementById('cropped_profile_photo_data');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const deleteProfilePhotoButton = document.getElementById('deleteProfilePhotoButton');

            let cropperInstance;
            let originalFileDetails = null;

            // Fallback KoperasiApp jika belum ada
            window.KoperasiApp = window.KoperasiApp || {};
            KoperasiApp.openModal = KoperasiApp.openModal || function(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            };
            KoperasiApp.closeModal = KoperasiApp.closeModal || function(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = 'auto';
                }
            };
            KoperasiApp.showNotification = KoperasiApp.showNotification || function(message, type = 'info') {
                console.log(`Notification (${type}): ${message}`); // Fallback sederhana
                alert(`Notification (${type}): ${message}`);
            };


            if (originalPhotoInput) {
                originalPhotoInput.addEventListener('change', function(event) {
                    const files = event.target.files;
                    if (files && files.length > 0) {
                        const file = files[0];
                        if (file.size > 2 * 1024 * 1024) {
                            KoperasiApp.showNotification('Ukuran file terlalu besar (Maks 2MB).', 'error');
                            this.value = '';
                            return;
                        }
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                        if (!allowedTypes.includes(file.type)) {
                            KoperasiApp.showNotification('Tipe file tidak didukung (JPG, PNG, WEBP).',
                                'error');
                            this.value = '';
                            return;
                        }

                        originalFileDetails = {
                            name: file.name,
                            type: file.type
                        };
                        if (fileNameDisplay) fileNameDisplay.textContent =
                            `File: ${originalFileDetails.name}`;

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (imageToCropEl) imageToCropEl.src = e.target.result;
                            if (cropModalEl) KoperasiApp.openModal('cropImageModal');

                            if (cropperInstance) cropperInstance.destroy();
                            if (imageToCropEl) {
                                cropperInstance = new Cropper(imageToCropEl, {
                                    aspectRatio: 1,
                                    viewMode: 1,
                                    dragMode: 'move',
                                    background: false,
                                    preview: '.preview-circle-container',
                                    responsive: true,
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
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            function hideCropperModal() {
                if (cropModalEl) KoperasiApp.closeModal('cropImageModal');
                if (cropperInstance) {
                    cropperInstance.destroy();
                    cropperInstance = null;
                }
                if (originalPhotoInput) originalPhotoInput.value = '';
                if (fileNameDisplay) fileNameDisplay.textContent = 'Tidak ada file dipilih.';
                // Jangan kosongkan croppedImageDataInput di sini, karena user mungkin batal tapi sudah apply crop
            }

            if (closeCropModalBtn) closeCropModalBtn.addEventListener('click', hideCropperModal);
            if (cancelCropModalBtn) cancelCropModalBtn.addEventListener('click', hideCropperModal);

            if (applyCropBtn) {
                applyCropBtn.addEventListener('click', function() {
                    if (!cropperInstance || !originalFileDetails) {
                        KoperasiApp.showNotification('Pilih gambar dan crop terlebih dahulu.', 'warning');
                        return;
                    }
                    const canvas = cropperInstance.getCroppedCanvas({
                        width: 300,
                        height: 300,
                        imageSmoothingQuality: 'high'
                    });
                    const croppedImageDataURL = canvas.toDataURL(originalFileDetails.type || 'image/jpeg',
                        0.9);

                    if (croppedImageDataInput) croppedImageDataInput.value = croppedImageDataURL;

                    if (currentProfileImageEl) {
                        currentProfileImageEl.src = croppedImageDataURL;
                        if (profileInitialDivOnEdit) profileInitialDivOnEdit.style.display =
                        'none'; // Sembunyikan inisial
                        currentProfileImageEl.style.display = 'block'; // Tampilkan gambar
                    }
                    hideCropperModal();
                    KoperasiApp.showNotification(
                        'Foto profil telah di-crop. Klik "Simpan Perubahan Profil" untuk mengupload.',
                        'info');
                });
            }

            if (deleteProfilePhotoButton) {
                deleteProfilePhotoButton.addEventListener('click', function(event) {
                    event.preventDefault();
                    if (confirm('Apakah Anda yakin ingin menghapus foto profil Anda?')) {
                        document.getElementById('deleteActualProfilePhotoForm').submit();
                    }
                });
            }

            if (cropModalEl) {
                cropModalEl.addEventListener('click', function(event) {
                    if (event.target === this) {
                        hideCropperModal();
                    }
                });
            }
        });
    </script>
@endpush
