{{-- resources/views/anggota/profil/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Saya - Koperasi')
@section('page-title', 'Profil Akun Saya')
@section('page-subtitle', 'Informasi pribadi dan keanggotaan Anda')

@push('styles')
{{-- ... (Style dari kode Anda SAMA seperti sebelumnya) ... --}}
<style>.profile-image-container{position:relative;display:inline-block}.profile-image{width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid #e5e7eb;box-shadow:0 10px 25px rgba(0,0,0,.1);transition:all .3s ease}.profile-image:hover{transform:scale(1.05);box-shadow:0 15px 35px rgba(0,0,0,.15)}.profile-initial{width:120px;height:120px;border-radius:50%;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;font-size:3rem;font-weight:700;color:#fff;border:4px solid #e5e7eb;box-shadow:0 10px 25px rgba(0,0,0,.1);transition:all .3s ease}.profile-initial:hover{transform:scale(1.05);box-shadow:0 15px 35px rgba(0,0,0,.15)}.info-card{background:linear-gradient(135deg,rgba(255,255,255,.9) 0%,rgba(255,255,255,.8) 100%);backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.2)}.status-badge{display:inline-flex;align-items:center;padding:.5rem 1rem;border-radius:9999px;font-size:.875rem;font-weight:600;text-transform:capitalize}.status-active{background-color:#dcfce7;color:#166534}.status-inactive{background-color:#fecaca;color:#991b1b}.animate-fade-in{animation:fadeIn .6s ease-out}@keyframes fadeIn{0%{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}</style>
@endpush

@section('content')
<div class="animate-fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="info-card rounded-2xl shadow-xl p-8">
                <div class="text-center">
                    <div class="profile-image-container mb-6">
                        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff&size=128&font-size=0.33&bold=true&rounded=true' }}" 
                             alt="Foto Profil {{ $user->name }}" 
                             class="profile-image mx-auto">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $user->name }}</h3>
                    <p class="text-gray-600 mb-4 text-sm">{{ $user->email }}</p>
                    <div class="flex justify-center mb-6">
                        <span class="status-badge {{ ($user->status ?? 'active') == 'active' ? 'status-active' : 'status-inactive' }}">
                            <i class="fas {{ ($user->status ?? 'active') == 'active' ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600' }} text-xs mr-2"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    <a href="{{ route('anggota.profil.edit') }}" class="block">
                        <x-forms.button type="button" variant="primary" icon="edit" class="w-full py-3 text-base">Edit Profil & Password</x-forms.button>
                    </a>
                </div>
            </div>
        </div>
        <div class="lg:col-span-2">
            <div class="info-card rounded-2xl shadow-xl">
                <div class="p-6 border-b border-gray-200"><h3 class="text-2xl font-bold text-gray-800 flex items-center"><i class="fas fa-user-circle mr-3 text-blue-500"></i>Informasi Keanggotaan</h3></div>
                <div class="p-6">
                    <dl class="space-y-4 text-sm">
                        <div class="flex justify-between items-center py-3 px-4 bg-gray-50/50 rounded-lg"><dt class="text-gray-600 font-medium flex items-center"><i class="fas fa-user mr-2 text-gray-400"></i>Nama Lengkap</dt><dd class="font-semibold text-gray-800 text-right">{{ $user->name }}</dd></div>
                        <div class="flex justify-between items-center py-3 px-4 bg-gray-50/50 rounded-lg"><dt class="text-gray-600 font-medium flex items-center"><i class="fas fa-envelope mr-2 text-gray-400"></i>Email</dt><dd class="font-semibold text-gray-800 text-right">{{ $user->email }}</dd></div>
                        <div class="flex justify-between items-center py-3 px-4 bg-gray-50/50 rounded-lg"><dt class="text-gray-600 font-medium flex items-center"><i class="fas fa-id-card mr-2 text-gray-400"></i>Nomor Anggota</dt><dd class="font-semibold text-gray-800 text-right">{{ $user->nomor_anggota ?? '-' }}</dd></div>
                        <div class="flex justify-between items-center py-3 px-4 bg-gray-50/50 rounded-lg">
                            <dt class="text-gray-600 font-medium flex items-center"><i class="fas fa-birthday-cake mr-2 text-gray-400"></i>Tanggal Lahir</dt>
                            <dd class="font-semibold text-gray-800 text-right">{{ $user->date_of_birth ? $user->date_of_birth->isoFormat('DD MMMM YYYY') : 'Belum diatur' }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-3 px-4 bg-gray-50/50 rounded-lg">
                            <dt class="text-gray-600 font-medium flex items-center"><i class="fas fa-hourglass-half mr-2 text-gray-400"></i>Umur</dt>
                            <dd class="font-semibold text-gray-800 text-right">{{ $user->age ? $user->age . ' tahun' : '-' }}</dd> {{-- Menggunakan accessor age --}}
                        </div>
                        <div class="flex justify-between items-center py-3 px-4 bg-gray-50/50 rounded-lg"><dt class="text-gray-600 font-medium flex items-center"><i class="fas fa-calendar-plus mr-2 text-gray-400"></i>Tanggal Bergabung</dt><dd class="font-semibold text-gray-800 text-right">{{ $user->created_at->isoFormat('DD MMMM YYYY') }}</dd></div>
                        <div class="flex justify-between items-center py-3 px-4 bg-gray-50/50 rounded-lg"><dt class="text-gray-600 font-medium flex items-center"><i class="fas fa-toggle-on mr-2 text-gray-400"></i>Status Akun</dt><dd><span class="status-badge {{ ($user->status ?? 'active') == 'active' ? 'status-active' : 'status-inactive' }}">{{ ucfirst($user->status ?? 'Aktif') }}</span></dd></div>
                        <div class="flex justify-between items-center py-3 px-4 bg-gray-50/50 rounded-lg"><dt class="text-gray-600 font-medium flex items-center"><i class="fas fa-clock mr-2 text-gray-400"></i>Login Terakhir</dt><dd class="font-semibold text-gray-800 text-right">{{ $user->last_login_at ? $user->last_login_at->isoFormat('DD MMMM YYYY, HH:mm') : 'Belum tercatat' }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection