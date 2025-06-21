<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Barang Terkini</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header p { margin: 3px 0; font-size: 10px; color: #555; }
        .filters-section { margin-bottom: 15px; padding: 10px; border: 1px solid #eee; font-size: 9px; }
        .filters-section h3 { margin-top: 0; font-size: 11px; }
        .summary-section { margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6; }
        .summary-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        .summary-item { text-align: center; }
        .summary-item .label { font-size: 8px; color: #666; margin-bottom: 2px; }
        .summary-item .value { font-size: 12px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 9px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 8px; color: #777; }
        .stok-status { padding: 2px 6px; border-radius: 10px; font-size: 8px; font-weight: bold; }
        .stok-aman { background-color: #d4edda; color: #155724; }
        .stok-rendah { background-color: #fff3cd; color: #856404; }
        .stok-habis { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Stok Barang Terkini</h1>
        <p>{{ config('app.name', 'Koperasi Management System') }}</p>
        <p>Dicetak pada: {{ $generated_at->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</p>
    </div>

    @if(count(array_filter($filtersApplied)))
    <div class="filters-section">
        <h3>Filter yang Diterapkan:</h3>
        @foreach($filtersApplied as $key => $value)
            @if($value && $value !== 'all')
                <p><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                @if($key === 'unit_usaha_id')
                    {{ \App\Models\UnitUsaha::find($value)->nama_unit_usaha ?? $value }}
                @elseif($key === 'search_barang')
                    "{{ $value }}"
                @else
                    {{ $value }}
                @endif
                </p>
            @endif
        @endforeach
    </div>
    @endif

    <div class="summary-section">
        <h3>Ringkasan Stok</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total Nilai Stok</div>
                <div class="value">Rp {{ number_format($total_nilai_stok_estimasi, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Items</div>
                <div class="value">{{ number_format($total_items, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Stok Rendah</div>
                <div class="value">{{ number_format($stok_rendah_count, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Stok Habis</div>
                <div class="value">{{ number_format($stok_habis_count, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Unit Usaha</th>
                <th class="text-center">Stok</th>
                <th class="text-center">Satuan</th>
                <th class="text-right">Harga Beli</th>
                <th class="text-right">Harga Jual</th>
                <th class="text-right">Nilai Stok</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftar_stok as $index => $barang)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $barang->kode_barang ?? '-' }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($barang->stok, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $barang->satuan }}</td>
                    <td class="text-right">{{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($barang->stok * $barang->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($barang->stok == 0)
                            <span class="stok-status stok-habis">Habis</span>
                        @elseif($barang->stok <= 10)
                            <span class="stok-status stok-rendah">Rendah</span>
                        @else
                            <span class="stok-status stok-aman">Aman</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data stok barang.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dicetak oleh sistem pada {{ $generated_at->isoFormat('DD MMMM YYYY, HH:mm:ss') }}
    </div>
</body>
</html>
