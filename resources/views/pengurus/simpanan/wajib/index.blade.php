@extends('layouts.app')

@section('title', 'Manajemen Simpanan Wajib - Koperasi')
@section('page-title', 'Simpanan Wajib Anggota')
@section('page-subtitle', 'Kelola dan catat simpanan wajib anggota per periode')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah Simpanan Wajib -->
    <div class="lg:col-span-1">
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-800">Catat Simpanan Wajib Baru</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('pengurus.simpanan.wajib.store') }}" method="POST" class="space-y-6" data-validate>
                    @csrf
                    
                    <x-forms.select 
                        name="user_id" 
                        label="Pilih Anggota" 
                        :options="$semuaAnggota->pluck('full_name_with_nomor', 'id')"
                        placeholder="Pilih anggota" 
                        :required="true"
                    />
                    
                    <x-forms.input 
                        type="number" 
                        name="jumlah" 
                        label="Jumlah Simpanan Wajib" 
                        placeholder="Contoh: 50000" 
                        :value="old('jumlah')"
                        :required="true" 
                        min="1"     {{-- Atribut individual --}}
                        step="any"  {{-- Atribut individual --}}
                    />
                    
                    <x-forms.input 
                        type="date" 
                        name="tanggal_bayar" 
                        label="Tanggal Bayar" 
                        :value="old('tanggal_bayar', date('Y-m-d'))" 
                        :required="true"
                    />

                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $bulanOptions = collect(range(1, 12))->mapWithKeys(function ($m) {
                                return [$m => \Carbon\Carbon::create()->month($m)->translatedFormat('F')];
                            });
                        @endphp
                        <x-forms.select 
                            name="bulan" 
                            label="Untuk Bulan" 
                            :options="$bulanOptions" 
                            :value="old('bulan', request('bulan', date('n')))" 
                            :required="true"
                        />
                        <x-forms.input 
                            type="number" 
                            name="tahun" 
                            label="Untuk Tahun" 
                            :value="old('tahun', request('tahun', date('Y')))" 
                            placeholder="YYYY" 
                            :required="true" 
                            min="{{ date('Y') - 5 }}" {{-- Atribut individual --}}
                            max="{{ date('Y') + 1 }}" {{-- Atribut individual --}}
                            step="1"              {{-- Atribut individual --}}
                        />
                    </div>

                    <div>
                        <label for="keterangan_wajib" class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan (Opsional)</label>
                        <textarea id="keterangan_wajib" name="keterangan" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 placeholder-gray-400" 
                                  placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="flex justify-end pt-2">
                        <x-forms.button type="submit" variant="primary" icon="save">
                            Simpan Setoran
                        </x-forms.button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Daftar Anggota & Status Simpanan Wajib -->
    <div class="lg:col-span-2">
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in">
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Status Simpanan Wajib - {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</h3>
                    <form method="GET" action="{{ route('pengurus.simpanan.wajib.index') }}" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        <select name="bulan" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                            @endforeach
                        </select>
                        <select name="tahun" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <select name="status_bayar_wajib" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">Semua Status</option>
                            <option value="sudah" {{ request('status_bayar_wajib') == 'sudah' ? 'selected' : '' }}>Sudah Bayar</option>
                            <option value="belum" {{ request('status_bayar_wajib') == 'belum' ? 'selected' : '' }}>Belum Bayar</option>
                        </select>
                        <input type="text" name="search_anggota" value="{{ request('search_anggota') }}" placeholder="Cari anggota..." class="w-full sm:w-auto px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                        <x-forms.button type="submit" variant="secondary" size="sm" class="py-2.5">
                            Filter
                        </x-forms.button>
                    </form>
                </div>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px]">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-gray-50">
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Nama Anggota</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">No. Anggota</th>
                                <th class="text-center py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Status Bayar</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Jumlah Bayar</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Tgl. Bayar</th>
                                <th class="text-center py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggotas as $anggota)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-300">
                                    <td class="py-3 px-4 font-medium text-gray-800">{{ $anggota->name }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $anggota->nomor_anggota ?? '-' }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @if($anggota->sudah_bayar_wajib_periode_ini)
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Sudah Bayar</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-gray-800 font-semibold text-right">
                                        @if($anggota->sudah_bayar_wajib_periode_ini && $anggota->detail_pembayaran_wajib)
                                            @rupiah($anggota->detail_pembayaran_wajib->jumlah)
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-gray-600">
                                        {{ $anggota->sudah_bayar_wajib_periode_ini && $anggota->detail_pembayaran_wajib ? \Carbon\Carbon::parse($anggota->detail_pembayaran_wajib->tanggal_bayar)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                         <a href="{{ route('pengurus.simpanan.riwayatAnggota', $anggota->id) }}?tab=wajib" class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-lg transition-all duration-300" title="Lihat Riwayat Lengkap">
                                            <i class="fas fa-eye fa-fw"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-users-slash text-4xl mb-3 text-gray-300"></i>
                                            Tidak ada data anggota ditemukan untuk filter ini.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($anggotas->hasPages())
                    <div class="mt-6">
                        {{ $anggotas->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection