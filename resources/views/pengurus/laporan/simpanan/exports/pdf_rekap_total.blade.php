<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Total Simpanan</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header p { margin: 3px 0; font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 9px;}
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 8px; color: #777;}
        .total-row { background-color: #e9ecef; font-weight: bold; }
        .stats-section { margin-bottom: 20px; }
        .stats-table { width: 100%; }
        .stats-table td { border: 1px solid #ddd; padding: 6px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rekapitulasi Total Simpanan</h1>
        <p>{{ config('app.name', 'Koperasi Management System') }}</p>
        <p>Dicetak pada: {{ $generated_at->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</p>
    </div>

    <div class="stats-section">
        <h3>Ringkasan Simpanan</h3>
        <table class="stats-table">
            <tr>
                <td><strong>Jenis Simpanan</strong></td>
                <td class="text-right"><strong>Total (Rupiah)</strong></td>
                <td class="text-center"><strong>Persentase</strong></td>
            </tr>
            <tr>
                <td>Total Simpanan Pokok Terkumpul</td>
                <td class="text-right">{{ number_format($rekapitulasi['total_simpanan_pokok'], 0, ',', '.') }}</td>
                <td class="text-center">{{ $rekapitulasi['grand_total_simpanan'] > 0 ? number_format(($rekapitulasi['total_simpanan_pokok'] / $rekapitulasi['grand_total_simpanan']) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>Total Simpanan Wajib Terkumpul</td>
                <td class="text-right">{{ number_format($rekapitulasi['total_simpanan_wajib'], 0, ',', '.') }}</td>
                <td class="text-center">{{ $rekapitulasi['grand_total_simpanan'] > 0 ? number_format(($rekapitulasi['total_simpanan_wajib'] / $rekapitulasi['grand_total_simpanan']) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td>Total Saldo Aktif Simpanan Sukarela</td>
                <td class="text-right">{{ number_format($rekapitulasi['total_simpanan_sukarela_aktif'], 0, ',', '.') }}</td>
                <td class="text-center">{{ $rekapitulasi['grand_total_simpanan'] > 0 ? number_format(($rekapitulasi['total_simpanan_sukarela_aktif'] / $rekapitulasi['grand_total_simpanan']) * 100, 1) : 0 }}%</td>
            </tr>
            <tr class="total-row">
                <td><strong>GRAND TOTAL SEMUA SIMPANAN</strong></td>
                <td class="text-right"><strong>{{ number_format($rekapitulasi['grand_total_simpanan'], 0, ',', '.') }}</strong></td>
                <td class="text-center"><strong>100%</strong></td>
            </tr>
        </table>
    </div>

    <div class="stats-section">
        <h3>Statistik Tambahan</h3>
        <table class="stats-table">
            <tr>
                <td>Jumlah Anggota Aktif</td>
                <td class="text-right">{{ number_format($statistik_tambahan['jumlah_anggota_aktif'], 0, ',', '.') }} orang</td>
            </tr>
            <tr>
                <td>Anggota dengan Simpanan Sukarela</td>
                <td class="text-right">{{ number_format($statistik_tambahan['anggota_dengan_sukarela'], 0, ',', '.') }} orang</td>
            </tr>
            <tr>
                <td>Rata-rata Simpanan per Anggota</td>
                <td class="text-right">{{ number_format($statistik_tambahan['rata_simpanan_per_anggota'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pertumbuhan Simpanan Wajib (Bulan ini vs Bulan lalu)</td>
                <td class="text-right">{{ number_format($statistik_tambahan['pertumbuhan_persen'], 2, ',', '.') }}%</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 20px; font-size: 8px; color: #666;">
        <p><strong>Catatan:</strong></p>
        <ul>
            <li>Total saldo aktif simpanan sukarela dihitung berdasarkan saldo akhir dari setiap anggota yang memiliki transaksi simpanan sukarela.</li>
            <li>Pertumbuhan dihitung berdasarkan perbandingan total simpanan wajib bulan ini dengan bulan sebelumnya.</li>
            <li>Data ini dihasilkan secara otomatis oleh sistem pada {{ $generated_at->isoFormat('DD MMMM YYYY, HH:mm:ss') }}.</li>
        </ul>
    </div>

    <div class="footer">
        Dokumen ini dicetak oleh sistem - {{ config('app.name', 'Koperasi Management System') }}
    </div>
</body>
</html>
