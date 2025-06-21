<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Simpanan Wajib Belum Bayar</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 16px; }
        .header p { margin: 3px 0; font-size: 10px; color: #555; }
        .period-info { background-color: #f8f9fa; padding: 10px; margin-bottom: 15px; border-left: 4px solid #dc3545; }
        .stats-section { margin-bottom: 20px; }
        .stats-table { width: 100%; }
        .stats-table td { border: 1px solid #ddd; padding: 6px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 9px;}
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: -30px; left: 0; right: 0; text-align: center; font-size: 8px; color: #777;}
        .alert-success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 10px; margin-bottom: 15px; }
        .alert-warning { background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 10px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Simpanan Wajib Belum Bayar</h1>
        <p>{{ config('app.name', 'Koperasi Management System') }}</p>
        <p>Dicetak pada: {{ $generated_at->isoFormat('DD MMMM YYYY, HH:mm:ss') }}</p>
    </div>

    <div class="period-info">
        <h3>Periode Laporan</h3>
        <p><strong>Bulan:</strong> {{ \Carbon\Carbon::create()->month($periode['bulan'])->translatedFormat('F') }} {{ $periode['tahun'] }}</p>
    </div>

    <div class="stats-section">
        <h3>Statistik Pembayaran</h3>
        <table class="stats-table">
            <tr>
                <td><strong>Total Anggota</strong></td>
                <td class="text-right">{{ number_format($statistik['total_anggota'], 0, ',', '.') }} orang</td>
            </tr>
            <tr>
                <td><strong>Sudah Bayar</strong></td>
                <td class="text-right">{{ number_format($statistik['sudah_bayar'], 0, ',', '.') }} orang</td>
            </tr>
            <tr>
                <td><strong>Belum Bayar</strong></td>
                <td class="text-right">{{ number_format($statistik['belum_bayar'], 0, ',', '.') }} orang</td>
            </tr>
            <tr>
                <td><strong>Tingkat Kepatuhan</strong></td>
                <td class="text-right">{{ number_format($statistik['persentase_bayar'], 1, ',', '.') }}%</td>
            </tr>
        </table>
    </div>

    @if($anggota_belum_bayar_wajib->count() == 0)
        <div class="alert-success">
            <h4>Excellent! Semua Anggota Sudah Bayar</h4>
            <p>Semua anggota telah melunasi simpanan wajib untuk periode {{ \Carbon\Carbon::create()->month($periode['bulan'])->translatedFormat('F') }} {{ $periode['tahun'] }}.</p>
        </div>
    @else
        <div class="alert-warning">
            <h4>Perhatian: {{ $anggota_belum_bayar_wajib->count() }} Anggota Belum Bayar</h4>
            <p>Terdapat {{ $anggota_belum_bayar_wajib->count() }} anggota yang belum melunasi simpanan wajib untuk periode {{ \Carbon\Carbon::create()->month($periode['bulan'])->translatedFormat('F') }} {{ $periode['tahun'] }}.</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th>Nama Anggota</th>
                    <th>No. Anggota</th>
                    <th>Email</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($anggota_belum_bayar_wajib as $index => $anggota)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $anggota->name }}</td>
                        <td>{{ $anggota->nomor_anggota ?? '-' }}</td>
                        <td>{{ $anggota->email }}</td>
                        <td class="text-center" style="color: #dc3545; font-weight: bold;">BELUM BAYAR</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div style="margin-top: 20px; font-size: 8px; color: #666;">
        <p><strong>Catatan:</strong></p>
        <ul>
            <li>Laporan ini menampilkan anggota yang belum melunasi simpanan wajib untuk periode yang dipilih.</li>
            <li>Segera lakukan tindak lanjut untuk anggota yang belum melunasi pembayaran.</li>
            <li>Data ini dihasilkan secara otomatis oleh sistem pada {{ $generated_at->isoFormat('DD MMMM YYYY, HH:mm:ss') }}.</li>
        </ul>
    </div>

    <div class="footer">
        Dokumen ini dicetak oleh sistem - {{ config('app.name', 'Koperasi Management System') }}
    </div>
</body>
</html>
