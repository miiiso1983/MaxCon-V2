<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>اختبار Custom Dropdowns</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Select Styles -->
    <link rel="stylesheet" href="{{ asset('css/custom-select.css') }}">
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            color: #2d3748;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4a5568;
        }
        
        /* Hide original select elements */
        .custom-dropdown select {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>اختبار Custom Search Dropdowns</h1>
        
        <div class="form-group">
            <label>العميل *</label>
            <div class="custom-dropdown" data-name="customer_id" data-required="true">
                <div class="dropdown-header" onclick="toggleDropdown(this)">
                    <span class="dropdown-placeholder">اختر العميل</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-content">
                    <input type="text" class="dropdown-search" placeholder="البحث عن عميل..." onkeyup="filterOptions(this)">
                    <div class="dropdown-options">
                        <div class="dropdown-option" data-value="" onclick="selectOption(this)">
                            اختر العميل
                        </div>
                        <div class="dropdown-option" data-value="1" onclick="selectOption(this)">
                            شركة الأدوية المتقدمة (ADV001)
                        </div>
                        <div class="dropdown-option" data-value="2" onclick="selectOption(this)">
                            مستشفى بغداد العام (BGH002)
                        </div>
                        <div class="dropdown-option" data-value="3" onclick="selectOption(this)">
                            صيدلية النور الطبية (NUR003)
                        </div>
                        <div class="dropdown-option" data-value="4" onclick="selectOption(this)">
                            مجمع الشفاء الطبي (SHF004)
                        </div>
                    </div>
                </div>
                <select name="customer_id" style="display: none;">
                    <option value="">اختر العميل</option>
                    <option value="1">شركة الأدوية المتقدمة (ADV001)</option>
                    <option value="2">مستشفى بغداد العام (BGH002)</option>
                    <option value="3">صيدلية النور الطبية (NUR003)</option>
                    <option value="4">مجمع الشفاء الطبي (SHF004)</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label>المنتج *</label>
            <div class="custom-dropdown" data-name="product_id" data-required="true">
                <div class="dropdown-header" onclick="toggleDropdown(this)">
                    <span class="dropdown-placeholder">اختر المنتج</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-content">
                    <input type="text" class="dropdown-search" placeholder="البحث عن منتج..." onkeyup="filterOptions(this)">
                    <div class="dropdown-options">
                        <div class="dropdown-option" data-value="" onclick="selectOption(this)">
                            اختر المنتج
                        </div>
                        <div class="dropdown-option" data-value="1" onclick="selectOption(this)">
                            باراسيتامول 500 مجم (100 قرص)
                        </div>
                        <div class="dropdown-option" data-value="2" onclick="selectOption(this)">
                            أموكسيسيلين 250 مجم (50 كبسولة)
                        </div>
                        <div class="dropdown-option" data-value="3" onclick="selectOption(this)">
                            إيبوبروفين 400 مجم (75 قرص)
                        </div>
                        <div class="dropdown-option" data-value="4" onclick="selectOption(this)">
                            أسبرين 100 مجم (200 قرص)
                        </div>
                    </div>
                </div>
                <select name="product_id" style="display: none;">
                    <option value="">اختر المنتج</option>
                    <option value="1">باراسيتامول 500 مجم (100 قرص)</option>
                    <option value="2">أموكسيسيلين 250 مجم (50 كبسولة)</option>
                    <option value="3">إيبوبروفين 400 مجم (75 قرص)</option>
                    <option value="4">أسبرين 100 مجم (200 قرص)</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label>العملة *</label>
            <div class="custom-dropdown" data-name="currency" data-required="true">
                <div class="dropdown-header" onclick="toggleDropdown(this)">
                    <span class="dropdown-placeholder">اختر العملة</span>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-content">
                    <input type="text" class="dropdown-search" placeholder="البحث عن عملة..." onkeyup="filterOptions(this)">
                    <div class="dropdown-options">
                        <div class="dropdown-option" data-value="" onclick="selectOption(this)">
                            اختر العملة
                        </div>
                        <div class="dropdown-option" data-value="IQD" onclick="selectOption(this)">
                            دينار عراقي (IQD)
                        </div>
                        <div class="dropdown-option" data-value="USD" onclick="selectOption(this)">
                            دولار أمريكي (USD)
                        </div>
                        <div class="dropdown-option" data-value="EUR" onclick="selectOption(this)">
                            يورو (EUR)
                        </div>
                        <div class="dropdown-option" data-value="SAR" onclick="selectOption(this)">
                            ريال سعودي (SAR)
                        </div>
                    </div>
                </div>
                <select name="currency" style="display: none;">
                    <option value="">اختر العملة</option>
                    <option value="IQD">دينار عراقي (IQD)</option>
                    <option value="USD">دولار أمريكي (USD)</option>
                    <option value="EUR">يورو (EUR)</option>
                    <option value="SAR">ريال سعودي (SAR)</option>
                </select>
            </div>
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background: #f0f9ff; border-radius: 8px;">
            <h3 style="color: #0284c7; margin-bottom: 10px;">تعليمات الاختبار:</h3>
            <ul style="color: #075985; line-height: 1.6;">
                <li>انقر على أي dropdown لفتحه</li>
                <li>استخدم مربع البحث للبحث في الخيارات</li>
                <li>انقر على أي خيار لاختياره</li>
                <li>يجب أن ترى السهم يدور عند فتح/إغلاق القائمة</li>
                <li>يجب أن تظهر تأثيرات hover عند التمرير على الخيارات</li>
            </ul>
        </div>
    </div>

    <script>
        console.log('Test page loaded');
        
        // Custom Dropdown Functions
        function toggleDropdown(header) {
            console.log('toggleDropdown called');
            const dropdown = header.closest('.custom-dropdown');
            const content = dropdown.querySelector('.dropdown-content');
            
            // Close all other dropdowns
            document.querySelectorAll('.custom-dropdown .dropdown-content.show').forEach(otherContent => {
                if (otherContent !== content) {
                    otherContent.classList.remove('show');
                    otherContent.closest('.custom-dropdown').querySelector('.dropdown-header').classList.remove('active');
                }
            });
            
            // Toggle current dropdown
            content.classList.toggle('show');
            header.classList.toggle('active');
            
            // Focus search input if dropdown is opened
            if (content.classList.contains('show')) {
                const searchInput = content.querySelector('.dropdown-search');
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            }
        }
        
        function selectOption(option) {
            console.log('selectOption called', option.textContent);
            const dropdown = option.closest('.custom-dropdown');
            const header = dropdown.querySelector('.dropdown-header');
            const placeholder = header.querySelector('.dropdown-placeholder');
            const content = dropdown.querySelector('.dropdown-content');
            const hiddenSelect = dropdown.querySelector('select');
            
            // Update visual display
            placeholder.textContent = option.textContent.trim();
            
            // Update hidden select
            hiddenSelect.value = option.dataset.value;
            
            // Mark as selected
            dropdown.querySelectorAll('.dropdown-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            option.classList.add('selected');
            
            // Close dropdown
            content.classList.remove('show');
            header.classList.remove('active');
            
            console.log('Selected value:', hiddenSelect.value);
        }
        
        function filterOptions(searchInput) {
            const searchTerm = searchInput.value.toLowerCase();
            const dropdown = searchInput.closest('.custom-dropdown');
            const options = dropdown.querySelectorAll('.dropdown-option');
            
            options.forEach(option => {
                const text = option.textContent.toLowerCase();
                if (text.includes(searchTerm) || option.dataset.value === '') {
                    option.classList.remove('hidden');
                } else {
                    option.classList.add('hidden');
                }
            });
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.custom-dropdown')) {
                document.querySelectorAll('.dropdown-content.show').forEach(content => {
                    content.classList.remove('show');
                    content.closest('.custom-dropdown').querySelector('.dropdown-header').classList.remove('active');
                });
            }
        });
        
        console.log('All functions loaded');
    </script>
</body>
</html>
