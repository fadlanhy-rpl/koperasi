{{-- resources/views/anggota/simpanan/partials/_riwayat_sukarela_table.blade.php --}}
<div class="overflow-x-auto">
    <table class="w-full min-w-[700px] text-sm">
        <thead>
            <tr class="border-b-2 border-gray-200 bg-gray-50/50 text-xs uppercase text-gray-500">
                <th class="py-2.5 px-3 text-left">Tanggal Transaksi</th>
                <th class="py-2.5 px-3 text-left">Tipe</th>
                <th class="py-2.5 px-3 text-right">Jumlah</th>
                <th class="py-2.5 px-3 text-right">Saldo Sebelum</th>
                <th class="py-2.5 px-3 text-right">Saldo Sesudah</th>
                <th class="py-2.5 px-3 text-left">Keterangan</th>
                {{-- Kolom 'Dicatat Oleh' mungkin tidak terlalu relevan untuk anggota, bisa dihilangkan --}}
                {{-- <th class="py-2.5 px-3 text-left">Dicatat Oleh</th> --}}
            </tr>
        </thead>
        <tbody id="anggotaRiwayatSukarelaTableBody"> {{-- ID untuk update AJAX --}}
            @forelse($riwayat_sukarela as $sukarela)
                <tr class="border-b border-gray-100 hover:bg-gray-50/30 transition-colors">
                    <td class="py-2.5 px-3 text-gray-700">{{ \Carbon\Carbon::parse($sukarela->tanggal_transaksi)->isoFormat('DD MMMM YYYY') }}</td>
                    <td class="py-2.5 px-3">
                        @if($sukarela->tipe_transaksi == 'setor')
                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-700">Setor</span>
                        @else
                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-700">Tarik</span>
                        @endif
                    </td>
                    <td class="py-2.5 px-3 font-semibold text-right {{ $sukarela->tipe_transaksi == 'setor' ? 'text-green-600' : 'text-red-600' }}">
                        @rupiah($sukarela->jumlah)
                    </td>
                    <td class="py-2.5 px-3 text-gray-600 text-right">@rupiah($sukarela->saldo_sebelum)</td>
                    <td class="py-2.5 px-3 text-gray-800 font-semibold text-right">@rupiah($sukarela->saldo_sesudah)</td>
                    <td class="py-2.5 px-3 text-gray-600">{{ $sukarela->keterangan ?: '-' }}</td>
                    {{-- <td class="py-2.5 px-3 text-gray-500">{{ $sukarela->pengurus->name ?? 'Sistem' }}</td> --}}
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-400 italic"> {{-- Sesuaikan colspan jika kolom berubah --}}
                        Belum ada riwayat simpanan sukarela.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>