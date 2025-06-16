@extends('layouts.app')

@section('title', 'Profil Saya - Koperasi')

@section('page-title', 'Profil Akun Saya')
@section('page-subtitle', 'Lihat dan kelola informasi pribadi Anda')

@push('styles')
<style>
    .profile-image-container { position: relative; display: inline-block; }
    .profile-image { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #e5e7eb; /* gray-200 */ box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; }
    .profile-image:hover { transform: scale(1.05); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15); }
    .profile-initial { width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #60a5fa 0%, #a78bfa 100%); /* blue-400 to purple-400 */ display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: bold; color: white; border: 4px solid #e5e7eb; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; }
    .profile-initial:hover { transform: scale(1.05); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15); }
    .info-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); border: 1px solid rgba(229, 231, 235, 0.5); /* gray-200 with opacity */ }
    .status-badge { display: inline-flex; align-items: center; padding: 0.375rem 0.875rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 600; text-transform: capitalize; }
    .status-active { background-color: #dcfce7; /* green-100 */ color: #166534; /* green-800 */ }
    .status-inactive { background-color: #fee2e2; /* red-100 */ color: #991b1b; /* red-800 */ }
</style>
@endpush

@section('content')
<div class="animate-fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="info-card rounded-2xl shadow-xl p-8">
                <div class="text-center">
                    <div class="profile-image-container mb-6">
                        <img src="{{ Auth::user()->profile_photo_url }}" 
                             alt="Foto Profil {{ Auth::user()->name }}" 
                             class="profile-image mx-auto"
                             onerror="this.onerror=null; this.style.display='none'; document.getElementById('profileInitialDiv').style.display='flex';"> {{-- Fallback ke inisial jika gambar error --}}
                        <div id="profileInitialDiv" class="profile-initial mx-auto" style="{{ (Auth::user()->profile_photo_path && !str_contains(Auth::user()->profile_photo_url, 'placeholder_avatar.png')) ? 'display:none;' : 'display:flex;' }}">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ Auth::user()->name }}</h3>
                    <p class="text-gray-600 mb-4 text-sm">{{ Auth::user()->email }}</p>
                    
                    <div class="flex justify-center mb-6">
                        <span class="status-badge {{ (Auth::user()->status ?? 'active') == 'active' ? 'status-active' : 'status-inactive' }}">
                            <i class="fas fa-circle text-xs mr-2 {{ (Auth::user()->status ?? 'active') == 'active' ? 'text-green-500' : 'text-red-500' }}"></i>
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </div>
                    
                    <a href="{{ route('anggota.profil.edit') }}" class="block">
                        <x-forms.button variant="primary" icon="user-edit" class="w-full">
                            Edit Profil & Password
                        </x-forms.button>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Informasi Detail Anggota -->
        <div class="lg:col-span-2">
            <div class="info-card rounded-2xl shadow-xl">
                <div class="p-6 border-b border-gray-200/80">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-id-card-alt mr-3 text-blue-500"></i>
                        Informasi Keanggotaan
                    </h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-3">
                        @php
                            $profileInfo = [
                                ['icon' => 'user', 'label' => 'Nama Lengkap', 'value' => Auth::user()->name],
                                ['icon' => 'envelope', 'label' => 'Email', 'value' => Auth::user()->email],
                                ['icon' => 'id-badge', 'label' => 'Nomor Anggota', 'value' => Auth::user()->nomor_anggota ?? '-'],
                                ['icon' => 'calendar-plus', 'label' => 'Tanggal Bergabung', 'value' => Auth::user()->created_at->isoFormat('DD MMMM YYYY')],
                                ['icon' => 'user-check', 'label' => 'Status Akun', 'value_html' => "<span class='status-badge " . ((Auth::user()->status ?? 'active') == 'active' ? 'status-active' : 'status-inactive') . "'>" . ucfirst(Auth::user()->status ?? 'Aktif') . "</span>"],
                                ['icon' => 'clock', 'label' => 'Login Terakhir', 'value' => Auth::user()->last_login_at ? Auth::user()->last_login_at->isoFormat('DD MMMM YYYY, HH:mm') : 'Belum tercatat'],
                            ];
                        @endphp

                        @foreach($profileInfo as $info)
                        <div class="flex justify-between items-center py-2.5 px-4 bg-gray-50/70 rounded-lg">
                            <span class="text-gray-600 font-medium text-sm flex items-center">
                                <i class="fas fa-{{ $info['icon'] }} mr-2.5 text-gray-400 w-4 text-center"></i>
                                {{ $info['label'] }}
                            </span>
                            @if(isset($info['value_html']))
                                {!! $info['value_html'] !!}
                            @else
                                <span class="font-semibold text-gray-800 text-sm">{{ $info['value'] }}</span>
                            @endif
                        </div>
                        @endforeach
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection