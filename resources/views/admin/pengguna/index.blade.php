@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Koperasi')
@section('page-title', 'Manajemen Pengguna')
@section('page-subtitle', 'Kelola dan pantau pengguna sistem koperasi')

@push('styles')
<style>
    .user-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .user-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        animation: float 20s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .stats-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        min-height: 180px;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.7s ease;
    }
    
    .stats-card:hover::before {
        left: 100%;
    }
    
    .stats-card:hover {
        transform: translateY(-12px) scale(1.03);
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.25);
    }
    
    .user-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        min-height: 420px;
        display: flex;
        flex-direction: column;
    }
    
    .user-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .user-card:hover::after {
        opacity: 1;
    }
    
    .user-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border-color: rgba(99, 102, 241, 0.3);
    }
    
    .user-card-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 2rem;
        position: relative;
        z-index: 2;
    }
    
    .search-section {
        background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 50%, #c7d2fe 100%);
        border: 2px solid rgba(99, 102, 241, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .search-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.3) 50%, transparent 60%);
        transform: translateX(-100%);
        animation: shimmer 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .search-input {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(99, 102, 241, 0.2);
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        background: rgba(255, 255, 255, 1);
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1), 0 10px 25px -5px rgba(99, 102, 241, 0.2);
        transform: translateY(-2px);
    }
    
    .view-toggle {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(99, 102, 241, 0.2);
        border-radius: 16px;
        padding: 6px;
        display: flex;
        position: relative;
    }
    
    .view-toggle::before {
        content: '';
        position: absolute;
        top: 6px;
        left: 6px;
        width: calc(50% - 6px);
        height: calc(100% - 12px);
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-radius: 12px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px -2px rgba(99, 102, 241, 0.4);
    }
    
    .view-toggle[data-mode="table"]::before {
        transform: translateX(100%);
    }
    
    .view-toggle button {
        padding: 12px 20px;
        border-radius: 12px;
        transition: all 0.3s ease;
        font-weight: 600;
        position: relative;
        z-index: 2;
        flex: 1;
        text-align: center;
        border: none;
        background: transparent;
        cursor: pointer;
    }
    
    .view-toggle button.active {
        color: white;
    }
    
    .view-toggle button:not(.active) {
        color: #6b7280;
    }
    
    /* Enhanced Grid Layout */
    .user-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 2rem;
        padding: 2rem;
    }
    
    @media (max-width: 768px) {
        .user-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 1rem;
        }
        
        .user-card {
            min-height: 380px;
        }
    }
    
    @media (min-width: 1400px) {
        .user-grid {
            grid-template-columns: repeat(auto-fill, minmax(420px, 1fr));
        }
    }
    
    /* Enhanced Pagination */
    .enhanced-pagination {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-top: 2px solid #e2e8f0;
        padding: 2rem;
        border-radius: 0 0 1.5rem 1.5rem;
    }
    
    .pagination-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        align-items: center;
    }
    
    .pagination-info {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        padding: 1rem 2rem;
        border-radius: 1rem;
        border: 1px solid rgba(99, 102, 241, 0.2);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .pagination-info-text {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 500;
        text-align: center;
    }
    
    .pagination-info-numbers {
        font-weight: 700;
        color: #374151;
    }
    
    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .pagination-controls .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .pagination-controls .page-link::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .pagination-controls .page-link span {
        position: relative;
        z-index: 2;
    }
    
    .pagination-controls .page-link:not(.active):not(.disabled) {
        background: rgba(255, 255, 255, 0.8);
        color: #6b7280;
        border-color: #e5e7eb;
    }
    
    .pagination-controls .page-link:not(.active):not(.disabled):hover {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        border-color: #6366f1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px -2px rgba(99, 102, 241, 0.3);
    }
    
    .pagination-controls .page-link:not(.active):not(.disabled):hover::before {
        opacity: 0.1;
    }
    
    .pagination-controls .page-link.active {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        border-color: #6366f1;
        box-shadow: 0 4px 12px -2px rgba(99, 102, 241, 0.4);
        transform: translateY(-1px);
    }
    
    .pagination-controls .page-link.disabled {
        background: #f3f4f6;
        color: #9ca3af;
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .pagination-controls .page-link.prev,
    .pagination-controls .page-link.next {
        padding: 0.5rem 1rem;
        font-weight: 700;
    }
    
    .pagination-controls .page-link.prev:not(.disabled):hover,
    .pagination-controls .page-link.next:not(.disabled):hover {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
    }
    
    /* Table Styles */
    .data-table {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    .table-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table-header th {
        padding: 20px 24px;
        text-align: left;
        font-weight: 700;
        color: #374151;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .table-header th:last-child {
        text-align: center;
    }
    
    .table-body {
        background: white;
    }
    
    .table-row {
        position: relative;
        transition: all 0.3s ease;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .table-row:last-child {
        border-bottom: none;
    }
    
    .table-row:hover {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
    }
    
    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 12px;
    }
    
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .pulse-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #6366f1;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }
    
    .slide-in-up {
        animation: slideInUp 0.6s ease-out both;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .bounce-in {
        animation: bounceIn 0.8s ease-out;
    }
    
    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3); }
        50% { opacity: 1; transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { opacity: 1; transform: scale(1); }
    }
    
    .notification {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 1000;
        max-width: 400px;
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .progress-bar {
        height: 4px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
        overflow: hidden;
        margin-top: 8px;
    }
    
    .progress-fill {
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 2px;
        transition: width 0.1s linear;
    }

    .user-card-actions {
        position: relative;
        z-index: 10;
    }

    .search-loading {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Fix untuk view mode display */
    .content-container {
        min-height: 400px;
    }

    .grid-view, .table-view {
        display: none;
    }

    .grid-view.active, .table-view.active {
        display: block;
    }
    
    /* Empty State Improvements */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        position: relative;
    }
    
    .empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .empty-state-icon::before {
        content: '';
        position: absolute;
        inset: 0;
        background: conic-gradient(from 0deg, transparent, rgba(99, 102, 241, 0.2), transparent);
        animation: rotate 3s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Delete Modal Styles - Konsisten dengan show.blade.php */
    .delete-modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .delete-modal.show {
        opacity: 1;
        visibility: visible;
    }
    
    .delete-modal-content {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        max-width: 28rem;
        width: 90%;
        margin: 1rem;
        transform: scale(0.9) translateY(20px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .delete-modal.show .delete-modal-content {
        transform: scale(1) translateY(0);
    }
    
    .delete-modal-icon {
        width: 4rem;
        height: 4rem;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .delete-modal-icon::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle, rgba(239, 68, 68, 0.1) 0%, transparent 70%);
        animation: pulse-warning 2s ease-in-out infinite;
    }
    
    @keyframes pulse-warning {
        0%, 100% { opacity: 0.5; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.1); }
    }
    
    .delete-modal-icon svg {
        width: 1.5rem;
        height: 1.5rem;
        color: #ef4444;
        position: relative;
        z-index: 2;
    }
    
    .delete-modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    
    .delete-modal-message {
        color: #6b7280;
        margin-bottom: 2rem;
        text-align: center;
        line-height: 1.6;
    }
    
    .delete-modal-actions {
        display: flex;
        gap: 1rem;
    }
    
    .delete-modal-btn {
        flex: 1;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .delete-modal-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .delete-modal-btn:hover::before {
        opacity: 1;
    }
    
    .delete-modal-btn-cancel {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #374151;
    }
    
    .delete-modal-btn-cancel:hover {
        background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.1);
    }
    
    .delete-modal-btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .delete-modal-btn-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px -2px rgba(239, 68, 68, 0.4);
    }
</style>
@endpush

@section('content')
@include('layouts.partials._alerts')
<div class="space-y-8" x-data="userManager()" x-init="init()">
    <!-- Enhanced Hero Section -->
    <div class="user-hero rounded-3xl p-8 text-white shadow-2xl">
        <div class="relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="space-y-4">
                    <h1 class="text-4xl lg:text-5xl font-bold leading-tight">
                        Manajemen Pengguna
                        <span class="block text-2xl lg:text-3xl font-normal opacity-90 mt-2">
                            Kelola Pengguna dengan Efisien
                        </span>
                    </h1>
                    <p class="text-lg opacity-80 max-w-2xl">
                        Pantau dan kelola semua pengguna sistem koperasi Anda dalam satu dashboard yang terintegrasi
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="pulse-dot"></div>
                    <span class="text-sm font-medium opacity-90">Live Data</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Dashboard -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Total Pengguna -->
        <div class="stats-card rounded-3xl shadow-xl bounce-in" style="animation-delay: 0.1s">
            <div class="p-8 h-full flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-800" x-text="stats.total">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Pengguna</p>
                    <div class="flex items-center text-indigo-600 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        Pengguna Aktif
                    </div>
                    <div class="bg-blue-100 rounded-full h-2">
                        <div class="bg-blue-500 rounded-full h-2 w-[90%] animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Admin -->
        <div class="stats-card rounded-3xl shadow-xl bounce-in" style="animation-delay: 0.2s">
            <div class="p-8 h-full flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-800" x-text="stats.totalAdmin">{{ $stats['totalAdmin'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Admin</p>
                    <div class="flex items-center text-purple-600 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"/>
                        </svg>
                        Administrator
                    </div>
                    <div class="bg-purple-100 rounded-full h-2">
                        <div class="bg-purple-500 rounded-full h-2 w-full animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengguna Aktif -->
        <div class="stats-card rounded-3xl shadow-xl bounce-in" style="animation-delay: 0.3s">
            <div class="p-8 h-full flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-800" x-text="stats.totalActive">{{ $stats['totalActive'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Pengguna Aktif</p>
                    <div class="flex items-center text-emerald-600 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        Status Aktif
                    </div>
                    <div class="bg-emerald-100 rounded-full h-2">
                        <div class="bg-emerald-500 rounded-full h-2 w-[82%] animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengguna Baru -->
        <div class="stats-card rounded-3xl shadow-xl bounce-in" style="animation-delay: 0.4s">
            <div class="p-8 h-full flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-gray-800" x-text="stats.userBaru">{{ $stats['userBaru'] ?? 0 }}</div>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Pengguna Baru</p>
                    <div class="flex items-center text-amber-600 text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                        </svg>
                        30 Hari Terakhir
                    </div>
                    <div class="bg-amber-100 rounded-full h-2">
                        <div class="bg-amber-500 rounded-full h-2 w-[95%] animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Search and Filter Section -->
    <div class="search-section rounded-3xl p-8 shadow-xl">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
            <!-- Enhanced Search Input -->
            <div class="flex-1 max-w-2xl">
                <div class="relative">
                    <input type="text" 
                           x-model="searchTerm" 
                           @input.debounce.500ms="performSearch()"
                           placeholder="Cari pengguna berdasarkan nama, email, atau nomor anggota..." 
                           class="search-input w-full pl-16 pr-16 py-5 rounded-2xl text-lg font-medium placeholder-gray-500">
                    <div class="absolute left-5 top-1/2 transform -translate-y-1/2">
                        <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div x-show="isLoading" class="search-loading">
                        <svg class="w-6 h-6 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                    </div>
                    <button x-show="searchTerm.length > 0" 
                            @click="clearSearch()"
                            class="absolute right-5 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Enhanced Controls -->
            <div class="flex items-center space-x-6">
                <!-- View Toggle -->
                <div class="view-toggle" :data-mode="viewMode">
                    <button @click="setViewMode('grid')" :class="{ 'active': viewMode === 'grid' }" class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span>Grid</span>
                    </button>
                    <button @click="setViewMode('table')" :class="{ 'active': viewMode === 'table' }" class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        <span>Table</span>
                    </button>
                </div>

                <!-- Add Button -->
                <a href="{{ route('admin.manajemen-pengguna.create') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-2 transition-all duration-300 group">
                    <svg class="w-6 h-6 mr-3 group-hover:rotate-90 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                    </svg>
                    Tambah Pengguna
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Content Area -->
    <div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden content-container">
        <!-- Loading State -->
        <div x-show="isLoading" class="p-8">
            <div class="user-grid" x-show="viewMode === 'grid'">
                <template x-for="i in 6">
                    <div class="loading-skeleton h-96"></div>
                </template>
            </div>
            <div x-show="viewMode === 'table'" class="space-y-4">
                <template x-for="i in 8">
                    <div class="loading-skeleton h-16"></div>
                </template>
            </div>
        </div>

        <!-- Grid View -->
        <div class="grid-view" :class="{ 'active': viewMode === 'grid' && !isLoading }">
            <div id="userGrid" class="user-grid">
                @include('admin.pengguna.partials._user_grid_items', ['users' => $users])
            </div>
        </div>

        <!-- Table View -->
        <div class="table-view" :class="{ 'active': viewMode === 'table' && !isLoading }">
            <div class="data-table">
                <table class="w-full" id="userTable">
                    <thead class="table-header">
                        <tr>
                            <th class="text-left">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                    <span>Nama Pengguna</span>
                                </div>
                            </th>
                            <th class="text-left">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                    <span>Email</span>
                                </div>
                            </th>
                            <th class="text-left">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"/>
                                    </svg>
                                    <span>Role</span>
                                </div>
                            </th>
                            <th class="text-left">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    <span>Status</span>
                                </div>
                            </th>
                            <th class="text-left">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z"/>
                                    </svg>
                                    <span>Bergabung</span>
                                </div>
                            </th>
                            <th class="text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947z"/>
                                    </svg>
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody" class="table-body">
                        @include('admin.pengguna.partials._user_table_rows', ['users' => $users])
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Enhanced Pagination -->
        <div id="paginationLinksUser" class="enhanced-pagination">
            <div class="pagination-wrapper">
                <!-- Pagination Info -->
                <div class="pagination-info">
                    <div class="pagination-info-text">
                        Menampilkan 
                        <span class="pagination-info-numbers">{{ $users->firstItem() ?? 0 }}</span>
                        sampai 
                        <span class="pagination-info-numbers">{{ $users->lastItem() ?? 0 }}</span>
                        dari 
                        <span class="pagination-info-numbers">{{ $users->total() }}</span>
                        hasil
                    </div>
                </div>
                
                <!-- Pagination Controls -->
                <div class="pagination-controls">
                    @if ($users->onFirstPage())
                        <span class="page-link disabled prev">
                            <span>← Sebelumnya</span>
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="page-link prev">
                            <span>← Sebelumnya</span>
                        </a>
                    @endif

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span class="page-link active">
                                <span>{{ $page }}</span>
                            </span>
                        @else
                            <a href="{{ $url }}" class="page-link">
                                <span>{{ $page }}</span>
                            </a>
                        @endif
                    @endforeach

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="page-link next">
                            <span>Selanjutnya →</span>
                        </a>
                    @else
                        <span class="page-link disabled next">
                            <span>Selanjutnya →</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 max-w-md mx-4 transform transition-all duration-300 scale-95">
        <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6" id="deleteMessage">Apakah Anda yakin ingin menghapus pengguna ini?</p>
            <div class="flex space-x-4">
                <button id="cancelDelete" class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-xl font-semibold hover:bg-gray-300 transition-colors">
                    Batal
                </button>
                <button id="confirmDelete" class="flex-1 bg-red-500 text-white py-3 rounded-xl font-semibold hover:bg-red-600 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notification Container -->
<div id="notificationContainer"></div>

<script>
function userManager() {
    return {
        searchTerm: '{{ request('search', '') }}',
        viewMode: 'grid',
        isLoading: false,
        searchTimeout: null,
        deleteUrl: '',
        stats: {
            total: {{ $stats['total'] ?? 0 }},
            totalAdmin: {{ $stats['totalAdmin'] ?? 0 }},
            totalActive: {{ $stats['totalActive'] ?? 0 }},
            userBaru: {{ $stats['userBaru'] ?? 0 }}
        },
        
        init() {
            // Initialize view mode from localStorage
            this.viewMode = localStorage.getItem('userViewMode') || 'grid';
            
            // Watch for view mode changes
            this.$watch('viewMode', (value) => {
                localStorage.setItem('userViewMode', value);
                this.updateViewDisplay();
            });
            
            // Initialize view display
            this.updateViewDisplay();
            
            // Initialize delete modal handlers
            this.initializeDeleteModal();
            
            // Initialize tooltips and other interactive elements
            this.initializeInteractiveElements();
            
            // Add keyboard shortcuts
            this.setupKeyboardShortcuts();
        },
        
        updateViewDisplay() {
            // Update view toggle appearance
            const viewToggle = document.querySelector('.view-toggle');
            if (viewToggle) {
                viewToggle.setAttribute('data-mode', this.viewMode);
            }
            
            // Update button states
            const gridBtn = document.querySelector('.view-toggle button:first-child');
            const tableBtn = document.querySelector('.view-toggle button:last-child');
            
            if (gridBtn && tableBtn) {
                gridBtn.classList.toggle('active', this.viewMode === 'grid');
                tableBtn.classList.toggle('active', this.viewMode === 'table');
            }
        },
        
        setViewMode(mode) {
            this.viewMode = mode;
            this.showNotification(`Tampilan diubah ke ${mode === 'grid' ? 'Grid' : 'Tabel'}`, 'info');
        },
        
        clearSearch() {
            this.searchTerm = '';
            this.performSearch();
        },
        
        async performSearch() {
            // Clear existing timeout
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            // Set new timeout
            this.searchTimeout = setTimeout(async () => {
                await this.fetchData();
            }, 300);
        },
        
        async fetchData(page = 1) {
            this.isLoading = true;
            
            try {
                const url = new URL("{{ route('admin.manajemen-pengguna.index') }}");
                if (this.searchTerm.trim()) {
                    url.searchParams.append('search', this.searchTerm.trim());
                }
                url.searchParams.append('page', page.toString());
                url.searchParams.append('view_mode', this.viewMode);
                
                const response = await fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error('Server returned error response');
                }
                
                // Update content based on view mode
                if (this.viewMode === 'table' && data.html) {
                    const tableBody = document.getElementById('userTableBody');
                    if (tableBody) {
                        tableBody.innerHTML = data.html;
                    }
                } else if (this.viewMode === 'grid' && data.gridHtml) {
                    const gridContainer = document.getElementById('userGrid');
                    if (gridContainer) {
                        gridContainer.innerHTML = data.gridHtml;
                    }
                }
                
                // Update pagination
                const paginationContainer = document.getElementById('paginationLinksUser');
                if (paginationContainer && data.pagination) {
                    paginationContainer.innerHTML = data.pagination;
                }
                
                // Update stats
                if (data.stats) {
                    this.stats = data.stats;
                }
                
                // Show success message only for search
                if (this.searchTerm.trim()) {
                    this.showNotification(`Ditemukan ${data.total || 0} pengguna`, 'success');
                }
                
            } catch (error) {
                console.error('Error fetching data:', error);
                this.showNotification('Gagal memuat data. Silakan coba lagi.', 'error');
                
                // Show error state
                const errorHtml = `
                    <div class="text-center py-16">
                        <div class="text-red-500 text-lg font-medium mb-4">Gagal memuat data</div>
                        <button onclick="location.reload()" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                            Muat Ulang
                        </button>
                    </div>
                `;
                
                if (this.viewMode === 'table') {
                    const tableBody = document.getElementById('userTableBody');
                    if (tableBody) {
                        tableBody.innerHTML = `<tr><td colspan="6">${errorHtml}</td></tr>`;
                    }
                } else {
                    const gridContainer = document.getElementById('userGrid');
                    if (gridContainer) {
                        gridContainer.innerHTML = errorHtml;
                    }
                }
            } finally {
                this.isLoading = false;
            }
        },
        
        initializeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const cancelBtn = document.getElementById('cancelDelete');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            
            // Cancel button handler
            cancelBtn.addEventListener('click', () => {
                this.hideDeleteModal();
            });
            
            // Confirm button handler
            confirmBtn.addEventListener('click', () => {
                this.executeDelete();
            });
            
            // Close modal on outside click
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    this.hideDeleteModal();
                }
            });
            
            // Close modal on Escape key
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('show')) {
                    this.hideDeleteModal();
                }
            });
        },
        
        showDeleteModal(deleteUrl, userName) {
            this.deleteUrl = deleteUrl;
            const modal = document.getElementById('deleteModal');
            const message = document.getElementById('deleteMessage');
            
            message.textContent = `Apakah Anda yakin ingin menghapus pengguna "${userName}"? Tindakan ini tidak dapat diurungkan.`;
            
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        },
        
        hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('show');
            document.body.style.overflow = '';
            this.deleteUrl = '';
        },
        
        executeDelete() {
            if (!this.deleteUrl) return;
            
            try {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = this.deleteUrl;
                form.style.display = 'none';

                const csrfTokenInput = document.createElement('input');
                csrfTokenInput.type = 'hidden';
                csrfTokenInput.name = '_token';
                csrfTokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfTokenInput);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                
                this.hideDeleteModal();
                this.showNotification('Menghapus pengguna...', 'info');
                form.submit();
                
            } catch (error) {
                console.error('Error deleting user:', error);
                this.showNotification('Gagal menghapus pengguna', 'error');
                this.hideDeleteModal();
            }
        },
        
        confirmDelete(deleteUrl, userName) {
            this.showDeleteModal(deleteUrl, userName);
        },
        
        showNotification(message, type = 'info', duration = 4000) {
            const notification = document.createElement('div');
            notification.className = `notification bg-white border-l-4 rounded-lg shadow-xl p-6 ${
                type === 'success' ? 'border-green-500' : 
                type === 'error' ? 'border-red-500' : 
                type === 'warning' ? 'border-yellow-500' :
                'border-blue-500'
            }`;
            
            const iconColor = type === 'success' ? 'text-green-500' : 
                             type === 'error' ? 'text-red-500' : 
                             type === 'warning' ? 'text-yellow-500' :
                             'text-blue-500';
            
            const iconPath = type === 'success' ? 
                'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' :
                type === 'error' ?
                'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' :
                'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
            
            notification.innerHTML = `
                <div class='flex items-start space-x-4'>
                    <svg class='w-6 h-6 ${iconColor} flex-shrink-0 mt-0.5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='${iconPath}'/>
                    </svg>
                    <div class='flex-1'>
                        <p class='text-gray-800 font-medium'>${message}</p>
                        <div class='progress-bar mt-3'>
                            <div class='progress-fill' style='width: 100%'></div>
                        </div>
                    </div>
                    <button onclick='this.parentElement.parentElement.remove()' class='text-gray-400 hover:text-gray-600 transition-colors duration-200'>
                        <svg class='w-5 h-5' fill='currentColor' viewBox='0 0 20 20'>
                            <path fill-rule='evenodd' d='M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z'/>
                        </svg>
                    </button>
                </div>
            `;
            
            document.getElementById('notificationContainer').appendChild(notification);
            
            // Show notification
            setTimeout(() => notification.classList.add('show'), 100);
            
            // Auto hide
            const progressFill = notification.querySelector('.progress-fill');
            let width = 100;
            const interval = setInterval(() => {
                width -= (100 / (duration / 50));
                progressFill.style.width = width + '%';
                if (width <= 0) {
                    clearInterval(interval);
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 300);
                }
            }, 50);
        },
        
        setupKeyboardShortcuts() {
            document.addEventListener('keydown', (event) => {
                // Ctrl/Cmd + K to focus search
                if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
                    event.preventDefault();
                    const searchInput = document.querySelector('input[x-model="searchTerm"]');
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }
                
                // Ctrl/Cmd + G to toggle grid view
                if ((event.ctrlKey || event.metaKey) && event.key === 'g') {
                    event.preventDefault();
                    this.setViewMode('grid');
                }
                
                // Ctrl/Cmd + T to toggle table view
                if ((event.ctrlKey || event.metaKey) && event.key === 't') {
                    event.preventDefault();
                    this.setViewMode('table');
                }
                
                // Escape to clear search
                if (event.key === 'Escape') {
                    const searchInput = document.querySelector('input[x-model="searchTerm"]');
                    if (searchInput && searchInput === document.activeElement) {
                        this.clearSearch();
                        searchInput.blur();
                    }
                }
            });
        },
        
        initializeInteractiveElements() {
            // Initialize any additional interactive elements
            console.log('Interactive elements initialized');
        }
    }
}

// Global function for delete confirmation (accessible from table rows and grid items)
window.confirmDelete = function(deleteUrl, userName) {
    const alpineComponent = document.querySelector('[x-data]');
    if (alpineComponent && alpineComponent.__x) {
        alpineComponent.__x.$data.confirmDelete(deleteUrl, userName);
    }
};

// Global function for delete confirmation from grid items
window.confirmDeleteUser = function(deleteUrl, userName) {
    const alpineComponent = document.querySelector('[x-data]');
    if (alpineComponent && alpineComponent.__x) {
        alpineComponent.__x.$data.confirmDelete(deleteUrl, userName);
    }
};
</script>
@endsection

{{-- delete --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let deleteUrl = '';

    // Delete modal functions
    window.confirmDelete = function(url, userName) {
        deleteUrl = url;
        document.getElementById('deleteMessage').textContent = 
            `Apakah Anda yakin ingin menghapus pengguna "${userName}"? Tindakan ini tidak dapat diurungkan.`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    };

    document.getElementById('cancelDelete').addEventListener('click', function() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;
        form.style.display = 'none';
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        
        document.body.appendChild(form);
        form.submit();
    });

    // Close modal on outside click
    document.getElementById('deleteModal').addEventListener('click', function(event) {
        if (event.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced pagination handling
    document.getElementById('paginationLinksUser').addEventListener('click', function(event) {
        const target = event.target.closest('a');
        if (target && target.href && !target.classList.contains('disabled') && !target.querySelector('span[aria-disabled="true"]')) {
            event.preventDefault();
            
            // Extract page number from URL
            const url = new URL(target.href);
            const page = url.searchParams.get('page') || 1;
            
            // Get Alpine.js component and call fetchData
            const alpineComponent = document.querySelector('[x-data]');
            if (alpineComponent && alpineComponent.__x) {
                alpineComponent.__x.$data.fetchData(page);
            }
        }
    });
});

// Success/Error notifications from server
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        const alpineComponent = document.querySelector('[x-data]');
        if (alpineComponent && alpineComponent.__x) {
            setTimeout(() => {
                alpineComponent.__x.$data.showNotification('{{ session('success') }}', 'success');
            }, 500);
        }
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        const alpineComponent = document.querySelector('[x-data]');
        if (alpineComponent && alpineComponent.__x) {
            setTimeout(() => {
                alpineComponent.__x.$data.showNotification('{{ session('error') }}', 'error');
            }, 500);
        }
    });
@endif

@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        const alpineComponent = document.querySelector('[x-data]');
        if (alpineComponent && alpineComponent.__x) {
            setTimeout(() => {
                alpineComponent.__x.$data.showNotification('Terdapat kesalahan dalam input data', 'error');
            }, 500);
        }
    });
@endif
</script>
@endpush