@forelse($anggota_belum_bayar_wajib as $index => $anggota)
    <tr class="table-row border-b border-gray-100">
        <td class="py-4 px-4 text-gray-700 font-medium">
            {{ $anggota_belum_bayar_wajib->firstItem() + $index }}
        </td>
        <td class="py-4 px-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                    {{ strtoupper(substr($anggota->name, 0, 2)) }}
                </div>
                <div>
                    <div class="font-semibold text-gray-800">{{ $anggota->name }}</div>
                    <div class="text-xs text-red-500 font-medium">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Belum bayar simpanan wajib
                    </div>
                </div>
            </div>
        </td>
        <td class="py-4 px-4 text-gray-600 font-mono">
            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">
                {{ $anggota->nomor_anggota ?? '-' }}
            </span>
        </td>
        <td class="py-4 px-4 text-gray-600">
            <div class="flex items-center">
                <i class="fas fa-envelope text-gray-400 mr-2"></i>
                {{ $anggota->email }}
            </div>
        </td>
        <td class="py-4 px-4 text-center">
            <div class="flex items-center justify-center space-x-2">
                <a href="{{ route('pengurus.simpanan.wajib.index', ['search_anggota' => $anggota->nomor_anggota, 'bulan' => $periode['bulan'], 'tahun' => $periode['tahun']]) }}#form-tambah-wajib" 
                   class="btn-success" 
                   title="Catat Pembayaran untuk {{ $anggota->name }}">
                    <i class="fas fa-plus-circle"></i>
                    Catat Bayar
                </a>
                <button type="button" 
                        onclick="sendReminder({{ $anggota->id }}, '{{ $anggota->name }}')"
                        class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 p-2 rounded-lg transition-all duration-300 hover:scale-110" 
                        title="Kirim Pengingat ke {{ $anggota->name }}">
                    <i class="fas fa-bell"></i>
                </button>
                <button type="button" 
                        onclick="showAnggotaDetail({{ $anggota->id }})"
                        class="bg-blue-100 hover:bg-blue-200 text-blue-600 p-2 rounded-lg transition-all duration-300 hover:scale-110" 
                        title="Detail Anggota {{ $anggota->name }}">
                    <i class="fas fa-info-circle"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-16 text-gray-500">
            <div class="flex flex-col items-center">
                <i class="fas fa-check-circle text-6xl mb-4 text-green-400"></i>
                <h3 class="text-xl font-semibold mb-2 text-green-600">Excellent! Semua Anggota Sudah Bayar</h3>
                <p class="text-gray-400 mb-4">
                    Semua anggota telah melunasi simpanan wajib untuk periode 
                    <span class="font-semibold">{{ \Carbon\Carbon::create()->month($periode['bulan'])->translatedFormat('F') }} {{ $periode['tahun'] }}</span>
                </p>
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg">
                    <i class="fas fa-trophy mr-2"></i>
                    Tingkat kepatuhan: 100%
                </div>
            </div>
        </td>
    </tr>
@endforelse

<script>
function sendReminder(anggotaId, anggotaName) {
    // TODO: Implement reminder functionality
    if (confirm(`Kirim pengingat pembayaran simpanan wajib kepada ${anggotaName}?`)) {
        // Here you would typically make an AJAX call to send reminder
        console.log('Sending reminder to member ID:', anggotaId);
        
        // Show success notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg bg-green-500 text-white transition-all duration-300 transform translate-x-full';
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>Pengingat berhasil dikirim ke ${anggotaName}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

function showAnggotaDetail(anggotaId) {
    // TODO: Implement modal or popup for member details
    console.log('Show detail for member ID:', anggotaId);
    // This could open a modal with detailed member information
}
</script>
