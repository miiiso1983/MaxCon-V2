/**
 * Analytics Charts Fix
 * This file provides fallback functionality for charts
 */

// Fallback chart data if server data is not available
const fallbackData = {
    revenue_trend: {
        labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
        data: [2500000, 2800000, 3200000, 2900000, 3500000, 3800000]
    },
    sales_by_category: {
        labels: ['أدوية عامة', 'فيتامينات', 'مضادات حيوية', 'مسكنات', 'أخرى'],
        data: [35, 25, 20, 15, 5]
    },
    customer_segments: {
        labels: ['مميزون', 'منتظمون', 'جدد', 'نادرون'],
        data: [15, 35, 25, 25]
    }
};

// Initialize charts with error handling
function initializeAnalyticsCharts() {
    console.log('Initializing analytics charts...');
    
    // Check if Chart.js is available
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded');
        showChartError('Chart.js library is not available');
        return;
    }

    // Set Chart.js defaults
    Chart.defaults.font.family = 'Cairo, sans-serif';
    Chart.defaults.font.size = 12;
    Chart.defaults.color = '#4a5568';

    // Initialize each chart
    initRevenueChart();
    initCategoryChart();
    initCustomerSegmentsChart();
    initMarketShareChart();
    initPerformanceChart();
    initGoalsChart();
    initRiskMatrixChart();
    initRevenueProfitChart();
    initMarginTrendsChart();
}

// Revenue Chart
function initRevenueChart() {
    const canvas = document.getElementById('revenueChart');
    if (!canvas) return;

    try {
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: window.chartData?.revenue_trend?.labels || fallbackData.revenue_trend.labels,
                datasets: [{
                    label: 'الإيرادات (دينار)',
                    data: window.chartData?.revenue_trend?.data || fallbackData.revenue_trend.data,
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
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#e2e8f0' },
                        ticks: {
                            callback: function(value) {
                                return (value / 1000000).toFixed(1) + 'M د.ع';
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
        console.log('Revenue chart initialized');
    } catch (error) {
        console.error('Error initializing revenue chart:', error);
        showChartError('Failed to load revenue chart');
    }
}

// Category Chart
function initCategoryChart() {
    const canvas = document.getElementById('categoryChart');
    if (!canvas) return;

    try {
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: window.chartData?.sales_by_category?.labels || fallbackData.sales_by_category.labels,
                datasets: [{
                    data: window.chartData?.sales_by_category?.data || fallbackData.sales_by_category.data,
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
                        labels: { padding: 20, usePointStyle: true }
                    }
                },
                cutout: '60%'
            }
        });
        console.log('Category chart initialized');
    } catch (error) {
        console.error('Error initializing category chart:', error);
    }
}

// Customer Segments Chart
function initCustomerSegmentsChart() {
    const canvas = document.getElementById('customerSegmentsChart');
    if (!canvas) return;

    try {
        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: window.chartData?.customer_segments?.labels || fallbackData.customer_segments.labels,
                datasets: [{
                    label: 'النسبة المئوية',
                    data: window.chartData?.customer_segments?.data || fallbackData.customer_segments.data,
                    backgroundColor: ['#48bb78', '#4299e1', '#ed8936', '#9f7aea'],
                    borderRadius: 8,
                    borderSkipped: false
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
                        grid: { color: '#e2e8f0' },
                        ticks: { callback: function(value) { return value + '%'; } }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
        console.log('Customer segments chart initialized');
    } catch (error) {
        console.error('Error initializing customer segments chart:', error);
    }
}

// Market Share Chart
function initMarketShareChart() {
    const canvas = document.getElementById('marketShareChart');
    if (!canvas) return;

    try {
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['الشركة أ', 'الشركة ب', 'شركتنا', 'الشركة ج', 'أخرى'],
                datasets: [{
                    data: [35, 20, 18, 15, 12],
                    backgroundColor: ['#4299e1', '#ed8936', '#48bb78', '#9f7aea', '#f56565'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '60%'
            }
        });
        console.log('Market share chart initialized');
    } catch (error) {
        console.error('Error initializing market share chart:', error);
    }
}

// Performance Chart (Radar)
function initPerformanceChart() {
    const canvas = document.getElementById('performanceChart');
    if (!canvas) return;

    try {
        new Chart(canvas, {
            type: 'radar',
            data: {
                labels: ['العائد على الاستثمار', 'رضا العملاء', 'دوران المخزون', 'نمو المبيعات', 'هامش الربح'],
                datasets: [{
                    label: 'الأداء الحالي',
                    data: [18.7, 87.5, 4.2, 15.5, 23.5],
                    borderColor: '#48bb78',
                    backgroundColor: '#48bb7820',
                    borderWidth: 3
                }, {
                    label: 'الهدف',
                    data: [20.0, 90.0, 4.5, 15.0, 25.0],
                    borderColor: '#4299e1',
                    backgroundColor: '#4299e120',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                scales: { r: { beginAtZero: true, max: 100 } }
            }
        });
        console.log('Performance chart initialized');
    } catch (error) {
        console.error('Error initializing performance chart:', error);
    }
}

// Goals Chart
function initGoalsChart() {
    const canvas = document.getElementById('goalsChart');
    if (!canvas) return;

    try {
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['محقق', 'قيد التنفيذ', 'متأخر'],
                datasets: [{
                    data: [80, 15, 5],
                    backgroundColor: ['#48bb78', '#ed8936', '#f56565'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                cutout: '60%'
            }
        });
        console.log('Goals chart initialized');
    } catch (error) {
        console.error('Error initializing goals chart:', error);
    }
}

// Risk Matrix Chart
function initRiskMatrixChart() {
    const canvas = document.getElementById('riskMatrixChart');
    if (!canvas) return;

    try {
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
                    x: { title: { display: true, text: 'الاحتمالية (%)' }, min: 0, max: 100 },
                    y: { title: { display: true, text: 'التأثير (1-10)' }, min: 0, max: 10 }
                }
            }
        });
        console.log('Risk matrix chart initialized');
    } catch (error) {
        console.error('Error initializing risk matrix chart:', error);
    }
}

// Revenue vs Profit Chart
function initRevenueProfitChart() {
    const canvas = document.getElementById('revenueProfitChart');
    if (!canvas) return;

    try {
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
        console.log('Revenue profit chart initialized');
    } catch (error) {
        console.error('Error initializing revenue profit chart:', error);
    }
}

// Margin Trends Chart
function initMarginTrendsChart() {
    const canvas = document.getElementById('marginTrendsChart');
    if (!canvas) return;

    try {
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
        console.log('Margin trends chart initialized');
    } catch (error) {
        console.error('Error initializing margin trends chart:', error);
    }
}

// Show error message
function showChartError(message) {
    console.error('Chart Error:', message);
    
    // Find all chart containers and show error
    const chartContainers = document.querySelectorAll('[id$="Chart"]');
    chartContainers.forEach(container => {
        if (container) {
            container.parentElement.innerHTML = `
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 300px; background: #fed7d7; border-radius: 10px; color: #c53030; font-weight: 600; text-align: center;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 24px; margin-bottom: 10px;"></i>
                    <div>خطأ في تحميل الرسم البياني</div>
                    <small>${message}</small>
                </div>
            `;
        }
    });
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for other scripts to load
    setTimeout(initializeAnalyticsCharts, 500);
});

// Export for global use
window.initializeAnalyticsCharts = initializeAnalyticsCharts;
