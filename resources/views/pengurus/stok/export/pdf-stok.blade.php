<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .filters {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .filters h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        
        .filters p {
            margin: 3px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
        }
        
        td {
            font-size: 10px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .status-normal {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-low {
            color: #ffc107;
            font-weight: bold;
        }
        
        .status-out {
            color: #dc3545;
            font-weight: bold;
        }
        
        .summary {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #ccc;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN STOK BARANG</h1>
        <p>Koperasi Management System</p>
        <p>Generated: {{ $generated_at->format('d/m/Y H:i:s') }}</p>
    </div>

    @if(!empty(array_filter($filters)))
    <div class="filters">
        <h3>Filter yang Diterapkan:</h3>
        @if($filters['search'])
            <p><strong>Pencarian:</strong> {{ $filters['search'] }}</p>
        @endif
        @if($filters['unit_usaha'])
            @php
                $unitUsaha = \App\Models\UnitUsaha::find($filters['unit_usaha']);
            @endphp
            <p><strong>Unit Usaha:</strong> {{ $unitUsaha ? $unitUsaha->nama_unit_usaha : 'N/A' }}</p>
        @endif
        @if($filters['stock_level'])
            @php
                $levelText = [
                    'low' => 'Stok Rendah',
                    'out' => 'Stok Habis',
                    'normal' => 'Stok Normal'
                ];
            @endphp
            <p><strong>Level Stok:</strong> {{ $levelText[$filters['stock_level']] ?? $filters['stock_level'] }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Unit Usaha</th>
                <th class="text-center">Stok</th>
                <th>Satuan</th>
                <th class="text-right">Harga Beli</th>
                <th class="text-right">Nilai Stok</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangs as $index => $barang)
                @php
                    $nilaiStok = $barang->stok * ($barang->harga_beli ?? 0);
                    $statusClass = $barang->stok == 0 ? 'status-out' : ($barang->stok <= 10 ? 'status-low' : 'status-normal');
                    $statusText = $barang->stok == 0 ? 'Habis' : ($barang->stok <= 10 ? 'Rendah' : 'Normal');
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $barang->kode_barang ?? '-' }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}</td>
                    <td class="text-center">{{ $barang->stok }}</td>
                    <td>{{ $barang->satuan }}</td>
                    <td class="text-right">Rp {{ number_format($barang->harga_beli ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($nilaiStok, 0, ',', '.') }}</td>
                    <td class="text-center {{ $statusClass }}">{{ $statusText }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3>Ringkasan Laporan</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <span>Total Item:</span>
                <strong>{{ $total_items }}</strong>
            </div>
            <div class="summary-item">
                <span>Total Nilai Inventori:</span>
                <strong>Rp {{ number_format($total_value, 0, ',', '.') }}</strong>
            </div>
            <div class="summary-item">
                <span>Stok Rendah:</span>
                <strong>{{ $low_stock }}</strong>
            </div>
            <div class="summary-item">
                <span>Stok Habis:</span>
                <strong>{{ $out_of_stock }}</strong>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem pada {{ $generated_at->format('d/m/Y H:i:s') }}</p>
        <p>© {{ date('Y') }} Koperasi Management System</p>
    </div>
</body>
</html>
