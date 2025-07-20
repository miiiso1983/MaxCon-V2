/**
 * MaxCon ERP - Responsive JavaScript
 * Handles mobile navigation, responsive behaviors, and touch interactions
 */

class ResponsiveManager {
    constructor() {
        this.sidebar = document.querySelector('.sidebar');
        this.sidebarOverlay = document.querySelector('.sidebar-overlay');
        this.mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        this.mainContent = document.querySelector('.main-content');
        this.isMobile = window.innerWidth <= 767;
        this.isTablet = window.innerWidth >= 768 && window.innerWidth <= 1023;
        this.isDesktop = window.innerWidth >= 1024;
        
        this.init();
    }
    
    init() {
        this.createMobileElements();
        this.bindEvents();
        this.handleResize();
        this.initTouchGestures();
        this.optimizeForMobile();
    }
    
    createMobileElements() {
        // Create mobile menu toggle if it doesn't exist
        if (!this.mobileMenuToggle) {
            this.mobileMenuToggle = document.createElement('button');
            this.mobileMenuToggle.className = 'mobile-menu-toggle';
            this.mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            this.mobileMenuToggle.setAttribute('aria-label', 'فتح القائمة');
            
            const topHeader = document.querySelector('.top-header');
            if (topHeader) {
                topHeader.insertBefore(this.mobileMenuToggle, topHeader.firstChild);
            }
        }
        
        // Create sidebar overlay if it doesn't exist
        if (!this.sidebarOverlay) {
            this.sidebarOverlay = document.createElement('div');
            this.sidebarOverlay.className = 'sidebar-overlay';
            document.body.appendChild(this.sidebarOverlay);
        }
    }
    
    bindEvents() {
        // Mobile menu toggle
        if (this.mobileMenuToggle) {
            this.mobileMenuToggle.addEventListener('click', () => {
                this.toggleSidebar();
            });
        }
        
        // Sidebar overlay click
        if (this.sidebarOverlay) {
            this.sidebarOverlay.addEventListener('click', () => {
                this.closeSidebar();
            });
        }
        
        // Window resize
        window.addEventListener('resize', () => {
            this.handleResize();
        });
        
        // Escape key to close sidebar
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isSidebarOpen()) {
                this.closeSidebar();
            }
        });
        
        // Close sidebar when clicking nav links on mobile
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (this.isMobile) {
                    this.closeSidebar();
                }
            });
        });
    }
    
    toggleSidebar() {
        if (this.isSidebarOpen()) {
            this.closeSidebar();
        } else {
            this.openSidebar();
        }
    }
    
    openSidebar() {
        if (this.sidebar) {
            this.sidebar.classList.add('open');
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }
        if (this.sidebarOverlay) {
            this.sidebarOverlay.classList.add('show');
        }
        
        // Update toggle button
        if (this.mobileMenuToggle) {
            this.mobileMenuToggle.innerHTML = '<i class="fas fa-times"></i>';
            this.mobileMenuToggle.setAttribute('aria-label', 'إغلاق القائمة');
        }
    }
    
    closeSidebar() {
        if (this.sidebar) {
            this.sidebar.classList.remove('open');
            document.body.style.overflow = ''; // Restore scroll
        }
        if (this.sidebarOverlay) {
            this.sidebarOverlay.classList.remove('show');
        }
        
        // Update toggle button
        if (this.mobileMenuToggle) {
            this.mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
            this.mobileMenuToggle.setAttribute('aria-label', 'فتح القائمة');
        }
    }
    
    isSidebarOpen() {
        return this.sidebar && this.sidebar.classList.contains('open');
    }
    
    handleResize() {
        const newWidth = window.innerWidth;
        const wasMobile = this.isMobile;
        
        this.isMobile = newWidth <= 767;
        this.isTablet = newWidth >= 768 && newWidth <= 1023;
        this.isDesktop = newWidth >= 1024;
        
        // Close sidebar when switching from mobile to desktop
        if (wasMobile && !this.isMobile) {
            this.closeSidebar();
        }
        
        // Update mobile menu toggle visibility
        if (this.mobileMenuToggle) {
            this.mobileMenuToggle.style.display = this.isMobile ? 'block' : 'none';
        }
        
        // Adjust grid layouts
        this.adjustGridLayouts();
        
        // Optimize tables for mobile
        this.optimizeTables();
    }
    
    adjustGridLayouts() {
        const grids = document.querySelectorAll('.grid');
        grids.forEach(grid => {
            if (this.isMobile) {
                // Force single column on mobile
                grid.style.gridTemplateColumns = '1fr';
            } else if (this.isTablet) {
                // Adjust for tablet
                if (grid.classList.contains('grid-cols-4')) {
                    grid.style.gridTemplateColumns = 'repeat(2, 1fr)';
                } else if (grid.classList.contains('grid-cols-3')) {
                    grid.style.gridTemplateColumns = 'repeat(2, 1fr)';
                }
            } else {
                // Reset to original for desktop
                grid.style.gridTemplateColumns = '';
            }
        });
    }
    
    optimizeTables() {
        const tables = document.querySelectorAll('.table');
        tables.forEach(table => {
            const wrapper = table.closest('.table-responsive');
            if (wrapper) {
                if (this.isMobile) {
                    // Add horizontal scroll for mobile
                    wrapper.style.overflowX = 'auto';
                    wrapper.style.webkitOverflowScrolling = 'touch';
                } else {
                    wrapper.style.overflowX = 'visible';
                }
            }
        });
    }
    
    initTouchGestures() {
        if (!('ontouchstart' in window)) return;
        
        let startX = 0;
        let startY = 0;
        let isScrolling = false;
        
        document.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            isScrolling = false;
        }, { passive: true });
        
        document.addEventListener('touchmove', (e) => {
            if (!startX || !startY) return;
            
            const diffX = startX - e.touches[0].clientX;
            const diffY = startY - e.touches[0].clientY;
            
            if (Math.abs(diffX) > Math.abs(diffY)) {
                isScrolling = false;
                
                // Swipe right to open sidebar (from right edge)
                if (diffX < -50 && startX < 50 && !this.isSidebarOpen()) {
                    this.openSidebar();
                }
                
                // Swipe left to close sidebar
                if (diffX > 50 && this.isSidebarOpen()) {
                    this.closeSidebar();
                }
            } else {
                isScrolling = true;
            }
        }, { passive: true });
        
        document.addEventListener('touchend', () => {
            startX = 0;
            startY = 0;
            isScrolling = false;
        }, { passive: true });
    }
    
    optimizeForMobile() {
        if (!this.isMobile) return;
        
        // Add touch-friendly classes
        document.body.classList.add('mobile-optimized');
        
        // Optimize form inputs for mobile
        const inputs = document.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            // Prevent zoom on iOS
            if (input.type === 'text' || input.type === 'email' || input.type === 'password') {
                input.style.fontSize = '16px';
            }
            
            // Add touch-friendly padding
            input.style.minHeight = '44px';
        });
        
        // Optimize buttons for touch
        const buttons = document.querySelectorAll('.btn, button');
        buttons.forEach(button => {
            button.style.minHeight = '44px';
            button.style.minWidth = '44px';
        });
        
        // Add loading states for better UX
        this.addLoadingStates();
    }
    
    addLoadingStates() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحميل...';
                    
                    // Re-enable after 5 seconds as fallback
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = submitBtn.getAttribute('data-original-text') || 'حفظ';
                    }, 5000);
                }
            });
        });
    }
    
    // Utility methods
    static isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
    
    static isIOSDevice() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent);
    }
    
    static isAndroidDevice() {
        return /Android/.test(navigator.userAgent);
    }
}

// Initialize responsive manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.responsiveManager = new ResponsiveManager();
});

// Additional utility functions
window.ResponsiveUtils = {
    // Show/hide elements based on screen size
    showOnMobile: (selector) => {
        const elements = document.querySelectorAll(selector);
        elements.forEach(el => {
            el.classList.add('show-mobile', 'hidden-tablet', 'hidden-desktop');
        });
    },
    
    showOnTablet: (selector) => {
        const elements = document.querySelectorAll(selector);
        elements.forEach(el => {
            el.classList.add('hidden-mobile', 'show-tablet', 'hidden-desktop');
        });
    },
    
    showOnDesktop: (selector) => {
        const elements = document.querySelectorAll(selector);
        elements.forEach(el => {
            el.classList.add('hidden-mobile', 'hidden-tablet', 'show-desktop');
        });
    },
    
    // Responsive image loading
    loadResponsiveImages: () => {
        const images = document.querySelectorAll('img[data-src-mobile], img[data-src-tablet], img[data-src-desktop]');
        images.forEach(img => {
            let src = img.getAttribute('data-src-desktop');
            
            if (window.innerWidth <= 767 && img.getAttribute('data-src-mobile')) {
                src = img.getAttribute('data-src-mobile');
            } else if (window.innerWidth <= 1023 && img.getAttribute('data-src-tablet')) {
                src = img.getAttribute('data-src-tablet');
            }
            
            if (src) {
                img.src = src;
            }
        });
    }
};
