{{-- resources/views/pengurus/transaksi_pembelian/index.blade.php --}}
@extends('layouts.app')
{{-- ... (@section title, page-title, page-subtitle) ... --}}
@section('title', 'Daftar Transaksi Pembelian - Koperasi')
@section('page-title', 'Riwayat Transaksi Pembelian')
@section('page-subtitle', 'Kelola dan lihat semua transaksi pembelian anggota')

@section('content')
<div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <h3 class="text-xl font-bold text-gray-800">Daftar Transaksi</h3>
        <a href="{{ route('pengurus.transaksi-pembelian.create') }}" class="w-full sm:w-auto">
            <x-forms.button type="button" variant="primary" icon="plus-circle" class="w-full sm:w-auto">Buat Transaksi Baru (POS)</x-forms.button>
        </a>
    </div>
    <div class="p-6">
        <form method="GET" action="{{ route('pengurus.transaksi-pembelian.index') }}" id="filterTransaksiForm" class="mb-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 items-end">
            {{-- ... (Input filter sama seperti sebelumnya, pastikan ID unik jika perlu) ... --}}
            <div><label for="search_transaksi_idx" class="block text-sm font-medium text-gray-700 mb-1">Cari:</label><input type="text" id="search_transaksi_idx" name="search" value="{{ request('search') }}" placeholder="Kode/Anggota..." class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm"></div>
            <div><label for="status_pembayaran_filter_idx" class="block text-sm font-medium text-gray-700 mb-1">Status:</label><select id="status_pembayaran_filter_idx" name="status_pembayaran" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm"> @foreach($statuses as $value => $label)<option value="{{ $value }}" {{ request('status_pembayaran', 'all') == $value ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select></div>
            <div><label for="tanggal_mulai_filter_idx" class="block text-sm font-medium text-gray-700 mb-1">Dari:</label><input type="date" id="tanggal_mulai_filter_idx" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm"></div>
            <div><label for="tanggal_selesai_filter_idx" class="block text-sm font-medium text-gray-700 mb-1">Sampai:</label><input type="date" id="tanggal_selesai_filter_idx" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm"></div>
            <div class="flex items-end space-x-2">
                <x-forms.button type="button" id="applyTransaksiFilterBtn" variant="secondary" size="md" class="w-full py-2.5"><i class="fas fa-filter mr-2"></i>Filter</x-forms.button>
                <a href="{{ route('pengurus.transaksi-pembelian.index') }}" class="w-full sm:w-auto">
                    <x-forms.button type="button" variant="neutral" size="md" class="w-full py-2.5"><i class="fas fa-undo mr-2"></i>Reset</x-forms.button>
                </a>
            </div>
        </form>
        <div class="overflow-x-auto"><table class="w-full min-w-[900px]" id="transaksiTable"><thead class="border-b-2 border-gray-200 bg-gray-50"><tr class="text-xs uppercase text-gray-500"><th class="py-3 px-4 font-semibold text-left">Kode & Tgl. Transaksi</th><th class="py-3 px-4 font-semibold text-left">Anggota</th><th class="py-3 px-4 font-semibold text-left">Kasir</th><th class="py-3 px-4 font-semibold text-right">Total Harga</th><th class="py-3 px-4 font-semibold text-center">Status Bayar</th><th class="py-3 px-4 font-semibold text-left">Metode Bayar</th><th class="py-3 px-4 font-semibold text-center">Aksi</th></tr></thead><tbody id="transaksiTableBody"> @include('pengurus.transaksi_pembelian.partials._transaksi_table_rows', ['pembelians' => $pembelians])</tbody></table></div>
        <div id="paginationLinksTransaksi" class="mt-6">{{ $pembelians->links('vendor.pagination.tailwind') }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterTransaksiForm');
        const tableBody = document.getElementById('transaksiTableBody');
        const paginationContainer = document.getElementById('paginationLinksTransaksi');
        const applyFilterBtn = document.getElementById('applyTransaksiFilterBtn'); // Tombol filter
        let currentRequestController = null;

        function fetchTransaksiData(url) {
            if (currentRequestController) currentRequestController.abort();
            currentRequestController = new AbortController();
            const signal = currentRequestController.signal;

            // tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i> Memuat...</td></tr>';

            KoperasiApp.makeRequest(url, { signal, headers: {'X-Requested-With': 'XMLHttpRequest'} })
                .then(data => {
                    if (signal.aborted) return;
                    if(data.html) tableBody.innerHTML = data.html;
                    if(data.pagination) paginationContainer.innerHTML = data.pagination;
                    window.history.pushState({path:url},'',url);
                })
                .catch(error => {
                    if (error.name !== 'AbortError') {
                        KoperasiApp.showNotification('Gagal memuat data transaksi.', 'error');
                        tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-red-500">Gagal memuat data.</td></tr>';
                    }
                })
                .finally(() => { currentRequestController = null; });
        }

        if (applyFilterBtn) {
            applyFilterBtn.addEventListener('click', function() {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                params.set('page', '1'); // Selalu ke halaman pertama saat filter baru
                const url = "{{ route('pengurus.transaksi-pembelian.index') }}?" + params.toString();
                fetchTransaksiData(url);
            });
        }
        
        // Untuk input search, bisa tambahkan debounce jika mau live search
        const searchInputTrx = document.getElementById('search_transaksi_idx');
        let searchDebounceTimer;
        if(searchInputTrx){
            searchInputTrx.addEventListener('input', function(){
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(() => {
                    applyFilterBtn.click(); // Trigger filter setelah debounce
                }, 700);
            });
        }


        if (paginationContainer) {
            paginationContainer.addEventListener('click', function(event) {
                const target = event.target.closest('a');
                if (target && target.href && !target.classList.contains('disabled') && !target.querySelector('span[aria-disabled="true"]')) {
                    event.preventDefault();
                    fetchTransaksiData(target.href); // URL dari link paginasi sudah benar
                    window.scrollTo({ top: document.getElementById('transaksiTable').offsetTop - 80, behavior: 'smooth' });
                }
            });
        }
    });
</script>
@endpush