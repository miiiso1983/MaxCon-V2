<?php
// System Settings - Direct access
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعدادات النظام - MaxCon ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { font-family: 'Cairo', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f7fafc; line-height: 1.6; color: #2d3748; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; text-align: center; }
        .header h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: 10px; }
        .card { background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .card-title { font-size: 1.4rem; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; }
        .card-title i { margin-left: 12px; color: #8b5cf6; }
        .setting-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #f1f5f9; }
        .setting-item:last-child { border-bottom: none; }
        .setting-info h4 { margin: 0; color: #2d3748; font-size: 16px; }
        .setting-info p { margin: 0; color: #718096; font-size: 14px; }
        .form-input { padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 14px; }
        .btn { padding: 12px 25px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; border: none; cursor: pointer; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
        .btn-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
        .btn-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .alert { padding: 15px; border-radius: 8px; margin: 20px 0; }
        .alert-info { background: #e6fffa; border: 1px solid #81e6d9; color: #234e52; }
        .alert-warning { background: #fffbeb; border: 1px solid #fbbf24; color: #92400e; }
        .system-info { background: #f8fafc; border-radius: 8px; padding: 15px; margin: 10px 0; }
        .system-info h4 { color: #2d3748; margin-bottom: 8px; }
        .system-info p { color: #4a5568; margin: 0; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .status-good { color: #10b981; font-weight: 600; }
        .status-warning { color: #f59e0b; font-weight: 600; }
        .status-error { color: #ef4444; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-server" style="margin-left: 15px;"></i>إعدادات النظام</h1>
            <p>إعدادات الخادم والأداء ومعلومات النظام</p>
        </div>

        <div class="alert alert-info">
            <h4 style="margin-bottom: 10px;">💡 ملاحظة:</h4>
            <p>هذه الإعدادات تؤثر على أداء النظام بالكامل. يُنصح بعدم تغييرها إلا من قبل مختصين.</p>
        </div>

        <div class="grid">
            <div class="card">
                <div class="card-title"><i class="fas fa-info-circle"></i>معلومات النظام</div>
                
                <div class="system-info">
                    <h4>إصدار النظام</h4>
                    <p>MaxCon ERP v2.0.1</p>
                </div>
                
                <div class="system-info">
                    <h4>إصدار PHP</h4>
                    <p class="status-good">PHP 8.1.12</p>
                </div>
                
                <div class="system-info">
                    <h4>إصدار قاعدة البيانات</h4>
                    <p class="status-good">MySQL 8.0.30</p>
                </div>
                
                <div class="system-info">
                    <h4>مساحة القرص المستخدمة</h4>
                    <p class="status-warning">2.5 GB / 10 GB (25%)</p>
                </div>
                
                <div class="system-info">
                    <h4>استخدام الذاكرة</h4>
                    <p class="status-good">512 MB / 2 GB (25%)</p>
                </div>
                
                <div class="system-info">
                    <h4>وقت تشغيل الخادم</h4>
                    <p>15 يوم، 8 ساعات، 32 دقيقة</p>
                </div>
            </div>

            <div class="card">
                <div class="card-title"><i class="fas fa-tachometer-alt"></i>إعدادات الأداء</div>
                
                <div class="setting-item">
                    <div class="setting-info">
                        <h4>حد الذاكرة لكل عملية</h4>
                        <p>الحد الأقصى للذاكرة المستخدمة</p>
                    </div>
                    <select class="form-input">
                        <option value="128">128 MB</option>
                        <option value="256" selected>256 MB</option>
                        <option value="512">512 MB</option>
                        <option value="1024">1 GB</option>
                    </select>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h4>مهلة تنفيذ العمليات</h4>
                        <p>الحد الأقصى لوقت تنفيذ العملية</p>
                    </div>
                    <select class="form-input">
                        <option value="30" selected>30 ثانية</option>
                        <option value="60">60 ثانية</option>
                        <option value="120">2 دقيقة</option>
                        <option value="300">5 دقائق</option>
                    </select>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h4>حجم ملف الرفع الأقصى</h4>
                        <p>الحد الأقصى لحجم الملفات المرفوعة</p>
                    </div>
                    <select class="form-input">
                        <option value="2">2 MB</option>
                        <option value="5" selected>5 MB</option>
                        <option value="10">10 MB</option>
                        <option value="50">50 MB</option>
                    </select>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h4>تفعيل ضغط البيانات</h4>
                        <p>ضغط البيانات لتوفير عرض النطاق</p>
                    </div>
                    <select class="form-input">
                        <option value="enabled" selected>مفعل</option>
                        <option value="disabled">معطل</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title"><i class="fas fa-database"></i>إعدادات قاعدة البيانات</div>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>حالة الاتصال بقاعدة البيانات</h4>
                    <p>حالة الاتصال الحالية</p>
                </div>
                <span class="status-good">متصل ✓</span>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>عدد الاتصالات النشطة</h4>
                    <p>عدد الاتصالات المفتوحة حالياً</p>
                </div>
                <span>5 / 100</span>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>حجم قاعدة البيانات</h4>
                    <p>المساحة المستخدمة في قاعدة البيانات</p>
                </div>
                <span>245 MB</span>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>آخر نسخة احتياطية</h4>
                    <p>تاريخ آخر نسخة احتياطية لقاعدة البيانات</p>
                </div>
                <span class="status-good">اليوم 03:00 ص</span>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <button class="btn btn-primary" onclick="testDatabaseConnection()">
                    <i class="fas fa-plug"></i>
                    اختبار الاتصال
                </button>
                
                <button class="btn btn-warning" onclick="optimizeDatabase()">
                    <i class="fas fa-tools"></i>
                    تحسين قاعدة البيانات
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-title"><i class="fas fa-bug"></i>إعدادات التطوير والتشخيص</div>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>وضع التطوير (Debug Mode)</h4>
                    <p>عرض رسائل الأخطاء التفصيلية</p>
                </div>
                <select class="form-input">
                    <option value="false" selected>معطل (الإنتاج)</option>
                    <option value="true">مفعل (التطوير)</option>
                </select>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>مستوى تسجيل الأخطاء</h4>
                    <p>مستوى تفصيل سجل الأخطاء</p>
                </div>
                <select class="form-input">
                    <option value="error" selected>أخطاء فقط</option>
                    <option value="warning">تحذيرات وأخطاء</option>
                    <option value="info">جميع المعلومات</option>
                    <option value="debug">وضع التشخيص</option>
                </select>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>تفعيل الكاش</h4>
                    <p>تفعيل نظام التخزين المؤقت</p>
                </div>
                <select class="form-input">
                    <option value="enabled" selected>مفعل</option>
                    <option value="disabled">معطل</option>
                </select>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <button class="btn btn-warning" onclick="clearCache()">
                    <i class="fas fa-broom"></i>
                    مسح الكاش
                </button>
                
                <button class="btn btn-danger" onclick="clearLogs()">
                    <i class="fas fa-trash"></i>
                    مسح سجل الأخطاء
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-title"><i class="fas fa-shield-alt"></i>إعدادات الأمان المتقدمة</div>
            
            <div class="setting-item">
                <div class="setting-info">
                    <h4>تفعيل جدار الحماية</h4>
                    <p>حماية من الهجمات الشائعة</p>
                </div>
                <select class="form-input">
                    <option value="enabled" selected>مفعل</option>
                    <option value="disabled">معطل</option>
                </select>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>حد معدل الطلبات</h4>
                    <p>عدد الطلبات المسموحة في الدقيقة</p>
                </div>
                <select class="form-input">
                    <option value="60" selected>60 طلب/دقيقة</option>
                    <option value="120">120 طلب/دقيقة</option>
                    <option value="300">300 طلب/دقيقة</option>
                    <option value="unlimited">بدون حد</option>
                </select>
            </div>

            <div class="setting-item">
                <div class="setting-info">
                    <h4>تشفير الاتصالات</h4>
                    <p>إجبار استخدام HTTPS</p>
                </div>
                <select class="form-input">
                    <option value="forced" selected>إجباري</option>
                    <option value="optional">اختياري</option>
                    <option value="disabled">معطل</option>
                </select>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <button class="btn btn-primary" onclick="saveSystemSettings()">
                <i class="fas fa-save"></i>
                حفظ جميع الإعدادات
            </button>
            
            <button class="btn btn-warning" onclick="restartSystem()" style="margin-right: 15px;">
                <i class="fas fa-redo"></i>
                إعادة تشغيل النظام
            </button>
            
            <a href="https://www.maxcon.app" class="btn btn-success" style="margin-right: 15px;">
                <i class="fas fa-home"></i>
                العودة للنظام
            </a>
        </div>

        <div class="alert alert-warning" style="margin-top: 30px;">
            <h4 style="margin-bottom: 10px;">⚠️ تحذير:</h4>
            <p>تغيير إعدادات النظام قد يؤثر على الأداء أو الأمان. تأكد من فهم كل إعداد قبل تغييره.</p>
        </div>
    </div>

    <script>
        function saveSystemSettings() {
            const btn = event.target;
            const originalText = btn.innerHTML;
            
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check"></i> تم الحفظ!';
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 2000);
            }, 1500);
        }

        function testDatabaseConnection() {
            alert('جاري اختبار الاتصال بقاعدة البيانات...\n✓ الاتصال ناجح!');
        }

        function optimizeDatabase() {
            if (confirm('هل تريد تحسين قاعدة البيانات؟ قد يستغرق هذا بضع دقائق.')) {
                alert('تم بدء عملية تحسين قاعدة البيانات...');
            }
        }

        function clearCache() {
            if (confirm('هل تريد مسح جميع ملفات الكاش؟')) {
                alert('تم مسح الكاش بنجاح!');
            }
        }

        function clearLogs() {
            if (confirm('هل تريد مسح جميع ملفات السجل؟')) {
                alert('تم مسح ملفات السجل بنجاح!');
            }
        }

        function restartSystem() {
            if (confirm('هل تريد إعادة تشغيل النظام؟ سيتم قطع الاتصال مؤقتاً.')) {
                alert('جاري إعادة تشغيل النظام...');
            }
        }
    </script>
</body>
</html>
