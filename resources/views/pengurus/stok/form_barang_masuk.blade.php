@extends('layouts.app')

@section('title', 'Catat Barang Masuk - Koperasi')

@section('page-title', 'Pencatatan Barang Masuk')
@section('page-subtitle', 'Tambah kuantitas stok untuk: ' . $barang->nama_barang)

@section('content')
<div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in max-w-xl mx-auto">
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-xl font-bold text-gray-800">Form Barang Masuk: <span class="text-blue-600">{{ $barang->nama_barang }}</span></h3>
        <p class="text-sm text-gray-500">Stok Saat Ini: <span class="font-semibold">{{ $barang->stok }} {{ $barang->satuan }}</span></p>
    </div>
    <div class="p-6">
        <form action="{{ route('pengurus.stok.storeBarangMasuk', $barang->id) }}" method="POST" class="space-y-6" data-validate>
            @csrf
            
            <x-forms.input 
                type="number" 
                name="jumlah" 
                label="Jumlah Barang Masuk" 
                placeholder="0" 
                :required="true" 
                min="1"  {{-- SUDAH BENAR --}}
                step="1" {{-- SUDAH BENAR --}}
            />
            
            {{-- Opsional: Update Harga Beli Saat Barang Masuk --}}
            {{-- <x-forms.input 
                type="number" 
                name="harga_beli_baru" 
                label="Harga Beli Baru (Opsional)" 
                placeholder="{{ $barang->harga_beli }}" 
                attributes="min=0 step=any"
                :value="old('harga_beli_baru', $barang->harga_beli)"
            /> 
            <small class="text-xs text-gray-500 -mt-4 block">Kosongkan jika harga beli tidak berubah.</small> --}}
            
            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan (Opsional)</label>
                <textarea id="keterangan" name="keterangan" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 placeholder-gray-400" 
                          placeholder="Contoh: Pembelian dari Supplier XYZ, Penerimaan barang retur">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('pengurus.barang.show', $barang->id) }}">
                     <x-forms.button type="button" variant="secondary">
                        Batal
                    </x-forms.button>
                </a>
                <x-forms.button type="submit" variant="success" icon="plus-circle">
                    Tambah Stok
                </x-forms.button>
            </div>
        </form>
    </div>
</div>
@endsection