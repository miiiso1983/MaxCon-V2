// Fix Sidebar Settings - Add settings menu dynamically
// This script adds the settings menu to the sidebar if it doesn't exist

(function() {
    'use strict';
    
    console.log('🔧 تشغيل إصلاح قسم الإعدادات...');
    
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
        const sidebar = document.querySelector('.sidebar ul') || document.querySelector('nav ul') || document.querySelector('.menu');
        
        if (!sidebar) {
            console.log('❌ لم يتم العثور على القائمة الجانبية');
            return;
        }
        
        // Find and remove existing broken settings menu
        const existingSettings = sidebar.querySelectorAll('a');
        existingSettings.forEach(link => {
            if (link.textContent.includes('الإعدادات') || link.href.includes('#')) {
                const parentLi = link.closest('li');
                if (parentLi && link.textContent.includes('الإعدادات')) {
                    console.log('🗑️ إزالة قسم الإعدادات المعطل...');
                    parentLi.remove();
                }
            }
        });
        
        // Create settings menu HTML
        const settingsMenuHTML = `
            <li class="menu-section" style="margin: 10px 0;">
                <a href="#" class="menu-toggle" onclick="toggleSettingsSubmenu(this)" style="display: flex; align-items: center; padding: 12px 15px; color: #4a5568; text-decoration: none; border-radius: 8px; transition: all 0.3s ease;">
                    <i class="fas fa-cog" style="margin-left: 12px; font-size: 16px;"></i>
                    <span style="flex: 1;">الإعدادات</span>
                    <i class="fas fa-chevron-down toggle-icon" style="font-size: 12px; transition: transform 0.3s ease;"></i>
                </a>
                <ul class="submenu" style="display: none; padding-right: 20px; margin-top: 5px;">
                    <li style="margin: 5px 0;">
                        <a href="/settings-company.php" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 8px 12px; color: #718096; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease;">
                            <i class="fas fa-building" style="margin-left: 10px; font-size: 14px;"></i>
                            إعدادات الشركة
                        </a>
                    </li>
                    <li style="margin: 5px 0;">
                        <a href="/settings-users.php" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 8px 12px; color: #718096; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease;">
                            <i class="fas fa-users-cog" style="margin-left: 10px; font-size: 14px;"></i>
                            إدارة المستخدمين
                        </a>
                    </li>
                    <li style="margin: 5px 0;">
                        <a href="/settings-security.php" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 8px 12px; color: #718096; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease;">
                            <i class="fas fa-shield-alt" style="margin-left: 10px; font-size: 14px;"></i>
                            الأمان والخصوصية
                        </a>
                    </li>
                    <li style="margin: 5px 0;">
                        <a href="/settings-backup.php" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 8px 12px; color: #718096; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease;">
                            <i class="fas fa-database" style="margin-left: 10px; font-size: 14px;"></i>
                            النسخ الاحتياطية
                        </a>
                    </li>
                    <li style="margin: 5px 0;">
                        <a href="/settings-system.php" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 8px 12px; color: #718096; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease;">
                            <i class="fas fa-server" style="margin-left: 10px; font-size: 14px;"></i>
                            إعدادات النظام
                        </a>
                    </li>
                    <li style="margin: 5px 0;">
                        <a href="/tenant-guide-direct.php" class="submenu-item" target="_blank" style="display: flex; align-items: center; padding: 8px 12px; color: #718096; text-decoration: none; border-radius: 6px; font-size: 14px; transition: all 0.3s ease;">
                            <i class="fas fa-rocket" style="margin-left: 10px; font-size: 14px;"></i>
                            دليل المستأجر الجديد
                        </a>
                    </li>
                </ul>
            </li>
        `;
        
        // Find a good place to insert the settings menu (before the last item)
        const lastItem = sidebar.lastElementChild;
        if (lastItem) {
            lastItem.insertAdjacentHTML('beforebegin', settingsMenuHTML);
        } else {
            sidebar.insertAdjacentHTML('beforeend', settingsMenuHTML);
        }
        
        // Add hover effects
        const menuItems = sidebar.querySelectorAll('.menu-toggle, .submenu-item');
        menuItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f7fafc';
                this.style.color = '#2d3748';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
                this.style.color = '';
            });
        });
        
        console.log('✅ تم إضافة قسم الإعدادات بنجاح!');
        
        // Show success message
        showSuccessMessage();
    });
    
    // Toggle submenu function
    window.toggleSettingsSubmenu = function(element) {
        const submenu = element.nextElementSibling;
        const icon = element.querySelector('.toggle-icon');
        
        if (submenu.style.display === 'none' || submenu.style.display === '') {
            submenu.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
        } else {
            submenu.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
        }
    };
    
    // Show success message
    function showSuccessMessage() {
        // Create success notification
        const notification = document.createElement('div');
        notification.innerHTML = `
            <div style="position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 15px 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); z-index: 10000; font-family: 'Cairo', sans-serif; font-weight: 600; max-width: 300px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-check-circle" style="font-size: 18px;"></i>
                    <div>
                        <div style="font-size: 14px; margin-bottom: 5px;">تم إضافة قسم الإعدادات!</div>
                        <div style="font-size: 12px; opacity: 0.9;">ابحث عن "الإعدادات" في القائمة الجانبية</div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
        
        // Add click to close
        notification.addEventListener('click', () => {
            notification.remove();
        });
    }
    
    // Add CSS for better styling
    const style = document.createElement('style');
    style.textContent = `
        .menu-section .submenu-item:hover {
            background-color: #667eea !important;
            color: white !important;
        }
        
        .menu-toggle:hover {
            background-color: #667eea !important;
            color: white !important;
        }
        
        .toggle-icon {
            transition: transform 0.3s ease !important;
        }
        
        .submenu {
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
    
})();
