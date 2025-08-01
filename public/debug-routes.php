<?php
// Debug routes file - يجب حذفه في الإنتاج
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فحص الراوتات - MaxCon ERP</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }

        .header h1 {
            color: #2d3748;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 30px;
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
        }

        .section-title {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-left: 10px;
            color: #667eea;
        }

        .route-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-right: 4px solid #10b981;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .route-item.error {
            border-right-color: #ef4444;
        }

        .route-name {
            font-weight: 600;
            color: #2d3748;
        }

        .route-url {
            color: #4a5568;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }

        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status.success {
            background: #d1fae5;
            color: #065f46;
        }

        .status.error {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .info-box {
            background: #e6fffa;
            border: 1px solid #81e6d9;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            color: #234e52;
        }

        .error-box {
            background: #fef2f2;
            border: 1px solid #f87171;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            color: #991b1b;
        }

        .code {
            background: #1a202c;
            color: #e2e8f0;
            padding: 10px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 فحص راوتات النظام</h1>
            <p>تشخيص مشكلة عدم ظهور دليل المستأجر الجديد</p>
        </div>

        <!-- System Guide Routes Check -->
        <div class="section">
            <div class="section-title">
                <i class="fas fa-route"></i>
                راوتات دليل النظام (System Guide)
            </div>
            
            <?php
            $systemGuideRoutes = [
                'tenant.system-guide.index' => '/tenant/system-guide/',
                'tenant.system-guide.introduction' => '/tenant/system-guide/introduction',
                'tenant.system-guide.faq' => '/tenant/system-guide/faq',
                'tenant.system-guide.new-tenant-guide' => '/tenant/system-guide/new-tenant-guide',
                'tenant.system-guide.videos' => '/tenant/system-guide/videos',
                'tenant.system-guide.download-manual' => '/tenant/system-guide/download-manual'
            ];

            foreach ($systemGuideRoutes as $routeName => $expectedUrl) {
                echo '<div class="route-item">';
                echo '<div>';
                echo '<div class="route-name">' . $routeName . '</div>';
                echo '<div class="route-url">' . $expectedUrl . '</div>';
                echo '</div>';
                echo '<div>';
                echo '<span class="status success">متوقع</span>';
                echo '<a href="' . $expectedUrl . '" class="btn" target="_blank">';
                echo '<i class="fas fa-external-link-alt"></i> اختبار';
                echo '</a>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>

        <!-- Sidebar Links Check -->
        <div class="section">
            <div class="section-title">
                <i class="fas fa-bars"></i>
                روابط القائمة الجانبية
            </div>
            
            <div class="info-box">
                <h4>الرابط في القائمة الجانبية:</h4>
                <div class="code">{{ route('tenant.system-guide.new-tenant-guide') }}</div>
                <p>هذا الرابط يجب أن يولد: <strong>/tenant/system-guide/new-tenant-guide</strong></p>
            </div>

            <div class="route-item">
                <div>
                    <div class="route-name">رابط القائمة الجانبية</div>
                    <div class="route-url">resources/views/layouts/tenant.blade.php (السطر 654)</div>
                </div>
                <div>
                    <span class="status success">موجود</span>
                </div>
            </div>
        </div>

        <!-- File Structure Check -->
        <div class="section">
            <div class="section-title">
                <i class="fas fa-folder"></i>
                هيكل الملفات
            </div>
            
            <?php
            $files = [
                'routes/web.php' => 'ملف الراوتات الرئيسي',
                'routes/tenant/system-guide.php' => 'راوتات دليل النظام',
                'app/Http/Controllers/Tenant/SystemGuideController.php' => 'كونترولر دليل النظام',
                'resources/views/tenant/system-guide/new-tenant-guide.blade.php' => 'صفحة دليل المستأجر الجديد',
                'resources/views/layouts/tenant.blade.php' => 'تخطيط القائمة الجانبية'
            ];

            foreach ($files as $file => $description) {
                $exists = file_exists('../' . $file);
                echo '<div class="route-item ' . ($exists ? '' : 'error') . '">';
                echo '<div>';
                echo '<div class="route-name">' . $file . '</div>';
                echo '<div class="route-url">' . $description . '</div>';
                echo '</div>';
                echo '<div>';
                echo '<span class="status ' . ($exists ? 'success' : 'error') . '">';
                echo $exists ? 'موجود' : 'مفقود';
                echo '</span>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>

        <!-- Solutions -->
        <div class="section">
            <div class="section-title">
                <i class="fas fa-tools"></i>
                خطوات حل المشكلة
            </div>
            
            <div class="error-box">
                <h4>إذا لم يظهر الرابط في القائمة الجانبية، جرب هذه الخطوات بالترتيب:</h4>
            </div>

            <div class="route-item">
                <div>
                    <div class="route-name">1. مسح كاش Laravel</div>
                    <div class="route-url">php artisan route:clear && php artisan cache:clear && php artisan view:clear</div>
                </div>
                <div>
                    <span class="status error">مطلوب</span>
                </div>
            </div>

            <div class="route-item">
                <div>
                    <div class="route-name">2. إعادة تشغيل الخادم</div>
                    <div class="route-url">php artisan serve</div>
                </div>
                <div>
                    <span class="status error">مطلوب</span>
                </div>
            </div>

            <div class="route-item">
                <div>
                    <div class="route-name">3. مسح كاش المتصفح</div>
                    <div class="route-url">Ctrl+F5 أو Cmd+Shift+R</div>
                </div>
                <div>
                    <span class="status error">مطلوب</span>
                </div>
            </div>

            <div class="route-item">
                <div>
                    <div class="route-name">4. التأكد من تسجيل الدخول كمستأجر</div>
                    <div class="route-url">وليس كمدير عام</div>
                </div>
                <div>
                    <span class="status error">مهم</span>
                </div>
            </div>

            <div class="route-item">
                <div>
                    <div class="route-name">5. فحص الأخطاء في سجل Laravel</div>
                    <div class="route-url">storage/logs/laravel.log</div>
                </div>
                <div>
                    <span class="status error">تحقق</span>
                </div>
            </div>
        </div>

        <!-- Direct Test Links -->
        <div class="section">
            <div class="section-title">
                <i class="fas fa-link"></i>
                روابط الاختبار المباشر
            </div>
            
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="/tenant/system-guide/new-tenant-guide" class="btn" target="_blank">
                    <i class="fas fa-rocket"></i> دليل المستأجر الجديد
                </a>
                <a href="/tenant/system-guide/" class="btn" target="_blank">
                    <i class="fas fa-home"></i> الصفحة الرئيسية
                </a>
                <a href="/tenant/system-guide/faq" class="btn" target="_blank">
                    <i class="fas fa-question-circle"></i> الأسئلة الشائعة
                </a>
                <a href="/دليل_المستأجر_التفاعلي.html" class="btn" target="_blank">
                    <i class="fas fa-file-alt"></i> الدليل التفاعلي
                </a>
            </div>
        </div>

        <div class="info-box" style="margin-top: 30px;">
            <h4>💡 ملاحظة مهمة:</h4>
            <p>إذا كانت الروابط المباشرة تعمل ولكن لا تظهر في القائمة الجانبية، فالمشكلة في الكاش أو في تحديث الصفحة.</p>
            <p>تأكد من تسجيل الدخول كمستأجر وليس كمدير عام.</p>
        </div>
    </div>
</body>
</html>
