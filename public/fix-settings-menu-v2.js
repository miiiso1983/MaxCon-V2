// Fix Settings Menu V2 - Enhanced version to handle broken settings menu
// This script removes broken settings menu and adds working one

(function() {
    'use strict';
    
    console.log('🔧 تشغيل إصلاح قسم الإعدادات المحسن...');
    
    // Wait for DOM to be ready
    function ready(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }
    
    ready(function() {
        // Check if we're on the tenant dashboard
        if (!window.location.href.includes('maxcon.app') || window.location.href.includes('/admin/')) {
            console.log('❌ ليس في لوحة تحكم المستأجر');
            return;
        }
        
        // Find the sidebar
        const sidebar = document.querySelector('.sidebar ul') || 
                      document.querySelector('nav ul') || 
                      document.querySelector('.menu') ||
                      document.querySelector('ul');
        
        if (!sidebar) {
            console.log('❌ لم يتم العثور على القائمة الجانبية');
            return;
        }
        
        console.log('✅ تم العثور على القائمة الجانبية');
        
        // Remove ALL existing settings menus (broken or working)
        const allLinks = sidebar.querySelectorAll('a');
        let removedCount = 0;
        
        allLinks.forEach(link => {
            const text = link.textContent.trim();
            if (text.includes('الإعدادات') || text.includes('Settings')) {
                const parentLi = link.closest('li');
                if (parentLi) {
                    console.log('🗑️ إزالة قسم إعدادات موجود:', text);
                    parentLi.remove();
                    removedCount++;
                }
            }
        });
        
        console.log(`🗑️ تم إزالة ${removedCount} قسم إعدادات`);
        
        // Create new working settings menu
        const settingsMenuHTML = `
            <li class="menu-section settings-menu-fixed" style="margin: 10px 0; position: relative;">
                <a href="javascript:void(0)" class="menu-toggle settings-toggle" onclick="toggleSettingsMenu()" style="display: flex; align-items: center; padding: 12px 15px; color: #4a5568; text-decoration: none; border-radius: 8px; transition: all 0.3s ease; cursor: pointer;">
                    <i class="fas fa-cog" style="margin-left: 12px; font-size: 16px; color: #667eea;"></i>
                    <span style="flex: 1; font-weight: 600;">الإعدادات</span>
                    <i class="fas fa-chevron-down settings-toggle-icon" style="font-size: 12px; transition: transform 0.3s ease; color: #718096;"></i>
                </a>
                <ul class="submenu settings-submenu" style="display: none; padding-right: 20px; margin-top: 8px; background: #f8fafc; border-radius: 8px; padding: 10px;">
                    <li style="margin: 8px 0;">
                        <a href="https://www.maxcon.app/settings-company.php" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 10px 15px; color: #4a5568; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease; background: white; margin-bottom: 5px;">
                            <i class="fas fa-building" style="margin-left: 10px; font-size: 14px; color: #667eea; width: 20px;"></i>
                            <span>إعدادات الشركة</span>
                        </a>
                    </li>
                    <li style="margin: 8px 0;">
                        <a href="https://www.maxcon.app/settings-users.php" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 10px 15px; color: #4a5568; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease; background: white; margin-bottom: 5px;">
                            <i class="fas fa-users-cog" style="margin-left: 10px; font-size: 14px; color: #10b981; width: 20px;"></i>
                            <span>إدارة المستخدمين</span>
                        </a>
                    </li>
                    <li style="margin: 8px 0;">
                        <a href="https://www.maxcon.app/settings-security.php" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 10px 15px; color: #4a5568; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease; background: white; margin-bottom: 5px;">
                            <i class="fas fa-shield-alt" style="margin-left: 10px; font-size: 14px; color: #ef4444; width: 20px;"></i>
                            <span>الأمان والخصوصية</span>
                        </a>
                    </li>
                    <li style="margin: 8px 0;">
                        <a href="https://www.maxcon.app/settings-backup.php" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 10px 15px; color: #4a5568; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease; background: white; margin-bottom: 5px;">
                            <i class="fas fa-database" style="margin-left: 10px; font-size: 14px; color: #10b981; width: 20px;"></i>
                            <span>النسخ الاحتياطية</span>
                        </a>
                    </li>
                    <li style="margin: 8px 0;">
                        <a href="https://www.maxcon.app/settings-system.php" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 10px 15px; color: #4a5568; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease; background: white; margin-bottom: 5px;">
                            <i class="fas fa-server" style="margin-left: 10px; font-size: 14px; color: #8b5cf6; width: 20px;"></i>
                            <span>إعدادات النظام</span>
                        </a>
                    </li>
                    <li style="margin: 8px 0;">
                        <a href="https://www.maxcon.app/tenant-guide-direct.php" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 10px 15px; color: #4a5568; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease; background: white; margin-bottom: 5px;">
                            <i class="fas fa-rocket" style="margin-left: 10px; font-size: 14px; color: #f59e0b; width: 20px;"></i>
                            <span>دليل المستأجر الجديد</span>
                        </a>
                    </li>
                    <li style="margin: 8px 0;">
                        <a href="https://www.maxcon.app/test-all-settings.html" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 10px 15px; color: #4a5568; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease; background: white; margin-bottom: 5px;">
                            <i class="fas fa-vial" style="margin-left: 10px; font-size: 14px; color: #06b6d4; width: 20px;"></i>
                            <span>اختبار جميع الإعدادات</span>
                        </a>
                    </li>
                </ul>
            </li>
        `;
        
        // Find a good place to insert (before last item or at end)
        const lastItem = sidebar.lastElementChild;
        if (lastItem) {
            lastItem.insertAdjacentHTML('beforebegin', settingsMenuHTML);
        } else {
            sidebar.insertAdjacentHTML('beforeend', settingsMenuHTML);
        }
        
        console.log('✅ تم إضافة قسم الإعدادات الجديد!');
        
        // Add enhanced hover effects
        setTimeout(() => {
            const menuItems = document.querySelectorAll('.settings-submenu .submenu-item');
            menuItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#667eea';
                    this.style.color = 'white';
                    this.style.transform = 'translateX(-5px)';
                    this.style.boxShadow = '0 4px 15px rgba(102, 126, 234, 0.3)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = 'white';
                    this.style.color = '#4a5568';
                    this.style.transform = 'translateX(0)';
                    this.style.boxShadow = 'none';
                });
            });
            
            // Main toggle hover
            const mainToggle = document.querySelector('.settings-toggle');
            if (mainToggle) {
                mainToggle.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#667eea';
                    this.style.color = 'white';
                    this.style.transform = 'translateX(-3px)';
                });
                
                mainToggle.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                    this.style.color = '#4a5568';
                    this.style.transform = 'translateX(0)';
                });
            }
        }, 100);
        
        // Show success message
        showEnhancedSuccessMessage();
    });
    
    // Enhanced toggle function
    window.toggleSettingsMenu = function() {
        const submenu = document.querySelector('.settings-submenu');
        const icon = document.querySelector('.settings-toggle-icon');
        
        if (!submenu || !icon) return;
        
        if (submenu.style.display === 'none' || submenu.style.display === '') {
            submenu.style.display = 'block';
            submenu.style.animation = 'slideDown 0.3s ease';
            icon.style.transform = 'rotate(180deg)';
            console.log('📂 فتح قائمة الإعدادات');
        } else {
            submenu.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
            console.log('📁 إغلاق قائمة الإعدادات');
        }
    };
    
    // Enhanced success message
    function showEnhancedSuccessMessage() {
        // Remove any existing notifications
        const existingNotifications = document.querySelectorAll('.settings-fix-notification');
        existingNotifications.forEach(n => n.remove());
        
        // Create enhanced success notification
        const notification = document.createElement('div');
        notification.className = 'settings-fix-notification';
        notification.innerHTML = `
            <div style="position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 20px 25px; border-radius: 15px; box-shadow: 0 8px 30px rgba(16, 185, 129, 0.4); z-index: 10000; font-family: 'Cairo', sans-serif; font-weight: 600; max-width: 350px; border: 2px solid rgba(255,255,255,0.2);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                    <i class="fas fa-check-circle" style="font-size: 24px; color: #ffffff;"></i>
                    <div>
                        <div style="font-size: 16px; font-weight: 700;">تم إصلاح قسم الإعدادات! ✨</div>
                    </div>
                </div>
                <div style="font-size: 13px; opacity: 0.9; line-height: 1.4;">
                    • تم إزالة القسم المعطل<br>
                    • تم إضافة قسم جديد يعمل بشكل صحيح<br>
                    • ابحث عن "الإعدادات" في القائمة الجانبية<br>
                    • جميع الروابط تعمل الآن! 🎯
                </div>
                <div style="margin-top: 12px; text-align: center;">
                    <button onclick="this.closest('.settings-fix-notification').remove()" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">
                        إغلاق
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 8 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 8000);
        
        // Add click to close
        notification.addEventListener('click', (e) => {
            if (e.target.tagName !== 'BUTTON') {
                notification.remove();
            }
        });
    }
    
    // Add enhanced CSS
    const style = document.createElement('style');
    style.textContent = `
        .settings-menu-fixed .submenu-item {
            transition: all 0.3s ease !important;
        }
        
        .settings-menu-fixed .submenu-item:hover {
            background-color: #667eea !important;
            color: white !important;
            transform: translateX(-5px) !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
        }
        
        .settings-toggle:hover {
            background-color: #667eea !important;
            color: white !important;
            transform: translateX(-3px) !important;
        }
        
        .settings-toggle-icon {
            transition: transform 0.3s ease !important;
        }
        
        .settings-submenu {
            animation: slideDown 0.3s ease !important;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
                max-height: 0;
            }
            to {
                opacity: 1;
                transform: translateY(0);
                max-height: 500px;
            }
        }
        
        .settings-fix-notification {
            animation: slideInRight 0.5s ease !important;
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    `;
    document.head.appendChild(style);
    
    console.log('🎉 إصلاح قسم الإعدادات مكتمل!');
    
})();
