@extends('layouts.app')

@section('title', 'Laporan Laba Rugi Penjualan - Koperasi')
@section('page-title', 'Laporan Estimasi Laba Rugi')
@section('page-subtitle', 'Analisis profitabilitas penjualan barang koperasi')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" />
<style>
    /* Menggunakan style yang mirip dengan laporan umum untuk konsistensi */
    .select2-container .select2-selection--single { height: 44px !important; border-radius: 12px !important; border: 2px solid #E5E7EB !important; padding-top: 0.5rem !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 42px !important; right: 12px !important;}
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 26px !important; padding-left: 16px !important;}
    .filter-section { background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(248, 250, 252, 0.9) 100%); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08); border-radius: 1rem; margin-bottom: 2rem; }
    .enhanced-table-container { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px); border-radius: 16px; overflow: hidden; box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.1); margin-top: 2rem; }
    .table-row:hover { background: linear-gradient(90deg, rgba(59, 130, 246, 0.05) 0%, rgba(147, 197, 253, 0.05) 100%); }
    .export-btn { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 10px 18px; border-radius: 10px; font-weight: 600; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; font-size: 13px;}
    .export-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3); }
    .stats-card { /* ... copy dari umum.blade.php jika ingin digunakan ... */ }
    .animate-slide-in-up { animation: slideInUp 0.6s ease-out forwards; }
    @keyframes slideInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endpush

@section('content')
<div class="animate-slide-in-up">
    <!-- Enhanced Filter Section -->
    <div class="filter-section p-6 md:p-8 relative">
        <div class="absolute top-4 right-4 md:top-6 md:right-6">
            <div class="flex flex-wrap gap-2">
                <button type="button" class="export-btn" onclick="exportLabaRugi('pdf')">
                    <i class="fas fa-file-pdf"></i> <span class="hidden sm:inline">PDF</span>
                </button>
                <button type="button" class="export-btn" onclick="exportLabaRugi('excel')">
                    <i class="fas fa-file-excel"></i> <span class="hidden sm:inline">Excel (CSV)</span>
                </button>
                 <button type="button" class="export-btn" onclick="printLabaRugi()">
                    <i class="fas fa-print"></i> <span class="hidden sm:inline">Print</span>
                </button>
            </div>
        </div>
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-1"><i class="fas fa-chart-line text-green-500 mr-2"></i>Filter Laporan Laba Rugi</h2>
            <p class="text-gray-600 text-sm">Pilih periode dan filter lain untuk melihat estimasi laba rugi.</p>
        </div>
        <form method="GET" action="{{ route('pengurus.laporan.penjualan.labaRugi') }}" id="filterLabaRugiForm" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 items-end">
                <div class="space-y-1">
                    <label for="daterange_laba_rugi" class="block text-xs font-semibold text-gray-700">Periode Tanggal:</label>
                    <input type="text" id="daterange_laba_rugi" name="daterange_laba_rugi" 
                           class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium">
                    <input type="hidden" name="tanggal_mulai" id="tanggal_mulai_laba_rugi" value="{{ request('tanggal_mulai', $tanggalMulai) }}">
                    <input type="hidden" name="tanggal_selesai" id="tanggal_selesai_laba_rugi" value="{{ request('tanggal_selesai', $tanggalSelesai) }}">
                </div>
                <div class="space-y-1">
                    <label for="unit_usaha_id_lr_filter" class="block text-xs font-semibold text-gray-700">Unit Usaha:</label>
                    <select name="unit_usaha_id" id="unit_usaha_id_lr_filter" class="w-full select2-filter-lr">
                        <option value="">Semua Unit Usaha</option>
                        @foreach($filters['unit_usahas'] as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_usaha_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit_usaha }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label for="barang_id_lr_filter" class="block text-xs font-semibold text-gray-700">Barang (Opsional):</label>
                    <select name="barang_id" id="barang_id_lr_filter" class="w-full select2-filter-lr">
                        <option value="">Semua Barang</option>
                        @foreach($filters['barangs'] as $barang)
                            <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>{{ $barang->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label for="per_page_lr_filter" class="block text-xs font-semibold text-gray-700">Item per Halaman:</label>
                     <select name="per_page" id="per_page_lr_filter" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium">
                        <option value="10" {{ request('per_page', 25) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 25) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 25) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
             <div class="flex justify-start items-end space-x-3 pt-5 border-t border-gray-200/80">
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center text-sm">
                    <i class="fas fa-filter mr-2"></i>Terapkan
                </button>
                <a href="{{ route('pengurus.laporan.penjualan.labaRugi') }}" class="bg-gradient-to-r from-gray-400 to-gray-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-gray-500 hover:to-gray-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center text-sm">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>
    
    <!-- Enhanced Ringkasan Laba Rugi -->
    <div class="stats-grid mb-6">
        <div class="stats-card" style="--gradient-from: #06b6d4; --gradient-to: #22d3ee;">
            <p class="text-sm opacity-90 mb-1">Total Pendapatan</p>
            <p class="text-3xl font-bold">@rupiah($totalPendapatanKeseluruhan)</p>
        </div>
        <div class="stats-card" style="--gradient-from: #f43f5e; --gradient-to: #fb7185;">
            <p class="text-sm opacity-90 mb-1">Total Estimasi HPP</p>
            <p class="text-3xl font-bold">@rupiah($totalHppEstimasiKeseluruhan)</p>
        </div>
        <div class="stats-card" style="--gradient-from: #16a34a; --gradient-to: #4ade80;">
            <p class="text-sm opacity-90 mb-1">Total Estimasi Laba Kotor</p>
            <p class="text-3xl font-bold">@rupiah($totalEstimasiLabaKotorKeseluruhan)</p>
        </div>
    </div>

    <!-- Enhanced Tabel Laporan Laba Rugi per Barang -->
    <div class="enhanced-table-container">
        <div class="p-6 border-b border-gray-200/80">
            <h3 class="text-xl font-bold text-gray-800 mb-1"><i class="fas fa-balance-scale text-green-500 mr-2"></i>Rincian Estimasi Laba Rugi per Barang</h3>
            <p class="text-sm text-gray-600">Periode: <span class="font-semibold text-blue-600">{{ \Carbon\Carbon::parse($tanggalMulai)->isoFormat('DD MMMM YYYY') }}</span> - <span class="font-semibold text-blue-600">{{ \Carbon\Carbon::parse($tanggalSelesai)->isoFormat('DD MMMM YYYY') }}</span></p>
        </div>
        <div class="p-2 md:p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-sm">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">No.</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Kode Barang</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Nama Barang</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-600">Terjual</th>
                            <th class="py-3 px-4 text-right font-semibold text-gray-600">Pendapatan</th>
                            <th class="py-3 px-4 text-right font-semibold text-gray-600">HPP (Est)</th>
                            <th class="py-3 px-4 text-right font-semibold text-gray-600">Laba Kotor (Est)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($laporanLabaRugiItems as $item)
                            <tr class="table-row">
                                <td class="py-4 px-4 text-gray-700">{{ $laporanLabaRugiItems->firstItem() + $loop->index }}</td>
                                <td class="py-4 px-4 text-gray-600">{{ $item->kode_barang ?? '-' }}</td>
                                <td class="py-4 px-4 font-medium text-gray-800">{{ $item->nama_barang }}</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded-full font-semibold text-xs">
                                        {{ number_format($item->total_terjual, 0, ',', '.') }} 
                                        <span class="ml-1 text-purple-600">{{ $item->satuan ?? '' }}</span>
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right text-gray-700">@rupiah($item->total_pendapatan)</td>
                                <td class="py-4 px-4 text-right text-gray-700">@rupiah($item->total_hpp_estimasi)</td>
                                <td class="py-4 px-4 text-right font-bold text-lg {{ $item->estimasi_laba_kotor >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    @rupiah($item->estimasi_laba_kotor)
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-16 text-gray-500">
                                    <div class="flex flex-col items-center space-y-3">
                                        <i class="fas fa-calculator text-5xl text-gray-300"></i>
                                        <p class="text-lg font-medium">Tidak ada data laba rugi ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($laporanLabaRugiItems->hasPages())
                <div class="mt-8">
                    {{ $laporanLabaRugiItems->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.select2-filter-lr').select2({
            placeholder: "Pilih Opsi",
            width: '100%',
            allowClear: true,
            dropdownParent: $(this).parent()
        });

        const initialStartDateLR = moment('{{ $tanggalMulai }}');
        const initialEndDateLR = moment('{{ $tanggalSelesai }}');

        $('#daterange_laba_rugi').daterangepicker({
            startDate: initialStartDateLR,
            endDate: initialEndDateLR,
            locale: { format: 'DD/MM/YYYY', /* ... locale lainnya ... */ },
            ranges: { /* ... ranges lainnya ... */ }
        }, function(start, end, label) {
            $('#tanggal_mulai_laba_rugi').val(start.format('YYYY-MM-DD'));
            $('#tanggal_selesai_laba_rugi').val(end.format('YYYY-MM-DD'));
        });
         if ($('#daterange_laba_rugi').val()) {
            const datesLR = $('#daterange_laba_rugi').data('daterangepicker');
            if (datesLR) {
                 $('#tanggal_mulai_laba_rugi').val(datesLR.startDate.format('YYYY-MM-DD'));
                 $('#tanggal_selesai_laba_rugi').val(datesLR.endDate.format('YYYY-MM-DD'));
            }
        }

        window.exportLabaRugi = function(format) {
            const form = document.getElementById('filterLabaRugiForm');
            if (!form) return;
            const params = new URLSearchParams(new FormData(form));
            params.append('export', format);
            window.open("{{ route('pengurus.laporan.penjualan.labaRugi') }}?" + params.toString(), '_blank');
        };
        window.printLabaRugi = function() {
            exportLabaRugi('pdf');
        }
    });
</script>
@endpush