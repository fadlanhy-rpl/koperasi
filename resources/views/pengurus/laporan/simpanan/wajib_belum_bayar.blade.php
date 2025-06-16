@extends('layouts.app')

@section('title', 'Laporan Simpanan Wajib Belum Bayar - Koperasi')

@section('page-title', 'Simpanan Wajib Belum Dibayar')
@section('page-subtitle', 'Daftar anggota yang belum melunasi simpanan wajib untuk periode tertentu')

@section('content')
<div class="animate-fade-in">
    <!-- Filter Section -->
    <div class="mb-6 bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
        <form method="GET" action="{{ route('pengurus.laporan.simpanan.wajibBelumBayar') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="bulan_filter_wjb" class="block text-sm font-medium text-gray-700 mb-1">Pilih Bulan:</label>
                    <select name="bulan" id="bulan_filter_wjb" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ ($periode['bulan'] ?? date('n')) == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tahun_filter_wjb" class="block text-sm font-medium text-gray-700 mb-1">Pilih Tahun:</label>
                    <select name="tahun" id="tahun_filter_wjb" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ ($periode['tahun'] ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex items-end">
                    <x-forms.button type="submit" variant="primary" size="md" icon="filter" class="w-full sm:w-auto py-2.5">
                        Tampilkan Laporan
                    </x-forms.button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Laporan -->
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Anggota Belum Bayar Simpanan Wajib</h3>
            <p class="text-sm text-gray-500">Periode: <span class="font-semibold">{{ \Carbon\Carbon::create()->month($periode['bulan'])->translatedFormat('F') }} {{ $periode['tahun'] }}</span></p>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-xs uppercase text-gray-500">
                            <th class="py-2.5 px-3 text-left">No.</th>
                            <th class="py-2.5 px-3 text-left">Nama Anggota</th>
                            <th class="py-2.5 px-3 text-left">No. Anggota</th>
                            <th class="py-2.5 px-3 text-left">Email</th>
                            <th class="py-2.5 px-3 text-center">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggota_belum_bayar_wajib as $index => $anggota)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="py-2.5 px-3 text-gray-700">{{ $anggota_belum_bayar_wajib->firstItem() + $index }}</td>
                                <td class="py-2.5 px-3 font-medium text-gray-800">{{ $anggota->name }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $anggota->nomor_anggota ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-gray-600">{{ $anggota->email }}</td>
                                <td class="py-2.5 px-3 text-center">
                                    {{-- Tombol untuk langsung ke form catat simpanan wajib untuk anggota ini & periode ini --}}
                                    <a href="{{ route('pengurus.simpanan.wajib.index', ['search_anggota' => $anggota->nomor_anggota, 'bulan' => $periode['bulan'], 'tahun' => $periode['tahun']]) }}#form-tambah-wajib" {{-- Arahkan ke form di halaman index simpanan wajib --}}
                                       class="text-green-600 hover:text-green-800 p-1.5 hover:bg-green-50 rounded-lg transition-all duration-300 text-xs font-medium inline-flex items-center" 
                                       title="Catat Pembayaran untuk {{ $anggota->name }}">
                                        <i class="fas fa-plus-circle mr-1"></i> Catat Bayar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-500">
                                     <div class="flex flex-col items-center">
                                        <i class="fas fa-check-circle text-4xl mb-3 text-green-400"></i>
                                        Semua anggota sudah membayar simpanan wajib untuk periode ini.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($anggota_belum_bayar_wajib->hasPages())
                <div class="mt-6">
                    {{ $anggota_belum_bayar_wajib->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>
    <div class="mt-8 flex justify-start">
        <a href="{{ route('pengurus.dashboard') }}"> {{-- Atau ke halaman utama laporan --}}
            <x-forms.button type="button" variant="secondary" icon="arrow-left">
                Kembali
            </x-forms.button>
        </a>
    </div>
</div>
@endsection

@push('scripts')
{{-- Tidak ada JS spesifik untuk halaman ini, filter via GET request biasa --}}
@endpush