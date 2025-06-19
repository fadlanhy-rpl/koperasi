{{-- resources/views/anggota/simpanan/partials/_riwayat_pokok_table.blade.php --}}
@if($riwayat_pokok->isNotEmpty())
    <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px]">
                <thead class="bg-gradient-to-r from-blue-50 via-blue-25 to-indigo-50">
                    <tr>
                        <th class="py-5 px-6 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">
                            <div class="flex items-center space-x-2 group">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                </svg>
                                <span>Tanggal Bayar</span>
                            </div>
                        </th>
                        <th class="py-5 px-6 text-right text-xs font-bold text-blue-800 uppercase tracking-wider">
                            <div class="flex items-center justify-end space-x-2 group">
                                <span>Jumlah</span>
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582z"/>
                                </svg>
                            </div>
                        </th>
                        <th class="py-5 px-6 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Keterangan</th>
                        <th class="py-5 px-6 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($riwayat_pokok as $index => $pokok)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-transparent transition-all duration-300 group transform hover:scale-[1.01]" 
                        style="animation: slideInUp 0.5s ease-out {{ $index * 0.1 }}s both;">
                        <td class="py-5 px-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full mr-4 group-hover:scale-125 transition-transform duration-200 shadow-sm"></div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 group-hover:text-blue-700 transition-colors duration-200">
                                        {{ \Carbon\Carbon::parse($pokok->tanggal_bayar)->isoFormat('DD MMMM YYYY') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($pokok->tanggal_bayar)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-6 whitespace-nowrap text-right">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 shadow-sm group-hover:shadow-md transition-shadow duration-200">
                                @rupiah($pokok->jumlah)
                            </span>
                        </td>
                        <td class="py-5 px-6">
                            <span class="text-sm text-gray-600 group-hover:text-gray-800 transition-colors duration-200">{{ $pokok->keterangan ?: '-' }}</span>
                        </td>
                        <td class="py-5 px-6">
                            <div class="flex items-center">
                                <div class="w-9 h-9 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-500 group-hover:text-gray-700 transition-colors duration-200">{{ $pokok->pengurus->name ?? 'Sistem' }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="text-center py-20">
        <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
            <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Belum ada data simpanan pokok</h3>
        <p class="text-gray-500 text-base max-w-md mx-auto">Data transaksi akan muncul di sini setelah Anda melakukan simpanan pokok pertama</p>
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
</style>
