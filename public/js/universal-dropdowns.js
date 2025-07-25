/**
 * Universal Dropdown System for MaxCon ERP
 * Automatically converts all select elements to searchable dropdowns
 */

(function() {
    'use strict';

    // Configuration
    const config = {
        autoInit: true,
        searchThreshold: 5, // Number of options to trigger search
        defaultPlaceholder: 'اختر خياراً...',
        searchPlaceholder: 'البحث...',
        noResultsText: 'لا توجد نتائج',
        loadingText: 'جاري التحميل...',
        clearText: 'مسح',
        selectAllText: 'اختيار الكل',
        deselectAllText: 'إلغاء اختيار الكل'
    };

    // Selectors to ignore
    const ignoredSelectors = [
        '.dataTables_length select',
        '.pagination select',
        '.dt-buttons select',
        '[data-no-search]',
        '[data-no-custom-select]',
        '.no-custom-select',
        '.ignore-dropdown'
    ];

    // Context-specific configurations
    const contextConfigs = {
        '.form-group': {
            searchable: true,
            clearable: true
        },
        '.filter-form': {
            searchable: true,
            clearable: true
        },
        '.modal': {
            searchable: true,
            clearable: false
        },
        '.sidebar': {
            searchable: false,
            clearable: false
        }
    };

    /**
     * Check if select should be ignored
     */
    function shouldIgnoreSelect(select) {
        // Check if already initialized
        if (select.dataset.initialized === 'true') {
            return true;
        }

        // Check ignored selectors
        for (let selector of ignoredSelectors) {
            if (select.matches(selector) || select.closest(selector.split(' ')[0])) {
                return true;
            }
        }

        // Check if has very few options
        if (select.options.length <= 2) {
            return true;
        }

        // Check if hidden
        if (select.style.display === 'none' || select.hidden) {
            return true;
        }

        return false;
    }

    /**
     * Get context-specific configuration
     */
    function getContextConfig(select) {
        for (let [selector, config] of Object.entries(contextConfigs)) {
            if (select.closest(selector)) {
                return config;
            }
        }
        return {};
    }

    /**
     * Generate appropriate placeholder
     */
    function generatePlaceholder(select) {
        // Check existing placeholder in data attribute
        if (select.dataset.placeholder) {
            return select.dataset.placeholder;
        }

        // Check first option as placeholder
        const firstOption = select.options[0];
        if (firstOption && (firstOption.value === '' || 
            firstOption.textContent.includes('اختر') || 
            firstOption.textContent.includes('جميع') ||
            firstOption.textContent.includes('كل'))) {
            return firstOption.textContent.trim();
        }

        // Generate from label
        const label = select.closest('.form-group, .field, div')?.querySelector('label');
        if (label) {
            const labelText = label.textContent.replace(/[*:]/g, '').trim();
            return `اختر ${labelText}...`;
        }

        // Generate from name attribute
        if (select.name) {
            const nameText = select.name.replace(/[_\[\]]/g, ' ').trim();
            return `اختر ${nameText}...`;
        }

        return config.defaultPlaceholder;
    }

    /**
     * Initialize a single select element
     */
    function initializeSelect(select) {
        if (shouldIgnoreSelect(select)) {
            return null;
        }

        // Add data-custom-select attribute if not present
        if (!select.hasAttribute('data-custom-select')) {
            select.setAttribute('data-custom-select', '');
        }

        // Get context configuration
        const contextConfig = getContextConfig(select);
        
        // Determine if should be searchable
        const optionCount = select.options.length;
        const shouldBeSearchable = contextConfig.searchable !== false && 
                                 (select.dataset.searchable !== 'false') && 
                                 optionCount >= config.searchThreshold;

        // Build options
        const options = {
            searchable: shouldBeSearchable,
            multiple: select.hasAttribute('multiple'),
            placeholder: generatePlaceholder(select),
            searchPlaceholder: select.dataset.searchPlaceholder || config.searchPlaceholder,
            noResultsText: select.dataset.noResultsText || config.noResultsText,
            clearable: contextConfig.clearable !== false && select.dataset.clearable !== 'false',
            ...contextConfig
        };

        try {
            // Initialize CustomSelect if available
            if (window.CustomSelect) {
                const instance = new CustomSelect(select, options);
                select.dataset.initialized = 'true';
                return instance;
            } else {
                console.warn('CustomSelect class not found. Make sure custom-select.js is loaded.');
                return null;
            }
        } catch (error) {
            console.error('Error initializing select:', error);
            return null;
        }
    }

    /**
     * Initialize all selects in a container
     */
    function initializeAllSelects(container = document) {
        const selects = container.querySelectorAll('select');
        const instances = [];

        selects.forEach(select => {
            const instance = initializeSelect(select);
            if (instance) {
                instances.push(instance);
            }
        });

        return instances;
    }

    /**
     * Reinitialize selects after dynamic content
     */
    function reinitializeSelects(container = document) {
        // Remove existing wrappers
        const wrappers = container.querySelectorAll('.custom-select-wrapper');
        wrappers.forEach(wrapper => {
            const select = wrapper.querySelector('select');
            if (select) {
                select.dataset.initialized = '';
                wrapper.parentNode.insertBefore(select, wrapper);
                wrapper.remove();
            }
        });

        return initializeAllSelects(container);
    }

    /**
     * Setup mutation observer for dynamic content
     */
    function setupMutationObserver() {
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1) { // Element node
                            const selects = node.querySelectorAll ? 
                                           node.querySelectorAll('select:not([data-initialized="true"])') : [];
                            
                            if (selects.length > 0 || (node.tagName === 'SELECT' && !node.dataset.initialized)) {
                                setTimeout(() => {
                                    initializeAllSelects(node.parentNode || document);
                                }, 100);
                            }
                        }
                    });
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        return observer;
    }

    /**
     * Initialize on DOM ready
     */
    function init() {
        if (config.autoInit) {
            initializeAllSelects();
            setupMutationObserver();
        }
    }

    // Public API
    window.UniversalDropdowns = {
        init: init,
        initializeSelect: initializeSelect,
        initializeAllSelects: initializeAllSelects,
        reinitializeSelects: reinitializeSelects,
        config: config
    };

    // Auto-initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Also initialize on window load for safety
    window.addEventListener('load', () => {
        setTimeout(init, 500);
    });

})();
