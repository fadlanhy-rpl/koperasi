@extends('layouts.app')

@section('title', 'Riwayat Simpanan: ' . $anggota->name . ' - Koperasi')

@section('page-title', 'Riwayat Simpanan Anggota')
@section('page-subtitle', 'Detail transaksi simpanan untuk: ' . $anggota->name . ' ('. ($anggota->nomor_anggota ?? 'N/A') .')')

@section('content')
<div class="animate-fade-in">
    <!-- Info Anggota & Saldo Ringkas -->
    <div class="mb-6 bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $anggota->name }}</h2>
                <p class="text-gray-600">No. Anggota: {{ $anggota->nomor_anggota ?? '-' }} | Role: {{ ucfirst($anggota->role) }}</p>
            </div>
            <div class="mt-4 md:mt-0 grid grid-cols-1 sm:grid-cols-3 gap-4 text-center md:text-right">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Total Pokok</p>
                    <p class="text-lg font-semibold text-blue-600">@rupiah($total_pokok ?? 0)</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Total Wajib</p>
                    <p class="text-lg font-semibold text-green-600">@rupiah($total_wajib ?? 0)</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Saldo Sukarela</p>
                    <p class="text-lg font-semibold text-yellow-600">@rupiah($saldo_sukarela_terkini ?? 0)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigasi -->
    <div x-data="{ activeTab: '{{ request('tab', 'pokok') }}' }" class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-6" aria-label="Tabs">
                <button @click="activeTab = 'pokok'" 
                        :class="{ 'border-blue-500 text-blue-600 font-semibold': activeTab === 'pokok', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'pokok' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    Simpanan Pokok
                </button>
                <button @click="activeTab = 'wajib'"
                        :class="{ 'border-blue-500 text-blue-600 font-semibold': activeTab === 'wajib', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'wajib' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    Simpanan Wajib
                </button>
                <button @click="activeTab = 'sukarela'"
                        :class="{ 'border-blue-500 text-blue-600 font-semibold': activeTab === 'sukarela', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'sukarela' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    Simpanan Sukarela
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="mt-6">
            <!-- Simpanan Pokok Content -->
            <div x-show="activeTab === 'pokok'" x-transition.opacity>
                @include('pengurus.simpanan.partials._riwayat_pokok_table', ['riwayat_pokok' => $riwayat_pokok ?? collect()])
            </div>

            <!-- Simpanan Wajib Content -->
            <div x-show="activeTab === 'wajib'" x-transition.opacity>
                <div id="riwayatWajibContent">
                    @include('pengurus.simpanan.partials._riwayat_wajib_table', ['riwayat_wajib' => $riwayat_wajib ?? collect(), 'anggota' => $anggota])
                </div>
                <div id="paginationLinksWajib" class="mt-4">
                    @if(isset($riwayat_wajib) && $riwayat_wajib->hasPages())
                        {{ $riwayat_wajib->appends(['tab' => 'wajib'])->links('vendor.pagination.tailwind') }}
                    @endif
                </div>
            </div>

            <!-- Simpanan Sukarela Content -->
            <div x-show="activeTab === 'sukarela'" x-transition.opacity>
                 <div id="riwayatSukarelaContent">
                    @include('pengurus.simpanan.partials._riwayat_sukarela_table', ['riwayat_sukarela' => $riwayat_sukarela ?? collect(), 'anggota' => $anggota])
                </div>
                <div id="paginationLinksSukarela" class="mt-4">
                     @if(isset($riwayat_sukarela) && $riwayat_sukarela->hasPages())
                        {{ $riwayat_sukarela->appends(['tab' => 'sukarela'])->links('vendor.pagination.tailwind') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-start">
        <a href="{{ url()->previous() }}" {{-- Kembali ke halaman sebelumnya --}}
            class="inline-flex items-center px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-colors duration-300 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Pastikan Alpine.js sudah di-load via CDN di layouts.app.blade.php
    // <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    document.addEventListener('DOMContentLoaded', function() {
        function handleAjaxPagination(containerId, paginationLinksId, tabName) {
            const paginationContainer = document.getElementById(paginationLinksId);
            if (!paginationContainer) return;

            paginationContainer.addEventListener('click', function(event) {
                const target = event.target.closest('a');
                if (target && target.href && !target.classList.contains('disabled') && !target.querySelector('span[aria-disabled="true"]')) {
                    event.preventDefault();
                    const url = new URL(target.href);
                    // Pastikan parameter tab selalu ada untuk request AJAX
                    url.searchParams.set('tab', tabName); 

                    KoperasiApp.makeRequest(url.toString(), { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                        .then(data => {
                            if (data.html && data.pagination) {
                                document.getElementById(containerId).innerHTML = data.html;
                                document.getElementById(paginationLinksId).innerHTML = data.pagination;
                                // Update URL browser tanpa reload untuk UX yang lebih baik
                                window.history.pushState({path:url.toString()},'',url.toString());
                            } else {
                                console.error('Struktur data AJAX tidak sesuai untuk tab:', tabName, data);
                                KoperasiApp.showNotification('Gagal memuat data paginasi.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error(`Error fetching paginated data for ${tabName}:`, error);
                            KoperasiApp.showNotification(`Gagal memuat data untuk ${tabName}.`, 'error');
                        });
                }
            });
        }

        handleAjaxPagination('riwayatWajibContent', 'paginationLinksWajib', 'wajib');
        handleAjaxPagination('riwayatSukarelaContent', 'paginationLinksSukarela', 'sukarela');

        // Jika ada parameter tab di URL saat halaman load, set activeTab Alpine.js
        const currentUrlParams = new URLSearchParams(window.location.search);
        const currentTab = currentUrlParams.get('tab');
        if (currentTab) {
            const alpineComponent = document.querySelector('[x-data]');
            if (alpineComponent && alpineComponent.__x) { // Cek jika Alpine sudah inisialisasi
                alpineComponent.__x.$data.activeTab = currentTab;
            } else { // Jika Alpine belum siap, tunggu sebentar
                setTimeout(() => {
                    const alpineCompRetry = document.querySelector('[x-data]');
                    if(alpineCompRetry && alpineCompRetry.__x) alpineCompRetry.__x.$data.activeTab = currentTab;
                }, 100);
            }
        }
    });
</script>
@endpush