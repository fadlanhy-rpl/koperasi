{{-- resources/views/pengurus/simpanan/partials/_riwayat_wajib_table.blade.php --}}
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="w-full min-w-[700px]">
        <thead>
            <tr class="border-b-2 border-gray-200 bg-gray-50">
                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Periode (Bulan/Tahun)</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Tanggal Bayar</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Jumlah</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Keterangan</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Dicatat Oleh</th>
            </tr>
        </thead>
        <tbody id="riwayatWajibTableBody"> {{-- ID untuk update AJAX --}}
            @forelse($riwayat_wajib as $wajib)
                <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="py-3 px-4 text-gray-700">{{ \Carbon\Carbon::create()->month($wajib->bulan)->translatedFormat('F') }} {{ $wajib->tahun }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ \Carbon\Carbon::parse($wajib->tanggal_bayar)->format('d M Y') }}</td>
                    <td class="py-3 px-4 text-gray-800 font-semibold text-right">@rupiah($wajib->jumlah)</td>
                    <td class="py-3 px-4 text-gray-600">{{ $wajib->keterangan ?: '-' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $wajib->pengurus->name ?? 'Sistem' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-400 italic">
                        Belum ada data simpanan wajib untuk anggota ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>