<?php
// Backup Settings - Direct access
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>النسخ الاحتياطية - MaxCon ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { font-family: 'Cairo', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f7fafc; line-height: 1.6; color: #2d3748; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; padding: 30px; margin-bottom: 30px; color: white; text-align: center; }
        .header h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: 10px; }
        .card { background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .card-title { font-size: 1.4rem; font-weight: 700; color: #2d3748; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; }
        .card-title i { margin-left: 12px; color: #10b981; }
        .btn { padding: 12px 25px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; border: none; cursor: pointer; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
        .btn-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
        .btn-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .backup-item { display: flex; justify-content: space-between; align-items: center; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 10px; }
        .backup-info h4 { margin: 0; color: #2d3748; }
        .backup-info p { margin: 0; color: #718096; font-size: 14px; }
        .backup-size { background: #f7fafc; padding: 4px 8px; border-radius: 4px; font-size: 12px; color: #4a5568; }
        .progress-bar { background: #e2e8f0; border-radius: 10px; height: 8px; overflow: hidden; margin: 10px 0; }
        .progress-fill { background: linear-gradient(135deg, #10b981 0%, #059669 100%); height: 100%; transition: width 0.3s ease; }
        .alert { padding: 15px; border-radius: 8px; margin: 20px 0; }
        .alert-info { background: #e6fffa; border: 1px solid #81e6d9; color: #234e52; }
        .alert-success { background: #f0fff4; border: 1px solid #68d391; color: #22543d; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; border-radius: 12px; padding: 20px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .stat-number { font-size: 2rem; font-weight: 800; margin-bottom: 5px; }
        .stat-label { color: #718096; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-database" style="margin-left: 15px;"></i>النسخ الاحتياطية</h1>
            <p>إدارة النسخ الاحتياطية واستعادة البيانات</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" style="color: #10b981;">15</div>
                <div class="stat-label">إجمالي النسخ الاحتياطية</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #667eea;">2.5 GB</div>
                <div class="stat-label">حجم البيانات</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #f59e0b;">يومياً</div>
                <div class="stat-label">تكرار النسخ</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" style="color: #ef4444;">30 يوم</div>
                <div class="stat-label">مدة الاحتفاظ</div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">
                <div><i class="fas fa-plus-circle"></i>إنشاء نسخة احتياطية جديدة</div>
                <button class="btn btn-primary" onclick="createBackup()">
                    <i class="fas fa-save"></i>
                    إنشاء نسخة احتياطية الآن
                </button>
            </div>
            
            <div id="backupProgress" style="display: none;">
                <h4 style="margin-bottom: 10px;">جاري إنشاء النسخة الاحتياطية...</h4>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill" style="width: 0%;"></div>
                </div>
                <p id="progressText" style="text-align: center; color: #4a5568;">0% مكتمل</p>
            </div>
        </div>

        <div class="card">
            <div class="card-title">
                <div><i class="fas fa-history"></i>النسخ الاحتياطية المتاحة</div>
            </div>
            
            <div class="backup-item">
                <div class="backup-info">
                    <h4>نسخة احتياطية تلقائية - اليوم</h4>
                    <p>تم الإنشاء: اليوم في 03:00 ص</p>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span class="backup-size">245 MB</span>
                    <button class="btn btn-success btn-sm" onclick="restoreBackup('today')">
                        <i class="fas fa-undo"></i>
                        استعادة
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="downloadBackup('today')">
                        <i class="fas fa-download"></i>
                        تحميل
                    </button>
                </div>
            </div>

            <div class="backup-item">
                <div class="backup-info">
                    <h4>نسخة احتياطية تلقائية - أمس</h4>
                    <p>تم الإنشاء: أمس في 03:00 ص</p>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span class="backup-size">243 MB</span>
                    <button class="btn btn-success btn-sm" onclick="restoreBackup('yesterday')">
                        <i class="fas fa-undo"></i>
                        استعادة
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="downloadBackup('yesterday')">
                        <i class="fas fa-download"></i>
                        تحميل
                    </button>
                </div>
            </div>

            <div class="backup-item">
                <div class="backup-info">
                    <h4>نسخة احتياطية أسبوعية</h4>
                    <p>تم الإنشاء: منذ 3 أيام في 02:00 ص</p>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span class="backup-size">238 MB</span>
                    <button class="btn btn-success btn-sm" onclick="restoreBackup('weekly')">
                        <i class="fas fa-undo"></i>
                        استعادة
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="downloadBackup('weekly')">
                        <i class="fas fa-download"></i>
                        تحميل
                    </button>
                </div>
            </div>

            <div class="backup-item">
                <div class="backup-info">
                    <h4>نسخة احتياطية شهرية</h4>
                    <p>تم الإنشاء: منذ أسبوع في 01:00 ص</p>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span class="backup-size">220 MB</span>
                    <button class="btn btn-success btn-sm" onclick="restoreBackup('monthly')">
                        <i class="fas fa-undo"></i>
                        استعادة
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="downloadBackup('monthly')">
                        <i class="fas fa-download"></i>
                        تحميل
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">
                <div><i class="fas fa-cogs"></i>إعدادات النسخ الاحتياطية</div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <div>
                    <h4 style="margin-bottom: 10px;">تكرار النسخ التلقائية</h4>
                    <select style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <option value="daily" selected>يومياً</option>
                        <option value="weekly">أسبوعياً</option>
                        <option value="monthly">شهرياً</option>
                        <option value="disabled">معطل</option>
                    </select>
                </div>
                
                <div>
                    <h4 style="margin-bottom: 10px;">وقت النسخ التلقائي</h4>
                    <input type="time" value="03:00" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px;">
                </div>
                
                <div>
                    <h4 style="margin-bottom: 10px;">مدة الاحتفاظ بالنسخ</h4>
                    <select style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <option value="7">7 أيام</option>
                        <option value="30" selected>30 يوم</option>
                        <option value="90">90 يوم</option>
                        <option value="365">سنة واحدة</option>
                    </select>
                </div>
                
                <div>
                    <h4 style="margin-bottom: 10px;">نوع النسخة الاحتياطية</h4>
                    <select style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px;">
                        <option value="full" selected>نسخة كاملة</option>
                        <option value="incremental">نسخة تزايدية</option>
                        <option value="differential">نسخة تفاضلية</option>
                    </select>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <button class="btn btn-primary" onclick="saveBackupSettings()">
                    <i class="fas fa-save"></i>
                    حفظ الإعدادات
                </button>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="https://www.maxcon.app" class="btn btn-success">
                <i class="fas fa-home"></i>
                العودة للنظام
            </a>
        </div>

        <div class="alert alert-info">
            <h4 style="margin-bottom: 10px;">💡 نصائح مهمة:</h4>
            <ul style="margin: 10px 0; padding-right: 20px;">
                <li>تأكد من إجراء نسخ احتياطية دورية</li>
                <li>احتفظ بنسخ احتياطية في أماكن متعددة</li>
                <li>اختبر استعادة النسخ الاحتياطية بانتظام</li>
                <li>احم النسخ الاحتياطية بكلمات مرور قوية</li>
            </ul>
        </div>
    </div>

    <script>
        function createBackup() {
            const progressDiv = document.getElementById('backupProgress');
            const progressFill = document.getElementById('progressFill');
            const progressText = document.getElementById('progressText');
            
            progressDiv.style.display = 'block';
            
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 100) progress = 100;
                
                progressFill.style.width = progress + '%';
                progressText.textContent = Math.round(progress) + '% مكتمل';
                
                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        progressDiv.style.display = 'none';
                        alert('تم إنشاء النسخة الاحتياطية بنجاح!');
                        location.reload();
                    }, 1000);
                }
            }, 200);
        }

        function restoreBackup(backupId) {
            if (confirm('هل أنت متأكد من استعادة هذه النسخة الاحتياطية؟ سيتم استبدال البيانات الحالية.')) {
                alert('تم بدء عملية الاستعادة. سيتم إعادة تشغيل النظام بعد الانتهاء.');
            }
        }

        function downloadBackup(backupId) {
            alert('جاري تحضير النسخة الاحتياطية للتحميل...');
        }

        function saveBackupSettings() {
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
    </script>
</body>
</html>
