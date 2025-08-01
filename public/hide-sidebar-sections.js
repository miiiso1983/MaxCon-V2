// Hide Sidebar Sections - Remove Settings and Reports sections from tenant sidebar
console.log('🔒 بدء إخفاء أقسام الإعدادات والتقارير من القائمة الجانبية...');

// Wait for page to load
setTimeout(function() {
    try {
        console.log('🔍 البحث عن أقسام الإعدادات والتقارير لإخفائها...');
        
        // Find all links and menu items
        const allElements = document.querySelectorAll('a, li, div');
        let hiddenCount = 0;
        
        allElements.forEach(element => {
            const text = element.textContent ? element.textContent.trim() : '';
            
            // Check if element contains settings or reports text
            if (text.includes('الإعدادات') || text.includes('Settings') || 
                text.includes('التقارير') || text.includes('الإحصائيات') || 
                text.includes('Reports') || text.includes('نظام التقارير') ||
                text.includes('تقارير المبيعات') || text.includes('التقارير المالية') ||
                text.includes('منشئ التقارير') || text.includes('سجل التقارير')) {
                
                // Find the parent menu item (li element)
                let parentLi = element.closest('li');
                
                // If it's already an li element, use it
                if (element.tagName === 'LI') {
                    parentLi = element;
                }
                
                if (parentLi) {
                    // Check if this is a main menu item (not a submenu item)
                    const isMainMenuItem = parentLi.classList.contains('menu-section') || 
                                         parentLi.classList.contains('has-submenu') ||
                                         parentLi.querySelector('.menu-toggle') ||
                                         (text.includes('الإعدادات') && !parentLi.closest('.submenu')) ||
                                         (text.includes('التقارير') && !parentLi.closest('.submenu'));
                    
                    if (isMainMenuItem) {
                        console.log('🔒 إخفاء قسم:', text);
                        
                        // Hide the element with animation
                        parentLi.style.transition = 'all 0.5s ease';
                        parentLi.style.opacity = '0';
                        parentLi.style.transform = 'translateX(-20px)';
                        parentLi.style.maxHeight = '0';
                        parentLi.style.overflow = 'hidden';
                        parentLi.style.marginTop = '0';
                        parentLi.style.marginBottom = '0';
                        parentLi.style.paddingTop = '0';
                        parentLi.style.paddingBottom = '0';
                        
                        // After animation, completely hide the element
                        setTimeout(() => {
                            parentLi.style.display = 'none';
                        }, 500);
                        
                        hiddenCount++;
                    }
                }
            }
        });

        // Also remove any dynamically added settings/reports menus from previous scripts
        const dynamicMenus = document.querySelectorAll(
            '.settings-submenu-universal, .reports-submenu-universal, ' +
            '.settings-menu-fixed, [id*="settings"], [id*="reports"], ' +
            '[class*="settings"], [class*="reports"]'
        );
        
        dynamicMenus.forEach(menu => {
            const text = menu.textContent ? menu.textContent.trim() : '';
            if (text.includes('الإعدادات') || text.includes('التقارير') || 
                text.includes('Settings') || text.includes('Reports')) {
                console.log('🗑️ إزالة قائمة ديناميكية:', text.substring(0, 50) + '...');
                menu.remove();
                hiddenCount++;
            }
        });

        // Remove any floating sidebars that might contain settings
        const floatingSidebars = document.querySelectorAll('[style*="position: fixed"]');
        floatingSidebars.forEach(sidebar => {
            const text = sidebar.textContent ? sidebar.textContent.trim() : '';
            if (text.includes('الإعدادات') || text.includes('التقارير') || 
                text.includes('قائمة سريعة')) {
                console.log('🗑️ إزالة قائمة عائمة:', text.substring(0, 30) + '...');
                sidebar.remove();
                hiddenCount++;
            }
        });

        // Remove any notification about adding settings
        const notifications = document.querySelectorAll('[style*="position: fixed"]');
        notifications.forEach(notification => {
            const text = notification.textContent ? notification.textContent.trim() : '';
            if (text.includes('تم إضافة قسم الإعدادات') || 
                text.includes('تم الإصلاح') || 
                text.includes('قسم الإعدادات')) {
                console.log('🗑️ إزالة إشعار:', text.substring(0, 30) + '...');
                notification.remove();
            }
        });

        console.log(`🔒 تم إخفاء ${hiddenCount} قسم من القائمة الجانبية`);

        // Show confirmation message
        if (hiddenCount > 0) {
            showHideConfirmation(hiddenCount);
        } else {
            console.log('ℹ️ لم يتم العثور على أقسام للإخفاء');
        }

        // Override any future attempts to add settings/reports
        overrideFutureFixes();

    } catch (error) {
        console.error('❌ خطأ في إخفاء الأقسام:', error);
    }
}, 2000); // Wait 2 seconds for page load

// Function to show hide confirmation
function showHideConfirmation(hiddenCount) {
    const notification = document.createElement('div');
    notification.innerHTML = `
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 20px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(239, 68, 68, 0.4);
            z-index: 10000;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            max-width: 350px;
            border: 2px solid rgba(255,255,255,0.2);
        ">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                <i class="fas fa-eye-slash" style="font-size: 24px;"></i>
                <div style="font-size: 16px; font-weight: 700;">تم إخفاء الأقسام! 🔒</div>
            </div>
            <div style="font-size: 13px; opacity: 0.9; line-height: 1.4;">
                ✅ تم إخفاء ${hiddenCount} قسم<br>
                🔒 قسم الإعدادات مخفي<br>
                📊 قسم التقارير مخفي<br>
                🛡️ القائمة الجانبية نظيفة الآن
            </div>
            <button onclick="this.closest('div').parentNode.remove()" style="
                background: rgba(255,255,255,0.2);
                border: 1px solid rgba(255,255,255,0.3);
                color: white;
                padding: 8px 15px;
                border-radius: 6px;
                font-size: 12px;
                cursor: pointer;
                margin-top: 10px;
                width: 100%;
            ">إغلاق</button>
        </div>
    `;
    
    document.body.appendChild(notification);

    // Auto remove after 6 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 6000);
}

// Override future attempts to add settings/reports
function overrideFutureFixes() {
    // Override common functions that might add settings
    window.toggleSettingsMenu = function() {
        console.log('🔒 محاولة فتح قائمة الإعدادات - مرفوضة');
        alert('🔒 قسم الإعدادات غير متاح للمستأجرين');
    };
    
    window.toggleReportsMenu = function() {
        console.log('🔒 محاولة فتح قائمة التقارير - مرفوضة');
        alert('📊 قسم التقارير غير متاح حالياً');
    };
    
    window.toggleNewSettings = function() {
        console.log('🔒 محاولة فتح إعدادات جديدة - مرفوضة');
        alert('🔒 الإعدادات غير متاحة للمستأجرين');
    };

    // Monitor for new elements being added
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Element node
                    const text = node.textContent ? node.textContent.trim() : '';
                    if (text.includes('الإعدادات') || text.includes('التقارير') || 
                        text.includes('Settings') || text.includes('Reports')) {
                        console.log('🔒 منع إضافة عنصر جديد:', text.substring(0, 30) + '...');
                        node.remove();
                    }
                }
            });
        });
    });

    // Start observing
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    console.log('🛡️ تم تفعيل الحماية ضد إضافة أقسام الإعدادات والتقارير');
}

console.log('⏳ انتظار تحميل الصفحة لإخفاء الأقسام...');
