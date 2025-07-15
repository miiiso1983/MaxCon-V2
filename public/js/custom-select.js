/**
 * Custom Select Component for MaxCon ERP
 * Provides searchable, customizable dropdown functionality
 */

class CustomSelect {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            searchable: true,
            placeholder: 'اختر خياراً...',
            searchPlaceholder: 'البحث...',
            noResultsText: 'لا توجد نتائج',
            loadingText: 'جاري التحميل...',
            multiple: false,
            clearable: true,
            ...options
        };
        
        this.isOpen = false;
        this.selectedValues = [];
        this.filteredOptions = [];
        this.highlightedIndex = -1;
        
        this.init();
    }
    
    init() {
        this.createStructure();
        this.bindEvents();
        this.updateDisplay();
    }
    
    createStructure() {
        // Hide original select
        this.element.style.display = 'none';
        
        // Create wrapper
        this.wrapper = document.createElement('div');
        this.wrapper.className = `searchable-select ${this.options.multiple ? 'multi-select' : ''}`;
        
        // Create button
        this.button = document.createElement('button');
        this.button.type = 'button';
        this.button.className = 'select-button';
        this.button.innerHTML = `
            <span class="selected-text placeholder">${this.options.placeholder}</span>
            <i class="fas fa-chevron-down arrow"></i>
        `;
        
        // Create dropdown
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'select-dropdown';
        
        // Create search input if searchable
        if (this.options.searchable) {
            this.searchInput = document.createElement('input');
            this.searchInput.type = 'text';
            this.searchInput.className = 'select-search';
            this.searchInput.placeholder = this.options.searchPlaceholder;
            this.dropdown.appendChild(this.searchInput);
        }
        
        // Create options container
        this.optionsContainer = document.createElement('div');
        this.optionsContainer.className = 'select-options';
        this.dropdown.appendChild(this.optionsContainer);
        
        // Assemble structure
        this.wrapper.appendChild(this.button);
        this.wrapper.appendChild(this.dropdown);
        
        // Insert after original select
        this.element.parentNode.insertBefore(this.wrapper, this.element.nextSibling);
        
        // Load options
        this.loadOptions();
    }
    
    loadOptions() {
        this.allOptions = [];
        const options = this.element.querySelectorAll('option');
        
        options.forEach((option, index) => {
            if (option.value) {
                this.allOptions.push({
                    value: option.value,
                    text: option.textContent.trim(),
                    selected: option.selected,
                    disabled: option.disabled,
                    element: option
                });
                
                if (option.selected) {
                    this.selectedValues.push(option.value);
                }
            }
        });
        
        this.filteredOptions = [...this.allOptions];
        this.renderOptions();
    }
    
    renderOptions() {
        this.optionsContainer.innerHTML = '';
        
        if (this.filteredOptions.length === 0) {
            const noResults = document.createElement('div');
            noResults.className = 'no-results';
            noResults.textContent = this.options.noResultsText;
            this.optionsContainer.appendChild(noResults);
            return;
        }
        
        this.filteredOptions.forEach((option, index) => {
            const optionElement = document.createElement('div');
            optionElement.className = `select-option ${option.selected ? 'selected' : ''}`;
            optionElement.dataset.value = option.value;
            optionElement.dataset.index = index;
            
            if (this.options.multiple) {
                optionElement.className += ' checkbox-option';
                optionElement.innerHTML = `
                    <input type="checkbox" ${option.selected ? 'checked' : ''}>
                    <span>${option.text}</span>
                `;
            } else {
                optionElement.textContent = option.text;
            }
            
            this.optionsContainer.appendChild(optionElement);
        });
    }
    
    bindEvents() {
        // Button click
        this.button.addEventListener('click', (e) => {
            e.preventDefault();
            this.toggle();
        });
        
        // Search input
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                this.filterOptions(e.target.value);
            });
            
            this.searchInput.addEventListener('keydown', (e) => {
                this.handleKeydown(e);
            });
        }
        
        // Options click
        this.optionsContainer.addEventListener('click', (e) => {
            const option = e.target.closest('.select-option');
            if (option && !option.classList.contains('no-results')) {
                this.selectOption(option.dataset.value);
            }
        });
        
        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!this.wrapper.contains(e.target)) {
                this.close();
            }
        });
        
        // Keyboard navigation
        this.button.addEventListener('keydown', (e) => {
            this.handleKeydown(e);
        });
    }
    
    handleKeydown(e) {
        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                if (!this.isOpen) {
                    this.open();
                } else {
                    this.highlightNext();
                }
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                if (this.isOpen) {
                    this.highlightPrevious();
                }
                break;
                
            case 'Enter':
                e.preventDefault();
                if (this.isOpen && this.highlightedIndex >= 0) {
                    const option = this.filteredOptions[this.highlightedIndex];
                    this.selectOption(option.value);
                } else if (!this.isOpen) {
                    this.open();
                }
                break;
                
            case 'Escape':
                this.close();
                break;
                
            case 'Tab':
                this.close();
                break;
        }
    }
    
    highlightNext() {
        this.highlightedIndex = Math.min(this.highlightedIndex + 1, this.filteredOptions.length - 1);
        this.updateHighlight();
    }
    
    highlightPrevious() {
        this.highlightedIndex = Math.max(this.highlightedIndex - 1, 0);
        this.updateHighlight();
    }
    
    updateHighlight() {
        const options = this.optionsContainer.querySelectorAll('.select-option');
        options.forEach((option, index) => {
            option.classList.toggle('highlighted', index === this.highlightedIndex);
        });
        
        // Scroll into view
        if (this.highlightedIndex >= 0) {
            const highlightedOption = options[this.highlightedIndex];
            if (highlightedOption) {
                highlightedOption.scrollIntoView({ block: 'nearest' });
            }
        }
    }
    
    filterOptions(searchTerm) {
        const term = searchTerm.toLowerCase().trim();
        
        if (!term) {
            this.filteredOptions = [...this.allOptions];
        } else {
            this.filteredOptions = this.allOptions.filter(option =>
                option.text.toLowerCase().includes(term)
            );
        }
        
        this.highlightedIndex = -1;
        this.renderOptions();
    }
    
    selectOption(value) {
        if (this.options.multiple) {
            const index = this.selectedValues.indexOf(value);
            if (index > -1) {
                this.selectedValues.splice(index, 1);
            } else {
                this.selectedValues.push(value);
            }
        } else {
            this.selectedValues = [value];
            this.close();
        }
        
        this.updateOriginalSelect();
        this.updateDisplay();
        this.renderOptions();
        
        // Trigger change event
        this.element.dispatchEvent(new Event('change', { bubbles: true }));
    }
    
    updateOriginalSelect() {
        const options = this.element.querySelectorAll('option');
        options.forEach(option => {
            option.selected = this.selectedValues.includes(option.value);
        });
    }
    
    updateDisplay() {
        const selectedText = this.button.querySelector('.selected-text');
        
        if (this.selectedValues.length === 0) {
            selectedText.textContent = this.options.placeholder;
            selectedText.className = 'selected-text placeholder';
        } else if (this.options.multiple) {
            if (this.selectedValues.length === 1) {
                const option = this.allOptions.find(opt => opt.value === this.selectedValues[0]);
                selectedText.textContent = option ? option.text : '';
            } else {
                selectedText.textContent = `تم اختيار ${this.selectedValues.length} عنصر`;
            }
            selectedText.className = 'selected-text';
        } else {
            const option = this.allOptions.find(opt => opt.value === this.selectedValues[0]);
            selectedText.textContent = option ? option.text : '';
            selectedText.className = 'selected-text';
        }
    }
    
    open() {
        if (this.isOpen) return;
        
        this.isOpen = true;
        this.wrapper.classList.add('open');
        
        if (this.searchInput) {
            this.searchInput.focus();
            this.searchInput.value = '';
            this.filterOptions('');
        }
        
        this.highlightedIndex = -1;
    }
    
    close() {
        if (!this.isOpen) return;
        
        this.isOpen = false;
        this.wrapper.classList.remove('open');
        this.highlightedIndex = -1;
    }
    
    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }
    
    setValue(value) {
        if (Array.isArray(value)) {
            this.selectedValues = [...value];
        } else {
            this.selectedValues = value ? [value] : [];
        }
        
        this.updateOriginalSelect();
        this.updateDisplay();
        this.renderOptions();
    }
    
    getValue() {
        return this.options.multiple ? this.selectedValues : this.selectedValues[0] || null;
    }
    
    destroy() {
        this.wrapper.remove();
        this.element.style.display = '';
    }
}

// Auto-initialize all select elements with data-custom-select attribute
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('select[data-custom-select]');
    
    selects.forEach(select => {
        const options = {
            searchable: select.dataset.searchable !== 'false',
            multiple: select.hasAttribute('multiple'),
            placeholder: select.dataset.placeholder || 'اختر خياراً...',
            searchPlaceholder: select.dataset.searchPlaceholder || 'البحث...',
            noResultsText: select.dataset.noResultsText || 'لا توجد نتائج',
        };
        
        new CustomSelect(select, options);
    });
});

// Global function to initialize custom select
window.initCustomSelect = function(element, options = {}) {
    return new CustomSelect(element, options);
};

// Global function to initialize all selects in a container
window.initCustomSelects = function(container = document) {
    const selects = container.querySelectorAll('select[data-custom-select]');
    const instances = [];
    
    selects.forEach(select => {
        const options = {
            searchable: select.dataset.searchable !== 'false',
            multiple: select.hasAttribute('multiple'),
            placeholder: select.dataset.placeholder || 'اختر خياراً...',
            searchPlaceholder: select.dataset.searchPlaceholder || 'البحث...',
            noResultsText: select.dataset.noResultsText || 'لا توجد نتائج',
        };
        
        instances.push(new CustomSelect(select, options));
    });
    
    return instances;
};
