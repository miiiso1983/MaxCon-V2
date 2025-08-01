<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'لوحة التحكم') - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Select Styles -->
    <link rel="stylesheet" href="{{ asset('css/custom-select.css') }}">

    <!-- MaxCon Enhanced Styles -->
    <link rel="stylesheet" href="{{ asset('css/maxcon-enhancements.css') }}">

    <!-- Analytics Charts Styles -->
    <link rel="stylesheet" href="{{ asset('css/analytics-charts.css') }}">

    <!-- Charts Styles -->
    <link rel="stylesheet" href="{{ asset('css/charts-styles.css') }}">

    <!-- Responsive Styles -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    <!-- Page Specific Styles -->
    @stack('styles')

    <!-- Scripts -->
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->

    <!-- Universal Charts Fix -->
    <script src="{{ asset('js/charts-universal-fix.js') }}"></script>

    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }

        body {
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        /* Enhanced Sidebar Header Styles */
        .sidebar-logo-container {
            position: relative;
            overflow: hidden;
        }

        .sidebar-logo-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 10s linear infinite;
            pointer-events: none;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .gradient-text-primary {
            background: linear-gradient(45deg, #fbbf24, #f59e0b, #ea580c, #dc2626);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 3s ease-in-out infinite;
            text-shadow: 0 0 30px rgba(251, 191, 36, 0.5);
        }

        .gradient-text-secondary {
            background: linear-gradient(45deg, #60a5fa, #a78bfa, #f472b6, #fb7185);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: gradientShift 4s ease-in-out infinite reverse;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .crown-icon {
            filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.7));
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-5px); }
            60% { transform: translateY(-3px); }
        }

        .divider-line {
            background: linear-gradient(90deg, transparent, #fbbf24, #f59e0b, #fbbf24, transparent);
            animation: shimmer 2s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* Hover effects for sidebar header */
        .sidebar-logo-container:hover .crown-icon {
            animation: spin 1s ease-in-out;
        }

        @keyframes spin {
            0% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.1); }
            100% { transform: rotate(360deg) scale(1); }
        }

        .sidebar-logo-container:hover .gradient-text-primary {
            animation-duration: 1s;
        }

        .sidebar-logo-container:hover .gradient-text-secondary {
            animation-duration: 1.5s;
        }

        /* Pulse animation for dots */
        @keyframes pulse {
            0%, 100% {
                opacity: 0.4;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.2);
            }
        }

        /* Enhanced 3D effect on hover */
        .sidebar-logo-container:hover > div:first-child {
            transform: perspective(1000px) rotateX(10deg) rotateY(5deg) scale(1.05) !important;
            box-shadow: 0 15px 40px rgba(251, 191, 36, 0.5) !important;
        }

        .sidebar {
            background: linear-gradient(180deg, #4c63d2 0%, #5a67d8 50%, #667eea 100%);
            width: 280px;
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 1000;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
            transition: background 0.3s ease;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Sidebar fade effect at top and bottom */
        .sidebar::before {
            content: '';
            position: fixed;
            top: 0;
            right: 0;
            width: 280px;
            height: 20px;
            background: linear-gradient(180deg, #4c63d2 0%, transparent 100%);
            z-index: 1001;
            pointer-events: none;
        }

        .sidebar::after {
            content: '';
            position: fixed;
            bottom: 0;
            right: 0;
            width: 280px;
            height: 20px;
            background: linear-gradient(0deg, #667eea 0%, transparent 100%);
            z-index: 1001;
            pointer-events: none;
        }

        .main-content {
            margin-right: 280px;
            padding: 20px;
            min-height: 100vh;
            background: #f5f7fa;
        }

        .top-header {
            background: white;
            border-radius: 12px;
            padding: 15px 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1000;
            overflow: visible;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
        }

        .page-subtitle {
            font-size: 14px;
            color: #718096;
            margin: 5px 0 0 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #4c63d2;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .btn-green {
            background: #48bb78;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-blue {
            background: #4299e1;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-orange {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-orange:hover {
            background: linear-gradient(135deg, #dd6b20 0%, #c05621 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(237, 137, 54, 0.4);
        }

        .search-filters {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            align-items: center;
        }

        .search-box {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 15px;
            width: 200px;
            font-size: 14px;
        }

        .filter-select {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 14px;
            min-width: 120px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .data-table th {
            background: #f7fafc;
            padding: 15px;
            text-align: right;
            font-weight: 600;
            color: #4a5568;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #2d3748;
        }

        .data-table tr:hover {
            background: #f7fafc;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-inactive {
            background: #fed7d7;
            color: #742a2a;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #718096;
        }

        .stat-icon {
            position: absolute;
            top: 15px;
            left: 15px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            margin: 2px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .nav-link i {
            margin-left: 12px;
            width: 20px;
        }

        /* Navigation Section Styles */
        .nav-section {
            margin: 10px 0;
        }

        .nav-section-title {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 2px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-section-title:hover {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.9);
        }

        .nav-section:not(.collapsed) .nav-section-title {
            background: rgba(255, 255, 255, 0.08);
            color: white;
        }

        .nav-section-title i {
            margin-left: 12px;
            width: 20px;
            font-size: 16px;
        }

        .nav-section-title::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            left: 20px;
            transition: transform 0.3s ease;
            font-size: 12px;
        }

        .nav-section.collapsed .nav-section-title::after {
            transform: rotate(-90deg);
        }

        .nav-section-title:active {
            transform: scale(0.98);
        }

        .nav-section-content {
            max-height: 1000px;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .nav-section.collapsed .nav-section-content {
            max-height: 0;
        }

        .nav-section .nav-link {
            margin-right: 30px;
            padding-right: 15px;
            border-right: 2px solid transparent;
            position: relative;
        }

        .nav-section .nav-link:hover,
        .nav-section .nav-link.active {
            border-right-color: rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(-2px);
        }

        .nav-section .nav-link:hover::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 0 2px 2px 0;
        }

        .nav-section .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #fff;
            border-radius: 0 2px 2px 0;
        }
    </style>
</head>

<body>
    <!-- Modern Collapsible Sidebar -->
    @if(auth()->user()->hasRole('tenant-admin'))
        <x-collapsible-sidebar />
    @else
        <!-- Super Admin Sidebar (Keep existing for super admin) -->
        <div class="sidebar">
            <div class="p-6">
                <!-- Logo -->
                <div class="sidebar-logo-container text-center mb-8" style="padding: 20px; position: relative;">
                    <div style="background: linear-gradient(135deg, #fbbf24, #f59e0b, #ea580c); border-radius: 20px; padding: 20px; display: inline-block; margin-bottom: 20px; box-shadow: 0 10px 30px rgba(251, 191, 36, 0.3); transform: perspective(1000px) rotateX(5deg); transition: all 0.3s ease;">
                        <i class="fas fa-crown crown-icon" style="color: white; font-size: 32px;"></i>
                    </div>
                    <h1 class="gradient-text-primary" style="font-size: 28px; font-weight: 800; margin-bottom: 10px; letter-spacing: 1px; text-align: center;">
                        MaxCon Master
                    </h1>
                    <p class="gradient-text-secondary" style="font-size: 16px; font-weight: 600; text-align: center; margin-bottom: 15px;">
                        إدارة النظام الرئيسية
                    </p>
                    <div style="display: flex; justify-content: center; margin-top: 15px;">
                        <div class="divider-line" style="width: 80px; height: 3px; border-radius: 2px;"></div>
                    </div>
                    <div style="margin-top: 10px; display: flex; justify-content: center; gap: 5px;">
                        <div style="width: 8px; height: 8px; background: #fbbf24; border-radius: 50%; animation: pulse 2s infinite;"></div>
                        <div style="width: 8px; height: 8px; background: #f59e0b; border-radius: 50%; animation: pulse 2s infinite 0.5s;"></div>
                        <div style="width: 8px; height: 8px; background: #ea580c; border-radius: 50%; animation: pulse 2s infinite 1s;"></div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-1">
                @if(auth()->user()->isSuperAdmin())
                    <!-- Super Admin Navigation -->
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        لوحة التحكم
                    </a>

                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="fas fa-crown"></i>
                        إدارة النظام
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        إدارة المستخدمين
                    </a>

                    <a href="{{ route('admin.tenants.maxcon') }}"
                       class="nav-link {{ request()->routeIs('admin.tenants.maxcon') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        جميع المستأجرين
                    </a>

                    <a href="{{ route('admin.tenants.create') }}"
                       class="nav-link {{ request()->routeIs('admin.tenants.create') ? 'active' : '' }}">
                        <i class="fas fa-plus"></i>
                        إضافة مستأجر جديد
                    </a>

                    <a href="{{ route('admin.tenants.index') }}"
                       class="nav-link {{ request()->routeIs('admin.tenants.index') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        طلبات الانضمام
                    </a>

                    <a href="{{ route('admin.licenses.expired') }}" class="nav-link">
                        <i class="fas fa-exclamation-triangle"></i>
                        التراخيص المنتهية
                    </a>

                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-line"></i>
                        مراقبة النظام
                    </a>

                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i>
                        إعدادات النظام
                    </a>

                    <a href="#" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        سجلات النظام
                    </a>

                    <a href="#" class="nav-link">
                        <i class="fas fa-database"></i>
                        النسخ الاحتياطية
                    </a>

                    <a href="#" class="nav-link">
                        <i class="fas fa-heart"></i>
                        خطط الاشتراك
                    </a>
                @endif
            </nav>
        </div>
    </div>
    @endif






    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div>
                <h1 class="page-title">@yield('page-title', 'إدارة المستأجرين')</h1>
                <p class="page-subtitle">@yield('page-description', 'إدارة شاملة لجميع المستأجرين في النظام')</p>
            </div>

            <div class="user-info" style="position: relative;">
                <div class="text-right">
                    <div class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-500">
                        @if(auth()->user()->isSuperAdmin())
                            مدير النظام
                        @elseif(auth()->user()->hasRole('tenant-admin'))
                            مدير المؤسسة
                        @else
                            مستخدم
                        @endif
                    </div>
                </div>
                <div class="user-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <!-- Quick Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit"
                                style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; box-shadow: 0 2px 8px rgba(245, 101, 101, 0.3);"
                                onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(245, 101, 101, 0.4)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(245, 101, 101, 0.3)';"
                                onclick="return confirm('هل أنت متأكد من تسجيل الخروج؟')"
                                title="تسجيل الخروج السريع">
                            <i class="fas fa-sign-out-alt"></i>
                            خروج
                        </button>
                    </form>


                </div>
            </div>
        </div>

        <!-- Content -->
        <main>
            @yield('content')
        </main>
    </div>



    <!-- Scripts -->
    <script>


        // Add keyboard shortcut for logout (Ctrl+Shift+L)
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.shiftKey && event.key === 'L') {
                if (confirm('هل أنت متأكد من تسجيل الخروج؟')) {
                    const logoutForm = document.querySelector('form[action*="logout"]');
                    if (logoutForm) {
                        logoutForm.submit();
                    }
                }
            }
        });
    </script>
    @stack('scripts')

    <!-- Custom Select JavaScript -->
    <script src="{{ asset('js/custom-select.js') }}"></script>

    <script>
        // Toggle sidebar sections with animation
        function toggleSection(element) {
            const section = element.closest('.nav-section');
            const content = section.querySelector('.nav-section-content');
            const isCollapsed = section.classList.contains('collapsed');

            // Add transition effect
            if (isCollapsed) {
                section.classList.remove('collapsed');
                // Animate expansion
                content.style.maxHeight = content.scrollHeight + 'px';
                setTimeout(() => {
                    content.style.maxHeight = '1000px';
                }, 300);
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
                setTimeout(() => {
                    section.classList.add('collapsed');
                    content.style.maxHeight = '0';
                }, 10);
            }

            // Save state to localStorage
            const sectionTitle = element.textContent.trim();
            const newState = !isCollapsed;
            localStorage.setItem('sidebar_' + sectionTitle, newState ? 'expanded' : 'collapsed');
        }

        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.nav-section');
            sections.forEach(section => {
                const titleElement = section.querySelector('.nav-section-title');
                const content = section.querySelector('.nav-section-content');

                if (titleElement && content) {
                    const sectionTitle = titleElement.textContent.trim();
                    const savedState = localStorage.getItem('sidebar_' + sectionTitle);

                    if (savedState === 'collapsed') {
                        section.classList.add('collapsed');
                        content.style.maxHeight = '0';
                    } else if (savedState === 'expanded') {
                        section.classList.remove('collapsed');
                        content.style.maxHeight = '1000px';
                    }
                }
            });

            // Add hover effects to nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(-2px)';
                });

                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active')) {
                        this.style.transform = 'translateX(0)';
                    }
                });
            });

            // Add click effect to section titles
            const sectionTitles = document.querySelectorAll('.nav-section-title');
            sectionTitles.forEach(title => {
                title.addEventListener('mousedown', function() {
                    this.style.transform = 'scale(0.98)';
                });

                title.addEventListener('mouseup', function() {
                    this.style.transform = 'scale(1)';
                });

                title.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });

        // Smooth scrolling for sidebar
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.addEventListener('wheel', function(e) {
                e.preventDefault();
                this.scrollTop += e.deltaY * 0.5; // Smoother scrolling
            });
        }

        // Auto-expand section if current page is in it
        document.addEventListener('DOMContentLoaded', function() {
            const activeLink = document.querySelector('.nav-link.active');
            if (activeLink) {
                const parentSection = activeLink.closest('.nav-section');
                if (parentSection && parentSection.classList.contains('collapsed')) {
                    const titleElement = parentSection.querySelector('.nav-section-title');
                    if (titleElement) {
                        toggleSection(titleElement);
                    }
                }
            }

            // Check if System Guide section exists
            const systemGuideSection = document.querySelector('.nav-section .nav-section-title i.fa-graduation-cap');
            if (systemGuideSection) {
                console.log('✅ System Guide section found in sidebar!');
                // Add a subtle highlight to make it more visible
                const systemGuideTitle = systemGuideSection.closest('.nav-section-title');
                if (systemGuideTitle) {
                    systemGuideTitle.style.border = '2px solid rgba(16, 185, 129, 0.3)';
                    systemGuideTitle.style.borderRadius = '8px';
                    systemGuideTitle.style.boxShadow = '0 0 10px rgba(16, 185, 129, 0.2)';
                }
            } else {
                console.log('❌ System Guide section NOT found in sidebar!');
            }
        });
    </script>
</body>
</html>
