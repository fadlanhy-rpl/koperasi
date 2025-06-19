@extends('layouts.app')

@section('title', 'Manajemen Simpanan Pokok - Koperasi')

@section('page-title', 'Simpanan Pokok Anggota')
@section('page-subtitle', 'Kelola dan catat simpanan pokok anggota koperasi')

@push('styles')
<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    .slide-in {
        animation: slideIn 0.5s ease-out;
    }
    @keyframes slideIn {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    @keyframes fadeInUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .gradient-border {
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(45deg, #3b82f6, #8b5cf6, #06b6d4) border-box;
        border: 2px solid transparent;
    }
    
    /* Enhanced Select2 styling for member selection */
    .member-select .select2-container--default .select2-selection--single {
        height: 52px !important;
        border: 2px solid #d1d5db !important;
        border-radius: 12px !important;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    }
    
    .member-select .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1f2937 !important;
        font-weight: 600 !important;
        font-size: 15px !important;
        line-height: 48px !important;
        padding-left: 16px !important;
    }
    
    .member-select .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6b7280 !important;
        font-weight: 500 !important;
        font-style: italic !important;
    }
    
    .member-select .select2-dropdown {
        border: 2px solid #3b82f6 !important;
        border-radius: 12px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        background: white !important;
    }
    
    .member-select .select2-container--default .select2-results__option {
        padding: 14px 20px !important;
        color: #111827 !important;
        font-weight: 500 !important;
        font-size: 14px !important;
        border-bottom: 1px solid #f3f4f6 !important;
        transition: all 0.2s ease !important;
    }
    
    .member-select .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
        color: white !important;
        transform: translateX(4px) !important;
    }
    
    .member-select .select2-container--default .select2-results__option[aria-selected=true] {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%) !important;
        color: #1e40af !important;
        font-weight: 700 !important;
        border-left: 4px solid #3b82f6 !important;
    }
</style>
@endpush

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Anggota</p>
                <p class="text-3xl font-bold">{{ $anggotas->total() }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Sudah Bayar</p>
                <p class="text-3xl font-bold">{{ $anggotas->where('jumlah_setoran_pokok', '>', 0)->count() }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-2xl p-6 text-white card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Belum Bayar</p>
                <p class="text-3xl font-bold">{{ $anggotas->where('jumlah_setoran_pokok', '<=', 0)->count() }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-exclamation-circle text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-6 text-white card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Total Simpanan</p>
                <p class="text-2xl font-bold">@rupiah($anggotas->sum('total_simpanan_pokok'))</p>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fas fa-wallet text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Enhanced Form Tambah Simpanan Pokok -->
    <div class="lg:col-span-1">
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 card-hover gradient-border slide-in">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-3xl">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-500 p-2 rounded-xl">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Catat Simpanan Pokok Baru</h3>
                        <p class="text-sm text-gray-600">Tambahkan setoran simpanan pokok anggota</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <form action="{{ route('pengurus.simpanan.pokok.store') }}" method="POST" class="space-y-6" data-validate id="simpananForm">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-user-check text-blue-500 mr-2"></i>Pilih Anggota
                        </label>
                        <div class="member-select">
                            <select name="user_id" id="memberSelect" class="w-full" required>
                                <option value="">Pilih anggota yang belum bayar simpanan pokok...</option>
                                @foreach($anggotaBelumBayarPokok as $anggota)
                                    <option value="{{ $anggota->id }}" {{ old('user_id') == $anggota->id ? 'selected' : '' }}>
                                        {{ $anggota->name }} - {{ $anggota->nomor_anggota ?? 'No ID' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('user_id')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>Jumlah Simpanan Pokok
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="text" name="jumlah" id="jumlahInput" 
                                   placeholder="100.000" 
                                   value="{{ old('jumlah') }}"
                                   required 
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 placeholder-gray-400 font-medium text-gray-800"
                                   oninput="formatCurrency(this)">
                        </div>
                        @error('jumlah')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-purple-500 mr-2"></i>Tanggal Bayar
                        </label>
                        <input type="date" name="tanggal_bayar" 
                               value="{{ old('tanggal_bayar', date('Y-m-d')) }}" 
                               required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 font-medium text-gray-800">
                        @error('tanggal_bayar')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="keterangan_pokok" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sticky-note text-yellow-500 mr-2"></i>Keterangan (Opsional)
                        </label>
                        <textarea id="keterangan_pokok" name="keterangan" rows="3" 
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 placeholder-gray-400 resize-none" 
                                  placeholder="Catatan tambahan untuk setoran ini...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <button type="submit" id="submitBtn" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center space-x-2">
                            <i class="fas fa-save"></i>
                            <span>Simpan Setoran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Enhanced Daftar Anggota & Status Simpanan Pokok -->
    <div class="lg:col-span-2">
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 card-hover fade-in-up">
            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-t-3xl">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="bg-indigo-500 p-2 rounded-xl">
                            <i class="fas fa-list-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Status Simpanan Pokok Anggota</h3>
                            <p class="text-sm text-gray-600">Pantau status pembayaran simpanan pokok</p>
                        </div>
                    </div>
                    
                    <!-- Enhanced Search and Filter -->
                    <form method="GET" action="{{ route('pengurus.simpanan.pokok.index') }}" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <div class="relative">
                            <select name="status_bayar_pokok" onchange="this.form.submit()" class="appearance-none bg-white border-2 border-gray-200 rounded-xl px-4 py-2.5 pr-8 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium transition-all duration-300 text-gray-700">
                                <option value="">🔍 Semua Status</option>
                                <option value="sudah" {{ request('status_bayar_pokok') == 'sudah' ? 'selected' : '' }}>✅ Sudah Bayar</option>
                                <option value="belum" {{ request('status_bayar_pokok') == 'belum' ? 'selected' : '' }}>❌ Belum Bayar</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                        
                        <div class="relative">
                            <input type="text" name="search_anggota" value="{{ request('search_anggota') }}" 
                                   placeholder="🔍 Cari nama anggota..." 
                                   class="w-full sm:w-64 px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all duration-300 text-gray-700">
                        </div>
                        
                        <button type="submit" class="bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white px-6 py-2.5 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-search mr-2"></i>Cari
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full min-w-[600px]">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <th class="text-left py-4 px-6 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                    <i class="fas fa-user mr-2 text-blue-500"></i>Nama Anggota
                                </th>
                                <th class="text-left py-4 px-6 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                    <i class="fas fa-id-card mr-2 text-green-500"></i>No. Anggota
                                </th>
                                <th class="text-center py-4 px-6 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                    <i class="fas fa-check-circle mr-2 text-purple-500"></i>Status Pokok
                                </th>
                                <th class="text-right py-4 px-6 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                    <i class="fas fa-money-bill mr-2 text-yellow-500"></i>Total Setoran
                                </th>
                                <th class="text-center py-4 px-6 font-bold text-gray-700 uppercase text-xs tracking-wider">
                                    <i class="fas fa-cogs mr-2 text-red-500"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($anggotas as $anggota)
                                <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 group">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="bg-gradient-to-r from-blue-400 to-blue-500 w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($anggota->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800 group-hover:text-blue-600 transition-colors">{{ $anggota->name }}</p>
                                                <p class="text-xs text-gray-500">Anggota Koperasi</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="bg-gray-100 px-3 py-1 rounded-full text-sm font-medium text-gray-700">
                                            {{ $anggota->nomor_anggota ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($anggota->jumlah_setoran_pokok > 0)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300">
                                                <i class="fas fa-check-circle mr-1.5"></i>Sudah Lunas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300 pulse-animation">
                                                <i class="fas fa-exclamation-circle mr-1.5"></i>Belum Bayar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <span class="text-lg font-bold text-gray-800">@rupiah($anggota->total_simpanan_pokok ?? 0)</span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('pengurus.simpanan.riwayatAnggota', $anggota->id) }}?tab=pokok" 
                                           class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-300" 
                                           title="Lihat Riwayat Simpanan">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12">
                                        <div class="flex flex-col items-center space-y-4">
                                            <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center">
                                                <i class="fas fa-users-slash text-3xl text-gray-400"></i>
                                            </div>
                                            <div>
                                                <p class="text-lg font-semibold text-gray-600">Tidak ada data anggota</p>
                                                <p class="text-sm text-gray-500">Belum ada anggota yang terdaftar atau sesuai filter</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($anggotas->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $anggotas->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 for member selection
        $('#memberSelect').select2({
            placeholder: 'Ketik untuk mencari anggota...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Tidak ada anggota yang ditemukan";
                },
                searching: function() {
                    return "Mencari anggota...";
                },
                inputTooShort: function() {
                    return "Ketik minimal 1 karakter untuk mencari";
                }
            },
            templateResult: function(option) {
                if (!option.id) {
                    return option.text;
                }
                
                var $option = $(
                    '<div class="flex items-center space-x-3">' +
                        '<div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">' +
                            option.text.charAt(0).toUpperCase() +
                        '</div>' +
                        '<div>' +
                            '<div class="font-semibold text-gray-800">' + option.text.split(' - ')[0] + '</div>' +
                            '<div class="text-xs text-gray-500">ID: ' + (option.text.split(' - ')[1] || 'Tidak ada') + '</div>' +
                        '</div>' +
                    '</div>'
                );
                return $option;
            },
            templateSelection: function(option) {
                if (!option.id) {
                    return option.text;
                }
                return option.text.split(' - ')[0]; // Show only name in selection
            }
        });

        // Currency formatting function
        window.formatCurrency = function(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value) {
                // Format with thousand separators
                let formatted = parseInt(value).toLocaleString('id-ID');
                input.value = formatted;
            }
        };
        
        // Form submission with enhanced loading state
        $('#simpananForm').on('submit', function(e) {
            const submitBtn = $('#submitBtn');
            const originalHtml = submitBtn.html();
            
            // Validate required fields
            const memberId = $('#memberSelect').val();
            const jumlah = $('#jumlahInput').val();
            
            if (!memberId) {
                e.preventDefault();
                showNotification('Silakan pilih anggota terlebih dahulu!', 'error');
                return false;
            }
            
            if (!jumlah || jumlah.trim() === '') {
                e.preventDefault();
                showNotification('Silakan masukkan jumlah simpanan pokok!', 'error');
                $('#jumlahInput').focus();
                return false;
            }
            
            // Show loading state
            submitBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');
            submitBtn.prop('disabled', true);
            
            // Convert formatted currency back to number for submission
            const cleanValue = jumlah.replace(/[^\d]/g, '');
            $('#jumlahInput').val(cleanValue);
            
            // Re-enable after timeout as fallback
            setTimeout(() => {
                submitBtn.html(originalHtml);
                submitBtn.prop('disabled', false);
            }, 5000);
        });
        
        // Enhanced hover effects for table rows
        $('tbody tr').hover(
            function() {
                $(this).css('transform', 'scale(1.01)');
            },
            function() {
                $(this).css('transform', 'scale(1)');
            }
        );
        
        // Auto-submit search form with debounce
        let searchTimeout;
        $('input[name="search_anggota"]').on('input', function() {
            clearTimeout(searchTimeout);
            const form = $(this).closest('form');
            searchTimeout = setTimeout(() => {
                form.submit();
            }, 800);
        });
        
        // Initialize currency formatting on page load
        const jumlahInput = $('#jumlahInput');
        if (jumlahInput.val()) {
            formatCurrency(jumlahInput[0]);
        }
    });
    
    // Success/Error notifications
    @if(session('success'))
        $(document).ready(function() {
            showNotification('{{ session('success') }}', 'success');
        });
    @endif
    
    @if($errors->any())
        $(document).ready(function() {
            let errorMessages = '';
            @foreach($errors->all() as $error)
                errorMessages += '<p class="mb-1">• {{ $error }}</p>';
            @endforeach
            
            Swal.fire({
                icon: 'error',
                title: 'Terdapat Kesalahan!',
                html: errorMessages,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Perbaiki'
            });
        });
    @endif
</script>
@endpush