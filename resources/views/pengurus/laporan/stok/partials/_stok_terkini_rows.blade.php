@forelse($daftar_stok as $index => $barang)
    <tr class="table-row border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
        <td class="py-3 px-4 text-gray-700 font-medium">{{ $daftar_stok->firstItem() + $index }}</td>
        <td class="py-3 px-4 text-gray-600">
            <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">
                {{ $barang->kode_barang ?? '-' }}
            </span>
        </td>
        <td class="py-3 px-4 font-medium text-gray-800">
            <div class="flex items-center">
                <i class="fas fa-box text-purple-500 mr-2"></i>
                {{ $barang->nama_barang }}
            </div>
        </td>
        <td class="py-3 px-4 text-gray-600">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <i class="fas fa-store mr-1"></i>
                {{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}
            </span>
        </td>
        <td class="py-3 px-4 text-center">
            <div class="flex flex-col items-center">
                <span class="text-lg font-bold {{ $barang->stok <= 10 && $barang->stok > 0 ? 'text-yellow-600' : ($barang->stok == 0 ? 'text-red-600' : 'text-green-600') }}">
                    {{ number_format($barang->stok, 0, ',', '.') }}
                </span>
                <span class="text-xs text-gray-500">{{ $barang->satuan }}</span>
                @if($barang->stok == 0)
                    <span class="stok-status stok-habis mt-1">
                        <i class="fas fa-times-circle"></i>
                        Habis
                    </span>
                @elseif($barang->stok <= 10)
                    <span class="stok-status stok-rendah mt-1">
                        <i class="fas fa-exclamation-triangle"></i>
                        Rendah
                    </span>
                @else
                    <span class="stok-status stok-aman mt-1">
                        <i class="fas fa-check-circle"></i>
                        Aman
                    </span>
                @endif
            </div>
        </td>
        <td class="py-3 px-4 text-right text-gray-700">
            <span class="font-semibold">@rupiah($barang->harga_beli)</span>
        </td>
        <td class="py-3 px-4 text-right text-gray-700">
            <span class="font-semibold">@rupiah($barang->harga_jual)</span>
        </td>
        <td class="py-3 px-4 text-right">
            <span class="font-bold text-blue-600 text-lg">
                @rupiah($barang->stok * $barang->harga_beli)
            </span>
        </td>
        <td class="py-3 px-4 text-center">
            <div class="flex items-center justify-center space-x-2">
                <a href="{{ route('pengurus.laporan.stok.kartuStok', $barang->id) }}" 
                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-all duration-200" 
                   title="Lihat Kartu Stok">
                    <i class="fas fa-clipboard-list mr-1"></i>
                    <span class="hidden sm:inline">Kartu Stok</span>
                </a>
                {{-- <a href="{{ route('pengurus.barang.show', $barang->id) }}" 
                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 hover:text-emerald-700 transition-all duration-200" 
                   title="Lihat Detail Barang">
                    <i class="fas fa-eye mr-1"></i>
                    <span class="hidden sm:inline">Detail</span>
                </a> --}}
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="text-center py-12 text-gray-500">
            <div class="flex flex-col items-center">
                <i class="fas fa-boxes text-6xl mb-4 text-gray-300"></i>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">Tidak Ada Data Stok</h3>
                <p class="text-sm text-gray-500">Tidak ada data stok barang ditemukan untuk filter ini.</p>
            </div>
        </td>
    </tr>
@endforelse
