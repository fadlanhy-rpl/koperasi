@extends('layouts.app')

@section('title', 'Catat Pembayaran Cicilan - Koperasi')

@section('page-title', 'Pembayaran Cicilan')
@section('page-subtitle', 'Catat pembayaran angsuran untuk transaksi #' . $pembelian->kode_pembelian)

@section('content')
<div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in max-w-xl mx-auto">
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-xl font-bold text-gray-800">Form Pembayaran Cicilan</h3>
        <p class="text-sm text-gray-500 mt-1">Untuk Transaksi: <span class="font-semibold text-blue-600">{{ $pembelian->kode_pembelian }}</span></p>
        <p class="text-sm text-gray-500">Anggota: <span class="font-semibold">{{ $pembelian->user->name }}</span></p>
    </div>
    <div class="p-6">
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm">
            <div class="flex justify-between mb-1">
                <span class="text-gray-600">Total Tagihan Awal:</span>
                <span class="font-semibold text-gray-800">@rupiah($pembelian->total_harga)</span>
            </div>
            <div class="flex justify-between">
                <span class="text-red-600 font-medium">Sisa Tagihan Saat Ini:</span>
                <span class="font-bold text-red-600">@rupiah($sisaTagihan)</span>
            </div>
        </div>

        <form action="{{ route('pengurus.pembayaran-cicilan.store', $pembelian->id) }}" method="POST" class="space-y-6" data-validate>
            @csrf
            
            <x-forms.input 
                type="number" 
                name="jumlah_bayar" 
                label="Jumlah Bayar Cicilan" 
                placeholder="0" 
                :required="true" 
                :attributes="['min' => '0.01', 'max' => $sisaTagihan > 0 ? $sisaTagihan : '0.01', 'step' => 'any']"
            />
            
            <x-forms.input 
                type="date" 
                name="tanggal_bayar" 
                label="Tanggal Bayar" 
                :value="date('Y-m-d')" 
                :required="true"
            />

            <div>
                <label for="keterangan_cicilan" class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan (Opsional)</label>
                <textarea id="keterangan_cicilan" name="keterangan" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 placeholder-gray-400" 
                          placeholder="Contoh: Pembayaran angsuran ke-2">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('pengurus.transaksi-pembelian.show', $pembelian->id) }}">
                     <x-forms.button type="button" variant="secondary">
                        Batal
                    </x-forms.button>
                </a>
                @if($sisaTagihan > 0)
                <x-forms.button type="submit" variant="success" icon="check-circle">
                    Simpan Pembayaran
                </x-forms.button>
                @else
                 <x-forms.button type="button" variant="success" icon="check-double" class="opacity-50 cursor-not-allowed" disabled>
                    Sudah Lunas
                </x-forms.button>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection