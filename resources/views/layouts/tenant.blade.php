<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MaxCon ERP')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        /* Reset and hide any conflicting elements */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Hide the dark sidebar - user wants the blue one */
        .sidebar {
            display: none !important;
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            background: #f8fafc;
            color: #1f2937;
            line-height: 1.6;
        }
        
        .main-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 280px !important;
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%) !important;
            color: white !important;
            padding: 20px 0 !important;
            position: fixed !important;
            top: 0 !important;
            right: 0 !important;
            height: 100vh !important;
            overflow-y: auto !important;
            z-index: 1000 !important;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1) !important;
            transition: all 0.3s ease !important;
            border: none !important;
            margin: 0 !important;
            display: block !important;
            box-sizing: border-box !important;
        }
        
        .sidebar-header {
            padding: 0 20px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .sidebar-header h2 {
            font-size: 24px;
            font-weight: 700;
            color: white;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: flex !important;
            align-items: center !important;
            padding: 12px 20px !important;
            color: rgba(255,255,255,0.8) !important;
            text-decoration: none !important;
            transition: all 0.3s ease !important;
            border-right: 3px solid transparent !important;
            border-radius: 0 !important;
            margin: 0 !important;
            font-weight: 500 !important;
            background: transparent !important;
            width: auto !important;
            height: auto !important;
            box-sizing: border-box !important;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.1);
            color: white;
            border-right-color: #10b981;
        }
        
        .sidebar-menu a i {
            margin-left: 12px;
            width: 20px;
            text-align: center;
        }

        /* Menu Sections & Submenus */
        .menu-section {
            position: relative;
        }

        .menu-toggle {
            position: relative;
            justify-content: space-between;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
            font-size: 12px;
        }

        .menu-toggle.active .toggle-icon {
            transform: rotate(180deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0,0,0,0.2);
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .submenu.open {
            max-height: 500px;
        }

        .submenu-item {
            padding: 10px 20px 10px 50px !important;
            font-size: 14px;
            border-right: 3px solid transparent !important;
        }

        .submenu-item:hover,
        .submenu-item.active {
            background: rgba(255,255,255,0.15) !important;
            border-right-color: #10b981 !important;
        }

        .submenu-item i {
            margin-left: 10px;
            width: 16px;
            font-size: 12px;
        }

        .submenu-section {
            padding: 8px 20px 5px 50px;
        }

        .submenu-title {
            font-size: 11px;
            color: rgba(255,255,255,0.6);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .content-area {
            flex: 1;
            margin-right: 280px;
            min-height: 100vh;
        }
        
        .top-navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        .main-content {
            padding: 30px;
        }
        
        .breadcrumb {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .breadcrumb a {
            color: #6b7280;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            color: #374151;
        }
        
        .breadcrumb .current {
            color: #374151;
            font-weight: 600;
        }
        
        /* Override any external CSS frameworks */
        .sidebar * {
            box-sizing: border-box !important;
        }

        .sidebar ul {
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .sidebar li {
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
        }

        /* Prevent Tailwind or Bootstrap interference */
        .sidebar .sidebar-menu {
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .sidebar .sidebar-menu li {
            margin-bottom: 5px !important;
            list-style: none !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .content-area {
                margin-right: 0;
            }
            
            .main-content {
                padding: 20px 15px;
            }
        }
        
        /* Utility Classes */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .btn-success {
            background: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background: #059669;
        }
        
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .alert-warning {
            background: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        
        .alert-info {
            background: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #93c5fd;
        }
    </style>
    
    @stack('styles')

    <!-- System Guide Assets -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css" rel="stylesheet">
</head>
<body>
    <div class="main-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-chart-line"></i> MaxCon ERP</h2>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('tenant.dashboard') }}" class="{{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        الرئيسية
                    </a>
                </li>

                <!-- إدارة المبيعات -->
                <li class="menu-section">
                    <a href="#" class="menu-toggle {{ request()->routeIs('tenant.sales.*') ? 'active' : '' }}" onclick="toggleSubmenu(this)">
                        <i class="fas fa-shopping-cart"></i>
                        إدارة المبيعات
                        <i class="fas fa-chevron-down toggle-icon"></i>
                    </a>
                    <ul class="submenu {{ request()->routeIs('tenant.sales.*') ? 'open' : '' }}">
                        <li>
                            <a href="#" onclick="alert('الطلبات - قريباً')" class="submenu-item">
                                <i class="fas fa-file-invoice"></i>
                                الطلبات
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="alert('الفواتير - قريباً')" class="submenu-item">
                                <i class="fas fa-receipt"></i>
                                الفواتير
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="alert('العملاء - قريباً')" class="submenu-item">
                                <i class="fas fa-users"></i>
                                العملاء
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="alert('المنتجات - قريباً')" class="submenu-item">
                                <i class="fas fa-box"></i>
                                المنتجات
                            </a>
                        </li>

                        <!-- أهداف البيع -->
                        <li class="submenu-section">
                            <span class="submenu-title">أهداف البيع</span>
                        </li>
                        <li>
                            <a href="{{ route('tenant.sales.targets.index') }}" class="submenu-item {{ request()->routeIs('tenant.sales.targets.index') ? 'active' : '' }}">
                                <i class="fas fa-bullseye"></i>
                                قائمة الأهداف
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.sales.targets.create') }}" class="submenu-item {{ request()->routeIs('tenant.sales.targets.create') ? 'active' : '' }}">
                                <i class="fas fa-plus-circle"></i>
                                إنشاء هدف جديد
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.sales.targets.dashboard') }}" class="submenu-item {{ request()->routeIs('tenant.sales.targets.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-chart-line"></i>
                                لوحة الأهداف
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.sales.targets.reports') }}" class="submenu-item {{ request()->routeIs('tenant.sales.targets.reports') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar"></i>
                                تقارير الأهداف
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- وحدة المخزون -->
                <li>
                    <a href="#" onclick="alert('وحدة المخزون - قريباً')">
                        <i class="fas fa-boxes"></i>
                        إدارة المخزون
                    </a>
                </li>

                <!-- وحدة المحاسبة -->
                <li>
                    <a href="#" onclick="alert('وحدة المحاسبة - قريباً')">
                        <i class="fas fa-calculator"></i>
                        المحاسبة
                    </a>
                </li>

                <!-- نظام التقارير الديناميكي -->
                <li class="has-submenu">
                    <a href="#" class="menu-toggle">
                        <i class="fas fa-chart-line"></i>
                        نظام التقارير الديناميكي
                        <i class="fas fa-chevron-down submenu-arrow"></i>
                    </a>
                    <ul class="submenu">
                        <!-- لوحة التقارير الرئيسية -->
                        <li>
                            <a href="{{ route('tenant.reports.index') }}" class="submenu-item {{ request()->routeIs('tenant.reports.index') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt"></i>
                                لوحة التقارير
                            </a>
                        </li>

                        <!-- تقارير المبيعات -->
                        <li class="submenu-section">
                            <span class="submenu-title">تقارير المبيعات</span>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.generate', 'تقرير_المبيعات_اليومية') }}" class="submenu-item">
                                <i class="fas fa-chart-bar"></i>
                                المبيعات اليومية
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.generate', 'تقرير_أداء_المندوبين') }}" class="submenu-item">
                                <i class="fas fa-users"></i>
                                أداء المندوبين
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.generate', 'العملاء_الأكثر_شراءً') }}" class="submenu-item">
                                <i class="fas fa-star"></i>
                                العملاء الأكثر شراءً
                            </a>
                        </li>

                        <!-- التقارير المالية -->
                        <li class="submenu-section">
                            <span class="submenu-title">التقارير المالية</span>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.generate', 'تقرير_التدفقات_النقدية') }}" class="submenu-item">
                                <i class="fas fa-money-bill-wave"></i>
                                التدفقات النقدية
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.generate', 'الذمم_المدينة') }}" class="submenu-item">
                                <i class="fas fa-file-invoice-dollar"></i>
                                الذمم المدينة
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.generate', 'الميزانية_العمومية') }}" class="submenu-item">
                                <i class="fas fa-balance-scale"></i>
                                الميزانية العمومية
                            </a>
                        </li>

                        <!-- تقارير المخزون -->
                        <li class="submenu-section">
                            <span class="submenu-title">تقارير المخزون</span>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.generate', 'تقرير_مستويات_المخزون') }}" class="submenu-item">
                                <i class="fas fa-boxes"></i>
                                مستويات المخزون
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.generate', 'حركات_المخزون') }}" class="submenu-item">
                                <i class="fas fa-exchange-alt"></i>
                                حركات المخزون
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.generate', 'تنبيهات_النفاد') }}" class="submenu-item">
                                <i class="fas fa-exclamation-triangle"></i>
                                تنبيهات النفاد
                            </a>
                        </li>

                        <!-- تقارير المنتجات -->
                        <li class="submenu-section">
                            <span class="submenu-title">تقارير المنتجات</span>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.generate', 'المنتجات_الأكثر_مبيعاً') }}" class="submenu-item">
                                <i class="fas fa-trophy"></i>
                                المنتجات الأكثر مبيعاً
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.generate', 'تحليل_ربحية_المنتجات') }}" class="submenu-item">
                                <i class="fas fa-chart-line"></i>
                                تحليل الربحية
                            </a>
                        </li>

                        <!-- أدوات التقارير -->
                        <li class="submenu-section">
                            <span class="submenu-title">أدوات التقارير</span>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.builder') }}" class="submenu-item">
                                <i class="fas fa-cogs"></i>
                                منشئ التقارير
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.reports.history') }}" class="submenu-item">
                                <i class="fas fa-history"></i>
                                سجل التقارير
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- كيفية استخدام النظام -->
                <li class="menu-section">
                    <a href="#" class="menu-toggle {{ request()->routeIs('tenant.system-guide.*') ? 'active' : '' }}" onclick="toggleSubmenu(this)">
                        <i class="fas fa-graduation-cap"></i>
                        كيفية استخدام النظام
                        <i class="fas fa-chevron-down toggle-icon"></i>
                    </a>
                    <ul class="submenu {{ request()->routeIs('tenant.system-guide.*') ? 'open' : '' }}">
                        <li>
                            <a href="{{ route('tenant.system-guide.index') }}" class="submenu-item {{ request()->routeIs('tenant.system-guide.index') ? 'active' : '' }}">
                                <i class="fas fa-home"></i>
                                الصفحة الرئيسية
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.system-guide.introduction') }}" class="submenu-item {{ request()->routeIs('tenant.system-guide.introduction') ? 'active' : '' }}">
                                <i class="fas fa-info-circle"></i>
                                مقدمة عن النظام
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.system-guide.videos') }}" class="submenu-item {{ request()->routeIs('tenant.system-guide.videos') ? 'active' : '' }}">
                                <i class="fas fa-play-circle"></i>
                                الفيديوهات التعليمية
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.system-guide.faq') }}" class="submenu-item {{ request()->routeIs('tenant.system-guide.faq') ? 'active' : '' }}">
                                <i class="fas fa-question-circle"></i>
                                الأسئلة الشائعة
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.system-guide.download-manual') }}" class="submenu-item">
                                <i class="fas fa-download"></i>
                                تحميل دليل المستخدم
                            </a>
                        </li>

                        <!-- أدلة الوحدات -->
                        <li class="submenu-section">
                            <span class="submenu-title">أدلة الوحدات</span>
                        </li>
                        <li>
                            <a href="{{ route('tenant.system-guide.module', 'sales') }}" class="submenu-item">
                                <i class="fas fa-shopping-bag"></i>
                                دليل المبيعات
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.system-guide.module', 'inventory') }}" class="submenu-item">
                                <i class="fas fa-warehouse"></i>
                                دليل المخزون
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.system-guide.module', 'targets') }}" class="submenu-item">
                                <i class="fas fa-bullseye"></i>
                                دليل الأهداف
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tenant.system-guide.module', 'accounting') }}" class="submenu-item">
                                <i class="fas fa-calculator"></i>
                                دليل المحاسبة
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- الذكاء الاصطناعي والتحليلات -->
                <li>
                    <a href="{{ route('tenant.analytics.dashboard') }}">
                        <i class="fas fa-brain"></i>
                        الذكاء الاصطناعي والتحليلات
                    </a>
                </li>

                <!-- الإعدادات -->
                <li>
                    <a href="{{ route('tenant.settings.index') }}">
                        <i class="fas fa-cog"></i>
                        الإعدادات
                    </a>
                </li>
            </ul>
        </aside>
        
        <!-- Main Content -->
        <div class="content-area">
            <!-- Top Navbar -->
            <nav class="top-navbar">
                <div>
                    <button onclick="toggleSidebar()" class="btn" style="background: #f3f4f6; color: #374151;">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                
                <div class="user-info">
                    <span>مرحباً، {{ auth()->user()->name ?? 'المستخدم' }}</span>
                    <div class="user-avatar">
                        {{ substr(auth()->user()->name ?? 'M', 0, 1) }}
                    </div>
                    <a href="{{ route('logout.confirm') }}" class="btn btn-danger" style="padding: 8px 15px; font-size: 14px;">
                        <i class="fas fa-sign-out-alt"></i>
                        خروج
                    </a>
                </div>
            </nav>
            
            <!-- Breadcrumb -->
            @if(!request()->routeIs('tenant.dashboard'))
            <div class="breadcrumb">
                <a href="{{ route('tenant.dashboard') }}">الرئيسية</a>
                @if(request()->routeIs('tenant.sales.targets.*'))
                    <span> / </span>
                    <a href="{{ route('tenant.sales.targets.index') }}">أهداف البيع</a>
                    @if(!request()->routeIs('tenant.sales.targets.index'))
                        <span> / </span>
                        <span class="current">@yield('title')</span>
                    @endif
                @else
                    <span> / </span>
                    <span class="current">@yield('title')</span>
                @endif
            </div>
            @endif
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('warning') }}
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    {{ session('info') }}
                </div>
            @endif
            
            <!-- Main Content -->
            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('open');
        }

        function toggleSubmenu(element) {
            event.preventDefault();

            const submenu = element.nextElementSibling;
            const isOpen = submenu.classList.contains('open');

            // Close all other submenus
            document.querySelectorAll('.submenu.open').forEach(menu => {
                if (menu !== submenu) {
                    menu.classList.remove('open');
                    menu.previousElementSibling.classList.remove('active');
                }
            });

            // Toggle current submenu
            if (isOpen) {
                submenu.classList.remove('open');
                element.classList.remove('active');
            } else {
                submenu.classList.add('open');
                element.classList.add('active');
            }
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('[onclick="toggleSidebar()"]');

            if (window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !toggleBtn.contains(e.target) &&
                sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
            }
        });

        // Handle responsive sidebar
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
            }
        });

        // Auto-open submenu if current page is in it
        document.addEventListener('DOMContentLoaded', function() {
            const activeSubmenuItem = document.querySelector('.submenu-item.active');
            if (activeSubmenuItem) {
                const submenu = activeSubmenuItem.closest('.submenu');
                const menuToggle = submenu.previousElementSibling;

                submenu.classList.add('open');
                menuToggle.classList.add('active');
            }
        });
    </script>

    <!-- System Guide Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>
    <script src="{{ asset('js/system-tooltips.js') }}"></script>
    <script src="{{ asset('js/interactive-tours.js') }}"></script>

    @stack('scripts')
</body>
</html>
