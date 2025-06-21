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

    .kasir{
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    }

    .floating-cart-summary {
        position: sticky;
        top: 2rem;
        z-index: 10;
    }

    .product-search-dropdown {
        max-height: 300px;
        overflow-y: auto;
        border-radius: 16px;
        border: 2px solid #e5e7eb;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
    }

    .search-result-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .search-result-item:hover {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        transform: translateX(4px);
    }

    .empty-cart-illustration {
        opacity: 0.6;
        filter: grayscale(0.3);
    }

    .pulse-ring {
        animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }

    @keyframes pulse-ring {
        0% {
            transform: scale(0.33);
        }
        40%, 50% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            transform: scale(1.2);
        }
    }
</style>
@endpush

@section('content')
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
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
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
                                <select name="user_id" id="user_id" class="w-full select2-basic enhanced-input" required data-placeholder="Pilih Anggota">
                                    <option value=""></option>
                                    @foreach($anggota as $agt)
                                        <option value="{{ $agt->id }}" data-nomor="{{ $agt->nomor_anggota ?? '-' }}" {{ old('user_id') == $agt->id ? 'selected' : '' }}>
                                            {{ $agt->name }} ({{ $agt->nomor_anggota ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="tanggal_pembelian" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt mr-2 text-green-500"></i>
                                    Tanggal Transaksi <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_pembelian" id="tanggal_pembelian" 
                                       value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" 
                                       class="enhanced-input w-full" required>
                                @error('tanggal_pembelian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Product Search & Cart -->
                    <div class="pos-card p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-600 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-shopping-cart text-white text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Pilih Barang</h3>
                        </div>

                        <div class="mb-6">
                            <label for="barang_search" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-search mr-2 text-purple-500"></i>
                                Cari & Tambah Barang
                            </label>
                            <select id="barang_search" class="w-full select2-basic enhanced-input" data-placeholder="Ketik nama atau kode barang...">
                                <option value=""></option>
                                @foreach($barangs as $brg)
                                    <option value="{{ $brg->id }}" 
                                            data-nama="{{ $brg->nama_barang }}" 
                                            data-kode="{{ $brg->kode_barang ?? '' }}" 
                                            data-harga="{{ $brg->harga_jual }}" 
                                            data-stok="{{ $brg->stok }}" 
                                            data-satuan="{{ $brg->satuan }}">
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
                                    <div class="empty-cart-illustration mb-4">
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
                                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-600 rounded-full flex items-center justify-center mr-4">
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
                                                   class="mr-3" {{ old('metode_pembayaran', 'tunai') == 'tunai' ? 'checked' : '' }}>
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
                                            <input type="radio" name="metode_pembayaran" value="saldo_sukarela" id="saldo_sukarela" 
                                                   class="mr-3" {{ old('metode_pembayaran') == 'saldo_sukarela' ? 'checked' : '' }}>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">
                                                    <i class="fas fa-piggy-bank mr-2 text-blue-500"></i>
                                                    Saldo Sukarela
                                                </div>
                                                <div class="text-sm text-gray-600">Potong dari saldo</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="payment-method-card" data-method="hutang">
                                        <div class="flex items-center">
                                            <input type="radio" name="metode_pembayaran" value="hutang" id="hutang" 
                                                   class="mr-3" {{ old('metode_pembayaran') == 'hutang' ? 'checked' : '' }}>
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
                                @error('metode_pembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Payment Details -->
                            <div id="pembayaranTunaiFields" class="mb-6 {{ old('metode_pembayaran', 'tunai') !== 'tunai' ? 'hidden' : '' }}">
                                <label for="total_bayar_manual" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-coins mr-2 text-yellow-500"></i>
                                    Jumlah Bayar (Tunai)
                                </label>
                                <input type="number" name="total_bayar_manual" id="total_bayar_manual" 
                                       class="enhanced-input w-full" placeholder="0" 
                                       value="{{ old('total_bayar_manual') }}" min="0" step="any">
                                <div class="flex justify-between text-sm mt-2 p-3 bg-gray-50 rounded-lg">
                                    <span class="text-gray-600">Kembalian:</span>
                                    <span class="font-semibold text-green-600" id="kembalianDisplay">Rp 0</span>
                                </div>
                                @error('total_bayar_manual') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div id="infoSaldoSukarela" class="mb-6 {{ old('metode_pembayaran') !== 'saldo_sukarela' ? 'hidden' : '' }}">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                        <span class="font-semibold text-blue-800">Info Saldo</span>
                                    </div>
                                    <p class="text-blue-700 text-sm">Saldo sukarela anggota akan dicek saat proses simpan.</p>
                                    <p class="text-blue-600 font-semibold mt-1">
                                        Saldo Terkini: <span id="displaySaldoSukarelaAnggota">-</span>
                                    </p>
                                </div>
                            </div>
                            
                            <div id="infoHutangFields" class="mb-6 {{ old('metode_pembayaran') !== 'hutang' ? 'hidden' : '' }}">
                                <label for="uang_muka" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-hand-holding-usd mr-2 text-purple-500"></i>
                                    Uang Muka (DP)
                                </label>
                                <input type="number" name="uang_muka" id="uang_muka" 
                                       class="enhanced-input w-full" placeholder="0" 
                                       value="{{ old('uang_muka') }}" min="0" step="any">
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada uang muka</p>
                                @error('uang_muka') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Notes -->
                            <div class="mb-6">
                                <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-sticky-note mr-2 text-pink-500"></i>
                                    Catatan (Opsional)
                                </label>
                                <textarea name="catatan" id="catatan" rows="3" 
                                          class="enhanced-input w-full resize-none" 
                                          placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                                @error('catatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-3">
                                <button type="submit" class="btn-enhanced w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white py-4 px-6 rounded-2xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Proses Transaksi
                                </button>
                                <button type="button" id="resetTransaksiBtn" class="btn-enhanced w-full bg-gradient-to-r from-gray-400 to-gray-500 text-white py-3 px-6 rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300">
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

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2-basic').select2({
        placeholder: function() {
            return $(this).data('placeholder') || "Pilih opsi";
        },
        width: '100%',
        dropdownCssClass: 'select2-dropdown-enhanced'
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
    const displaySaldoSukarelaAnggotaEl = document.getElementById('displaySaldoSukarelaAnggota');
    const infoHutangFieldsEl = document.getElementById('infoHutangFields');
    const resetTransaksiBtn = document.getElementById('resetTransaksiBtn');
    const userIdEl = $('#user_id');

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
            showNotification(`Jumlah melebihi stok tersedia (${item.stok_awal}) untuk ${item.nama}`, 'warning');
            return;
        }
        
        item.jumlah = quantity;
        updateCartDisplay();
    };

    window.removeFromCart = function(index) {
        const item = cart[index];
        cart.splice(index, 1);
        showNotification(`${item.nama} dihapus dari keranjang`, 'info');
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
                showNotification(`${item.nama} ditambahkan ke keranjang`, 'success');
            } else {
                showNotification(`Stok ${item.nama} sudah maksimal di keranjang`, 'warning');
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
            showNotification(`${selectedOption.data('nama')} ditambahkan ke keranjang`, 'success');
        }
        
        updateCartDisplay();
        barangSearchEl.val(null).trigger('change');
    });

    function updatePaymentFields() {
        const selectedMethod = document.querySelector('input[name="metode_pembayaran"]:checked')?.value;
        const totalBelanja = cart.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);

        // Hide all payment fields
        pembayaranTunaiFieldsEl.classList.add('hidden');
        infoSaldoSukarelaEl.classList.add('hidden');
        infoHutangFieldsEl.classList.add('hidden');

        // Update payment method cards
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.remove('active');
        });

        if (selectedMethod) {
            document.querySelector(`[data-method="${selectedMethod}"]`).classList.add('active');

            if (selectedMethod === 'tunai') {
                pembayaranTunaiFieldsEl.classList.remove('hidden');
                const bayar = parseFloat(totalBayarManualEl.value) || 0;
                const kembali = bayar - totalBelanja;
                kembalianDisplayEl.textContent = formatRupiah(kembali < 0 ? 0 : kembali);
                
                if (bayar > 0 && bayar < totalBelanja) {
                    totalBayarManualEl.classList.add('border-red-500');
                } else {
                    totalBayarManualEl.classList.remove('border-red-500');
                }
            } else if (selectedMethod === 'saldo_sukarela') {
                infoSaldoSukarelaEl.classList.remove('hidden');
                fetchSaldoSukarelaAnggota();
            } else if (selectedMethod === 'hutang') {
                infoHutangFieldsEl.classList.remove('hidden');
            }
        }
    }

    async function fetchSaldoSukarelaAnggota() {
        const selectedUserId = userIdEl.val();
        if (!selectedUserId) {
            displaySaldoSukarelaAnggotaEl.textContent = 'Pilih anggota terlebih dahulu';
            return;
        }
        displaySaldoSukarelaAnggotaEl.textContent = 'Rp XXX (Fitur belum aktif)';
    }

    // Event listeners
    metodePembayaranEls.forEach(radio => {
        radio.addEventListener('change', updatePaymentFields);
    });

    if (totalBayarManualEl) {
        totalBayarManualEl.addEventListener('input', updatePaymentFields);
    }

    userIdEl.on('change', updatePaymentFields);

    // Payment method card clicks
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.addEventListener('click', function() {
            const method = this.dataset.method;
            const radio = document.getElementById(method);
            if (radio) {
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
                $('#user_id').val(null).trigger('change');
                $('#barang_search').val(null).trigger('change');
                cart.length = 0;
                updateCartDisplay();
                showNotification('Transaksi telah direset', 'info');
            }
        });
    }

    // Form submission validation
    document.getElementById('posForm').addEventListener('submit', function(e) {
        if (cart.length === 0) {
            e.preventDefault();
            showNotification('Keranjang belanja masih kosong!', 'error');
            return false;
        }

        const selectedMethod = document.querySelector('input[name="metode_pembayaran"]:checked')?.value;
        const totalBelanja = cart.reduce((sum, item) => sum + (item.harga * item.jumlah), 0);

        if (selectedMethod === 'tunai') {
            const bayar = parseFloat(totalBayarManualEl.value) || 0;
            if (bayar < totalBelanja) {
                e.preventDefault();
                showNotification('Jumlah pembayaran tunai kurang dari total belanja!', 'error');
                totalBayarManualEl.focus();
                return false;
            }
        }

        // Show loading state
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        submitBtn.disabled = true;

        // Re-enable button after 10 seconds as fallback
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });

    // Initialize
    updateCartDisplay();
    updatePaymentFields();
});
</script>
@endpush
