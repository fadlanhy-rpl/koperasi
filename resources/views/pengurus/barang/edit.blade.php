@extends('layouts.app')

@section('title', 'Edit Barang: ' . $barang->nama_barang)
@section('page-title', 'Edit Data Barang')
@section('page-subtitle', 'Perbarui detail untuk: ' . $barang->nama_barang)

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .slide-in {
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .gradient-border {
            background: linear-gradient(white, white) padding-box,
                linear-gradient(45deg, #3b82f6, #8b5cf6, #06b6d4) border-box;
            border: 2px solid transparent;
        }

        /* Enhanced image upload area */
        .image-upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .image-upload-area:hover {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            transform: scale(1.02);
        }

        .image-upload-area.has-image {
            border-color: #10b981;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        }

        .current-image-container {
            position: relative;
            width: 200px;
            height: 200px;
            border-radius: 16px;
            overflow: hidden;
            border: 3px solid #e5e7eb;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            transition: all 0.3s ease;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .current-image-container:hover {
            border-color: #3b82f6;
            transform: scale(1.05);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .current-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .current-image-container:hover .image-overlay {
            opacity: 1;
        }

        .new-image-preview {
            width: 150px;
            height: 150px;
            border-radius: 12px;
            overflow: hidden;
            border: 3px solid #10b981;
            background: white;
            box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.1);
            margin-top: 1rem;
        }

        .new-image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Cropper modal styling */
        .img-container-cropper {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            margin-bottom: 1rem;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        #imageToCropBarangEdit {
            display: block;
            max-width: 100%;
            max-height: 100%;
        }

        .preview-barang-container-edit {
            width: 150px;
            height: 150px;
            overflow: hidden;
            border: 3px solid #3b82f6;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            background: white;
            margin-top: 0.5rem;
        }

        .upload-button-barang {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            font-size: 0.875rem;
        }

        .upload-button-barang:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .modal-overlay {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 600px;
            width: 95%;
            max-height: 90vh;
            overflow-y: auto;
        }

        input[type="file"].visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }

        /* Enhanced form styling */
        .form-group {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .form-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: white;
            outline: none;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        /* Select2 enhanced styling */
        .select2-container--default .select2-selection--single {
            height: 52px !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
            padding-top: 8px !important;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        .select2-dropdown {
            border: 2px solid #3b82f6 !important;
            border-radius: 12px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
        }

        /* Current stock display */
        .current-stock {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 2px solid #bbf7d0;
            position: relative;
            overflow: hidden;
        }

        .current-stock::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .current-stock-value {
            font-size: 2rem;
            font-weight: 800;
            color: #059669;
            line-height: 1;
        }

        .stock-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .stock-action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stock-action-btn.primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .stock-action-btn.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .stock-action-btn.danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .stock-action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px -2px rgba(0, 0, 0, 0.1);
        }

        /* Delete image section */
        .delete-image-section {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 2px solid #fecaca;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .delete-checkbox {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 0.375rem;
            border: 2px solid #f87171;
            background: white;
            transition: all 0.3s ease;
        }

        .delete-checkbox:checked {
            background: #ef4444;
            border-color: #ef4444;
        }

        /* Action buttons */
        .action-btn {
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.875rem;
            gap: 0.5rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .action-btn.primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }

        .action-btn.secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .current-image-container {
                width: 150px;
                height: 150px;
            }

            .current-stock-value {
                font-size: 1.5rem;
            }

            .stock-actions {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
        <div class="max-w-5xl mx-auto px-4">
            <div
                class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/30 card-hover gradient-border slide-in">
                <!-- Header -->
                <div class="p-8 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-3xl">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="bg-blue-500 p-3 rounded-xl">
                                <i class="fas fa-edit text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-800">Edit Barang</h1>
                                <p class="text-gray-600 mt-1">{{ $barang->nama_barang }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-sm font-medium">
                                        <i class="fas fa-barcode mr-1"></i>
                                        {{ $barang->kode_barang ?? 'Tanpa Kode' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('pengurus.barang.show', $barang->id) }}" class="action-btn secondary">
                                <i class="fas fa-eye"></i>
                                <span>Lihat Detail</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-8">
                    <form action="{{ route('pengurus.barang.update', $barang->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-8" data-validate id="editBarangForm">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 fade-in-up">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                Informasi Dasar
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-tag text-blue-500 mr-2"></i>Nama Barang
                                    </label>
                                    <input type="text" name="nama_barang" class="form-input"
                                        value="{{ old('nama_barang', $barang->nama_barang) }}" required
                                        placeholder="Masukkan nama barang">
                                    @error('nama_barang')
                                        <p class="text-red-500 text-sm mt-1 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-barcode text-green-500 mr-2"></i>Kode Barang
                                    </label>
                                    <input type="text" name="kode_barang" class="form-input"
                                        value="{{ old('kode_barang', $barang->kode_barang) }}"
                                        placeholder="Otomatis jika kosong">
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Kosongkan untuk generate otomatis jika belum ada
                                    </p>
                                    @error('kode_barang')
                                        <p class="text-red-500 text-sm mt-1 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-building text-purple-500 mr-2"></i>Unit Usaha
                                    </label>
                                    <select name="unit_usaha_id" class="form-input select2-basic" required>
                                        <option value="">Pilih unit usaha</option>
                                        @foreach ($unitUsahas as $unit)
                                            <option value="{{ $unit->id }}"
                                                {{ old('unit_usaha_id', $barang->unit_usaha_id) == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->nama_unit_usaha }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_usaha_id')
                                        <p class="text-red-500 text-sm mt-1 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-weight text-orange-500 mr-2"></i>Satuan Barang
                                    </label>
                                    <select name="satuan" class="form-input select2-basic" required>
                                        <option value="">Pilih satuan</option>
                                        @foreach ($satuans as $satuan)
                                            <option value="{{ $satuan }}"
                                                {{ old('satuan', $barang->satuan) == $satuan ? 'selected' : '' }}>
                                                {{ ucfirst($satuan) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('satuan')
                                        <p class="text-red-500 text-sm mt-1 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Pricing & Stock -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-6 fade-in-up">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>
                                Harga & Stok
                            </h3>

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-shopping-cart text-red-500 mr-2"></i>Harga Beli Satuan
                                    </label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                        <input type="number" name="harga_beli" class="form-input pl-12"
                                            value="{{ old('harga_beli', $barang->harga_beli) }}" required min="0"
                                            step="any" placeholder="0">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Harga modal pembelian</p>
                                    @error('harga_beli')
                                        <p class="text-red-500 text-sm mt-1 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-tag text-blue-500 mr-2"></i>Harga Jual Satuan
                                    </label>
                                    <div class="relative">
                                        <span
                                            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                        <input type="number" name="harga_jual" class="form-input pl-12"
                                            value="{{ old('harga_jual', $barang->harga_jual) }}" required min="0"
                                            step="any" placeholder="0">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Harga jual ke pembeli</p>
                                    @error('harga_jual')
                                        <p class="text-red-500 text-sm mt-1 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-boxes text-purple-500 mr-2"></i>Stok Saat Ini
                                    </label>
                                    <div class="current-stock">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <div class="current-stock-value">{{ number_format($barang->stok) }}</div>
                                                <div class="text-sm text-gray-600 font-medium">
                                                    {{ ucfirst($barang->satuan) }}</div>
                                            </div>
                                            <div class="text-right">
                                                @if ($barang->stok == 0)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-times-circle mr-1"></i>Habis
                                                    </span>
                                                @elseif($barang->stok <= 10)
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>Sedikit
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>Tersedia
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="stock-actions">
                                            <a href="{{ route('pengurus.stok.formBarangMasuk', ['barang' => $barang->id]) }}"
                                                class="stock-action-btn success">
                                                <i class="fas fa-plus-circle"></i>
                                                Stok Masuk
                                            </a>
                                            <a href="{{ route('pengurus.stok.formBarangKeluar', ['barang' => $barang->id]) }}"
                                                class="stock-action-btn danger">
                                                <i class="fas fa-minus-circle"></i>
                                                Stok Keluar
                                            </a>
                                            <a href="{{ route('pengurus.stok.index', ['search_stok' => $barang->kode_barang]) }}"
                                                class="stock-action-btn primary">
                                                <i class="fas fa-history"></i>
                                                Riwayat
                                            </a>
                                        </div>

                                        <p class="text-xs text-gray-600 mt-3 flex items-center">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Perubahan stok dilakukan melalui menu Pencatatan Stok
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6 fade-in-up">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-align-left text-yellow-500 mr-2"></i>
                                Deskripsi Barang
                            </h3>

                            <div class="form-group">
                                <label for="deskripsi_barang_edit" class="form-label">Deskripsi (Opsional)</label>
                                <textarea id="deskripsi_barang_edit" name="deskripsi" rows="4" class="form-input resize-none"
                                    placeholder="Jelaskan detail barang, spesifikasi, atau informasi penting lainnya...">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <p class="text-red-500 text-sm mt-1 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Image Management -->
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 fade-in-up">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                                <i class="fas fa-camera text-indigo-500 mr-2"></i>
                                Kelola Gambar Barang
                            </h3>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Current Image -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                        <i class="fas fa-image text-blue-500 mr-2"></i>
                                        Gambar Saat Ini
                                    </h4>
                                    <!-- Di bagian current image -->
                                    <div class="current-image-container">
                                        <img id="currentBarangImage"
                                            src="{{ $barang->gambar_path ? asset('storage/' . $barang->gambar_path) : 'https://ui-avatars.com/api/?name=' . urlencode($barang->nama_barang) . '&background=random&color=fff&size=200&font-size=0.33&bold=true&rounded=false' }}"
                                            alt="Gambar {{ $barang->nama_barang }}"
                                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($barang->nama_barang) }}&background=random&color=fff&size=200&font-size=0.33&bold=true&rounded=false'">
                                        <div class="image-overlay">
                                            <div class="text-center text-white">
                                                <i class="fas fa-search-plus text-2xl mb-2"></i>
                                                <p class="text-sm font-medium">Klik untuk memperbesar</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($barang->gambar_path)
                                        <div class="delete-image-section mt-4">
                                            <label for="hapus_gambar_sekarang" class="flex items-start cursor-pointer">
                                                <input type="checkbox" id="hapus_gambar_sekarang"
                                                    name="hapus_gambar_sekarang" value="1"
                                                    class="delete-checkbox mt-0.5 mr-3">
                                                <div>
                                                    <span class="text-red-700 font-medium">Hapus gambar saat ini</span>
                                                    <p class="text-xs text-red-600 mt-1">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        Tindakan ini tidak dapat dibatalkan setelah menyimpan
                                                    </p>
                                                </div>
                                            </label>
                                        </div>
                                    @endif
                                </div>

                                <!-- Upload New Image -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                                        <i class="fas fa-upload text-green-500 mr-2"></i>
                                        Upload Gambar Baru (Opsional)
                                    </h4>

                                    <div class="image-upload-area" id="imageUploadArea">
                                        <input type="file" id="gambar_barang_input_edit_original"
                                            accept="image/png,image/jpeg,image/jpg,image/webp" class="visually-hidden">

                                        <div class="text-center">
                                            <div class="mb-4">
                                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                            </div>
                                            <label for="gambar_barang_input_edit_original"
                                                class="upload-button-barang cursor-pointer">
                                                <i class="fas fa-plus mr-2"></i>
                                                Pilih Gambar Baru
                                            </label>
                                            <p class="text-sm text-gray-600 mt-3">Format: JPG, PNG, WEBP</p>
                                            <p class="text-xs text-gray-500">Maksimal 2MB</p>
                                        </div>

                                        <div id="newImagePreviewContainer" class="hidden">
                                            <div class="new-image-preview">
                                                <img id="newImagePreview" src="#" alt="Preview Gambar Baru">
                                            </div>
                                            <p class="text-sm text-green-600 font-medium mt-2 text-center">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Gambar baru siap digunakan
                                            </p>
                                        </div>
                                    </div>

                                    <div id="fileInfoDisplay" class="mt-3 text-sm text-blue-600 font-medium hidden"></div>

                                    <button type="button" id="removeNewImageBtn"
                                        class="mt-3 text-sm text-red-500 hover:text-red-700 hover:underline hidden">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Hapus gambar baru
                                    </button>
                                </div>
                            </div>

                            <input type="hidden" name="cropped_gambar_barang" id="cropped_gambar_barang_data_edit">
                            @error('gambar_barang')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                            @error('cropped_gambar_barang')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6">
                            <a href="{{ route('pengurus.barang.index') }}" class="w-full sm:w-auto">
                                <button type="button" class="action-btn secondary w-full">
                                    <i class="fas fa-times"></i>
                                    <span>Batal</span>
                                </button>
                            </a>
                            <button type="submit" id="submitBtn" class="action-btn primary w-full sm:w-auto">
                                <i class="fas fa-save"></i>
                                <span>Update Barang</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cropping Modal -->
    <div id="cropGambarBarangModalEdit" class="fixed inset-0 modal-overlay hidden items-center justify-center z-50 p-4">
        <div class="modal-content animate-scale-in">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-crop-alt mr-3 text-blue-500"></i>
                        Sesuaikan Gambar Barang
                    </h3>
                    <button type="button" id="closeCropBarangModalBtnEdit"
                        class="text-gray-400 hover:text-gray-600 transition-colors text-2xl">
                        ×
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div class="img-container-cropper mb-6">
                    <img id="imageToCropBarangEdit" src="#" alt="Pratinjau Crop Barang">
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-center order-last sm:order-first">
                        <p class="text-sm font-medium text-gray-700 mb-2">Pratinjau:</p>
                        <div class="preview-barang-container-edit"></div>
                    </div>

                    <div class="flex space-x-3 w-full sm:w-auto">
                        <button type="button" id="cancelCropBarangModalBtnEdit"
                            class="flex-1 sm:flex-none bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-3 px-6 rounded-lg transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button type="button" id="applyCropBarangBtnEdit"
                            class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-all duration-300">
                            <i class="fas fa-check mr-2"></i>Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Zoom Modal -->
    <div id="imageZoomModal" class="fixed inset-0 modal-overlay hidden items-center justify-center z-50 p-4"
        onclick="closeImageZoom()">
        <div class="max-w-4xl max-h-full">
            <img id="zoomedImage" src="#" alt="Gambar Diperbesar"
                class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('.select2-basic').select2({
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            // Image cropping functionality
            const originalGambarEditInput = document.getElementById('gambar_barang_input_edit_original');
            const currentBarangImageEl = document.getElementById('currentBarangImage');
            const newImagePreview = document.getElementById('newImagePreview');
            const newImagePreviewContainer = document.getElementById('newImagePreviewContainer');
            const removeGambarEditBtn = document.querySelector('input[name="hapus_gambar_sekarang"]');
            const removeNewImageBtn = document.getElementById('removeNewImageBtn');
            const fileInfoDisplay = document.getElementById('fileInfoDisplay');
            const imageUploadArea = document.getElementById('imageUploadArea');

            const cropModalBarangEditEl = document.getElementById('cropGambarBarangModalEdit');
            const imageToCropBarangEditEl = document.getElementById('imageToCropBarangEdit');
            const closeCropBarangModalBtnEdit = document.getElementById('closeCropBarangModalBtnEdit');
            const cancelCropBarangModalBtnEdit = document.getElementById('cancelCropBarangModalBtnEdit');
            const applyCropBarangBtnEdit = document.getElementById('applyCropBarangBtnEdit');
            const croppedGambarBarangInputEdit = document.getElementById('cropped_gambar_barang_data_edit');

            let cropperBarangEditInstance;
            let originalBarangFileDetailsEdit = null;

            // Image zoom functionality
            const imageZoomModal = document.getElementById('imageZoomModal');
            const zoomedImage = document.getElementById('zoomedImage');

            if (currentBarangImageEl) {
                currentBarangImageEl.addEventListener('click', function() {
                    zoomedImage.src = this.src;
                    imageZoomModal.classList.remove('hidden');
                    imageZoomModal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                });
            }

            window.closeImageZoom = function() {
                imageZoomModal.classList.add('hidden');
                imageZoomModal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            };

            function openBarangCropModalEdit() {
                if (cropModalBarangEditEl) {
                    cropModalBarangEditEl.classList.remove('hidden');
                    cropModalBarangEditEl.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            }

            function hideBarangCropModalEdit() {
                if (cropModalBarangEditEl) {
                    cropModalBarangEditEl.classList.add('hidden');
                    cropModalBarangEditEl.classList.remove('flex');
                    document.body.style.overflow = 'auto';
                }
                if (cropperBarangEditInstance) {
                    cropperBarangEditInstance.destroy();
                    cropperBarangEditInstance = null;
                }
            }

            function resetNewImagePreview() {
                if (newImagePreviewContainer) newImagePreviewContainer.classList.add('hidden');
                if (removeNewImageBtn) removeNewImageBtn.classList.add('hidden');
                if (fileInfoDisplay) {
                    fileInfoDisplay.textContent = '';
                    fileInfoDisplay.classList.add('hidden');
                }
                if (croppedGambarBarangInputEdit) croppedGambarBarangInputEdit.value = '';
                if (originalGambarEditInput) originalGambarEditInput.value = '';
                if (imageUploadArea) imageUploadArea.classList.remove('has-image');
            }

            if (originalGambarEditInput) {
                originalGambarEditInput.addEventListener('change', function(event) {
                    const files = event.target.files;
                    if (files && files.length > 0) {
                        const file = files[0];

                        // Validate file size
                        if (file.size > 2 * 1024 * 1024) {
                            showNotification('Ukuran file maksimal 2MB', 'error');
                            this.value = '';
                            return;
                        }

                        // Validate file type
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                        if (!allowedTypes.includes(file.type)) {
                            showNotification('Format file harus JPG, PNG, atau WEBP', 'error');
                            this.value = '';
                            return;
                        }

                        originalBarangFileDetailsEdit = {
                            name: file.name,
                            type: file.type,
                            size: file.size
                        };

                        if (fileInfoDisplay) {
                            fileInfoDisplay.innerHTML = `
                        <i class="fas fa-file-image mr-1"></i>
                        ${originalBarangFileDetailsEdit.name} 
                        <span class="text-gray-500">(${(originalBarangFileDetailsEdit.size / 1024 / 1024).toFixed(2)} MB)</span>
                    `;
                            fileInfoDisplay.classList.remove('hidden');
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (imageToCropBarangEditEl) imageToCropBarangEditEl.src = e.target.result;
                            openBarangCropModalEdit();

                            if (cropperBarangEditInstance) cropperBarangEditInstance.destroy();

                            if (imageToCropBarangEditEl && imageToCropBarangEditEl.src &&
                                imageToCropBarangEditEl.src !== '#') {
                                cropperBarangEditInstance = new Cropper(imageToCropBarangEditEl, {
                                    aspectRatio: 4 / 3,
                                    viewMode: 1,
                                    preview: '.preview-barang-container-edit',
                                    responsive: true,
                                    restore: false,
                                    guides: true,
                                    center: true,
                                    highlight: false,
                                    cropBoxMovable: true,
                                    cropBoxResizable: true,
                                    toggleDragModeOnDblclick: false,
                                });
                            } else {
                                hideBarangCropModalEdit();
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            if (closeCropBarangModalBtnEdit) closeCropBarangModalBtnEdit.addEventListener('click',
                hideBarangCropModalEdit);
            if (cancelCropBarangModalBtnEdit) cancelCropBarangModalBtnEdit.addEventListener('click',
                hideBarangCropModalEdit);

            if (applyCropBarangBtnEdit) {
                applyCropBarangBtnEdit.addEventListener('click', function() {
                    if (cropperBarangEditInstance && originalBarangFileDetailsEdit) {
                        const canvas = cropperBarangEditInstance.getCroppedCanvas({
                            width: 800,
                            height: 600,
                            imageSmoothingQuality: 'high'
                        });

                        if (canvas) {
                            const croppedImageDataURL = canvas.toDataURL(originalBarangFileDetailsEdit
                                .type || 'image/jpeg', 0.9);

                            if (croppedGambarBarangInputEdit) croppedGambarBarangInputEdit.value =
                                croppedImageDataURL;
                            if (newImagePreview) newImagePreview.src = croppedImageDataURL;
                            if (newImagePreviewContainer) newImagePreviewContainer.classList.remove(
                                'hidden');
                            if (removeNewImageBtn) removeNewImageBtn.classList.remove('hidden');
                            if (imageUploadArea) imageUploadArea.classList.add('has-image');

                            // Uncheck "hapus gambar" if checked
                            if (removeGambarEditBtn && removeGambarEditBtn.checked) {
                                removeGambarEditBtn.checked = false;
                            }

                            hideBarangCropModalEdit();
                            showNotification('Gambar baru berhasil disesuaikan', 'success');
                        } else {
                            showNotification('Gagal memproses gambar', 'error');
                        }
                    } else {
                        showNotification('Pilih gambar terlebih dahulu', 'error');
                    }
                });
            }

            // Handle "hapus gambar" checkbox
            if (removeGambarEditBtn) {
                removeGambarEditBtn.addEventListener('change', function() {
                    if (this.checked) {
                        if (currentBarangImageEl) {
                            currentBarangImageEl.src = "{{ asset('img/placeholder_barang.png') }}";
                        }
                        // Reset new image if any
                        resetNewImagePreview();

                        showNotification('Gambar akan dihapus saat menyimpan', 'info');
                    } else {
                        if (currentBarangImageEl) {
                            currentBarangImageEl.src = "{{ $barang->gambar_url }}";
                        }
                    }
                });
            }

            // Handle remove new image
            if (removeNewImageBtn) {
                removeNewImageBtn.addEventListener('click', function() {
                    resetNewImagePreview();
                    showNotification('Gambar baru dihapus', 'info');
                });
            }

            // Form submission
            const form = document.getElementById('editBarangForm');
            const submitBtn = document.getElementById('submitBtn');

            if (form) {
                form.addEventListener('submit', function(e) {
                    const originalHtml = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                    submitBtn.disabled = true;

                    // Re-enable after timeout as fallback
                    setTimeout(() => {
                        submitBtn.innerHTML = originalHtml;
                        submitBtn.disabled = false;
                    }, 10000);
                });
            }
        });

        // Notification function
        function showNotification(message, type = 'info') {
            if (window.showNotification) {
                window.showNotification(message, type);
            } else {
                alert(message);
            }
        }
    </script>
@endpush
