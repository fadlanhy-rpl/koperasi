@extends('layouts.app')

@section('title', 'Tambah Unit Usaha - Koperasi')

@section('page-title', 'Tambah Unit Usaha Baru')
@section('page-subtitle', 'Buat entri untuk unit bisnis koperasi')

@section('content')
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in max-w-2xl mx-auto">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Formulir Tambah Unit Usaha</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('pengurus.unit-usaha.store') }}" method="POST" class="space-y-6" data-validate>
                @csrf
                
                <x-forms.input 
                    type="text" 
                    name="nama_unit_usaha" 
                    label="Nama Unit Usaha" 
                    placeholder="Contoh: Kantin Sekolah, Toko ATK" 
                    :required="true" 
                />
                
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi (Opsional)</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 placeholder-gray-400" 
                              placeholder="Jelaskan tentang unit usaha ini...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('pengurus.unit-usaha.index') }}">
                         <x-forms.button type="button" variant="secondary">
                            Batal
                        </x-forms.button>
                    </a>
                    <x-forms.button type="submit" variant="primary" icon="save">
                        Simpan Unit Usaha
                    </x-forms.button>
                </div>
            </form>
        </div>
    </div>
@endsection