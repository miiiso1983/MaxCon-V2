<?php

/**
 * Fix Arabic PDF Font Issues
 * 
 * إصلاح مشاكل الخطوط العربية في PDF
 */

require_once '../vendor/autoload.php';

use Mpdf\Mpdf;

echo "🔧 إصلاح مشاكل الخطوط العربية في PDF...\n\n";

try {
    // Test 1: Basic Arabic text with different font configurations
    echo "🧪 اختبار 1: إعدادات الخطوط الأساسية...\n";
    
    $configurations = [
        'dejavusans' => [
            'default_font' => 'dejavusans',
            'title' => 'DejaVu Sans (الافتراضي)'
        ],
        'arial' => [
            'default_font' => 'arial',
            'title' => 'Arial Unicode'
        ],
        'sans-serif' => [
            'default_font' => 'sans-serif',
            'title' => 'Sans Serif'
        ]
    ];
    
    foreach ($configurations as $key => $config) {
        echo "  - اختبار {$config['title']}...\n";
        
        try {
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 16,
                'margin_bottom' => 16,
                'default_font_size' => 14,
                'default_font' => $config['default_font'],
                'dir' => 'rtl',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
                'useSubstitutions' => true,
                'debug' => false,
            ]);
            
            $html = '
            <!DOCTYPE html>
            <html lang="ar" dir="rtl">
            <head>
                <meta charset="UTF-8">
                <title>اختبار الخط العربي</title>
                <style>
                    body {
                        font-family: ' . $config['default_font'] . ', sans-serif;
                        font-size: 14pt;
                        direction: rtl;
                        text-align: right;
                    }
                    .title {
                        font-size: 18pt;
                        font-weight: bold;
                        margin-bottom: 20px;
                        text-align: center;
                    }
                    .content {
                        line-height: 1.8;
                        margin-bottom: 15px;
                    }
                </style>
            </head>
            <body>
                <div class="title">اختبار الخط العربي - ' . $config['title'] . '</div>
                <div class="content">
                    مرحباً بكم في نظام MaxCon ERP. هذا اختبار للتأكد من عرض النص العربي بشكل صحيح.
                </div>
                <div class="content">
                    النظام يدعم جميع الميزات التالية:
                    <br>• إدارة المبيعات والعملاء
                    <br>• إدارة المخزون والمنتجات  
                    <br>• النظام المحاسبي المتكامل
                    <br>• إدارة الموارد البشرية
                    <br>• التقارير والتحليلات
                </div>
                <div class="content">
                    الأرقام العربية: ١٢٣٤٥٦٧٨٩٠
                    <br>الأرقام الإنجليزية: 1234567890
                    <br>النص المختلط: MaxCon ERP نظام إدارة موارد المؤسسات
                </div>
            </body>
            </html>';
            
            $mpdf->WriteHTML($html);
            
            $filename = "test_arabic_{$key}_" . date('Y-m-d_H-i-s') . '.pdf';
            $path = __DIR__ . '/' . $filename;
            
            $mpdf->Output($path, 'F');
            echo "    ✅ تم إنشاء: {$filename}\n";
            
        } catch (\Exception $e) {
            echo "    ❌ فشل: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🔧 اختبار 2: إعدادات محسنة للعربية...\n";
    
    // Enhanced configuration for Arabic
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
        'useSubstitutions' => true,
        'debug' => false,
        'fontDir' => [
            __DIR__ . '/../storage/fonts/',
            __DIR__ . '/fonts/',
        ],
        'tempDir' => sys_get_temp_dir(),
    ]);
    
    // Set document properties
    $mpdf->SetTitle('اختبار الخط العربي المحسن');
    $mpdf->SetAuthor('MaxCon ERP');
    $mpdf->SetCreator('mPDF');
    
    $enhancedHtml = '
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>دليل المستخدم - MaxCon ERP</title>
        <style>
            @page {
                margin: 15mm;
                margin-header: 9mm;
                margin-footer: 9mm;
            }
            body {
                font-family: dejavusans, arial, sans-serif;
                font-size: 12pt;
                line-height: 1.6;
                color: #333;
                direction: rtl;
                text-align: right;
            }
            .cover {
                text-align: center;
                padding: 50px 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-radius: 10px;
                margin-bottom: 30px;
            }
            .title {
                font-size: 24pt;
                font-weight: bold;
                margin-bottom: 20px;
            }
            .subtitle {
                font-size: 16pt;
                margin-bottom: 30px;
                opacity: 0.9;
            }
            .section {
                margin-bottom: 25px;
                padding: 20px;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
            }
            .section-title {
                font-size: 16pt;
                font-weight: bold;
                color: #2d3748;
                margin-bottom: 15px;
                border-bottom: 2px solid #667eea;
                padding-bottom: 5px;
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
                margin-bottom: 8px;
            }
            .info-box {
                background-color: #ebf8ff;
                border: 2px solid #63b3ed;
                border-radius: 8px;
                padding: 15px;
                margin: 20px 0;
            }
        </style>
    </head>
    <body>
        <div class="cover">
            <div class="title">دليل المستخدم</div>
            <div class="subtitle">نظام MaxCon ERP</div>
            <div>نظام إدارة موارد المؤسسات الشامل</div>
        </div>
        
        <div class="section">
            <div class="section-title">مرحباً بكم في نظام MaxCon ERP</div>
            <div class="content">
                نظام MaxCon ERP هو حل متكامل لإدارة موارد المؤسسات، مصمم خصيصاً للشركات العربية 
                مع دعم كامل للغة العربية والمتطلبات المحلية.
            </div>
        </div>
        
        <div class="section">
            <div class="section-title">المميزات الرئيسية</div>
            <ul class="list">
                <li>إدارة شاملة للمبيعات والعملاء</li>
                <li>نظام مخزون متقدم مع تتبع المنتجات</li>
                <li>نظام محاسبي متكامل ومتوافق مع المعايير المحلية</li>
                <li>إدارة الموارد البشرية والرواتب</li>
                <li>تقارير تفاعلية وتحليلات ذكية</li>
                <li>دعم كامل للغة العربية والعملات المحلية</li>
            </ul>
        </div>
        
        <div class="info-box">
            <strong>ملاحظة مهمة:</strong><br>
            إذا كان النص العربي يظهر بوضوح في هذا الملف، فإن النظام يعمل بشكل صحيح.
            إذا ظهرت علامات استفهام (؟؟؟) بدلاً من النص العربي، فهناك مشكلة في إعدادات الخط.
        </div>
        
        <div class="section">
            <div class="section-title">اختبار النصوص المختلطة</div>
            <div class="content">
                النظام يدعم النصوص المختلطة مثل: MaxCon ERP نظام إدارة موارد المؤسسات.
                <br>الأرقام العربية: ١٢٣٤٥٦٧٨٩٠
                <br>الأرقام الإنجليزية: 1234567890
                <br>التاريخ: ' . date('Y/m/d') . '
                <br>الوقت: ' . date('H:i:s') . '
            </div>
        </div>
    </body>
    </html>';
    
    $mpdf->WriteHTML($enhancedHtml);
    
    $filename = 'enhanced_arabic_test_' . date('Y-m-d_H-i-s') . '.pdf';
    $path = __DIR__ . '/' . $filename;
    
    $mpdf->Output($path, 'F');
    echo "✅ تم إنشاء الملف المحسن: {$filename}\n";
    
    echo "\n📋 تعليمات الاختبار:\n";
    echo "1. افتح الملفات المُنشأة أعلاه\n";
    echo "2. تحقق من عرض النص العربي بوضوح\n";
    echo "3. إذا ظهرت علامات استفهام، جرب الحلول التالية:\n";
    echo "   - تأكد من تثبيت خطوط عربية على الخادم\n";
    echo "   - تحديث إعدادات mPDF\n";
    echo "   - استخدام خطوط ويب بدلاً من خطوط النظام\n";
    
    echo "\n🔧 الحل المقترح:\n";
    echo "إذا استمرت المشكلة، سنقوم بتحديث SystemGuideController لاستخدام الإعدادات المحسنة.\n";
    
} catch (\Exception $e) {
    echo "❌ خطأ عام: " . $e->getMessage() . "\n";
    echo "تفاصيل: " . $e->getTraceAsString() . "\n";
}

echo "\n✅ انتهى الاختبار\n";

?>
