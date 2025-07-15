/**
 * Simple Charts Fix - نسخة مبسطة لحل مشاكل الرسوم البيانية
 * MaxCon ERP System
 */

(function() {
    'use strict';
    
    console.log('🚀 تحميل النظام المبسط للرسوم البيانية...');
    
    // منع التحميل المتعدد
    if (window.chartsSimpleFixLoaded) {
        console.log('⚠️ النظام المبسط محمل مسبقاً');
        return;
    }
    window.chartsSimpleFixLoaded = true;
    
    // تهيئة متغيرات النظام
    window.chartInstances = window.chartInstances || {};
    
    // دالة تدمير رسم بياني محدد
    function destroyChart(chartId) {
        if (window.chartInstances[chartId]) {
            try {
                window.chartInstances[chartId].destroy();
                delete window.chartInstances[chartId];
                console.log(`✅ تم تدمير الرسم: ${chartId}`);
                return true;
            } catch (error) {
                console.warn(`⚠️ خطأ في تدمير الرسم ${chartId}:`, error);
                return false;
            }
        }
        return true;
    }
    
    // دالة تدمير جميع الرسوم
    function destroyAllCharts() {
        console.log('🗑️ تدمير جميع الرسوم البيانية...');
        Object.keys(window.chartInstances).forEach(chartId => {
            destroyChart(chartId);
        });
        window.chartInstances = {};
    }
    
    // دالة إنشاء رسم الإيرادات
    function createRevenueChart() {
        const canvas = document.getElementById('revenueChart');
        if (!canvas) {
            console.log('❌ Canvas revenueChart غير موجود');
            return false;
        }
        
        console.log('📊 إنشاء رسم الإيرادات...');
        destroyChart('revenueChart');
        
        const labels = window.analyticsData?.revenue_trend?.labels || ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'];
        const data = window.analyticsData?.revenue_trend?.data || [2500000, 2800000, 3200000, 2900000, 3500000, 3800000];
        
        try {
            const chart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'الإيرادات (دينار)',
                        data: data,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#667eea',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'الإيرادات: ' + (context.parsed.y / 1000000).toFixed(1) + 'M د.ع';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { 
                                callback: value => (value/1000000).toFixed(1) + 'M د.ع'
                            }
                        }
                    }
                }
            });
            
            window.chartInstances.revenueChart = chart;
            console.log('✅ تم إنشاء رسم الإيرادات بنجاح');
            return true;
        } catch (error) {
            console.error('❌ خطأ في إنشاء رسم الإيرادات:', error);
            return false;
        }
    }
    
    // دالة إنشاء رسم الفئات
    function createCategoryChart() {
        const canvas = document.getElementById('categoryChart');
        if (!canvas) {
            console.log('❌ Canvas categoryChart غير موجود');
            return false;
        }
        
        console.log('📊 إنشاء رسم الفئات...');
        destroyChart('categoryChart');
        
        const labels = window.analyticsData?.sales_by_category?.labels || ['أدوية عامة', 'فيتامينات', 'مضادات حيوية', 'مسكنات', 'أخرى'];
        const data = window.analyticsData?.sales_by_category?.data || [35, 25, 20, 15, 5];
        
        try {
            const chart = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: ['#48bb78', '#4299e1', '#ed8936', '#9f7aea', '#f56565'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom',
                            labels: { padding: 20 }
                        }
                    },
                    cutout: '60%'
                }
            });
            
            window.chartInstances.categoryChart = chart;
            console.log('✅ تم إنشاء رسم الفئات بنجاح');
            return true;
        } catch (error) {
            console.error('❌ خطأ في إنشاء رسم الفئات:', error);
            return false;
        }
    }
    
    // دالة إنشاء رسم شرائح العملاء
    function createCustomerSegmentsChart() {
        const canvas = document.getElementById('customerSegmentsChart');
        if (!canvas) {
            console.log('❌ Canvas customerSegmentsChart غير موجود');
            return false;
        }
        
        console.log('📊 إنشاء رسم شرائح العملاء...');
        destroyChart('customerSegmentsChart');
        
        const labels = window.analyticsData?.customer_segments?.labels || ['مستشفيات', 'صيدليات', 'عيادات', 'موزعين'];
        const data = window.analyticsData?.customer_segments?.data || [40, 35, 15, 10];
        
        try {
            const chart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'النسبة المئوية',
                        data: data,
                        backgroundColor: ['#48bb78', '#4299e1', '#ed8936', '#9f7aea'],
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            max: 50,
                            ticks: { callback: value => value + '%' }
                        }
                    }
                }
            });
            
            window.chartInstances.customerSegmentsChart = chart;
            console.log('✅ تم إنشاء رسم شرائح العملاء بنجاح');
            return true;
        } catch (error) {
            console.error('❌ خطأ في إنشاء رسم شرائح العملاء:', error);
            return false;
        }
    }
    
    // دالة إنشاء رسم الأداء
    function createPerformanceChart() {
        const canvas = document.getElementById('performanceChart');
        if (!canvas) {
            console.log('❌ Canvas performanceChart غير موجود');
            return false;
        }

        console.log('📊 إنشاء رسم الأداء...');
        destroyChart('performanceChart');

        try {
            const chart = new Chart(canvas, {
                type: 'radar',
                data: {
                    labels: ['العائد على الاستثمار', 'رضا العملاء', 'دوران المخزون', 'نمو المبيعات', 'هامش الربح'],
                    datasets: [{
                        label: 'الأداء الحالي',
                        data: [18.7, 87.5, 4.2, 15.5, 23.5],
                        borderColor: '#48bb78',
                        backgroundColor: 'rgba(72, 187, 120, 0.2)',
                        borderWidth: 3,
                        pointBackgroundColor: '#48bb78'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });

            window.chartInstances.performanceChart = chart;
            console.log('✅ تم إنشاء رسم الأداء بنجاح');
            return true;
        } catch (error) {
            console.error('❌ خطأ في إنشاء رسم الأداء:', error);
            return false;
        }
    }

    // دوال إضافية للرسوم الأخرى في صفحة Analytics
    function createMarketShareChart() {
        const canvas = document.getElementById('marketShareChart');
        if (!canvas) return false;

        console.log('📊 إنشاء رسم حصة السوق...');
        destroyChart('marketShareChart');

        try {
            const chart = new Chart(canvas, {
                type: 'pie',
                data: {
                    labels: ['شركتنا', 'المنافس الأول', 'المنافس الثاني', 'أخرى'],
                    datasets: [{
                        data: [35, 25, 20, 20],
                        backgroundColor: ['#48bb78', '#4299e1', '#ed8936', '#9f7aea']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            window.chartInstances.marketShareChart = chart;
            console.log('✅ تم إنشاء رسم حصة السوق بنجاح');
            return true;
        } catch (error) {
            console.error('❌ خطأ في إنشاء رسم حصة السوق:', error);
            return false;
        }
    }

    function createGoalsChart() {
        const canvas = document.getElementById('goalsChart');
        if (!canvas) return false;

        console.log('📊 إنشاء رسم الأهداف...');
        destroyChart('goalsChart');

        try {
            const chart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                    datasets: [{
                        label: 'المحقق',
                        data: [85, 92, 78, 88],
                        backgroundColor: '#48bb78'
                    }, {
                        label: 'المستهدف',
                        data: [100, 100, 100, 100],
                        backgroundColor: '#e2e8f0'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, max: 120 } }
                }
            });

            window.chartInstances.goalsChart = chart;
            console.log('✅ تم إنشاء رسم الأهداف بنجاح');
            return true;
        } catch (error) {
            console.error('❌ خطأ في إنشاء رسم الأهداف:', error);
            return false;
        }
    }

    function createRiskMatrixChart() {
        const canvas = document.getElementById('riskMatrixChart');
        if (!canvas) return false;

        console.log('📊 إنشاء رسم مصفوفة المخاطر...');
        destroyChart('riskMatrixChart');

        try {
            const chart = new Chart(canvas, {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'المخاطر',
                        data: [
                            {x: 2, y: 3}, {x: 4, y: 2}, {x: 3, y: 4}, {x: 1, y: 1}
                        ],
                        backgroundColor: ['#f56565', '#ed8936', '#48bb78', '#4299e1']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { min: 0, max: 5, title: { display: true, text: 'الاحتمالية' } },
                        y: { min: 0, max: 5, title: { display: true, text: 'التأثير' } }
                    }
                }
            });

            window.chartInstances.riskMatrixChart = chart;
            console.log('✅ تم إنشاء رسم مصفوفة المخاطر بنجاح');
            return true;
        } catch (error) {
            console.error('❌ خطأ في إنشاء رسم مصفوفة المخاطر:', error);
            return false;
        }
    }

    function createRevenueProfitChart() {
        const canvas = document.getElementById('revenueProfitChart');
        if (!canvas) return false;

        console.log('📊 إنشاء رسم الإيرادات والأرباح...');
        destroyChart('revenueProfitChart');

        try {
            const chart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                    datasets: [{
                        label: 'الإيرادات',
                        data: [2500000, 2800000, 3200000, 2900000, 3500000, 3800000],
                        borderColor: '#4299e1',
                        backgroundColor: 'rgba(66, 153, 225, 0.1)',
                        yAxisID: 'y'
                    }, {
                        label: 'الأرباح',
                        data: [500000, 560000, 640000, 580000, 700000, 760000],
                        borderColor: '#48bb78',
                        backgroundColor: 'rgba(72, 187, 120, 0.1)',
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { type: 'linear', display: true, position: 'right' },
                        y1: { type: 'linear', display: true, position: 'left' }
                    }
                }
            });

            window.chartInstances.revenueProfitChart = chart;
            console.log('✅ تم إنشاء رسم الإيرادات والأرباح بنجاح');
            return true;
        } catch (error) {
            console.error('❌ خطأ في إنشاء رسم الإيرادات والأرباح:', error);
            return false;
        }
    }

    function createMarginTrendsChart() {
        const canvas = document.getElementById('marginTrendsChart');
        if (!canvas) return false;

        console.log('📊 إنشاء رسم اتجاهات الهامش...');
        destroyChart('marginTrendsChart');

        try {
            const chart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                    datasets: [{
                        label: 'هامش الربح %',
                        data: [20, 22, 18, 25, 23, 26],
                        borderColor: '#9f7aea',
                        backgroundColor: 'rgba(159, 122, 234, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, max: 30 }
                    }
                }
            });

            window.chartInstances.marginTrendsChart = chart;
            console.log('✅ تم إنشاء رسم اتجاهات الهامش بنجاح');
            return true;
        } catch (error) {
            console.error('❌ خطأ في إنشاء رسم اتجاهات الهامش:', error);
            return false;
        }
    }
    
    // دالة تحميل Chart.js
    function loadChartJS() {
        return new Promise((resolve, reject) => {
            if (typeof Chart !== 'undefined') {
                console.log('✅ Chart.js محمل مسبقاً');
                resolve();
                return;
            }
            
            console.log('📦 تحميل Chart.js...');
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js';
            script.onload = () => {
                console.log('✅ تم تحميل Chart.js بنجاح');
                resolve();
            };
            script.onerror = () => {
                console.error('❌ فشل في تحميل Chart.js');
                reject(new Error('Failed to load Chart.js'));
            };
            document.head.appendChild(script);
        });
    }
    
    // دالة تشغيل جميع الرسوم
    async function initializeAllCharts() {
        console.log('🚀 بدء تشغيل جميع الرسوم البيانية...');

        try {
            // تحميل Chart.js أولاً
            await loadChartJS();

            // التأكد من وجود البيانات
            if (!window.analyticsData) {
                console.warn('⚠️ لا توجد بيانات، استخدام البيانات الافتراضية');
                window.analyticsData = {
                    revenue_trend: {
                        labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                        data: [2500000, 2800000, 3200000, 2900000, 3500000, 3800000]
                    },
                    sales_by_category: {
                        labels: ['أدوية عامة', 'فيتامينات', 'مضادات حيوية', 'مسكنات', 'أخرى'],
                        data: [35, 25, 20, 15, 5]
                    },
                    customer_segments: {
                        labels: ['مستشفيات', 'صيدليات', 'عيادات', 'موزعين'],
                        data: [40, 35, 15, 10]
                    }
                };
            }

            // قائمة الرسوم المتاحة مع دوال الإنشاء
            const availableCharts = [
                { id: 'revenueChart', create: createRevenueChart },
                { id: 'categoryChart', create: createCategoryChart },
                { id: 'customerSegmentsChart', create: createCustomerSegmentsChart },
                { id: 'performanceChart', create: createPerformanceChart },
                { id: 'marketShareChart', create: createMarketShareChart },
                { id: 'goalsChart', create: createGoalsChart },
                { id: 'riskMatrixChart', create: createRiskMatrixChart },
                { id: 'revenueProfitChart', create: createRevenueProfitChart },
                { id: 'marginTrendsChart', create: createMarginTrendsChart }
            ];

            // إنشاء الرسوم الموجودة فقط
            let successCount = 0;
            let totalFound = 0;

            for (const chart of availableCharts) {
                const canvas = document.getElementById(chart.id);
                if (canvas) {
                    totalFound++;
                    console.log(`📊 وجد Canvas: ${chart.id}`);

                    try {
                        const success = chart.create();
                        if (success) {
                            successCount++;
                            console.log(`✅ تم إنشاء: ${chart.id}`);
                        } else {
                            console.warn(`⚠️ فشل في إنشاء: ${chart.id}`);
                        }
                    } catch (error) {
                        console.error(`❌ خطأ في إنشاء ${chart.id}:`, error);
                    }
                } else {
                    console.log(`⏭️ تخطي ${chart.id} - Canvas غير موجود`);
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
    window.initCharts = initializeAllCharts;
    window.destroyAllCharts = destroyAllCharts;
    window.destroyChart = destroyChart;
    
    // تشغيل تلقائي عند تحميل الصفحة
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(initializeAllCharts, 500);
        });
    } else {
        setTimeout(initializeAllCharts, 500);
    }
    
    console.log('✅ تم تحميل النظام المبسط للرسوم البيانية');
    
})();
