@extends('layouts.app')

@section('title', 'Dashboard Anggota - Koperasi')
@section('page-title', 'Dashboard Saya')
@section('page-subtitle', 'Informasi simpanan dan aktivitas Anda')

@push('styles')
    <style>
        /* .dashboard-container {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                position: relative;
                overflow: hidden;
            }

            .dashboard-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="20" cy="80" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                opacity: 0.3;
                pointer-events: none;
            }

            .dashboard-content {
                position: relative;
                z-index: 1;
                padding: 2rem 1rem;
            } */

        .welcome-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 32px;
            padding: 3rem 2rem;
            margin-bottom: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .welcome-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .welcome-title {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
            line-height: 1.1;
        }

        .welcome-subtitle {
            font-size: 1.25rem;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .enhanced-stats-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .enhanced-stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .enhanced-stats-card:hover::before {
            opacity: 1;
        }

        .enhanced-stats-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .stats-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .stats-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }

        .enhanced-stats-card:hover .stats-icon::before {
            left: 100%;
        }

        .stats-icon.blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
        }

        .stats-icon.green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        }

        .stats-icon.yellow {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
        }

        .stats-value {
            font-size: 2.5rem;
            font-weight: 900;
            color: #1f2937;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .stats-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #4b5563;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .stats-trend {
            font-size: 0.9rem;
            font-weight: 600;
            color: #6b7280;
            position: relative;
            z-index: 1;
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .action-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.8) 100%);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 2rem;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--card-color-start), var(--card-color-end));
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .action-card:hover::before {
            opacity: 0.1;
        }

        .action-card:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            color: white;
            text-decoration: none;
        }

        .action-card.green {
            --card-color-start: #10b981;
            --card-color-end: #059669;
        }

        .action-card.purple {
            --card-color-start: #8b5cf6;
            --card-color-end: #7c3aed;
        }

        .action-card.orange {
            --card-color-start: #f59e0b;
            --card-color-end: #d97706;
        }

        .action-card.blue {
            --card-color-start: #3b82f6;
            --card-color-end: #1d4ed8;
        }

        .action-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .action-card:hover .action-icon {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .action-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            color: white;
            transition: color 0.3s ease;
        }

        .action-card:hover .action-title {
            color: white;
        }

        .action-subtitle {
            font-size: 0.9rem;
            font-weight: 500;
            color: white;
            position: relative;
            z-index: 1;
            transition: color 0.3s ease;
        }

        .action-card:hover .action-subtitle {
            color: rgba(255, 255, 255, 0.9);
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .chart-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .chart-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #f59e0b, #10b981);
            background-size: 400% 100%;
            animation: gradient 3s ease infinite;
        }

        @keyframes gradient {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .chart-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .activity-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .activity-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: rgba(249, 250, 251, 0.8);
            border-radius: 16px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(229, 231, 235, 0.5);
            position: relative;
            overflow: hidden;
        }

        .activity-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .activity-item:hover::before {
            left: 100%;
        }

        .activity-item:hover {
            transform: translateX(8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.9);
        }

        .activity-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            position: relative;
            z-index: 1;
        }

        .activity-content {
            flex: 1;
            position: relative;
            z-index: 1;
        }

        .activity-description {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .activity-date {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .activity-amount {
            font-weight: 800;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }

        .summary-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .summary-header {
            padding: 2rem;
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
            background: linear-gradient(135deg, rgba(249, 250, 251, 0.8) 0%, rgba(243, 244, 246, 0.8) 100%);
        }

        .summary-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1f2937;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0;
        }

        .summary-item {
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            border-right: 1px solid rgba(229, 231, 235, 0.3);
            border-bottom: 1px solid rgba(229, 231, 235, 0.3);
        }

        .summary-item:hover {
            background: rgba(249, 250, 251, 0.8);
            transform: scale(1.05);
            z-index: 1;
        }

        .summary-item-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            position: relative;
            overflow: hidden;
        }

        .summary-item-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }

        .summary-item:hover .summary-item-icon::before {
            left: 100%;
        }

        .summary-value {
            font-size: 2rem;
            font-weight: 900;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .summary-label {
            font-size: 0.9rem;
            color: #6b7280;
            font-weight: 600;
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        .animate-slide-up {
            animation: slideUp 0.8s ease-out;
        }

        .animate-bounce-in {
            animation: bounceIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .welcome-title {
                font-size: 2rem;
            }

            .stats-value {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-content {
                padding: 1rem;
            }

            .welcome-section {
                padding: 2rem 1rem;
            }

            .welcome-title {
                font-size: 1.75rem;
            }

            .welcome-subtitle {
                font-size: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 16px;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }
    </style>
    <script src="https://kit.fontawesome.com/e164a56a3b.js" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="dashboard-container">

        <!-- Enhanced Welcome Section -->
        {{-- <div class="welcome-section animate-fade-in">
            <div class="welcome-content">
                <h1 class="welcome-title">Selamat Datang di Dashboard Anda</h1>
                <p class="welcome-subtitle">Kelola simpanan dan pantau aktivitas koperasi Anda dengan mudah</p>
                <div class="flex justify-center">
                    <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full text-white font-semibold shadow-lg">
                        <i class="fas fa-user-circle mr-2"></i>
                        Status: Anggota Aktif
                    </div>
                </div>
            </div>
        </div> --}}

        <div
            class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 rounded-3xl p-12 text-white relative overflow-hidden mb-8">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-48 translate-x-48"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-32 -translate-x-32"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-6 lg:mb-0">
                    <h1 class="text-4xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-blue-100 text-lg">Kelola simpanan dan pantau aktivitas koperasi Anda dengan mudah</p>
                    <div class="flex items-center mt-4 space-x-4">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-calendar-alt text-blue-200"></i>
                            <span class="text-blue-100" id="current-date"></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-clock text-blue-200"></i>
                            <span class="text-blue-100 font-mono" id="current-time"></span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-center lg:items-center justify-center">
                    <div
                        class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mb-4 animate-pulse-slow">
                        <i class="fa-solid fa-user  text-4xl text-yellow-300"></i>
                    </div>
                    <span class="text-blue-100 font-semibold">Student</span>
                </div>
            </div>
        </div>

        <!-- Enhanced Stats Cards -->
        <div class="stats-grid animate-slide-up">
            <div class="enhanced-stats-card" style="animation-delay: 0.1s">
                <div class="stats-icon blue">
                    <i class="fas fa-money-check-alt text-white text-2xl"></i>
                </div>
                <div class="stats-value" data-target="{{ $totalSimpananPokok }}">Rp 0</div>
                <div class="stats-title">Simpanan Pokok</div>
                <div class="stats-trend">
                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                    Lunas
                </div>
            </div>

            <div class="enhanced-stats-card" style="animation-delay: 0.2s">
                <div class="stats-icon green">
                    <i class="fas fa-calendar-check text-white text-2xl"></i>
                </div>
                <div class="stats-value" data-target="{{ $totalSimpananWajib }}">Rp 0</div>
                <div class="stats-title">Total Simpanan Wajib</div>
                <div class="stats-trend">
                    <i class="fas fa-chart-line text-green-500 mr-1"></i>
                    {{ $jumlahBulanBayarWajib }}x terbayar
                </div>
            </div>

            <div class="enhanced-stats-card" style="animation-delay: 0.3s">
                <div class="stats-icon yellow">
                    <i class="fas fa-hand-holding-usd text-white text-2xl"></i>
                </div>
                <div class="stats-value" data-target="{{ $saldoSimpananSukarela }}">Rp 0</div>
                <div class="stats-title">Saldo Simpanan Sukarela</div>
                <div class="stats-trend">
                    <i class="fas fa-arrow-down text-blue-500 mr-1"></i>
                    Dapat ditarik
                </div>
            </div>
        </div>

        <!-- Enhanced Quick Actions -->
        <div class="quick-actions-grid animate-slide-up" style="animation-delay: 0.4s">
            <a href="{{ route('anggota.pembelian.katalog') }}"
                class="action-card blue bg-gradient-to-br from-blue-500 to-blue-600">
                <div class="action-icon">
                    <i class="fas fa-store-alt text-white text-2xl"></i>
                </div>
                <div class="action-title">Belanja Barang</div>
                <div class="action-subtitle">Lihat katalog produk koperasi</div>
            </a>

            <a href="{{ route('anggota.simpanan.show') }}"
                class="action-card green bg-gradient-to-br from-green-500 to-green-600">
                <div class="action-icon">
                    <i class="fas fa-wallet text-white text-2xl"></i>
                </div>
                <div class="action-title">Rincian Simpanan</div>
                <div class="action-subtitle">Lihat detail simpanan Anda</div>
            </a>

            <a href="{{ route('anggota.pembelian.riwayat') }}"
                class="action-card purple bg-gradient-to-br from-purple-500 to-purple-600">
                <div class="action-icon">
                    <i class="fas fa-receipt text-white text-2xl"></i>
                </div>
                <div class="action-title">Riwayat Pembelian</div>
                <div class="action-subtitle">Cek transaksi belanja</div>
            </a>

            <a href="{{ route('anggota.profil.show') }}"
                class="action-card orange bg-gradient-to-br from-orange-500 to-orange-600">
                <div class="action-icon">
                    <i class="fas fa-user-edit text-white text-2xl"></i>
                </div>
                <div class="action-title">Profil Saya</div>
                <div class="action-subtitle">Kelola akun Anda</div>
            </a>
        </div>

        <!-- Enhanced Content Grid -->
        <div class="content-grid animate-slide-up" style="animation-delay: 0.5s">
            <!-- Enhanced Chart Container -->
            <div class="chart-container">
                <h3 class="chart-title">
                    <i class="fas fa-chart-line mr-3 text-blue-500"></i>
                    Grafik Simpanan (6 Bulan Terakhir)
                </h3>
                <div class="h-64 md:h-72 relative">
                    <canvas id="memberSavingsChart"></canvas>
                </div>
            </div>

            <!-- Enhanced Activity Container -->
            <div class="activity-container">
                <h3 class="activity-title">
                    <i class="fas fa-history mr-3 text-purple-500"></i>
                    Aktivitas Terbaru Anda
                </h3>
                <div class="space-y-3 max-h-72 overflow-y-auto">
                    @forelse($aktivitasTerbaru as $aktivitas)
                        <div class="activity-item">
                            <div class="activity-icon bg-{{ $aktivitas->icon_bg }}-100">
                                <i class="fas fa-{{ $aktivitas->icon }} text-{{ $aktivitas->icon_bg }}-600"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-description">{{ $aktivitas->deskripsi }}</p>
                                <p class="activity-date">{{ $aktivitas->tanggal_format }}</p>
                            </div>
                            <span class="activity-amount text-{{ $aktivitas->icon_bg }}-600">
                                @rupiah($aktivitas->jumlah)
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-history text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada aktivitas terbaru</p>
                            <p class="text-gray-400 text-sm mt-1">Aktivitas Anda akan muncul di sini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Enhanced Summary Container -->
        <div class="summary-container animate-slide-up" style="animation-delay: 0.6s">
            <div class="summary-header">
                <h3 class="summary-title">
                    <i class="fas fa-chart-pie mr-3 text-indigo-500"></i>
                    Ringkasan Keanggotaan
                </h3>
            </div>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-item-icon bg-blue-100">
                        <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                    </div>
                    <p class="summary-value">{{ $lamaKeanggotaanBulan }}</p>
                    <p class="summary-label">Bulan Bergabung</p>
                </div>

                <div class="summary-item">
                    <div class="summary-item-icon bg-green-100">
                        <i class="fas fa-shopping-bag text-green-600 text-2xl"></i>
                    </div>
                    <p class="summary-value">{{ $totalPembelianCount }}</p>
                    <p class="summary-label">Total Pembelian</p>
                </div>

                <div class="summary-item">
                    <div class="summary-item-icon bg-yellow-100">
                        <i class="fas fa-coins text-yellow-600 text-2xl"></i>
                    </div>
                    <p class="summary-value" data-currency="{{ $totalSemuaSimpanan }}">@rupiah($totalSemuaSimpanan)</p>
                    <p class="summary-label">Total Simpanan</p>
                </div>

                <div class="summary-item">
                    <div class="summary-item-icon bg-purple-100">
                        <i class="fas fa-user-check text-purple-600 text-2xl"></i>
                    </div>
                    <p class="summary-value">{{ $statusKeanggotaan }}</p>
                    <p class="summary-label">Status Keanggotaan</p>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function updateDateTime() {
                const now = new Date();
                const dateOptions = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const timeOptions = {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                };

                document.getElementById('current-date').textContent = now.toLocaleDateString('id-ID', dateOptions);
                document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID', timeOptions);
            }

            updateDateTime();
            setInterval(updateDateTime, 1000);

            // Enhanced counter animation for stats
            function animateCounter(element, target, duration = 2000) {
                const start = 0;
                const increment = target / (duration / 16);
                let current = start;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }

                    // Format as currency
                    element.textContent = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(current);
                }, 16);
            }

            // Animate all stats values
            const statsValues = document.querySelectorAll('.stats-value[data-target]');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = parseInt(entry.target.dataset.target);
                        animateCounter(entry.target, target);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.5
            });

            statsValues.forEach(el => observer.observe(el));

            // Enhanced Chart.js configuration
            const memberSavingsCtx = document.getElementById('memberSavingsChart');
            if (memberSavingsCtx) {
                const chartData = {
                    labels: @json($dataSavingsChart['labels'] ?? []),
                    datasets: [{
                            label: 'Simpanan Wajib',
                            data: @json($dataSavingsChart['wajib'] ?? []),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 3,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            borderWidth: 3
                        },
                        {
                            label: 'Setoran Simp. Sukarela',
                            data: @json($dataSavingsChart['sukarela'] ?? []),
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#f59e0b',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 3,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            borderWidth: 3
                        }
                    ]
                };

                new Chart(memberSavingsCtx.getContext('2d'), {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    color: '#6B7280',
                                    font: {
                                        size: 14,
                                        weight: '600'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                titleColor: '#1f2937',
                                bodyColor: '#374151',
                                borderColor: '#e5e7eb',
                                borderWidth: 1,
                                cornerRadius: 12,
                                padding: 12,
                                displayColors: true,
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' +
                                            new Intl.NumberFormat('id-ID', {
                                                style: 'currency',
                                                currency: 'IDR',
                                                minimumFractionDigits: 0
                                            }).format(context.parsed.y);
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    },
                                    callback: function(value) {
                                        return new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            minimumFractionDigits: 0,
                                            notation: 'compact'
                                        }).format(value);
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            }
                        },
                        elements: {
                            point: {
                                hoverBackgroundColor: '#ffffff'
                            }
                        }
                    }
                });
            }

            // Add smooth scrolling for better UX
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add loading states for action cards
            document.querySelectorAll('.action-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    // Add loading state
                    const icon = this.querySelector('.action-icon i');
                    const originalClass = icon.className;
                    icon.className = 'fas fa-spinner fa-spin text-white text-2xl';

                    // Restore after navigation (fallback)
                    setTimeout(() => {
                        icon.className = originalClass;
                    }, 2000);
                });
            });

            // Enhanced intersection observer for staggered animations
            const animatedElements = document.querySelectorAll('.animate-slide-up, .animate-fade-in');
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        animationObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            animatedElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                animationObserver.observe(el);
            });

            // Add parallax effect to background
            window.addEventListener('scroll', () => {
                const scrolled = window.pageYOffset;
                const parallax = document.querySelector('.dashboard-container::before');
                if (parallax) {
                    const speed = scrolled * 0.5;
                    parallax.style.transform = `translateY(${speed}px)`;
                }
            });
        });
    </script>
@endpush
