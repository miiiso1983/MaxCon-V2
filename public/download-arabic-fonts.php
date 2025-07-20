<?php

/**
 * Download Arabic Fonts for PDF Generation
 * 
 * تحميل الخطوط العربية لإنشاء ملفات PDF
 */

echo "🔤 تحميل الخطوط العربية...\n";

// Create fonts directories
$storageDir = '../storage/fonts/';
$publicDir = './fonts/';

if (!file_exists($storageDir)) {
    mkdir($storageDir, 0755, true);
    echo "✅ تم إنشاء مجلد storage/fonts\n";
}

if (!file_exists($publicDir)) {
    mkdir($publicDir, 0755, true);
    echo "✅ تم إنشاء مجلد public/fonts\n";
}

// Arabic fonts to download
$fonts = [
    'Amiri-Regular.ttf' => 'https://github.com/aliftype/amiri/releases/download/1.000/Amiri-1.000.zip',
    'NotoSansArabic-Regular.ttf' => 'https://fonts.google.com/download?family=Noto%20Sans%20Arabic',
];

// Alternative: Use system fonts or create placeholder files
echo "📝 إنشاء ملفات خطوط بديلة...\n";

// Create a simple font configuration
$fontConfig = [
    'amiri' => [
        'R' => 'DejaVuSans.ttf',
        'B' => 'DejaVuSans-Bold.ttf',
        'useOTL' => 0xFF,
        'useKashida' => 75,
    ],
    'noto' => [
        'R' => 'DejaVuSans.ttf', 
        'B' => 'DejaVuSans-Bold.ttf',
        'useOTL' => 0xFF,
        'useKashida' => 75,
    ],
];

// Save font configuration
file_put_contents($storageDir . 'font-config.json', json_encode($fontConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "✅ تم حفظ إعدادات الخطوط\n";

// Create CSS for web fonts
$webFontsCSS = "
@import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;700&display=swap');

.arabic-text {
    font-family: 'Amiri', 'Noto Sans Arabic', 'DejaVu Sans', sans-serif;
    direction: rtl;
    text-align: right;
}

.pdf-content {
    font-family: 'Amiri', 'Noto Sans Arabic', 'DejaVu Sans', sans-serif;
    font-size: 12pt;
    line-height: 1.6;
    direction: rtl;
    text-align: right;
}
";

file_put_contents($publicDir . 'arabic-fonts.css', $webFontsCSS);
echo "✅ تم إنشاء ملف CSS للخطوط العربية\n";

// Instructions for manual font installation
$instructions = "
تعليمات تثبيت الخطوط العربية:
=====================================

1. خط أميري (Amiri):
   - تحميل من: https://github.com/aliftype/amiri/releases
   - استخراج الملفات ونسخ .ttf إلى storage/fonts/

2. خط Noto Sans Arabic:
   - تحميل من: https://fonts.google.com/noto/specimen/Noto+Sans+Arabic
   - نسخ الملفات إلى storage/fonts/

3. خطوط بديلة:
   - DejaVu Sans (مثبت افتراضياً مع mPDF)
   - يدعم العربية بشكل أساسي

4. اختبار الخطوط:
   - تشغيل /test-arabic-pdf.php
   - التحقق من عرض النص العربي بشكل صحيح

ملاحظات:
- تأكد من صلاحيات الكتابة على مجلد storage/fonts
- الخطوط المحملة ستحسن جودة النص العربي في PDF
- يمكن استخدام النظام بدون خطوط إضافية مع DejaVu Sans
";

file_put_contents($storageDir . 'FONT_INSTRUCTIONS.txt', $instructions);
echo "✅ تم حفظ تعليمات تثبيت الخطوط\n";

echo "\n🎯 النتائج:\n";
echo "- تم إنشاء مجلدات الخطوط\n";
echo "- تم إنشاء إعدادات الخطوط البديلة\n";
echo "- تم إنشاء ملف CSS للخطوط العربية\n";
echo "- تم حفظ تعليمات التثبيت\n";

echo "\n📁 الملفات المُنشأة:\n";
echo "- storage/fonts/font-config.json\n";
echo "- storage/fonts/FONT_INSTRUCTIONS.txt\n";
echo "- public/fonts/arabic-fonts.css\n";

echo "\n🔗 الخطوة التالية:\n";
echo "تشغيل: /test-arabic-pdf.php لاختبار إنشاء PDF بالعربية\n";

?>
