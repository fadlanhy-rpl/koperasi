@extends('layouts.app')

@section('title', 'Point of Sale (POS) - Koperasi')

@push('styles')
    <style>
        .pos-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .pos-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .cart-item {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideUp 0.4s ease-out;
        }

        .cart-item:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .payment-method-card {
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .payment-method-card.active {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            transform: scale(1.02);
        }

        .payment-method-card.insufficient-balance {
            border-color: #ef4444;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        }

        .quantity-input {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .quantity-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .total-display {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            border-radius: 20px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }

        .kasir {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }

        .floating-cart-summary {
            position: sticky;
            top: 2rem;
            z-index: 10;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        .error-message {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 12px;
            margin-top: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .success-message {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
            color: #16a34a;
            padding: 12px 16px;
            border-radius: 12px;
            margin-top: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Enhanced Select2 styling */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 48px !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            padding: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 44px !important;
            padding-left: 16px !important;
            color: #374151 !important;
            font-weight: 500 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            right: 12px !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        .select2-dropdown {
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .select2-container--default .select2-results__option {
            padding: 12px 16px !important;
            font-weight: 500 !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6 !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 2px solid #e5e7eb !important;
            border-radius: 8px !important;
            padding: 8px 12px !important;
            margin: 8px !important;
            width: calc(100% - 16px) !important;
        }
    </style>
@endpush

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8 p-8 kasir shadow-lg rounded-lg">
            <h1 class="text-4xl font-bold text-white mb-2">
                <i class="fas fa-cash-register mr-3"></i>
                Point of Sale
            </h1>
            <p class="text-blue-100 text-lg">Sistem kasir modern untuk transaksi yang efisien</p>
        </div>

        <form id="posForm" action="{{ route('pengurus.transaksi-pembelian.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Transaction Details & Items -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Transaction Info Card -->
                    <div class="pos-card p-6">
                        <div class="flex items-center mb-6">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Informasi Transaksi</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                                    Anggota Pembeli <span class="text-red-500">*</span>
                                </label>
                                <select name="user_id" id="user_id" class="select2-enhanced" required>
                                    <option value="">Pilih Anggota</option>
                                    @foreach ($anggota as $agt)
                                        <option value="{{ $agt->id }}" data-nomor="{{ $agt->nomor_anggota ?? '-' }}"
                                            {{ old('user_id') == $agt->id ? 'selected' : '' }}>
                                            {{ $agt->name }} ({{ $agt->nomor_anggota ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="error-message"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="tanggal_pembelian" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt mr-2 text-green-500"></i>
                                    Tanggal Transaksi <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_pembelian" id="tanggal_pembelian"
                                    value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}"
                                    class="enhanced-input w-full" required>
                                @error('tanggal_pembelian')
                                    <p class="error-message"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Product Search & Cart -->
                    <div class="pos-card p-6">
                        <div class="flex items-center mb-6">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-shopping-cart text-white text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Pilih Barang</h3>
                        </div>

                        <div class="mb-6">
                            <label for="barang_search" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-search mr-2 text-purple-500"></i>
                                Cari & Tambah Barang
                            </label>
                            <select id="barang_search" class="select2-enhanced">
                                <option value="">Ketik nama atau kode barang...</option>
                                @foreach ($barangs as $brg)
                                    <option value="{{ $brg->id }}" data-nama="{{ $brg->nama_barang }}"
                                        data-kode="{{ $brg->kode_barang ?? '' }}" data-harga="{{ $brg->harga_jual }}"
                                        data-stok="{{ $brg->stok }}" data-satuan="{{ $brg->satuan }}">
                                        {{ $brg->nama_barang }} (Stok: {{ $brg->stok }}) - @rupiah($brg->harga_jual)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Shopping Cart -->
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xl font-bold text-gray-800">
                                    <i class="fas fa-shopping-basket mr-2 text-orange-500"></i>
                                    Keranjang Belanja
                                </h4>
                                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    <span id="cartItemCount">0</span> Item
                                </div>
                            </div>

                            <div class="space-y-3" id="cartItemsContainer">
                                <div id="cartEmptyState" class="text-center py-12">
                                    <div class="mb-4">
                                        <i class="fas fa-shopping-cart text-6xl text-gray-300"></i>
                                    </div>
                                    <p class="text-gray-500 text-lg font-medium">Keranjang masih kosong</p>
                                    <p class="text-gray-400 text-sm">Pilih barang dari dropdown di atas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Payment Summary -->
                <div class="lg:col-span-1">
                    <div class="floating-cart-summary">
                        <div class="pos-card p-6">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-calculator text-white text-xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800">Pembayaran</h3>
                            </div>

                            <!-- Total Summary -->
                            <div class="total-display mb-6">
                                <div class="text-sm opacity-90 mb-1">Total Belanja</div>
                                <div class="text-3xl font-bold" id="cartTotal">Rp 0</div>
                                <div class="text-sm opacity-75 mt-1">
                                    <span id="cartSubtotal">0</span> item dalam keranjang
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i class="fas fa-credit-card mr-2 text-indigo-500"></i>
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-3">
                                    <div class="payment-method-card" data-method="tunai">
                                        <div class="flex items-center">
                                            <input type="radio" name="metode_pembayaran" value="tunai" id="tunai"
                                                class="mr-3"
                                                {{ old('metode_pembayaran', 'tunai') == 'tunai' ? 'checked' : '' }}>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">
                                                    <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>
                                                    Tunai
                                                </div>
                                                <div class="text-sm text-gray-600">Pembayaran cash</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="payment-method-card" data-method="saldo_sukarela">
                                        <div class="flex items-center">
                                            <input type="radio" name="metode_pembayaran" value="saldo_sukarela"
                                                id="saldo_sukarela" class="mr-3"
                                                {{ old('metode_pembayaran') == 'saldo_sukarela' ? 'checked' : '' }}>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">
                                                    <i class="fas fa-piggy-bank mr-2 text-blue-500"></i>
                                                    Saldo Sukarela
                                                </div>
                                                <div class="text-sm text-gray-600">Potong dari saldo</div>
                                                <div class="text-xs text-gray-500 mt-1" id="saldoSukarelaStatus">
                                                    <i class="fas fa-user-times text-gray-400"></i> Pilih anggota terlebih
                                                    dahulu
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="payment-method-card" data-method="hutang">
                                        <div class="flex items-center">
                                            <input type="radio" name="metode_pembayaran" value="hutang" id="hutang"
                                                class="mr-3"
                                                {{ old('metode_pembayaran') == 'hutang' ? 'checked' : '' }}>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">
                                                    <i class="fas fa-handshake mr-2 text-orange-500"></i>
                                                    Hutang/Cicilan
                                                </div>
                                                <div class="text-sm text-gray-600">Bayar kemudian</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('metode_pembayaran')
                                    <p class="error-message"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Details -->
                            <div id="pembayaranTunaiFields"
                                class="mb-6 {{ old('metode_pembayaran', 'tunai') !== 'tunai' ? 'hidden' : '' }}">
                                <label for="total_bayar_manual" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-coins mr-2 text-yellow-500"></i>
                                    Jumlah Bayar (Tunai) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="total_bayar_manual" id="total_bayar_manual"
                                    class="enhanced-input w-full" placeholder="0"
                                    value="{{ old('total_bayar_manual') }}" min="0" step="0.01">
                                <div class="flex justify-between text-sm mt-2 p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-600">Kembalian:</span>
                                    <span class="font-semibold text-green-600" id="kembalianDisplay">Rp 0</span>
                                </div>
                                @error('total_bayar_manual')
                                    <p class="error-message"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="infoSaldoSukarela"
                                class="mb-6 {{ old('metode_pembayaran') !== 'saldo_sukarela' ? 'hidden' : '' }}">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                        <span class="font-semibold text-blue-800">Info Saldo Sukarela</span>
                                    </div>
                                    <div id="saldoSukarelaInfo">
                                        <div class="text-blue-700 text-sm">
                                            <i class="fas fa-user-times text-blue-400 mr-1"></i>
                                            Pilih anggota untuk melihat saldo sukarela
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="infoHutangFields"
                                class="mb-6 {{ old('metode_pembayaran') !== 'hutang' ? 'hidden' : '' }}">
                                <label for="uang_muka" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-hand-holding-usd mr-2 text-purple-500"></i>
                                    Uang Muka (DP)
                                </label>
                                <input type="number" name="uang_muka" id="uang_muka" class="enhanced-input w-full"
                                    placeholder="0" value="{{ old('uang_muka') }}" min="0" step="0.01">
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada uang muka</p>
                                @error('uang_muka')
                                    <p class="error-message"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="mb-6">
                                <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-sticky-note mr-2 text-pink-500"></i>
                                    Catatan (Opsional)
                                </label>
                                <textarea name="catatan" id="catatan" rows="3" class="enhanced-input w-full resize-none"
                                    placeholder="Catatan tambahan..." maxlength="1000">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <p class="error-message"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-3">
                                <button type="submit" id="submitTransaksiBtn"
                                    class="btn-enhanced w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white py-4 px-6 rounded-2xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Proses Transaksi
                                </button>
                                <button type="button" id="resetTransaksiBtn"
                                    class="btn-enhanced w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white py-3 px-6 rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-undo mr-2"></i>
                                    Reset Transaksi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="items" id="itemsJsonInput">
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2 with enhanced styling
            $('.select2-enhanced').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || $(this).find('option:first').text() ||
                        "Pilih opsi";
                },
                allowClear: true,
                width: '100%',
                dropdownCssClass: 'select2-dropdown-enhanced',
                language: {
                    noResults: function() {
                        return "Tidak ada hasil ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    }
                }
            });

            const cart = [];
            const itemsJsonInput = document.getElementById('itemsJsonInput');
            const cartItemsContainer = document.getElementById('cartItemsContainer');
            const cartEmptyState = document.getElementById('cartEmptyState');
            const cartTotalEl = document.getElementById('cartTotal');
            const cartSubtotalEl = document.getElementById('cartSubtotal');
            const cartItemCountEl = document.getElementById('cartItemCount');
            const barangSearchEl = $('#barang_search');
            const metodePembayaranEls = document.querySelectorAll('input[name="metode_pembayaran"]');
            const pembayaranTunaiFieldsEl = document.getElementById('pembayaranTunaiFields');
            const totalBayarManualEl = document.getElementById('total_bayar_manual');
            const kembalianDisplayEl = document.getElementById('kembalianDisplay');
            const infoSaldoSukarelaEl = document.getElementById('infoSaldoSukarela');
            const infoHutangFieldsEl = document.getElementById('infoHutangFields');
            const resetTransaksiBtn = document.getElementById('resetTransaksiBtn');
            const submitTransaksiBtn = document.getElementById('submitTransaksiBtn');
            const userIdEl = $('#user_id');

            let currentSaldoSukarela = 0;
            let isLoadingSaldo = false;

            function formatRupiah(amount) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(amount);
            }

            function updateCartDisplay() {
                let subtotal = 0;
                let itemCount = 0;

                if (cart.length === 0) {
                    cartEmptyState.classList.remove('hidden');
                    cartItemsContainer.innerHTML = '';
                    cartItemsContainer.appendChild(cartEmptyState);
                } else {
                    cartEmptyState.classList.add('hidden');
                    cartItemsContainer.innerHTML = '';

                    cart.forEach((item, index) => {
                        const itemSubtotal = item.harga * item.jumlah;
                        subtotal += itemSubtotal;
                        itemCount += item.jumlah;

                        const cartItemEl = document.createElement('div');
                        cartItemEl.className = 'cart-item p-4 mb-3';
                        cartItemEl.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-800">${item.nama}</h5>
                            <p class="text-sm text-gray-500">${item.kode || '-'} • ${formatRupiah(item.harga)}/${item.satuan}</p>
                            <p class="text-xs text-gray-400">Stok tersedia: ${item.stok_awal}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-2">
                                <button type="button" class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center hover:bg-red-200 transition-colors" onclick="updateQuantity(${index}, ${item.jumlah - 1})">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <input type="number" value="${item.jumlah}" min="1" max="${item.stok_awal}" 
                                       class="quantity-input w-16 h-8 text-center text-sm" 
                                       onchange="updateQuantity(${index}, this.value)">
                                <button type="button" class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center hover:bg-green-200 transition-colors" onclick="updateQuantity(${index}, ${item.jumlah + 1})">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-gray-800">${formatRupiah(itemSubtotal)}</div>
                                <button type="button" class="text-red-500 hover:text-red-700 text-sm" onclick="removeFromCart(${index})">
                                    <i class="fas fa-trash-alt mr-1"></i>Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                        cartItemsContainer.appendChild(cartItemEl);
                    });
                }

                cartTotalEl.textContent = formatRupiah(subtotal);
                cartSubtotalEl.textContent = itemCount;
                cartItemCountEl.textContent = cart.length;

                // Update items JSON input
                itemsJsonInput.value = JSON.stringify(cart.map(item => ({
                    barang_id: item.id,
                    jumlah: item.jumlah,
                    harga_satuan: item.harga
                })));

                updatePaymentFields();
            }

            window.updateQuantity = function(index, newQuantity) {
                const quantity = parseInt(newQuantity);
                const item = cart[index];

                if (quantity <= 0) {
                    removeFromCart(index);
                    return;
                }

                if (quantity > item.stok_awal) {
                    window.showNotification(
                        `Jumlah melebihi stok tersedia (${item.stok_awal}) untuk ${item.nama}`, 'warning');
                    return;
                }

                item.jumlah = quantity;
                updateCartDisplay();
            };

            window.removeFromCart = function(index) {
                const item = cart[index];
                cart.splice(index, 1);
                window.showNotification(`${item.nama} dihapus dari keranjang`, 'info');
                updateCartDisplay();
            };

            // Add item to cart from search
            barangSearchEl.on('select2:select', function(e) {
                const selectedOption = $(e.params.data.element);
                const barangId = parseInt(selectedOption.val());

                if (!barangId) return;

                const existingItemIndex = cart.findIndex(item => item.id === barangId);

                if (existingItemIndex > -1) {
                    const item = cart[existingItemIndex];
                    if (item.jumlah < item.stok_awal) {
                        item.jumlah++;
                        window.showNotification(`${item.nama} ditambahkan ke keranjang`, 'success');
                    } else {
                        window.showNotification(`Stok ${item.nama} sudah maksimal di keranjang`, 'warning');
                    }
                } else {
                    cart.push({
                        id: barangId,
                        nama: selectedOption.data('nama'),
                        kode: selectedOption.data('kode'),
                        harga: parseFloat(selectedOption.data('harga')),
                        stok_awal: parseInt(selectedOption.data('stok')),
                        satuan: selectedOption.data('satuan'),
                        jumlah: 1
                    });
                    window.showNotification(`${selectedOption.data('nama')} ditambahkan ke keranjang`,
                        'success');
                }

                updateCartDisplay();
                barangSearchEl.val(null).trigger('change');
            });

            async function fetchSaldoSukarela() {
                const selectedUserId = userIdEl.val();
                const saldoStatusEl = document.getElementById('saldoSukarelaStatus');
                const saldoInfoEl = document.getElementById('saldoSukarelaInfo');

                if (!selectedUserId) {
                    saldoStatusEl.innerHTML =
                        '<i class="fas fa-user-times text-gray-400"></i> Pilih anggota terlebih dahulu';
                    saldoInfoEl.innerHTML =
                        '<div class="text-blue-700 text-sm"><i class="fas fa-user-times text-blue-400 mr-1"></i>Pilih anggota untuk melihat saldo sukarela</div>';
                    currentSaldoSukarela = 0;
                    return;
                }

                if (isLoadingSaldo) return;
                isLoadingSaldo = true;

                saldoStatusEl.innerHTML =
                    '<span class="loading-spinner mr-1" style="width: 12px; height: 12px; border-width: 2px; border-color: #3b82f6; border-top-color: transparent;"></span> Memuat saldo...';
                saldoInfoEl.innerHTML =
                    '<div class="flex items-center text-blue-700 text-sm"><span class="loading-spinner mr-2" style="width: 16px; height: 16px; border-width: 2px; border-color: #3b82f6; border-top-color: transparent;"></span>Memuat informasi saldo...</div>';

                try {
                    // Get fresh CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                    'content');

                    if (!csrfToken) {
                        throw new Error('CSRF token tidak ditemukan');
                    }

                    const response = await fetch(`{{ route('pengurus.api.saldo-sukarela') }}`, {
                        method: 'POST', // <-- UBAH INI
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            user_id: selectedUserId
                        }) // <-- TAMBAHKAN INI
                    });

                    if (!response.ok) {
                        if (response.status === 419) {
                            // CSRF token expired, reload page
                            window.showNotification('Sesi telah berakhir. Halaman akan dimuat ulang.',
                                'warning');
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                            return;
                        }
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();

                    if (data.success || data.saldo !== undefined) {
                        currentSaldoSukarela = data.saldo || 0;
                        const saldoFormatted = data.saldo_formatted || formatRupiah(currentSaldoSukarela);
                        saldoStatusEl.innerHTML =
                            `<i class="fas fa-piggy-bank text-blue-500"></i> ${saldoFormatted}`;

                        const totalBelanja = cart.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);
                        const sufficient = currentSaldoSukarela >= totalBelanja;

                        saldoInfoEl.innerHTML = `
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-700">Saldo Tersedia:</span>
                        <span class="font-semibold text-blue-800">${saldoFormatted}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-700">Total Belanja:</span>
                        <span class="font-semibold text-blue-800">${formatRupiah(totalBelanja)}</span>
                    </div>
                    <div class="flex justify-between text-sm border-t pt-2">
                        <span class="text-blue-700">Status:</span>
                        <span class="font-semibold ${sufficient ? 'text-green-600' : 'text-red-600'}">
                            <i class="fas fa-${sufficient ? 'check' : 'times'}-circle mr-1"></i>
                            ${sufficient ? 'Saldo Mencukupi' : 'Saldo Tidak Mencukupi'}
                        </span>
                    </div>
                </div>
            `;

                        const saldoCard = document.querySelector('[data-method="saldo_sukarela"]');
                        if (saldoCard) {
                            saldoCard.classList.remove('insufficient-balance');
                            if (!sufficient && totalBelanja > 0) {
                                saldoCard.classList.add('insufficient-balance');
                            }
                        }
                    } else {
                        throw new Error(data.message || 'Gagal mengambil data saldo');
                    }
                } catch (error) {
                    console.error('Error fetching saldo sukarela:', error);
                    saldoStatusEl.innerHTML =
                        '<i class="fas fa-exclamation-triangle text-red-500"></i> Gagal memuat saldo';
                    saldoInfoEl.innerHTML =
                        '<div class="text-red-600 text-sm"><i class="fas fa-exclamation-circle mr-1"></i>Gagal memuat informasi saldo. Silakan coba lagi.</div>';
                    currentSaldoSukarela = 0;

                    if (error.message.includes('CSRF')) {
                        window.showNotification('Sesi telah berakhir. Silakan refresh halaman.', 'error');
                    } else {
                        window.showNotification('Gagal memuat saldo sukarela. Silakan coba lagi.', 'error');
                    }
                } finally {
                    isLoadingSaldo = false;
                }
            }

            function updatePaymentFields() {
                const selectedMethod = document.querySelector('input[name="metode_pembayaran"]:checked')?.value;
                const totalBelanja = cart.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);

                // Hide all payment fields
                pembayaranTunaiFieldsEl.classList.add('hidden');
                infoSaldoSukarelaEl.classList.add('hidden');
                infoHutangFieldsEl.classList.add('hidden');

                // Update payment method cards
                document.querySelectorAll('.payment-method-card').forEach(card => {
                    card.classList.remove('active', 'insufficient-balance');
                });

                if (selectedMethod) {
                    const selectedCard = document.querySelector(`[data-method="${selectedMethod}"]`);
                    selectedCard.classList.add('active');

                    if (selectedMethod === 'tunai') {
                        pembayaranTunaiFieldsEl.classList.remove('hidden');
                        const bayar = parseFloat(totalBayarManualEl.value) || 0;
                        const kembali = bayar - totalBelanja;
                        kembalianDisplayEl.textContent = formatRupiah(kembali < 0 ? 0 : kembali);

                        if (bayar > 0 && bayar < totalBelanja) {
                            totalBayarManualEl.classList.add('border-red-500');
                            totalBayarManualEl.classList.remove('border-green-500');
                        } else if (bayar >= totalBelanja && totalBelanja > 0) {
                            totalBayarManualEl.classList.add('border-green-500');
                            totalBayarManualEl.classList.remove('border-red-500');
                        } else {
                            totalBayarManualEl.classList.remove('border-red-500', 'border-green-500');
                        }
                    } else if (selectedMethod === 'saldo_sukarela') {
                        infoSaldoSukarelaEl.classList.remove('hidden');
                        fetchSaldoSukarela();
                    } else if (selectedMethod === 'hutang') {
                        infoHutangFieldsEl.classList.remove('hidden');
                    }
                }
            }

            // Event listeners
            metodePembayaranEls.forEach(radio => {
                radio.addEventListener('change', updatePaymentFields);
            });

            if (totalBayarManualEl) {
                totalBayarManualEl.addEventListener('input', updatePaymentFields);
            }

            userIdEl.on('change', function() {
                updatePaymentFields();
                currentSaldoSukarela = 0;
            });

            // Payment method card clicks
            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.addEventListener('click', function() {
                    const method = this.dataset.method;
                    const radio = document.getElementById(method);
                    if (radio && !radio.disabled) {
                        radio.checked = true;
                        updatePaymentFields();
                    }
                });
            });

            // Reset transaction
            if (resetTransaksiBtn) {
                resetTransaksiBtn.addEventListener('click', function() {
                    if (confirm('Yakin ingin mereset semua data transaksi?')) {
                        document.getElementById('posForm').reset();
                        userIdEl.val(null).trigger('change');
                        barangSearchEl.val(null).trigger('change');
                        cart.length = 0;
                        currentSaldoSukarela = 0;
                        updateCartDisplay();
                        window.showNotification('Transaksi telah direset', 'info');
                    }
                });
            }

            // Enhanced form validation
            function validateForm() {
                const errors = [];

                if (!userIdEl.val()) {
                    errors.push('Pilih anggota pembeli');
                }

                if (cart.length === 0) {
                    errors.push('Keranjang belanja masih kosong');
                }

                const selectedMethod = document.querySelector('input[name="metode_pembayaran"]:checked')?.value;
                const totalBelanja = cart.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);

                if (!selectedMethod) {
                    errors.push('Pilih metode pembayaran');
                } else {
                    if (selectedMethod === 'tunai') {
                        const bayar = parseFloat(totalBayarManualEl.value) || 0;
                        if (bayar < totalBelanja) {
                            errors.push(
                                `Jumlah pembayaran tunai kurang dari total belanja (${formatRupiah(totalBelanja)})`
                                );
                        }
                    } else if (selectedMethod === 'saldo_sukarela') {
                        if (currentSaldoSukarela < totalBelanja) {
                            errors.push(
                                `Saldo sukarela tidak mencukupi. Tersedia: ${formatRupiah(currentSaldoSukarela)}, Dibutuhkan: ${formatRupiah(totalBelanja)}`
                                );
                        }
                    }
                }

                return errors;
            }

            // Form submission validation
            document.getElementById('posForm').addEventListener('submit', function(e) {
                // Check if CSRF token exists
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                'content');
                const formCsrfToken = document.querySelector('input[name="_token"]')?.value;

                if (!csrfToken || !formCsrfToken) {
                    e.preventDefault();
                    window.showNotification('Sesi telah berakhir. Halaman akan dimuat ulang.', 'warning');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                    return false;
                }

                const errors = validateForm();

                if (errors.length > 0) {
                    e.preventDefault();
                    window.showNotification('Validasi gagal: ' + errors.join(', '), 'error');

                    if (errors[0].includes('anggota')) {
                        userIdEl.select2('open');
                    } else if (errors[0].includes('keranjang')) {
                        barangSearchEl.select2('open');
                    } else if (errors[0].includes('metode pembayaran')) {
                        document.querySelector('input[name="metode_pembayaran"]').focus();
                    } else if (errors[0].includes('tunai')) {
                        totalBayarManualEl.focus();
                    }

                    return false;
                }

                // Show loading state
                const originalText = submitTransaksiBtn.innerHTML;
                submitTransaksiBtn.innerHTML =
                    '<span class="loading-spinner mr-2"></span>Memproses Transaksi...';
                submitTransaksiBtn.disabled = true;

                const formInputs = document.querySelectorAll(
                    '#posForm input, #posForm select, #posForm textarea, #posForm button');
                formInputs.forEach(input => {
                    if (input !== submitTransaksiBtn) {
                        input.disabled = true;
                    }
                });

                // Set timeout to re-enable form if something goes wrong
                setTimeout(() => {
                    submitTransaksiBtn.innerHTML = originalText;
                    submitTransaksiBtn.disabled = false;
                    formInputs.forEach(input => {
                        input.disabled = false;
                    });
                }, 30000);
            });

            // Initialize
            updateCartDisplay();
            updatePaymentFields();
        });
    </script>
@endpush
