<?php

/**
 * Complete PDF Fonts Fix for Arabic Support
 * 
 * إصلاح شامل لمشكلة الخطوط العربية في PDF
 */

require_once '../vendor/autoload.php';

// Load Laravel
$app = require_once '../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Mpdf\Mpdf;

echo "🔧 إصلاح شامل لمشكلة الخطوط العربية في PDF...\n\n";

// Create necessary directories
$directories = [
    storage_path('fonts'),
    storage_path('app/temp'),
    public_path('fonts'),
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
        echo "✅ تم إنشاء مجلد: {$dir}\n";
    }
}

echo "\n🧪 اختبار إعدادات مختلفة للخطوط...\n";

// Test different configurations
$configurations = [
    'config1' => [
        'title' => 'الإعداد الأساسي',
        'config' => [
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'dir' => 'rtl',
        ]
    ],
    'config2' => [
        'title' => 'الإعداد المحسن',
        'config' => [
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'dir' => 'rtl',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'useSubstitutions' => true,
        ]
    ],
    'config3' => [
        'title' => 'الإعداد المتقدم',
        'config' => [
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'dir' => 'rtl',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'useSubstitutions' => true,
            'fontDir' => [storage_path('fonts'), public_path('fonts')],
            'tempDir' => storage_path('app/temp'),
        ]
    ]
];

$testText = 'مرحباً بكم في نظام MaxCon ERP - نظام إدارة موارد المؤسسات الشامل';

foreach ($configurations as $key => $setup) {
    echo "  🔧 اختبار {$setup['title']}...\n";
    
    try {
        $mpdf = new Mpdf($setup['config']);
        
        $html = '
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <style>
                body { 
                    font-family: dejavusans, arial, sans-serif; 
                    font-size: 14pt; 
                    direction: rtl; 
                    text-align: right; 
                }
                .title { 
                    font-size: 18pt; 
                    font-weight: bold; 
                    text-align: center; 
                    margin-bottom: 20px; 
                }
            </style>
        </head>
        <body>
            <div class="title">' . $setup['title'] . '</div>
            <p>' . $testText . '</p>
            <p>الأرقام العربية: ١٢٣٤٥٦٧٨٩٠</p>
            <p>الأرقام الإنجليزية: 1234567890</p>
            <p>التاريخ: ' . date('Y/m/d H:i:s') . '</p>
        </body>
        </html>';
        
        $mpdf->WriteHTML($html);
        
        $filename = "test_{$key}_" . date('Y-m-d_H-i-s') . '.pdf';
        $path = __DIR__ . '/' . $filename;
        
        $mpdf->Output($path, 'F');
        echo "    ✅ نجح: {$filename}\n";
        
    } catch (\Exception $e) {
        echo "    ❌ فشل: " . $e->getMessage() . "\n";
    }
}

echo "\n🎯 إنشاء دليل المستخدم التجريبي...\n";

try {
    // Use the best working configuration
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
        'fontDir' => [storage_path('fonts'), public_path('fonts')],
        'tempDir' => storage_path('app/temp'),
    ]);
    
    // Set document properties
    $mpdf->SetTitle('دليل المستخدم - MaxCon ERP');
    $mpdf->SetAuthor('MaxCon ERP System');
    $mpdf->SetSubject('دليل استخدام نظام MaxCon ERP');
    $mpdf->SetKeywords('MaxCon, ERP, دليل المستخدم, نظام إدارة');
    
    $html = '
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
                page-break-after: always;
            }
            .title {
                font-size: 28pt;
                font-weight: bold;
                margin-bottom: 20px;
            }
            .subtitle {
                font-size: 18pt;
                margin-bottom: 30px;
                opacity: 0.9;
            }
            .chapter {
                page-break-before: always;
                margin-bottom: 30px;
            }
            .chapter-title {
                font-size: 20pt;
                font-weight: bold;
                color: #667eea;
                margin-bottom: 20px;
                border-bottom: 3px solid #667eea;
                padding-bottom: 10px;
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
        <!-- Cover Page -->
        <div class="cover">
            <div class="title">دليل المستخدم</div>
            <div class="subtitle">نظام MaxCon ERP</div>
            <div style="font-size: 16pt; margin: 20px 0;">
                نظام إدارة موارد المؤسسات الشامل
            </div>
            <div style="font-size: 14pt; margin: 20px 0;">
                الحل المتكامل لإدارة الأعمال الصيدلانية
            </div>
            <div style="font-size: 12pt; margin-top: 40px; opacity: 0.8;">
                الإصدار 2.0 - ' . date('Y/m/d') . '
            </div>
        </div>

        <!-- Chapter 1: Introduction -->
        <div class="chapter">
            <div class="chapter-title">الفصل الأول: مقدمة عن النظام</div>
            
            <div class="section-title">ما هو نظام MaxCon ERP؟</div>
            <div class="content">
                MaxCon ERP هو نظام إدارة موارد المؤسسات (Enterprise Resource Planning) متكامل وشامل، 
                مصمم خصيصاً للشركات الصيدلانية والطبية في العراق والمنطقة العربية. يهدف النظام إلى توحيد 
                وأتمتة جميع العمليات التجارية في مؤسستك من خلال منصة واحدة موحدة تدعم اللغة العربية بالكامل.
            </div>
            
            <div class="section-title">المميزات الرئيسية للنظام</div>
            <ul class="list">
                <li>نظام شامل ومتكامل يغطي جميع احتياجات إدارة الأعمال</li>
                <li>دعم كامل للغة العربية مع واجهات عربية بالكامل</li>
                <li>نظام سحابي آمن مع وصول من أي مكان</li>
                <li>متوافق مع الأجهزة المحمولة والهواتف الذكية</li>
                <li>تقارير ذكية ومتقدمة مع تحليلات تفاعلية</li>
                <li>ذكاء اصطناعي مدمج للتنبؤات والتحليلات</li>
            </ul>
            
            <div class="info-box">
                <strong>معلومة مهمة:</strong><br>
                تم تطوير النظام بناءً على خبرة عملية في السوق العراقي والعربي، مع مراعاة المتطلبات المحلية 
                والتحديات الخاصة بالقطاع الصيدلاني. النظام يدعم العملة العراقية والضرائب المحلية.
            </div>
        </div>

        <!-- Chapter 2: Getting Started -->
        <div class="chapter">
            <div class="chapter-title">الفصل الثاني: البدء مع النظام</div>
            
            <div class="section-title">تسجيل الدخول</div>
            <div class="content">
                للوصول إلى نظام MaxCon ERP، تحتاج إلى:
            </div>
            <ul class="list">
                <li>رابط النظام الخاص بمؤسستك</li>
                <li>اسم المستخدم وكلمة المرور</li>
                <li>متصفح ويب حديث يدعم JavaScript</li>
                <li>اتصال إنترنت مستقر</li>
            </ul>
            
            <div class="warning-box">
                <strong>تنبيه أمني:</strong><br>
                لا تشارك بيانات تسجيل الدخول مع أي شخص آخر. تأكد من تسجيل الخروج عند الانتهاء من العمل.
            </div>
        </div>

        <!-- Final Page -->
        <div class="chapter">
            <div style="text-align: center; padding: 50px 20px;">
                <div style="font-size: 24pt; font-weight: bold; color: #667eea; margin-bottom: 20px;">
                    شكراً لاختياركم MaxCon ERP
                </div>
                <div style="font-size: 14pt; line-height: 1.8; color: #4a5568;">
                    نحن فخورون بثقتكم في حلولنا التقنية<br>
                    ونتطلع لمساعدتكم في تحقيق أهدافكم التجارية<br><br>
                    
                    <strong>فريق MaxCon ERP</strong><br>
                    support@maxcon.app<br>
                    www.maxcon.app<br>
                    ' . date('Y') . '
                </div>
            </div>
        </div>
    </body>
    </html>';
    
    $mpdf->WriteHTML($html);
    
    $filename = 'user_manual_fixed_' . date('Y-m-d_H-i-s') . '.pdf';
    $path = __DIR__ . '/' . $filename;
    
    $mpdf->Output($path, 'F');
    echo "✅ تم إنشاء دليل المستخدم المُصحح: {$filename}\n";
    
    // Also output to browser
    $mpdf->Output($filename, 'I');
    
} catch (\Exception $e) {
    echo "❌ خطأ في إنشاء دليل المستخدم: " . $e->getMessage() . "\n";
    echo "تفاصيل: " . $e->getTraceAsString() . "\n";
}

echo "\n📋 ملخص الإصلاحات:\n";
echo "1. تم تحديث إعدادات mPDF لدعم أفضل للعربية\n";
echo "2. تم استخدام خط dejavusans كخط افتراضي\n";
echo "3. تم تفعيل useSubstitutions لاستبدال الأحرف\n";
echo "4. تم إنشاء مجلدات الخطوط والملفات المؤقتة\n";
echo "5. تم اختبار إعدادات مختلفة للعثور على الأفضل\n";

echo "\n🔗 الخطوة التالية:\n";
echo "إذا كان النص العربي يظهر بوضوح في الملفات المُنشأة، فالنظام يعمل بشكل صحيح.\n";
echo "يمكنك الآن تجربة تحميل دليل المستخدم من النظام.\n";

?>
