@extends('layouts.app')

@section('title', 'Rekapitulasi Total Simpanan - Koperasi')

@section('page-title', 'Rekapitulasi Total Simpanan')
@section('page-subtitle', 'Ringkasan keseluruhan dana simpanan koperasi')

@section('content')
<div class="animate-fade-in">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-cards.stats_card 
            title="Total Simpanan Pokok"
            :value="'Rp ' . number_format($rekapitulasi['total_simpanan_pokok'] ?? 0, 0, ',', '.')"
            icon="money-check-alt"
            color="blue"
            delay="0.1s"
        />
        <x-cards.stats_card 
            title="Total Simpanan Wajib"
            :value="'Rp ' . number_format($rekapitulasi['total_simpanan_wajib'] ?? 0, 0, ',', '.')"
            icon="calendar-alt"
            color="green"
            delay="0.2s"
        />
        <x-cards.stats_card 
            title="Total Saldo Simp. Sukarela"
            :value="'Rp ' . number_format($rekapitulasi['total_simpanan_sukarela_aktif'] ?? 0, 0, ',', '.')"
            icon="hand-holding-heart"
            color="yellow"
            delay="0.3s"
        />
        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 p-6 rounded-2xl shadow-xl text-white animate-bounce-in" style="animation-delay: 0.4s">
            <p class="text-sm text-purple-100 mb-1">Grand Total Semua Simpanan</p>
            <p class="text-3xl font-bold">@rupiah($rekapitulasi['grand_total_simpanan'] ?? 0)</p>
            <div class="mt-4 bg-white/20 rounded-full h-2.5 overflow-hidden">
                <div class="h-full bg-white rounded-full" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Detail Rekapitulasi</h3>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[400px] text-sm">
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4 font-medium text-gray-700">Total Simpanan Pokok Terkumpul</td>
                        <td class="py-3 px-4 text-right font-semibold text-gray-800">@rupiah($rekapitulasi['total_simpanan_pokok'] ?? 0)</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4 font-medium text-gray-700">Total Simpanan Wajib Terkumpul</td>
                        <td class="py-3 px-4 text-right font-semibold text-gray-800">@rupiah($rekapitulasi['total_simpanan_wajib'] ?? 0)</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4 font-medium text-gray-700">Total Saldo Aktif Simpanan Sukarela</td>
                        <td class="py-3 px-4 text-right font-semibold text-gray-800">@rupiah($rekapitulasi['total_simpanan_sukarela_aktif'] ?? 0)</td>
                    </tr>
                    <tr class="border-t-2 border-gray-200 bg-gray-50">
                        <td class="py-3 px-4 font-bold text-gray-800 text-base">GRAND TOTAL SEMUA SIMPANAN</td>
                        <td class="py-3 px-4 text-right font-bold text-blue-600 text-base">@rupiah($rekapitulasi['grand_total_simpanan'] ?? 0)</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mt-6 text-xs text-gray-500 italic">
            * Total saldo aktif simpanan sukarela dihitung berdasarkan saldo akhir dari setiap anggota yang memiliki transaksi simpanan sukarela.
        </div>
    </div>

     <div class="mt-8 flex justify-start">
        {{-- Tombol kembali ke dashboard laporan atau dashboard pengurus --}}
        <a href="{{ route('pengurus.dashboard') }}"> 
            <x-forms.button type="button" variant="secondary" icon="arrow-left">
                Kembali ke Dashboard Pengurus
            </x-forms.button>
        </a>
    </div>
</div>
@endsection