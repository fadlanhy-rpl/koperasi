@forelse($pembelians as $pembelian)
    <div class="transaction-row">
        <!-- Desktop Layout -->
        <div class="transaction-desktop transaction-grid">
            <!-- Transaction Code & Date -->
            <div>
                <a href="{{ route('pengurus.transaksi-pembelian.show', $pembelian->id) }}" 
                   class="block group">
                    <div class="transaction-code group-hover:text-blue-800 transition-colors">
                        {{ $pembelian->kode_pembelian }}
                    </div>
                    <div class="transaction-date">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('DD MMM YYYY') }}
                    </div>
                    <div class="transaction-date">
                        {{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('HH:mm') }}
                    </div>
                </a>
            </div>

            <!-- Member Info -->
            <div class="member-info">
                <div class="member-avatar">
                    {{ substr($pembelian->user->name ?? 'N', 0, 1) }}
                </div>
                <div class="member-details">
                    <div class="member-name">{{ $pembelian->user->name ?? 'N/A' }}</div>
                    <div class="member-id">
                        <i class="fas fa-id-card mr-1"></i>
                        {{ $pembelian->user->nomor_anggota ?? '-' }}
                    </div>
                </div>
            </div>

            <!-- Cashier -->
            <div class="text-sm text-gray-600">
                <i class="fas fa-user-tie mr-1 text-green-500"></i>
                {{ $pembelian->kasir->name ?? 'Sistem' }}
            </div>

            <!-- Total Amount -->
            <div class="amount-display">
                <div class="amount-value">
                    @rupiah($pembelian->total_harga)
                </div>
                <div class="amount-method">
                    <i class="fas fa-coins mr-1"></i>
                    {{ ucfirst(str_replace('_', ' ', $pembelian->metode_pembayaran)) }}
                </div>
            </div>

            <!-- Payment Status -->
            <div class="text-center">
                <span class="status-badge-enhanced 
                    @if($pembelian->status_pembayaran == 'lunas') status-lunas
                    @elseif($pembelian->status_pembayaran == 'cicilan') status-cicilan
                    @else status-belum-lunas @endif">
                    @if($pembelian->status_pembayaran == 'lunas')
                        <i class="fas fa-check-circle"></i>
                    @elseif($pembelian->status_pembayaran == 'cicilan')
                        <i class="fas fa-clock"></i>
                    @else
                        <i class="fas fa-exclamation-circle"></i>
                    @endif
                    {{ ucfirst($pembelian->status_pembayaran) }}
                </span>
            </div>

            <!-- Items Count -->
            <div class="items-info">
                <i class="fas fa-shopping-basket mr-1 text-orange-500"></i>
                {{ $pembelian->detailPembelians->count() }} item
                @if($pembelian->catatan)
                    <div class="text-xs text-gray-400 mt-1" title="{{ $pembelian->catatan }}">
                        <i class="fas fa-sticky-note mr-1"></i>
                        Ada catatan
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="text-center">
                <div class="flex justify-center space-x-2">
                    <a href="{{ route('pengurus.transaksi-pembelian.show', $pembelian->id) }}" 
                       class="action-button action-view" 
                       title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if($pembelian->status_pembayaran === 'cicilan')
                        <a href="{{ route('pengurus.pembayaran-cicilan.create', $pembelian->id) }}" 
                           class="action-button bg-gradient-to-r from-green-500 to-emerald-600 text-white" 
                           title="Catat Cicilan">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mobile Layout -->
        <div class="transaction-mobile">
            <div class="mobile-transaction-header">
                <div>
                    <a href="{{ route('pengurus.transaksi-pembelian.show', $pembelian->id) }}" 
                       class="transaction-code hover:text-blue-800 transition-colors">
                        {{ $pembelian->kode_pembelian }}
                    </a>
                    <div class="transaction-date">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('DD MMM YYYY, HH:mm') }}
                    </div>
                </div>
                <div class="text-center">
                    <span class="status-badge-enhanced 
                        @if($pembelian->status_pembayaran == 'lunas') status-lunas
                        @elseif($pembelian->status_pembayaran == 'cicilan') status-cicilan
                        @else status-belum-lunas @endif">
                        @if($pembelian->status_pembayaran == 'lunas')
                            <i class="fas fa-check-circle"></i>
                        @elseif($pembelian->status_pembayaran == 'cicilan')
                            <i class="fas fa-clock"></i>
                        @else
                            <i class="fas fa-exclamation-circle"></i>
                        @endif
                        {{ ucfirst($pembelian->status_pembayaran) }}
                    </span>
                </div>
            </div>

            <div class="mobile-transaction-body">
                <div class="member-info">
                    <div class="member-avatar">
                        {{ substr($pembelian->user->name ?? 'N', 0, 1) }}
                    </div>
                    <div class="member-details">
                        <div class="member-name">{{ $pembelian->user->name ?? 'N/A' }}</div>
                        <div class="member-id">
                            <i class="fas fa-id-card mr-1"></i>
                            {{ $pembelian->user->nomor_anggota ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="amount-display">
                    <div class="amount-value">
                        @rupiah($pembelian->total_harga)
                    </div>
                    <div class="amount-method">
                        <i class="fas fa-coins mr-1"></i>
                        {{ ucfirst(str_replace('_', ' ', $pembelian->metode_pembayaran)) }}
                    </div>
                </div>
            </div>

            <div class="mobile-transaction-footer">
                <div class="items-info">
                    <i class="fas fa-shopping-basket mr-1 text-orange-500"></i>
                    {{ $pembelian->detailPembelians->count() }} item
                    @if($pembelian->catatan)
                        <span class="text-xs text-gray-400 ml-2" title="{{ $pembelian->catatan }}">
                            <i class="fas fa-sticky-note mr-1"></i>
                            Ada catatan
                        </span>
                    @endif
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('pengurus.transaksi-pembelian.show', $pembelian->id) }}" 
                       class="action-button action-view" 
                       title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if($pembelian->status_pembayaran === 'cicilan')
                        <a href="{{ route('pengurus.pembayaran-cicilan.create', $pembelian->id) }}" 
                           class="action-button bg-gradient-to-r from-green-500 to-emerald-600 text-white" 
                           title="Catat Cicilan">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-16">
        <div class="mb-6">
            <i class="fas fa-receipt text-6xl text-gray-300"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada transaksi ditemukan</h3>
        <p class="text-gray-500 mb-6">Belum ada data transaksi pembelian atau sesuaikan filter pencarian</p>
        <a href="{{ route('pengurus.transaksi-pembelian.create') }}" 
           class="inline-flex items-center bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-full font-semibold hover:from-blue-600 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
            <i class="fas fa-plus-circle mr-2"></i>
            Buat Transaksi Pertama
        </a>
    </div>
@endforelse
