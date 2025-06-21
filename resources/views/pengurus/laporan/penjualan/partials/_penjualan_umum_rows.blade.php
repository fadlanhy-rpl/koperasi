{{-- Enhanced table rows with better styling and error handling --}}
@forelse($detailPembelians as $index => $detail)
    <tr class="table-row border-b border-gray-100 transition-all duration-200" data-index="{{ $index }}">
        <td class="py-4 px-4">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-receipt text-white text-sm"></i>
                    </div>
                </div>
                <div>
                    @if($detail->pembelian)
                        <a href="{{ route('pengurus.transaksi-pembelian.show', $detail->pembelian->id) }}" 
                           class="text-blue-600 hover:text-blue-800 font-semibold hover:underline transition-colors duration-200 flex items-center">
                            <i class="fas fa-external-link-alt mr-2 text-xs"></i>
                            {{ $detail->pembelian->kode_pembelian }}
                        </a>
                        <div class="text-xs text-gray-500 mt-1 flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            {{ \Carbon\Carbon::parse($detail->pembelian->tanggal_pembelian)->isoFormat('DD MMM YYYY, HH:mm') }}
                        </div>
                    @else
                        <span class="text-red-500 font-medium">Transaksi Dihapus</span>
                        <div class="text-xs text-gray-500 mt-1">Data tidak tersedia</div>
                    @endif
                </div>
            </div>
        </td>
        
        <td class="py-4 px-4">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                </div>
                <div>
                    @if($detail->pembelian && $detail->pembelian->user)
                        <p class="text-gray-800 font-medium">{{ $detail->pembelian->user->name }}</p>
                        <div class="text-xs text-gray-500 flex items-center mt-1">
                            <i class="fas fa-id-badge mr-1"></i>
                            {{ $detail->pembelian->user->nomor_anggota ?? '-' }}
                        </div>
                    @else
                        <p class="text-gray-500 font-medium">Anggota Tidak Diketahui</p>
                        <div class="text-xs text-gray-400 mt-1">Data tidak tersedia</div>
                    @endif
                </div>
            </div>
        </td>
        
        <td class="py-4 px-4">
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-box text-white text-xs"></i>
                    </div>
                    @if($detail->barang)
                        <p class="text-gray-800 font-medium">{{ $detail->barang->nama_barang }}</p>
                    @else
                        <p class="text-red-500 font-medium">Barang Dihapus</p>
                    @endif
                </div>
                <div class="ml-10">
                    <p class="text-xs text-gray-500 flex items-center">
                        <i class="fas fa-barcode mr-1"></i>
                        {{ $detail->barang->kode_barang ?? '-' }}
                    </p>
                    @if($detail->barang && $detail->barang->unitUsaha)
                        <p class="text-xs text-blue-600 font-medium flex items-center mt-1">
                            <i class="fas fa-store mr-1"></i>
                            {{ $detail->barang->unitUsaha->nama_unit_usaha }}
                        </p>
                    @else
                        <p class="text-xs text-gray-400 font-medium flex items-center mt-1">
                            <i class="fas fa-store mr-1"></i>
                            Unit Usaha Tidak Diketahui
                        </p>
                    @endif
                </div>
            </div>
        </td>
        
        <td class="py-4 px-4 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-orange-400 to-red-500 text-white rounded-xl font-bold text-lg">
                {{ number_format($detail->jumlah, 0, ',', '.') }}
            </div>
            @if($detail->barang && $detail->barang->satuan)
                <div class="text-xs text-gray-500 mt-1">{{ $detail->barang->satuan }}</div>
            @endif
        </td>
        
        <td class="py-4 px-4 text-right">
            <div class="text-gray-800 font-semibold text-lg">@rupiah($detail->harga_satuan)</div>
            <div class="text-xs text-gray-500">per unit</div>
        </td>
        
        <td class="py-4 px-4 text-right">
            <div class="text-gray-900 font-bold text-xl">@rupiah($detail->subtotal)</div>
            <div class="text-xs text-green-600 font-medium">
                <i class="fas fa-calculator mr-1"></i>
                {{ number_format($detail->jumlah, 0, ',', '.') }} × @rupiah($detail->harga_satuan)
            </div>
        </td>
        
        <td class="py-4 px-4 text-center">
            @if($detail->pembelian)
                @php
                    $statusConfig = [
                        'lunas' => ['status-lunas', 'fas fa-check-circle', 'Lunas'],
                        'cicilan' => ['status-cicilan', 'fas fa-clock', 'Cicilan'],
                        'belum_lunas' => ['status-belum-lunas', 'fas fa-exclamation-circle', 'Belum Lunas']
                    ];
                    $status = $detail->pembelian->status_pembayaran;
                    $config = $statusConfig[$status] ?? $statusConfig['belum_lunas'];
                @endphp
                
                <span class="status-badge {{ $config[0] }}">
                    <i class="{{ $config[1] }} mr-1"></i>
                    {{ $config[2] }}
                </span>
            @else
                <span class="status-badge status-belum-lunas">
                    <i class="fas fa-question-circle mr-1"></i>
                    Tidak Diketahui
                </span>
            @endif
        </td>
        
        <td class="py-4 px-4">
            @if($detail->pembelian)
                @php
                    $metodeBayar = $detail->pembelian->metode_pembayaran;
                    $metodeConfig = [
                        'tunai' => ['fas fa-money-bill-wave', 'text-green-600', 'Tunai'],
                        'transfer' => ['fas fa-university', 'text-blue-600', 'Transfer'],
                        'kartu_kredit' => ['fas fa-credit-card', 'text-purple-600', 'Kartu Kredit'],
                        'e_wallet' => ['fas fa-mobile-alt', 'text-orange-600', 'E-Wallet']
                    ];
                    $metodeInfo = $metodeConfig[$metodeBayar] ?? ['fas fa-question-circle', 'text-gray-600', ucfirst(str_replace('_', ' ', $metodeBayar))];
                @endphp
                
                <div class="flex items-center space-x-2">
                    <i class="{{ $metodeInfo[0] }} {{ $metodeInfo[1] }}"></i>
                    <span class="text-gray-700 font-medium">{{ $metodeInfo[2] }}</span>
                </div>
            @else
                <div class="flex items-center space-x-2">
                    <i class="fas fa-question-circle text-gray-400"></i>
                    <span class="text-gray-400 font-medium">Tidak Diketahui</span>
                </div>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-16">
            <div class="flex flex-col items-center space-y-4">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-invoice-dollar text-4xl text-gray-400"></i>
                </div>
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak Ada Data</h3>
                    <p class="text-gray-500 max-w-md">
                        Tidak ada data penjualan ditemukan untuk filter yang diterapkan. 
                        Coba ubah kriteria pencarian atau periode tanggal.
                    </p>
                </div>
                <button onclick="document.getElementById('filterLaporanForm').reset(); document.getElementById('filterLaporanForm').submit();" 
                        class="mt-4 px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-purple-700 transition-all duration-300">
                    <i class="fas fa-undo mr-2"></i>
                    Reset Filter
                </button>
            </div>
        </td>
    </tr>
@endforelse
