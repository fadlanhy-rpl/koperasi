<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Pergerakan Stok</title>
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
        
        .tipe-masuk {
            color: #28a745;
            font-weight: bold;
        }
        
        .tipe-keluar {
            color: #dc3545;
            font-weight: bold;
        }
        
        .tipe-penyesuaian {
            color: #ffc107;
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
        <h1>HISTORI PERGERAKAN STOK</h1>
        <p>Koperasi Management System</p>
        <p>Generated: {{ $generated_at->format('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th class="text-center">Tipe</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Stok Sebelum</th>
                <th class="text-right">Stok Sesudah</th>
                <th>Keterangan</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histori as $index => $item)
                @php
                    $tipeClass = $item->tipe == 'masuk' ? 'tipe-masuk' : ($item->tipe == 'keluar' ? 'tipe-keluar' : 'tipe-penyesuaian');
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->barang->nama_barang ?? 'N/A' }}</td>
                    <td class="text-center {{ $tipeClass }}">{{ ucfirst($item->tipe) }}</td>
                    <td class="text-right">{{ $item->jumlah }}</td>
                    <td class="text-right">{{ $item->stok_sebelum }}</td>
                    <td class="text-right">{{ $item->stok_sesudah }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td>{{ $item->user->name ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data histori</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem pada {{ $generated_at->format('d/m/Y H:i:s') }}</p>
        <p>© {{ date('Y') }} Koperasi Management System</p>
    </div>
</body>
</html>
