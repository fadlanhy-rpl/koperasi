@forelse($barangs as $item)
    <tr class="table-row border-b border-gray-100 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 group">
        <!-- Product Image -->
        <td class="py-4 px-6">
            <div class="flex justify-center">
                <div class="relative group/image">
                    <img src="{{ $item->gambar_path ? asset('storage/' . $item->gambar_path) : 'https://ui-avatars.com/api/?name='.urlencode($item->nama_barang).'&background=random&color=fff&size=80&font-size=0.33&bold=true&rounded=false' }}" 
                         alt="Gambar {{ $item->nama_barang }}" 
                         class="product-image cursor-pointer"
                         loading="lazy"
                         onclick="openImageModal('{{ $item->gambar_path ? asset('storage/' . $item->gambar_path) : 'https://ui-avatars.com/api/?name='.urlencode($item->nama_barang).'&background=random&color=fff&size=400&font-size=0.33&bold=true&rounded=false' }}', '{{ addslashes($item->nama_barang) }}')"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($item->nama_barang) }}&background=random&color=fff&size=80&font-size=0.33&bold=true&rounded=false'">
                    
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover/image:bg-opacity-30 transition-all duration-300 rounded-xl flex items-center justify-center opacity-0 group-hover/image:opacity-100">
                        <i class="fas fa-search-plus text-white text-lg"></i>
                    </div>
                    
                    <!-- Image Status Badge -->
                    @if(!$item->gambar_path)
                        <div class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                            <i class="fas fa-image"></i>
                        </div>
                    @endif
                </div>
            </div>
        </td>
        
        <!-- Product Info -->
        <td class="py-4 px-6">
            <div class="flex flex-col space-y-2">
                <a href="{{ route('pengurus.barang.show', $item->id) }}" 
                   class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors duration-300 hover:underline text-lg">
                    {{ $item->nama_barang }}
                </a>
                <div class="flex items-center space-x-2 flex-wrap">
                    <span class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-3 py-1 rounded-lg text-xs font-medium border border-gray-300">
                        <i class="fas fa-barcode mr-1 text-gray-500"></i>
                        {{ $item->kode_barang ?? 'Tanpa Kode' }}
                    </span>
                    @if($item->deskripsi)
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-lg text-xs font-medium" title="{{ $item->deskripsi }}">
                            <i class="fas fa-info-circle mr-1"></i>
                            Ada Deskripsi
                        </span>
                    @endif
                    @if(!$item->gambar_path)
                        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded-lg text-xs font-medium" title="Menggunakan gambar otomatis">
                            <i class="fas fa-magic mr-1"></i>
                            Auto Avatar
                        </span>
                    @else
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-lg text-xs font-medium" title="Memiliki gambar custom">
                            <i class="fas fa-image mr-1"></i>
                            Custom Image
                        </span>
                    @endif
                    <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded-lg text-xs font-medium">
                        <i class="fas fa-calendar-plus mr-1"></i>
                        {{ $item->created_at->format('M Y') }}
                    </span>
                </div>
            </div>
        </td>
        
        <!-- Unit Usaha -->
        <td class="py-4 px-6">
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-r from-purple-100 to-purple-200 p-2.5 rounded-xl border border-purple-300">
                    <i class="fas fa-building text-purple-600"></i>
                </div>
                <div>
                    <span class="text-gray-800 font-medium text-sm">
                        {{ $item->unitUsaha->nama_unit_usaha ?? 'N/A' }}
                    </span>
                    <p class="text-xs text-gray-500">Unit Usaha</p>
                </div>
            </div>
        </td>
        
        <!-- Harga Beli -->
        <td class="py-4 px-6 text-right">
            <div class="flex flex-col items-end space-y-1">
                <span class="text-lg font-bold text-gray-800">
                    @rupiah($item->harga_beli)
                </span>
                <div class="flex items-center space-x-1">
                    <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                    <span class="text-xs text-gray-500 font-medium">Harga Modal</span>
                </div>
            </div>
        </td>
        
        <!-- Harga Jual -->
        <td class="py-4 px-6 text-right">
            <div class="flex flex-col items-end space-y-1">
                <span class="text-lg font-bold text-green-600">
                    @rupiah($item->harga_jual)
                </span>
                <div class="flex items-center space-x-1">
                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                    <span class="text-xs text-gray-500 font-medium">Harga Jual</span>
                </div>
                @if($item->harga_jual > $item->harga_beli)
                    @php
                        $profit = $item->harga_jual - $item->harga_beli;
                        $profitPercent = ($profit / $item->harga_beli) * 100;
                    @endphp
                    <div class="bg-green-50 px-2 py-1 rounded-lg border border-green-200">
                        <span class="text-xs text-green-700 font-medium">
                            <i class="fas fa-arrow-up mr-1"></i>
                            +{{ number_format($profitPercent, 1) }}%
                        </span>
                    </div>
                @elseif($item->harga_jual < $item->harga_beli)
                    <div class="bg-red-50 px-2 py-1 rounded-lg border border-red-200">
                        <span class="text-xs text-red-700 font-medium">
                            <i class="fas fa-arrow-down mr-1"></i>
                            Rugi
                        </span>
                    </div>
                @endif
            </div>
        </td>
        
        <!-- Stock Status -->
        <td class="py-4 px-6 text-center">
            <div class="flex flex-col items-center space-y-2">
                <div class="relative">
                    <span class="text-3xl font-bold 
                        @if($item->stok == 0) text-gray-400 
                        @elseif($item->stok <= 10) text-red-600 
                        @else text-green-600 @endif">
                        {{ number_format($item->stok) }}
                    </span>
                    @if($item->stok <= 10 && $item->stok > 0)
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                    @endif
                </div>
                
                <div class="flex flex-col items-center space-y-1">
                    <span class="stock-indicator 
                        @if($item->stok == 0) stock-empty
                        @elseif($item->stok <= 10) stock-low
                        @else stock-good @endif">
                        @if($item->stok == 0) 
                            <i class="fas fa-times-circle mr-1"></i>Habis
                        @elseif($item->stok <= 10) 
                            <i class="fas fa-exclamation-triangle mr-1"></i>Sedikit
                        @else 
                            <i class="fas fa-check-circle mr-1"></i>Tersedia
                        @endif
                    </span>
                    <span class="text-xs text-gray-500 font-medium bg-gray-100 px-2 py-1 rounded-lg">
                        {{ ucfirst($item->satuan) }}
                    </span>
                </div>
                
                <!-- Quick Stock Info -->
                @if($item->stok > 0)
                    @php
                        $stockValue = $item->stok * $item->harga_beli;
                    @endphp
                    <div class="bg-blue-50 px-2 py-1 rounded-lg border border-blue-200 mt-1">
                        <span class="text-xs text-blue-700 font-medium" title="Nilai Stok (Harga Beli x Stok)">
                            <i class="fas fa-calculator mr-1"></i>
                            @rupiah($stockValue)
                        </span>
                    </div>
                @endif
            </div>
        </td>
        
        <!-- Description -->
        <td class="py-4 px-6">
            <div class="max-w-xs">
                @if($item->deskripsi)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 hover:bg-yellow-100 transition-colors">
                        <p class="text-sm text-gray-700 line-clamp-3" title="{{ $item->deskripsi }}">
                            {{ Str::limit($item->deskripsi, 80) }}
                        </p>
                        @if(strlen($item->deskripsi) > 80)
                            <button onclick="showFullDescription('{{ $item->id }}', `{{ addslashes($item->deskripsi) }}`, `{{ addslashes($item->nama_barang) }}`)" 
                                    class="text-xs text-blue-600 hover:text-blue-800 font-medium mt-2 hover:underline flex items-center">
                                <i class="fas fa-expand-alt mr-1"></i>Lihat Selengkapnya
                            </button>
                        @endif
                    </div>
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-center hover:bg-gray-100 transition-colors">
                        <i class="fas fa-file-alt text-gray-300 text-lg mb-1"></i>
                        <p class="text-xs text-gray-400 italic">Tidak ada deskripsi</p>
                        <a href="{{ route('pengurus.barang.edit', $item->id) }}" 
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium mt-1 hover:underline">
                            <i class="fas fa-plus mr-1"></i>Tambah Deskripsi
                        </a>
                    </div>
                @endif
            </div>
        </td>
        
        <!-- Actions -->
        <td class="py-4 px-6 text-center">
            <div class="flex items-center justify-center space-x-2">
                <!-- View Button -->
                <a href="{{ route('pengurus.barang.show', $item->id) }}" 
                   class="action-btn view group/btn" 
                   title="Lihat Detail Barang">
                    <i class="fas fa-eye group-hover/btn:scale-110 transition-transform"></i>
                </a>
                
                <!-- Edit Button -->
                <a href="{{ route('pengurus.barang.edit', $item->id) }}" 
                   class="action-btn edit group/btn" 
                   title="Edit Data Barang">
                    <i class="fas fa-edit group-hover/btn:scale-110 transition-transform"></i>
                </a>
                
                <!-- Delete Button -->
                <button onclick="confirmDelete('{{ route('pengurus.barang.destroy', $item->id) }}', `{{ addslashes($item->nama_barang) }}`)" 
                        class="action-btn delete group/btn" 
                        title="Hapus Barang">
                    <i class="fas fa-trash group-hover/btn:scale-110 transition-transform"></i>
                </button>
                
                <!-- Quick Stock Actions Dropdown -->
                <div class="relative group/dropdown ">
                    <button class="action-btn bg-gradient-to-r from-gray-500 to-gray-600 text-white hover:from-gray-600 hover:to-gray-700" 
                            title="Aksi Stok Cepat">
                        <i class="fas fa-boxes "></i>
                        <i class="fas fa-chevron-down ml-1 text-xs "></i>
                    </button>
                    
                    <div class="absolute right-0 top-full mt-2 w-52 bg-white rounded-xl shadow-2xl border border-gray-200 opacity-0 invisible group-hover/dropdown:opacity-100 group-hover/dropdown:visible transition-all duration-300 z-20">
                        <div class="py-2">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Kelola Stok</p>
                            </div>
                            
                            <a href="{{ route('pengurus.stok.formBarangMasuk', ['barang' => $item->id]) }}" 
                               class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors group/item">
                                <div class="bg-green-100 p-1.5 rounded-lg mr-3 group-hover/item:bg-green-200 transition-colors">
                                    <i class="fas fa-plus-circle text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Stok Masuk</p>
                                    <p class="text-xs text-gray-500">Tambah stok barang</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('pengurus.stok.formBarangKeluar', ['barang' => $item->id]) }}" 
                               class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors group/item">
                                <div class="bg-red-100 p-1.5 rounded-lg mr-3 group-hover/item:bg-red-200 transition-colors">
                                    <i class="fas fa-minus-circle text-red-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Stok Keluar</p>
                                    <p class="text-xs text-gray-500">Kurangi stok barang</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('pengurus.stok.formPenyesuaianStok', ['barang' => $item->id]) }}" 
                               class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 transition-colors group/item">
                                <div class="bg-yellow-100 p-1.5 rounded-lg mr-3 group-hover/item:bg-yellow-200 transition-colors">
                                    <i class="fas fa-exchange-alt text-yellow-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Penyesuaian</p>
                                    <p class="text-xs text-gray-500">Sesuaikan stok manual</p>
                                </div>
                            </a>
                            
                            <div class="border-t border-gray-100 my-1"></div>
                            
                            <a href="{{ route('pengurus.stok.index', ['search_stok' => $item->kode_barang]) }}" 
                               class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors group/item">
                                <div class="bg-blue-100 p-1.5 rounded-lg mr-3 group-hover/item:bg-blue-200 transition-colors">
                                    <i class="fas fa-history text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium">Riwayat Stok</p>
                                    <p class="text-xs text-gray-500">Lihat histori perubahan</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-16">
            <div class="flex flex-col items-center space-y-6">
                <div class="bg-gradient-to-r from-gray-100 to-gray-200 w-24 h-24 rounded-full flex items-center justify-center">
                    <i class="fas fa-box-open text-5xl text-gray-400"></i>
                </div>
                <div class="text-center">
                    <p class="text-xl font-semibold text-gray-600 mb-2">Tidak ada barang ditemukan</p>
                    <p class="text-sm text-gray-500 mb-6">Belum ada barang yang terdaftar atau sesuai dengan pencarian Anda</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('pengurus.barang.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Tambah Barang Pertama
                        </a>
                        @if(request('search') || request('unit_usaha_filter'))
                            <a href="{{ route('pengurus.barang.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-all duration-300">
                                <i class="fas fa-times mr-2"></i>
                                Reset Filter
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </td>
    </tr>
@endforelse

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-80 hidden items-center justify-center z-50 p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute -top-4 -right-4 bg-white rounded-full w-10 h-10 flex items-center justify-center text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors z-10">
            <i class="fas fa-times text-lg"></i>
        </button>
        <img id="modalImage" src="#" alt="Gambar Barang" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
        <div id="modalImageCaption" class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-70 text-white p-4 rounded-b-lg">
            <p class="text-center font-medium"></p>
        </div>
    </div>
</div>

<!-- Description Modal -->
<div id="descriptionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-96 overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <div class="flex justify-between items-center">
                <h3 id="descriptionModalTitle" class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-align-left mr-3 text-yellow-500"></i>
                    Deskripsi Barang
                </h3>
                <button onclick="closeDescriptionModal()" class="text-gray-400 hover:text-gray-600 transition-colors text-2xl">
                    ×
                </button>
            </div>
        </div>
        <div class="p-6 overflow-y-auto max-h-64">
            <p id="descriptionModalContent" class="text-gray-700 leading-relaxed whitespace-pre-wrap"></p>
        </div>
        <div class="p-4 border-t border-gray-200 bg-gray-50 flex justify-end">
            <button onclick="closeDescriptionModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
        </div>
    </div>
</div>

<script>
// Image Modal Functions
function openImageModal(imageSrc, imageAlt) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalCaption = document.getElementById('modalImageCaption');
    
    if (modal && modalImage && modalCaption) {
        modalImage.src = imageSrc;
        modalImage.alt = imageAlt;
        modalCaption.querySelector('p').textContent = imageAlt;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Description Modal Functions
function showFullDescription(itemId, description, itemName) {
    const modal = document.getElementById('descriptionModal');
    const title = document.getElementById('descriptionModalTitle');
    const content = document.getElementById('descriptionModalContent');
    
    if (modal && title && content) {
        title.innerHTML = `<i class="fas fa-align-left mr-3 text-yellow-500"></i>Deskripsi: ${itemName}`;
        content.textContent = description;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeDescriptionModal() {
    const modal = document.getElementById('descriptionModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const imageModal = document.getElementById('imageModal');
    const descriptionModal = document.getElementById('descriptionModal');
    
    if (event.target === imageModal) {
        closeImageModal();
    }
    
    if (event.target === descriptionModal) {
        closeDescriptionModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
        closeDescriptionModal();
    }
});
</script>