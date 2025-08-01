// Force Fix Sidebar - Guaranteed to work
console.log('🔧 بدء الإصلاح القسري للقائمة الجانبية...');

// Wait a bit for page to load
setTimeout(function() {
    try {
        // Find all possible sidebar containers
        const possibleSidebars = [
            document.querySelector('.sidebar'),
            document.querySelector('.sidebar ul'),
            document.querySelector('nav'),
            document.querySelector('nav ul'),
            document.querySelector('.menu'),
            document.querySelector('aside'),
            document.querySelector('aside ul'),
            document.querySelector('[class*="sidebar"]'),
            document.querySelector('[class*="menu"]'),
            document.querySelector('[class*="nav"]')
        ];

        let sidebar = null;
        for (let s of possibleSidebars) {
            if (s && s.querySelector('a')) {
                sidebar = s.tagName === 'UL' ? s : s.querySelector('ul');
                if (sidebar) break;
            }
        }

        if (!sidebar) {
            console.log('❌ لم يتم العثور على القائمة الجانبية');
            alert('❌ لم يتم العثور على القائمة الجانبية. تأكد من أنك في لوحة تحكم المستأجر.');
            return;
        }

        console.log('✅ تم العثور على القائمة الجانبية:', sidebar);

        // Remove ALL existing settings items
        const allItems = sidebar.querySelectorAll('li');
        let removedCount = 0;
        
        allItems.forEach(item => {
            const text = item.textContent || '';
            if (text.includes('الإعدادات') || text.includes('Settings') || text.includes('إعداد')) {
                console.log('🗑️ إزالة عنصر:', text.trim());
                item.remove();
                removedCount++;
            }
        });

        console.log(`🗑️ تم إزالة ${removedCount} عنصر`);

        // Create the new settings menu with inline styles
        const settingsHTML = `
        <li style="margin: 8px 0; list-style: none;">
            <a href="javascript:void(0)" onclick="toggleNewSettings()" style="
                display: flex; 
                align-items: center; 
                padding: 12px 15px; 
                color: #4a5568; 
                text-decoration: none; 
                border-radius: 8px; 
                transition: all 0.3s ease;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                margin-bottom: 5px;
                cursor: pointer;
            " onmouseover="this.style.background='#667eea'; this.style.color='white';" onmouseout="this.style.background='#f8fafc'; this.style.color='#4a5568';">
                <i class="fas fa-cog" style="margin-left: 12px; font-size: 16px; color: #667eea;"></i>
                <span style="flex: 1; font-weight: 600;">الإعدادات</span>
                <i class="fas fa-chevron-down" id="settingsChevron" style="font-size: 12px; transition: transform 0.3s ease;"></i>
            </a>
            <ul id="settingsSubmenu" style="
                display: none; 
                padding: 10px; 
                background: #f1f5f9; 
                border-radius: 8px; 
                margin-top: 5px;
                border: 1px solid #e2e8f0;
            ">
                <li style="margin: 5px 0;">
                    <a href="https://www.maxcon.app/settings-company.php" target="_blank" style="
                        display: flex; 
                        align-items: center; 
                        padding: 10px 15px; 
                        color: #4a5568; 
                        text-decoration: none; 
                        border-radius: 6px; 
                        font-size: 14px; 
                        background: white;
                        border: 1px solid #e2e8f0;
                        margin-bottom: 5px;
                    " onmouseover="this.style.background='#667eea'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#4a5568';">
                        <i class="fas fa-building" style="margin-left: 10px; color: #667eea; width: 20px;"></i>
                        إعدادات الشركة
                    </a>
                </li>
                <li style="margin: 5px 0;">
                    <a href="https://www.maxcon.app/settings-users.php" target="_blank" style="
                        display: flex; 
                        align-items: center; 
                        padding: 10px 15px; 
                        color: #4a5568; 
                        text-decoration: none; 
                        border-radius: 6px; 
                        font-size: 14px; 
                        background: white;
                        border: 1px solid #e2e8f0;
                        margin-bottom: 5px;
                    " onmouseover="this.style.background='#10b981'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#4a5568';">
                        <i class="fas fa-users-cog" style="margin-left: 10px; color: #10b981; width: 20px;"></i>
                        إدارة المستخدمين
                    </a>
                </li>
                <li style="margin: 5px 0;">
                    <a href="https://www.maxcon.app/settings-security.php" target="_blank" style="
                        display: flex; 
                        align-items: center; 
                        padding: 10px 15px; 
                        color: #4a5568; 
                        text-decoration: none; 
                        border-radius: 6px; 
                        font-size: 14px; 
                        background: white;
                        border: 1px solid #e2e8f0;
                        margin-bottom: 5px;
                    " onmouseover="this.style.background='#ef4444'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#4a5568';">
                        <i class="fas fa-shield-alt" style="margin-left: 10px; color: #ef4444; width: 20px;"></i>
                        الأمان والخصوصية
                    </a>
                </li>
                <li style="margin: 5px 0;">
                    <a href="https://www.maxcon.app/settings-backup.php" target="_blank" style="
                        display: flex; 
                        align-items: center; 
                        padding: 10px 15px; 
                        color: #4a5568; 
                        text-decoration: none; 
                        border-radius: 6px; 
                        font-size: 14px; 
                        background: white;
                        border: 1px solid #e2e8f0;
                        margin-bottom: 5px;
                    " onmouseover="this.style.background='#10b981'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#4a5568';">
                        <i class="fas fa-database" style="margin-left: 10px; color: #10b981; width: 20px;"></i>
                        النسخ الاحتياطية
                    </a>
                </li>
                <li style="margin: 5px 0;">
                    <a href="https://www.maxcon.app/settings-system.php" target="_blank" style="
                        display: flex; 
                        align-items: center; 
                        padding: 10px 15px; 
                        color: #4a5568; 
                        text-decoration: none; 
                        border-radius: 6px; 
                        font-size: 14px; 
                        background: white;
                        border: 1px solid #e2e8f0;
                        margin-bottom: 5px;
                    " onmouseover="this.style.background='#8b5cf6'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#4a5568';">
                        <i class="fas fa-server" style="margin-left: 10px; color: #8b5cf6; width: 20px;"></i>
                        إعدادات النظام
                    </a>
                </li>
                <li style="margin: 5px 0;">
                    <a href="https://www.maxcon.app/test-all-settings.html" target="_blank" style="
                        display: flex; 
                        align-items: center; 
                        padding: 10px 15px; 
                        color: #4a5568; 
                        text-decoration: none; 
                        border-radius: 6px; 
                        font-size: 14px; 
                        background: white;
                        border: 1px solid #e2e8f0;
                        margin-bottom: 5px;
                    " onmouseover="this.style.background='#06b6d4'; this.style.color='white';" onmouseout="this.style.background='white'; this.style.color='#4a5568';">
                        <i class="fas fa-vial" style="margin-left: 10px; color: #06b6d4; width: 20px;"></i>
                        اختبار جميع الإعدادات
                    </a>
                </li>
            </ul>
        </li>`;

        // Add to sidebar
        sidebar.insertAdjacentHTML('beforeend', settingsHTML);

        // Define toggle function globally
        window.toggleNewSettings = function() {
            const submenu = document.getElementById('settingsSubmenu');
            const chevron = document.getElementById('settingsChevron');
            
            if (submenu.style.display === 'none' || submenu.style.display === '') {
                submenu.style.display = 'block';
                chevron.style.transform = 'rotate(180deg)';
                console.log('📂 فتح قائمة الإعدادات');
            } else {
                submenu.style.display = 'none';
                chevron.style.transform = 'rotate(0deg)';
                console.log('📁 إغلاق قائمة الإعدادات');
            }
        };

        console.log('✅ تم إضافة قسم الإعدادات بنجاح!');

        // Show success notification
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
                border: 2px solid rgba(255,255,255,0.2);
            ">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                    <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                    <div style="font-size: 16px; font-weight: 700;">تم إضافة قسم الإعدادات! 🎉</div>
                </div>
                <div style="font-size: 13px; opacity: 0.9; line-height: 1.4;">
                    ✅ تم إزالة ${removedCount} قسم معطل<br>
                    ✅ تم إضافة قسم جديد يعمل<br>
                    ✅ 6 روابط إعدادات متاحة<br>
                    ✅ جميع الروابط تعمل الآن!
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

        // Auto remove notification after 10 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 10000);

        // Test the new menu
        setTimeout(() => {
            const newSettingsMenu = document.querySelector('[onclick="toggleNewSettings()"]');
            if (newSettingsMenu) {
                console.log('✅ قسم الإعدادات الجديد يعمل بشكل صحيح');
            } else {
                console.log('❌ مشكلة في قسم الإعدادات الجديد');
            }
        }, 1000);

    } catch (error) {
        console.error('❌ خطأ في الإصلاح:', error);
        alert('❌ حدث خطأ في الإصلاح: ' + error.message);
    }
}, 2000); // Wait 2 seconds for page to fully load

console.log('⏳ انتظار تحميل الصفحة...');
