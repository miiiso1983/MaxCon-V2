import './bootstrap';
import Alpine from 'alpinejs';

// Alpine.js plugins and configurations
Alpine.start();

// Make Alpine available globally
window.Alpine = Alpine;

import.meta.glob([
    '../images/**',
    '../fonts/**',
]);

// Custom JavaScript utilities
window.utils = {
    // Format currency
    formatCurrency(amount, currency = 'USD') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency
        }).format(amount);
    },

    // Format date
    formatDate(date, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        };
        return new Intl.DateTimeFormat('en-US', { ...defaultOptions, ...options }).format(new Date(date));
    },

    // Show toast notification
    showToast(message, type = 'success') {
        // This will be implemented with a toast component
        console.log(`${type.toUpperCase()}: ${message}`);
    },

    // Copy to clipboard
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            this.showToast('Copied to clipboard!');
        } catch (err) {
            console.error('Failed to copy: ', err);
        }
    }
};
