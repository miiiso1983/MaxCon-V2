<?php
// Security Settings - Direct access
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأمان والخصوصية - MaxCon ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { font-family: 'Cairo', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f7fafc; line-height: 1.6; color: #2d3748; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; text-align: center; }
        .header h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: 10px; }
        .card { background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .card-title { font-size: 1.4rem; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; }
        .card-title i { margin-left: 12px; color: #ef4444; }
        .setting-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #f1f5f9; }
        .setting-item:last-child { border-bottom: none; }
        .setting-info h4 { margin: 0; color: #2d3748; font-size: 16px; }
        .setting-info p { margin: 0; color: #718096; font-size: 14px; }
        .toggle-switch { position: relative; width: 60px; height: 30px; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e0; transition: .4s; border-radius: 30px; }
        .slider:before { position: absolute; content: ""; height: 22px; width: 22px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #10b981; }
        input:checked + .slider:before { transform: translateX(30px); }
        .btn { padding: 12px 25px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; border: none; cursor: pointer; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
        .btn-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .alert { padding: 15px; border-radius: 8px; margin: 20px 0; }
        .alert-warning { background: #fffbeb; border: 1px solid #fbbf24; color: #92400e; }
        .alert-info { background: #e6fffa; border: 1px solid #81e6d9; color: #234e52; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; }
        .form-input { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.3s ease; }
        .form-input:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-shield-alt" style="margin-left: 15px;"></i>الأمان والخصوصية</h1>
            <p>إعدادات الأمان وحماية البيانات والخصوصية</p>
        </div>

        <div class="alert alert-warning">
            <h4 style="margin-bottom: 10px;">⚠️ تنبيه أمني:</h4>
            <p>هذه الإعدادات تؤثر على أمان النظام بالكامل. تأكد من فهم كل إعداد قبل تغييره.</p>
        </div>

        <div class="grid">
            <div class="card">
                <div class="card-title"><i class="fas fa-key"></i>إعدادات كلمات المرور</div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <h4>طول كلمة المرور الأدنى</h4>
                        <p>الحد الأدنى لعدد أحرف كلمة المرور</p>
                    </div>
                    <input type="number" class="form-input" value="8" min="6" max="20" style="width: 80px;">
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h4>تطلب أحرف كبيرة وصغيرة</h4>
                        <p>إجبار استخدام أحرف كبيرة وصغيرة</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h4>تطلب أرقام ورموز خاصة</h4>
                        <p>إجبار استخدام أرقام ورموز خاصة</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h4>انتهاء صلاحية كلمة المرور</h4>
                        <p>عدد الأيام قبل انتهاء كلمة المرور</p>
                    </div>
                    <select class="form-input" style="width: 120px;">
                        <option value="30">30 يوم</option>
                        <option value="60">60 يوم</option>
                        <option value="90" selected>90 يوم</option>
                        <option value="0">بدون انتهاء</option>
                    </select>
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="fas fa-user-lock"></i>إعدادات تسجيل الدخول</div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <h4>المصادقة الثنائية (2FA)</h4>
                        <p>تفعيل المصادقة الثنائية لجميع المستخدمين</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h4>مدة انتهاء الجلسة</h4>
                        <p>المدة قبل انتهاء جلسة المستخدم</p>
                    </div>
                    <select class="form-input" style="width: 120px;">
                        <option value="30">30 دقيقة</option>
                        <option value="60">ساعة واحدة</option>
                        <option value="480" selected>8 ساعات</option>
                        <option value="1440">24 ساعة</option>
                    </select>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h4>عدد محاولات الدخول الخاطئة</h4>
                        <p>عدد المحاولات قبل قفل الحساب</p>
                    </div>
                    <input type="number" class="form-input" value="5" min="3" max="10" style="width: 80px;">
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h4>مدة قفل الحساب</h4>
                        <p>المدة بالدقائق لقفل الحساب</p>
                    </div>
                    <input type="number" class="form-input" value="30" min="5" max="1440" style="width: 80px;">
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title"><i class="fas fa-database"></i>حماية البيانات</div>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>تشفير البيانات الحساسة</h4>
                    <p>تشفير البيانات المالية والشخصية</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked disabled>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>النسخ الاحتياطية التلقائية</h4>
                    <p>إنشاء نسخ احتياطية تلقائية للبيانات</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>تسجيل جميع العمليات</h4>
                    <p>تسجيل جميع العمليات في سجل النظام</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>تنبيهات الأمان</h4>
                    <p>إرسال تنبيهات عند العمليات الحساسة</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="card">
            <div class="card-title"><i class="fas fa-eye-slash"></i>إعدادات الخصوصية</div>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>إخفاء البيانات الحساسة</h4>
                    <p>إخفاء الأرقام المالية في التقارير</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox">
                    <span class="slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>مشاركة البيانات مع الطرف الثالث</h4>
                    <p>السماح بمشاركة البيانات للتحليلات</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox">
                    <span class="slider"></span>
                </label>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>الاحتفاظ بسجل البيانات</h4>
                    <p>مدة الاحتفاظ بسجل العمليات</p>
                </div>
                <select class="form-input" style="width: 150px;">
                    <option value="30">30 يوم</option>
                    <option value="90">90 يوم</option>
                    <option value="365" selected>سنة واحدة</option>
                    <option value="1095">3 سنوات</option>
                </select>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <button class="btn btn-primary" onclick="saveSecuritySettings()">
                <i class="fas fa-save"></i>
                حفظ إعدادات الأمان
            </button>
            
            <button class="btn btn-danger" onclick="resetSecuritySettings()" style="margin-right: 15px;">
                <i class="fas fa-undo"></i>
                إعادة تعيين
            </button>
            
            <a href="https://www.maxcon.app" class="btn btn-success" style="margin-right: 15px;">
                <i class="fas fa-home"></i>
                العودة للنظام
            </a>
        </div>

        <div class="alert alert-info" style="margin-top: 30px;">
            <h4 style="margin-bottom: 10px;">💡 نصائح الأمان:</h4>
            <ul style="margin: 10px 0; padding-right: 20px;">
                <li>استخدم كلمات مرور قوية ومعقدة</li>
                <li>فعّل المصادقة الثنائية لجميع المديرين</li>
                <li>راجع سجل العمليات بانتظام</li>
                <li>قم بإجراء نسخ احتياطية دورية</li>
                <li>حدّث النظام بانتظام</li>
            </ul>
        </div>
    </div>

    <script>
        function saveSecuritySettings() {
            const btn = event.target;
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check"></i> تم الحفظ بنجاح!';
                btn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 2000);
            }, 1500);
        }

        function resetSecuritySettings() {
            if (confirm('هل أنت متأكد من إعادة تعيين جميع إعدادات الأمان؟')) {
                location.reload();
            }
        }
    </script>
</body>
</html>
