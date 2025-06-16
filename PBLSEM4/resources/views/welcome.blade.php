@extends('layouts.template')
@push('css')
<style>
    /* Animation keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }

    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }

    /* DASHBOARD PESERTA */
    /* Main container styling */
    .info-section {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-top: 2rem;
        box-shadow: 0 10px 30px rgba(0, 123, 255, 0.1);
        position: relative;
        overflow: hidden;
    }

    .info-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #007bff, #0056b3, #007bff);
        background-size: 200% 100%;
        animation: gradient-shift 3s ease infinite;
    }

     /* Header styling */
    .section-header {
        text-align: center;
        margin-bottom: 2.5rem;
        animation: fadeInUp 0.8s ease-out;
    }

    .section-title {
        font-size: 2.2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #007bff, #0056b3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .section-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        font-weight: 400;
    }

    /* Card styling */
    .info-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.08);
        border: none;
        margin-bottom: 1.5rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, #007bff, #0056b3);
    }

    .info-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 123, 255, 0.15);
    }

    .card-body-custom {
        padding: 2rem;
        position: relative;
        z-index: 2;
    }

    /* Title styling */
    .info-title {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        padding: 12px 20px;
        border-radius: 50px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .info-title::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .info-card:hover .info-title::before {
        left: 100%;
    }

    .info-title i {
        margin-right: 8px;
        font-size: 1.1rem;
    }

    /* Content styling */
    .info-content {
        color: #495057;
        font-size: 1.15rem;
        line-height: 1.6;
        margin: 0;
        font-weight: 400;
    }

    /* Empty state styling */
    .empty-state {
        text-align: center;
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        border: none;
        border-radius: 16px;
        padding: 3rem 2rem;
        box-shadow: 0 8px 25px rgba(255, 193, 7, 0.1);
        animation: pulse 2s infinite;
    }

    .empty-state i {
        font-size: 3rem;
        color: #856404;
        margin-bottom: 1rem;
        display: block;
    }

    .empty-state-text {
        color: #856404;
        font-size: 1.2rem;
        font-weight: 500;
        margin: 0;
    }

    /* Animation classes */
    .animate-card {
        animation: slideInLeft 0.8s ease-out backwards;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .section-title {
            font-size: 1.8rem;
        }
        
        .info-section {
            padding: 1.5rem;
            margin-top: 1rem;
        }
        
        .card-body-custom {
            padding: 1.5rem;
        }
        
        .info-title {
            font-size: 0.9rem;
            padding: 10px 16px;
        }
        
        .info-content {
            font-size: 1rem;
        }
    }

    /* Additional enhancements */
    .icon-bounce {
        animation: bounce 2s infinite;
    }

    .welcome-section {
        animation: slideInLeft 0.8s ease-out 0.2s backwards;
    }
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
    }

    /* Dashboard Card Styling */
    .dashboard-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 123, 255, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
        position: relative;
        animation: fadeInUp 0.8s ease-out;
    }

    /* Header Styling */
    .dashboard-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    .header-content {
        display: flex;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .dashboard-icon {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 1rem;
        margin-right: 1rem;
        backdrop-filter: blur(10px);
    }

    .dashboard-icon i {
        font-size: 1.5rem;
        color: white;
    }

    .dashboard-title {
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header-decoration {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ffd700, #ff6b6b, #4ecdc4, #45b7d1);
        background-size: 400% 100%;
        animation: gradient-shift 3s ease infinite;
    }

    /* Body Styling */
    .dashboard-body {
        padding: 2.5rem;
    }

    /* Profile Welcome */
    .profile-welcome {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
        border-radius: 16px;
        border-left: 5px solid #007bff;
        position: relative;
        overflow: hidden;
        animation: slideInLeft 0.8s ease-out 0.2s backwards;
    }

    .profile-welcome::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.05));
        transform: skewX(-15deg);
    }

    .profile-avatar {
        background: linear-gradient(135deg, #007bff, #0056b3);
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.5rem;
        box-shadow: 0 8px 20px rgba(0, 123, 255, 0.3);
        position: relative;
        z-index: 2;
    }

    .profile-avatar i {
        font-size: 1.5rem;
        color: white;
    }

    .welcome-content {
        flex: 1;
        position: relative;
        z-index: 2;
    }

    .welcome-greeting {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .user-name {
        background: linear-gradient(135deg, #007bff, #0056b3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: capitalize;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        transition: all 0.3s ease;
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .role-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 123, 255, 0.3);
    }

   /* Admin Statistics Section */  

    .admin-stats-section {  
        margin-top: 2rem;  
        animation: fadeInUp 0.8s ease-out 0.4s backwards;  
    }  

    .section-header {  
        text-align: center;  
        margin-bottom: 2rem;  
    }  

    .section-title {  
        font-size: 1.7rem;  
        font-weight: 700;  
        background: linear-gradient(135deg, #007bff, #0056b3);  
        -webkit-background-clip: text;  
        -webkit-text-fill-color: transparent;  
        background-clip: text;  
        margin-bottom: 0.2rem;  
        display: flex;  
        align-items: center;  
        justify-content: center;  
        gap: 0.5rem;  
    }  

    .section-subtitle {  
        color: #6c757d;  
        font-size: 1rem;  
        font-weight: 300;  
    }  

    /* Modern Statistics Cards - userStats */  
    .stats-grid {  
        display: grid;  
        /* Mengurangi minmax agar kartu bisa lebih kecil */
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); 
        gap: 1rem; /* Mengurangi gap antar kartu */
        margin-bottom: 3rem;  
    }  

    .stat-card {  
        background: white;  
        border-radius: 16px;  
        padding: 1rem; /* Mengurangi padding secara signifikan */
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.08);  
        border: none;  
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);  
        position: relative;  
        overflow: hidden;  
        animation: slideInLeft 0.6s ease-out backwards;  
    }  

    .stat-card:nth-child(1) { animation-delay: 0.1s; }  
    .stat-card:nth-child(2) { animation-delay: 0.2s; }  
    .stat-card:nth-child(3) { animation-delay: 0.3s; }  
    .stat-card:nth-child(4) { animation-delay: 0.4s; }  
    .stat-card:nth-child(5) { animation-delay: 0.5s; }  
    .stat-card:nth-child(6) { animation-delay: 0.6s; }  

    .stat-card::before {  
        content: '';  
        position: absolute;  
        top: 0;  
        left: 0;  
        width: 5px;  
        height: 100%;  
        transition: width 0.3s ease;  
    }  

    .stat-card:hover {  
        transform: translateY(-5px);  
        box-shadow: 0 15px 35px rgba(0, 123, 255, 0.15);  
    }  

    .stat-card:hover::before {  
        width: 100%;  
        opacity: 0.1;  
    }  

    .stat-card.primary::before { background: linear-gradient(180deg, #007bff, #0056b3); }  
    .stat-card.dark::before { background: linear-gradient(180deg, #343a40, #23272b); }  
    .stat-card.success::before { background: linear-gradient(180deg, #28a745, #1e7e34); }  
    .stat-card.info::before { background: linear-gradient(180deg, #17a2b8, #117a8b); }  
    .stat-card.warning::before { background: linear-gradient(180deg, #ffc107, #e0a800); }  
    .stat-card.secondary::before { background: linear-gradient(180deg, #6c757d, #5a6268); }
    .stat-card.danger::before { background: linear-gradient(180deg, #dc3545, #bd2130); }  

    .stat-header {  
        display: flex;  
        align-items: center;  
        justify-content: space-between;  
        margin-bottom: 0.5rem; /* Mengurangi margin-bottom */
    }  

    .stat-icon {  
        width: 40px; /* Mengurangi ukuran ikon */
        height: 40px; /* Mengurangi ukuran ikon */
        border-radius: 10px; /* Menyesuaikan border-radius */
        display: flex;  
        align-items: center;  
        justify-content: center;  
        color: white;  
        font-size: 1rem; /* Mengurangi ukuran font ikon */
        transition: transform 0.3s ease;  
    }  

    .stat-card:hover .stat-icon {  
        transform: scale(1.1);  
    }  

    .stat-icon.primary { background: linear-gradient(135deg, #007bff, #0056b3); }  
    .stat-icon.dark { background: linear-gradient(135deg, #343a40, #23272b); }  
    .stat-icon.success { background: linear-gradient(135deg, #28a745, #1e7e34); }  
    .stat-icon.info { background: linear-gradient(135deg, #17a2b8, #117a8b); }  
    .stat-icon.warning { background: linear-gradient(135deg, #ffc107, #e0a800); color: #212529; }  
    .stat-icon.secondary { background: linear-gradient(135deg, #6c757d, #5a6268); }
    .stat-icon.danger { background: linear-gradient(135deg, #dc3545, #bd2130); }

    .stat-title {  
        font-size: 0.8rem; /* Mengurangi ukuran font title */
        font-weight: 600;  
        color: #6c757d;  
        text-transform: uppercase;  
        letter-spacing: 0.5px;  
        margin-bottom: 0.3rem; /* Mengurangi margin-bottom */
    }  

    .stat-value {  
        font-size: 1.6rem; /* Mengurangi ukuran font value */
        font-weight: 700;  
        color: #2c3e50;  
        margin: 0;  
    }  

    /* Status Cards Section - statusStat */  
    .status-section {  
        margin-top: 2rem; /* Sedikit mengurangi margin top */
        animation: fadeInUp 0.8s ease-out 0.6s backwards;  
    }  

    .status-grid {  
        display: grid;  
        /* Mengurangi minmax agar kartu bisa lebih kecil */
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); 
        gap: 0.8rem; /* Mengurangi gap antar kartu status */
        margin-bottom: 3rem;  
    }  

    .status-card {  
        background: white;  
        border-radius: 12px; /* Sedikit mengurangi border-radius */
        padding: 0.8rem; /* Mengurangi padding secara signifikan */
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.06);  
        transition: all 0.3s ease;  
        position: relative;  
        overflow: hidden;  
        animation: slideInLeft 0.6s ease-out backwards;  
    }  

    .status-card:nth-child(1) { animation-delay: 0.7s; }  
    .status-card:nth-child(2) { animation-delay: 0.8s; }  
    .status-card:nth-child(3) { animation-delay: 0.9s; }  
    .status-card:nth-child(4) { animation-delay: 1.0s; }  
    .status-card:nth-child(5) { animation-delay: 1.1s; }  

    .status-card:hover {  
        transform: translateY(-3px);  
        box-shadow: 0 10px 25px rgba(0, 123, 255, 0.12);  
    }  

    /* Charts Section */  
    .charts-section {  
        margin-top: 3rem;  
        animation: fadeInUp 0.8s ease-out 0.8s backwards;  
    }  

    .chart-card {  
        background: white;  
        border-radius: 16px;  
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.08);  
        overflow: hidden;  
        margin-bottom: 2rem;  
        transition: all 0.3s ease;  
    }  

    .chart-card:hover {  
        transform: translateY(-2px);  
        box-shadow: 0 12px 30px rgba(0, 123, 255, 0.12);  
    }  

    .chart-header {  
        background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);  
        padding: 1.5rem;  
        border-bottom: 1px solid #e9ecef;  
    }  

    .chart-title {  
        font-size: 1.2rem;  
        font-weight: 600;  
        color: #2c3e50;  
        margin: 0;  
        display: flex;  
        align-items: center;  
        gap: 0.5rem;  
    }  

    .chart-body {  
        padding: 2rem;  
    }  

    /* Quick Actions Section */  
    .quick-actions {  
        margin-top: 3rem;  
        animation: fadeInUp 0.8s ease-out 1s backwards;  
    }  

    .actions-grid {  
        display: grid;  
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));  
        gap: 1.5rem;  
    }  

    .action-card {  
        background: white;  
        border-radius: 14px;  
        padding: 1.5rem;  
        text-align: center;  
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.06);  
        transition: all 0.3s ease;  
        text-decoration: none;  
        color: inherit;  
        border: 2px solid transparent;  
        position: relative;  
        overflow: hidden;  
    }  

    .action-card::before {  
        content: '';  
        position: absolute;  
        top: -50%;  
        left: -50%;  
        width: 200%;  
        height: 200%;  
        background: linear-gradient(45deg, transparent, rgba(0, 123, 255, 0.05), transparent);  
        transform: rotate(-45deg);  
        transition: all 0.5s ease;  
        opacity: 0;  
    }  

    .action-card:hover::before {  
        opacity: 1;  
        transform: rotate(-45deg) translate(50%, 50%);  
    }  

    .action-card:hover {  
        transform: translateY(-5px);  
        box-shadow: 0 12px 30px rgba(0, 123, 255, 0.15);  
        border-color: #007bff;  
        text-decoration: none;  
        color: inherit;  
    }  

    .action-icon {  
        width: 60px;  
        height: 60px;  
        border-radius: 50%;  
        background: linear-gradient(135deg, #007bff, #0056b3);  
        color: white;  
        display: flex;  
        align-items: center;  
        justify-content: center;  
        margin: 0 auto 1rem;  
        font-size: 1.5rem;  
        transition: transform 0.3s ease;  
        position: relative;  
        z-index: 2;  
    }  

    .action-card:hover .action-icon {  
        transform: scale(1.1);  
    }  

    .action-title {  
        font-size: 1rem;  
        font-weight: 600;  
        color: #2c3e50;  
        margin: 0;  
        position: relative;  
        z-index: 2;  
    }  

    /* Responsive Design */  
    @media (max-width: 768px) {  
        .dashboard-header {  
            padding: 1.5rem;  
        }  
        
        .dashboard-title {  
            font-size: 1.5rem;  
        }  
        
        .dashboard-body {  
            padding: 1.5rem;  
        }  
        
        .profile-welcome {  
            flex-direction: column;  
            text-align: center;  
            padding: 1.5rem 1rem;  
        }  
        
        .profile-avatar {  
            margin-right: 0;  
            margin-bottom: 1rem;  
        }  
        
        .welcome-greeting {  
            font-size: 1.3rem;  
        }  

        .section-title {  
            font-size: 1rem;  
        }  

        .stats-grid {  
            /* Mengurangi minmax lebih lanjut untuk mobile */
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); 
            gap: 0.8rem; /* Mengurangi gap untuk mobile */
        }  

        .stat-card {  
            padding: 0.8rem; /* Mengurangi padding untuk mobile */
        }  

        .stat-value {  
            font-size: 1.3rem; /* Mengurangi font-size untuk mobile */
        }  

        .stat-title {
            font-size: 0.7rem; /* Mengurangi font-size title untuk mobile */
        }

        .stat-icon {
            width: 35px; /* Mengurangi ukuran ikon untuk mobile */
            height: 35px; /* Mengurangi ukuran ikon untuk mobile */
            font-size: 0.9rem; /* Mengurangi font-size ikon untuk mobile */
        }

        .status-grid {
            /* Mengurangi minmax lebih lanjut untuk mobile */
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); 
            gap: 0.6rem; /* Mengurangi gap untuk mobile */
        }

        .status-card {
            padding: 0.6rem; /* Mengurangi padding untuk mobile */
        }

        .chart-body {  
            padding: 1rem;  
        }  
    }
    
    /* Animation keyframes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
@endpush

@section('content')

    <div class="dashboard-card">
        <div class="dashboard-header">
            <div class="header-content">
                <div class="dashboard-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <h3 class="dashboard-title">Dashboard</h3>
            </div>
            <div class="header-decoration"></div>
        </div>

        @php
            $role = $user->role;
            switch ($role) {
                case 'admin':
                    $nama = $user->admin_nama ?? $user->nama_lengkap;
                    $roleColor = "bg-gradient-danger";
                    $roleIcon = "fas fa-user-shield";
                    break;
                case 'mahasiswa':
                    $nama = $user->mahasiswa_nama ?? $user->nama_lengkap;
                    $roleColor = "bg-gradient-primary";
                    $roleIcon = "fas fa-user-graduate";
                    break;
                case 'dosen':
                    $nama = $user->dosen_nama ?? $user->nama_lengkap;
                    $roleColor = "bg-gradient-success";
                    $roleIcon = "fas fa-chalkboard-teacher";
                    break;
                case 'tendik':
                    $nama = $user->tendik_nama ?? $user->nama_lengkap;
                    $roleColor = "bg-gradient-warning";
                    $roleIcon = "fas fa-user-tie";
                    break;
                default:
                    $nama = $user->nama_lengkap;
                    $roleColor = "bg-gradient-secondary";
                    $roleIcon = "fas fa-user";
            }
        @endphp

        <div class="dashboard-body">
            <!-- Profile Section -->
            <div class="profile-welcome">
                <div class="profile-avatar">
                    <i class="{{ $roleIcon }}"></i>
                </div>
                <div class="welcome-content">
                    <h4 class="welcome-greeting">
                        Selamat datang, <span class="user-name">{{ $nama }}</span>!
                    </h4>
                    <div class="role-badge {{ $roleColor }}">
                        <i class="{{ $roleIcon }} mr-2"></i>
                        <span class="role-text">{{ ucfirst($role) }}</span>
                    </div>
                </div>
            </div>

            @if(isset($user) && $user->role === 'admin')
                <!-- Admin Statistics Section -->
                <div class="admin-stats-section">
                    <div class="section-header">
                        <h3 class="section-title">
                            <i class="fas fa-users"></i>
                            Statistik Pengguna
                        </h3>
                        <p class="section-subtitle">Ringkasan data pengguna sistem</p>
                    </div>

                    @php
                        $userStats = [
                            ['title' => 'Total User', 'count_var' => 'jumlah_user', 'icon' => 'fa-users', 'color' => 'primary'],
                            ['title' => 'Admin', 'count_var' => 'jumlah_admin', 'icon' => 'fa-user-shield', 'color' => 'dark'],
                            ['title' => 'Mahasiswa', 'count_var' => 'jumlah_mahasiswa', 'icon' => 'fa-user-graduate', 'color' => 'success'],
                            ['title' => 'Dosen', 'count_var' => 'jumlah_dosen', 'icon' => 'fa-chalkboard-teacher', 'color' => 'info'],
                            ['title' => 'Tendik', 'count_var' => 'jumlah_tendik', 'icon' => 'fa-briefcase', 'color' => 'warning'],
                            ['title' => 'Pendaftar', 'count_var' => 'jumlah_pendaftar', 'icon' => 'fa-clipboard-list', 'color' => 'secondary'],
                        ];
                    @endphp

                    <div class="stats-grid">
                        @foreach ($userStats as $stat)
                            <div class="stat-card {{ $stat['color'] }}">
                                <div class="stat-header">
                                    <div class="stat-icon {{ $stat['color'] }}">
                                        <i class="fas {{ $stat['icon'] }}"></i>
                                    </div>
                                </div>
                                <div class="stat-title">{{ $stat['title'] }}</div>
                                <div class="stat-value" id="card-count-{{ \Illuminate\Support\Str::slug($stat['count_var'], '-') }}">
                                    {{ ${$stat['count_var']} ?? 0 }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Status Section -->
                    <div class="status-section">
                        <div class="section-header">
                            <h2 class="section-title">
                                <i class="fas fa-chart-line"></i>
                                Status Pendaftaran
                            </h2>
                            <p class="section-subtitle">Status dan hasil seleksi peserta</p>
                        </div>

                        @php
                            $statusStats = [
                                ['title' => 'Menunggu', 'count_var' => 'status_menunggu', 'icon' => 'fa-clock', 'color' => 'warning'],
                                ['title' => 'Diterima', 'count_var' => 'status_diterima', 'icon' => 'fa-check-circle', 'color' => 'success'],
                                ['title' => 'Ditolak', 'count_var' => 'status_ditolak', 'icon' => 'fa-times-circle', 'color' => 'danger'],
                                ['title' => 'Lolos', 'count_var' => 'jumlah_lolos', 'icon' => 'fa-thumbs-up', 'color' => 'success'],
                                ['title' => 'Tidak Lolos', 'count_var' => 'jumlah_tidak_lolos', 'icon' => 'fa-thumbs-down', 'color' => 'danger'],
                            ];
                        @endphp

                        <div class="status-grid">
                            @foreach ($statusStats as $stat)
                                <div class="status-card">
                                    <div class="stat-header">
                                        <div class="stat-icon {{ $stat['color'] }}">
                                            <i class="fas {{ $stat['icon'] }}"></i>
                                        </div>
                                    </div>
                                    <div class="stat-title">{{ $stat['title'] }}</div>
                                    <div class="stat-value" id="card-count-{{ \Illuminate\Support\Str::slug($stat['count_var'], '-') }}">
                                        {{ ${$stat['count_var']} ?? 0 }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="charts-section">
                        <div class="section-header">
                            <h2 class="section-title">
                                <i class="fas fa-chart-bar"></i>
                                Analisis Data
                            </h2>
                            <p class="section-subtitle">Visualisasi data dan tren</p>
                        </div>

                        <div class="row">
                            {{-- KARTU BAR CHART (PENGGANTI LINE CHART) --}}
                            <div class="col-lg-8 mb-4">
                                <div class="chart-card">
                                    <div class="chart-header">
                                        <h6 class="chart-title">
                                            <i class="fas fa-chart-bar"></i> {{-- ICON DIUBAH --}}
                                            Rata-rata Nilai Mahasiswa per Jurusan {{-- JUDUL DIUBAH --}}
                                        </h6>
                                    </div>
                                    <div class="chart-body">
                                        <div style="height: 350px; position: relative;">
                                            <canvas id="barChart"></canvas> {{-- ID CANVAS DIUBAH --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KARTU PIE CHART (TETAP) --}}
                            <div class="col-lg-4 mb-4">
                                <div class="chart-card">
                                    <div class="chart-header">
                                        <h6 class="chart-title">
                                            <i class="fas fa-chart-pie"></i>
                                            Persentase Seleksi Hasil Ujian
                                        </h6>
                                    </div>
                                    <div class="chart-body">
                                        <div style="height: 300px; position: relative;">
                                            <canvas id="pieChart"></canvas>
                                        </div>
                                        <div class="mt-3 text-center">
                                            <small class="text-muted">
                                                <br>
                                                <br>
                                                <i class=""></i>
                                                <i class=""></i>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @if(in_array($role, ['mahasiswa', 'dosen', 'tendik']))
            <div class="info-section">
                <div class="section-header" style="text-align: center;">
                    <h2 class="section-title" style="display: inline-block;">
                        Informasi Terkini
                    </h2>
                    <p class="section-subtitle">Dapatkan update terbaru dan penting untuk Anda</p>
                </div>

                @forelse($informasi as $index => $item)
                    <div class="info-card animate-card" 
                        style="animation-delay: {{ $index * 0.15 }}s;">
                        <div class="card-body-custom">
                            <div class="info-title">
                                <i class="fas fa-info-circle"></i>
                                {{ $item->judul }}
                            </div>
                            <p class="info-content">{!! $item->isi !!}</p>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p class="empty-state-text">
                            Tidak ada informasi terbaru saat ini.<br>
                            <small>Silakan cek kembali nanti untuk update terbaru.</small>
                        </p>
                    </div>
                @endforelse
            </div>
        @endif



    @push('js')
       <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <script>
            let barChartInstance;
            let pieChartInstance;
            let eventSource; 

            // Fungsi untuk membersihkan koneksi SSE
            function cleanupSSE() {
                if (eventSource) {
                    console.log('Closing SSE connection...');
                    eventSource.close();
                    eventSource = null; // Hapus referensi
                }
            }

            window.addEventListener('load', function() {
                const userRole = '{{ $user->role ?? "" }}';
                if (userRole === 'admin') {
                    initBarChart(@json($barChartLabels ?? []), @json($barChartValues ?? []));
                    initPieChart(@json($jumlah_lolos ?? 0), @json($jumlah_tidak_lolos ?? 0));
                    
                    setupSSE(); 
                }
            });

            // PERBAIKAN: Gunakan event 'pagehide' dan 'beforeunload' untuk memastikan
            // koneksi SSE ditutup saat user meninggalkan halaman. 'pagehide' lebih modern
            // dan andal untuk kasus ini.
            window.addEventListener('pagehide', cleanupSSE);
            window.addEventListener('beforeunload', cleanupSSE);

            /**
             * Menginisialisasi atau memperbarui Bar Chart.
             */
            function initBarChart(labels, values) {
                const barCanvas = document.getElementById('barChart');
                if (!barCanvas) return;
                const ctx = barCanvas.getContext('2d');

                if (barChartInstance) {
                    barChartInstance.destroy();
                }

                barChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Rata-rata Nilai',
                            data: values,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                                'rgba(75, 192, 192, 0.5)',
                                'rgba(153, 102, 255, 0.5)',
                                'rgba(255, 159, 64, 0.5)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'Rata-rata Nilai' } },
                            x: { title: { display: true, text: 'Jurusan' } }
                        }
                    }
                });
            }
            
            /**
             * Menginisialisasi atau memperbarui Pie Chart.
             */
            function initPieChart(lolos, tidakLolos) {
                const pieCanvas = document.getElementById('pieChart');
                if (!pieCanvas) return;
                const ctx = pieCanvas.getContext('2d');
                const totalData = lolos + tidakLolos;
                let chartData, chartLabels, backgroundColors, borderColors;
                
                if (totalData === 0) {
                    chartData = [1];
                    chartLabels = ['Belum Ada Data'];
                    backgroundColors = ['rgba(128, 128, 128, 0.7)'];
                    borderColors = ['rgba(128, 128, 128, 1)'];
                } else {
                    chartData = [lolos, tidakLolos];
                    chartLabels = ['Lolos (' + lolos + ')', 'Tidak Lolos (' + tidakLolos + ')'];
                    backgroundColors = ['rgba(75, 192, 192, 0.7)', 'rgba(255, 99, 132, 0.7)'];
                    borderColors = ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'];
                }

                if (pieChartInstance) {
                    pieChartInstance.destroy();
                }

                pieChartInstance = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            label: 'Status Kelulusan',
                            data: chartData,
                            backgroundColor: backgroundColors,
                            borderColor: borderColors,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            /**
             * Mengatur koneksi Server-Sent Events (SSE).
             */
            function setupSSE() {
                cleanupSSE(); // Selalu bersihkan koneksi lama sebelum memulai yang baru
                
                eventSource = new EventSource("{{ route('dashboard.chart.stream') }}");
                
                eventSource.onmessage = function(event) {
                    try {
                        const data = JSON.parse(event.data);

                        // Update Bar Chart
                        if (barChartInstance && data.barChart) {
                            barChartInstance.data.labels = data.barChart.labels;
                            barChartInstance.data.datasets[0].data = data.barChart.values;
                            barChartInstance.update();
                        }

                        // Update Pie Chart
                        if (pieChartInstance && data.pieChartData) {
                            initPieChart(data.pieChartData.lolos, data.pieChartData.tidakLolos);
                        }

                        // Update Kartu Statistik
                        if (data.cardData) {
                        for (const key in data.cardData) {
                                if (Object.prototype.hasOwnProperty.call(data.cardData, key)) {
                                    const elementId = `card-count-${key.replace(/_/g, '-')}`;
                                    const cardElement = document.getElementById(elementId);
                                    if (cardElement) {
                                        cardElement.innerText = data.cardData[key];
                                    }
                                }
                            }
                        }
                    } catch (error) {
                        console.error('Error parsing SSE data or updating elements:', error);
                    }
                };

                eventSource.onerror = function(err) {
                    console.error('EventSource error:', err);
                    cleanupSSE();
                    setTimeout(setupSSE, 5000); 
                };
            }
        </script>
    @endpush
@endsection