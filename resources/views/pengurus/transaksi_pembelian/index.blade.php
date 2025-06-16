@extends('layouts.app')

@section('title', 'Daftar Transaksi Pembelian - Koperasi')

@section('page-title', 'Riwayat Transaksi Pembelian')
@section('page-subtitle', 'Kelola dan lihat semua transaksi pembelian anggota')

@section('content')
<div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <h3 class="text-xl font-bold text-gray-800">Daftar Transaksi</h3>
        <a href="{{ route('pengurus.transaksi-pembelian.create') }}" class="w-full sm:w-auto">
            <x-forms.button type="button" variant="primary" icon="plus-circle" class="w-full sm:w-auto">
                Buat Transaksi Baru (POS)
            </x-forms.button>
        </a>
    </div>
    
    <div class="p-6">
        <!-- Filters -->
        <form method="GET" action="{{ route('pengurus.transaksi-pembelian.index') }}" id="filterTransaksiForm" class="mb-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label for="search_transaksi" class="block text-sm font-medium text-gray-700 mb-1">Cari Transaksi:</label>
                <input type="text" id="search_transaksi" name="search" value="{{ request('search') }}" placeholder="Kode/Nama Anggota..." 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label for="status_pembayaran_filter" class="block text-sm font-medium text-gray-700 mb-1">Status Bayar:</label>
                <select id="status_pembayaran_filter" name="status_pembayaran" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                    <option value="all">Semua Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status_pembayaran') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="tanggal_mulai_filter" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal:</label>
                <input type="date" id="tanggal_mulai_filter" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label for="tanggal_selesai_filter" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal:</label>
                <input type="date" id="tanggal_selesai_filter" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <div class="flex items-end">
                <x-forms.button type="submit" variant="secondary" size="md" class="w-full py-2.5">
                    <i class="fas fa-filter mr-2"></i>Filter
                </x-forms.button>
            </div>
        </form>
        
        <!-- Transaksi Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]" id="transaksiTable">
                <thead>
                    <tr class="border-b-2 border-gray-200 bg-gray-50">
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Kode & Tgl. Transaksi</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Anggota</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Kasir</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Total Harga</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Status Bayar</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Metode Bayar</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-600 uppercase text-xs">Aksi</th>
                    </tr>
                </thead>
                <tbody id="transaksiTableBody">
                    @include('pengurus.transaksi_pembelian.partials._transaksi_table_rows', ['pembelians' => $pembelians])
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div id="paginationLinksTransaksi" class="mt-6">
            {{ $pembelians->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterTransaksiForm');
        // Untuk AJAX filter jika diperlukan, mirip dengan manajemen pengguna/barang
        // Saat ini, filter akan submit form dan reload halaman.
        // Jika ingin AJAX:
        // const searchInput = document.getElementById('search_transaksi');
        // const statusFilter = document.getElementById('status_pembayaran_filter');
        // const tglMulai = document.getElementById('tanggal_mulai_filter');
        // const tglSelesai = document.getElementById('tanggal_selesai_filter');
        // let debounceTimerTransaksi;

        // function fetchTransaksi() {
        //     clearTimeout(debounceTimerTransaksi);
        //     debounceTimerTransaksi = setTimeout(() => {
        //         const formData = new FormData(filterForm);
        //         const params = new URLSearchParams(formData);
        //         const url = new URL("{{ route('pengurus.transaksi-pembelian.index') }}");
        //         url.search = params.toString();
        //         url.searchParams.append('page', '1');

        //         KoperasiApp.makeRequest(url.toString(), { headers: {'X-Requested-With': 'XMLHttpRequest'} })
        //             .then(data => {
        //                 document.getElementById('transaksiTableBody').innerHTML = data.html;
        //                 document.getElementById('paginationLinksTransaksi').innerHTML = data.pagination;
        //             })
        //             .catch(error => {
        //                 console.error('Error fetching transaksi:', error);
        //                 KoperasiApp.showNotification('Gagal memuat data transaksi.', 'error');
        //             });
        //     }, 500);
        // }

        // if(searchInput) searchInput.addEventListener('input', fetchTransaksi);
        // if(statusFilter) statusFilter.addEventListener('change', fetchTransaksi);
        // if(tglMulai) tglMulai.addEventListener('change', fetchTransaksi);
        // if(tglSelesai) tglSelesai.addEventListener('change', fetchTransaksi);

        // Untuk pagination AJAX (contoh dasar, perlu disesuaikan dengan filter)
        document.getElementById('paginationLinksTransaksi').addEventListener('click', function(event) {
            const target = event.target.closest('a');
            if (target && target.href && !target.classList.contains('disabled') && !target.querySelector('span[aria-disabled="true"]')) {
                // Jika ingin AJAX pagination, prevent default dan fetch.
                // Untuk non-AJAX, biarkan default behavior.
                // event.preventDefault();
                // const url = new URL(target.href);
                // // Append existing filters to pagination URL if needed
                // const currentParams = new URLSearchParams(new FormData(filterForm));
                // currentParams.forEach((value, key) => {
                //     if (key !== 'page') url.searchParams.set(key, value);
                // });
                // window.location.href = url.toString(); // atau fetch
            }
        });
    });
</script>
@endpush