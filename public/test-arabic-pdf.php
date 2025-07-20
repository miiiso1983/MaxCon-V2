<?php

/**
 * Test Arabic PDF Generation
 * 
 * اختبار إنشاء PDF بالعربية
 */

require_once '../vendor/autoload.php';

use Mpdf\Mpdf;

echo "🧪 اختبار إنشاء PDF بالعربية...\n";

try {
    // Initialize mPDF with Arabic support
    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'orientation' => 'P',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
        'default_font_size' => 12,
        'default_font' => 'dejavusans',
        'dir' => 'rtl',
        'autoScriptToLang' => true,
        'autoLangToFont' => true,
    ]);

    echo "✅ تم تهيئة mPDF بنجاح\n";

    // Test HTML content with Arabic
    $html = '
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>اختبار PDF بالعربية</title>
        <style>
            body {
                font-family: dejavusans, sans-serif;
                font-size: 12pt;
                line-height: 1.6;
                direction: rtl;
                text-align: right;
            }
            .title {
                font-size: 18pt;
                font-weight: bold;
                color: #667eea;
                text-align: center;
                margin-bottom: 20px;
                border-bottom: 2px solid #667eea;
                padding-bottom: 10px;
            }
            .section {
                margin-bottom: 20px;
            }
            .section-title {
                font-size: 14pt;
                font-weight: bold;
                color: #2d3748;
                margin-bottom: 10px;
            }
            .content {
                font-size: 12pt;
                line-height: 1.8;
                text-align: justify;
            }
            .list {
                margin: 10px 0 10px 20px;
            }
            .list li {
                margin-bottom: 5px;
            }
            .info-box {
                background-color: #ebf8ff;
                border: 2px solid #63b3ed;
                border-radius: 8px;
                padding: 15px;
                margin: 20px 0;
            }
            .warning-box {
                background-color: #fef5e7;
                border: 2px solid #f6ad55;
                border-radius: 8px;
                padding: 15px;
                margin: 20px 0;
            }
        </style>
    </head>
    <body>
        <div class="title">اختبار إنشاء PDF بالعربية - MaxCon ERP</div>
        
        <div class="section">
            <div class="section-title">مرحباً بكم في نظام MaxCon ERP</div>
            <div class="content">
                هذا اختبار لإنشاء ملف PDF يدعم اللغة العربية بشكل كامل. النظام يستخدم مكتبة mPDF 
                التي توفر دعماً ممتازاً للغة العربية والكتابة من اليمين إلى اليسار.
            </div>
        </div>

        <div class="section">
            <div class="section-title">المميزات المدعومة:</div>
            <ul class="list">
                <li>الكتابة من اليمين إلى اليسار (RTL)</li>
                <li>دعم الخطوط العربية</li>
                <li>تنسيق النصوص والفقرات</li>
                <li>الجداول والقوائم</li>
                <li>الألوان والتصميم</li>
                <li>الرؤوس والتذييلات</li>
            </ul>
        </div>

        <div class="info-box">
            <strong>معلومة مهمة:</strong><br>
            تم إنشاء هذا الملف باستخدام مكتبة mPDF مع إعدادات محسنة للغة العربية. 
            النظام يدعم جميع أنواع النصوص العربية والتنسيقات المختلفة.
        </div>

        <div class="section">
            <div class="section-title">اختبار النصوص المختلطة:</div>
            <div class="content">
                يمكن للنظام التعامل مع النصوص المختلطة مثل: MaxCon ERP نظام إدارة موارد المؤسسات.
                كما يدعم الأرقام العربية ١٢٣٤٥٦٧٨٩٠ والإنجليزية 1234567890.
            </div>
        </div>

        <div class="warning-box">
            <strong>تنبيه:</strong><br>
            إذا كان النص العربي يظهر بشكل صحيح في هذا الملف، فإن النظام جاهز لإنشاء 
            دليل المستخدم وجميع التقارير بالعربية.
        </div>

        <div class="section">
            <div class="section-title">معلومات تقنية:</div>
            <div class="content">
                <strong>المكتبة:</strong> mPDF v8.2+<br>
                <strong>الترميز:</strong> UTF-8<br>
                <strong>الاتجاه:</strong> RTL (من اليمين لليسار)<br>
                <strong>الخط الافتراضي:</strong> DejaVu Sans<br>
                <strong>تاريخ الإنشاء:</strong> ' . date('Y-m-d H:i:s') . '<br>
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px; padding: 20px; border-top: 1px solid #e2e8f0;">
            <strong>© ' . date('Y') . ' MaxCon ERP - جميع الحقوق محفوظة</strong><br>
            www.maxcon.app
        </div>
    </body>
    </html>';

    // Set document properties
    $mpdf->SetTitle('اختبار PDF بالعربية - MaxCon ERP');
    $mpdf->SetAuthor('MaxCon ERP System');
    $mpdf->SetSubject('اختبار دعم اللغة العربية في PDF');

    echo "✅ تم إنشاء محتوى HTML\n";

    // Write HTML content
    $mpdf->WriteHTML($html);
    echo "✅ تم كتابة المحتوى في PDF\n";

    // Save PDF
    $filename = 'test_arabic_pdf_' . date('Y-m-d_H-i-s') . '.pdf';
    $path = __DIR__ . '/' . $filename;
    
    $mpdf->Output($path, 'F');
    echo "✅ تم حفظ الملف: {$filename}\n";

    // Also output to browser for immediate viewing
    $mpdf->Output($filename, 'I');

} catch (\Exception $e) {
    echo "❌ خطأ في إنشاء PDF: " . $e->getMessage() . "\n";
    echo "تفاصيل الخطأ: " . $e->getTraceAsString() . "\n";
    
    echo "\n🔧 حلول مقترحة:\n";
    echo "1. تأكد من تثبيت مكتبة mPDF\n";
    echo "2. تحقق من صلاحيات الكتابة\n";
    echo "3. تأكد من وجود الخطوط المطلوبة\n";
    echo "4. جرب تشغيل: composer require mpdf/mpdf\n";
}

echo "\n🎯 انتهى الاختبار\n";
echo "إذا تم إنشاء الملف بنجاح، فإن النظام جاهز لإنشاء دليل المستخدم بالعربية.\n";

?>
