{{-- resources/views/anggota/simpanan/partials/_riwayat_wajib_table.blade.php --}}
@if($riwayat_wajib->isNotEmpty())
    <div class="space-y-4">
        @foreach($riwayat_wajib as $index => $wajib)
            <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 group transform hover:-translate-y-1" 
                 style="animation: slideInUp 0.5s ease-out {{ $index * 0.1 }}s both;">
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                    <!-- Periode Column -->
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-emerald-200 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200 shadow-sm">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-1">
                                PERIODE (BULAN/TAHUN)
                            </div>
                            <div class="font-bold text-gray-800 text-lg group-hover:text-green-700 transition-colors duration-200">
                                {{ \Carbon\Carbon::create()->month($wajib->bulan)->translatedFormat('F') }} {{ $wajib->tahun }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">
                                Periode {{ $wajib->bulan }}/{{ $wajib->tahun }}
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Bayar Column -->
                    <div>
                        <div class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-2">
                            TANGGAL BAYAR
                        </div>
                        <div class="space-y-1">
                            <div class="font-semibold text-gray-800 group-hover:text-green-700 transition-colors duration-200">
                                {{ \Carbon\Carbon::parse($wajib->tanggal_bayar)->isoFormat('DD MMMM YYYY') }}
                            </div>
                            <div class="text-sm text-gray-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($wajib->tanggal_bayar)->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <!-- Jumlah Column -->
                    <div class="text-center">
                        <div class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-2">
                            JUMLAH
                        </div>
                        <div class="inline-flex items-center">
                            <span class="inline-flex items-center px-4 py-3 rounded-2xl text-lg font-bold bg-gradient-to-r from-green-100 to-emerald-200 text-green-800 shadow-sm group-hover:shadow-md transition-all duration-200 group-hover:scale-105">
                                @rupiah($wajib->jumlah)
                            </span>
                        </div>
                    </div>

                    <!-- Keterangan Column -->
                    <div>
                        <div class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-2">
                            KETERANGAN
                        </div>
                        <div class="flex items-center">
                            @if($wajib->keterangan)
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                    <span class="text-gray-700 group-hover:text-gray-800 transition-colors duration-200">{{ $wajib->keterangan }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 italic">Tidak ada keterangan</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon bg-gradient-to-br from-green-100 to-emerald-200">
            <svg class="w-16 h-16 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
            </svg>
        </div>
        <h3 class="empty-title">Belum Ada Riwayat Simpanan Wajib</h3>
        <p class="empty-description">
            Riwayat pembayaran simpanan wajib bulanan Anda akan muncul di sini setelah melakukan pembayaran pertama.
        </p>
        <div class="mt-6">
            <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                </svg>
                Simpanan wajib dibayar setiap bulan
            </div>
        </div>
    </div>
@endif

<style>
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.empty-state {
    @apply text-center py-20;
}

.empty-icon {
    @apply w-32 h-32 bg-gradient-to-br from-green-100 to-emerald-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg;
}

.empty-title {
    @apply text-xl font-bold text-gray-700 mb-2;
}

.empty-description {
    @apply text-gray-500 text-base max-w-md mx-auto;
}
</style>