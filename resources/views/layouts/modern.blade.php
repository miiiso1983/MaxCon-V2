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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">

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
            background: linear-gradient(180deg, #5b73e8 0%, #4c63d2 30%, #667eea 70%, #7c3aed 100%);
            width: 280px;
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 1000;
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.15);
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
            height: 25px;
            background: linear-gradient(180deg, #5b73e8 0%, transparent 100%);
            z-index: 1001;
            pointer-events: none;
        }

        .sidebar::after {
            content: '';
            position: fixed;
            bottom: 0;
            right: 0;
            width: 280px;
            height: 25px;
            background: linear-gradient(0deg, #7c3aed 0%, transparent 100%);
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
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            margin: 2px 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
            border-right: 3px solid transparent;
            position: relative;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            transform: translateX(-3px);
            border-right-color: rgba(255, 255, 255, 0.3);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-right-color: #fbbf24;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-link i {
            margin-left: 12px;
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        /* Navigation Section Styles */
        .nav-section {
            margin: 10px 0;
        }

        .nav-section-title {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            font-weight: 700;
            text-transform: none;
            letter-spacing: 0.3px;
            margin: 5px 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-section-title:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateX(-2px);
        }

        .nav-section:not(.collapsed) .nav-section-title {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            border-color: rgba(255, 255, 255, 0.25);
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

        /* Add subtle glow effect to active items */
        .nav-link.active {
            box-shadow: 0 0 15px rgba(251, 191, 36, 0.3);
        }

        /* Improve section dividers */
        .nav-section {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 8px;
            padding-bottom: 8px;
        }

        .nav-section:last-child {
            border-bottom: none;
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
            font-size: 14px;
        }

        .nav-section .nav-link:before {
            content: '';
            position: absolute;
            right: -15px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: #fbbf24;
            border-radius: 2px;
            transition: height 0.3s ease;
        }

        .nav-section .nav-link.active:before {
            height: 20px;
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
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-6">
            <!-- Logo -->
            <div class="sidebar-logo-container text-center mb-8" style="padding: 20px; position: relative;">
                <div style="background: linear-gradient(135deg, #fbbf24, #f59e0b, #ea580c); border-radius: 20px; padding: 20px; display: inline-block; margin-bottom: 20px; box-shadow: 0 10px 30px rgba(251, 191, 36, 0.3); transform: perspective(1000px) rotateX(5deg); transition: all 0.3s ease;">
                    <i class="fas fa-crown crown-icon" style="color: white; font-size: 32px;"></i>
                </div>
                <h1 style="font-size: 24px; font-weight: 800; margin-bottom: 8px; letter-spacing: 1px; text-align: center; color: #fbbf24; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                    MaxCon
                </h1>
                <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 8px; letter-spacing: 0.5px; text-align: center; color: #f59e0b; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                    Master
                </h2>
                <p style="font-size: 14px; font-weight: 600; text-align: center; margin-bottom: 15px; color: rgba(255,255,255,0.9); text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
                    إدارة النظام الرئيسية
                </p>
                <div style="display: flex; justify-content: center; margin-top: 15px;">
                    <div style="width: 80px; height: 3px; background: linear-gradient(90deg, #fbbf24, #f59e0b, #ea580c); border-radius: 2px;"></div>
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
                @else
                    <!-- Tenant Admin Navigation -->
                    <a href="{{ route('tenant.dashboard') }}"
                       class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        لوحة التحكم
                    </a>

                    <!-- User Management Section -->
                    <div class="nav-section {{ request()->routeIs('tenant.users.*') || request()->routeIs('tenant.roles.*') ? '' : 'collapsed' }}">
                        <div class="nav-section-title" onclick="toggleSection(this)">
                            <i class="fas fa-users-cog"></i>
                            إدارة المستخدمين والصلاحيات
                        </div>
                        <div class="nav-section-content">
                            <a href="{{ route('tenant.users.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.users.*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                إدارة المستخدمين
                            </a>

                            <a href="{{ route('tenant.roles.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.roles.*') ? 'active' : '' }}">
                                <i class="fas fa-user-shield"></i>
                                إدارة الأدوار والصلاحيات
                            </a>
                        </div>
                    </div>

                    <!-- Purchasing Management Section -->
                    <div class="nav-section {{ request()->routeIs('tenant.purchasing.*') ? '' : 'collapsed' }}">
                        <div class="nav-section-title" onclick="toggleSection(this)">
                            <i class="fas fa-truck"></i>
                            إدارة المشتريات
                        </div>
                        <div class="nav-section-content">
                            <a href="{{ route('tenant.purchasing.suppliers.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.purchasing.suppliers.*') ? 'active' : '' }}">
                                <i class="fas fa-truck"></i>
                                إدارة الموردين
                            </a>

                            <a href="{{ route('tenant.purchasing.purchase-requests.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.purchasing.purchase-requests.*') ? 'active' : '' }}">
                                <i class="fas fa-file-alt"></i>
                                طلبات الشراء
                            </a>

                            <a href="{{ route('tenant.purchasing.purchase-orders.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.purchasing.purchase-orders.*') ? 'active' : '' }}">
                                <i class="fas fa-clipboard-list"></i>
                                أوامر الشراء
                            </a>

                            <a href="{{ route('tenant.purchasing.quotations.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.purchasing.quotations.*') ? 'active' : '' }}">
                                <i class="fas fa-quote-right"></i>
                                عروض الأسعار
                            </a>

                            <a href="{{ route('tenant.purchasing.contracts.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.purchasing.contracts.*') ? 'active' : '' }}">
                                <i class="fas fa-file-contract"></i>
                                العقود والاتفاقيات
                            </a>
                        </div>
                    </div>

                    <!-- Sales Management Section -->
                    <div class="nav-section {{ request()->routeIs('tenant.sales.*') ? '' : 'collapsed' }}">
                        <div class="nav-section-title" onclick="toggleSection(this)">
                            <i class="fas fa-shopping-bag"></i>
                            إدارة المبيعات
                        </div>
                        <div class="nav-section-content">
                            <a href="{{ route('tenant.sales.orders.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.sales.orders.*') ? 'active' : '' }}">
                                <i class="fas fa-shopping-cart"></i>
                                طلبات المبيعات
                            </a>

                            <a href="{{ route('tenant.sales.customers.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.sales.customers.*') ? 'active' : '' }}">
                                <i class="fas fa-users"></i>
                                إدارة العملاء
                            </a>

                            <a href="{{ route('tenant.sales.products.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.sales.products.*') ? 'active' : '' }}">
                                <i class="fas fa-pills"></i>
                                إدارة المنتجات
                            </a>

                            <a href="{{ route('tenant.sales.invoices.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.sales.invoices.*') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice"></i>
                                إدارة الفواتير
                            </a>

                            <a href="{{ route('tenant.sales.returns.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.sales.returns.*') ? 'active' : '' }}">
                                <i class="fas fa-undo-alt"></i>
                                إدارة المرتجعات
                            </a>

                            <!-- Sales Targets Section -->
                            <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 15px;">
                                <div style="color: rgba(255,255,255,0.7); font-size: 12px; font-weight: 600; margin-bottom: 10px; padding: 0 15px;">
                                    أهداف البيع
                                </div>

                                <a href="{{ route('tenant.sales.targets.index') }}"
                                   class="nav-link {{ request()->routeIs('tenant.sales.targets.index') ? 'active' : '' }}">
                                    <i class="fas fa-bullseye"></i>
                                    قائمة الأهداف
                                </a>

                                <a href="{{ route('tenant.sales.targets.create') }}"
                                   class="nav-link {{ request()->routeIs('tenant.sales.targets.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus-circle"></i>
                                    إنشاء هدف جديد
                                </a>

                                <a href="{{ route('tenant.sales.targets.dashboard') }}"
                                   class="nav-link {{ request()->routeIs('tenant.sales.targets.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-chart-line"></i>
                                    لوحة تحكم الأهداف
                                </a>

                                <a href="{{ route('tenant.sales.targets.reports') }}"
                                   class="nav-link {{ request()->routeIs('tenant.sales.targets.reports') ? 'active' : '' }}">
                                    <i class="fas fa-chart-bar"></i>
                                    تقارير الأهداف
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Section -->
                    <div class="nav-section {{ request()->routeIs('tenant.inventory.*') ? '' : 'collapsed' }}">
                        <div class="nav-section-title" onclick="toggleSection(this)">
                            <i class="fas fa-warehouse"></i>
                            إدارة المخزون
                        </div>
                        <div class="nav-section-content">
                            <a href="{{ route('tenant.inventory.categories.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.categories.*') ? 'active' : '' }}">
                                <i class="fas fa-tags"></i>
                                فئات المنتجات
                            </a>

                            <a href="{{ route('tenant.inventory.inventory-products.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.inventory-products.*') ? 'active' : '' }}">
                                <i class="fas fa-cube"></i>
                                كتالوج المنتجات
                            </a>

                            <a href="{{ route('tenant.inventory.qr.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.qr.*') ? 'active' : '' }}">
                                <i class="fas fa-qrcode"></i>
                                QR كود المنتجات
                            </a>

                            <a href="{{ route('tenant.inventory.warehouses.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.warehouses.*') ? 'active' : '' }}">
                                <i class="fas fa-warehouse"></i>
                                إدارة المستودعات
                            </a>

                            <a href="{{ route('tenant.inventory.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.index', 'tenant.inventory.show', 'tenant.inventory.create', 'tenant.inventory.edit') ? 'active' : '' }}">
                                <i class="fas fa-boxes"></i>
                                إدارة المخزون
                            </a>

                            <a href="{{ route('tenant.inventory.movements.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.movements.*') ? 'active' : '' }}">
                                <i class="fas fa-exchange-alt"></i>
                                حركات المخزون
                            </a>

                            <a href="{{ route('tenant.inventory.audits.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.audits.*') ? 'active' : '' }}">
                                <i class="fas fa-clipboard-list"></i>
                                الجرد الدوري
                            </a>

                            <a href="{{ route('tenant.inventory.alerts.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.alerts.*') ? 'active' : '' }}">
                                <i class="fas fa-exclamation-triangle"></i>
                                التنبيهات
                            </a>

                            <a href="{{ route('tenant.inventory.reports.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.reports.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar"></i>
                                تقارير المخزون
                            </a>

                            <a href="{{ route('tenant.inventory.custom-reports.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.custom-reports.*') ? 'active' : '' }}">
                                <i class="fas fa-file-alt"></i>
                                التقارير المخصصة
                            </a>
                        </div>
                    </div>

                    <!-- Accounting Section -->
                    <div class="nav-section {{ request()->routeIs('tenant.inventory.accounting.*') ? '' : 'collapsed' }}">
                        <div class="nav-section-title" onclick="toggleSection(this)">
                            <i class="fas fa-calculator"></i>
                            النظام المحاسبي
                        </div>
                        <div class="nav-section-content">
                            <a href="{{ route('tenant.inventory.accounting.chart-of-accounts.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.accounting.chart-of-accounts.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-tree"></i>
                                دليل الحسابات
                            </a>

                            <a href="{{ route('tenant.inventory.accounting.cost-centers.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.accounting.cost-centers.*') ? 'active' : '' }}">
                                <i class="fas fa-building"></i>
                                مراكز التكلفة
                            </a>

                            <a href="{{ route('tenant.inventory.accounting.journal-entries.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.accounting.journal-entries.*') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice"></i>
                                القيود المحاسبية
                            </a>

                            <a href="{{ route('tenant.inventory.accounting.reports.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.accounting.reports.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-line"></i>
                                التقارير المالية
                            </a>

                            <a href="{{ route('tenant.inventory.accounting.reports.trial-balance') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.accounting.reports.trial-balance') ? 'active' : '' }}">
                                <i class="fas fa-balance-scale"></i>
                                ميزان المراجعة
                            </a>

                            <a href="{{ route('tenant.inventory.accounting.reports.income-statement') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.accounting.reports.income-statement') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar"></i>
                                قائمة الدخل
                            </a>

                            <a href="{{ route('tenant.inventory.accounting.reports.balance-sheet') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.accounting.reports.balance-sheet') ? 'active' : '' }}">
                                <i class="fas fa-building"></i>
                                الميزانية العمومية
                            </a>

                            <a href="{{ route('tenant.inventory.accounting.reports.cash-flow') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.accounting.reports.cash-flow') ? 'active' : '' }}">
                                <i class="fas fa-coins"></i>
                                التدفقات النقدية
                            </a>

                            <a href="{{ route('tenant.inventory.accounting.reports.account-ledger') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.accounting.reports.account-ledger') ? 'active' : '' }}">
                                <i class="fas fa-book"></i>
                                دفتر الأستاذ
                            </a>
                        </div>

                                @if(\Illuminate\Support\Facades\Route::has('tenant.inventory.accounting.receivables.index'))
                                <a href="{{ route('tenant.inventory.accounting.receivables.index') }}"
                                   class="nav-link {{ request()->routeIs('tenant.inventory.accounting.receivables.*') ? 'active' : '' }}">
                                    <i class="fas fa-hand-holding-usd"></i>
                                    التحصيل - الذمم المدينة
                                </a>
                                @endif



                    </div>



                    <!-- Regulatory Affairs Section -->
                    <div class="nav-section {{ request()->routeIs('tenant.inventory.regulatory.*') ? '' : 'collapsed' }}">
                        <div class="nav-section-title" onclick="toggleSection(this)">
                            <i class="fas fa-shield-alt"></i>
                            الشؤون التنظيمية
                        </div>
                        <div class="nav-section-content">
                            <a href="{{ route('tenant.inventory.regulatory.companies.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.regulatory.companies.*') ? 'active' : '' }}">
                                <i class="fas fa-building"></i>
                                تسجيل الشركات
                            </a>

                            <a href="{{ route('tenant.inventory.regulatory.products.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.regulatory.products.*') ? 'active' : '' }}">
                                <i class="fas fa-pills"></i>
                                تسجيل المنتجات
                            </a>

                            <a href="{{ route('tenant.inventory.regulatory.laboratory-tests.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.regulatory.laboratory-tests.*') ? 'active' : '' }}">
                                <i class="fas fa-flask"></i>
                                الفحوصات المخبرية
                            </a>

                            <a href="{{ route('tenant.inventory.regulatory.inspections.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.regulatory.inspections.*') ? 'active' : '' }}">
                                <i class="fas fa-search"></i>
                                التفتيش التنظيمي
                            </a>

                            <a href="{{ route('tenant.inventory.regulatory.certificates.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.regulatory.certificates.*') ? 'active' : '' }}">
                                <i class="fas fa-certificate"></i>
                                شهادات الجودة
                            </a>

                            <a href="{{ route('tenant.inventory.regulatory.product-recalls.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.regulatory.product-recalls.*') || request()->routeIs('tenant.inventory.regulatory.recalls.*') ? 'active' : '' }}">
                                <i class="fas fa-exclamation-triangle"></i>
                                سحب المنتجات
                            </a>

                            <a href="{{ route('tenant.inventory.regulatory.reports.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.regulatory.reports.*') ? 'active' : '' }}">
                                <i class="fas fa-file-alt"></i>
                                التقارير التنظيمية
                            </a>

                            <a href="{{ route('tenant.inventory.regulatory.documents.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.regulatory.documents.*') ? 'active' : '' }}">
                                <i class="fas fa-folder-open"></i>
                                الوثائق التنظيمية
                            </a>

                            <a href="{{ route('tenant.inventory.regulatory.dashboard') }}"
                               class="nav-link {{ request()->routeIs('tenant.inventory.regulatory.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                لوحة الشؤون التنظيمية
                            </a>
                        </div>
                    </div>

                    <!-- Human Resources Section -->
                    <div class="nav-section {{ request()->routeIs('tenant.hr.*') ? '' : 'collapsed' }}">
                        <div class="nav-section-title" onclick="toggleSection(this)">
                            <i class="fas fa-users"></i>
                            الموارد البشرية
                        </div>
                        <div class="nav-section-content">
                            <a href="{{ route('tenant.hr.dashboard') }}"
                               class="nav-link {{ request()->routeIs('tenant.hr.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                لوحة الموارد البشرية
                            </a>

                            <a href="{{ route('tenant.hr.employees.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.hr.employees.*') ? 'active' : '' }}">
                                <i class="fas fa-user-tie"></i>
                                إدارة الموظفين
                            </a>

                            <a href="{{ route('tenant.hr.departments.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.hr.departments.*') ? 'active' : '' }}">
                                <i class="fas fa-building"></i>
                                الأقسام والمناصب
                            </a>

                            <a href="{{ route('tenant.hr.attendance.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.hr.attendance.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-check"></i>
                                الحضور والانصراف
                            </a>

                            <a href="{{ route('tenant.hr.leaves.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.hr.leaves.*') || request()->routeIs('tenant.hr.leave-types.*') ? 'active' : '' }}">
                                <i class="fas fa-calendar-times"></i>
                                إدارة الإجازات
                            </a>

                            <a href="{{ route('tenant.hr.shifts.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.hr.shifts.*') ? 'active' : '' }}">
                                <i class="fas fa-clock"></i>
                                إدارة المناوبات
                            </a>

                            <a href="{{ route('tenant.hr.overtime.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.hr.overtime.*') ? 'active' : '' }}">
                                <i class="fas fa-stopwatch"></i>
                                الساعات الإضافية
                            </a>

                            <a href="{{ route('tenant.hr.payroll.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.hr.payroll.*') ? 'active' : '' }}">
                                <i class="fas fa-money-bill-wave"></i>
                                كشف الرواتب
                            </a>
                        </div>
                    </div>

                    <!-- Analytics and AI Section -->
                    <div class="nav-section {{ request()->routeIs('tenant.analytics.*') ? '' : 'collapsed' }}">
                        <div class="nav-section-title" onclick="toggleSection(this)">
                            <i class="fas fa-brain"></i>
                            الذكاء الاصطناعي والتحليلات
                        </div>
                        <div class="nav-section-content">
                            <a href="{{ route('tenant.analytics.predictions') }}"
                               class="nav-link {{ request()->routeIs('tenant.analytics.predictions') ? 'active' : '' }}">
                                <i class="fas fa-crystal-ball"></i>
                                التنبؤات الذكية
                            </a>

                            <a href="{{ route('tenant.analytics.market-trends') }}"
                               class="nav-link {{ request()->routeIs('tenant.analytics.market-trends') ? 'active' : '' }}">
                                <i class="fas fa-chart-line"></i>
                                تحليل اتجاهات السوق
                            </a>

                            <a href="{{ route('tenant.analytics.customer-behavior') }}"
                               class="nav-link {{ request()->routeIs('tenant.analytics.customer-behavior') ? 'active' : '' }}">
                                <i class="fas fa-user-chart"></i>
                                تحليل سلوك العملاء
                            </a>

                            <a href="{{ route('tenant.analytics.profitability') }}"
                               class="nav-link {{ request()->routeIs('tenant.analytics.profitability') ? 'active' : '' }}">
                                <i class="fas fa-chart-pie"></i>
                                تحليل الربحية
                            </a>

                            <a href="{{ route('tenant.analytics.risk-management') }}"
                               class="nav-link {{ request()->routeIs('tenant.analytics.risk-management') ? 'active' : '' }}">
                                <i class="fas fa-shield-alt"></i>
                                إدارة المخاطر
                            </a>

                            <a href="{{ route('tenant.analytics.executive-reports') }}"
                               class="nav-link {{ request()->routeIs('tenant.analytics.executive-reports') ? 'active' : '' }}">
                                <i class="fas fa-file-chart"></i>
                                التقارير التنفيذية
                            </a>
                        </div>
                    </div>

                    <!-- Dynamic Reports System Section -->
                    <div class="nav-section {{ request()->routeIs('tenant.reports.*') ? '' : 'collapsed' }}">
                        <div class="nav-section-title" onclick="toggleSection(this)">
                            <i class="fas fa-chart-line"></i>
                            نظام التقارير الديناميكي
                        </div>
                        <div class="nav-section-content">
                            <!-- Main Dashboard -->
                            <a href="{{ route('tenant.reports.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.reports.index') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                لوحة التقارير
                            </a>

                            <!-- Sales Reports Subsection -->
                            <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 15px;">
                                <div style="color: rgba(255,255,255,0.7); font-size: 12px; font-weight: 600; margin-bottom: 10px; padding: 0 15px;">
                                    تقارير المبيعات
                                </div>

                                <a href="{{ route('tenant.reports.generate', 'تقرير_المبيعات_اليومية') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.generate') && request()->route('reportType') == 'تقرير_المبيعات_اليومية' ? 'active' : '' }}">
                                    <i class="fas fa-chart-bar"></i>
                                    المبيعات اليومية
                                </a>

                                <a href="{{ route('tenant.reports.generate', 'تقرير_أداء_المندوبين') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.generate') && request()->route('reportType') == 'تقرير_أداء_المندوبين' ? 'active' : '' }}">
                                    <i class="fas fa-users"></i>
                                    أداء المندوبين
                                </a>

                                <a href="{{ route('tenant.reports.generate', 'العملاء_الأكثر_شراءً') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.generate') && request()->route('reportType') == 'العملاء_الأكثر_شراءً' ? 'active' : '' }}">
                                    <i class="fas fa-star"></i>
                                    العملاء الأكثر شراءً
                                </a>
                            </div>

                            <!-- Financial Reports Subsection -->
                            <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 15px;">
                                <div style="color: rgba(255,255,255,0.7); font-size: 12px; font-weight: 600; margin-bottom: 10px; padding: 0 15px;">
                                    التقارير المالية
                                </div>

                                <a href="{{ route('tenant.reports.generate', 'تقرير_التدفقات_النقدية') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.generate') && request()->route('reportType') == 'تقرير_التدفقات_النقدية' ? 'active' : '' }}">
                                    <i class="fas fa-money-bill-wave"></i>
                                    التدفقات النقدية
                                </a>

                                <a href="{{ route('tenant.reports.generate', 'الذمم_المدينة') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.generate') && request()->route('reportType') == 'الذمم_المدينة' ? 'active' : '' }}">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    الذمم المدينة
                                </a>

                                <a href="{{ route('tenant.reports.generate', 'الميزانية_العمومية') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.generate') && request()->route('reportType') == 'الميزانية_العمومية' ? 'active' : '' }}">
                                    <i class="fas fa-balance-scale"></i>
                                    الميزانية العمومية
                                </a>
                            </div>

                            <!-- Inventory Reports Subsection -->
                            <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 15px;">
                                <div style="color: rgba(255,255,255,0.7); font-size: 12px; font-weight: 600; margin-bottom: 10px; padding: 0 15px;">
                                    تقارير المخزون
                                </div>

                                <a href="{{ route('tenant.reports.generate', 'تقرير_مستويات_المخزون') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.generate') && request()->route('reportType') == 'تقرير_مستويات_المخزون' ? 'active' : '' }}">
                                    <i class="fas fa-boxes"></i>
                                    مستويات المخزون
                                </a>

                                <a href="{{ route('tenant.reports.generate', 'حركات_المخزون') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.generate') && request()->route('reportType') == 'حركات_المخزون' ? 'active' : '' }}">
                                    <i class="fas fa-exchange-alt"></i>
                                    حركات المخزون
                                </a>

                                <a href="{{ route('tenant.reports.generate', 'تنبيهات_النفاد') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.generate') && request()->route('reportType') == 'تنبيهات_النفاد' ? 'active' : '' }}">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    تنبيهات النفاد
                                </a>
                            </div>

                            <!-- Products Reports Subsection -->
                            <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 15px;">
                                <div style="color: rgba(255,255,255,0.7); font-size: 12px; font-weight: 600; margin-bottom: 10px; padding: 0 15px;">
                                    تقارير المنتجات
                                </div>

                                <a href="{{ route('tenant.reports.generate', 'المنتجات_الأكثر_مبيعاً') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.generate') && request()->route('reportType') == 'المنتجات_الأكثر_مبيعاً' ? 'active' : '' }}">
                                    <i class="fas fa-trophy"></i>
                                    المنتجات الأكثر مبيعاً
                                </a>

                                <a href="{{ route('tenant.reports.generate', 'تحليل_ربحية_المنتجات') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.generate') && request()->route('reportType') == 'تحليل_ربحية_المنتجات' ? 'active' : '' }}">
                                    <i class="fas fa-chart-line"></i>
                                    تحليل الربحية
                                </a>
                            </div>

                            <!-- Report Tools Subsection -->
                            <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 15px;">
                                <div style="color: rgba(255,255,255,0.7); font-size: 12px; font-weight: 600; margin-bottom: 10px; padding: 0 15px;">
                                    أدوات التقارير
                                </div>

                                <a href="{{ route('tenant.reports.builder') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.builder') ? 'active' : '' }}">
                                    <i class="fas fa-cogs"></i>
                                    منشئ التقارير
                                </a>

                                <a href="{{ route('tenant.reports.history') }}"
                                   class="nav-link {{ request()->routeIs('tenant.reports.history') ? 'active' : '' }}">
                                    <i class="fas fa-history"></i>
                                    سجل التقارير
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- System Guide Section -->
                    <div class="nav-section">
                        <div class="nav-section-title" onclick="toggleSection(this)">
                            <i class="fas fa-graduation-cap"></i>
                            كيفية استخدام النظام
                        </div>
                        <div class="nav-section-content">
                            <a href="{{ route('tenant.system-guide.index') }}"
                               class="nav-link {{ request()->routeIs('tenant.system-guide.index') ? 'active' : '' }}">
                                <i class="fas fa-home"></i>
                                الصفحة الرئيسية
                            </a>

                            <a href="{{ route('tenant.system-guide.introduction') }}"
                               class="nav-link {{ request()->routeIs('tenant.system-guide.introduction') ? 'active' : '' }}">
                                <i class="fas fa-info-circle"></i>
                                مقدمة عن النظام
                            </a>

                            <a href="{{ route('tenant.system-guide.videos') }}"
                               class="nav-link {{ request()->routeIs('tenant.system-guide.videos') ? 'active' : '' }}">
                                <i class="fas fa-play-circle"></i>
                                الفيديوهات التعليمية
                            </a>

                            <a href="{{ route('tenant.system-guide.faq') }}"
                               class="nav-link {{ request()->routeIs('tenant.system-guide.faq') ? 'active' : '' }}">
                                <i class="fas fa-question-circle"></i>
                                الأسئلة الشائعة
                            </a>

                            <a href="{{ route('tenant.system-guide.new-tenant-guide') }}"
                               class="nav-link {{ request()->routeIs('tenant.system-guide.new-tenant-guide') ? 'active' : '' }}">
                                <i class="fas fa-rocket"></i>
                                دليل المستأجر الجديد
                            </a>

                            <a href="{{ route('tenant.system-guide.download-manual') }}"
                               class="nav-link">
                                <i class="fas fa-download"></i>
                                تحميل دليل المستخدم
                            </a>

                            <!-- Module Guides Subsection -->
                            <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 15px;">
                                <div style="color: rgba(255,255,255,0.7); font-size: 12px; font-weight: 600; margin-bottom: 10px; padding: 0 15px;">
                                    أدلة الوحدات
                                </div>

                                <a href="{{ route('tenant.system-guide.module', 'sales') }}"
                                   class="nav-link">
                                    <i class="fas fa-shopping-bag"></i>
                                    دليل المبيعات
                                </a>

                                <a href="{{ route('tenant.system-guide.module', 'inventory') }}"
                                   class="nav-link">
                                    <i class="fas fa-warehouse"></i>
                                    دليل المخزون
                                </a>

                                <a href="{{ route('tenant.system-guide.module', 'targets') }}"
                                   class="nav-link">
                                    <i class="fas fa-bullseye"></i>
                                    دليل الأهداف
                                </a>

                                <a href="{{ route('tenant.system-guide.module', 'accounting') }}"
                                   class="nav-link">
                                    <i class="fas fa-calculator"></i>
                                    دليل المحاسبة
                                </a>

                                <a href="{{ route('tenant.system-guide.module', 'hr') }}"
                                   class="nav-link">
                                    <i class="fas fa-users"></i>
                                    دليل الموارد البشرية
                                </a>

                                <a href="{{ route('tenant.system-guide.module', 'regulatory') }}"
                                   class="nav-link">
                                    <i class="fas fa-shield-alt"></i>
                                    دليل الشؤون التنظيمية
                                </a>

                                <a href="{{ route('tenant.system-guide.module', 'analytics') }}"
                                   class="nav-link">
                                    <i class="fas fa-brain"></i>
                                    دليل الذكاء الاصطناعي
                                </a>
                            </div>
                        </div>
                    </div>


                @endif
            </nav>
        </div>
    </div>






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

            // Safe fallback for copyErrorInfo used in some pages
            if (typeof window.copyErrorInfo !== 'function') {
                window.copyErrorInfo = function(selector){
                    try {
                        var el = selector ? document.querySelector(selector) : null;
                        var text = el ? (el.innerText || el.textContent || '') : '';
                        if (!text) {
                            var errEl = document.querySelector('.alert.alert-danger');
                            text = errEl ? (errEl.innerText || errEl.textContent || '') : (document.body.innerText || '');
                        }
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(text).then(function(){
                                alert('تم نسخ معلومات الخطأ');
                            }).catch(function(){ alert('تعذر النسخ'); });
                        } else {
                            // Fallback textarea
                            var ta = document.createElement('textarea');
                            ta.value = text;
                            document.body.appendChild(ta);
                            ta.select();
                            try { document.execCommand('copy'); alert('تم نسخ معلومات الخطأ'); } catch(e) {}
                            document.body.removeChild(ta);
                        }
                    } catch (e) {
                        console.error('copyErrorInfo fallback failed', e);
                    }
                    return false;
                };
            }

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
