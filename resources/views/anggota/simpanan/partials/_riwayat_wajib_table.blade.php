{{-- resources/views/anggota/simpanan/partials/_riwayat_wajib_table.blade.php --}}
<div class="overflow-x-auto">
    <table class="w-full min-w-[600px] text-sm">
        <thead>
            <tr class="border-b-2 border-gray-200 bg-gray-50/50 text-xs uppercase text-gray-500">
                <th class="py-2.5 px-3 text-left">Periode (Bulan/Tahun)</th>
                <th class="py-2.5 px-3 text-left">Tanggal Bayar</th>
                <th class="py-2.5 px-3 text-right">Jumlah</th>
                <th class="py-2.5 px-3 text-left">Keterangan</th>
            </tr>
        </thead>
        <tbody id="anggotaRiwayatWajibTableBody">
            @forelse($riwayat_wajib as $wajib)
                <tr class="border-b border-gray-100 hover:bg-gray-50/30">
                    <td class="py-2.5 px-3 text-gray-700">{{ \Carbon\Carbon::create()->month($wajib->bulan)->translatedFormat('F') }} {{ $wajib->tahun }}</td>
                    <td class="py-2.5 px-3 text-gray-700">{{ \Carbon\Carbon::parse($wajib->tanggal_bayar)->isoFormat('DD MMMM YYYY') }}</td>
                    <td class="py-2.5 px-3 font-semibold text-gray-800 text-right">@rupiah($wajib->jumlah)</td>
                    <td class="py-2.5 px-3 text-gray-600">{{ $wajib->keterangan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-8 text-gray-400 italic">
                        Belum ada riwayat simpanan wajib.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>