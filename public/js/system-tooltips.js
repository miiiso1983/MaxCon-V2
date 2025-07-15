/**
 * System Tooltips and Interactive Help
 * 
 * Provides contextual help and tooltips throughout the system
 */

class SystemTooltips {
    constructor() {
        this.tooltips = new Map();
        this.isEnabled = true;
        this.currentTooltip = null;
        this.init();
    }

    init() {
        this.loadTooltipData();
        this.setupEventListeners();
        this.createTooltipContainer();
        this.initializeTooltips();
    }

    loadTooltipData() {
        // Tooltip data for different modules
        this.tooltipData = {
            // Sales Module
            'sales-customer-add': {
                title: 'إضافة عميل جديد',
                content: 'انقر هنا لإضافة عميل جديد. تأكد من إدخال جميع المعلومات المطلوبة مثل الاسم والهاتف والعنوان.',
                position: 'bottom',
                type: 'info'
            },
            'sales-invoice-create': {
                title: 'إنشاء فاتورة',
                content: 'يمكنك إنشاء فاتورة جديدة من هنا. اختر العميل أولاً ثم أضف المنتجات والكميات.',
                position: 'bottom',
                type: 'success'
            },
            'sales-payment-track': {
                title: 'تتبع المدفوعات',
                content: 'راجع حالة المدفوعات والمستحقات من هذا القسم. يمكنك إضافة دفعات جديدة أو تحديث الحالة.',
                position: 'left',
                type: 'warning'
            },

            // Inventory Module
            'inventory-product-add': {
                title: 'إضافة منتج جديد',
                content: 'أضف منتجاً جديداً إلى المخزون. لا تنس تحديد الفئة ووحدة القياس والسعر.',
                position: 'bottom',
                type: 'info'
            },
            'inventory-stock-alert': {
                title: 'تنبيهات المخزون',
                content: 'هذا القسم يعرض المنتجات التي تحتاج إلى إعادة تموين. راجعها بانتظام لتجنب نفاد المخزون.',
                position: 'top',
                type: 'danger'
            },
            'inventory-movement': {
                title: 'حركات المخزون',
                content: 'سجل دخول وخروج البضائع من هنا. تأكد من دقة الكميات والتواريخ.',
                position: 'right',
                type: 'info'
            },

            // Targets Module
            'targets-create': {
                title: 'إنشاء هدف جديد',
                content: 'حدد أهدافاً واقعية وقابلة للقياس. يمكنك تحديد أهداف للمنتجات أو المندوبين أو الفرق.',
                position: 'bottom',
                type: 'success'
            },
            'targets-progress': {
                title: 'تتبع التقدم',
                content: 'راقب تقدم الأهداف من هنا. الألوان تشير إلى مستوى الإنجاز: أخضر (ممتاز)، أصفر (جيد)، أحمر (يحتاج تحسين).',
                position: 'top',
                type: 'info'
            },

            // Accounting Module
            'accounting-entry': {
                title: 'إنشاء قيد محاسبي',
                content: 'تأكد من توازن القيد قبل الحفظ. مجموع المدين يجب أن يساوي مجموع الدائن.',
                position: 'bottom',
                type: 'warning'
            },
            'accounting-reports': {
                title: 'التقارير المالية',
                content: 'اختر الفترة الزمنية المناسبة للحصول على تقارير دقيقة. يمكنك تصدير التقارير بصيغة PDF أو Excel.',
                position: 'left',
                type: 'info'
            },

            // General System
            'system-search': {
                title: 'البحث السريع',
                content: 'استخدم البحث السريع للعثور على العملاء، المنتجات، أو الفواتير بسرعة.',
                position: 'bottom',
                type: 'info'
            },
            'system-notifications': {
                title: 'الإشعارات',
                content: 'تحقق من الإشعارات بانتظام للبقاء على اطلاع بآخر التحديثات والتنبيهات المهمة.',
                position: 'bottom-left',
                type: 'info'
            },
            'system-profile': {
                title: 'الملف الشخصي',
                content: 'يمكنك تحديث معلوماتك الشخصية وتغيير كلمة المرور من هنا.',
                position: 'bottom-left',
                type: 'info'
            }
        };
    }

    setupEventListeners() {
        // Show tooltip on hover
        document.addEventListener('mouseenter', (e) => {
            const element = e.target.closest('[data-tooltip]');
            if (element && this.isEnabled) {
                this.showTooltip(element);
            }
        }, true);

        // Hide tooltip on mouse leave
        document.addEventListener('mouseleave', (e) => {
            const element = e.target.closest('[data-tooltip]');
            if (element && this.currentTooltip) {
                this.hideTooltip();
            }
        }, true);

        // Hide tooltip on scroll
        document.addEventListener('scroll', () => {
            if (this.currentTooltip) {
                this.hideTooltip();
            }
        });

        // Hide tooltip on window resize
        window.addEventListener('resize', () => {
            if (this.currentTooltip) {
                this.hideTooltip();
            }
        });
    }

    createTooltipContainer() {
        this.tooltipContainer = document.createElement('div');
        this.tooltipContainer.id = 'system-tooltip';
        this.tooltipContainer.style.cssText = `
            position: absolute;
            z-index: 10000;
            background: #1e293b;
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.4;
            max-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            pointer-events: none;
            opacity: 0;
            transform: translateY(5px);
            transition: all 0.2s ease;
            display: none;
        `;
        document.body.appendChild(this.tooltipContainer);
    }

    initializeTooltips() {
        // Add tooltips to existing elements
        this.addTooltipsToElements();
        
        // Watch for new elements
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === 1) { // Element node
                        this.addTooltipsToElement(node);
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    addTooltipsToElements() {
        // Add tooltips based on common patterns
        const patterns = [
            { selector: 'button[onclick*="create"], .btn-create, .create-btn', tooltip: 'system-create' },
            { selector: 'button[onclick*="edit"], .btn-edit, .edit-btn', tooltip: 'system-edit' },
            { selector: 'button[onclick*="delete"], .btn-delete, .delete-btn', tooltip: 'system-delete' },
            { selector: '.search-input, input[type="search"]', tooltip: 'system-search' },
            { selector: '.notification-bell, .notifications', tooltip: 'system-notifications' },
            { selector: '.user-profile, .profile-dropdown', tooltip: 'system-profile' }
        ];

        patterns.forEach(pattern => {
            document.querySelectorAll(pattern.selector).forEach(element => {
                if (!element.hasAttribute('data-tooltip')) {
                    element.setAttribute('data-tooltip', pattern.tooltip);
                }
            });
        });
    }

    addTooltipsToElement(element) {
        // Add tooltips to a specific element and its children
        if (element.matches && element.matches('[data-tooltip]')) {
            // Element already has tooltip
            return;
        }

        // Check for common patterns
        const patterns = [
            { selector: 'button[onclick*="create"]', tooltip: 'system-create' },
            { selector: 'button[onclick*="edit"]', tooltip: 'system-edit' },
            { selector: 'button[onclick*="delete"]', tooltip: 'system-delete' }
        ];

        patterns.forEach(pattern => {
            if (element.matches && element.matches(pattern.selector)) {
                element.setAttribute('data-tooltip', pattern.tooltip);
            }
        });
    }

    showTooltip(element) {
        const tooltipKey = element.getAttribute('data-tooltip');
        const customTitle = element.getAttribute('data-tooltip-title');
        const customContent = element.getAttribute('data-tooltip-content');
        
        let tooltipData;
        
        if (customTitle || customContent) {
            tooltipData = {
                title: customTitle || '',
                content: customContent || customTitle || '',
                position: element.getAttribute('data-tooltip-position') || 'top',
                type: element.getAttribute('data-tooltip-type') || 'info'
            };
        } else {
            tooltipData = this.tooltipData[tooltipKey];
        }

        if (!tooltipData) return;

        this.currentTooltip = element;
        this.renderTooltip(tooltipData, element);
    }

    renderTooltip(data, element) {
        const { title, content, type } = data;
        
        // Color scheme based on type
        const colors = {
            info: { bg: '#3b82f6', border: '#2563eb' },
            success: { bg: '#10b981', border: '#059669' },
            warning: { bg: '#f59e0b', border: '#d97706' },
            danger: { bg: '#ef4444', border: '#dc2626' }
        };

        const color = colors[type] || colors.info;

        this.tooltipContainer.innerHTML = `
            ${title ? `<div style="font-weight: 600; margin-bottom: 4px; font-size: 13px;">${title}</div>` : ''}
            <div style="font-size: 12px; opacity: 0.9;">${content}</div>
        `;

        this.tooltipContainer.style.background = color.bg;
        this.tooltipContainer.style.borderLeft = `3px solid ${color.border}`;
        
        this.positionTooltip(element, data.position);
        
        this.tooltipContainer.style.display = 'block';
        
        // Animate in
        requestAnimationFrame(() => {
            this.tooltipContainer.style.opacity = '1';
            this.tooltipContainer.style.transform = 'translateY(0)';
        });
    }

    positionTooltip(element, position = 'top') {
        const rect = element.getBoundingClientRect();
        const tooltip = this.tooltipContainer;
        const tooltipRect = tooltip.getBoundingClientRect();
        
        let top, left;

        switch (position) {
            case 'bottom':
                top = rect.bottom + window.scrollY + 8;
                left = rect.left + window.scrollX + (rect.width / 2) - (tooltipRect.width / 2);
                break;
            case 'left':
                top = rect.top + window.scrollY + (rect.height / 2) - (tooltipRect.height / 2);
                left = rect.left + window.scrollX - tooltipRect.width - 8;
                break;
            case 'right':
                top = rect.top + window.scrollY + (rect.height / 2) - (tooltipRect.height / 2);
                left = rect.right + window.scrollX + 8;
                break;
            case 'bottom-left':
                top = rect.bottom + window.scrollY + 8;
                left = rect.right + window.scrollX - tooltipRect.width;
                break;
            default: // top
                top = rect.top + window.scrollY - tooltipRect.height - 8;
                left = rect.left + window.scrollX + (rect.width / 2) - (tooltipRect.width / 2);
        }

        // Keep tooltip within viewport
        const padding = 10;
        left = Math.max(padding, Math.min(left, window.innerWidth - tooltipRect.width - padding));
        top = Math.max(padding, top);

        tooltip.style.top = `${top}px`;
        tooltip.style.left = `${left}px`;
    }

    hideTooltip() {
        if (this.tooltipContainer) {
            this.tooltipContainer.style.opacity = '0';
            this.tooltipContainer.style.transform = 'translateY(5px)';
            
            setTimeout(() => {
                this.tooltipContainer.style.display = 'none';
            }, 200);
        }
        this.currentTooltip = null;
    }

    // Public methods
    enable() {
        this.isEnabled = true;
    }

    disable() {
        this.isEnabled = false;
        this.hideTooltip();
    }

    addTooltip(selector, data) {
        document.querySelectorAll(selector).forEach(element => {
            element.setAttribute('data-tooltip-title', data.title || '');
            element.setAttribute('data-tooltip-content', data.content || '');
            element.setAttribute('data-tooltip-position', data.position || 'top');
            element.setAttribute('data-tooltip-type', data.type || 'info');
        });
    }

    removeTooltip(selector) {
        document.querySelectorAll(selector).forEach(element => {
            element.removeAttribute('data-tooltip');
            element.removeAttribute('data-tooltip-title');
            element.removeAttribute('data-tooltip-content');
            element.removeAttribute('data-tooltip-position');
            element.removeAttribute('data-tooltip-type');
        });
    }
}

// Initialize tooltips when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.systemTooltips = new SystemTooltips();
});

// Helper function to add tooltips programmatically
function addTooltip(selector, title, content, options = {}) {
    if (window.systemTooltips) {
        window.systemTooltips.addTooltip(selector, {
            title,
            content,
            position: options.position || 'top',
            type: options.type || 'info'
        });
    }
}
