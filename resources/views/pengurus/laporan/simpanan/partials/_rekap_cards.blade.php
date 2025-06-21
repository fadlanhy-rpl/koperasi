<div class="stats-card">
    <div class="flex items-center justify-between mb-4">
        <div class="p-3 bg-white/20 rounded-xl">
            <i class="fas fa-money-check-alt text-2xl"></i>
        </div>
        <div class="text-right">
            <div class="text-sm opacity-80">dari total</div>
            <div class="text-lg font-bold text-green-200">
                {{ $rekapitulasi['grand_total_simpanan'] > 0 ? number_format(($rekapitulasi['total_simpanan_pokok'] / $rekapitulasi['grand_total_simpanan']) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>
    <div class="flex-1">
        <p class="text-sm opacity-90 mb-2">Total Simpanan Pokok</p>
        <p class="text-3xl font-bold mb-2">Rp {{ number_format($rekapitulasi['total_simpanan_pokok'], 0, ',', '.') }}</p>
    </div>
    <div class="flex items-center text-sm opacity-80">
        <i class="fas fa-coins mr-2"></i>
        Simpanan dasar anggota
    </div>
</div>

<div class="stats-card success">
    <div class="flex items-center justify-between mb-4">
        <div class="p-3 bg-white/20 rounded-xl">
            <i class="fas fa-calendar-check text-2xl"></i>
        </div>
        <div class="text-right">
            <div class="text-sm opacity-80">dari total</div>
            <div class="text-lg font-bold text-green-200">
                {{ $rekapitulasi['grand_total_simpanan'] > 0 ? number_format(($rekapitulasi['total_simpanan_wajib'] / $rekapitulasi['grand_total_simpanan']) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>
    <div class="flex-1">
        <p class="text-sm opacity-90 mb-2">Total Simpanan Wajib</p>
        <p class="text-3xl font-bold mb-2">Rp {{ number_format($rekapitulasi['total_simpanan_wajib'], 0, ',', '.') }}</p>
    </div>
    <div class="flex items-center text-sm opacity-80">
        <i class="fas fa-chart-line mr-2"></i>
        Pertumbuhan: {{ number_format($statistikTambahan['pertumbuhan_persen'] ?? 0, 1) }}%
    </div>
</div>

<div class="stats-card warning">
    <div class="flex items-center justify-between mb-4">
        <div class="p-3 bg-white/20 rounded-xl">
            <i class="fas fa-hand-holding-heart text-2xl"></i>
        </div>
        <div class="text-right">
            <div class="text-sm opacity-80">dari total</div>
            <div class="text-lg font-bold text-green-200">
                {{ $rekapitulasi['grand_total_simpanan'] > 0 ? number_format(($rekapitulasi['total_simpanan_sukarela_aktif'] / $rekapitulasi['grand_total_simpanan']) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>
    <div class="flex-1">
        <p class="text-sm opacity-90 mb-2">Saldo Simpanan Sukarela</p>
        <p class="text-3xl font-bold mb-2">Rp {{ number_format($rekapitulasi['total_simpanan_sukarela_aktif'], 0, ',', '.') }}</p>
    </div>
    <div class="flex items-center text-sm opacity-80">
        <i class="fas fa-users mr-2"></i>
        {{ $statistikTambahan['anggota_dengan_sukarela'] ?? 0 }} anggota aktif
    </div>
</div>

<div class="stats-card grand-total">
    <div class="flex items-center justify-between mb-6">
        <div class="p-4 bg-white/20 rounded-xl">
            <i class="fas fa-chart-pie text-3xl"></i>
        </div>
        <div class="text-right">
            <div class="text-sm opacity-80">{{ $statistikTambahan['jumlah_anggota_aktif'] ?? 0 }} anggota</div>
            <div class="text-lg font-bold text-green-200">
                Rp {{ number_format($statistikTambahan['rata_simpanan_per_anggota'] ?? 0, 0, ',', '.') }}
            </div>
            <div class="text-xs opacity-70">rata-rata per anggota</div>
        </div>
    </div>
    <div class="flex-1">
        <p class="text-lg opacity-90 mb-3">Grand Total Semua Simpanan</p>
        <p class="text-4xl font-bold mb-4">Rp {{ number_format($rekapitulasi['grand_total_simpanan'], 0, ',', '.') }}</p>
        <div class="bg-white/20 rounded-full h-3 overflow-hidden">
            <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: 100%"></div>
        </div>
    </div>
    <div class="flex items-center justify-between text-sm opacity-80 mt-4">
        <div class="flex items-center">
            <i class="fas fa-trending-up mr-2"></i>
            Total aset simpanan
        </div>
        <div class="text-right">
            <i class="fas fa-shield-alt mr-2"></i>
            Terjamin
        </div>
    </div>
</div>
