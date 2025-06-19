{{-- resources/views/anggota/simpanan/partials/_riwayat_sukarela_table.blade.php --}}
@if($riwayat_sukarela->isNotEmpty())
    <div class="space-y-4">
        @foreach($riwayat_sukarela as $index => $sukarela)
            <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-lg transition-all duration-300 group transform hover:-translate-y-1" 
                 style="animation: slideInUp 0.5s ease-out {{ $index * 0.1 }}s both;">
                
                <div class="grid grid-cols-1 lg:grid-cols-6 gap-6 items-center">
                    <!-- Tanggal Transaksi Column -->
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-amber-200 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200 shadow-sm">
                            <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-orange-600 uppercase tracking-wider mb-1">
                                TANGGAL TRANSAKSI
                            </div>
                            <div class="font-bold text-gray-800 group-hover:text-orange-700 transition-colors duration-200">
                                {{ \Carbon\Carbon::parse($sukarela->tanggal_transaksi)->isoFormat('DD MMMM YYYY') }}
                            </div>
                            <div class="text-sm text-gray-500 flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($sukarela->tanggal_transaksi)->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <!-- Tipe Transaksi Column -->
                    <div class="text-center">
                        <div class="text-xs font-semibold text-orange-600 uppercase tracking-wider mb-2">
                            TIPE TRANSAKSI
                        </div>
                        @if($sukarela->tipe_transaksi == 'setor')
                            <div class="inline-flex items-center space-x-2">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                                    </svg>
                                </div>
                                <span class="font-semibold text-green-700">Setor</span>
                            </div>
                        @else
                            <div class="inline-flex items-center space-x-2">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"/>
                                    </svg>
                                </div>
                                <span class="font-semibold text-red-700">Tarik</span>
                            </div>
                        @endif
                    </div>

                    <!-- Jumlah Column -->
                    <div class="text-center">
                        <div class="text-xs font-semibold text-orange-600 uppercase tracking-wider mb-2">
                            JUMLAH
                        </div>
                        <div class="font-bold text-lg {{ $sukarela->tipe_transaksi == 'setor' ? 'text-green-600' : 'text-red-600' }}">
                            @if($sukarela->tipe_transaksi == 'setor')
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                                    </svg>
                                    @rupiah($sukarela->jumlah)
                                </span>
                            @else
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"/>
                                    </svg>
                                    @rupiah($sukarela->jumlah)
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Saldo Sebelum Column -->
                    <div class="text-center">
                        <div class="text-xs font-semibold text-orange-600 uppercase tracking-wider mb-2">
                            SALDO SEBELUM
                        </div>
                        <div class="inline-flex items-center px-3 py-2 bg-gray-100 rounded-xl">
                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                            </svg>
                            <span class="font-semibold text-gray-700">@rupiah($sukarela->saldo_sebelum)</span>
                        </div>
                    </div>

                    <!-- Saldo Sesudah Column -->
                    <div class="text-center">
                        <div class="text-xs font-semibold text-orange-600 uppercase tracking-wider mb-2">
                            SALDO SESUDAH
                        </div>
                        <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-100 to-amber-200 rounded-xl shadow-sm group-hover:shadow-md transition-all duration-200">
                            <svg class="w-4 h-4 text-orange-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            <span class="font-bold text-orange-800 text-lg">@rupiah($sukarela->saldo_sesudah)</span>
                        </div>
                    </div>

                    <!-- Keterangan Column -->
                    <div>
                        <div class="text-xs font-semibold text-orange-600 uppercase tracking-wider mb-2">
                            KETERANGAN
                        </div>
                        <div class="flex items-start">
                            @if($sukarela->keterangan)
                                <div class="flex items-start space-x-2">
                                    <div class="w-2 h-2 bg-orange-400 rounded-full mt-2 flex-shrink-0"></div>
                                    <span class="text-gray-700 group-hover:text-gray-800 transition-colors duration-200 text-sm leading-relaxed">{{ $sukarela->keterangan }}</span>
                                </div>
                            @else
                                <span class="text-gray-400 italic text-sm">Tidak ada keterangan</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Mobile Layout -->
                <div class="lg:hidden mt-4 pt-4 border-t border-gray-100">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Saldo Sebelum:</span>
                            <div class="font-semibold">@rupiah($sukarela->saldo_sebelum)</div>
                        </div>
                        <div>
                            <span class="text-gray-500">Saldo Sesudah:</span>
                            <div class="font-bold text-orange-600">@rupiah($sukarela->saldo_sesudah)</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="empty-icon bg-gradient-to-br from-orange-100 to-amber-200">
            <svg class="w-16 h-16 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
            </svg>
        </div>
        <h3 class="empty-title">Belum Ada Riwayat Simpanan Sukarela</h3>
        <p class="empty-description">
            Riwayat transaksi simpanan sukarela (setor/tarik) Anda akan muncul di sini setelah melakukan transaksi pertama.
        </p>
        <div class="mt-6 space-y-3">
            <div class="flex flex-wrap justify-center gap-3">
                <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                    </svg>
                    Setor kapan saja
                </div>
                <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"/>
                    </svg>
                    Tarik sesuai kebutuhan
                </div>
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
    @apply w-32 h-32 bg-gradient-to-br from-orange-100 to-amber-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg;
}

.empty-title {
    @apply text-xl font-bold text-gray-700 mb-2;
}

.empty-description {
    @apply text-gray-500 text-base max-w-md mx-auto;
}
</style>