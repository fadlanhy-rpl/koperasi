@extends('layouts.app')

@section('title', 'Buat Transaksi Baru (POS) - Koperasi')

@section('page-title', 'Point of Sale (POS)')
@section('page-subtitle', 'Catat transaksi pembelian barang anggota')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* ... (Style Select2 dan custom lainnya SAMA seperti sebelumnya) ... */
    .select2-container .select2-selection--single { height: 46px !important; border-radius: 0.75rem !important; border: 1px solid #D1D5DB !important; padding-top: 0.55rem !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 44px !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 28px !important; }
    .select2-dropdown { border-radius: 0.75rem !important; border: 1px solid #D1D5DB !important; }
    .select2-container--open .select2-dropdown--below { margin-top: 2px !important; }
    .select2-search--dropdown .select2-search__field { border-radius: 0.5rem !important; border: 1px solid #D1D5DB !important; padding: 0.5rem 0.75rem !important;}
    .item-in-cart td { vertical-align: middle; }
</style>
@endpush

@section('content')
<form id="posForm" action="{{ route('pengurus.transaksi-pembelian.store') }}" method="POST" class="animate-fade-in" data-validate>
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Detail Transaksi & Item Barang -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Transaksi Dasar -->
            <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Transaksi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1.5">Anggota Pembeli <span class="text-red-500">*</span></label>
                        <select name="user_id" id="user_id" class="w-full select2-basic" required data-placeholder="Pilih Anggota">
                            <option value=""></option> {{-- Option kosong untuk placeholder Select2 --}}
                            @foreach($anggota as $agt) {{-- Variabel $anggota dari controller --}}
                                <option value="{{ $agt->id }}" data-nomor="{{ $agt->nomor_anggota ?? '-' }}" {{ old('user_id') == $agt->id ? 'selected' : '' }}>
                                    {{ $agt->name }} ({{ $agt->nomor_anggota ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <x-forms.input 
                        type="date" 
                        name="tanggal_pembelian" 
                        label="Tanggal Transaksi" 
                        :value="old('tanggal_pembelian', date('Y-m-d'))" 
                        :required="true"
                    />
                </div>
            </div>

            <!-- Pencarian & Daftar Item Barang -->
            <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20">
                {{-- ... (Bagian Pilih Barang dan Keranjang SAMA seperti sebelumnya) ... --}}
                 <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Barang</h3>
                <div>
                    <label for="barang_search" class="block text-sm font-medium text-gray-700 mb-1.5">Cari & Tambah Barang</label>
                    <select id="barang_search" class="w-full select2-basic" data-placeholder="Ketik nama atau kode barang...">
                        <option value=""></option>
                        @foreach($barangs as $brg) {{-- Variabel $barangs dari controller --}}
                            <option value="{{ $brg->id }}" data-nama="{{ $brg->nama_barang }}" data-kode="{{ $brg->kode_barang ?? '' }}" data-harga="{{ $brg->harga_jual }}" data-stok="{{ $brg->stok }}" data-satuan="{{ $brg->satuan }}">
                                {{ $brg->nama_barang }} (Stok: {{ $brg->stok }}) - @rupiah($brg->harga_jual)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-6 overflow-x-auto"><h4 class="text-md font-semibold text-gray-700 mb-3">Keranjang Belanja</h4><table class="w-full min-w-[600px]"><thead><tr class="border-b-2 border-gray-200 bg-gray-50 text-xs uppercase text-gray-500"><th class="py-2 px-3 text-left">Barang</th><th class="py-2 px-3 text-center w-24">Jumlah</th><th class="py-2 px-3 text-right">Harga Satuan</th><th class="py-2 px-3 text-right">Subtotal</th><th class="py-2 px-3 text-center w-16">Aksi</th></tr></thead><tbody id="cartItemsBody"><tr id="cartEmptyRow"><td colspan="5" class="text-center py-6 text-gray-400 italic">Keranjang masih kosong.</td></tr></tbody></table></div>
            </div>
        </div>

        <!-- Kolom Kanan: Ringkasan Pembayaran & Aksi -->
        <div class="lg:col-span-1">
            <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-white/20 sticky top-24">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-3">Ringkasan & Pembayaran</h3>
                {{-- ... (Ringkasan belanja SAMA seperti sebelumnya) ... --}}
                <div class="space-y-3 mb-6 text-sm"><div class="flex justify-between"><span class="text-gray-600">Subtotal Barang:</span><span class="font-semibold text-gray-800" id="cartSubtotal">Rp 0</span></div><div class="flex justify-between text-lg border-t pt-3"><span class="font-bold text-gray-800">Total Belanja:</span><span class="font-bold text-blue-600" id="cartTotal">Rp 0</span></div></div>
                
                <div>
                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-1.5">Metode Pembayaran <span class="text-red-500">*</span></label>
                    <select name="metode_pembayaran" id="metode_pembayaran" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500" required>
                        <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="saldo_sukarela" {{ old('metode_pembayaran') == 'saldo_sukarela' ? 'selected' : '' }}>Potong Saldo Sukarela</option>
                        <option value="hutang" {{ old('metode_pembayaran') == 'hutang' ? 'selected' : '' }}>Hutang / Cicilan</option>
                    </select>
                    @error('metode_pembayaran') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div id="pembayaranTunaiFields" class="mt-4 space-y-4 {{ old('metode_pembayaran', 'tunai') !== 'tunai' ? 'hidden' : '' }}">
                    <x-forms.input 
                        type="number" 
                        name="total_bayar_manual" 
                        label="Jumlah Bayar (Tunai)" 
                        placeholder="0" 
                        :value="old('total_bayar_manual')"
                        min="0" {{-- Atribut individual --}}
                        step="any" {{-- Atribut individual --}}
                    />
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Kembalian:</span>
                        <span class="font-semibold text-gray-800" id="kembalianDisplay">Rp 0</span>
                    </div>
                </div>

                <div id="infoSaldoSukarela" class="mt-4 {{ old('metode_pembayaran') !== 'saldo_sukarela' ? 'hidden' : '' }} bg-blue-50 p-3 rounded-lg text-sm text-blue-700">
                    Saldo sukarela anggota akan dicek saat proses simpan.
                    <p>Saldo Terkini Anggota: <span id="displaySaldoSukarelaAnggota" class="font-semibold">-</span></p>
                </div>
                
                <div id="infoHutangFields" class="mt-4 {{ old('metode_pembayaran') !== 'hutang' ? 'hidden' : '' }}">
                     <x-forms.input 
                        type="number" 
                        name="uang_muka" 
                        label="Uang Muka (DP)" 
                        placeholder="0" 
                        :value="old('uang_muka')"
                        min="0" {{-- Atribut individual --}}
                        step="any" {{-- Atribut individual --}}
                    />
                     <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada uang muka.</p>
                </div>

                <div class="mt-6">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1.5">Catatan (Opsional)</label>
                    <textarea name="catatan" id="catatan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500" placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                </div>

                <div class="mt-8">
                    <x-forms.button type="submit" variant="primary" size="lg" class="w-full" icon="check-circle">
                        Proses & Simpan Transaksi
                    </x-forms.button>
                </div>
                <div class="mt-3">
                     <x-forms.button type="button" id="resetTransaksiBtn" variant="secondary" size="md" class="w-full" icon="undo">
                        Reset Transaksi
                    </x-forms.button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="items" id="itemsJsonInput">
</form>
@endsection

@push('scripts')
{{-- ... (CDN Select2 dan JavaScript untuk POS SAMA seperti kode Anda sebelumnya) ... --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script> /* ... (Kode JS POS yang sudah kita buat sebelumnya, pastikan ID elemen dan logika benar) ... */ 

 document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('.select2-basic').select2({
                placeholder: $(this).data('placeholder') || "Pilih opsi",
                width: '100%'
            });
            $('#barang_search').select2({
                placeholder: "Ketik nama atau kode barang...",
                width: '100%',
                language: {
                    noResults: function() {
                        return "Barang tidak ditemukan";
                    }
                }
            });

            const cart = [];
            const itemsJsonInput = document.getElementById('itemsJsonInput');
            const cartItemsBody = document.getElementById('cartItemsBody');
            const cartEmptyRow = document.getElementById('cartEmptyRow');
            const cartSubtotalEl = document.getElementById('cartSubtotal');
            const cartTotalEl = document.getElementById('cartTotal');
            const barangSearchEl = $('#barang_search'); // jQuery object for Select2
            const metodePembayaranEl = document.getElementById('metode_pembayaran');
            const pembayaranTunaiFieldsEl = document.getElementById('pembayaranTunaiFields');
            const totalBayarManualEl = document.querySelector('input[name="total_bayar_manual"]');
            const kembalianDisplayEl = document.getElementById('kembalianDisplay');
            const infoSaldoSukarelaEl = document.getElementById('infoSaldoSukarela');
            const displaySaldoSukarelaAnggotaEl = document.getElementById('displaySaldoSukarelaAnggota');
            const infoHutangFieldsEl = document.getElementById('infoHutangFields');
            const uangMukaEl = document.querySelector('input[name="uang_muka"]');
            const resetTransaksiBtn = document.getElementById('resetTransaksiBtn');
            const userIdEl = $('#user_id'); // jQuery object for Select2


            function formatRupiah(angka) {
                return KoperasiApp.formatCurrency(angka).replace(",00", ""); // Hapus ,00 dari formatCurrency
            }

            function renderCart() {
                cartItemsBody.innerHTML = ''; // Clear current items
                let subtotal = 0;
                if (cart.length === 0) {
                    cartItemsBody.appendChild(cartEmptyRow);
                } else {
                    cart.forEach((item, index) => {
                        const itemSubtotal = item.harga * item.jumlah;
                        subtotal += itemSubtotal;
                        const row = `
                    <tr class="border-b border-gray-100 item-in-cart" data-index="${index}">
                        <td class="py-3 px-3">
                            <p class="font-medium text-gray-800">${item.nama}</p>
                            <p class="text-xs text-gray-500">${item.kode || '-'}</p>
                        </td>
                        <td class="py-3 px-3 text-center">
                            <input type="number" value="${item.jumlah}" min="1" max="${item.stok_awal}" 
                                   class="w-20 text-center border border-gray-300 rounded-md py-1.5 px-2 quantity-input" data-index="${index}">
                        </td>
                        <td class="py-3 px-3 text-right text-gray-600">${formatRupiah(item.harga)}</td>
                        <td class="py-3 px-3 text-right font-semibold text-gray-800">${formatRupiah(itemSubtotal)}</td>
                        <td class="py-3 px-3 text-center">
                            <button type="button" class="text-red-500 hover:text-red-700 remove-item-btn" data-index="${index}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>`;
                        cartItemsBody.insertAdjacentHTML('beforeend', row);
                    });
                }
                cartSubtotalEl.textContent = formatRupiah(subtotal);
                cartTotalEl.textContent = formatRupiah(subtotal);
                itemsJsonInput.value = JSON.stringify(cart.map(item => ({
                    barang_id: item.id,
                    jumlah: item.jumlah,
                    harga_satuan: item.harga
                })));
                updatePembayaranFields(); // Update payment fields based on total
            }

            function updateItemQuantity(index, newQuantity) {
                const item = cart[index];
                if (newQuantity > 0 && newQuantity <= item.stok_awal) {
                    item.jumlah = newQuantity;
                } else if (newQuantity > item.stok_awal) {
                    item.jumlah = item.stok_awal; // Set to max stock
                    KoperasiApp.showNotification(
                        `Jumlah melebihi stok tersedia (${item.stok_awal}) untuk ${item.nama}.`, 'warning');
                }
                renderCart();
            }

            function removeItemFromCart(index) {
                cart.splice(index, 1);
                renderCart();
            }

            barangSearchEl.on('select2:select', function(e) {
                const selectedOption = $(e.params.data.element);
                const barangId = parseInt(selectedOption.val());

                if (!barangId) return;

                const existingItemIndex = cart.findIndex(item => item.id === barangId);

                if (existingItemIndex > -1) {
                    // Jika barang sudah ada, tambahkan jumlahnya
                    const item = cart[existingItemIndex];
                    if (item.jumlah < item.stok_awal) {
                        item.jumlah++;
                    } else {
                        KoperasiApp.showNotification(`Stok ${item.nama} sudah maksimal di keranjang.`,
                            'warning');
                    }
                } else {
                    // Tambah barang baru ke keranjang
                    cart.push({
                        id: barangId,
                        nama: selectedOption.data('nama'),
                        kode: selectedOption.data('kode'),
                        harga: parseFloat(selectedOption.data('harga')),
                        stok_awal: parseInt(selectedOption.data('stok')),
                        satuan: selectedOption.data('satuan'),
                        jumlah: 1
                    });
                }
                renderCart();
                barangSearchEl.val(null).trigger('change'); // Reset select2
            });

            cartItemsBody.addEventListener('change', function(e) {
                if (e.target.classList.contains('quantity-input')) {
                    const index = parseInt(e.target.dataset.index);
                    const newQuantity = parseInt(e.target.value);
                    updateItemQuantity(index, newQuantity);
                }
            });

            cartItemsBody.addEventListener('click', function(e) {
                const removeButton = e.target.closest('.remove-item-btn');
                if (removeButton) {
                    const index = parseInt(removeButton.dataset.index);
                    removeItemFromCart(index);
                }
            });

            function updatePembayaranFields() {
                const metode = metodePembayaranEl.value;
                const totalBelanja = parseFloat(cartTotalEl.textContent.replace(/[^0-9.-]+/g, "").replace('.',
                    '')) || 0;

                pembayaranTunaiFieldsEl.classList.add('hidden');
                infoSaldoSukarelaEl.classList.add('hidden');
                infoHutangFieldsEl.classList.add('hidden');

                if (metode === 'tunai') {
                    pembayaranTunaiFieldsEl.classList.remove('hidden');
                    const bayar = parseFloat(totalBayarManualEl.value) || 0;
                    const kembali = bayar - totalBelanja;
                    kembalianDisplayEl.textContent = formatRupiah(kembali < 0 ? 0 : kembali);
                    if (bayar > 0 && bayar < totalBelanja) {
                        totalBayarManualEl.classList.add('border-red-500');
                    } else {
                        totalBayarManualEl.classList.remove('border-red-500');
                    }
                } else if (metode === 'saldo_sukarela') {
                    infoSaldoSukarelaEl.classList.remove('hidden');
                    // Fetch and display member's saldo sukarela if needed
                    fetchSaldoSukarelaAnggota();
                } else if (metode === 'hutang') {
                    infoHutangFieldsEl.classList.remove('hidden');
                }
            }

            async function fetchSaldoSukarelaAnggota() {
                const selectedUserId = userIdEl.val();
                if (!selectedUserId) {
                    displaySaldoSukarelaAnggotaEl.textContent = 'Pilih anggota terlebih dahulu.';
                    return;
                }
                // Anda perlu endpoint API untuk ini
                // Contoh: /api/anggota/{id}/saldo-sukarela
                // Untuk sekarang, kita tampilkan placeholder
                // displaySaldoSukarelaAnggotaEl.textContent = 'Memuat saldo...'; 
                // KoperasiApp.makeRequest(`/api/anggota/${selectedUserId}/saldo-sukarela`) // Ganti dengan route API yang benar
                //     .then(data => {
                //         displaySaldoSukarelaAnggotaEl.textContent = formatRupiah(data.saldo_sukarela || 0);
                //     })
                //     .catch(err => {
                //         console.error("Gagal fetch saldo", err);
                //         displaySaldoSukarelaAnggotaEl.textContent = 'Gagal memuat saldo.';
                //     });
                displaySaldoSukarelaAnggotaEl.textContent =
                'Rp XXX (Fitur ambil saldo belum aktif)'; // Placeholder
            }

            if (metodePembayaranEl) metodePembayaranEl.addEventListener('change', updatePembayaranFields);
            if (totalBayarManualEl) totalBayarManualEl.addEventListener('input', updatePembayaranFields);
            if (userIdEl) userIdEl.on('change', updatePembayaranFields); // Panggil juga saat anggota diganti

            function resetFormTransaksi() {
                document.getElementById('posForm').reset();
                $('#user_id').val(null).trigger('change'); // Reset select2 user
                $('#barang_search').val(null).trigger('change'); // Reset select2 barang
                cart.length = 0; // Kosongkan array cart
                renderCart(); // Render ulang keranjang (akan menampilkan pesan kosong)
                updatePembayaranFields(); // Setel ulang field pembayaran
                KoperasiApp.showNotification('Form transaksi telah direset.', 'info');
            }

            if (resetTransaksiBtn) resetTransaksiBtn.addEventListener('click', resetFormTransaksi);

            // Initial call
            renderCart();
        });

</script>
@endpush