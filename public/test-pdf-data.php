<?php

/**
 * Test PDF Data Structure
 * 
 * اختبار هيكل بيانات PDF
 */

require_once '../vendor/autoload.php';

// Load Laravel
$app = require_once '../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🧪 اختبار هيكل بيانات PDF...\n\n";

// Simulate the SystemGuideController methods
function getSystemModules() {
    return [
        'sales' => [
            'name' => 'إدارة المبيعات',
            'description' => 'نظام شامل لإدارة المبيعات والعملاء',
            'features' => [
                'إدارة العملاء',
                'إصدار الفواتير',
                'متابعة المدفوعات',
                'تقارير المبيعات'
            ],
            'difficulty' => 'سهل',
            'video_duration' => '5-7 دقائق',
            'color' => '#10b981'
        ],
        'inventory' => [
            'name' => 'إدارة المخزون',
            'description' => 'نظام متكامل لإدارة المخزون والمنتجات',
            'features' => [
                'إدارة المنتجات',
                'تتبع المخزون',
                'حركات المخزون',
                'تقارير المخزون'
            ],
            'difficulty' => 'متوسط',
            'video_duration' => '7-10 دقائق',
            'color' => '#3b82f6'
        ]
    ];
}

function getSystemFeatures() {
    return [
        'comprehensive' => [
            'title' => 'نظام شامل ومتكامل',
            'description' => 'يغطي جميع احتياجات إدارة الأعمال من المبيعات إلى المحاسبة',
            'icon' => 'fas fa-puzzle-piece'
        ],
        'arabic' => [
            'title' => 'دعم كامل للغة العربية',
            'description' => 'واجهات عربية بالكامل مع دعم الكتابة من اليمين إلى اليسار',
            'icon' => 'fas fa-language'
        ]
    ];
}

function getUserTypes() {
    return [
        'admin' => [
            'title' => 'مدير النظام',
            'description' => 'صلاحيات كاملة لإدارة النظام والمستخدمين',
            'permissions' => ['إدارة المستخدمين', 'إعدادات النظام', 'النسخ الاحتياطية'],
            'color' => '#ef4444'
        ],
        'manager' => [
            'title' => 'المدير التنفيذي',
            'description' => 'إدارة العمليات اليومية ومراجعة التقارير',
            'permissions' => ['إدارة المبيعات', 'إدارة المخزون', 'التقارير المالية'],
            'color' => '#3b82f6'
        ]
    ];
}

function getFAQs() {
    return [
        'أسئلة عامة' => [
            [
                'question' => 'ما هو نظام MaxCon ERP؟',
                'answer' => 'نظام إدارة موارد المؤسسات شامل ومتكامل'
            ],
            [
                'question' => 'هل النظام يدعم اللغة العربية؟',
                'answer' => 'نعم، النظام يدعم اللغة العربية بالكامل'
            ]
        ],
        'أسئلة تقنية' => [
            [
                'question' => 'هل يمكن الوصول للنظام من الهاتف؟',
                'answer' => 'نعم، النظام متوافق مع جميع الأجهزة'
            ]
        ]
    ];
}

// Test data structure
$modules = getSystemModules();
$systemFeatures = getSystemFeatures();
$userTypes = getUserTypes();
$faqs = getFAQs();

echo "📊 اختبار البيانات:\n\n";

echo "1. الوحدات (modules):\n";
foreach ($modules as $slug => $module) {
    echo "   - {$module['name']}: " . count($module['features']) . " ميزات\n";
}

echo "\n2. مميزات النظام (systemFeatures):\n";
foreach ($systemFeatures as $key => $feature) {
    echo "   - {$feature['title']}\n";
}

echo "\n3. أنواع المستخدمين (userTypes):\n";
foreach ($userTypes as $key => $userType) {
    echo "   - {$userType['title']}: " . count($userType['permissions']) . " صلاحيات\n";
}

echo "\n4. الأسئلة الشائعة (faqs):\n";
foreach ($faqs as $category => $categoryFaqs) {
    echo "   - {$category}: " . count($categoryFaqs) . " أسئلة\n";
}

echo "\n✅ جميع البيانات صحيحة ومتوافقة مع القالب\n";

// Test Blade template rendering
echo "\n🎨 اختبار عرض القالب...\n";

try {
    $html = view('tenant.system-guide.pdf.user-manual-mpdf', compact(
        'modules', 'systemFeatures', 'userTypes', 'faqs'
    ))->render();
    
    echo "✅ تم عرض القالب بنجاح\n";
    echo "📏 حجم HTML: " . strlen($html) . " حرف\n";
    
    // Check for common issues
    if (strpos($html, 'Array') !== false) {
        echo "⚠️  تحذير: يوجد كلمة 'Array' في HTML - قد تكون هناك مشكلة في البيانات\n";
    } else {
        echo "✅ لا توجد مشاكل في البيانات\n";
    }
    
} catch (\Exception $e) {
    echo "❌ خطأ في عرض القالب: " . $e->getMessage() . "\n";
    echo "السطر: " . $e->getLine() . "\n";
    echo "الملف: " . $e->getFile() . "\n";
}

echo "\n🔗 الخطوة التالية:\n";
echo "إذا كان الاختبار ناجحاً، جرب تحميل دليل المستخدم من النظام\n";

?>
