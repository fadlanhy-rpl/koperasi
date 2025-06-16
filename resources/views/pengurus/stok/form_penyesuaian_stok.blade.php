@extends('layouts.app')

@section('title', 'Penyesuaian Stok - Koperasi')

@section('page-title', 'Penyesuaian Stok Barang')
@section('page-subtitle', 'Sesuaikan jumlah stok fisik untuk: ' . $barang->nama_barang)

@section('content')
<div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in max-w-xl mx-auto">
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-xl font-bold text-gray-800">Form Penyesuaian Stok: <span class="text-blue-600">{{ $barang->nama_barang }}</span></h3>
        <p class="text-sm text-gray-500">Stok Tercatat Saat Ini: <span class="font-semibold">{{ $barang->stok }} {{ $barang->satuan }}</span></p>
    </div>
    <div class="p-6">
        <form action="{{ route('pengurus.stok.storePenyesuaianStok', $barang->id) }}" method="POST" class="space-y-6" data-validate>
            @csrf
            
            <x-forms.input 
                type="number" 
                name="stok_fisik" 
                label="Jumlah Stok Fisik (Hasil Hitungan)" 
                placeholder="0" 
                :value="old('stok_fisik', $barang->stok)" {{-- Default ke stok saat ini --}}
                :required="true" 
                min="0"  {{-- Atribut diteruskan secara individual --}}
                step="1" {{-- Atribut diteruskan secara individual --}}
            />
            
            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan Penyesuaian <span class="text-red-500">*</span></label>
                <textarea id="keterangan" name="keterangan" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 placeholder-gray-400" 
                          placeholder="Contoh: Hasil stock opname bulanan, Koreksi kesalahan input" required>{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('pengurus.barang.show', $barang->id) }}"> {{-- Atau ke halaman stok index --}}
                     <x-forms.button type="button" variant="secondary">
                        Batal
                    </x-forms.button>
                </a>
                <x-forms.button type="submit" variant="primary" icon="check-circle">
                    Simpan Penyesuaian
                </x-forms.button>
            </div>
        </form>
    </div>
</div>
@endsection