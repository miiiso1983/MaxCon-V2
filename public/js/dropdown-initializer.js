/**
 * Dropdown Initializer for MaxCon ERP
 * Ensures all dropdowns are converted to searchable selects
 */

(function() {
    'use strict';

    // Configuration for different page types
    const pageConfigs = {
        // Inventory pages
        'inventory': {
            searchThreshold: 3,
            defaultSearchable: true,
            clearable: true
        },
        // Sales pages
        'sales': {
            searchThreshold: 5,
            defaultSearchable: true,
            clearable: true
        },
        // HR pages
        'hr': {
            searchThreshold: 4,
            defaultSearchable: true,
            clearable: false
        },
        // Admin pages
        'admin': {
            searchThreshold: 3,
            defaultSearchable: true,
            clearable: true
        },
        // Reports pages
        'reports': {
            searchThreshold: 2,
            defaultSearchable: false,
            clearable: true
        }
    };

    /**
     * Get page configuration based on URL
     */
    function getPageConfig() {
        const path = window.location.pathname;
        
        for (let [key, config] of Object.entries(pageConfigs)) {
            if (path.includes(key)) {
                return config;
            }
        }
        
        // Default configuration
        return {
            searchThreshold: 5,
            defaultSearchable: true,
            clearable: true
        };
    }

    /**
     * Initialize dropdowns with page-specific configuration
     */
    function initializePageDropdowns() {
        const config = getPageConfig();
        const selects = document.querySelectorAll('select:not([data-initialized="true"])');
        
        selects.forEach(select => {
            // Skip if should be ignored
            if (shouldIgnoreSelect(select)) {
                return;
            }

            // Apply page-specific configuration
            const optionCount = select.options.length;
            const shouldBeSearchable = optionCount >= config.searchThreshold && config.defaultSearchable;
            
            // Set attributes
            select.setAttribute('data-custom-select', '');
            if (!select.dataset.searchable) {
                select.dataset.searchable = shouldBeSearchable.toString();
            }
            if (!select.dataset.clearable) {
                select.dataset.clearable = config.clearable.toString();
            }
            if (!select.dataset.placeholder) {
                select.dataset.placeholder = generatePlaceholder(select);
            }
        });

        // Initialize with UniversalDropdowns if available
        if (window.UniversalDropdowns) {
            window.UniversalDropdowns.initializeAllSelects();
        } else if (window.initCustomSelects) {
            window.initCustomSelects();
        }
    }

    /**
     * Check if select should be ignored
     */
    function shouldIgnoreSelect(select) {
        const ignoredSelectors = [
            '.dataTables_length',
            '.pagination',
            '.dt-buttons',
            '[data-no-search]',
            '[data-no-custom-select]'
        ];

        for (let selector of ignoredSelectors) {
            if (select.closest(selector)) {
                return true;
            }
        }

        return select.options.length <= 2 || select.dataset.initialized === 'true';
    }

    /**
     * Generate placeholder for select
     */
    function generatePlaceholder(select) {
        // Check first option
        const firstOption = select.options[0];
        if (firstOption && (firstOption.value === '' || 
            firstOption.textContent.includes('اختر') || 
            firstOption.textContent.includes('جميع'))) {
            return firstOption.textContent.trim();
        }

        // Generate from label
        const label = select.closest('.form-group, div')?.querySelector('label');
        if (label) {
            const labelText = label.textContent.replace(/[*:]/g, '').trim();
            return `اختر ${labelText}...`;
        }

        return 'اختر خياراً...';
    }

    /**
     * Handle dynamic content loading
     */
    function handleDynamicContent() {
        // Listen for common events that indicate new content
        const events = ['DOMContentLoaded', 'load', 'livewire:load', 'turbo:load'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                setTimeout(initializePageDropdowns, 100);
            });
        });

        // Listen for Livewire updates
        if (window.Livewire) {
            window.Livewire.hook('message.processed', () => {
                setTimeout(initializePageDropdowns, 200);
            });
        }

        // Listen for AJAX completions
        if (window.jQuery) {
            jQuery(document).ajaxComplete(() => {
                setTimeout(initializePageDropdowns, 100);
            });
        }

        // Mutation observer for dynamic content
        const observer = new MutationObserver(mutations => {
            let shouldReinit = false;
            
            mutations.forEach(mutation => {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1) {
                            const selects = node.querySelectorAll ? 
                                           node.querySelectorAll('select:not([data-initialized="true"])') : [];
                            if (selects.length > 0 || (node.tagName === 'SELECT' && !node.dataset.initialized)) {
                                shouldReinit = true;
                            }
                        }
                    });
                }
            });

            if (shouldReinit) {
                setTimeout(initializePageDropdowns, 150);
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    /**
     * Initialize on page load
     */
    function init() {
        initializePageDropdowns();
        handleDynamicContent();
        
        // Log initialization
        console.log('🔽 Dropdown Initializer loaded for:', window.location.pathname);
        console.log('📊 Page config:', getPageConfig());
    }

    // Public API
    window.DropdownInitializer = {
        init: init,
        initializePageDropdowns: initializePageDropdowns,
        getPageConfig: getPageConfig
    };

    // Auto-initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
