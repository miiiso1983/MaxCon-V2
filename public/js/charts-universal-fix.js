/**
 * Universal Charts Fix for Analytics Pages
 * This script automatically detects and fixes chart issues across all analytics pages
 */

(function() {
    'use strict';

    // Wait for DOM and Chart.js to be ready
    function initCharts() {
        console.log('Starting chart initialization...');

        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.log('Chart.js not loaded, loading from CDN...');
            loadChartJS();
            return;
        }

        console.log('Chart.js is available, initializing charts...');

        // Set Chart.js defaults
        try {
            Chart.defaults.font.family = 'Cairo, sans-serif';
            Chart.defaults.font.size = 12;
            Chart.defaults.color = '#4a5568';
            Chart.defaults.responsive = true;
            Chart.defaults.maintainAspectRatio = false;
        } catch (error) {
            console.warn('Could not set Chart.js defaults:', error);
        }

        // Initialize charts with delay to ensure DOM is ready
        setTimeout(() => {
            initAnalyticsCharts();
        }, 100);
    }

    // Load Chart.js from CDN if not available
    function loadChartJS() {
        // Check if already loading
        if (window.chartJSLoading) {
            return;
        }
        window.chartJSLoading = true;

        console.log('Loading Chart.js from CDN...');
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js';
        script.onload = function() {
            console.log('Chart.js loaded successfully from CDN');
            window.chartJSLoading = false;
            setTimeout(initCharts, 200);
        };
        script.onerror = function() {
            console.error('Failed to load Chart.js from CDN');
            window.chartJSLoading = false;
            // Try alternative CDN
            loadChartJSAlternative();
        };
        document.head.appendChild(script);
    }

    // Alternative CDN for Chart.js
    function loadChartJSAlternative() {
        console.log('Trying alternative CDN for Chart.js...');
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js';
        script.onload = function() {
            console.log('Chart.js loaded from alternative CDN');
            setTimeout(initCharts, 200);
        };
        script.onerror = function() {
            console.error('Failed to load Chart.js from alternative CDN');
            showError('فشل في تحميل مكتبة الرسوم البيانية');
            // Show charts with placeholder data anyway
            setTimeout(initAnalyticsCharts, 500);
        };
        document.head.appendChild(script);
    }

    // Destroy all existing charts
    function destroyAllCharts() {
        console.log('Destroying all existing charts...');
        if (window.chartInstances) {
            Object.keys(window.chartInstances).forEach(chartId => {
                try {
                    window.chartInstances[chartId].destroy();
                    console.log(`Destroyed chart: ${chartId}`);
                } catch (error) {
                    console.warn(`Error destroying chart ${chartId}:`, error);
                }
            });
            window.chartInstances = {};
        }
    }

    // Initialize all analytics charts
    function initAnalyticsCharts() {
        console.log('Initializing analytics charts...');

        // First destroy any existing charts
        destroyAllCharts();

        const charts = [
            { id: 'revenueChart', type: 'line', init: initRevenueChart },
            { id: 'categoryChart', type: 'doughnut', init: initCategoryChart },
            { id: 'customerSegmentsChart', type: 'bar', init: initCustomerSegmentsChart },
            { id: 'marketShareChart', type: 'doughnut', init: initMarketShareChart },
            { id: 'performanceChart', type: 'radar', init: initPerformanceChart },
            { id: 'goalsChart', type: 'doughnut', init: initGoalsChart },
            { id: 'riskMatrixChart', type: 'scatter', init: initRiskMatrixChart },
            { id: 'revenueProfitChart', type: 'bar', init: initRevenueProfitChart },
            { id: 'marginTrendsChart', type: 'line', init: initMarginTrendsChart }
        ];

        let chartsInitialized = 0;

        charts.forEach(chart => {
            const canvas = document.getElementById(chart.id);
            if (canvas) {
                console.log(`Found canvas for ${chart.id}, initializing...`);
                try {
                    // Clear any existing error messages
                    const container = canvas.parentElement;
                    if (container && container.innerHTML.includes('خطأ في تحميل الرسم البياني')) {
                        container.innerHTML = `<canvas id="${chart.id}"></canvas>`;
                    }

                    chart.init();
                    chartsInitialized++;
                    console.log(`✅ ${chart.id} initialized successfully`);
                } catch (error) {
                    console.error(`❌ Error initializing ${chart.id}:`, error);
                    showChartError(chart.id, error.message);
                }
            } else {
                console.log(`Canvas ${chart.id} not found on this page`);
            }
        });

        console.log(`Initialized ${chartsInitialized} charts successfully`);
    }

    // Chart initialization functions
    function initRevenueChart() {
        const canvas = document.getElementById('revenueChart');
        if (!canvas) {
            console.log('Revenue chart canvas not found');
            return;
        }

        console.log('Initializing revenue chart...');

        // Destroy existing chart if it exists
        destroyChart('revenueChart');

        // Use data from global scope if available
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
                        backgroundColor: '#667eea20',
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
                            grid: { color: '#e2e8f0' },
                            ticks: {
                                callback: value => (value/1000000).toFixed(1) + 'M د.ع',
                                color: '#4a5568'
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#4a5568' }
                        }
                    }
                }
            });

            // Store chart instance
            window.chartInstances = window.chartInstances || {};
            window.chartInstances.revenueChart = chart;

            console.log('✅ Revenue chart created successfully');
        } catch (error) {
            console.error('❌ Error creating revenue chart:', error);
            throw error;
        }
    }

    function initCategoryChart() {
        const canvas = document.getElementById('categoryChart');
        if (!canvas) {
            console.log('Category chart canvas not found');
            return;
        }

        console.log('Initializing category chart...');

        // Destroy existing chart if it exists
        destroyChart('categoryChart');

        // Use data from global scope if available
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
                        borderWidth: 0,
                        hoverBorderWidth: 3,
                        hoverBorderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                color: '#4a5568'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed + '%';
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });

            // Store chart instance
            window.chartInstances = window.chartInstances || {};
            window.chartInstances.categoryChart = chart;

            console.log('✅ Category chart created successfully');
        } catch (error) {
            console.error('❌ Error creating category chart:', error);
            throw error;
        }
    }

    function initCustomerSegmentsChart() {
        const canvas = document.getElementById('customerSegmentsChart');
        if (!canvas) {
            console.log('Customer segments chart canvas not found');
            return;
        }

        console.log('Initializing customer segments chart...');
        destroyChart('customerSegmentsChart');

        const labels = window.analyticsData?.customer_segments?.labels || ['مميزون', 'منتظمون', 'جدد', 'نادرون'];
        const data = window.analyticsData?.customer_segments?.data || [15, 35, 25, 25];

        try {
            const chart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'النسبة المئوية',
                        data: data,
                        backgroundColor: ['#48bb78', '#4299e1', '#ed8936', '#9f7aea'],
                        borderRadius: 8,
                        borderWidth: 0
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
                                    return context.label + ': ' + context.parsed.y + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 50,
                            ticks: {
                                callback: value => value + '%',
                                color: '#4a5568'
                            },
                            grid: { color: '#e2e8f0' }
                        },
                        x: {
                            ticks: { color: '#4a5568' },
                            grid: { display: false }
                        }
                    }
                }
            });

            window.chartInstances = window.chartInstances || {};
            window.chartInstances.customerSegmentsChart = chart;
            console.log('✅ Customer segments chart created successfully');
        } catch (error) {
            console.error('❌ Error creating customer segments chart:', error);
            throw error;
        }
    }

    function initMarketShareChart() {
        const canvas = document.getElementById('marketShareChart');
        if (!canvas) return;

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['الشركة أ', 'الشركة ب', 'شركتنا', 'الشركة ج', 'أخرى'],
                datasets: [{
                    data: [35, 20, 18, 15, 12],
                    backgroundColor: ['#4299e1', '#ed8936', '#48bb78', '#9f7aea', '#f56565']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '60%'
            }
        });
    }

    function initPerformanceChart() {
        const canvas = document.getElementById('performanceChart');
        if (!canvas) return;

        new Chart(canvas, {
            type: 'radar',
            data: {
                labels: ['العائد على الاستثمار', 'رضا العملاء', 'دوران المخزون', 'نمو المبيعات', 'هامش الربح'],
                datasets: [{
                    label: 'الأداء الحالي',
                    data: [18.7, 87.5, 4.2, 15.5, 23.5],
                    borderColor: '#48bb78',
                    backgroundColor: '#48bb7820'
                }, {
                    label: 'الهدف',
                    data: [20.0, 90.0, 4.5, 15.0, 25.0],
                    borderColor: '#4299e1',
                    backgroundColor: '#4299e120'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { r: { beginAtZero: true, max: 100 } }
            }
        });
    }

    function initGoalsChart() {
        const canvas = document.getElementById('goalsChart');
        if (!canvas) return;

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['محقق', 'قيد التنفيذ', 'متأخر'],
                datasets: [{
                    data: [80, 15, 5],
                    backgroundColor: ['#48bb78', '#ed8936', '#f56565']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                cutout: '60%'
            }
        });
    }

    function initRiskMatrixChart() {
        const canvas = document.getElementById('riskMatrixChart');
        if (!canvas) return;

        new Chart(canvas, {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'المخاطر المالية',
                    data: [{x: 35, y: 6}, {x: 65, y: 8}, {x: 20, y: 5}],
                    backgroundColor: '#48bb78'
                }, {
                    label: 'المخاطر التشغيلية',
                    data: [{x: 45, y: 9}, {x: 25, y: 7}, {x: 30, y: 6}],
                    backgroundColor: '#f56565'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: {
                    x: { min: 0, max: 100, title: { display: true, text: 'الاحتمالية (%)' } },
                    y: { min: 0, max: 10, title: { display: true, text: 'التأثير (1-10)' } }
                }
            }
        });
    }

    function initRevenueProfitChart() {
        const canvas = document.getElementById('revenueProfitChart');
        if (!canvas) return;

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: ['أدوية عامة', 'فيتامينات', 'مضادات حيوية', 'مسكنات', 'أخرى'],
                datasets: [{
                    label: 'الإيرادات (مليون دينار)',
                    data: [45, 35, 30, 25, 15],
                    backgroundColor: '#4299e1'
                }, {
                    label: 'الأرباح (مليون دينار)',
                    data: [15, 16, 12, 10, 4.5],
                    backgroundColor: '#48bb78'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    function initMarginTrendsChart() {
        const canvas = document.getElementById('marginTrendsChart');
        if (!canvas) return;

        new Chart(canvas, {
            type: 'line',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'هامش الربح الإجمالي (%)',
                    data: [33.2, 34.1, 35.8, 34.9, 36.2, 35.8],
                    borderColor: '#9f7aea',
                    backgroundColor: '#9f7aea20',
                    fill: true
                }, {
                    label: 'هامش الربح الصافي (%)',
                    data: [21.5, 22.3, 23.5, 22.8, 24.1, 23.5],
                    borderColor: '#ed8936',
                    backgroundColor: '#ed893620',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: { y: { beginAtZero: true, max: 40 } }
            }
        });
    }

    // Destroy specific chart safely
    function destroyChart(chartId) {
        if (window.chartInstances && window.chartInstances[chartId]) {
            try {
                window.chartInstances[chartId].destroy();
                delete window.chartInstances[chartId];
                console.log(`✅ Destroyed chart: ${chartId}`);
                return true;
            } catch (error) {
                console.warn(`⚠️ Error destroying chart ${chartId}:`, error);
                return false;
            }
        }
        return true; // No chart to destroy
    }

    // Show chart error
    function showChartError(chartId, errorMessage = '') {
        console.log(`Showing error for chart: ${chartId}`);
        const canvas = document.getElementById(chartId);
        if (canvas && canvas.parentElement) {
            canvas.parentElement.innerHTML = `
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 300px; background: #f7fafc; border: 2px dashed #cbd5e0; border-radius: 10px; color: #4a5568; font-weight: 600; text-align: center;">
                    <i class="fas fa-chart-bar" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                    <div style="font-size: 16px; margin-bottom: 8px;">لا توجد بيانات للعرض</div>
                    <small style="opacity: 0.7;">سيتم تحديث البيانات قريباً</small>
                    ${errorMessage ? `<div style="font-size: 12px; margin-top: 10px; opacity: 0.6;">${errorMessage}</div>` : ''}
                </div>
            `;
        }
    }

    // Show general error
    function showError(message) {
        console.error('Charts Error:', message);
    }

    // Prevent multiple initializations
    if (window.chartsUniversalFixLoaded) {
        console.log('Charts universal fix already loaded, skipping...');
        return;
    }
    window.chartsUniversalFixLoaded = true;

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM ready, initializing charts...');
            initCharts();
        });
    } else {
        console.log('DOM already ready, initializing charts...');
        initCharts();
    }

    // Also expose functions globally for manual initialization
    window.initCharts = initCharts;
    window.destroyAllCharts = destroyAllCharts;
    window.destroyChart = destroyChart;

    // Retry initialization after a delay
    setTimeout(initCharts, 2000);

})();
