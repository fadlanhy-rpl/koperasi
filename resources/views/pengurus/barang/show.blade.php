@extends('layouts.app')

@section('title', 'Detail Barang: ' . $barang->nama_barang)
@section('page-title', 'Detail Barang')
@section('page-subtitle', $barang->nama_barang . ' (' . ($barang->kode_barang ?? 'Tanpa Kode') . ')')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-in">
        <!-- Detail Barang Card -->
        <div class="lg:col-span-1">
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800">Informasi Barang</h3>
                </div>
                <div class="p-6 space-y-3 text-sm">
                    {{-- ... (Detail informasi barang seperti sebelumnya) ... --}}
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Nama Barang:</span>
                        <span class="font-semibold text-gray-800 text-right">{{ $barang->nama_barang }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Kode Barang:</span>
                        <span class="font-semibold text-gray-800">{{ $barang->kode_barang ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Unit Usaha:</span>
                        <span class="font-semibold text-gray-800">{{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Harga Beli:</span>
                        <span class="font-semibold text-gray-800">@rupiah($barang->harga_beli)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Harga Jual:</span>
                        <span class="font-semibold text-gray-800">@rupiah($barang->harga_jual)</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 font-medium">Stok Saat Ini:</span>
                        <span class="font-bold text-xl {{ $barang->stok <= 10 && $barang->stok > 0 ? 'text-red-600' : ($barang->stok == 0 ? 'text-gray-400' : 'text-green-600') }}">
                            {{ $barang->stok }} <span class="text-xs text-gray-500 font-normal">{{ $barang->satuan }}</span>
                        </span>
                    </div>
                     <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Satuan:</span>
                        <span class="font-semibold text-gray-800">{{ ucfirst($barang->satuan) }}</span>
                    </div>
                    <hr class="my-2">
                    <div>
                        <span class="text-gray-500 font-medium block mb-1">Deskripsi:</span>
                        <p class="text-gray-700 leading-relaxed">{{ $barang->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </div>
                    <div class="mt-6 flex space-x-3">
                        <a href="{{ route('pengurus.barang.edit', $barang->id) }}" class="flex-1">
                            <x-forms.button type="button" variant="primary" icon="edit" class="w-full">Edit Barang</x-forms.button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Histori Stok Card -->
        <div class="lg:col-span-2">
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-gray-100 flex flex-wrap justify-between items-center gap-3">
                    <h3 class="text-xl font-bold text-gray-800">Histori Pergerakan Stok</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('pengurus.stok.formBarangMasuk', ['barang' => $barang->id]) }}">
                            <x-forms.button type="button" variant="success" size="sm" icon="plus-circle">Stok Masuk</x-forms.button>
                        </a>
                        {{-- PERBAIKAN DI SINI --}}
                        <a href="{{ route('pengurus.stok.formBarangKeluar', ['barang' => $barang->id]) }}">
                            <x-forms.button type="button" variant="danger" size="sm" icon="minus-circle">Stok Keluar</x-forms.button>
                        </a>
                        <a href="{{ route('pengurus.stok.formPenyesuaianStok', ['barang' => $barang->id]) }}">
                            <x-forms.button type="button" variant="neutral" size="sm" icon="exchange-alt">Penyesuaian</x-forms.button>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[700px]" id="historiStokTable">
                            {{-- ... (thead tabel histori stok) ... --}}
                            <thead>
                                <tr class="border-b-2 border-gray-200 bg-gray-50">
                                    <th class="text-left py-2.5 px-4 font-semibold text-gray-600 uppercase text-xs">Tanggal</th>
                                    <th class="text-left py-2.5 px-4 font-semibold text-gray-600 uppercase text-xs">Tipe</th>
                                    <th class="text-center py-2.5 px-4 font-semibold text-gray-600 uppercase text-xs">Jumlah</th>
                                    <th class="text-center py-2.5 px-4 font-semibold text-gray-600 uppercase text-xs">Stok Sebelum</th>
                                    <th class="text-center py-2.5 px-4 font-semibold text-gray-600 uppercase text-xs">Stok Sesudah</th>
                                    <th class="text-left py-2.5 px-4 font-semibold text-gray-600 uppercase text-xs">Keterangan</th>
                                    <th class="text-left py-2.5 px-4 font-semibold text-gray-600 uppercase text-xs">Oleh</th>
                                </tr>
                            </thead>
                            <tbody id="historiStokTableBody">
                                @include('pengurus.barang.partials._histori_stok_rows', ['historiStoks' => $historiStoks])
                            </tbody>
                        </table>
                    </div>
                    <div id="paginationLinksHistori" class="mt-6">
                        {{ $historiStoks->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-start">
        <a href="{{ route('pengurus.barang.index') }}">
            <x-forms.button type="button" variant="secondary" icon="arrow-left">
                Kembali ke Daftar Barang
            </x-forms.button>
        </a>
    </div>
@endsection

@push('scripts')
<script>
// ... (JavaScript untuk AJAX paginasi histori stok jika ada, seperti sebelumnya) ...
document.addEventListener('DOMContentLoaded', function() {
    const paginationContainer = document.getElementById('paginationLinksHistori');
    if (paginationContainer) {
        paginationContainer.addEventListener('click', function(event) {
            const target = event.target.closest('a');
            if (target && target.href && !target.classList.contains('disabled') && !target.querySelector('span[aria-disabled="true"]')) {
                event.preventDefault();
                const url = new URL(target.href);
                // Pastikan parameter 'page_histori' digunakan untuk request AJAX
                // URL dari paginator Laravel sudah benar jika nama parameter unik ('page_histori')
                
                KoperasiApp.makeRequest(url.toString(), { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                    .then(data => {
                        if (data.html_histori && data.pagination_histori) {
                            document.getElementById('historiStokTableBody').innerHTML = data.html_histori;
                            document.getElementById('paginationLinksHistori').innerHTML = data.pagination_histori;
                             window.scrollTo({ top: document.getElementById('historiStokTable').offsetTop - 80, behavior: 'smooth' });
                        } else {
                             KoperasiApp.showNotification('Gagal memuat data paginasi histori.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching histori stok (paginasi):', error);
                        KoperasiApp.showNotification('Gagal memuat histori stok.', 'error');
                    });
            }
        });
    }
});
</script>
@endpush