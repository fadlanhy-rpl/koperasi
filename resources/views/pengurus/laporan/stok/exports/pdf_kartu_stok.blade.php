<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Stok: {{ $barang->nama_barang }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header p { margin: 3px 0; font-size: 10px; color: #555; }
        .barang-info { margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6; }
        .barang-info h3 { margin-top: 0; font-size: 12px; }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .info-item { margin-bottom: 5px; }
        .info-item .label { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 9px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 8px; color: #777; }
        .tipe-masuk { color: #28a745; font-weight: bold; }
        .tipe-keluar { color: #dc3545; font-weight: bold; }
        .tipe-penyesuaian { color: #ffc107; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kartu Stok Barang</h1>
        <p>{{ config('app.name', 'Koperasi Management System') }}</p>
        <p>Dicetak pada: {{ $generated_at->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</p>
    </div>

    <div class="barang-info">
        <h3>Informasi Barang</h3>
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <span class="label">Nama Barang:</span> {{ $barang->nama_barang }}
                </div>
                <div class="info-item">
                    <span class="label">Kode Barang:</span> {{ $barang->kode_barang ?? '-' }}
                </div>
                <div class="info-item">
                    <span class="label">Unit Usaha:</span> {{ $barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}
                </div>
                <div class="info-item">
                    <span class="label">Satuan:</span> {{ $barang->satuan }}
                </div>
            </div>
            <div>
                <div class="info-item">
                    <span class="label">Stok Terkini:</span> {{ number_format($barang->stok, 0, ',', '.') }} {{ $barang->satuan }}
                </div>
                <div class="info-item">
                    <span class="label">Harga Beli:</span> Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}
                </div>
                <div class="info-item">
                    <span class="label">Harga Jual:</span> Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}
                </div>
                <div class="info-item">
                    <span class="label">Nilai Stok:</span> Rp {{ number_format($barang->stok * $barang->harga_beli, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal & Waktu</th>
                <th>Tipe Transaksi</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Stok Sebelum</th>
                <th class="text-center">Stok Sesudah</th>
                <th>Keterangan</th>
                <th>Dicatat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kartu_stok as $histori)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($histori->created_at)->format('d/m/Y H:i:s') }}</td>
                    <td>
                        @if($histori->tipe_transaksi == 'masuk')
                            <span class="tipe-masuk">{{ ucfirst(str_replace('_', ' ', $histori->tipe_transaksi)) }}</span>
                        @elseif($histori->tipe_transaksi == 'keluar')
                            <span class="tipe-keluar">{{ ucfirst(str_replace('_', ' ', $histori->tipe_transaksi)) }}</span>
                        @else
                            <span class="tipe-penyesuaian">{{ ucfirst(str_replace('_', ' ', $histori->tipe_transaksi)) }}</span>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($histori->jumlah, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($histori->stok_sebelum, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($histori->stok_sesudah, 0, ',', '.') }}</td>
                    <td>{{ $histori->keterangan ?? '-' }}</td>
                    <td>{{ $histori->user->name ?? 'System' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada histori pergerakan stok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dicetak oleh sistem pada {{ $generated_at->isoFormat('DD MMMM YYYY, HH:mm:ss') }}
    </div>
</body>
</html>
