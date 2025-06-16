@extends('layouts.app')

@section('title', 'Edit Barang - Koperasi')
@section('page-title', 'Edit Barang: ' . $barang->nama_barang)
@section('page-subtitle', 'Perbarui detail barang koperasi')

@section('content')
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in max-w-3xl mx-auto">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Formulir Edit Barang</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('pengurus.barang.update', $barang->id) }}" method="POST" class="space-y-6" data-validate>
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-forms.input type="text" name="nama_barang" label="Nama Barang" :value="$barang->nama_barang" :required="true" />
                    
                    <x-forms.input type="text" name="kode_barang" label="Kode Barang (Opsional)" :value="$barang->kode_barang" placeholder="Otomatis jika kosong & belum ada" />
                    
                    <x-forms.select name="unit_usaha_id" label="Unit Usaha" :options="$unitUsahas->pluck('nama_unit_usaha', 'id')" :value="$barang->unit_usaha_id" :required="true" />
                    
                    @php
                        $satuanOptions = collect($satuans ?? [])->mapWithKeys(function ($item) { return [$item => ucfirst($item)]; });
                    @endphp
                    <x-forms.select name="satuan" label="Satuan Barang" :options="$satuanOptions" :value="$barang->satuan" :required="true" />

                    <x-forms.input 
                        type="number" 
                        name="harga_beli" 
                        label="Harga Beli" 
                        :value="$barang->harga_beli" 
                        :required="true" 
                        min="0" {{-- Atribut individual --}}
                        step="any" {{-- Atribut individual --}}
                    />
                    
                    <x-forms.input 
                        type="number" 
                        name="harga_jual" 
                        label="Harga Jual" 
                        :value="$barang->harga_jual" 
                        :required="true" 
                        min="0" {{-- Atribut individual --}}
                        step="any" {{-- Atribut individual --}}
                    />
                    
                    <div class="md:col-span-2"> {{-- Stok dibuat read-only di form edit --}}
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Stok Saat Ini</label>
                        <p class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-xl text-gray-700">
                            {{ $barang->stok }} {{ $barang->satuan }}
                        </p>
                        <small class="text-xs text-gray-500 mt-1">Perubahan stok dilakukan melalui menu 
                            <a href="{{ route('pengurus.stok.index') }}#{{-- atau link ke form penyesuaian untuk barang ini --}}" 
                               class="text-blue-600 hover:underline">Pencatatan Stok</a>.
                        </small>
                    </div>
                </div>
                
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi Barang (Opsional)</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 placeholder-gray-400">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
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
                        Update Barang
                    </x-forms.button>
                </div>
            </form>
        </div>
    </div>
@endsection