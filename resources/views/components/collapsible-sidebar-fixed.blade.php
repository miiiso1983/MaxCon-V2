{{-- Collapsible Sidebar Component --}}
<div x-data="sidebarData()" 
     x-init="initSidebar()"
     class="fixed right-0 top-0 h-full z-50 transition-all duration-300 ease-in-out"
     :class="isCollapsed ? 'w-16' : 'w-72'">
     
    <!-- Sidebar Container -->
    <div class="h-full shadow-2xl flex flex-col" style="background: linear-gradient(180deg, #5b73e8 0%, #4c63d2 30%, #667eea 70%, #7c3aed 100%);">
        
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
                <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-3" style="background: linear-gradient(135deg, #fbbf24, #f59e0b, #ea580c); box-shadow: 0 10px 30px rgba(251, 191, 36, 0.3);">
                    <i class="fas fa-crown text-2xl text-white"></i>
                </div>
                <h1 class="font-bold text-xl mb-1" style="color: #fbbf24; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">MaxCon</h1>
                <h2 class="font-bold text-lg mb-2" style="color: #f59e0b; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Master</h2>
                <p class="text-sm" style="color: rgba(255,255,255,0.9); text-shadow: 0 1px 2px rgba(0,0,0,0.3);">إدارة النظام الرئيسية</p>
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
                    <i class="fas fa-shopping-cart"></i>
                    <span x-show="!isCollapsed" x-transition class="mr-3 flex-1">إدارة المبيعات</span>
                    <i x-show="!isCollapsed" :class="open ? 'fas fa-chevron-down' : 'fas fa-chevron-left'" class="text-xs transition-transform"></i>
                </button>
                
                <div x-show="open && !isCollapsed" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="{{ route('tenant.sales.orders.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.sales.orders.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice"></i>
                        <span class="mr-3">أوامر البيع</span>
                    </a>
                    <a href="{{ route('tenant.sales.invoices.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.sales.invoices.*') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        <span class="mr-3">الفواتير</span>
                    </a>
                    <a href="{{ route('tenant.sales.customers.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.sales.customers.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span class="mr-3">العملاء</span>
                    </a>
                    <a href="{{ route('tenant.sales.products.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.sales.products.*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span class="mr-3">المنتجات</span>
                    </a>
                </div>
            </div>

            <!-- Purchasing Management -->
            <div x-data="{ open: {{ request()->routeIs('tenant.purchasing.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="nav-item w-full text-right {{ request()->routeIs('tenant.purchasing.*') ? 'active' : '' }}"
                        :data-tooltip="isCollapsed ? 'إدارة المشتريات' : null">
                    <i class="fas fa-truck"></i>
                    <span x-show="!isCollapsed" x-transition class="mr-3 flex-1">إدارة المشتريات</span>
                    <i x-show="!isCollapsed" :class="open ? 'fas fa-chevron-down' : 'fas fa-chevron-left'" class="text-xs transition-transform"></i>
                </button>
                
                <div x-show="open && !isCollapsed" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="{{ route('tenant.purchasing.suppliers.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.purchasing.suppliers.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span class="mr-3">الموردين</span>
                    </a>
                    <a href="{{ route('tenant.purchasing.purchase-requests.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.purchasing.purchase-requests.*') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <span class="mr-3">طلبات الشراء</span>
                    </a>
                </div>
            </div>

            <!-- Inventory Management -->
            <div x-data="{ open: {{ request()->routeIs('tenant.inventory.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="nav-item w-full text-right {{ request()->routeIs('tenant.inventory.*') ? 'active' : '' }}"
                        :data-tooltip="isCollapsed ? 'إدارة المخزون' : null">
                    <i class="fas fa-boxes"></i>
                    <span x-show="!isCollapsed" x-transition class="mr-3 flex-1">إدارة المخزون</span>
                    <i x-show="!isCollapsed" :class="open ? 'fas fa-chevron-down' : 'fas fa-chevron-left'" class="text-xs transition-transform"></i>
                </button>
                
                <div x-show="open && !isCollapsed" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="{{ route('tenant.inventory.inventory-products.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.inventory.inventory-products.*') ? 'active' : '' }}">
                        <i class="fas fa-cube"></i>
                        <span class="mr-3">المنتجات</span>
                    </a>
                    <a href="{{ route('tenant.inventory.movements.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.inventory.movements.*') ? 'active' : '' }}">
                        <i class="fas fa-exchange-alt"></i>
                        <span class="mr-3">حركات المخزون</span>
                    </a>
                    <a href="{{ route('tenant.inventory.warehouses.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.inventory.warehouses.*') ? 'active' : '' }}">
                        <i class="fas fa-warehouse"></i>
                        <span class="mr-3">المستودعات</span>
                    </a>
                </div>
            </div>

            <!-- Accounting -->
            <div x-data="{ open: {{ request()->routeIs('tenant.accounting.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="nav-item w-full text-right {{ request()->routeIs('tenant.accounting.*') ? 'active' : '' }}"
                        :data-tooltip="isCollapsed ? 'المحاسبة' : null">
                    <i class="fas fa-calculator"></i>
                    <span x-show="!isCollapsed" x-transition class="mr-3 flex-1">المحاسبة</span>
                    <i x-show="!isCollapsed" :class="open ? 'fas fa-chevron-down' : 'fas fa-chevron-left'" class="text-xs transition-transform"></i>
                </button>
                
                <div x-show="open && !isCollapsed" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="{{ route('tenant.accounting.chart-of-accounts.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.accounting.chart-of-accounts.*') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        <span class="mr-3">دليل الحسابات</span>
                    </a>
                    <a href="{{ route('tenant.accounting.journal-entries.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.accounting.journal-entries.*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span class="mr-3">القيود اليومية</span>
                    </a>
                    <a href="{{ route('tenant.accounting.cost-centers.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.accounting.cost-centers.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span class="mr-3">مراكز التكلفة</span>
                    </a>
                </div>
            </div>

            <!-- HR Management -->
            <div x-data="{ open: {{ request()->routeIs('tenant.hr.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="nav-item w-full text-right {{ request()->routeIs('tenant.hr.*') ? 'active' : '' }}"
                        :data-tooltip="isCollapsed ? 'الموارد البشرية' : null">
                    <i class="fas fa-users"></i>
                    <span x-show="!isCollapsed" x-transition class="mr-3 flex-1">الموارد البشرية</span>
                    <i x-show="!isCollapsed" :class="open ? 'fas fa-chevron-down' : 'fas fa-chevron-left'" class="text-xs transition-transform"></i>
                </button>
                
                <div x-show="open && !isCollapsed" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="{{ route('tenant.hr.employees.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.hr.employees.*') ? 'active' : '' }}">
                        <i class="fas fa-user-tie"></i>
                        <span class="mr-3">الموظفين</span>
                    </a>
                    <a href="{{ route('tenant.hr.departments.index') }}" class="nav-item text-sm {{ request()->routeIs('tenant.hr.departments.*') ? 'active' : '' }}">
                        <i class="fas fa-sitemap"></i>
                        <span class="mr-3">الأقسام</span>
                    </a>
                </div>
            </div>

            <!-- System Guide -->
            <div x-data="{ open: {{ request()->routeIs('tenant.system-guide.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="nav-item w-full text-right {{ request()->routeIs('tenant.system-guide.*') ? 'active' : '' }}"
                        :data-tooltip="isCollapsed ? 'كيفية استخدام النظام' : null">
                    <i class="fas fa-question-circle"></i>
                    <span x-show="!isCollapsed" x-transition class="mr-3 flex-1">كيفية استخدام النظام</span>
                    <i x-show="!isCollapsed" :class="open ? 'fas fa-chevron-down' : 'fas fa-chevron-left'" class="text-xs transition-transform"></i>
                </button>
                
                <div x-show="open && !isCollapsed" x-transition class="mr-6 mt-1 space-y-1">
                    <a href="{{ route('tenant.system-guide.new-tenant-guide') }}" class="nav-item text-sm {{ request()->routeIs('tenant.system-guide.new-tenant-guide') ? 'active' : '' }}">
                        <i class="fas fa-rocket"></i>
                        <span class="mr-3">دليل المستأجر الجديد</span>
                    </a>
                    <a href="{{ route('tenant.system-guide.videos') }}" class="nav-item text-sm {{ request()->routeIs('tenant.system-guide.videos') ? 'active' : '' }}">
                        <i class="fas fa-play-circle"></i>
                        <span class="mr-3">الفيديوهات التعليمية</span>
                    </a>
                </div>
            </div>
            
        </nav>
        
        <!-- Footer -->
        <div class="p-4 border-t border-blue-500/30" x-show="!isCollapsed" x-transition>
            <div class="text-center text-blue-200 text-xs">
                <p>© 2024 MaxCon ERP</p>
                <p>جميع الحقوق محفوظة</p>
            </div>
        </div>
    </div>
    
    <!-- Mobile Overlay -->
    <div x-show="!isCollapsed" 
         @click="isCollapsed = true"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"></div>
</div>

<style>
    /* Navigation Item Styles */
    .nav-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        border-radius: 0.625rem;
        color: rgba(255, 255, 255, 0.85);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        margin: 0.125rem 0.5rem;
        font-weight: 500;
        border-right: 3px solid transparent;
        position: relative;
    }

    .nav-item:hover {
        color: white;
        background-color: rgba(255, 255, 255, 0.12);
        transform: translateX(-0.1875rem);
        text-decoration: none;
        border-right-color: rgba(255, 255, 255, 0.3);
    }

    .nav-item.active {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
        font-weight: 600;
        border-right-color: #fbbf24;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Tooltip for collapsed state */
    .nav-item[data-tooltip]:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        white-space: nowrap;
        z-index: 1000;
        margin-left: 0.5rem;
    }

    /* Scrollbar styling */
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
