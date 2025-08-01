<?php

/**
 * Generate Tenant Getting Started Guide PDF
 * 
 * إنشاء دليل البدء للمستأجر الجديد
 */

require_once '../vendor/autoload.php';

use Mpdf\Mpdf;

echo "📋 إنشاء دليل المستأجر الجديد...\n";

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
        'useSubstitutions' => true,
        'debug' => false,
    ]);

    // Set document properties
    $mpdf->SetTitle('دليل المستأجر الجديد - MaxCon ERP');
    $mpdf->SetAuthor('MaxCon ERP System');
    $mpdf->SetSubject('دليل البدء للمستأجرين الجدد');
    $mpdf->SetKeywords('MaxCon, ERP, دليل المستأجر, البدء');

    $html = '
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>دليل المستأجر الجديد - MaxCon ERP</title>
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
                border-bottom: 2px solid #667eea;
                padding-bottom: 10px;
            }
            .section-title {
                font-size: 16pt;
                font-weight: bold;
                color: #4a5568;
                margin: 25px 0 15px 0;
            }
            .step-number {
                background: #667eea;
                color: white;
                border-radius: 50%;
                width: 30px;
                height: 30px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                margin-left: 10px;
            }
            .code-block {
                background: #f7fafc;
                border: 1px solid #e2e8f0;
                border-radius: 5px;
                padding: 15px;
                margin: 10px 0;
                font-family: monospace;
                font-size: 10pt;
            }
            .info-box {
                background: #e6fffa;
                border-right: 4px solid #38b2ac;
                padding: 15px;
                margin: 15px 0;
                border-radius: 5px;
            }
            .warning-box {
                background: #fffbeb;
                border-right: 4px solid #f59e0b;
                padding: 15px;
                margin: 15px 0;
                border-radius: 5px;
            }
            .checklist {
                list-style: none;
                padding-right: 0;
            }
            .checklist li {
                margin: 8px 0;
                padding-right: 25px;
                position: relative;
            }
            .checklist li:before {
                content: "✓";
                position: absolute;
                right: 0;
                color: #10b981;
                font-weight: bold;
            }
            .step-list {
                counter-reset: step-counter;
                list-style: none;
                padding-right: 0;
            }
            .step-list li {
                counter-increment: step-counter;
                margin: 15px 0;
                padding-right: 40px;
                position: relative;
            }
            .step-list li:before {
                content: counter(step-counter);
                position: absolute;
                right: 0;
                background: #667eea;
                color: white;
                border-radius: 50%;
                width: 25px;
                height: 25px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 10pt;
            }
        </style>
    </head>
    <body>
        <!-- Cover Page -->
        <div class="cover">
            <div class="title">🚀 دليل المستأجر الجديد</div>
            <div class="subtitle">خطوات البدء في MaxCon ERP</div>
            <div style="font-size: 14pt; margin-top: 40px;">
                الدليل الشامل للاستفادة القصوى من نظام إدارة الأعمال
            </div>
            <div style="font-size: 12pt; margin-top: 30px; opacity: 0.8;">
                الإصدار 1.0 - ' . date('Y') . '
            </div>
        </div>

        <!-- Chapter 1: Basic Steps -->
        <div class="chapter">
            <div class="chapter-title">📋 الخطوات الأساسية للبدء</div>
            
            <div class="section-title">
                <span class="step-number">1</span>
                تسجيل الدخول الأول
            </div>
            <div class="code-block">
URL: https://your-tenant.maxcon.app/login
Email: admin@your-tenant.com
Password: [كلمة المرور المرسلة]
            </div>
            
            <div class="section-title">
                <span class="step-number">2</span>
                إعداد الملف الشخصي
            </div>
            <ul class="checklist">
                <li>تحديث بيانات الشركة</li>
                <li>رفع شعار الشركة</li>
                <li>تحديد العملة الأساسية</li>
                <li>إعداد معلومات الاتصال</li>
            </ul>
            
            <div class="section-title">
                <span class="step-number">3</span>
                إعداد المستخدمين والصلاحيات
            </div>
            <div class="code-block">
الأدوار المتاحة:
• tenant-admin: إدارة كاملة للمستأجر
• manager: إدارة محدودة  
• employee: موظف عادي
• customer: عميل
            </div>
        </div>

        <!-- Chapter 2: Detailed Setup -->
        <div class="chapter">
            <div class="chapter-title">🏗️ خطوات الإعداد التفصيلية</div>
            
            <div class="section-title">المرحلة الأولى: الإعدادات الأساسية</div>
            
            <ol class="step-list">
                <li>
                    <strong>إعداد بيانات الشركة</strong>
                    <ul>
                        <li>اسم الشركة والعنوان</li>
                        <li>أرقام الهواتف والفاكس</li>
                        <li>البريد الإلكتروني الرسمي</li>
                        <li>الرقم الضريبي والسجل التجاري</li>
                    </ul>
                </li>
                
                <li>
                    <strong>إعداد العملة والمحاسبة</strong>
                    <ul>
                        <li>تحديد العملة الأساسية (IQD/USD/EUR)</li>
                        <li>إعداد أسعار الصرف</li>
                        <li>تحديد السنة المالية</li>
                        <li>إعداد طرق الدفع</li>
                    </ul>
                </li>
                
                <li>
                    <strong>إنشاء المستخدمين</strong>
                    <div class="code-block">
خطوات إنشاء مستخدم:
1. الذهاب إلى: /admin/users
2. إضافة مستخدم جديد
3. تعيين الدور المناسب
4. تفعيل الحساب
                    </div>
                </li>
            </ol>
            
            <div class="section-title">المرحلة الثانية: إعداد البيانات الأساسية</div>
            
            <ol class="step-list" start="4">
                <li>
                    <strong>إعداد المخزون</strong>
                    <ul>
                        <li>إنشاء فئات المنتجات</li>
                        <li>إضافة المنتجات الأساسية</li>
                        <li>تحديد مستويات المخزون</li>
                        <li>إعداد تنبيهات النفاد</li>
                    </ul>
                </li>
                
                <li>
                    <strong>إعداد العملاء والموردين</strong>
                    <ul>
                        <li>إضافة بيانات العملاء الأساسيين</li>
                        <li>تحديد حدود الائتمان</li>
                        <li>إعداد شروط الدفع</li>
                        <li>إضافة بيانات الموردين</li>
                    </ul>
                </li>
                
                <li>
                    <strong>إعداد نظام الصلاحيات</strong>
                    <div class="code-block">
صلاحيات العملاء:
• place_orders: إنشاء طلبيات
• view_financial_info: عرض المعلومات المالية
• view_own_orders: عرض الطلبيات الخاصة
                    </div>
                </li>
            </ol>
        </div>

        <!-- Chapter 3: Best Practices -->
        <div class="chapter">
            <div class="chapter-title">🎯 للاستخدام بكفاءة عالية</div>
            
            <div class="section-title">أفضل الممارسات</div>
            
            <div class="info-box">
                <strong>1. التدريب والتأهيل</strong><br>
                • تدريب الموظفين على النظام<br>
                • إنشاء دليل استخدام داخلي<br>
                • تحديد مسؤوليات كل مستخدم
            </div>
            
            <div class="info-box">
                <strong>2. إعداد التقارير</strong><br>
                • تخصيص التقارير المطلوبة<br>
                • جدولة التقارير الدورية<br>
                • إعداد تنبيهات الأداء
            </div>
            
            <div class="warning-box">
                <strong>3. النسخ الاحتياطي</strong><br>
                • إعداد نسخ احتياطية تلقائية<br>
                • اختبار استعادة البيانات<br>
                • توثيق إجراءات الطوارئ
            </div>
            
            <div class="info-box">
                <strong>4. المراقبة والتحسين</strong><br>
                • مراقبة أداء النظام<br>
                • تحليل تقارير الاستخدام<br>
                • تحديث البيانات بانتظام
            </div>
        </div>

        <!-- Chapter 4: Advanced Settings -->
        <div class="chapter">
            <div class="chapter-title">🔧 الإعدادات المتقدمة</div>
            
            <div class="section-title">للمستأجرين المتقدمين</div>
            
            <div class="section-title">1. تخصيص النظام</div>
            <ul>
                <li>تخصيص الواجهات</li>
                <li>إعداد سير العمل المخصص</li>
                <li>تكامل مع أنظمة خارجية</li>
            </ul>
            
            <div class="section-title">2. الأمان المتقدم</div>
            <ul>
                <li>تفعيل المصادقة الثنائية (2FA)</li>
                <li>إعداد سياسات كلمات المرور</li>
                <li>مراجعة سجلات النشاط</li>
            </ul>
            
            <div class="section-title">3. التحليلات والذكاء الاصطناعي</div>
            <ul>
                <li>إعداد لوحات المعلومات</li>
                <li>تحليل اتجاهات المبيعات</li>
                <li>توقعات المخزون</li>
            </ul>
        </div>

        <!-- Chapter 5: Support -->
        <div class="chapter">
            <div class="chapter-title">📞 الدعم والمساعدة</div>
            
            <div class="section-title">قنوات الدعم</div>
            <div class="code-block">
• الدعم الفني: support@maxcon.app
• التدريب: training@maxcon.app
• المبيعات: sales@maxcon.app
            </div>
            
            <div class="section-title">الموارد المفيدة</div>
            <ul class="checklist">
                <li>دليل المستخدم الشامل</li>
                <li>فيديوهات تعليمية</li>
                <li>منتدى المجتمع</li>
                <li>قاعدة المعرفة</li>
            </ul>
            
            <div class="section-title">⚡ نصائح للكفاءة العالية</div>
            <ol>
                <li><strong>ابدأ بالأساسيات</strong> - لا تحاول استخدام جميع الميزات دفعة واحدة</li>
                <li><strong>درب فريقك</strong> - استثمر في تدريب الموظفين</li>
                <li><strong>راقب الأداء</strong> - استخدم التقارير لتحسين العمليات</li>
                <li><strong>حدث البيانات</strong> - حافظ على دقة وحداثة البيانات</li>
                <li><strong>استخدم الأتمتة</strong> - فعل الميزات التلقائية لتوفير الوقت</li>
            </ol>
            
            <div class="info-box" style="margin-top: 30px; text-align: center;">
                <strong>🎉 مبروك!</strong><br>
                بهذه الخطوات، ستتمكن من الاستفادة القصوى من نظام MaxCon ERP<br>
                وتحقيق كفاءة عالية في إدارة أعمالك
            </div>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 50px; padding: 20px; border-top: 1px solid #e2e8f0;">
            <div style="font-size: 14pt; font-weight: bold; color: #667eea;">
                MaxCon ERP - نظام إدارة الأعمال المتكامل
            </div>
            <div style="font-size: 10pt; color: #4a5568; margin-top: 10px;">
                www.maxcon.app | support@maxcon.app<br>
                © ' . date('Y') . ' MaxCon ERP. جميع الحقوق محفوظة.
            </div>
        </div>
    </body>
    </html>';

    $mpdf->WriteHTML($html);
    
    $filename = 'دليل_المستأجر_الجديد_MaxCon_' . date('Y-m-d_H-i-s') . '.pdf';
    $path = __DIR__ . '/' . $filename;
    
    $mpdf->Output($path, 'F');
    echo "✅ تم إنشاء دليل المستأجر الجديد: {$filename}\n";
    
    // Also output to browser
    $mpdf->Output($filename, 'I');
    
} catch (\Exception $e) {
    echo "❌ خطأ في إنشاء دليل المستأجر: " . $e->getMessage() . "\n";
    echo "تفاصيل: " . $e->getTraceAsString() . "\n";
}

echo "\n📋 تم إنشاء دليل المستأجر الجديد بنجاح!\n";
echo "الملف يحتوي على جميع الخطوات والإرشادات اللازمة للبدء في استخدام النظام.\n";

?>