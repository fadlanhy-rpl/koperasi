@extends('layouts.app')

@section('title', 'Laporan Penjualan per Barang - Koperasi')
@section('page-title', 'Laporan Penjualan per Barang')
@section('page-subtitle', 'Analisis penjualan berdasarkan item barang terlaris')

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
                <button type="button" class="export-btn" onclick="exportPerBarang('pdf')">
                    <i class="fas fa-file-pdf"></i> <span class="hidden sm:inline">PDF</span>
                </button>
                <button type="button" class="export-btn" onclick="exportPerBarang('excel')">
                    <i class="fas fa-file-excel"></i> <span class="hidden sm:inline">Excel (CSV)</span>
                </button>
                 <button type="button" class="export-btn" onclick="printPerBarang()">
                    <i class="fas fa-print"></i> <span class="hidden sm:inline">Print</span>
                </button>
            </div>
        </div>
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-1"><i class="fas fa-filter text-blue-500 mr-2"></i>Filter Laporan Penjualan per Barang</h2>
            <p class="text-gray-600 text-sm">Sesuaikan kriteria untuk analisis yang lebih mendalam.</p>
        </div>
        <form method="GET" action="{{ route('pengurus.laporan.penjualan.perBarang') }}" id="filterPerBarangForm" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
                <div class="space-y-1">
                    <label for="daterange_per_barang" class="block text-xs font-semibold text-gray-700">Periode Tanggal:</label>
                    <input type="text" id="daterange_per_barang" name="daterange_per_barang" 
                           class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium">
                    <input type="hidden" name="tanggal_mulai" id="tanggal_mulai_per_barang" value="{{ request('tanggal_mulai', $tanggalMulai) }}">
                    <input type="hidden" name="tanggal_selesai" id="tanggal_selesai_per_barang" value="{{ request('tanggal_selesai', $tanggalSelesai) }}">
                </div>
                <div class="space-y-1">
                    <label for="unit_usaha_id_per_barang" class="block text-xs font-semibold text-gray-700">Unit Usaha:</label>
                    <select name="unit_usaha_id" id="unit_usaha_id_per_barang" class="w-full select2-filter-per-barang">
                        <option value="">Semua Unit Usaha</option>
                        @foreach($filters['unit_usahas'] as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_usaha_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit_usaha }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label for="limit_per_barang" class="block text-xs font-semibold text-gray-700">Tampilkan (Top):</label>
                    <select name="limit" id="limit_per_barang" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium">
                        <option value="10" {{ request('limit', $limit) == 10 ? 'selected' : '' }}>10 Barang</option>
                        <option value="25" {{ request('limit', $limit) == 25 ? 'selected' : '' }}>25 Barang</option>
                        <option value="50" {{ request('limit', $limit) == 50 ? 'selected' : '' }}>50 Barang</option>
                        <option value="100" {{ request('limit', $limit) == 100 ? 'selected' : '' }}>100 Barang</option>
                    </select>
                </div>
            </div>
             <div class="flex justify-start items-end space-x-3 pt-5 border-t border-gray-200/80">
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center text-sm">
                    <i class="fas fa-filter mr-2"></i>Terapkan
                </button>
                <a href="{{ route('pengurus.laporan.penjualan.perBarang') }}" class="bg-gradient-to-r from-gray-400 to-gray-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-gray-500 hover:to-gray-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center text-sm">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Enhanced Table Laporan Penjualan per Barang -->
    <div class="enhanced-table-container">
        <div class="p-6 border-b border-gray-200/80">
            <h3 class="text-xl font-bold text-gray-800 mb-1"><i class="fas fa-boxes text-green-500 mr-2"></i>Laporan Penjualan per Barang</h3>
            <p class="text-sm text-gray-600">Periode: <span class="font-semibold text-blue-600">{{ \Carbon\Carbon::parse($tanggalMulai)->isoFormat('DD MMMM YYYY') }}</span> - <span class="font-semibold text-blue-600">{{ \Carbon\Carbon::parse($tanggalSelesai)->isoFormat('DD MMMM YYYY') }}</span>. Menampilkan top <span class="font-semibold">{{ $limit }}</span> barang.</p>
        </div>
        <div class="p-2 md:p-6">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-sm">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">No.</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Kode Barang</th>
                            <th class="py-3 px-4 text-left font-semibold text-gray-600">Nama Barang</th>
                            <th class="py-3 px-4 text-center font-semibold text-gray-600">Total Terjual</th>
                            <th class="py-3 px-4 text-right font-semibold text-gray-600">Total Omset</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($laporanPerBarang as $item)
                            <tr class="table-row">
                                <td class="py-4 px-4 text-gray-700">{{ $loop->iteration }}</td>
                                <td class="py-4 px-4 text-gray-600">{{ $item->kode_barang ?? '-' }}</td>
                                <td class="py-4 px-4 font-medium text-gray-800">{{ $item->nama_barang }}</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-semibold text-xs">
                                        {{ number_format($item->total_terjual, 0, ',', '.') }} 
                                        <span class="ml-1 text-blue-600">{{ $item->satuan ?? '' }}</span>
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right font-bold text-lg text-green-600">@rupiah($item->total_omset_barang)</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16 text-gray-500">
                                     <div class="flex flex-col items-center space-y-3">
                                        <i class="fas fa-box-open text-5xl text-gray-300"></i>
                                        <p class="text-lg font-medium">Tidak ada data penjualan barang.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Tidak ada paginasi karena ini adalah laporan 'Top X' --}}
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
        $('.select2-filter-per-barang').select2({
            placeholder: "Pilih Unit Usaha",
            width: '100%',
            allowClear: true,
            dropdownParent: $(this).parent()
        });

        const initialStartDatePerBarang = moment('{{ $tanggalMulai }}');
        const initialEndDatePerBarang = moment('{{ $tanggalSelesai }}');

        $('#daterange_per_barang').daterangepicker({
            startDate: initialStartDatePerBarang,
            endDate: initialEndDatePerBarang,
            locale: { format: 'DD/MM/YYYY', separator: ' - ', applyLabel: 'Terapkan', cancelLabel: 'Batal', fromLabel: 'Dari', toLabel: 'Sampai', customRangeLabel: 'Custom', daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'], monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'], firstDay: 1 },
            ranges: {
               'Hari Ini': [moment(), moment()],
               'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
               '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
               'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
               'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            alwaysShowCalendars: true,
            autoUpdateInput: true
        }, function(start, end, label) {
            $('#tanggal_mulai_per_barang').val(start.format('YYYY-MM-DD'));
            $('#tanggal_selesai_per_barang').val(end.format('YYYY-MM-DD'));
        });
        if ($('#daterange_per_barang').val()) {
            const dates = $('#daterange_per_barang').data('daterangepicker');
            if (dates) {
                 $('#tanggal_mulai_per_barang').val(dates.startDate.format('YYYY-MM-DD'));
                 $('#tanggal_selesai_per_barang').val(dates.endDate.format('YYYY-MM-DD'));
            }
        }

        window.exportPerBarang = function(format) {
            const form = document.getElementById('filterPerBarangForm');
            if (!form) return;
            const params = new URLSearchParams(new FormData(form));
            params.append('export', format);
            window.open("{{ route('pengurus.laporan.penjualan.perBarang') }}?" + params.toString(), '_blank');
        };
        window.printPerBarang = function() {
            exportPerBarang('pdf');
        }
    });
</script>
@endpush