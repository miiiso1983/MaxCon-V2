<?php

/**
 * Quick Fix for Arabic PDF using TCPDF
 * 
 * حل سريع لمشكلة PDF العربي باستخدام TCPDF
 */

require_once '../vendor/autoload.php';

// Load Laravel
$app = require_once '../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use TCPDF;

echo "🔧 حل سريع لمشكلة PDF العربي باستخدام TCPDF...\n\n";

try {
    // Create new TCPDF document
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    
    // Set document information
    $pdf->SetCreator('MaxCon ERP');
    $pdf->SetAuthor('MaxCon ERP System');
    $pdf->SetTitle('دليل المستخدم - MaxCon ERP');
    $pdf->SetSubject('دليل استخدام نظام MaxCon ERP');
    $pdf->SetKeywords('MaxCon, ERP, دليل المستخدم');
    
    // Set default header data
    $pdf->SetHeaderData('', 0, 'دليل المستخدم - MaxCon ERP', 'نظام إدارة موارد المؤسسات');
    
    // Set header and footer fonts
    $pdf->setHeaderFont(Array('dejavusans', '', 10));
    $pdf->setFooterFont(Array('dejavusans', '', 8));
    
    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont('dejavusans');
    
    // Set margins
    $pdf->SetMargins(15, 27, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    
    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 25);
    
    // Set image scale factor
    $pdf->setImageScale(1.25);
    
    // Set font
    $pdf->SetFont('dejavusans', '', 12);
    
    // Set language direction
    $pdf->setRTL(true);
    
    // Add a page
    $pdf->AddPage();
    
    // Arabic text content
    $html = '
    <style>
        body {
            font-family: dejavusans;
            font-size: 12pt;
            direction: rtl;
            text-align: right;
        }
        .title {
            font-size: 20pt;
            font-weight: bold;
            text-align: center;
            color: #667eea;
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 16pt;
            font-weight: bold;
            color: #2d3748;
            margin: 20px 0 10px 0;
        }
        .content {
            font-size: 12pt;
            line-height: 1.8;
            margin-bottom: 15px;
            text-align: justify;
        }
        .list {
            margin: 10px 0 20px 20px;
        }
        .info-box {
            background-color: #ebf8ff;
            border: 2px solid #63b3ed;
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
        }
    </style>
    
    <div class="title">دليل المستخدم - نظام MaxCon ERP</div>
    
    <div class="section-title">مرحباً بكم في نظام MaxCon ERP</div>
    <div class="content">
        نظام MaxCon ERP هو حل متكامل لإدارة موارد المؤسسات، مصمم خصيصاً للشركات العربية 
        مع دعم كامل للغة العربية والمتطلبات المحلية.
    </div>
    
    <div class="section-title">المميزات الرئيسية</div>
    <ul class="list">
        <li>إدارة شاملة للمبيعات والعملاء</li>
        <li>نظام مخزون متقدم مع تتبع المنتجات</li>
        <li>نظام محاسبي متكامل ومتوافق مع المعايير المحلية</li>
        <li>إدارة الموارد البشرية والرواتب</li>
        <li>تقارير تفاعلية وتحليلات ذكية</li>
        <li>دعم كامل للغة العربية والعملات المحلية</li>
    </ul>
    
    <div class="info-box">
        <strong>معلومة مهمة:</strong><br>
        إذا كان النص العربي يظهر بوضوح في هذا الملف، فإن النظام يعمل بشكل صحيح.
        TCPDF يوفر دعماً ممتازاً للغة العربية والكتابة من اليمين إلى اليسار.
    </div>
    
    <div class="section-title">البدء مع النظام</div>
    <div class="content">
        للوصول إلى نظام MaxCon ERP، تحتاج إلى:
    </div>
    <ul class="list">
        <li>رابط النظام الخاص بمؤسستك</li>
        <li>اسم المستخدم وكلمة المرور</li>
        <li>متصفح ويب حديث يدعم JavaScript</li>
        <li>اتصال إنترنت مستقر</li>
    </ul>
    
    <div class="section-title">اختبار النصوص المختلطة</div>
    <div class="content">
        النظام يدعم النصوص المختلطة مثل: MaxCon ERP نظام إدارة موارد المؤسسات.
        <br>الأرقام العربية: ١٢٣٤٥٦٧٨٩٠
        <br>الأرقام الإنجليزية: 1234567890
        <br>التاريخ: ' . date('Y/m/d') . '
        <br>الوقت: ' . date('H:i:s') . '
    </div>
    
    <div style="text-align: center; margin-top: 40px; padding: 20px; border-top: 1px solid #e2e8f0;">
        <strong>© ' . date('Y') . ' MaxCon ERP - جميع الحقوق محفوظة</strong><br>
        www.maxcon.app
    </div>';
    
    // Print text using writeHTMLCell()
    $pdf->writeHTML($html, true, false, true, false, '');
    
    // Add a new page for more content
    $pdf->AddPage();
    
    $pdf->writeHTML('
    <div class="title">وحدات النظام</div>
    
    <div class="section-title">وحدة إدارة المبيعات</div>
    <div class="content">
        تتيح لك هذه الوحدة إدارة جميع عمليات المبيعات من إنشاء العروض وحتى تحصيل المدفوعات.
    </div>
    <ul class="list">
        <li>إدارة العملاء والموردين</li>
        <li>إنشاء عروض الأسعار والفواتير</li>
        <li>متابعة المدفوعات والمستحقات</li>
        <li>تقارير المبيعات التفصيلية</li>
    </ul>
    
    <div class="section-title">وحدة إدارة المخزون</div>
    <div class="content">
        نظام متكامل لإدارة المخزون مع تتبع دقيق للمنتجات وحركات المخزون.
    </div>
    <ul class="list">
        <li>إدارة المنتجات والفئات</li>
        <li>تتبع حركات المخزون</li>
        <li>إدارة المستودعات المتعددة</li>
        <li>تقارير المخزون والجرد</li>
    </ul>
    
    <div class="info-box">
        <strong>نصيحة:</strong><br>
        استخدم خاصية البحث السريع للعثور على المنتجات والعملاء بسهولة.
        يمكنك البحث باللغة العربية أو الإنجليزية.
    </div>', true, false, true, false, '');
    
    // Save PDF file
    $filename = 'user_manual_tcpdf_' . date('Y-m-d_H-i-s') . '.pdf';
    $filepath = __DIR__ . '/' . $filename;
    
    $pdf->Output($filepath, 'F');
    echo "✅ تم إنشاء الملف بنجاح: {$filename}\n";
    
    // Also output to browser
    $pdf->Output($filename, 'I');
    
} catch (\Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "تفاصيل: " . $e->getTraceAsString() . "\n";
    
    echo "\n🔧 جرب الحل البديل...\n";
    
    // Fallback: Simple HTML to PDF
    try {
        $html = '<!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <title>دليل المستخدم</title>
            <style>
                body { font-family: Arial, sans-serif; direction: rtl; text-align: right; }
                .title { font-size: 24px; font-weight: bold; text-align: center; margin-bottom: 20px; }
                .content { font-size: 14px; line-height: 1.6; margin-bottom: 15px; }
            </style>
        </head>
        <body>
            <div class="title">دليل المستخدم - MaxCon ERP</div>
            <div class="content">مرحباً بكم في نظام MaxCon ERP</div>
            <div class="content">هذا اختبار بسيط للنص العربي في PDF</div>
        </body>
        </html>';
        
        file_put_contents(__DIR__ . '/simple_arabic_test.html', $html);
        echo "✅ تم إنشاء ملف HTML بديل: simple_arabic_test.html\n";
        
    } catch (\Exception $e2) {
        echo "❌ فشل الحل البديل أيضاً: " . $e2->getMessage() . "\n";
    }
}

echo "\n📋 ملخص الاختبار:\n";
echo "1. تم استخدام TCPDF بدلاً من mPDF\n";
echo "2. تم تفعيل RTL (الكتابة من اليمين لليسار)\n";
echo "3. تم استخدام خط dejavusans\n";
echo "4. تم إنشاء محتوى عربي شامل\n";

echo "\n🔗 الخطوة التالية:\n";
echo "إذا كان النص العربي يظهر بوضوح في الملف المُنشأ، فسنقوم بتحديث النظام لاستخدام TCPDF.\n";
echo "إذا استمرت المشكلة، فقد تكون المشكلة في إعدادات الخادم أو الخطوط المثبتة.\n";

?>
