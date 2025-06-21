<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Estimasi Laba Rugi Penjualan</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 14px; }
        .header p { margin: 3px 0; font-size: 9px; color: #555; }
        .filters-section { margin-bottom: 15px; padding: 8px; border: 1px solid #eee; font-size: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 8px;}
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 8px; color: #777;}
        .total-row { background-color: #e9ecef; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Estimasi Laba Rugi Penjualan</h1>
        <p>{{ config('app.name', 'Koperasi Management System') }}</p>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->isoFormat('DD MMM YYYY') }} - {{ \Carbon\Carbon::parse($tanggalSelesai)->isoFormat('DD MMM YYYY') }}</p>
        <p>Dicetak pada: {{ $generated_at->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</p>
    </div>

    @if(count(array_filter($filtersApplied)))
    <div class="filters-section">
        <h3>Filter yang Diterapkan:</h3>
        @foreach($filtersApplied as $key => $value)
            @if($value)
                <p><strong>{{ ucfirst(str_replace(['_id', '_'], ['', ' '], $key)) }}:</strong> 
                @if($key === 'unit_usaha_id')
                     {{ \App\Models\UnitUsaha::find($value)->nama_unit_usaha ?? $value }}
                @elseif($key === 'barang_id')
                     {{ \App\Models\Barang::find($value)->nama_barang ?? $value }}
                @else
                    {{ ucfirst($value) }}
                @endif
                </p>
            @endif
        @endforeach
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th class="text-center">Terjual</th>
                <th class="text-right">Pendapatan</th>
                <th class="text-right">HPP (Est)</th>
                <th class="text-right">Laba Kotor (Est)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporanLabaRugiItems as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->kode_barang ?? '-' }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="text-center">{{ number_format($item->total_terjual, 0, ',', '.') }} {{ $item->satuan }}</td>
                    <td class="text-right">{{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->total_hpp_estimasi, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->estimasi_laba_kotor, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL KESELURUHAN:</td>
                <td class="text-right">{{ number_format($totalPendapatanKeseluruhan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalHppEstimasiKeseluruhan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalEstimasiLabaKotorKeseluruhan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dicetak oleh sistem.
    </div>
</body>
</html>