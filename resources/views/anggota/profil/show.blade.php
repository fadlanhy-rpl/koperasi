{{-- resources/views/anggota/profil/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Saya - Koperasi')
@section('page-title', 'Profil Akun Saya')
@section('page-subtitle', 'Informasi pribadi dan keanggotaan Anda')

@push('styles')
<style>
    .profile-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .profile-hero {
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        background: white;
        border-radius: 32px;
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.258);
    }

    .profile-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .profile-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .profile-avatar-section {
        flex-shrink: 0;
    }

    .profile-avatar-wrapper {
        position: relative;
        display: inline-block;
    }

    .profile-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 6px solid rgba(6, 0, 0, 0.814);
        backdrop-filter: blur(10px);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        border-color: rgba(0, 0, 0, 0.488);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    .profile-initial {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        font-weight: 900;
        color: black;
        border: 6px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .profile-initial:hover {
        transform: scale(1.05);
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .profile-status-indicator {
        position: absolute;
        bottom: 8px;
        right: 8px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 4px solid black;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .status-active { background: #10b981; }
    .status-inactive { background: #ef4444; }

    .profile-info-section {
        flex: 1;
        color: black;
    }

    .profile-name {
        font-size: 3rem;
        font-weight: 900;
        margin: 0 0 0.5rem 0;
        line-height: 1.1;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .profile-role {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .profile-email {
        font-size: 1.1rem;
        opacity: 0.8;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .profile-stats {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 900;
        display: block;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
        margin-top: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .profile-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-primary {
        /* background: rgba(0, 0, 0, 0.458); */
        background: black;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 12px 24px;
        border-radius: 16px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
    }

    .btn-primary:hover {
        background: rgba(255, 255, 255, 0.25);
        /* border-color: rgba(255, 255, 255, 0.4); */
        border: solid black 2px;
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        color: black;
        text-decoration: none;
    }

    .btn-secondary {
        background: transparent;
        border: 2px solid black;
        color: black;
        padding: 12px 24px;
        border-radius: 16px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
    }

    .btn-secondary:hover {
        background: black;
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
        text-decoration: none;
    }

    .info-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .info-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    }

    .info-card:hover::before {
        transform: scaleX(1);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .card-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1f2937;
        margin: 0;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 16px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .info-item:hover {
        background: #f1f5f9;
        border-color: #e2e8f0;
        transform: translateX(4px);
    }

    .info-label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        color: #475569;
    }

    .info-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .icon-blue { background: #dbeafe; color: #1d4ed8; }
    .icon-green { background: #dcfce7; color: #16a34a; }
    .icon-purple { background: #e9d5ff; color: #9333ea; }
    .icon-yellow { background: #fef3c7; color: #d97706; }
    .icon-red { background: #fecaca; color: #dc2626; }
    .icon-indigo { background: #e0e7ff; color: #4f46e5; }
    .icon-pink { background: #fce7f3; color: #ec4899; }
    .icon-gray { background: #f3f4f6; color: #6b7280; }

    .info-value {
        font-weight: 700;
        color: #1f2937;
        text-align: right;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .status-badge.active {
        background: #dcfce7;
        color: #16a34a;
    }

    .status-badge.inactive {
        background: #fecaca;
        color: #dc2626;
    }

    .animate-fade-in {
        animation: fadeIn 0.8s ease-out;
    }

    .animate-slide-up {
        animation: slideUp 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .profile-hero-content {
            flex-direction: column;
            text-align: center;
            gap: 1.5rem;
        }

        .profile-stats {
            justify-content: center;
        }

        .info-cards-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 1rem 0.5rem;
        }

        .profile-hero {
            padding: 2rem 1rem;
            border-radius: 20px;
        }

        .profile-name {
            font-size: 2rem;
        }

        .profile-avatar,
        .profile-initial {
            width: 120px;
            height: 120px;
        }

        .profile-initial {
            font-size: 3rem;
        }

        .profile-stats {
            gap: 1rem;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .profile-actions {
            flex-direction: column;
            width: 100%;
        }

        .btn-primary,
        .btn-secondary {
            justify-content: center;
            width: 100%;
        }

        .info-card {
            padding: 1.5rem;
        }

        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .info-value {
            text-align: left;
        }
    }

    @media (max-width: 480px) {
        .profile-stats {
            flex-direction: column;
            gap: 0.5rem;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 0.75rem 1rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .stat-number,
        .stat-label {
            margin: 0;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-container animate-fade-in">
    <!-- Profile Hero Section -->
    <div class="profile-hero">
        <div class="profile-hero-content">
            <div class="profile-avatar-section">
                <div class="profile-avatar-wrapper">
                    @if($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                             alt="Foto Profil {{ $user->name }}" 
                             class="profile-avatar">
                    @else
                        <div class="profile-initial">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="profile-status-indicator {{ ($user->status ?? 'active') == 'active' ? 'status-active' : 'status-inactive' }}"></div>
                </div>
            </div>

            <div class="profile-info-section">
                <h1 class="profile-name">{{ $user->name }}</h1>
                <p class="profile-role">{{ ucfirst($user->role) }} Koperasi</p>
                <div class="profile-email">
                    <i class="fas fa-envelope"></i>
                    {{ $user->email }}
                </div>

                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $user->created_at->diffInDays(now()) }}</span>
                        <span class="stat-label">Hari Bergabung</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $user->age ?? 0 }}</span>
                        <span class="stat-label">Tahun</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ ($user->status ?? 'active') == 'active' ? '100' : '0' }}%</span>
                        <span class="stat-label">Status</span>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="{{ route('anggota.profil.edit') }}" class="btn-primary">
                        <i class="fas fa-edit"></i>
                        Edit Profil
                    </a>
                    <a href="{{ route('anggota.dashboard') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Cards -->
    <div class="info-cards-grid">
        <!-- Personal Information Card -->
        <div class="info-card animate-slide-up">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="card-title">Informasi Pribadi</h3>
            </div>

            <div class="info-list">
                <div class="info-item">
                    <div class="info-label">
                        <div class="info-icon icon-blue">
                            <i class="fas fa-user"></i>
                        </div>
                        Nama Lengkap
                    </div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <div class="info-icon icon-green">
                            <i class="fas fa-envelope"></i>
                        </div>
                        Email Address
                    </div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <div class="info-icon icon-yellow">
                            <i class="fas fa-birthday-cake"></i>
                        </div>
                        Tanggal Lahir
                    </div>
                    <div class="info-value">
                        {{ $user->date_of_birth ? $user->date_of_birth->isoFormat('DD MMMM YYYY') : 'Belum diatur' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <div class="info-icon icon-red">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        Usia Saat Ini
                    </div>
                    <div class="info-value">{{ $user->age ? $user->age . ' tahun' : 'Belum diatur' }}</div>
                </div>
            </div>
        </div>

        <!-- Membership Information Card -->
        <div class="info-card animate-slide-up" style="animation-delay: 0.2s;">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <h3 class="card-title">Informasi Keanggotaan</h3>
            </div>

            <div class="info-list">
                <div class="info-item">
                    <div class="info-label">
                        <div class="info-icon icon-purple">
                            <i class="fas fa-id-card"></i>
                        </div>
                        Nomor Anggota
                    </div>
                    <div class="info-value">{{ $user->nomor_anggota ?? 'Belum diatur' }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <div class="info-icon icon-indigo">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        Member Since
                    </div>
                    <div class="info-value">{{ $user->created_at->isoFormat('DD MMMM YYYY') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <div class="info-icon icon-pink">
                            <i class="fas fa-toggle-on"></i>
                        </div>
                        Status Akun
                    </div>
                    <div class="info-value">
                        <span class="status-badge {{ ($user->status ?? 'active') == 'active' ? 'active' : 'inactive' }}">
                            <i class="fas {{ ($user->status ?? 'active') == 'active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ ucfirst($user->status ?? 'Aktif') }}
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <div class="info-icon icon-gray">
                            <i class="fas fa-clock"></i>
                        </div>
                        Last Login
                    </div>
                    <div class="info-value">
                        {{ $user->last_login_at ? $user->last_login_at->isoFormat('DD MMMM YYYY, HH:mm') : 'Belum tercatat' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
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

    // Enhanced button interactions
    const buttons = document.querySelectorAll('.btn-primary, .btn-secondary');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Create ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe animated elements
    document.querySelectorAll('.animate-slide-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
        observer.observe(el);
    });

    // Staggered animation for info items
    const infoItems = document.querySelectorAll('.info-item');
    infoItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        item.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, index * 100 + 800);
    });

    // Profile avatar interaction
    const avatar = document.querySelector('.profile-avatar, .profile-initial');
    if (avatar) {
        avatar.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05) rotate(2deg)';
        });
        
        avatar.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    }

    // Add loading state for edit button
    const editButton = document.querySelector('.btn-primary');
    if (editButton) {
        editButton.addEventListener('click', function() {
            const icon = this.querySelector('i');
            const originalClass = icon.className;
            
            icon.className = 'fas fa-spinner fa-spin';
            this.style.pointerEvents = 'none';
            
            // Restore after navigation (fallback)
            setTimeout(() => {
                icon.className = originalClass;
                this.style.pointerEvents = 'auto';
            }, 2000);
        });
    }
});

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .btn-primary, .btn-secondary {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endpush