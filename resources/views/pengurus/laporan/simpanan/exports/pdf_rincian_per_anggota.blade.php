<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rincian Simpanan per Anggota</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 14px; }
        .header p { margin: 3px 0; font-size: 9px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 8px;}
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 8px; color: #777;}
        .total-row { background-color: #e9ecef; font-weight: bold; }
        .summary-section { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rincian Simpanan per Anggota</h1>
        <p>{{ config('app.name', 'Koperasi Management System') }}</p>
        <p>Dicetak pada: {{ $generated_at->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</p>
    </div>

    <div class="summary-section">
        <h3>Ringkasan</h3>
        <table style="width: 60%;">
            <tr>
                <td>Jumlah Anggota</td>
                <td class="text-right">{{ number_format($ringkasan['jumlah_anggota'], 0, ',', '.') }} orang</td>
            </tr>
            <tr>
                <td>Total Simpanan Pokok</td>
                <td class="text-right">{{ number_format($ringkasan['total_pokok'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Simpanan Wajib</td>
                <td class="text-right">{{ number_format($ringkasan['total_wajib'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Saldo Sukarela</td>
                <td class="text-right">{{ number_format($ringkasan['total_sukarela'], 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>Grand Total</strong></td>
                <td class="text-right"><strong>{{ number_format($ringkasan['grand_total'], 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th>Nama Anggota</th>
                <th>No. Anggota</th>
                <th class="text-right">Simp. Pokok</th>
                <th class="text-right">Simp. Wajib</th>
                <th class="text-right">Saldo Sukarela</th>
                <th class="text-right">Total Semua</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan_per_anggota as $index => $anggota)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $anggota->name }}</td>
                    <td>{{ $anggota->nomor_anggota ?? '-' }}</td>
                    <td class="text-right">{{ number_format($anggota->total_simpanan_pokok_view, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($anggota->total_simpanan_wajib_view, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($anggota->saldo_simpanan_sukarela_view, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($anggota->total_simpanan_pokok_view + $anggota->total_simpanan_wajib_view + $anggota->saldo_simpanan_sukarela_view, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data anggota.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dicetak oleh sistem - {{ config('app.name', 'Koperasi Management System') }}
    </div>
</body>
</html>
