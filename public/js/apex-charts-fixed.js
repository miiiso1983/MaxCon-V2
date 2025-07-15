/**
 * ApexCharts System - Fixed Version
 * MaxCon ERP System - معالجة محسنة للأخطاء
 */

(function() {
    'use strict';
    
    console.log('🚀 تحميل نظام ApexCharts المحسن...');
    
    // منع التحميل المتعدد
    if (window.apexChartsFixedLoaded) {
        console.log('⚠️ نظام ApexCharts المحسن محمل مسبقاً');
        return;
    }
    window.apexChartsFixedLoaded = true;
    
    // تهيئة متغيرات النظام
    window.apexChartInstances = window.apexChartInstances || {};
    
    // الألوان المتناسقة
    const COLORS = {
        primary: '#667eea',
        secondary: '#764ba2',
        success: '#48bb78',
        warning: '#ed8936',
        danger: '#f56565',
        info: '#4299e1',
        purple: '#9f7aea',
        teal: '#38b2ac',
        gradient: ['#667eea', '#764ba2', '#48bb78', '#ed8936', '#f56565', '#4299e1', '#9f7aea', '#38b2ac']
    };
    
    // الإعدادات الافتراضية
    const DEFAULT_OPTIONS = {
        chart: {
            fontFamily: 'Cairo, sans-serif',
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: false,
                    zoom: false,
                    zoomin: false,
                    zoomout: false,
                    pan: false,
                    reset: false
                }
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: COLORS.gradient,
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        grid: {
            borderColor: '#e2e8f0',
            strokeDashArray: 3
        },
        xaxis: {
            labels: {
                style: {
                    colors: '#4a5568',
                    fontSize: '12px',
                    fontWeight: 500
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#4a5568',
                    fontSize: '12px',
                    fontWeight: 500
                }
            }
        },
        legend: {
            position: 'bottom',
            fontSize: '14px',
            fontWeight: 600,
            labels: {
                colors: '#2d3748'
            }
        },
        tooltip: {
            theme: 'light',
            style: {
                fontSize: '12px',
                fontFamily: 'Cairo, sans-serif'
            }
        }
    };
    
    // دالة تدمير رسم بياني محسنة
    function destroyChart(chartId) {
        console.log(`🗑️ محاولة تدمير الرسم: ${chartId}`);
        
        if (window.apexChartInstances[chartId]) {
            try {
                window.apexChartInstances[chartId].destroy();
                delete window.apexChartInstances[chartId];
                console.log(`✅ تم تدمير الرسم: ${chartId}`);
            } catch (error) {
                console.warn(`⚠️ خطأ في تدمير الرسم ${chartId}:`, error);
            }
        }
        
        // تنظيف العنصر
        const element = document.getElementById(chartId);
        if (element) {
            element.innerHTML = '';
            console.log(`🧹 تم تنظيف العنصر: ${chartId}`);
        }
        
        return true;
    }
    
    // دالة تدمير جميع الرسوم
    function destroyAllCharts() {
        console.log('🗑️ تدمير جميع الرسوم البيانية...');
        Object.keys(window.apexChartInstances).forEach(chartId => {
            destroyChart(chartId);
        });
        window.apexChartInstances = {};
    }
    
    // دالة تحميل ApexCharts
    function loadApexCharts() {
        return new Promise((resolve, reject) => {
            if (typeof ApexCharts !== 'undefined') {
                console.log('✅ ApexCharts محمل مسبقاً');
                resolve();
                return;
            }
            
            console.log('📦 تحميل ApexCharts...');
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/apexcharts@3.44.0/dist/apexcharts.min.js';
            script.onload = () => {
                console.log('✅ تم تحميل ApexCharts بنجاح');
                resolve();
            };
            script.onerror = () => {
                console.error('❌ فشل في تحميل ApexCharts');
                reject(new Error('Failed to load ApexCharts'));
            };
            document.head.appendChild(script);
        });
    }
    
    // دالة إنشاء رسم عامة
    function createChart(chartId, options) {
        return new Promise((resolve, reject) => {
            const element = document.getElementById(chartId);
            if (!element) {
                console.log(`❌ Element ${chartId} غير موجود`);
                reject(new Error(`Element ${chartId} not found`));
                return;
            }
            
            console.log(`📊 إنشاء رسم: ${chartId}`);
            
            // تدمير الرسم السابق
            destroyChart(chartId);
            
            try {
                // إضافة ID فريد للرسم
                options.chart.id = chartId + '_' + Date.now();
                
                const chart = new ApexCharts(element, options);
                
                chart.render().then(() => {
                    window.apexChartInstances[chartId] = chart;
                    console.log(`✅ تم إنشاء رسم: ${chartId}`);
                    resolve(chart);
                }).catch(error => {
                    console.error(`❌ خطأ في رندر رسم: ${chartId}`, error);
                    element.innerHTML = '<div style="text-align: center; padding: 50px; color: #f56565; font-family: Cairo, sans-serif;">خطأ في تحميل الرسم البياني</div>';
                    reject(error);
                });
                
            } catch (error) {
                console.error(`❌ خطأ في إنشاء رسم: ${chartId}`, error);
                element.innerHTML = '<div style="text-align: center; padding: 50px; color: #f56565; font-family: Cairo, sans-serif;">خطأ في تحميل الرسم البياني</div>';
                reject(error);
            }
        });
    }
    
    // دالة إنشاء رسم الإيرادات
    function createRevenueChart() {
        const chartId = 'revenueChart';
        const labels = window.analyticsData?.revenue_trend?.labels || ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'];
        const data = window.analyticsData?.revenue_trend?.data || [18500000, 22300000, 19800000, 25600000, 28900000, 31200000];
        
        const options = {
            ...DEFAULT_OPTIONS,
            chart: {
                ...DEFAULT_OPTIONS.chart,
                type: 'area',
                height: 300
            },
            series: [{
                name: 'الإيرادات (دينار)',
                data: data
            }],
            xaxis: {
                ...DEFAULT_OPTIONS.xaxis,
                categories: labels
            },
            yaxis: {
                ...DEFAULT_OPTIONS.yaxis,
                labels: {
                    ...DEFAULT_OPTIONS.yaxis.labels,
                    formatter: function(value) {
                        return (value / 1000000).toFixed(1) + 'M د.ع';
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            colors: [COLORS.primary]
        };
        
        return createChart(chartId, options);
    }
    
    // دالة إنشاء رسم الفئات
    function createCategoryChart() {
        const chartId = 'categoryChart';
        const labels = window.analyticsData?.sales_by_category?.labels || ['أدوية القلب', 'المضادات الحيوية', 'أدوية السكري', 'الفيتامينات', 'أخرى'];
        const data = window.analyticsData?.sales_by_category?.data || [35, 25, 20, 15, 5];
        
        const options = {
            ...DEFAULT_OPTIONS,
            chart: {
                ...DEFAULT_OPTIONS.chart,
                type: 'donut',
                height: 300
            },
            series: data,
            labels: labels,
            colors: [COLORS.success, COLORS.info, COLORS.warning, COLORS.purple, COLORS.danger],
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(1) + '%';
                }
            }
        };
        
        return createChart(chartId, options);
    }
    
    // دالة إنشاء رسم شرائح العملاء
    function createCustomerSegmentsChart() {
        const chartId = 'customerSegmentsChart';
        const labels = window.analyticsData?.customer_segments?.labels || ['مستشفيات', 'صيدليات', 'عيادات', 'موزعين'];
        const data = window.analyticsData?.customer_segments?.data || [40, 35, 15, 10];
        
        const options = {
            ...DEFAULT_OPTIONS,
            chart: {
                ...DEFAULT_OPTIONS.chart,
                type: 'bar',
                height: 300
            },
            series: [{
                name: 'النسبة المئوية',
                data: data
            }],
            xaxis: {
                ...DEFAULT_OPTIONS.xaxis,
                categories: labels
            },
            yaxis: {
                ...DEFAULT_OPTIONS.yaxis,
                max: 50,
                labels: {
                    ...DEFAULT_OPTIONS.yaxis.labels,
                    formatter: function(value) {
                        return value + '%';
                    }
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    columnWidth: '60%'
                }
            },
            colors: [COLORS.success]
        };
        
        return createChart(chartId, options);
    }
    
    // دالة إنشاء رسم الأداء
    function createPerformanceChart() {
        const chartId = 'performanceChart';
        
        const options = {
            ...DEFAULT_OPTIONS,
            chart: {
                ...DEFAULT_OPTIONS.chart,
                type: 'radar',
                height: 300
            },
            series: [{
                name: 'الأداء الحالي',
                data: [18.7, 87.5, 4.2, 15.5, 23.5]
            }],
            xaxis: {
                categories: ['العائد على الاستثمار', 'رضا العملاء', 'دوران المخزون', 'نمو المبيعات', 'هامش الربح']
            },
            colors: [COLORS.success],
            plotOptions: {
                radar: {
                    size: 120,
                    polygons: {
                        strokeColors: '#e2e8f0',
                        fill: {
                            colors: ['#f8f9fa', '#ffffff']
                        }
                    }
                }
            }
        };
        
        return createChart(chartId, options);
    }
    
    // دالة إنشاء رسم حصة السوق
    function createMarketShareChart() {
        const chartId = 'marketShareChart';
        
        const options = {
            ...DEFAULT_OPTIONS,
            chart: {
                ...DEFAULT_OPTIONS.chart,
                type: 'pie',
                height: 300
            },
            series: [35, 25, 20, 20],
            labels: ['شركتنا', 'المنافس الأول', 'المنافس الثاني', 'أخرى'],
            colors: [COLORS.success, COLORS.info, COLORS.warning, COLORS.purple],
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(1) + '%';
                }
            }
        };
        
        return createChart(chartId, options);
    }
    
    // دالة إنشاء رسم الأهداف
    function createGoalsChart() {
        const chartId = 'goalsChart';
        
        const options = {
            ...DEFAULT_OPTIONS,
            chart: {
                ...DEFAULT_OPTIONS.chart,
                type: 'bar',
                height: 300
            },
            series: [{
                name: 'المحقق',
                data: [85, 92, 78, 88]
            }, {
                name: 'المستهدف',
                data: [100, 100, 100, 100]
            }],
            xaxis: {
                ...DEFAULT_OPTIONS.xaxis,
                categories: ['Q1', 'Q2', 'Q3', 'Q4']
            },
            yaxis: {
                ...DEFAULT_OPTIONS.yaxis,
                max: 120,
                labels: {
                    ...DEFAULT_OPTIONS.yaxis.labels,
                    formatter: function(value) {
                        return value + '%';
                    }
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '60%'
                }
            },
            colors: [COLORS.success, COLORS.info]
        };
        
        return createChart(chartId, options);
    }
    
    // دالة تشغيل جميع الرسوم
    async function initializeAllApexCharts() {
        console.log('🚀 بدء تشغيل جميع الرسوم البيانية...');
        
        try {
            // تحميل ApexCharts أولاً
            await loadApexCharts();
            
            // التأكد من وجود البيانات
            if (!window.analyticsData) {
                console.warn('⚠️ لا توجد بيانات، استخدام البيانات الافتراضية');
                window.analyticsData = {
                    revenue_trend: {
                        labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                        data: [18500000, 22300000, 19800000, 25600000, 28900000, 31200000]
                    },
                    sales_by_category: {
                        labels: ['أدوية القلب', 'المضادات الحيوية', 'أدوية السكري', 'الفيتامينات', 'أخرى'],
                        data: [35, 25, 20, 15, 5]
                    },
                    customer_segments: {
                        labels: ['مستشفيات', 'صيدليات', 'عيادات', 'موزعين'],
                        data: [40, 35, 15, 10]
                    }
                };
            }
            
            // قائمة الرسوم المتاحة
            const availableCharts = [
                { id: 'revenueChart', create: createRevenueChart },
                { id: 'categoryChart', create: createCategoryChart },
                { id: 'customerSegmentsChart', create: createCustomerSegmentsChart },
                { id: 'performanceChart', create: createPerformanceChart },
                { id: 'marketShareChart', create: createMarketShareChart },
                { id: 'goalsChart', create: createGoalsChart }
            ];
            
            // إنشاء الرسوم الموجودة فقط
            let successCount = 0;
            let totalFound = 0;
            
            for (const chart of availableCharts) {
                const element = document.getElementById(chart.id);
                if (element) {
                    totalFound++;
                    console.log(`📊 وجد Element: ${chart.id}`);
                    
                    try {
                        await chart.create();
                        successCount++;
                        console.log(`✅ تم إنشاء: ${chart.id}`);
                    } catch (error) {
                        console.error(`❌ خطأ في إنشاء ${chart.id}:`, error);
                    }
                } else {
                    console.log(`⏭️ تخطي ${chart.id} - Element غير موجود`);
                }
            }
            
            console.log(`🎉 تم إنشاء ${successCount} من ${totalFound} رسوم بيانية موجودة بنجاح`);
            
            return successCount;
        } catch (error) {
            console.error('❌ خطأ في تشغيل الرسوم البيانية:', error);
            return 0;
        }
    }
    
    // تصدير الدوال للاستخدام العام
    window.createRevenueChart = createRevenueChart;
    window.createCategoryChart = createCategoryChart;
    window.createCustomerSegmentsChart = createCustomerSegmentsChart;
    window.createPerformanceChart = createPerformanceChart;
    window.createMarketShareChart = createMarketShareChart;
    window.createGoalsChart = createGoalsChart;
    window.initializeAllApexCharts = initializeAllApexCharts;
    window.destroyAllApexCharts = destroyAllCharts;
    window.destroyApexChart = destroyChart;
    window.loadApexCharts = loadApexCharts;
    
    // تشغيل تلقائي عند تحميل الصفحة
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(initializeAllApexCharts, 1000);
        });
    } else {
        setTimeout(initializeAllApexCharts, 1000);
    }
    
    console.log('✅ تم تحميل نظام ApexCharts المحسن');
    
})();
