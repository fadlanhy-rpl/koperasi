{{-- resources/views/pengurus/simpanan/partials/_riwayat_sukarela_table.blade.php --}}
<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="w-full min-w-[700px]">
        <thead>
            <tr class="border-b-2 border-gray-200 bg-gray-50">
                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Tanggal Transaksi</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Tipe</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Jumlah</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Saldo Sebelum</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Saldo Sesudah</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Keterangan</th>
                <th class="text-left py-3 px-4 font-semibold text-gray-600 uppercase text-sm">Dicatat Oleh</th>
            </tr>
        </thead>
        <tbody id="riwayatSukarelaTableBody"> {{-- ID untuk update AJAX --}}
            @forelse($riwayat_sukarela as $sukarela)
                <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="py-3 px-4 text-gray-700">{{ \Carbon\Carbon::parse($sukarela->tanggal_transaksi)->format('d M Y') }}</td>
                    <td class="py-3 px-4">
                        @if($sukarela->tipe_transaksi == 'setor')
                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Setor</span>
                        @else
                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tarik</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 font-semibold text-right {{ $sukarela->tipe_transaksi == 'setor' ? 'text-green-600' : 'text-red-600' }}">
                        @rupiah($sukarela->jumlah)
                    </td>
                    <td class="py-3 px-4 text-gray-600 text-right">@rupiah($sukarela->saldo_sebelum)</td>
                    <td class="py-3 px-4 text-gray-800 font-semibold text-right">@rupiah($sukarela->saldo_sesudah)</td>
                    <td class="py-3 px-4 text-gray-600">{{ $sukarela->keterangan ?: '-' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $sukarela->pengurus->name ?? 'Sistem' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-400 italic">
                        Belum ada data simpanan sukarela untuk anggota ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>