@extends('layouts.app')

@section('title', 'Manajemen Simpanan Sukarela - Koperasi')
@section('page-title', 'Simpanan Sukarela Anggota')
@section('page-subtitle', 'Kelola setoran dan penarikan simpanan sukarela')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Transaksi Simpanan Sukarela -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-800">Catat Setoran Sukarela</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('pengurus.simpanan.sukarela.storeSetoran') }}" method="POST" class="space-y-6" data-validate>
                    @csrf
                    <x-forms.select name="user_id" label="Pilih Anggota" :options="$semuaAnggota->pluck('full_name_with_nomor', 'id')" placeholder="Pilih anggota" :required="true"/>
                    <x-forms.input 
                        type="number" 
                        name="jumlah" 
                        label="Jumlah Setoran" 
                        placeholder="0" 
                        :value="old('jumlah')"
                        :required="true" 
                        min="1"     {{-- Atribut individual --}}
                        step="any"  {{-- Atribut individual --}}
                    />
                    <x-forms.input type="date" name="tanggal_transaksi" label="Tanggal Setor" :value="old('tanggal_transaksi', date('Y-m-d'))" :required="true"/>
                    <div>
                        <label for="keterangan_setor_sukarela" class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan (Opsional)</label>
                        <textarea id="keterangan_setor_sukarela" name="keterangan" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500" placeholder="Catatan setoran...">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end pt-2">
                        <x-forms.button type="submit" variant="success" icon="plus-circle">Simpan Setoran</x-forms.button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-gray-800">Catat Penarikan Sukarela</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('pengurus.simpanan.sukarela.storePenarikan') }}" method="POST" class="space-y-6" data-validate>
                    @csrf
                     <x-forms.select name="user_id" label="Pilih Anggota" :options="$semuaAnggota->pluck('full_name_with_nomor', 'id')" placeholder="Pilih anggota" :required="true"/>
                    <x-forms.input 
                        type="number" 
                        name="jumlah" 
                        label="Jumlah Penarikan" 
                        placeholder="0" 
                        :value="old('jumlah')"
                        :required="true" 
                        min="1"     {{-- Atribut individual --}}
                        step="any"  {{-- Atribut individual --}}
                        helpText="Pastikan saldo anggota mencukupi."
                    />
                    <x-forms.input type="date" name="tanggal_transaksi" label="Tanggal Tarik" :value="old('tanggal_transaksi', date('Y-m-d'))" :required="true"/>
                     <div>
                        <label for="keterangan_tarik_sukarela" class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan (Opsional)</label>
                        <textarea id="keterangan_tarik_sukarela" name="keterangan" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500" placeholder="Catatan penarikan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end pt-2">
                        <x-forms.button type="submit" variant="danger" icon="minus-circle">Simpan Penarikan</x-forms.button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Daftar Anggota & Saldo Simpanan Sukarela -->
    <div class="lg:col-span-2">
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in">
            <div class="p-6 border-b border-gray-100">
                 <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <h3 class="text-xl font-bold text-gray-800">Saldo Simpanan Sukarela Anggota</h3>
                    <form method="GET" action="{{ route('pengurus.simpanan.sukarela.index') }}" class="flex gap-2 w-full sm:w-auto">
                        <input type="text" name="search_anggota" value="{{ request('search_anggota') }}" placeholder="Cari anggota..." class="w-full sm:w-auto px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                        <x-forms.button type="submit" variant="secondary" size="sm" class="py-2.5">
                            <i class="fas fa-search"></i>
                        </x-forms.button>
                    </form>
                </div>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[600px]">
                        <thead>
                            <tr class="border-b-2 border-gray-200 bg-gray-50">
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Nama Anggota</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">No. Anggota</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Saldo Terkini</th>
                                <th class="text-center py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anggotas as $anggota)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-300">
                                    <td class="py-3 px-4 font-medium text-gray-800">{{ $anggota->name }}</td>
                                    <td class="py-3 px-4 text-gray-600">{{ $anggota->nomor_anggota ?? '-' }}</td>
                                    <td class="py-3 px-4 text-gray-800 font-semibold text-right">
                                        @rupiah($anggota->saldo_sukarela_terkini ?? 0)
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('pengurus.simpanan.riwayatAnggota', $anggota->id) }}?tab=sukarela" class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-lg transition-all duration-300" title="Lihat Riwayat Lengkap">
                                            <i class="fas fa-eye fa-fw"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-users-slash text-4xl mb-3 text-gray-300"></i>
                                            Tidak ada data anggota ditemukan.
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