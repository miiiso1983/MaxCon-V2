// Force Hide Sections - Guaranteed to hide settings and reports sections
console.log('🔒 بدء الإخفاء القسري للأقسام...');

// Execute immediately and repeatedly
function forceHideSections() {
    try {
        console.log('🔍 البحث عن الأقسام للإخفاء...');
        
        let hiddenCount = 0;
        
        // Find ALL elements that might contain settings or reports
        const allElements = document.querySelectorAll('*');
        
        allElements.forEach(element => {
            const text = element.textContent ? element.textContent.trim() : '';
            const innerHTML = element.innerHTML || '';
            
            // Check for settings or reports content
            if (text.includes('الإعدادات') || text.includes('Settings') || 
                text.includes('التقارير') || text.includes('الإحصائيات') || 
                text.includes('Reports') || text.includes('نظام التقارير') ||
                innerHTML.includes('fa-cog') || innerHTML.includes('fa-chart') ||
                innerHTML.includes('fa-settings') || innerHTML.includes('fa-gear')) {
                
                // Check if this is a menu item (not just text inside content)
                const isMenuItem = element.tagName === 'LI' || 
                                 element.tagName === 'A' ||
                                 element.closest('nav') ||
                                 element.closest('.sidebar') ||
                                 element.closest('.menu') ||
                                 element.closest('ul') ||
                                 element.classList.contains('menu-item') ||
                                 element.classList.contains('nav-item') ||
                                 element.parentElement?.tagName === 'LI';
                
                if (isMenuItem && text.length < 100) { // Avoid hiding large content areas
                    // Find the appropriate parent to hide
                    let targetElement = element;
                    
                    // If it's a link, hide the parent li
                    if (element.tagName === 'A') {
                        targetElement = element.closest('li') || element;
                    }
                    
                    // If it's already an li, use it
                    if (element.tagName === 'LI') {
                        targetElement = element;
                    }
                    
                    // Check if not already hidden
                    if (targetElement.style.display !== 'none' && 
                        !targetElement.classList.contains('force-hidden')) {
                        
                        console.log('🔒 إخفاء عنصر:', text.substring(0, 50));
                        
                        // Mark as hidden to avoid duplicate processing
                        targetElement.classList.add('force-hidden');
                        
                        // Hide with animation
                        targetElement.style.transition = 'all 0.5s ease';
                        targetElement.style.opacity = '0';
                        targetElement.style.transform = 'translateX(-50px)';
                        targetElement.style.maxHeight = '0';
                        targetElement.style.overflow = 'hidden';
                        targetElement.style.margin = '0';
                        targetElement.style.padding = '0';
                        
                        // Complete hide after animation
                        setTimeout(() => {
                            targetElement.style.display = 'none';
                        }, 500);
                        
                        hiddenCount++;
                    }
                }
            }
        });

        // Also hide by specific selectors
        const specificSelectors = [
            'a[href*="settings"]',
            'a[href*="reports"]', 
            'a[href="#"]',
            '.menu-item:has(.fa-cog)',
            '.menu-item:has(.fa-chart)',
            'li:has(a[href="#"])',
            '[class*="settings"]',
            '[class*="reports"]',
            '[id*="settings"]',
            '[id*="reports"]'
        ];

        specificSelectors.forEach(selector => {
            try {
                const elements = document.querySelectorAll(selector);
                elements.forEach(element => {
                    const text = element.textContent ? element.textContent.trim() : '';
                    if ((text.includes('الإعدادات') || text.includes('التقارير') || 
                         text.includes('Settings') || text.includes('Reports')) &&
                        !element.classList.contains('force-hidden')) {
                        
                        console.log('🎯 إخفاء بالمحدد:', selector, text.substring(0, 30));
                        
                        element.classList.add('force-hidden');
                        element.style.display = 'none';
                        hiddenCount++;
                    }
                });
            } catch (e) {
                // Ignore selector errors
            }
        });

        // Remove any dynamically added menus
        const dynamicMenus = document.querySelectorAll(
            '.settings-submenu-universal, .reports-submenu-universal, ' +
            '.settings-menu-fixed, [style*="position: fixed"]'
        );
        
        dynamicMenus.forEach(menu => {
            const text = menu.textContent ? menu.textContent.trim() : '';
            if (text.includes('الإعدادات') || text.includes('التقارير') || 
                text.includes('Settings') || text.includes('Reports') ||
                text.includes('قائمة سريعة') || text.includes('إصلاح')) {
                console.log('🗑️ إزالة قائمة ديناميكية');
                menu.remove();
                hiddenCount++;
            }
        });

        console.log(`🔒 تم إخفاء ${hiddenCount} عنصر في هذه المحاولة`);
        return hiddenCount;

    } catch (error) {
        console.error('❌ خطأ في الإخفاء:', error);
        return 0;
    }
}

// Run immediately
let totalHidden = forceHideSections();

// Run again after 1 second
setTimeout(() => {
    totalHidden += forceHideSections();
}, 1000);

// Run again after 3 seconds
setTimeout(() => {
    totalHidden += forceHideSections();
}, 3000);

// Run again after 5 seconds
setTimeout(() => {
    totalHidden += forceHideSections();
    
    // Show final result
    if (totalHidden > 0) {
        showHideConfirmation(totalHidden);
    } else {
        console.log('ℹ️ لم يتم العثور على أقسام للإخفاء');
        showNoSectionsFound();
    }
}, 5000);

// Monitor for new elements and hide them immediately
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        mutation.addedNodes.forEach(function(node) {
            if (node.nodeType === 1) { // Element node
                const text = node.textContent ? node.textContent.trim() : '';
                if (text.includes('الإعدادات') || text.includes('التقارير') || 
                    text.includes('Settings') || text.includes('Reports')) {
                    console.log('🚫 منع إضافة عنصر جديد:', text.substring(0, 30));
                    node.style.display = 'none';
                    node.classList.add('force-hidden');
                }
            }
        });
    });
});

// Start monitoring
observer.observe(document.body, {
    childList: true,
    subtree: true
});

// Override functions that might add menus
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

// Show confirmation message
function showHideConfirmation(hiddenCount) {
    // Remove any existing notifications first
    const existingNotifications = document.querySelectorAll('[style*="position: fixed"]');
    existingNotifications.forEach(notification => {
        const text = notification.textContent || '';
        if (text.includes('تم إخفاء') || text.includes('إصلاح') || text.includes('إضافة')) {
            notification.remove();
        }
    });

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
                ✅ تم إخفاء ${hiddenCount} عنصر<br>
                🔒 قسم الإعدادات مخفي<br>
                📊 قسم التقارير مخفي<br>
                🛡️ القائمة الجانبية نظيفة الآن<br>
                🚫 منع إضافة أقسام جديدة
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

    // Auto remove after 8 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 8000);
}

// Show message when no sections found
function showNoSectionsFound() {
    const notification = document.createElement('div');
    notification.innerHTML = `
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 20px 25px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(16, 185, 129, 0.4);
            z-index: 10000;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            max-width: 350px;
        ">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                <div style="font-size: 16px; font-weight: 700;">القائمة نظيفة! ✅</div>
            </div>
            <div style="font-size: 13px; opacity: 0.9; line-height: 1.4;">
                🔍 لم يتم العثور على أقسام للإخفاء<br>
                ✨ القائمة الجانبية نظيفة بالفعل<br>
                🛡️ الحماية مفعلة ضد الإضافات الجديدة
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

    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

console.log('🛡️ تم تفعيل الإخفاء القسري والحماية المستمرة');
