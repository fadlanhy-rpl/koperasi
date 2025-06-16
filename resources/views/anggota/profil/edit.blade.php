@extends('layouts.app')

@section('title', 'Edit Profil Saya - Koperasi')
@section('page-title', 'Edit Profil Akun')
@section('page-subtitle', 'Perbarui informasi pribadi, password, dan foto profil Anda')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    .img-container-cropper { width: 100%; height: 350px; background-color: #f3f4f6; margin-bottom: 1rem; border: 2px dashed #cbd5e1; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    #imageToCropInModal { display: block; max-width: 100%; max-height: 100%; }
    .preview-circle-container { width: 120px; height: 120px; overflow: hidden; border-radius: 50%; border: 3px solid #3b82f6; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2); background-color: #f9fafb; margin: 0 auto; }
    .cropper-view-box, .cropper-face { border-radius: 50%; }
    input[type="file"].visually-hidden { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }
    .profile-photo-current { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
    .modal-overlay { background: rgba(0,0,0,.7); backdrop-filter: blur(3px); }
    .modal-content { background: #fff; border-radius: .75rem; box-shadow: 0 10px 25px rgba(0,0,0,.2); max-width: 500px; width: 90%; }
    .info-card { background: linear-gradient(135deg,rgba(255,255,255,.9) 0%,rgba(255,255,255,.8) 100%); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,.2); }
    .upload-button { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem; }
    .upload-button:hover { transform: translateY(-1px); box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3); }
</style>
@endpush

@section('content')
<div class="animate-fade-in max-w-4xl mx-auto space-y-8">

    {{-- Form Utama untuk Update Profil (Nama, Email, Tanggal Lahir & Foto) --}}
    <form id="profileUpdateForm" action="{{ route('anggota.profil.update') }}" method="POST" enctype="multipart/form-data" data-validate>
        @csrf
        @method('PUT')
        <div class="info-card rounded-2xl shadow-xl">
            <div class="p-6 border-b border-gray-200/80">
                <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-edit mr-3 text-blue-500"></i>Informasi Profil & Foto
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <x-forms.input type="text" name="name" label="Nama Lengkap" :value="$user->name" :required="true" />
                <x-forms.input type="email" name="email" label="Alamat Email" :value="$user->email" :required="true" helpText="Email akan digunakan untuk login dan notifikasi."/>
                <x-forms.input 
                    type="date" 
                    name="date_of_birth" 
                    label="Tanggal Lahir (Opsional)" 
                    :value="$user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : ''" 
                    max="{{ now()->toDateString() }}" {{-- Atribut 'max' diteruskan secara individual --}}
                    helpText="Format: YYYY-MM-DD"
                />
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-4"><i class="fas fa-camera mr-2 text-gray-400"></i> Foto Profil</label>
                    <div class="flex items-center space-x-6">
                        <img id="currentProfileImage" 
                             src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff&size=100&font-size=0.33&bold=true&rounded=true' }}"
                             alt="Foto Profil" 
                             class="profile-photo-current">
                        <div class="flex-1">
                            {{-- Input file ini tetap ada tapi disembunyikan, JS akan memicunya --}}
                            <input type="file" id="profile_image_input" name="profile_image_original_unused" accept="image/png,image/jpeg,image/jpg,image/webp" class="visually-hidden">
                            <label for="profile_image_input" class="upload-button">
                                <i class="fas fa-upload"></i> Ganti Foto
                            </label>
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, WEBP (Maks 2MB).</p>
                            <span id="fileNameDisplay" class="text-xs text-blue-600 block mt-1 truncate" style="max-width: 200px;"></span>
                            @if($user->profile_photo_path && !str_contains($user->profile_photo_path, 'placeholder_avatar.png')) {{-- Cek path, bukan URL --}}
                                <button type="button" id="triggerDeleteProfilePhoto" class="mt-2 text-xs text-red-500 hover:text-red-700 hover:underline">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus Foto
                                </button>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="cropped_profile_photo" id="cropped_profile_photo_data">
                    @error('cropped_profile_photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @error('profile_image_original_unused') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror {{-- Error untuk file asli jika ada --}}
                </div>
            </div>
            <div class="p-6 bg-gray-50/70 rounded-b-2xl border-t border-gray-200/80 flex justify-end">
                <x-forms.button type="submit" variant="primary" icon="save" size="lg">Simpan Perubahan Profil</x-forms.button>
            </div>
        </div>
    </form>

    <form id="deleteActualProfilePhotoForm" action="{{ route('anggota.profil.photo.delete') }}" method="POST" class="hidden">@csrf @method('DELETE')</form>

    <div class="info-card rounded-2xl shadow-xl">
        <div class="p-6 border-b border-gray-200/80"><h3 class="text-2xl font-bold text-gray-800 flex items-center"><i class="fas fa-key mr-3 text-blue-500"></i>Ubah Password</h3></div>
        <div class="p-6">
            <form action="{{ route('anggota.profil.updatePassword') }}" method="POST" class="space-y-6" data-validate>
                @csrf @method('PUT')
                <x-forms.input type="password" name="current_password" label="Password Saat Ini" :required="true" />
                <x-forms.input type="password" name="password" label="Password Baru" placeholder="Min. 8 karakter" :required="true" />
                <x-forms.input type="password" name="password_confirmation" label="Konfirmasi Password Baru" :required="true" />
                <div class="flex justify-end pt-2"><x-forms.button type="submit" variant="primary" icon="key" size="lg">Update Password</x-forms.button></div>
            </form>
        </div>
    </div>

    <div class="mt-8 flex justify-start">
        <a href="{{ route('anggota.profil.show') }}"> 
            <x-forms.button type="button" variant="secondary" icon="arrow-left" size="lg">Kembali ke Profil Saya</x-forms.button>
        </a>
    </div>

    <div id="cropImageModal" class="fixed inset-0 modal-overlay hidden items-center justify-center z-[100] p-4 animate-fade-in">
        <div class="modal-content animate-scale-in w-full">
            <div class="p-6 border-b border-gray-200"><div class="flex justify-between items-center"><h3 class="text-xl font-semibold text-gray-800 flex items-center"><i class="fas fa-crop-alt mr-3 text-blue-500"></i>Sesuaikan Foto Profil</h3><button type="button" id="closeCropModalBtn" class="text-gray-400 hover:text-gray-600 transition-colors text-2xl">×</button></div></div>
            <div class="p-6"><div class="img-container-cropper mb-6"><img id="imageToCropInModal" src="#" alt="Image to crop"></div><div class="flex flex-col sm:flex-row items-center justify-between gap-4"><div class="text-center order-last sm:order-first mt-4 sm:mt-0"><p class="text-sm font-medium text-gray-700 mb-2">Pratinjau:</p><div class="preview-circle-container"></div> </div><div class="flex space-x-3 w-full sm:w-auto"><button type="button" id="cancelCropModalBtn" class="flex-1 sm:flex-none bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors"><i class="fas fa-times mr-2"></i>Batal</button><button type="button" id="applyCropBtn" class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors"><i class="fas fa-check mr-2"></i>Terapkan</button></div></div></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const originalPhotoInput = document.getElementById('profile_image_input'); // ID ini harus sama dengan 'for' di label
    const currentProfileImageEl = document.getElementById('currentProfileImage');
    const cropModalEl = document.getElementById('cropImageModal');
    const imageToCropEl = document.getElementById('imageToCropInModal');
    const closeCropModalBtn = document.getElementById('closeCropModalBtn');
    const cancelCropModalBtn = document.getElementById('cancelCropModalBtn');
    const applyCropBtn = document.getElementById('applyCropBtn');
    // const profileUpdateForm = document.getElementById('profileUpdateForm'); // Tidak digunakan secara langsung di JS ini
    const croppedImageDataInput = document.getElementById('cropped_profile_photo_data');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const deleteProfilePhotoButton = document.getElementById('triggerDeleteProfilePhoto');

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
        if(fileNameDisplay) fileNameDisplay.textContent = 'Tidak ada file dipilih.';
        // croppedImageDataInput.value = ''; // Jangan reset ini di cancel, hanya saat file baru dipilih
    }
    
    if (originalPhotoInput) {
        originalPhotoInput.addEventListener('change', function (event) {
            const files = event.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                if (file.size > 2 * 1024 * 1024) { 
                    alert('Ukuran file terlalu besar. Maksimal 2MB.'); 
                    this.value = ''; 
                    if(fileNameDisplay) fileNameDisplay.textContent = 'Pilih file (maks 2MB).'; 
                    return; 
                }
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(file.type)) { 
                    alert('Tipe file tidak didukung. Gunakan JPG, PNG, atau WEBP.'); 
                    this.value = ''; 
                    if(fileNameDisplay) fileNameDisplay.textContent = 'Tipe file tidak valid.'; 
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
                            aspectRatio: 1, viewMode: 1, dragMode: 'move', background: false,
                            preview: '.preview-circle-container', responsive: true, autoCropArea: 0.85, 
                            checkOrientation: false, modal: true, guides: true, center: true, highlight: false,
                            cropBoxMovable: true, cropBoxResizable: true, toggleDragModeOnDblclick: false,
                            minCropBoxWidth: 100, minCropBoxHeight: 100,
                        });
                    } else {
                        hideModalForCropper(); // Menggunakan fungsi yang benar
                    }
                };
                reader.onerror = function() { alert("Gagal membaca file gambar."); hideModalForCropper(); }
                reader.readAsDataURL(file);
            }
        });
    }

    if (closeCropModalBtn) closeCropModalBtn.addEventListener('click', closeModalForCropper);
    if (cancelCropModalBtn) cancelCropModalBtn.addEventListener('click', closeModalForCropper);

    if (applyCropBtn) {
        applyCropBtn.addEventListener('click', function () {
            if (cropperInstance && originalFileDetails) {
                const canvas = cropperInstance.getCroppedCanvas({ width: 300, height: 300, imageSmoothingEnabled: true, imageSmoothingQuality: 'high' });
                if (canvas) {
                    const croppedImageDataURL = canvas.toDataURL(originalFileDetails.type || 'image/jpeg', 0.9);
                    if (croppedImageDataInput) croppedImageDataInput.value = croppedImageDataURL;
                    if (currentProfileImageEl) currentProfileImageEl.src = croppedImageDataURL;
                    closeModalForCropper();
                    alert('Foto telah di-crop. Klik "Simpan Perubahan Profil" untuk menyimpan.');
                } else {
                    alert('Gagal melakukan proses crop.');
                }
            } else {
                alert('Silakan pilih gambar dan pastikan area crop sudah muncul sebelum menerapkan.');
            }
        });
    }
    
    if(deleteProfilePhotoButton){
        deleteProfilePhotoButton.addEventListener('click', function(e){
            e.preventDefault();
            if(confirm('Apakah Anda yakin ingin menghapus foto profil Anda? Data yang dihapus tidak dapat dikembalikan.')){
                document.getElementById('deleteActualProfilePhotoForm').submit();
            }
        });
    }

    if (cropModalEl) { cropModalEl.addEventListener('click', function(event) { if (event.target === this) { closeModalForCropper(); } }); }
});
</script>
@endpush