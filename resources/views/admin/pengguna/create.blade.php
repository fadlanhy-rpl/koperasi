@extends('layouts.app')

@section('title', 'Tambah Pengguna - Koperasi')
@section('page-title', 'Tambah Pengguna Baru')
@section('page-subtitle', 'Masukkan detail pengguna untuk mendaftarkannya ke sistem')

@section('content')
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Formulir Tambah Pengguna</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.manajemen-pengguna.store') }}" method="POST" class="space-y-6" data-validate>
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-forms.input type="text" name="name" label="Nama Lengkap" placeholder="Masukkan nama lengkap" :required="true" />
                    
                    <x-forms.input type="email" name="email" label="Alamat Email" placeholder="contoh@email.com" :required="true" />
                    
                    {{-- Nomor Anggota di backend kita adalah 'nomor_anggota' dan opsional saat registrasi --}}
                    <x-forms.input type="text" name="nomor_anggota" label="No. Anggota (Opsional)" placeholder="Contoh: KOP001 atau biarkan kosong" /> 
                    
                    <x-forms.select 
                        name="role" 
                        label="Peran (Role)" 
                        :options="$rolesForForm" {{-- Menggunakan variabel dari controller --}}
                        placeholder="Pilih peran pengguna" 
                        :required="true" 
                    />
                    
                    <x-forms.input type="password" name="password" label="Password" placeholder="Minimal 8 karakter" :required="true" />
                    
                    <x-forms.input type="password" name="password_confirmation" label="Konfirmasi Password" placeholder="Ulangi password" :required="true" />
                    
                    <div class="md:col-span-2"> {{-- Status bisa dibuat full width atau sesuai grid --}}
                        <x-forms.select 
                            name="status" 
                            label="Status Akun" 
                            :options="$statusesForForm" {{-- Menggunakan variabel dari controller --}}
                            :value="old('status', 'active')" {{-- Default ke active --}}
                            :required="true" 
                        />
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('admin.manajemen-pengguna.index') }}">
                         <x-forms.button type="button" variant="secondary">
                            Batal
                        </x-forms.button>
                    </a>
                    <x-forms.button type="submit" variant="primary" icon="save">
                        Simpan Pengguna
                    </x-forms.button>
                </div>
            </form>
        </div>
    </div>
@endsection