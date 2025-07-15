/**
 * Interactive Tours System
 * 
 * Provides guided tours for different modules using Intro.js
 */

class InteractiveTours {
    constructor() {
        this.tours = new Map();
        this.currentTour = null;
        this.init();
    }

    init() {
        this.loadTourData();
        this.setupIntroJS();
    }

    setupIntroJS() {
        // Load Intro.js if not already loaded
        if (typeof introJs === 'undefined') {
            this.loadIntroJS();
        }
    }

    loadIntroJS() {
        // Load Intro.js CSS
        const css = document.createElement('link');
        css.rel = 'stylesheet';
        css.href = 'https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css';
        document.head.appendChild(css);

        // Load Intro.js JavaScript
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js';
        script.onload = () => {
            this.customizeIntroJS();
        };
        document.head.appendChild(script);
    }

    customizeIntroJS() {
        // Add custom Arabic styles
        const customCSS = document.createElement('style');
        customCSS.textContent = `
            .introjs-tooltip {
                direction: rtl;
                text-align: right;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                border: none;
                max-width: 350px;
            }
            
            .introjs-tooltip-title {
                font-weight: 700;
                font-size: 18px;
                color: #1e293b;
                margin-bottom: 8px;
                padding-bottom: 8px;
                border-bottom: 2px solid #e2e8f0;
            }
            
            .introjs-tooltiptext {
                font-size: 14px;
                line-height: 1.6;
                color: #475569;
            }
            
            .introjs-button {
                border-radius: 8px;
                font-weight: 600;
                padding: 8px 16px;
                font-size: 14px;
                transition: all 0.3s ease;
            }
            
            .introjs-nextbutton {
                background: #3b82f6;
                border: none;
                color: white;
            }
            
            .introjs-nextbutton:hover {
                background: #1d4ed8;
            }
            
            .introjs-prevbutton {
                background: #6b7280;
                border: none;
                color: white;
            }
            
            .introjs-prevbutton:hover {
                background: #4b5563;
            }
            
            .introjs-skipbutton {
                background: #ef4444;
                border: none;
                color: white;
            }
            
            .introjs-skipbutton:hover {
                background: #dc2626;
            }
            
            .introjs-donebutton {
                background: #10b981;
                border: none;
                color: white;
            }
            
            .introjs-donebutton:hover {
                background: #059669;
            }
            
            .introjs-helperLayer {
                border-radius: 8px;
            }
            
            .introjs-overlay {
                background: rgba(0,0,0,0.7);
            }
            
            .introjs-bullets ul li a {
                background: #cbd5e1;
            }
            
            .introjs-bullets ul li a.active {
                background: #3b82f6;
            }
        `;
        document.head.appendChild(customCSS);
    }

    loadTourData() {
        this.tourData = {
            general: {
                name: 'جولة عامة في النظام',
                description: 'تعرف على الواجهة الرئيسية والتنقل في النظام',
                steps: [
                    {
                        element: '.sidebar',
                        intro: '<strong>القائمة الجانبية</strong><br>هذه هي القائمة الرئيسية للتنقل بين وحدات النظام المختلفة.',
                        position: 'right'
                    },
                    {
                        element: '.top-navbar',
                        intro: '<strong>شريط التنقل العلوي</strong><br>يحتوي على الإشعارات والبحث السريع وإعدادات الملف الشخصي.',
                        position: 'bottom'
                    },
                    {
                        element: '.main-content',
                        intro: '<strong>المحتوى الرئيسي</strong><br>هنا يتم عرض محتوى الصفحة الحالية والبيانات.',
                        position: 'top'
                    },
                    {
                        element: '.breadcrumb',
                        intro: '<strong>مسار التنقل</strong><br>يوضح موقعك الحالي في النظام ويساعدك على التنقل.',
                        position: 'bottom'
                    }
                ]
            },

            sales: {
                name: 'جولة في إدارة المبيعات',
                description: 'تعلم كيفية إدارة العملاء والطلبات والفواتير',
                steps: [
                    {
                        element: '[href*="customers"]',
                        intro: '<strong>إدارة العملاء</strong><br>من هنا يمكنك إضافة عملاء جدد وإدارة معلوماتهم.',
                        position: 'right'
                    },
                    {
                        element: '[href*="orders"]',
                        intro: '<strong>طلبات المبيعات</strong><br>إنشاء ومتابعة طلبات البيع من العملاء.',
                        position: 'right'
                    },
                    {
                        element: '[href*="invoices"]',
                        intro: '<strong>الفواتير</strong><br>إصدار الفواتير الإلكترونية وطباعتها وإرسالها للعملاء.',
                        position: 'right'
                    },
                    {
                        element: '.btn-create, .create-btn',
                        intro: '<strong>إنشاء جديد</strong><br>استخدم هذا الزر لإضافة عناصر جديدة في أي قسم.',
                        position: 'bottom'
                    }
                ]
            },

            inventory: {
                name: 'جولة في إدارة المخزون',
                description: 'تعلم كيفية إدارة المنتجات والمستودعات',
                steps: [
                    {
                        element: '[href*="products"]',
                        intro: '<strong>كتالوج المنتجات</strong><br>إدارة جميع منتجاتك وتصنيفاتها وأسعارها.',
                        position: 'right'
                    },
                    {
                        element: '[href*="warehouses"]',
                        intro: '<strong>المستودعات</strong><br>إدارة المستودعات المختلفة ومواقع التخزين.',
                        position: 'right'
                    },
                    {
                        element: '[href*="movements"]',
                        intro: '<strong>حركات المخزون</strong><br>تسجيل دخول وخروج البضائع وتتبع الحركات.',
                        position: 'right'
                    },
                    {
                        element: '.stock-alert, .alert-warning',
                        intro: '<strong>تنبيهات المخزون</strong><br>راقب هذه التنبيهات لتجنب نفاد المخزون.',
                        position: 'top'
                    }
                ]
            },

            targets: {
                name: 'جولة في أهداف البيع',
                description: 'تعلم كيفية تحديد ومتابعة الأهداف',
                steps: [
                    {
                        element: '.targets-dashboard',
                        intro: '<strong>لوحة تحكم الأهداف</strong><br>نظرة عامة على جميع الأهداف وحالة التقدم.',
                        position: 'top'
                    },
                    {
                        element: '.create-target-btn',
                        intro: '<strong>إنشاء هدف جديد</strong><br>حدد أهدافاً واقعية وقابلة للقياس لفريقك.',
                        position: 'bottom'
                    },
                    {
                        element: '.progress-chart',
                        intro: '<strong>مخططات التقدم</strong><br>تابع تقدم الأهداف بصرياً من خلال الرسوم البيانية.',
                        position: 'top'
                    }
                ]
            },

            accounting: {
                name: 'جولة في النظام المحاسبي',
                description: 'تعلم أساسيات النظام المحاسبي',
                steps: [
                    {
                        element: '[href*="chart-of-accounts"]',
                        intro: '<strong>دليل الحسابات</strong><br>إدارة الحسابات المحاسبية وتصنيفاتها.',
                        position: 'right'
                    },
                    {
                        element: '[href*="journal-entries"]',
                        intro: '<strong>القيود المحاسبية</strong><br>إنشاء وإدارة القيود المحاسبية اليومية.',
                        position: 'right'
                    },
                    {
                        element: '[href*="financial-reports"]',
                        intro: '<strong>التقارير المالية</strong><br>إنشاء تقارير الميزانية والأرباح والخسائر.',
                        position: 'right'
                    },
                    {
                        element: '.balance-warning',
                        intro: '<strong>تحذير التوازن</strong><br>تأكد دائماً من توازن القيود المحاسبية قبل الحفظ.',
                        position: 'top'
                    }
                ]
            }
        };
    }

    startTour(tourType = 'general') {
        if (typeof introJs === 'undefined') {
            console.warn('Intro.js not loaded yet');
            return;
        }

        const tour = this.tourData[tourType];
        if (!tour) {
            console.warn(`Tour type "${tourType}" not found`);
            return;
        }

        this.currentTour = tourType;

        // Configure intro.js
        const intro = introJs();
        
        intro.setOptions({
            nextLabel: 'التالي →',
            prevLabel: '← السابق',
            skipLabel: 'تخطي الجولة',
            doneLabel: 'إنهاء الجولة',
            showProgress: true,
            showBullets: true,
            exitOnOverlayClick: false,
            exitOnEsc: true,
            disableInteraction: false,
            scrollToElement: true,
            scrollPadding: 30,
            overlayOpacity: 0.7,
            steps: tour.steps
        });

        // Event handlers
        intro.onbeforechange((targetElement) => {
            // Scroll to element if needed
            if (targetElement) {
                targetElement.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
            }
        });

        intro.oncomplete(() => {
            this.onTourComplete(tourType);
        });

        intro.onexit(() => {
            this.onTourExit(tourType);
        });

        // Start the tour
        intro.start();

        // Track tour start
        this.trackTourEvent('started', tourType);
    }

    onTourComplete(tourType) {
        this.currentTour = null;
        this.trackTourEvent('completed', tourType);
        
        // Show completion message
        this.showCompletionMessage(tourType);
    }

    onTourExit(tourType) {
        this.currentTour = null;
        this.trackTourEvent('exited', tourType);
    }

    showCompletionMessage(tourType) {
        const tour = this.tourData[tourType];
        
        // Create completion modal
        const modal = document.createElement('div');
        modal.style.cssText = `
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.8); z-index: 10001;
            display: flex; align-items: center; justify-content: center;
        `;

        modal.innerHTML = `
            <div style="background: white; border-radius: 20px; padding: 40px; max-width: 500px; text-align: center; margin: 20px;">
                <div style="color: #10b981; font-size: 64px; margin-bottom: 20px;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 style="margin: 0 0 15px 0; color: #1e293b; font-size: 24px; font-weight: 700;">
                    تم إكمال الجولة بنجاح!
                </h2>
                <p style="color: #64748b; margin-bottom: 25px; line-height: 1.6;">
                    لقد أكملت جولة "${tour.name}" بنجاح. يمكنك الآن البدء في استخدام هذه الوحدة بثقة.
                </p>
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                            style="background: #10b981; color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        ممتاز!
                    </button>
                    <button onclick="window.interactiveTours.showTourMenu(); this.parentElement.parentElement.parentElement.remove()" 
                            style="background: #6b7280; color: white; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        جولات أخرى
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Auto remove after 10 seconds
        setTimeout(() => {
            if (modal.parentElement) {
                modal.remove();
            }
        }, 10000);
    }

    showTourMenu() {
        const modal = document.createElement('div');
        modal.style.cssText = `
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.8); z-index: 10001;
            display: flex; align-items: center; justify-content: center;
        `;

        const toursHTML = Object.entries(this.tourData).map(([key, tour]) => `
            <div style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 15px; cursor: pointer; transition: all 0.3s ease;"
                 onclick="window.interactiveTours.startTour('${key}'); this.closest('.tour-modal').remove()"
                 onmouseover="this.style.borderColor='#3b82f6'; this.style.background='#f8fafc'"
                 onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='white'">
                <h3 style="margin: 0 0 8px 0; color: #1e293b; font-size: 16px; font-weight: 700;">${tour.name}</h3>
                <p style="margin: 0; color: #64748b; font-size: 14px;">${tour.description}</p>
            </div>
        `).join('');

        modal.innerHTML = `
            <div class="tour-modal" style="background: white; border-radius: 20px; padding: 30px; max-width: 600px; max-height: 80vh; overflow-y: auto; margin: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
                    <h2 style="margin: 0; color: #1e293b; font-size: 24px; font-weight: 700;">
                        <i class="fas fa-route" style="color: #3b82f6; margin-left: 10px;"></i>
                        اختر جولة تفاعلية
                    </h2>
                    <button onclick="this.closest('.tour-modal').parentElement.remove()" 
                            style="background: #ef4444; color: white; border: none; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-size: 16px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                ${toursHTML}
            </div>
        `;

        document.body.appendChild(modal);
    }

    trackTourEvent(action, tourType) {
        // Track tour events for analytics
        if (typeof gtag !== 'undefined') {
            gtag('event', 'tour_' + action, {
                'tour_type': tourType,
                'timestamp': new Date().toISOString()
            });
        }
        
        console.log(`Tour ${action}: ${tourType}`);
    }

    // Public methods
    isAvailable() {
        return typeof introJs !== 'undefined';
    }

    getCurrentTour() {
        return this.currentTour;
    }

    addCustomTour(name, tourData) {
        this.tourData[name] = tourData;
    }
}

// Initialize tours when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.interactiveTours = new InteractiveTours();
});

// Helper functions
function startInteractiveTour(tourType = 'general') {
    if (window.interactiveTours) {
        window.interactiveTours.startTour(tourType);
    }
}

function showTourMenu() {
    if (window.interactiveTours) {
        window.interactiveTours.showTourMenu();
    }
}
