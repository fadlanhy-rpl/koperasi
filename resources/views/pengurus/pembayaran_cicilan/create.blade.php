@extends('layouts.app')

@section('title', 'Catat Pembayaran Cicilan - Koperasi')

@section('page-title', 'Pembayaran Cicilan')
@section('page-subtitle', 'Catat pembayaran angsuran untuk transaksi #' . $pembelian->kode_pembelian)

@push('styles')
<style>
    .payment-form {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .form-card {
        backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .amount-input {
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border: 2px solid #0ea5e9;
    }
    .amount-input:focus {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
    }
    .info-card {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-left: 4px solid #f59e0b;
    }
    .remaining-balance {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border-left: 4px solid #ef4444;
    }
    .progress-ring {
        transform: rotate(-90deg);
    }
    .floating-label {
        transition: all 0.3s ease;
    }
    .input-group:focus-within .floating-label {
        transform: translateY(-1.5rem) scale(0.85);
        color: #0ea5e9;
    }
    .input-group input:not(:placeholder-shown) + .floating-label {
        transform: translateY(-1.5rem) scale(0.85);
        color: #6b7280;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen payment-form py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header Card -->
        <div class="form-card rounded-2xl shadow-2xl mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Form Pembayaran Cicilan</h1>
                        <p class="opacity-90">Transaksi: <span class="font-semibold">{{ $pembelian->kode_pembelian }}</span></p>
                        <p class="opacity-90">Anggota: <span class="font-semibold">{{ $pembelian->user->name }}</span></p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/20 rounded-full p-4">
                            <i class="fas fa-credit-card text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Payment Summary -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Transaction Info -->
                <div class="form-card rounded-2xl shadow-xl p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Informasi Transaksi
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Kode Transaksi:</span>
                            <span class="font-semibold">{{ $pembelian->kode_pembelian }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Tanggal:</span>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->isoFormat('DD MMM YYYY') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Anggota:</span>
                            <span class="font-semibold">{{ $pembelian->user->name }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">No. Anggota:</span>
                            <span class="font-semibold">{{ $pembelian->user->nomor_anggota ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Progress -->
                <div class="form-card rounded-2xl shadow-xl p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-green-600"></i>
                        Progress Pembayaran
                    </h3>
                    
                    @php
                        $totalBayar = $pembelian->total_bayar + $pembelian->cicilans->sum('jumlah_bayar');
                        $progress = ($totalBayar / $pembelian->total_harga) * 100;
                    @endphp
                    
                    <div class="text-center mb-4">
                        <div class="relative inline-flex items-center justify-center w-24 h-24">
                            <svg class="w-24 h-24 progress-ring">
                                <circle cx="48" cy="48" r="40" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                                <circle cx="48" cy="48" r="40" stroke="#10b981" stroke-width="8" fill="none"
                                        stroke-dasharray="{{ 2 * pi() * 40 }}" 
                                        stroke-dashoffset="{{ 2 * pi() * 40 * (1 - $progress/100) }}"
                                        class="transition-all duration-500"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-lg font-bold text-gray-700">{{ number_format($progress, 0) }}%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Tagihan:</span>
                            <span class="font-semibold">@rupiah($pembelian->total_harga)</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Sudah Dibayar:</span>
                            <span class="font-semibold text-green-600">@rupiah($totalBayar)</span>
                        </div>
                        <hr>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Sisa Tagihan:</span>
                            <span class="font-bold text-lg text-red-600">@rupiah($sisaTagihan)</span>
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                @if($pembelian->cicilans->count() > 0)
                <div class="form-card rounded-2xl shadow-xl p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-history mr-2 text-purple-600"></i>
                        Riwayat Cicilan
                    </h3>
                    
                    <div class="space-y-3 max-h-48 overflow-y-auto">
                        @foreach($pembelian->cicilans->sortByDesc('tanggal_bayar') as $cicilan)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <div class="font-semibold text-sm">@rupiah($cicilan->jumlah_bayar)</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($cicilan->tanggal_bayar)->isoFormat('DD MMM YYYY') }}</div>
                            </div>
                            <div class="text-green-600">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <div class="form-card rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white p-6">
                        <h2 class="text-xl font-bold flex items-center">
                            <i class="fas fa-money-bill-wave mr-3"></i>
                            Form Pembayaran Cicilan
                        </h2>
                        <p class="opacity-90 mt-1">Masukkan detail pembayaran cicilan</p>
                    </div>
                    
                    <div class="p-8">
                        @if($sisaTagihan > 0)
                        <form action="{{ route('pengurus.pembayaran-cicilan.store', $pembelian->id) }}" method="POST" class="space-y-8" id="payment-form">
                            @csrf
                            
                            <!-- Amount Input -->
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-dollar-sign mr-2"></i>Jumlah Bayar Cicilan
                                </label>
                                <div class="relative">
                                    <input type="number" 
                                           name="jumlah_bayar" 
                                           id="jumlah_bayar"
                                           class="amount-input w-full px-6 py-4 rounded-xl transition-all duration-300"
                                           placeholder="0"
                                           min="0.01" 
                                           max="{{ $sisaTagihan }}" 
                                           step="any"
                                           value="{{ old('jumlah_bayar') }}"
                                           required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-blue-600 text-xl font-bold">Rp</span>
                                    </div>
                                </div>
                                @error('jumlah_bayar')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                                
                                <!-- Quick Amount Buttons -->
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <button type="button" onclick="setAmount({{ $sisaTagihan / 4 }})" 
                                            class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-semibold">
                                        25% (@rupiah($sisaTagihan / 4))
                                    </button>
                                    <button type="button" onclick="setAmount({{ $sisaTagihan / 2 }})" 
                                            class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-semibold">
                                        50% (@rupiah($sisaTagihan / 2))
                                    </button>
                                    <button type="button" onclick="setAmount({{ $sisaTagihan * 0.75 }})" 
                                            class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-semibold">
                                        75% (@rupiah($sisaTagihan * 0.75))
                                    </button>
                                    <button type="button" onclick="setAmount({{ $sisaTagihan }})" 
                                            class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm font-semibold">
                                        Lunas (@rupiah($sisaTagihan))
                                    </button>
                                </div>
                            </div>

                            <!-- Date Input -->
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-calendar mr-2"></i>Tanggal Bayar
                                </label>
                                <input type="date" 
                                       name="tanggal_bayar" 
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                       value="{{ old('tanggal_bayar', date('Y-m-d')) }}" 
                                       max="{{ date('Y-m-d') }}"
                                       required>
                                @error('tanggal_bayar')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Notes Input -->
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class="fas fa-sticky-note mr-2"></i>Keterangan (Opsional)
                                </label>
                                <textarea name="keterangan" 
                                          rows="4" 
                                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 resize-none"
                                          placeholder="Contoh: Pembayaran angsuran ke-2, cicilan bulan Januari, dll.">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('pengurus.transaksi-pembelian.show', $pembelian->id) }}" 
                                   class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors flex items-center justify-center gap-2 font-semibold">
                                    <i class="fas fa-times"></i>
                                    Batal
                                </a>
                                <button type="submit" 
                                        class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 flex items-center justify-center gap-2 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-check-circle"></i>
                                    Simpan Pembayaran
                                </button>
                            </div>
                        </form>
                        @else
                        <div class="text-center py-12">
                            <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check-double text-3xl text-green-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Transaksi Sudah Lunas!</h3>
                            <p class="text-gray-600 mb-6">Tidak ada sisa tagihan yang perlu dibayar.</p>
                            <a href="{{ route('pengurus.transaksi-pembelian.show', $pembelian->id) }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold">
                                <i class="fas fa-arrow-left"></i>
                                Kembali ke Detail Transaksi
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function setAmount(amount) {
        document.getElementById('jumlah_bayar').value = Math.round(amount);
        document.getElementById('jumlah_bayar').focus();
    }

    // Format number input
    document.getElementById('jumlah_bayar').addEventListener('input', function(e) {
        let value = e.target.value;
        if (value) {
            // Remove any non-numeric characters except decimal point
            value = value.replace(/[^0-9.]/g, '');
            e.target.value = value;
        }
    });

    // Form validation
    document.getElementById('payment-form').addEventListener('submit', function(e) {
        const amount = parseFloat(document.getElementById('jumlah_bayar').value);
        const maxAmount = {{ $sisaTagihan }};
        
        if (amount > maxAmount) {
            e.preventDefault();
            alert('Jumlah bayar tidak boleh melebihi sisa tagihan!');
            return false;
        }
        
        if (amount <= 0) {
            e.preventDefault();
            alert('Jumlah bayar harus lebih dari 0!');
            return false;
        }
        
        // Confirmation dialog
        const confirmation = confirm(`Apakah Anda yakin ingin mencatat pembayaran sebesar Rp ${amount.toLocaleString('id-ID')}?`);
        if (!confirmation) {
            e.preventDefault();
            return false;
        }
    });

    // Auto-focus on amount input
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('jumlah_bayar').focus();
    });
</script>
@endpush
@endsection