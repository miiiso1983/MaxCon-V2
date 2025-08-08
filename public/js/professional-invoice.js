/**
 * Professional Invoice JavaScript Enhancements
 * Advanced functionality for the professional invoice creation system
 */

class ProfessionalInvoice {
    constructor() {
        this.itemIndex = 1;
        this.autoSaveInterval = null;
        this.searchCache = new Map();
        this.validationRules = {};
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeSearch();
        this.setupAutoSave();
        this.loadUserPreferences();
        this.initializeValidation();
    }

    // Advanced Search Functionality
    initializeSearch() {
        const searchInputs = document.querySelectorAll('.search-input');
        searchInputs.forEach(input => {
            input.addEventListener('input', this.debounce((e) => {
                this.performSearch(e.target);
            }, 300));
        });
    }

    performSearch(input) {
        const query = input.value.toLowerCase().trim();
        const targetSelect = input.dataset.target;
        const selectElement = document.getElementById(targetSelect);
        
        if (!selectElement) return;

        // Use cached results if available
        const cacheKey = `${targetSelect}_${query}`;
        if (this.searchCache.has(cacheKey)) {
            this.displaySearchResults(selectElement, this.searchCache.get(cacheKey));
            return;
        }

        // Perform search
        const options = Array.from(selectElement.options);
        const results = options.filter(option => {
            if (!option.value) return false;
            
            const searchText = option.dataset.search || option.textContent.toLowerCase();
            return searchText.includes(query);
        });

        // Cache results
        this.searchCache.set(cacheKey, results);
        this.displaySearchResults(selectElement, results);
    }

    displaySearchResults(selectElement, results) {
        // Clear current options except the first one
        while (selectElement.children.length > 1) {
            selectElement.removeChild(selectElement.lastChild);
        }

        // Add filtered results
        results.forEach(option => {
            selectElement.appendChild(option.cloneNode(true));
        });

        // Highlight search terms
        this.highlightSearchTerms(selectElement);
    }

    highlightSearchTerms(selectElement) {
        const query = document.querySelector(`[data-target="${selectElement.id}"]`)?.value;
        if (!query) return;

        Array.from(selectElement.options).forEach(option => {
            if (option.value) {
                const text = option.textContent;
                const highlightedText = text.replace(
                    new RegExp(`(${query})`, 'gi'),
                    '<span class="search-highlight">$1</span>'
                );
                option.innerHTML = highlightedText;
            }
        });
    }

    // Advanced Auto-Save
    setupAutoSave() {
        const form = document.getElementById('invoiceForm');
        if (!form) return;

        // Auto-save every 30 seconds
        this.autoSaveInterval = setInterval(() => {
            this.saveToLocalStorage();
        }, 30000);

        // Save on form changes
        form.addEventListener('change', this.debounce(() => {
            this.saveToLocalStorage();
        }, 1000));
    }

    saveToLocalStorage() {
        const formData = new FormData(document.getElementById('invoiceForm'));
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        // Add timestamp
        data._timestamp = Date.now();
        data._version = '1.0';

        try {
            localStorage.setItem('professional_invoice_draft', JSON.stringify(data));
            this.showNotification('تم حفظ المسودة تلقائياً', 'success', 2000);
        } catch (error) {
            console.error('Auto-save failed:', error);
        }
    }

    loadFromLocalStorage() {
        try {
            const saved = localStorage.getItem('professional_invoice_draft');
            if (!saved) return false;

            const data = JSON.parse(saved);
            
            // Check if data is recent (within 24 hours)
            const age = Date.now() - (data._timestamp || 0);
            if (age > 24 * 60 * 60 * 1000) {
                localStorage.removeItem('professional_invoice_draft');
                return false;
            }

            return data;
        } catch (error) {
            console.error('Failed to load draft:', error);
            return false;
        }
    }

    // Advanced Validation
    initializeValidation() {
        this.validationRules = {
            customer_id: {
                required: true,
                message: 'يرجى اختيار العميل'
            },
            'items[*][product_id]': {
                required: true,
                message: 'يرجى اختيار المنتج'
            },
            'items[*][quantity]': {
                required: true,
                min: 1,
                message: 'يرجى إدخال كمية صحيحة'
            },
            'items[*][unit_price]': {
                required: true,
                min: 0,
                message: 'يرجى إدخال سعر صحيح'
            }
        };
    }

    validateForm() {
        const errors = [];
        const form = document.getElementById('invoiceForm');
        
        // Validate customer
        const customerSelect = form.querySelector('[name="customer_id"]');
        if (!customerSelect.value) {
            errors.push('يرجى اختيار العميل');
            this.highlightError(customerSelect);
        }

        // Validate items
        const itemRows = form.querySelectorAll('[name*="[product_id]"]');
        let hasValidItems = false;

        itemRows.forEach((select, index) => {
            if (select.value) {
                hasValidItems = true;
                
                // Validate quantity
                const quantityInput = form.querySelector(`[name="items[${index}][quantity]"]`);
                if (!quantityInput.value || quantityInput.value < 1) {
                    errors.push(`يرجى إدخال كمية صحيحة للمنتج ${index + 1}`);
                    this.highlightError(quantityInput);
                }

                // Validate price
                const priceInput = form.querySelector(`[name="items[${index}][unit_price]"]`);
                if (!priceInput.value || priceInput.value < 0) {
                    errors.push(`يرجى إدخال سعر صحيح للمنتج ${index + 1}`);
                    this.highlightError(priceInput);
                }

                // Validate stock
                if (!this.validateStock(index)) {
                    errors.push(`الكمية المطلوبة للمنتج ${index + 1} تتجاوز المخزون المتاح`);
                }
            }
        });

        if (!hasValidItems) {
            errors.push('يرجى إضافة منتج واحد على الأقل');
        }

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    highlightError(element) {
        element.classList.add('error');
        element.addEventListener('input', () => {
            element.classList.remove('error');
        }, { once: true });
    }

    // Stock Validation
    validateStock(index) {
        const productSelect = document.querySelector(`[name="items[${index}][product_id]"]`);
        const quantityInput = document.querySelector(`[name="items[${index}][quantity]"]`);
        
        if (!productSelect || !quantityInput) return true;

        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const stock = parseInt(selectedOption.dataset.stock || 0);
        const quantity = parseInt(quantityInput.value || 0);

        return quantity <= stock;
    }

    // User Preferences
    loadUserPreferences() {
        try {
            const prefs = JSON.parse(localStorage.getItem('invoice_preferences') || '{}');
            
            // Apply saved preferences
            if (prefs.autoSave !== undefined) {
                this.autoSaveEnabled = prefs.autoSave;
            }
            
            if (prefs.defaultWarehouse) {
                const warehouseInput = document.querySelector('[name="warehouse_name"]');
                if (warehouseInput && !warehouseInput.value) {
                    warehouseInput.value = prefs.defaultWarehouse;
                }
            }

            if (prefs.defaultSalesRep) {
                const salesRepInput = document.querySelector('[name="sales_representative"]');
                if (salesRepInput && !salesRepInput.value) {
                    salesRepInput.value = prefs.defaultSalesRep;
                }
            }
        } catch (error) {
            console.error('Failed to load preferences:', error);
        }
    }

    saveUserPreferences() {
        const prefs = {
            autoSave: this.autoSaveEnabled,
            defaultWarehouse: document.querySelector('[name="warehouse_name"]')?.value,
            defaultSalesRep: document.querySelector('[name="sales_representative"]')?.value,
            lastUpdated: Date.now()
        };

        try {
            localStorage.setItem('invoice_preferences', JSON.stringify(prefs));
        } catch (error) {
            console.error('Failed to save preferences:', error);
        }
    }

    // Enhanced Notifications
    showNotification(message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        const colors = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#3b82f6'
        };

        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${colors[type]};
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            z-index: 10000;
            font-weight: 600;
            transform: translateX(100%);
            transition: all 0.3s ease;
            max-width: 400px;
            font-family: 'Cairo', sans-serif;
        `;

        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="${icons[type]}"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Animate out
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, duration);
    }

    // Utility Functions
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    setupEventListeners() {
        // Form submission
        const form = document.getElementById('invoiceForm');
        if (form) {
            form.addEventListener('submit', (e) => {
                const validation = this.validateForm();
                if (!validation.isValid) {
                    e.preventDefault();
                    this.showNotification(validation.errors.join('<br>'), 'error', 5000);
                    return false;
                }
                
                // Clear draft on successful submission
                localStorage.removeItem('professional_invoice_draft');
                this.saveUserPreferences();
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                this.saveToLocalStorage();
                this.showNotification('تم حفظ المسودة', 'success');
            }
        });

        // Window beforeunload
        window.addEventListener('beforeunload', () => {
            this.saveToLocalStorage();
            this.saveUserPreferences();
        });
    }

    // Cleanup
    destroy() {
        if (this.autoSaveInterval) {
            clearInterval(this.autoSaveInterval);
        }
        this.searchCache.clear();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.professionalInvoice = new ProfessionalInvoice();
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.professionalInvoice) {
        window.professionalInvoice.destroy();
    }
});
