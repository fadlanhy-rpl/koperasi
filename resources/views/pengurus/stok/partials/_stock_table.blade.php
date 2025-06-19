<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-gray-200">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Informasi Barang</span>
                    </div>
                </th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-gray-200">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1z"/>
                        </svg>
                        <span>Unit Usaha</span>
                    </div>
                </th>
                <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-gray-200">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3z"/>
                        </svg>
                        <span>Stok Terkini</span>
                    </div>
                </th>
                <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-gray-200">
                    <span>Nilai Stok</span>
                </th>
                <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-gray-200">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"/>
                        </svg>
                        <span>Aksi Stok</span>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($barangs as $barang)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <!-- Product Info -->
                    <td class="px-6 py-6">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $barang->nama_barang }}</h3>
                                <div class="space-y-1">
                                    @if($barang->kode_barang)
                                        <p class="text-sm text-gray-500">
                                            <span class="font-medium">Kode:</span> {{ $barang->kode_barang }}
                                        </p>
                                    @endif
                                    @if($barang->deskripsi)
                                        <p class="text-sm text-gray-600 truncate max-w-xs">{{ $barang->deskripsi }}</p>
                                    @endif
                                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                                        <span>Harga: <span class="font-semibold">@rupiah($barang->harga_beli ?? 0)</span></span>
                                        <span>{{ $barang->satuan }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    
                    <!-- Unit Usaha -->
                    <td class="px-6 py-6">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <span class="font-semibold text-gray-700">
                                {{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}
                            </span>
                        </div>
                    </td>
                    
                    <!-- Stock Status -->
                    <td class="px-6 py-6 text-center">
                        <div class="flex flex-col items-center space-y-2">
                            <div class="text-3xl font-bold 
                                {{ $barang->stok == 0 ? 'text-red-600' : ($barang->stok <= 10 ? 'text-yellow-600' : 'text-green-600') }}">
                                {{ $barang->stok }}
                            </div>
                            <div class="stock-badge 
                                {{ $barang->stok == 0 ? 'stock-out' : ($barang->stok <= 10 ? 'stock-low' : 'stock-normal') }}">
                                @if($barang->stok == 0)
                                    Habis
                                @elseif($barang->stok <= 10)
                                    Rendah
                                @else
                                    Normal
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">{{ $barang->satuan }}</span>
                        </div>
                    </td>
                    
                    <!-- Stock Value -->
                    <td class="px-6 py-6 text-right">
                        <div class="space-y-1">
                            <div class="text-xl font-bold text-gray-900">
                                @rupiah(($barang->harga_beli ?? 0) * $barang->stok)
                            </div>
                            <div class="text-sm text-gray-500">
                                @rupiah($barang->harga_beli ?? 0) / {{ $barang->satuan }}
                            </div>
                        </div>
                    </td>
                    
                    <!-- Actions -->
                    <td class="px-6 py-6">
                        <div class="flex items-center justify-center space-x-2">
                            <!-- Quick Action Button -->
                            {{-- <button onclick="openQuickStockModal({{ json_encode([
                                'id' => $barang->id,
                                'nama_barang' => $barang->nama_barang,
                                'stok' => $barang->stok,
                                'satuan' => $barang->satuan
                            ]) }})"
                                    class="action-btn p-3 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-xl"
                                    title="Aksi Cepat">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"/>
                                </svg>
                            </button> --}}
                            
                            <!-- Add Stock -->
                            <a href="{{ route('pengurus.stok.formBarangMasuk', $barang->id) }}" 
                               class="action-btn p-3 bg-green-100 hover:bg-green-200 text-green-700 rounded-xl"
                               title="Tambah Stok">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                                </svg>
                            </a>
                            
                            <!-- Subtract Stock -->
                            <a href="{{ route('pengurus.stok.formBarangKeluar', $barang->id) }}" 
                               class="action-btn p-3 bg-red-100 hover:bg-red-200 text-red-700 rounded-xl"
                               title="Kurangi Stok">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                </svg>
                            </a>
                            
                            <!-- Adjust Stock -->
                            <a href="{{ route('pengurus.stok.formPenyesuaianStok', $barang->id) }}" 
                               class="action-btn p-3 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-xl"
                               title="Penyesuaian Stok">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"/>
                                </svg>
                            </a>
                            
                            <!-- History -->
                            <a href="{{ route('pengurus.barang.show', $barang->id) }}" 
                               class="action-btn p-3 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-xl"
                               title="Lihat Histori">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center space-y-4">
                            <svg class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z"/>
                            </svg>
                            <div class="text-center">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data barang</h3>
                                <p class="text-gray-500">Belum ada barang yang terdaftar dalam sistem atau tidak ada yang sesuai dengan filter.</p>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($barangs->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $barangs->links('vendor.pagination.tailwind') }}
    </div>
@endif
