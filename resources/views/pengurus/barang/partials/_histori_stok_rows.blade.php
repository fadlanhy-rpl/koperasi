@forelse($historiStoks as $histori)
    <tr class="border-b border-gray-100 hover:bg-gray-50/80 transition-all duration-300">
        <td>
            <div class="flex items-center">
                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-calendar-day text-blue-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $histori->created_at->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $histori->created_at->format('H:i') }}</p>
                </div>
            </div>
        </td>
        
        <td>
            @if($histori->tipe == 'masuk')
                <span class="history-type in">
                    <i class="fas fa-arrow-up mr-1"></i>Masuk
                </span>
            @elseif($histori->tipe == 'keluar')
                <span class="history-type out">
                    <i class="fas fa-arrow-down mr-1"></i>Keluar
                </span>
            @else
                <span class="history-type adjust">
                    <i class="fas fa-exchange-alt mr-1"></i>Penyesuaian
                </span>
            @endif
        </td>
        
        <td class="text-center">
            <span class="text-lg font-bold {{ $histori->jumlah >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $histori->jumlah >= 0 ? '+' : '' }}{{ $histori->jumlah }}
            </span>
        </td>
        
        <td class="text-center">
            <div class="bg-gray-100 rounded-lg py-1 px-3 inline-block">
                <span class="font-medium text-gray-700">{{ $histori->stok_sebelum }}</span>
            </div>
        </td>
        
        <td class="text-center">
            <div class="bg-blue-100 rounded-lg py-1 px-3 inline-block">
                <span class="font-bold text-blue-700">{{ $histori->stok_sesudah }}</span>
            </div>
        </td>
        
        <td>
            @if($histori->keterangan)
                <p class="text-sm text-gray-700">{{ $histori->keterangan }}</p>
            @else
                <p class="text-xs text-gray-400 italic">Tidak ada keterangan</p>
            @endif
        </td>
        
        <td>
            <div class="flex items-center">
                <div class="bg-gray-100 p-1 rounded-full mr-2">
                    <i class="fas fa-user text-gray-500"></i>
                </div>
                <span class="text-sm text-gray-700">{{ $histori->user->name ?? 'Sistem' }}</span>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-12">
            <div class="flex flex-col items-center">
                <div class="bg-gray-100 p-6 rounded-full mb-4">
                    <i class="fas fa-history text-5xl text-gray-300"></i>
                </div>
                <p class="text-lg font-medium text-gray-600">Belum ada histori stok</p>
                <p class="text-sm text-gray-500 mt-1">Riwayat perubahan stok akan muncul di sini</p>
            </div>
        </td>
    </tr>
@endforelse