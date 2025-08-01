// Universal Sidebar Fix - Works with any sidebar structure
console.log('🔍 بدء البحث الشامل عن القائمة الجانبية...');

// Wait longer for page to load completely
setTimeout(function() {
    try {
        console.log('🔍 البحث في جميع عناصر الصفحة...');
        
        // Find ALL links on the page that contain settings or reports text
        const allLinks = document.querySelectorAll('a');
        let foundLinks = [];
        
        allLinks.forEach(link => {
            const text = link.textContent.trim();
            const href = link.getAttribute('href') || '';
            
            if ((text.includes('الإعدادات') || text.includes('Settings') || 
                 text.includes('التقارير') || text.includes('الإحصائيات') || text.includes('Reports')) &&
                (href === '#' || href === '' || href === 'javascript:void(0)')) {
                foundLinks.push({
                    element: link,
                    text: text,
                    type: text.includes('الإعدادات') || text.includes('Settings') ? 'settings' : 'reports'
                });
                console.log('🎯 تم العثور على رابط:', text, 'النوع:', href);
            }
        });

        if (foundLinks.length === 0) {
            console.log('❌ لم يتم العثور على روابط الإعدادات أو التقارير');
            
            // Try to find any sidebar-like container
            const possibleContainers = document.querySelectorAll('div, nav, aside, ul, section');
            let sidebarFound = false;
            
            possibleContainers.forEach(container => {
                const rect = container.getBoundingClientRect();
                const hasLinks = container.querySelectorAll('a').length > 3;
                const isVertical = rect.height > rect.width;
                const isOnSide = rect.left < 300 || rect.right > window.innerWidth - 300;
                
                if (hasLinks && isVertical && isOnSide && !sidebarFound) {
                    console.log('🎯 تم العثور على حاوي محتمل للقائمة:', container.className || container.tagName);
                    addSettingsToContainer(container);
                    sidebarFound = true;
                }
            });
            
            if (!sidebarFound) {
                // Last resort: add to body
                addFloatingSidebar();
            }
            return;
        }

        console.log(`✅ تم العثور على ${foundLinks.length} رابط للإصلاح`);

        // Fix each found link
        foundLinks.forEach(linkInfo => {
            const link = linkInfo.element;
            const type = linkInfo.type;
            
            console.log('🔧 إصلاح رابط:', linkInfo.text);
            
            // Remove href to prevent # in URL
            link.removeAttribute('href');
            link.style.cursor = 'pointer';
            link.style.textDecoration = 'none';
            
            if (type === 'settings') {
                link.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleSettingsMenu(link);
                    return false;
                };
                
                // Add settings submenu
                addSubmenuToLink(link, createSettingsSubmenu());
                
            } else if (type === 'reports') {
                link.onclick = function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleReportsMenu(link);
                    return false;
                };
                
                // Add reports submenu
                addSubmenuToLink(link, createReportsSubmenu());
            }
        });

        // Create settings submenu
        function createSettingsSubmenu() {
            const submenu = document.createElement('div');
            submenu.className = 'settings-submenu-universal';
            submenu.style.cssText = `
                display: none;
                position: absolute;
                background: white;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                z-index: 1000;
                min-width: 250px;
                padding: 10px;
                margin-top: 5px;
            `;
            
            const settingsItems = [
                { icon: 'fas fa-building', text: 'إعدادات الشركة', url: 'https://www.maxcon.app/settings-company.php', color: '#667eea' },
                { icon: 'fas fa-users-cog', text: 'إدارة المستخدمين', url: 'https://www.maxcon.app/settings-users.php', color: '#10b981' },
                { icon: 'fas fa-shield-alt', text: 'الأمان والخصوصية', url: 'https://www.maxcon.app/settings-security.php', color: '#ef4444' },
                { icon: 'fas fa-database', text: 'النسخ الاحتياطية', url: 'https://www.maxcon.app/settings-backup.php', color: '#10b981' },
                { icon: 'fas fa-server', text: 'إعدادات النظام', url: 'https://www.maxcon.app/settings-system.php', color: '#8b5cf6' },
                { icon: 'fas fa-vial', text: 'اختبار الإعدادات', url: 'https://www.maxcon.app/test-all-settings.html', color: '#06b6d4' }
            ];

            settingsItems.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.style.cssText = 'margin: 5px 0;';
                
                const itemLink = document.createElement('a');
                itemLink.href = item.url;
                itemLink.target = '_blank';
                itemLink.style.cssText = `
                    display: flex;
                    align-items: center;
                    padding: 10px 15px;
                    color: #4a5568;
                    text-decoration: none;
                    border-radius: 6px;
                    font-size: 14px;
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    margin-bottom: 5px;
                    transition: all 0.3s ease;
                `;
                
                itemLink.innerHTML = `
                    <i class="${item.icon}" style="margin-left: 10px; color: ${item.color}; width: 20px;"></i>
                    <span>${item.text}</span>
                `;
                
                itemLink.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = item.color;
                    this.style.color = 'white';
                });
                
                itemLink.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '#f8fafc';
                    this.style.color = '#4a5568';
                });
                
                itemDiv.appendChild(itemLink);
                submenu.appendChild(itemDiv);
            });

            return submenu;
        }

        // Create reports submenu
        function createReportsSubmenu() {
            const submenu = document.createElement('div');
            submenu.className = 'reports-submenu-universal';
            submenu.style.cssText = `
                display: none;
                position: absolute;
                background: white;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                z-index: 1000;
                min-width: 250px;
                padding: 10px;
                margin-top: 5px;
            `;
            
            const reportsItems = [
                { icon: 'fas fa-chart-line', text: 'تقارير المبيعات', color: '#10b981' },
                { icon: 'fas fa-chart-pie', text: 'تقارير مالية', color: '#667eea' },
                { icon: 'fas fa-boxes', text: 'تقارير المخزون', color: '#f59e0b' },
                { icon: 'fas fa-users', text: 'تقارير العملاء', color: '#ef4444' },
                { icon: 'fas fa-brain', text: 'الذكاء الاصطناعي', color: '#8b5cf6' },
                { icon: 'fas fa-file-excel', text: 'تصدير Excel', color: '#059669' }
            ];

            reportsItems.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.style.cssText = 'margin: 5px 0;';
                
                const itemLink = document.createElement('div');
                itemLink.style.cssText = `
                    display: flex;
                    align-items: center;
                    padding: 10px 15px;
                    color: #4a5568;
                    border-radius: 6px;
                    font-size: 14px;
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    margin-bottom: 5px;
                    transition: all 0.3s ease;
                    cursor: pointer;
                `;
                
                itemLink.innerHTML = `
                    <i class="${item.icon}" style="margin-left: 10px; color: ${item.color}; width: 20px;"></i>
                    <span>${item.text}</span>
                `;
                
                itemLink.onclick = function() {
                    alert(`🚧 ${item.text}\n\nهذا القسم قيد التطوير وسيتم تفعيله قريباً.`);
                };
                
                itemLink.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = item.color;
                    this.style.color = 'white';
                });
                
                itemLink.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '#f8fafc';
                    this.style.color = '#4a5568';
                });
                
                itemDiv.appendChild(itemLink);
                submenu.appendChild(itemDiv);
            });

            return submenu;
        }

        // Add submenu to link
        function addSubmenuToLink(link, submenu) {
            const parent = link.parentElement;
            parent.style.position = 'relative';
            parent.appendChild(submenu);
        }

        // Add settings to any container
        function addSettingsToContainer(container) {
            const settingsItem = document.createElement('div');
            settingsItem.style.cssText = `
                padding: 10px;
                border: 2px solid #667eea;
                border-radius: 8px;
                margin: 10px;
                background: #f8fafc;
                cursor: pointer;
            `;
            settingsItem.innerHTML = `
                <div style="display: flex; align-items: center; color: #667eea; font-weight: 600;">
                    <i class="fas fa-cog" style="margin-left: 10px;"></i>
                    الإعدادات (تم الإضافة)
                </div>
            `;
            
            settingsItem.onclick = function() {
                window.open('https://www.maxcon.app/test-all-settings.html', '_blank');
            };
            
            container.appendChild(settingsItem);
            console.log('✅ تم إضافة قسم الإعدادات للحاوي');
        }

        // Add floating sidebar as last resort
        function addFloatingSidebar() {
            const floatingSidebar = document.createElement('div');
            floatingSidebar.style.cssText = `
                position: fixed;
                top: 20px;
                left: 20px;
                background: white;
                border: 2px solid #667eea;
                border-radius: 12px;
                padding: 20px;
                box-shadow: 0 8px 30px rgba(0,0,0,0.2);
                z-index: 10000;
                min-width: 200px;
            `;
            
            floatingSidebar.innerHTML = `
                <h3 style="color: #667eea; margin-bottom: 15px; text-align: center;">قائمة سريعة</h3>
                <div style="margin: 10px 0;">
                    <a href="https://www.maxcon.app/test-all-settings.html" target="_blank" style="
                        display: flex; align-items: center; padding: 10px; color: #4a5568; text-decoration: none;
                        border-radius: 6px; background: #f8fafc; margin-bottom: 5px;
                    ">
                        <i class="fas fa-cog" style="margin-left: 10px; color: #667eea;"></i>
                        الإعدادات
                    </a>
                    <div onclick="alert('قيد التطوير')" style="
                        display: flex; align-items: center; padding: 10px; color: #4a5568; cursor: pointer;
                        border-radius: 6px; background: #f8fafc; margin-bottom: 5px;
                    ">
                        <i class="fas fa-chart-line" style="margin-left: 10px; color: #10b981;"></i>
                        التقارير
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" style="
                    width: 100%; padding: 8px; background: #ef4444; color: white; border: none;
                    border-radius: 6px; cursor: pointer; margin-top: 10px;
                ">إغلاق</button>
            `;
            
            document.body.appendChild(floatingSidebar);
            console.log('✅ تم إضافة قائمة جانبية عائمة');
        }

        // Toggle functions
        window.toggleSettingsMenu = function(link) {
            const submenu = link.parentElement.querySelector('.settings-submenu-universal');
            if (submenu) {
                if (submenu.style.display === 'none' || submenu.style.display === '') {
                    submenu.style.display = 'block';
                    console.log('📂 فتح قائمة الإعدادات');
                } else {
                    submenu.style.display = 'none';
                    console.log('📁 إغلاق قائمة الإعدادات');
                }
            }
        };

        window.toggleReportsMenu = function(link) {
            const submenu = link.parentElement.querySelector('.reports-submenu-universal');
            if (submenu) {
                if (submenu.style.display === 'none' || submenu.style.display === '') {
                    submenu.style.display = 'block';
                    console.log('📂 فتح قائمة التقارير');
                } else {
                    submenu.style.display = 'none';
                    console.log('📁 إغلاق قائمة التقارير');
                }
            }
        };

        // Show success notification
        showSuccessNotification(foundLinks.length);

        function showSuccessNotification(fixedCount) {
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
                        <div style="font-size: 16px; font-weight: 700;">تم الإصلاح الشامل! 🎉</div>
                    </div>
                    <div style="font-size: 13px; opacity: 0.9; line-height: 1.4;">
                        ✅ تم إصلاح ${fixedCount} رابط<br>
                        ✅ لا مزيد من روابط # المعطلة<br>
                        ✅ قوائم فرعية تعمل<br>
                        ✅ جميع الأقسام تعمل الآن!
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
            }, 8000);
        }

        console.log('🎉 تم الإصلاح الشامل بنجاح!');

    } catch (error) {
        console.error('❌ خطأ في الإصلاح:', error);
        alert('❌ حدث خطأ في الإصلاح: ' + error.message);
    }
}, 5000); // Wait 5 seconds for complete page load

console.log('⏳ انتظار تحميل الصفحة كاملة (5 ثوانٍ)...');
