/**
 * Analytics Charts JavaScript Library
 * Handles chart initialization, error handling, and common functionality
 */

// Global chart configuration
window.AnalyticsCharts = {
    // Default colors
    colors: {
        primary: '#667eea',
        secondary: '#764ba2',
        success: '#48bb78',
        info: '#4299e1',
        warning: '#ed8936',
        danger: '#f56565',
        purple: '#9f7aea',
        teal: '#38b2ac'
    },

    // Chart instances storage
    instances: {},

    // Initialize charts
    init: function() {
        console.log('Initializing Analytics Charts...');
        
        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded. Please include Chart.js library.');
            this.showError('Chart.js library is not loaded');
            return false;
        }

        // Set global Chart.js defaults
        Chart.defaults.font.family = 'Cairo, sans-serif';
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#4a5568';
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.padding = 20;

        console.log('Analytics Charts initialized successfully');
        return true;
    },

    // Create chart with error handling
    createChart: function(canvasId, config) {
        try {
            const canvas = document.getElementById(canvasId);
            if (!canvas) {
                console.error(`Canvas element with ID '${canvasId}' not found`);
                this.showChartError(canvasId, 'Chart container not found');
                return null;
            }

            // Show loading state
            this.showLoading(canvasId);

            // Create chart
            const chart = new Chart(canvas, config);
            
            // Store instance
            this.instances[canvasId] = chart;

            // Hide loading state
            this.hideLoading(canvasId);

            console.log(`Chart '${canvasId}' created successfully`);
            return chart;

        } catch (error) {
            console.error(`Error creating chart '${canvasId}':`, error);
            this.showChartError(canvasId, 'Failed to create chart');
            return null;
        }
    },

    // Show loading state
    showLoading: function(canvasId) {
        const canvas = document.getElementById(canvasId);
        if (canvas) {
            const container = canvas.parentElement;
            container.classList.add('chart-loading');
        }
    },

    // Hide loading state
    hideLoading: function(canvasId) {
        const canvas = document.getElementById(canvasId);
        if (canvas) {
            const container = canvas.parentElement;
            container.classList.remove('chart-loading');
        }
    },

    // Show chart error
    showChartError: function(canvasId, message) {
        const canvas = document.getElementById(canvasId);
        if (canvas) {
            const container = canvas.parentElement;
            container.innerHTML = `
                <div class="chart-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>خطأ في تحميل الرسم البياني</div>
                    <small>${message}</small>
                </div>
            `;
        }
    },

    // Show no data state
    showNoData: function(canvasId, message = 'لا توجد بيانات للعرض') {
        const canvas = document.getElementById(canvasId);
        if (canvas) {
            const container = canvas.parentElement;
            container.innerHTML = `
                <div class="chart-no-data">
                    <i class="fas fa-chart-bar"></i>
                    <h4>لا توجد بيانات</h4>
                    <p>${message}</p>
                </div>
            `;
        }
    },

    // Update chart data
    updateChart: function(canvasId, newData) {
        const chart = this.instances[canvasId];
        if (chart) {
            try {
                chart.data = newData;
                chart.update('active');
                console.log(`Chart '${canvasId}' updated successfully`);
            } catch (error) {
                console.error(`Error updating chart '${canvasId}':`, error);
                this.showChartError(canvasId, 'Failed to update chart');
            }
        }
    },

    // Destroy chart
    destroyChart: function(canvasId) {
        const chart = this.instances[canvasId];
        if (chart) {
            chart.destroy();
            delete this.instances[canvasId];
            console.log(`Chart '${canvasId}' destroyed`);
        }
    },

    // Export chart as image
    exportChart: function(canvasId, filename = 'chart') {
        const chart = this.instances[canvasId];
        if (chart) {
            try {
                const url = chart.toBase64Image();
                const link = document.createElement('a');
                link.download = `${filename}.png`;
                link.href = url;
                link.click();
                console.log(`Chart '${canvasId}' exported as ${filename}.png`);
            } catch (error) {
                console.error(`Error exporting chart '${canvasId}':`, error);
                alert('فشل في تصدير الرسم البياني');
            }
        }
    },

    // Common chart configurations
    getLineChartConfig: function(labels, datasets, options = {}) {
        return {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e2e8f0'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                ...options
            }
        };
    },

    getBarChartConfig: function(labels, datasets, options = {}) {
        return {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e2e8f0'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                ...options
            }
        };
    },

    getDoughnutChartConfig: function(labels, data, options = {}) {
        return {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        this.colors.success,
                        this.colors.info,
                        this.colors.warning,
                        this.colors.purple,
                        this.colors.danger
                    ],
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
                        position: 'bottom'
                    }
                },
                cutout: '60%',
                ...options
            }
        };
    },

    // Show general error
    showError: function(message) {
        console.error('Analytics Charts Error:', message);
        
        // Create error notification
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f56565;
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 10000;
            font-weight: 600;
            animation: slideIn 0.3s ease-out;
        `;
        notification.innerHTML = `
            <i class="fas fa-exclamation-triangle" style="margin-left: 8px;"></i>
            ${message}
        `;
        
        document.body.appendChild(notification);
        
        // Remove notification after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    },

    // Utility function to format numbers
    formatNumber: function(value, type = 'number') {
        if (typeof value !== 'number') return value;
        
        switch (type) {
            case 'currency':
                return new Intl.NumberFormat('ar-IQ', {
                    style: 'currency',
                    currency: 'IQD',
                    minimumFractionDigits: 0
                }).format(value);
            case 'percentage':
                return value.toFixed(1) + '%';
            case 'compact':
                if (value >= 1000000) {
                    return (value / 1000000).toFixed(1) + 'M';
                } else if (value >= 1000) {
                    return (value / 1000).toFixed(1) + 'K';
                }
                return value.toString();
            default:
                return new Intl.NumberFormat('ar-IQ').format(value);
        }
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.AnalyticsCharts.init();
});

// Handle window resize
window.addEventListener('resize', function() {
    Object.values(window.AnalyticsCharts.instances).forEach(chart => {
        if (chart) {
            chart.resize();
        }
    });
});

// Export for global use
window.AC = window.AnalyticsCharts;
