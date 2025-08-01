<?php
// Company Settings - Direct access without Laravel routing
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات الشركة - MaxCon ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f7fafc;
            line-height: 1.6;
            color: #2d3748;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            text-align: center;
        }

        .header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .card-title i {
            margin-left: 12px;
            color: #667eea;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4a5568;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .alert-info {
            background: #e6fffa;
            border: 1px solid #81e6d9;
            color: #234e52;
        }

        .alert-warning {
            background: #fffbeb;
            border: 1px solid #fbbf24;
            color: #92400e;
        }

        .file-upload {
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .file-upload:hover {
            border-color: #667eea;
            background: #f7fafc;
        }

        .file-upload.dragover {
            border-color: #667eea;
            background: #edf2f7;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
            
            .header {
                padding: 20px;
            }
            
            .card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>
                <i class="fas fa-building" style="margin-left: 15px;"></i>
                إعدادات الشركة
            </h1>
            <p>إدارة المعلومات الأساسية والإعدادات العامة للشركة</p>
        </div>

        <!-- Alert -->
        <div class="alert alert-info">
            <h4 style="margin-bottom: 10px;">💡 ملاحظة:</h4>
            <p>هذه صفحة تجريبية لإعدادات الشركة. في النسخة النهائية، ستكون متصلة بقاعدة البيانات لحفظ التغييرات.</p>
        </div>

        <form id="companySettingsForm">
            <div class="grid">
                <!-- معلومات الشركة الأساسية -->
                <div class="card">
                    <div class="card-title">
                        <i class="fas fa-info-circle"></i>
                        المعلومات الأساسية
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">اسم الشركة *</label>
                        <input type="text" class="form-input" id="companyName" value="شركة MaxCon للحلول التقنية" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">الاسم التجاري</label>
                        <input type="text" class="form-input" id="tradeName" value="MaxCon ERP">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">رقم السجل التجاري</label>
                        <input type="text" class="form-input" id="commercialRegister" value="1234567890">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">الرقم الضريبي</label>
                        <input type="text" class="form-input" id="taxNumber" value="TAX123456789">
                    </div>
                </div>

                <!-- معلومات الاتصال -->
                <div class="card">
                    <div class="card-title">
                        <i class="fas fa-phone"></i>
                        معلومات الاتصال
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">رقم الهاتف الرئيسي *</label>
                        <input type="tel" class="form-input" id="mainPhone" value="+964-XXX-XXXX" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">رقم الهاتف الثانوي</label>
                        <input type="tel" class="form-input" id="secondaryPhone" value="">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">البريد الإلكتروني *</label>
                        <input type="email" class="form-input" id="email" value="info@maxcon.app" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">الموقع الإلكتروني</label>
                        <input type="url" class="form-input" id="website" value="https://www.maxcon.app">
                    </div>
                </div>
            </div>

            <!-- العنوان -->
            <div class="card">
                <div class="card-title">
                    <i class="fas fa-map-marker-alt"></i>
                    العنوان
                </div>
                
                <div class="grid">
                    <div class="form-group">
                        <label class="form-label">البلد *</label>
                        <select class="form-input" id="country" required>
                            <option value="العراق" selected>العراق</option>
                            <option value="السعودية">السعودية</option>
                            <option value="الإمارات">الإمارات</option>
                            <option value="الكويت">الكويت</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">المدينة *</label>
                        <input type="text" class="form-input" id="city" value="بغداد" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">المنطقة/الحي</label>
                        <input type="text" class="form-input" id="district" value="الكرادة">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">الرمز البريدي</label>
                        <input type="text" class="form-input" id="postalCode" value="10001">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">العنوان التفصيلي</label>
                    <textarea class="form-input form-textarea" id="fullAddress" placeholder="أدخل العنوان الكامل...">شارع الكرادة داخل، مجمع الكرادة التجاري، الطابق الثالث</textarea>
                </div>
            </div>

            <!-- شعار الشركة -->
            <div class="card">
                <div class="card-title">
                    <i class="fas fa-image"></i>
                    شعار الشركة
                </div>
                
                <div class="file-upload" onclick="document.getElementById('logoInput').click()">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: #cbd5e0; margin-bottom: 15px;"></i>
                    <h3 style="color: #4a5568; margin-bottom: 10px;">اضغط لرفع الشعار</h3>
                    <p style="color: #718096; font-size: 14px;">PNG, JPG, SVG - الحد الأقصى 2MB</p>
                    <input type="file" id="logoInput" accept="image/*" style="display: none;" onchange="handleLogoUpload(this)">
                </div>
                
                <div id="logoPreview" style="margin-top: 20px; text-align: center; display: none;">
                    <img id="logoImage" style="max-width: 200px; max-height: 200px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                </div>
            </div>

            <!-- إعدادات النظام -->
            <div class="card">
                <div class="card-title">
                    <i class="fas fa-cogs"></i>
                    إعدادات النظام
                </div>
                
                <div class="grid">
                    <div class="form-group">
                        <label class="form-label">العملة الافتراضية</label>
                        <select class="form-input" id="currency">
                            <option value="IQD" selected>دينار عراقي (IQD)</option>
                            <option value="USD">دولار أمريكي (USD)</option>
                            <option value="EUR">يورو (EUR)</option>
                            <option value="SAR">ريال سعودي (SAR)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">المنطقة الزمنية</label>
                        <select class="form-input" id="timezone">
                            <option value="Asia/Baghdad" selected>بغداد (GMT+3)</option>
                            <option value="Asia/Riyadh">الرياض (GMT+3)</option>
                            <option value="Asia/Dubai">دبي (GMT+4)</option>
                            <option value="Asia/Kuwait">الكويت (GMT+3)</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">تنسيق التاريخ</label>
                        <select class="form-input" id="dateFormat">
                            <option value="d/m/Y" selected>يوم/شهر/سنة</option>
                            <option value="m/d/Y">شهر/يوم/سنة</option>
                            <option value="Y-m-d">سنة-شهر-يوم</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">لغة النظام</label>
                        <select class="form-input" id="language">
                            <option value="ar" selected>العربية</option>
                            <option value="en">English</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- أزرار الحفظ -->
            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    حفظ التغييرات
                </button>
                
                <button type="button" class="btn btn-secondary" onclick="resetForm()" style="margin-right: 15px;">
                    <i class="fas fa-undo"></i>
                    إعادة تعيين
                </button>
                
                <a href="https://www.maxcon.app" class="btn btn-success" style="margin-right: 15px;">
                    <i class="fas fa-home"></i>
                    العودة للنظام
                </a>
            </div>
        </form>

        <!-- Warning Alert -->
        <div class="alert alert-warning" style="margin-top: 30px;">
            <h4 style="margin-bottom: 10px;">⚠️ تنبيه:</h4>
            <p>هذه صفحة تجريبية. في النسخة النهائية من النظام، ستكون جميع البيانات محفوظة في قاعدة البيانات ومحمية بنظام أمان متقدم.</p>
        </div>
    </div>

    <script>
        // Handle form submission
        document.getElementById('companySettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simulate saving
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="fas fa-check"></i> تم الحفظ بنجاح!';
                submitBtn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.style.background = '';
                    submitBtn.disabled = false;
                }, 2000);
            }, 1500);
        });

        // Handle logo upload
        function handleLogoUpload(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    document.getElementById('logoImage').src = e.target.result;
                    document.getElementById('logoPreview').style.display = 'block';
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Reset form
        function resetForm() {
            if (confirm('هل أنت متأكد من إعادة تعيين جميع البيانات؟')) {
                document.getElementById('companySettingsForm').reset();
                document.getElementById('logoPreview').style.display = 'none';
            }
        }

        // Auto-save functionality (demo)
        let autoSaveTimeout;
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    console.log('Auto-saved:', this.id, this.value);
                    // In real implementation, this would save to database
                }, 2000);
            });
        });
    </script>
</body>
</html>
