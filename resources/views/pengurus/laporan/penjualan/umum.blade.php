@extends('layouts.app')

@section('title', 'Laporan Penjualan Umum - Koperasi')

@section('page-title', 'Laporan Penjualan Umum')
@section('page-subtitle', 'Rincian item penjualan berdasarkan periode dan filter')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Style Select2 agar konsisten dengan form lain */
    .select2-container .select2-selection--single { height: 42px !important; border-radius: 0.75rem !important; border: 1px solid #D1D5DB !important; padding-top: 0.45rem !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 24px !important; }
    .select2-dropdown { border-radius: 0.75rem !important; border: 1px solid #D1D5DB !important; }
    .select2-search--dropdown .select2-search__field { border-radius: 0.5rem !important; border: 1px solid #D1D5DB !important; padding: 0.5rem 0.75rem !important;}
</style>
@endpush

@section('content')
<div class="animate-fade-in">
    <!-- Filter Section -->
    <div class="mb-6 bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
        <form method="GET" action="{{ route('pengurus.laporan.penjualan.umum') }}" id="filterLaporanForm" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 items-end">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal:</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai', $tanggalMulai) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal:</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ request('tanggal_selesai', $tanggalSelesai) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="anggota_id" class="block text-sm font-medium text-gray-700 mb-1">Anggota:</label>
                    <select name="anggota_id" id="anggota_id" class="w-full select2-filter">
                        <option value="">Semua Anggota</option>
                        @foreach($filters['anggotas'] as $anggota)
                            <option value="{{ $anggota->id }}" {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                {{ $anggota->name }} ({{ $anggota->nomor_anggota ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="unit_usaha_id" class="block text-sm font-medium text-gray-700 mb-1">Unit Usaha:</label>
                    <select name="unit_usaha_id" id="unit_usaha_id" class="w-full select2-filter">
                        <option value="">Semua Unit Usaha</option>
                        @foreach($filters['unit_usahas'] as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_usaha_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit_usaha }}</option>
                        @endforeach
                    </select>
                </div>
                 {{-- Filter barang bisa ditambahkan jika diperlukan, namun bisa jadi sangat banyak --}}
                 {{-- <div>
                    <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-1">Barang:</label>
                    <select name="barang_id" id="barang_id" class="w-full select2-filter">
                        <option value="">Semua Barang</option>
                        @foreach($filters['barangs'] as $barang)
                            <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->nama_barang }} ({{ $barang->kode_barang ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div> --}}
                <div>
                    <label for="status_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran:</label>
                    <select name="status_pembayaran" id="status_pembayaran" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm">
                        @foreach($filters['status_pembayaran_options'] as $value => $label)
                            <option value="{{ $value }}" {{ request('status_pembayaran', 'all') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-start items-end space-x-3 mt-4">
                <x-forms.button type="submit" variant="primary" size="md" icon="filter">
                    Terapkan Filter
                </x-forms.button>
                <a href="{{ route('pengurus.laporan.penjualan.umum') }}">
                    <x-forms.button type="button" variant="secondary" size="md" icon="undo">
                        Reset Filter
                    </x-forms.button>
                </a>
            </div>
        </form>
    </div>

    <!-- Ringkasan Penjualan -->
    <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="ringkasanPenjualanContainer">
        <div class="bg-gradient-to-br from-blue-500 to-purple-600 p-6 rounded-2xl shadow-xl text-white">
            <p class="text-sm text-blue-100 mb-1">Total Omset Penjualan</p>
            <p class="text-3xl font-bold" id="totalOmset">@rupiah($totalOmset)</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-teal-500 p-6 rounded-2xl shadow-xl text-white">
            <p class="text-sm text-green-100 mb-1">Total Item Terjual</p>
            <p class="text-3xl font-bold" id="totalItemTerjual">{{ number_format($totalItemTerjual, 0, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-orange-500 p-6 rounded-2xl shadow-xl text-white">
            <p class="text-sm text-orange-100 mb-1">Jumlah Transaksi</p>
            <p class="text-3xl font-bold" id="jumlahTransaksi">{{ number_format($jumlahTransaksi, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Tabel Detail Penjualan -->
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-white/20">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xl font-bold text-gray-800">Rincian Item Penjualan</h3>
            <p class="text-sm text-gray-500">Menampilkan item per transaksi dari <span class="font-semibold">{{ \Carbon\Carbon::parse($tanggalMulai)->isoFormat('DD MMMM YYYY') }}</span> sampai <span class="font-semibold">{{ \Carbon\Carbon::parse($tanggalSelesai)->isoFormat('DD MMMM YYYY') }}</span></p>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-sm" id="detailPenjualanTable">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50 text-xs uppercase text-gray-500">
                            <th class="py-2.5 px-3 text-left">Kode & Tgl. Transaksi</th>
                            <th class="py-2.5 px-3 text-left">Anggota</th>
                            <th class="py-2.5 px-3 text-left">Barang (Unit Usaha)</th>
                            <th class="py-2.5 px-3 text-center">Jml</th>
                            <th class="py-2.5 px-3 text-right">Harga Satuan</th>
                            <th class="py-2.5 px-3 text-right">Subtotal</th>
                            <th class="py-2.5 px-3 text-center">Status Bayar</th>
                            <th class="py-2.5 px-3 text-left">Metode Bayar</th>
                        </tr>
                    </thead>
                    <tbody id="detailPenjualanTableBody">
                        @include('pengurus.laporan.penjualan.partials._penjualan_umum_rows', ['detailPembelians' => $detailPembelians])
                    </tbody>
                </table>
            </div>
             <!-- Pagination -->
            <div id="paginationLinksDetailPenjualan" class="mt-6">
                {{ $detailPembelians->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.select2-filter').select2({
            placeholder: $(this).data('placeholder') || "Pilih opsi",
            width: '100%',
            allowClear: true // Memungkinkan opsi "Semua" dipilih kembali dengan menghapus pilihan
        });

        const filterForm = document.getElementById('filterLaporanForm');
        const paginationContainer = document.getElementById('paginationLinksDetailPenjualan');
        let currentRequestController = null; // AbortController

        function fetchLaporanData(url) {
            // Abort previous request if any
            if (currentRequestController) {
                currentRequestController.abort();
            }
            currentRequestController = new AbortController();
            const signal = currentRequestController.signal;

            // Show loading indicator (optional)
            // document.getElementById('loadingIndicator').style.display = 'block';

            KoperasiApp.makeRequest(url, { signal, headers: {'X-Requested-With': 'XMLHttpRequest'} })
                .then(data => {
                    if (signal.aborted) return; // Check if request was aborted

                    document.getElementById('detailPenjualanTableBody').innerHTML = data.html;
                    paginationContainer.innerHTML = data.pagination;
                    
                    // Update ringkasan
                    document.getElementById('totalOmset').textContent = data.ringkasan.total_omset_formatted;
                    document.getElementById('totalItemTerjual').textContent = data.ringkasan.total_item_terjual;
                    document.getElementById('jumlahTransaksi').textContent = data.ringkasan.jumlah_transaksi;

                    // Update URL
                    window.history.pushState({path:url},'',url);
                })
                .catch(error => {
                    if (error.name === 'AbortError') {
                        console.log('Fetch aborted');
                    } else {
                        console.error('Error fetching laporan:', error);
                        KoperasiApp.showNotification('Gagal memuat data laporan.', 'error');
                    }
                })
                .finally(() => {
                    // Hide loading indicator
                    // document.getElementById('loadingIndicator').style.display = 'none';
                    currentRequestController = null;
                });
        }

        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                const formData = new FormData(this);
                const params = new URLSearchParams(formData);
                params.set('page', '1'); // Selalu ke halaman pertama saat filter baru
                const url = "{{ route('pengurus.laporan.penjualan.umum') }}?" + params.toString();
                fetchLaporanData(url);
            });
        }

        if (paginationContainer) {
            paginationContainer.addEventListener('click', function(event) {
                const target = event.target.closest('a');
                if (target && target.href && !target.classList.contains('disabled') && !target.querySelector('span[aria-disabled="true"]')) {
                    event.preventDefault();
                    const url = target.href; // URL sudah mengandung parameter filter dari withQueryString()
                    fetchLaporanData(url);
                     window.scrollTo({ top: document.getElementById('detailPenjualanTable').offsetTop - 80, behavior: 'smooth' });
                }
            });
        }
    });
</script>
@endpush