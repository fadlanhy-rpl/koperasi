<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Umum</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header p { margin: 3px 0; font-size: 10px; color: #555; }
        .filters-section { margin-bottom: 15px; padding: 10px; border: 1px solid #eee; font-size: 9px; }
        .filters-section h3 { margin-top: 0; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 9px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .summary-section { margin-top: 20px; padding-top: 10px; border-top: 1px solid #000; }
        .summary-section table { width: 50%; float: right; }
        .summary-section td { border: none; padding: 3px; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 8px; color: #777; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Penjualan Umum</h1>
        <p>{{ config('app.name', 'Koperasi Management System') }}</p>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->isoFormat('DD MMM YYYY') }} - {{ \Carbon\Carbon::parse($tanggalSelesai)->isoFormat('DD MMM YYYY') }}</p>
        <p>Dicetak pada: {{ $generated_at->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</p>
    </div>

    @if(count(array_filter($filtersApplied)))
    <div class="filters-section">
        <h3>Filter yang Diterapkan:</h3>
        @foreach($filtersApplied as $key => $value)
            @if($value && $value !== 'all')
                <p><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                @if($key === 'anggota_id')
                    {{ \App\Models\User::find($value)->name ?? $value }}
                @elseif($key === 'unit_usaha_id')
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
                <th>Kode Trx</th>
                <th>Tanggal</th>
                <th>Anggota</th>
                <th>Barang</th>
                <th>Unit Usaha</th>
                <th class="text-center">Jml</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-right">Subtotal</th>
                <th class="text-center">Status</th>
                <th>Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailPembelians as $detail)
                <tr>
                    <td>{{ $detail->pembelian->kode_pembelian }}</td>
                    <td>{{ \Carbon\Carbon::parse($detail->pembelian->tanggal_pembelian)->format('d/m/y H:i') }}</td>
                    <td>{{ $detail->pembelian->user->name ?? 'N/A' }}</td>
                    <td>{{ $detail->barang->nama_barang ?? 'N/A' }}</td>
                    <td>{{ $detail->barang->unitUsaha->nama_unit_usaha ?? 'N/A' }}</td>
                    <td class="text-center">{{ $detail->jumlah }}</td>
                    <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    <td class="text-center">{{ ucfirst($detail->pembelian->status_pembayaran) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $detail->pembelian->metode_pembayaran)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-section">
        <table>
            <tr>
                <td>Total Omset Penjualan:</td>
                <td class="text-right"><strong>Rp {{ number_format($totalOmset, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Total Item Terjual:</td>
                <td class="text-right"><strong>{{ number_format($totalItemTerjual, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Jumlah Transaksi:</td>
                <td class="text-right"><strong>{{ number_format($jumlahTransaksi, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dokumen ini dicetak oleh sistem.
    </div>
</body>
</html>