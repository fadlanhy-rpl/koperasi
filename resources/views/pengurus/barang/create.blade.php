@extends('layouts.app')

@section('title', 'Tambah Barang Baru - Koperasi')
@section('page-title', 'Tambah Barang Baru')
@section('page-subtitle', 'Masukkan detail barang untuk unit usaha koperasi')

@section('content')
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in max-w-3xl mx-auto">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Formulir Tambah Barang</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('pengurus.barang.store') }}" method="POST" class="space-y-6" data-validate>
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-forms.input type="text" name="nama_barang" label="Nama Barang" placeholder="Contoh: Pulpen Pilot G2" :required="true" />
                    
                    <x-forms.input type="text" name="kode_barang" label="Kode Barang (Opsional)" placeholder="Otomatis jika kosong" />
                    
                    <x-forms.select name="unit_usaha_id" label="Unit Usaha" :options="$unitUsahas->pluck('nama_unit_usaha', 'id')" placeholder="Pilih unit usaha" :required="true" />
                    
                    @php
                        $satuanOptions = collect($satuans ?? [])->mapWithKeys(function ($item) { return [$item => ucfirst($item)]; });
                    @endphp
                    <x-forms.select name="satuan" label="Satuan Barang" :options="$satuanOptions" placeholder="Pilih satuan" :required="true" />

                    <x-forms.input 
                        type="number" 
                        name="harga_beli" 
                        label="Harga Beli" 
                        placeholder="0" 
                        :required="true" 
                        min="0" {{-- Atribut individual --}}
                        step="any" {{-- Atribut individual --}}
                    />
                    
                    <x-forms.input 
                        type="number" 
                        name="harga_jual" 
                        label="Harga Jual" 
                        placeholder="0" 
                        :required="true" 
                        min="0" {{-- Atribut individual --}}
                        step="any" {{-- Atribut individual --}}
                    />
                    
                    <x-forms.input 
                        type="number" 
                        name="stok" 
                        label="Stok Awal" 
                        placeholder="0" 
                        :required="true" 
                        min="0" {{-- Atribut individual --}}
                        step="1" {{-- Atribut individual --}}
                    />
                </div>
                
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Barang (Opsional)</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 placeholder-gray-400" 
                              placeholder="Jelaskan detail barang...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('pengurus.barang.index') }}">
                         <x-forms.button type="button" variant="secondary">
                            Batal
                        </x-forms.button>
                    </a>
                    <x-forms.button type="submit" variant="primary" icon="save">
                        Simpan Barang
                    </x-forms.button>
                </div>
            </form>
        </div>
    </div>
@endsection