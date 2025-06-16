{{-- resources/views/pengurus/simpanan/partials/_riwayat_pokok_table.blade.php --}}
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="w-full min-w-[600px]">
        <thead>
            <tr class="border-b-2 border-gray-200 bg-gray-50">
                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Tanggal Bayar</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Jumlah</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Keterangan</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Dicatat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat_pokok as $pokok)
                <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="py-3 px-4 text-gray-700">{{ \Carbon\Carbon::parse($pokok->tanggal_bayar)->format('d M Y') }}</td>
                    <td class="py-3 px-4 text-gray-800 font-semibold text-right">@rupiah($pokok->jumlah)</td>
                    <td class="py-3 px-4 text-gray-600">{{ $pokok->keterangan ?: '-' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $pokok->pengurus->name ?? 'Sistem' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-8 text-gray-400 italic">
                        Belum ada data simpanan pokok.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>