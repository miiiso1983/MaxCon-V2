/**
 * Advanced Invoice Validation System
 * Comprehensive validation for professional invoice creation
 */

class InvoiceValidator {
    constructor() {
        this.rules = {
            customer_id: {
                required: true,
                message: 'يرجى اختيار العميل'
            },
            invoice_date: {
                required: true,
                type: 'date',
                message: 'يرجى إدخال تاريخ صحيح للفاتورة'
            },
            due_date: {
                required: false,
                type: 'date',
                after: 'invoice_date',
                message: 'تاريخ الاستحقاق يجب أن يكون بعد تاريخ الفاتورة'
            }
        };
        
        this.itemRules = {
            product_id: {
                required: true,
                message: 'يرجى اختيار المنتج'
            },
            quantity: {
                required: true,
                type: 'number',
                min: 1,
                message: 'يرجى إدخال كمية صحيحة (أكبر من صفر)'
            },
            unit_price: {
                required: true,
                type: 'number',
                min: 0,
                message: 'يرجى إدخال سعر صحيح'
            },
            discount_amount: {
                required: false,
                type: 'number',
                min: 0,
                message: 'قيمة الخصم يجب أن تكون صفر أو أكبر'
            },
            free_samples: {
                required: false,
                type: 'number',
                min: 0,
                message: 'عدد العينات المجانية يجب أن يكون صفر أو أكبر'
            }
        };
        
        this.errors = [];
        this.warnings = [];
    }

    // Main validation function
    validateInvoice() {
        this.errors = [];
        this.warnings = [];
        
        // Validate basic invoice fields
        this.validateBasicFields();
        
        // Validate invoice items
        this.validateItems();
        
        // Validate business rules
        this.validateBusinessRules();
        
        return {
            isValid: this.errors.length === 0,
            errors: this.errors,
            warnings: this.warnings
        };
    }

    // Validate basic invoice fields
    validateBasicFields() {
        const form = document.getElementById('invoiceForm');
        
        Object.keys(this.rules).forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            const rule = this.rules[fieldName];
            
            if (!field) return;
            
            const value = field.value.trim();
            
            // Required validation
            if (rule.required && !value) {
                this.addError(field, rule.message);
                return;
            }
            
            if (!value) return; // Skip other validations if field is empty and not required
            
            // Type validation
            if (rule.type === 'date' && !this.isValidDate(value)) {
                this.addError(field, 'تاريخ غير صحيح');
                return;
            }
            
            if (rule.type === 'number' && !this.isValidNumber(value)) {
                this.addError(field, 'رقم غير صحيح');
                return;
            }
            
            // Min/Max validation
            if (rule.min !== undefined && parseFloat(value) < rule.min) {
                this.addError(field, `القيمة يجب أن تكون ${rule.min} أو أكبر`);
            }
            
            if (rule.max !== undefined && parseFloat(value) > rule.max) {
                this.addError(field, `القيمة يجب أن تكون ${rule.max} أو أقل`);
            }
            
            // Date comparison validation
            if (rule.after) {
                const compareField = form.querySelector(`[name="${rule.after}"]`);
                if (compareField && compareField.value && new Date(value) <= new Date(compareField.value)) {
                    this.addError(field, rule.message);
                }
            }
        });
    }

    // Validate invoice items
    validateItems() {
        const itemRows = document.querySelectorAll('[name*="[product_id]"]');
        let hasValidItems = false;
        
        itemRows.forEach((productSelect, index) => {
            if (productSelect.value) {
                hasValidItems = true;
                this.validateSingleItem(index);
            }
        });
        
        if (!hasValidItems) {
            this.errors.push('يرجى إضافة منتج واحد على الأقل إلى الفاتورة');
        }
    }

    // Validate single item
    validateSingleItem(index) {
        const form = document.getElementById('invoiceForm');
        
        Object.keys(this.itemRules).forEach(fieldName => {
            const field = form.querySelector(`[name="items[${index}][${fieldName}]"]`);
            const rule = this.itemRules[fieldName];
            
            if (!field) return;
            
            const value = field.value.trim();
            
            // Required validation
            if (rule.required && !value) {
                this.addError(field, `المنتج ${index + 1}: ${rule.message}`);
                return;
            }
            
            if (!value) return;
            
            // Type validation
            if (rule.type === 'number' && !this.isValidNumber(value)) {
                this.addError(field, `المنتج ${index + 1}: رقم غير صحيح`);
                return;
            }
            
            // Min/Max validation
            if (rule.min !== undefined && parseFloat(value) < rule.min) {
                this.addError(field, `المنتج ${index + 1}: ${rule.message}`);
            }
        });
        
        // Stock validation
        this.validateItemStock(index);
        
        // Discount validation
        this.validateItemDiscount(index);
    }

    // Validate item stock
    validateItemStock(index) {
        const productSelect = document.querySelector(`[name="items[${index}][product_id]"]`);
        const quantityInput = document.querySelector(`[name="items[${index}][quantity]"]`);
        
        if (!productSelect || !quantityInput || !productSelect.value) return;
        
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const stock = parseInt(selectedOption.dataset.stock || 0);
        const quantity = parseInt(quantityInput.value || 0);
        
        if (quantity > stock) {
            this.addError(quantityInput, `المنتج ${index + 1}: الكمية المطلوبة (${quantity}) تتجاوز المخزون المتاح (${stock})`);
        } else if (stock <= 10 && stock > 0) {
            this.addWarning(quantityInput, `المنتج ${index + 1}: المخزون منخفض (${stock} وحدة متبقية)`);
        }
    }

    // Validate item discount
    validateItemDiscount(index) {
        const quantityInput = document.querySelector(`[name="items[${index}][quantity]"]`);
        const priceInput = document.querySelector(`[name="items[${index}][unit_price]"]`);
        const discountInput = document.querySelector(`[name="items[${index}][discount_amount]"]`);
        const discountTypeSelect = document.querySelector(`[name="items[${index}][discount_type]"]`);
        
        if (!quantityInput || !priceInput || !discountInput) return;
        
        const quantity = parseFloat(quantityInput.value || 0);
        const price = parseFloat(priceInput.value || 0);
        const discount = parseFloat(discountInput.value || 0);
        const discountType = discountTypeSelect ? discountTypeSelect.value : 'amount';
        
        const lineTotal = quantity * price;
        
        if (discountType === 'percentage') {
            if (discount > 100) {
                this.addError(discountInput, `المنتج ${index + 1}: نسبة الخصم لا يمكن أن تتجاوز 100%`);
            } else if (discount > 50) {
                this.addWarning(discountInput, `المنتج ${index + 1}: نسبة خصم عالية (${discount}%)`);
            }
        } else {
            if (discount > lineTotal) {
                this.addError(discountInput, `المنتج ${index + 1}: قيمة الخصم لا يمكن أن تتجاوز إجمالي المنتج`);
            } else if (discount > lineTotal * 0.5) {
                this.addWarning(discountInput, `المنتج ${index + 1}: خصم عالي (${((discount / lineTotal) * 100).toFixed(1)}%)`);
            }
        }
    }

    // Validate business rules
    validateBusinessRules() {
        this.validateCreditLimit();
        this.validateDuplicateProducts();
        this.validateMinimumAmount();
    }

    // Validate credit limit
    validateCreditLimit() {
        const customerSelect = document.getElementById('customerSelect');
        if (!customerSelect || !customerSelect.value) return;
        
        const selectedOption = customerSelect.options[customerSelect.selectedIndex];
        const creditLimit = parseFloat(selectedOption.dataset.creditLimit || 0);
        const previousBalance = parseFloat(selectedOption.dataset.previousBalance || 0);
        const totalAmount = parseFloat(document.getElementById('totalAmount').value || 0);
        const totalDebt = previousBalance + totalAmount;
        
        if (creditLimit > 0 && totalDebt > creditLimit) {
            const excess = totalDebt - creditLimit;
            this.addError(customerSelect, `إجمالي المديونية (${totalDebt.toFixed(2)} د.ع) يتجاوز سقف المديونية (${creditLimit.toFixed(2)} د.ع) بمقدار ${excess.toFixed(2)} د.ع`);
        } else if (creditLimit > 0 && totalDebt > creditLimit * 0.9) {
            this.addWarning(customerSelect, `المديونية تقترب من السقف المحدد (${((totalDebt / creditLimit) * 100).toFixed(1)}%)`);
        }
    }

    // Validate duplicate products
    validateDuplicateProducts() {
        const productSelects = document.querySelectorAll('[name*="[product_id]"]');
        const selectedProducts = new Map();
        
        productSelects.forEach((select, index) => {
            if (select.value) {
                if (selectedProducts.has(select.value)) {
                    const firstIndex = selectedProducts.get(select.value);
                    this.addWarning(select, `المنتج مكرر في الصف ${firstIndex + 1} والصف ${index + 1}`);
                } else {
                    selectedProducts.set(select.value, index);
                }
            }
        });
    }

    // Validate minimum invoice amount
    validateMinimumAmount() {
        const totalAmount = parseFloat(document.getElementById('totalAmount').value || 0);
        const minimumAmount = 1000; // Minimum invoice amount
        
        if (totalAmount < minimumAmount) {
            this.addWarning(null, `إجمالي الفاتورة (${totalAmount.toFixed(2)} د.ع) أقل من الحد الأدنى المنصوح به (${minimumAmount} د.ع)`);
        }
    }

    // Helper functions
    isValidDate(dateString) {
        const date = new Date(dateString);
        return date instanceof Date && !isNaN(date);
    }

    isValidNumber(value) {
        return !isNaN(value) && isFinite(value);
    }

    addError(field, message) {
        this.errors.push(message);
        if (field) {
            field.classList.add('error');
            this.showFieldError(field, message);
        }
    }

    addWarning(field, message) {
        this.warnings.push(message);
        if (field) {
            field.classList.add('warning');
            this.showFieldWarning(field, message);
        }
    }

    showFieldError(field, message) {
        this.removeFieldMessages(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        errorDiv.style.cssText = `
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        `;
        
        field.parentNode.appendChild(errorDiv);
    }

    showFieldWarning(field, message) {
        this.removeFieldMessages(field);
        
        const warningDiv = document.createElement('div');
        warningDiv.className = 'field-warning';
        warningDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
        warningDiv.style.cssText = `
            color: #f59e0b;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        `;
        
        field.parentNode.appendChild(warningDiv);
    }

    removeFieldMessages(field) {
        const parent = field.parentNode;
        const existingError = parent.querySelector('.field-error');
        const existingWarning = parent.querySelector('.field-warning');
        
        if (existingError) parent.removeChild(existingError);
        if (existingWarning) parent.removeChild(existingWarning);
    }

    clearAllValidation() {
        // Remove error/warning classes
        document.querySelectorAll('.error, .warning').forEach(element => {
            element.classList.remove('error', 'warning');
        });
        
        // Remove error/warning messages
        document.querySelectorAll('.field-error, .field-warning').forEach(element => {
            element.parentNode.removeChild(element);
        });
        
        this.errors = [];
        this.warnings = [];
    }

    // Real-time validation
    setupRealTimeValidation() {
        const form = document.getElementById('invoiceForm');
        if (!form) return;
        
        // Validate on input change
        form.addEventListener('input', (e) => {
            if (e.target.matches('input, select, textarea')) {
                this.validateField(e.target);
            }
        });
        
        // Validate on blur
        form.addEventListener('blur', (e) => {
            if (e.target.matches('input, select, textarea')) {
                this.validateField(e.target);
            }
        }, true);
    }

    validateField(field) {
        // Clear previous validation for this field
        field.classList.remove('error', 'warning');
        this.removeFieldMessages(field);
        
        // Get field name and index if it's an item field
        const name = field.name;
        const itemMatch = name.match(/items\[(\d+)\]\[(\w+)\]/);
        
        if (itemMatch) {
            const index = parseInt(itemMatch[1]);
            const fieldName = itemMatch[2];
            
            // Validate single item field
            if (this.itemRules[fieldName]) {
                this.validateSingleItemField(index, fieldName, field);
            }
        } else {
            // Validate basic field
            if (this.rules[name]) {
                this.validateBasicField(name, field);
            }
        }
    }

    validateSingleItemField(index, fieldName, field) {
        const rule = this.itemRules[fieldName];
        const value = field.value.trim();
        
        if (rule.required && !value) {
            this.addError(field, rule.message);
            return;
        }
        
        if (!value) return;
        
        if (rule.type === 'number' && !this.isValidNumber(value)) {
            this.addError(field, 'رقم غير صحيح');
            return;
        }
        
        if (rule.min !== undefined && parseFloat(value) < rule.min) {
            this.addError(field, rule.message);
        }
        
        // Special validations
        if (fieldName === 'quantity') {
            this.validateItemStock(index);
        }
        
        if (fieldName === 'discount_amount') {
            this.validateItemDiscount(index);
        }
    }

    validateBasicField(fieldName, field) {
        const rule = this.rules[fieldName];
        const value = field.value.trim();
        
        if (rule.required && !value) {
            this.addError(field, rule.message);
            return;
        }
        
        if (!value) return;
        
        if (rule.type === 'date' && !this.isValidDate(value)) {
            this.addError(field, 'تاريخ غير صحيح');
            return;
        }
        
        if (rule.type === 'number' && !this.isValidNumber(value)) {
            this.addError(field, 'رقم غير صحيح');
            return;
        }
        
        if (rule.min !== undefined && parseFloat(value) < rule.min) {
            this.addError(field, `القيمة يجب أن تكون ${rule.min} أو أكبر`);
        }
    }
}

// Initialize validator when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.invoiceValidator = new InvoiceValidator();
    window.invoiceValidator.setupRealTimeValidation();
});
