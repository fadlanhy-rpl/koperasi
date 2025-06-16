@extends('layouts.app')

@section('title', 'Kartu Stok: ' . $barang->nama_barang)
@section('page-title', 'Kartu Stok Barang')
@section('page-subtitle', 'Detail pergerakan stok untuk: ' . $barang->nama_barang . ' (' . ($barang->kode_barang ?? 'N/A') . ')')

@section('content')
<div class="animate-fade-in">
    <!-- Info Barang & Stok Terkini -->
    <div class="mb-6 bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
        {{-- ... (Bagian Info Barang sama seperti sebelumnya) ... --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $barang->nama_barang }}</h2>
                <p class="text-gray-600">Kode: {{ $barang->kode_barang ?? '-' }} | Unit Usaha: {{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}</p>
            </div>
            <div class="mt-4 md:mt-0 text-center md:text-right">
                <p class="text-xs text-gray-500 uppercase">Stok Terkini</p>
                <p class="text-3xl font-bold {{ $barang->stok <= 10 && $barang->stok > 0 ? 'text-red-600' : ($barang->stok == 0 ? 'text-gray-400' : 'text-green-600') }}">
                    {{ $barang->stok }} <span class="text-lg font-normal">{{ $barang->satuan }}</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Tabel Histori Stok -->
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Histori Pergerakan Stok</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-sm" id="historiStokTableKartu">
                    {{-- ... (thead tabel histori stok sama seperti sebelumnya) ... --}}
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-xs uppercase text-gray-500">
                            <th class="py-2.5 px-3 text-left">Tanggal & Waktu</th>
                            <th class="py-2.5 px-3 text-left">Tipe Transaksi</th>
                            <th class="py-2.5 px-3 text-center">Jumlah</th>
                            <th class="py-2.5 px-3 text-center">Stok Sebelum</th>
                            <th class="py-2.5 px-3 text-center">Stok Sesudah</th>
                            <th class="py-2.5 px-3 text-left">Keterangan</th>
                            <th class="py-2.5 px-3 text-left">Dicatat Oleh</th>
                        </tr>
                    </thead>
                    <tbody id="historiStokTableBodyKartu">
                        @include('pengurus.barang.partials._histori_stok_rows', ['historiStoks' => $kartu_stok])
                    </tbody>
                </table>
            </div>
            <div id="paginationLinksHistoriKartu" class="mt-6">
                {{ $kartu_stok->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
        <a href="{{ route('pengurus.laporan.stok.daftarTerkini') }}"> 
            <x-forms.button type="button" variant="secondary" icon="arrow-left">
                Kembali ke Laporan Stok
            </x-forms.button>
        </a>
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
</div>
@endsection

@push('scripts')
<script>
// ... (JavaScript untuk AJAX paginasi histori stok jika ada, seperti sebelumnya) ...
// Untuk kartu_stok.blade.php, paginasi standar (reload) mungkin sudah cukup
// Jika ingin AJAX, pastikan controller LaporanStokController@kartuStokBarang
// juga menghandle $request->ajax() dan mengembalikan JSON berisi html_histori dan pagination_histori
document.addEventListener('DOMContentLoaded', function() {
    const paginationContainer = document.getElementById('paginationLinksHistoriKartu');
    
    if (paginationContainer) {
        paginationContainer.addEventListener('click', function(event) {
            const target = event.target.closest('a');
            if (target && target.href && !target.classList.contains('disabled') && !target.querySelector('span[aria-disabled="true"]')) {
                // Jika ingin AJAX, uncomment dan pastikan controller support
                // event.preventDefault();
                // const url = new URL(target.href);
                // url.searchParams.set('page_kartu_stok', new URLSearchParams(url.search).get('page')); // Pastikan param paginasi benar
                // KoperasiApp.makeRequest(url.toString(), { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                //     .then(data => {
                //         if (data.html_histori && data.pagination_histori) {
                //             document.getElementById('historiStokTableBodyKartu').innerHTML = data.html_histori;
                //             document.getElementById('paginationLinksHistoriKartu').innerHTML = data.pagination_histori;
                //             window.scrollTo({ top: document.getElementById('historiStokTableKartu').offsetTop - 80, behavior: 'smooth' });
                //         }
                //     })
                //     .catch(error => console.error('Error fetching paginated kartu stok:', error));
            }
        });
    }
});
</script>
@endpush