@extends('layouts.app')

@section('title', 'Manajemen Barang - Koperasi')

@section('page-title', 'Manajemen Barang')
@section('page-subtitle', 'Kelola daftar barang di semua unit usaha')

@section('content')
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20 animate-fade-in">
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
            <h3 class="text-xl font-bold text-gray-800">Daftar Barang Koperasi</h3>
            <a href="{{ route('pengurus.barang.create') }}" class="w-full sm:w-auto">
                <x-forms.button type="button" variant="primary" icon="plus" class="w-full sm:w-auto">
                    Tambah Barang Baru
                </x-forms.button>
            </a>
        </div>
        
        <div class="p-6">
            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="unit_usaha_filter_barang" class="block text-sm font-medium text-gray-700 mb-1">Filter Unit Usaha:</label>
                    <select id="unit_usaha_filter_barang" name="unit_usaha_filter" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="">Semua Unit Usaha</option>
                        @foreach($unitUsahas as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_usaha_filter') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit_usaha }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="search_input_barang" class="block text-sm font-medium text-gray-700 mb-1">Pencarian Barang:</label>
                    <div class="relative">
                        <input type="text" id="search_input_barang" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode barang..." 
                               class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <!-- Barang Table -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px]" id="barangTable">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50">
                            <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Nama Barang (Kode)</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Unit Usaha</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Harga Beli</th>
                            <th class="text-right py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Harga Jual</th>
                            <th class="text-center py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Stok</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Deskripsi Singkat</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="barangTableBody">
                        @include('pengurus.barang.partials._barang_table_rows', ['barangs' => $barangs])
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div id="paginationLinksBarang" class="mt-6">
                {{ $barangs->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const unitUsahaFilter = document.getElementById('unit_usaha_filter_barang');
        const searchInput = document.getElementById('search_input_barang');
        let debounceTimerBarang;

        function fetchBarang() {
            clearTimeout(debounceTimerBarang);
            debounceTimerBarang = setTimeout(() => {
                const unitUsaha = unitUsahaFilter.value;
                const search = searchInput.value;
                const url = new URL("{{ route('pengurus.barang.index') }}");

                if (unitUsaha) url.searchParams.append('unit_usaha_filter', unitUsaha);
                if (search) url.searchParams.append('search', search);
                url.searchParams.append('page', '1');

                KoperasiApp.makeRequest(url.toString(), { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                    .then(data => {
                        document.getElementById('barangTableBody').innerHTML = data.html;
                        document.getElementById('paginationLinksBarang').innerHTML = data.pagination;
                    })
                    .catch(error => {
                        console.error('Error fetching barang:', error);
                        KoperasiApp.showNotification('Gagal memuat data barang.', 'error');
                    });
            }, 500);
        }

        if(unitUsahaFilter) unitUsahaFilter.addEventListener('change', fetchBarang);
        if(searchInput) searchInput.addEventListener('input', fetchBarang);

        document.getElementById('paginationLinksBarang').addEventListener('click', function(event) {
            const target = event.target.closest('a');
             if (target && target.href && !target.classList.contains('disabled') && !target.querySelector('span[aria-disabled="true"]')) {
                event.preventDefault();
                const url = new URL(target.href);
                const unitUsaha = unitUsahaFilter.value;
                const search = searchInput.value;
                
                if (unitUsaha) url.searchParams.set('unit_usaha_filter', unitUsaha); else url.searchParams.delete('unit_usaha_filter');
                if (search) url.searchParams.set('search', search); else url.searchParams.delete('search');

                KoperasiApp.makeRequest(url.toString(), { headers: {'X-Requested-With': 'XMLHttpRequest'} })
                    .then(data => {
                        document.getElementById('barangTableBody').innerHTML = data.html;
                        document.getElementById('paginationLinksBarang').innerHTML = data.pagination;
                        window.scrollTo({ top: document.getElementById('barangTable').offsetTop - 80, behavior: 'smooth' });
                    })
                    .catch(error => console.error('Error fetching paginated barang:', error));
            }
        });
    });
    
    function confirmDelete(deleteUrl, itemName) {
        if (confirm(`Apakah Anda yakin ingin menghapus barang "${itemName}"? Pastikan barang ini tidak terkait transaksi.`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl;
            form.style.display = 'none';
            
            const csrfTokenInput = document.createElement('input');
            csrfTokenInput.type = 'hidden';
            csrfTokenInput.name = '_token';
            csrfTokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfTokenInput);
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush