@extends('layouts.app')

@section('title', 'Rincian Simpanan per Anggota - Koperasi')

@section('page-title', 'Laporan Rincian Simpanan per Anggota')
@section('page-subtitle', 'Detail total simpanan untuk setiap anggota koperasi')

@section('content')
<div class="animate-fade-in">
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                <h3 class="text-xl font-bold text-gray-800">Rincian Simpanan per Anggota</h3>
                <form method="GET" action="{{ route('pengurus.laporan.simpanan.rincianPerAnggota') }}" class="flex gap-2 w-full sm:w-auto sm:max-w-xs">
                    <input type="text" name="search_anggota" value="{{ request('search_anggota') }}" placeholder="Cari nama/no. anggota..." 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                    <x-forms.button type="submit" variant="secondary" size="sm" class="py-2.5">
                        <i class="fas fa-search"></i>
                    </x-forms.button>
                </form>
            </div>
        </div>
        
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-xs uppercase text-gray-500">
                            <th class="py-2.5 px-3 text-left">No.</th>
                            <th class="py-2.5 px-3 text-left">Nama Anggota</th>
                            <th class="py-2.5 px-3 text-left">No. Anggota</th>
                            <th class="py-2.5 px-3 text-right">Total Simp. Pokok</th>
                            <th class="py-2.5 px-3 text-right">Total Simp. Wajib</th>
                            <th class="py-2.5 px-3 text-right">Saldo Simp. Sukarela</th>
                            <th class="py-2.5 px-3 text-right font-semibold">Total Semua Simpanan</th>
                            <th class="py-2.5 px-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan_per_anggota as $index => $anggota)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="py-2.5 px-3 text-gray-700">{{ $laporan_per_anggota->firstItem() + $index }}</td>
                                <td class="py-2.5 px-3 font-medium text-gray-800">{{ $anggota->name }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $anggota->nomor_anggota ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-right text-gray-700">@rupiah($anggota->total_simpanan_pokok)</td>
                                <td class="py-2.5 px-3 text-right text-gray-700">@rupiah($anggota->total_simpanan_wajib)</td>
                                <td class="py-2.5 px-3 text-right text-gray-700">@rupiah($anggota->saldo_simpanan_sukarela)</td>
                                <td class="py-2.5 px-3 text-right font-bold text-blue-600">
                                    @rupiah($anggota->total_simpanan_pokok + $anggota->total_simpanan_wajib + $anggota->saldo_simpanan_sukarela)
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <a href="{{ route('pengurus.simpanan.riwayatAnggota', $anggota->id) }}" class="text-blue-600 hover:text-blue-800 p-1.5 hover:bg-blue-50 rounded-lg transition-all duration-300" title="Lihat Riwayat Detail">
                                        <i class="fas fa-eye fa-fw"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-10 text-gray-500">
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
            
            @if($laporan_per_anggota->hasPages())
                <div class="mt-6">
                    {{ $laporan_per_anggota->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>

    <div class="mt-8 flex justify-start">
        <a href="{{ route('pengurus.dashboard') }}"> 
            <x-forms.button type="button" variant="secondary" icon="arrow-left">
                Kembali ke Dashboard Pengurus
            </x-forms.button>
        </a>
    </div>
</div>
@endsection

@push('scripts')
{{-- Script AJAX untuk filter jika diperlukan, mirip dengan halaman lain --}}
@endpush