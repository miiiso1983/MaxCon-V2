<?php
/**
 * Fix Debug Mode Script
 * إصلاح مشكلة وضع التطوير
 */

echo "<h1>إصلاح مشكلة وضع التطوير</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;}</style>";

$env_file = '../.env';

if (file_exists($env_file)) {
    echo "<h2>إصلاح ملف .env</h2>";
    
    // Read current content
    $content = file_get_contents($env_file);
    
    // Check current debug setting
    if (strpos($content, 'APP_DEBUG=true') !== false) {
        echo "<p class='warning'>⚠️ APP_DEBUG حالياً مفعل (true)</p>";
        
        // Replace APP_DEBUG=true with APP_DEBUG=false
        $new_content = str_replace('APP_DEBUG=true', 'APP_DEBUG=false', $content);
        
        // Write back to file
        if (file_put_contents($env_file, $new_content)) {
            echo "<p class='ok'>✅ تم تعطيل APP_DEBUG بنجاح!</p>";
            echo "<p>تم تغيير APP_DEBUG من true إلى false</p>";
        } else {
            echo "<p class='error'>❌ فشل في كتابة ملف .env</p>";
        }
    } else if (strpos($content, 'APP_DEBUG=false') !== false) {
        echo "<p class='ok'>✅ APP_DEBUG معطل بالفعل (false)</p>";
    } else {
        echo "<p class='warning'>⚠️ لم يتم العثور على APP_DEBUG في ملف .env</p>";
        
        // Add APP_DEBUG=false to the file
        $new_content = $content . "\nAPP_DEBUG=false\n";
        
        if (file_put_contents($env_file, $new_content)) {
            echo "<p class='ok'>✅ تم إضافة APP_DEBUG=false إلى ملف .env</p>";
        } else {
            echo "<p class='error'>❌ فشل في إضافة APP_DEBUG إلى ملف .env</p>";
        }
    }
} else {
    echo "<p class='error'>❌ ملف .env غير موجود</p>";
}

echo "<h2>مسح الـ Cache</h2>";

// Clear Laravel caches
$commands = [
    'config:clear' => 'مسح cache الإعدادات',
    'route:clear' => 'مسح cache الـ routes',
    'view:clear' => 'مسح cache العروض',
    'cache:clear' => 'مسح cache العام'
];

foreach ($commands as $command => $description) {
    echo "<p>جاري {$description}...</p>";
    
    // Try to run artisan command
    $output = [];
    $return_code = 0;
    
    exec("cd .. && php artisan {$command} 2>&1", $output, $return_code);
    
    if ($return_code === 0) {
        echo "<p class='ok'>✅ {$description} - تم بنجاح</p>";
    } else {
        echo "<p class='warning'>⚠️ {$description} - " . implode(' ', $output) . "</p>";
    }
}

echo "<h2>اختبار الإصلاح</h2>";

// Test if the fix worked
if (file_exists($env_file)) {
    $content = file_get_contents($env_file);
    if (strpos($content, 'APP_DEBUG=false') !== false) {
        echo "<p class='ok'>✅ APP_DEBUG معطل الآن</p>";
        echo "<p>يجب أن تختفي مشكلة Symfony الآن</p>";
    } else {
        echo "<p class='error'>❌ APP_DEBUG لا يزال مفعلاً</p>";
    }
}

echo "<h2>الخطوات التالية</h2>";
echo "<div style='background:#f0f0f0;padding:15px;border-radius:5px;'>";
echo "<ol>";
echo "<li><strong>اختبر الصفحة الرئيسية:</strong> <a href='/tenant/sales/invoices' target='_blank'>صفحة الفواتير</a></li>";
echo "<li><strong>اختبر الصفحة الاحترافية:</strong> <a href='/invoice-professional.php' target='_blank'>الفواتير الاحترافية</a></li>";
echo "<li><strong>إذا استمرت المشكلة:</strong> تواصل مع مزود الاستضافة لإزالة highlight_file من disabled_functions</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<p><small>تم التشغيل في: " . date('Y-m-d H:i:s') . "</small></p>";
?>
