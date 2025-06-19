<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Nilai Inventori</title>
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
        
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
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
        <h1>LAPORAN NILAI INVENTORI</h1>
        <p>Koperasi Management System</p>
        <p>Generated: {{ $generated_at->format('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Unit Usaha</th>
                <th class="text-center">Stok</th>
                <th class="text-right">Harga Beli</th>
                <th class="text-right">Nilai Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangs as $index => $barang)
                @php
                    $nilaiTotal = $barang->stok * ($barang->harga_beli ?? 0);
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $barang->kode_barang ?? '-' }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}</td>
                    <td class="text-center">{{ $barang->stok }}</td>
                    <td class="text-right">Rp {{ number_format($barang->harga_beli ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($nilaiTotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="6" class="text-right">TOTAL NILAI INVENTORI:</td>
                <td class="text-right">Rp {{ number_format($total_nilai, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem pada {{ $generated_at->format('d/m/Y H:i:s') }}</p>
        <p>© {{ date('Y') }} Koperasi Management System</p>
    </div>
</body>
</html>
