@extends('layouts.app')

@section('title', 'Informasi Simpanan Saya - Koperasi')

@section('page-title', 'Rincian Simpanan Saya')
@section('page-subtitle', 'Lihat semua transaksi dan saldo simpanan Anda')

@section('content')
<div class="animate-fade-in">
    <!-- Ringkasan Saldo -->
    <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-6 rounded-2xl shadow-xl text-white">
            <p class="text-sm text-blue-100 mb-1">Total Simpanan Pokok</p>
            <p class="text-3xl font-bold">@rupiah($simpanan['total_pokok'] ?? 0)</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-6 rounded-2xl shadow-xl text-white">
            <p class="text-sm text-green-100 mb-1">Total Simpanan Wajib</p>
            <p class="text-3xl font-bold">@rupiah($simpanan['total_wajib'] ?? 0)</p>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-amber-600 p-6 rounded-2xl shadow-xl text-white">
            <p class="text-sm text-yellow-100 mb-1">Saldo Simpanan Sukarela</p>
            <p class="text-3xl font-bold">@rupiah($simpanan['saldo_sukarela_terkini'] ?? 0)</p>
        </div>
    </div>

    <!-- Tab Navigasi untuk Jenis Simpanan -->
    <div x-data="{ activeTabSimpanan: 'pokok' }" class="mb-6">
        <div class="border-b border-gray-200 bg-white/50 backdrop-blur-sm rounded-t-xl shadow">
            <nav class="-mb-px flex space-x-4 px-6" aria-label="Tabs">
                <button @click="activeTabSimpanan = 'pokok'" 
                        :class="{ 'border-blue-500 text-blue-600 font-semibold': activeTabSimpanan === 'pokok', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTabSimpanan !== 'pokok' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    Riwayat Simpanan Pokok
                </button>
                <button @click="activeTabSimpanan = 'wajib'"
                        :class="{ 'border-blue-500 text-blue-600 font-semibold': activeTabSimpanan === 'wajib', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTabSimpanan !== 'wajib' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    Riwayat Simpanan Wajib
                </button>
                <button @click="activeTabSimpanan = 'sukarela'"
                        :class="{ 'border-blue-500 text-blue-600 font-semibold': activeTabSimpanan === 'sukarela', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTabSimpanan !== 'sukarela' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    Riwayat Simpanan Sukarela
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="mt-0 bg-white/80 backdrop-blur-lg rounded-b-2xl shadow-lg border border-t-0 border-white/20 p-6">
            <!-- Simpanan Pokok Content -->
            <div x-show="activeTabSimpanan === 'pokok'" x-transition.opacity>
                @if(isset($simpanan['pokok']) && $simpanan['pokok']->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[500px] text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-200 bg-gray-50/50 text-xs uppercase text-gray-500">
                                    <th class="py-2.5 px-3 text-left">Tanggal Bayar</th>
                                    <th class="py-2.5 px-3 text-right">Jumlah</th>
                                    <th class="py-2.5 px-3 text-left">Keterangan</th>
                                    <th class="py-2.5 px-3 text-left">Dicatat Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($simpanan['pokok'] as $pokok)
                                <tr class="border-b border-gray-100 hover:bg-gray-50/30">
                                    <td class="py-2.5 px-3 text-gray-700">{{ \Carbon\Carbon::parse($pokok->tanggal_bayar)->isoFormat('DD MMMM YYYY') }}</td>
                                    <td class="py-2.5 px-3 font-semibold text-gray-800 text-right">@rupiah($pokok->jumlah)</td>
                                    <td class="py-2.5 px-3 text-gray-600">{{ $pokok->keterangan ?: '-' }}</td>
                                    <td class="py-2.5 px-3 text-gray-500">{{ $pokok->pengurus->name ?? 'Sistem' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-6 italic">Belum ada data simpanan pokok.</p>
                @endif
            </div>

            <!-- Simpanan Wajib Content -->
            <div x-show="activeTabSimpanan === 'wajib'" x-transition.opacity>
                <div id="riwayatAnggotaWajibContent">
                    @include('anggota.simpanan.partials._riwayat_wajib_table', ['riwayat_wajib' => $simpanan['wajib'] ?? collect()])
                </div>
                <div id="paginationLinksAnggotaWajib" class="mt-4">
                    @if(isset($simpanan['wajib']) && $simpanan['wajib']->hasPages())
                        {{ $simpanan['wajib']->appends(['tab' => 'wajib'])->links('vendor.pagination.tailwind') }}
                    @endif
                </div>
            </div>

            <!-- Simpanan Sukarela Content -->
            <div x-show="activeTabSimpanan === 'sukarela'" x-transition.opacity>
                 <div id="riwayatAnggotaSukarelaContent">
                    @include('anggota.simpanan.partials._riwayat_sukarela_table', ['riwayat_sukarela' => $simpanan['sukarela'] ?? collect()])
                </div>
                <div id="paginationLinksAnggotaSukarela" class="mt-4">
                     @if(isset($simpanan['sukarela']) && $simpanan['sukarela']->hasPages())
                        {{ $simpanan['sukarela']->appends(['tab' => 'sukarela'])->links('vendor.pagination.tailwind') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="mt-8 flex justify-start">
        <a href="{{ route('anggota.dashboard') }}"> 
            <x-forms.button type="button" variant="secondary" icon="arrow-left">
                Kembali ke Dashboard
            </x-forms.button>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Alpine.js sudah di-load di layouts.app.blade.php
    document.addEventListener('DOMContentLoaded', function() {
        function handleAnggotaAjaxPagination(containerId, paginationLinksId, tabName, pageParamName) {
            const paginationContainer = document.getElementById(paginationLinksId);
            if (!paginationContainer) return;

            paginationContainer.addEventListener('click', function(event) {
                const target = event.target.closest('a');
                 if (target && target.href && !target.classList.contains('disabled') && !target.querySelector('span[aria-disabled="true"]')) {
                    event.preventDefault();
                    const url = new URL(target.href);
                    // URL dari paginator sudah membawa parameter tab, jadi tidak perlu set manual
                    // url.searchParams.set('tab', tabName); // Ini akan terduplikasi jika sudah ada
                    
                    KoperasiApp.makeRequest(url.toString(), { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                        .then(data => {
                            // Controller SimpananAnggotaController perlu dimodifikasi untuk return JSON dengan struktur ini
                            if (data.html && data.pagination) {
                                document.getElementById(containerId).innerHTML = data.html;
                                document.getElementById(paginationLinksId).innerHTML = data.pagination;
                                window.history.pushState({path:url.toString()},'',url.toString());
                            } else {
                                 KoperasiApp.showNotification('Gagal memuat data paginasi, struktur data tidak sesuai.', 'error');
                            }
                        })
                        .catch(error => {
                            KoperasiApp.showNotification(`Gagal memuat data untuk ${tabName}.`, 'error');
                        });
                }
            });
        }

        handleAnggotaAjaxPagination('riwayatAnggotaWajibContent', 'paginationLinksAnggotaWajib', 'wajib', 'page_wajib');
        handleAnggotaAjaxPagination('riwayatAnggotaSukarelaContent', 'paginationLinksAnggotaSukarela', 'sukarela', 'page_sukarela');
        
        // Untuk mengaktifkan tab berdasarkan parameter URL saat halaman dimuat
        const currentUrlParamsAnggota = new URLSearchParams(window.location.search);
        const currentTabAnggota = currentUrlParamsAnggota.get('tab');
        if (currentTabAnggota) {
            const alpineComponentAnggota = document.querySelector('[x-data]');
            if (alpineComponentAnggota && alpineComponentAnggota.__x) {
                alpineComponentAnggota.__x.$data.activeTabSimpanan = currentTabAnggota;
            } else {
                setTimeout(() => { // Retry jika Alpine belum init
                    const alpineCompRetryAnggota = document.querySelector('[x-data]');
                    if(alpineCompRetryAnggota && alpineCompRetryAnggota.__x) alpineCompRetryAnggota.__x.$data.activeTabSimpanan = currentTabAnggota;
                }, 150);
            }
        }
    });
</script>
@endpush