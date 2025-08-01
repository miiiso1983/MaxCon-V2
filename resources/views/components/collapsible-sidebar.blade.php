{{-- Collapsible Sidebar Component --}}
<div x-data="sidebarData()" 
     x-init="initSidebar()"
     class="fixed right-0 top-0 h-full z-50 transition-all duration-300 ease-in-out"
     :class="isCollapsed ? 'w-16' : 'w-72'">
     
    <!-- Sidebar Container -->
    <div class="h-full bg-gradient-to-b from-blue-600 to-blue-800 shadow-2xl flex flex-col">
        
        <!-- Header Section -->
        <div class="p-4 border-b border-blue-500/30">
            <!-- Toggle Button -->
            <button @click="toggleSidebar()" 
                    class="w-full flex items-center justify-center p-2 rounded-lg bg-blue-500/20 hover:bg-blue-500/30 transition-colors duration-200 text-white">
                <i class="fas fa-bars text-lg"></i>
                <span x-show="!isCollapsed" x-transition class="mr-3 font-semibold">القائمة</span>
            </button>
            
            <!-- Logo Section -->
            <div class="mt-4 text-center" x-show="!isCollapsed" x-transition>
                <div class="w-16 h-16 mx-auto bg-white/10 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-chart-line text-2xl text-white"></i>
                </div>
                <h2 class="text-white font-bold text-lg">MaxCon ERP</h2>
                <p class="text-blue-200 text-sm">نظام إدارة المؤسسات</p>
            </div>
            
            <!-- Collapsed Logo -->
            <div class="mt-4 text-center" x-show="isCollapsed" x-transition>
                <div class="w-8 h-8 mx-auto bg-white/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1">
            
            <!-- Dashboard -->
            <a href="{{ route('tenant.dashboard') }}"
               class="nav-item {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}"
               :data-tooltip="isCollapsed ? 'الرئيسية' : null">
                <i class="fas fa-home"></i>
                <span x-show="!isCollapsed" x-transition class="mr-3">الرئيسية</span>
            </a>
            
            <!-- Sales Management -->
            <div x-data="{ open: {{ request()->routeIs('tenant.sales.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="nav-item w-full text-right {{ request()->routeIs('tenant.sales.*') ? 'active' : '' }}"
                        :data-tooltip="isCollapsed ? 'إدارة المبيعات' : null">
                    <div class="flex items-center justify-between w-full">
                        <div class="flex items-center">
                            <i class="fas fa-shopping-cart"></i>
                            <span x-show="!isCollapsed" x-transition class="mr-3">إدارة المبيعات</span>
                        </div>
                        <i x-show="!isCollapsed"
                           x-transition
                           class="fas fa-chevron-down transition-transform duration-200"
                           :class="open ? 'rotate-180' : ''"></i>
                    </div>
                </button>
                
                <!-- Sales Submenu -->
                <div x-show="open && !isCollapsed" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     class="mr-6 mt-2 space-y-1">
                     
                    <a href="#" onclick="alert('الطلبات - قريباً')" class="submenu-item">
                        <i class="fas fa-file-invoice"></i>
                        <span class="mr-2">الطلبات</span>
                    </a>
                    
                    <a href="#" onclick="alert('الفواتير - قريباً')" class="submenu-item">
                        <i class="fas fa-receipt"></i>
                        <span class="mr-2">الفواتير</span>
                    </a>
                    
                    <a href="#" onclick="alert('العملاء - قريباً')" class="submenu-item">
                        <i class="fas fa-users"></i>
                        <span class="mr-2">العملاء</span>
                    </a>
                    
                    <a href="#" onclick="alert('المنتجات - قريباً')" class="submenu-item">
                        <i class="fas fa-box"></i>
                        <span class="mr-2">المنتجات</span>
                    </a>
                    
                    <!-- Sales Targets Subsection -->
                    <div class="border-t border-blue-400/20 pt-2 mt-3">
                        <p class="text-blue-200 text-xs font-semibold mb-2 mr-2">أهداف البيع</p>
                        
                        <a href="{{ route('tenant.sales.targets.index') }}" 
                           class="submenu-item {{ request()->routeIs('tenant.sales.targets.index') ? 'active' : '' }}">
                            <i class="fas fa-bullseye"></i>
                            <span class="mr-2">قائمة الأهداف</span>
                        </a>
                        
                        <a href="{{ route('tenant.sales.targets.create') }}" 
                           class="submenu-item {{ request()->routeIs('tenant.sales.targets.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle"></i>
                            <span class="mr-2">إنشاء هدف جديد</span>
                        </a>
                        
                        <a href="{{ route('tenant.sales.targets.dashboard') }}" 
                           class="submenu-item {{ request()->routeIs('tenant.sales.targets.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>
                            <span class="mr-2">لوحة الأهداف</span>
                        </a>
                        
                        <a href="{{ route('tenant.sales.targets.reports') }}" 
                           class="submenu-item {{ request()->routeIs('tenant.sales.targets.reports') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar"></i>
                            <span class="mr-2">تقارير الأهداف</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Inventory Management -->
            <a href="#" onclick="alert('وحدة المخزون - قريباً')"
               class="nav-item"
               :data-tooltip="isCollapsed ? 'إدارة المخزون' : null">
                <i class="fas fa-boxes"></i>
                <span x-show="!isCollapsed" x-transition class="mr-3">إدارة المخزون</span>
            </a>

            <!-- Accounting -->
            <a href="#" onclick="alert('وحدة المحاسبة - قريباً')"
               class="nav-item"
               :data-tooltip="isCollapsed ? 'المحاسبة' : null">
                <i class="fas fa-calculator"></i>
                <span x-show="!isCollapsed" x-transition class="mr-3">المحاسبة</span>
            </a>

            <!-- HR Management -->
            <a href="#" onclick="alert('إدارة الموارد البشرية - قريباً')"
               class="nav-item"
               :data-tooltip="isCollapsed ? 'الموارد البشرية' : null">
                <i class="fas fa-users-cog"></i>
                <span x-show="!isCollapsed" x-transition class="mr-3">الموارد البشرية</span>
            </a>

            <!-- System Guide -->
            <a href="#" onclick="alert('دليل النظام - قريباً')"
               class="nav-item"
               :data-tooltip="isCollapsed ? 'دليل النظام' : null">
                <i class="fas fa-question-circle"></i>
                <span x-show="!isCollapsed" x-transition class="mr-3">دليل النظام</span>
            </a>
            
        </nav>
        
        <!-- Footer Section -->
        <div class="p-4 border-t border-blue-500/30" x-show="!isCollapsed" x-transition>
            <div class="text-center text-blue-200 text-xs">
                <p>MaxCon ERP v2.0</p>
                <p>© 2024 جميع الحقوق محفوظة</p>
            </div>
        </div>
        
        <!-- Collapsed Footer -->
        <div class="p-2 border-t border-blue-500/30 text-center" x-show="isCollapsed" x-transition>
            <i class="fas fa-info-circle text-blue-200 text-xs"></i>
        </div>
    </div>
    
    <!-- Mobile Overlay -->
    <div x-show="!isCollapsed && window.innerWidth < 768" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="isCollapsed = true"
         class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"></div>
</div>

<style>
    /* Navigation Item Styles */
    .nav-item {
        display: flex;
        align-items: center;
        padding: 0.625rem 0.75rem;
        border-radius: 0.5rem;
        color: rgba(255, 255, 255, 0.9);
        transition: all 0.2s ease;
        cursor: pointer;
        text-decoration: none;
    }

    .nav-item:hover {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(0.25rem);
        text-decoration: none;
    }

    .nav-item.active {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        font-weight: 600;
    }

    /* Submenu Item Styles */
    .submenu-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        color: rgba(191, 219, 254, 1);
        transition: all 0.2s ease;
        font-size: 0.875rem;
        text-decoration: none;
    }

    .submenu-item:hover {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(0.25rem);
        text-decoration: none;
    }

    .submenu-item.active {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
        font-weight: 500;
    }
    
    /* Custom Scrollbar */
    nav::-webkit-scrollbar {
        width: 4px;
    }
    
    nav::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 2px;
    }
    
    nav::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
    }
    
    nav::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }
</style>

<script>
    function sidebarData() {
        return {
            isCollapsed: false,
            
            initSidebar() {
                // Load saved state from localStorage
                const savedState = localStorage.getItem('sidebarCollapsed');
                if (savedState !== null) {
                    this.isCollapsed = JSON.parse(savedState);
                }
                
                // Auto-collapse on mobile
                this.handleResize();
                window.addEventListener('resize', () => this.handleResize());
            },
            
            toggleSidebar() {
                this.isCollapsed = !this.isCollapsed;
                localStorage.setItem('sidebarCollapsed', JSON.stringify(this.isCollapsed));

                // Dispatch custom event for content area adjustment
                window.dispatchEvent(new CustomEvent('sidebarToggled', {
                    detail: { isCollapsed: this.isCollapsed }
                }));
            },
            
            handleResize() {
                if (window.innerWidth < 768) {
                    this.isCollapsed = true;
                }
            }
        }
    }
</script>
