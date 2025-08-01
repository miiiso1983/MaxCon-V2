<?php
// Simple route test file
echo "<!DOCTYPE html>";
echo "<html lang='ar' dir='rtl'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>اختبار الراوت</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }";
echo ".container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }";
echo ".success { color: #10b981; font-weight: bold; }";
echo ".error { color: #ef4444; font-weight: bold; }";
echo ".info { background: #e6fffa; padding: 15px; border-radius: 8px; margin: 15px 0; border-right: 4px solid #10b981; }";
echo ".btn { background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; display: inline-block; margin: 5px; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<div class='container'>";
echo "<h1>🔧 اختبار راوت دليل المستأجر الجديد</h1>";

// Test if we can access Laravel routes
try {
    // Check if Laravel is available
    if (function_exists('route')) {
        echo "<p class='success'>✅ Laravel متاح</p>";
        
        // Try to generate the route
        try {
            $route_url = route('tenant.system-guide.new-tenant-guide');
            echo "<p class='success'>✅ الراوت يعمل بشكل صحيح</p>";
            echo "<p><strong>رابط الراوت:</strong> <a href='$route_url' class='btn'>$route_url</a></p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ خطأ في الراوت: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>❌ Laravel غير متاح في هذا الملف</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ خطأ عام: " . $e->getMessage() . "</p>";
}

echo "<div class='info'>";
echo "<h3>معلومات مهمة:</h3>";
echo "<ul>";
echo "<li>تأكد من أن الخادم يعمل: <code>php artisan serve</code></li>";
echo "<li>امسح الكاش: <code>php artisan route:clear</code></li>";
echo "<li>تأكد من تسجيل الدخول كمستأجر</li>";
echo "<li>جرب الرابط المباشر: <code>/system-guide/new-tenant-guide</code></li>";
echo "</ul>";
echo "</div>";

echo "<h3>روابط الاختبار:</h3>";
echo "<a href='/system-guide/new-tenant-guide' class='btn'>الرابط المباشر</a>";
echo "<a href='/test-new-tenant-guide.html' class='btn'>صفحة الاختبار</a>";
echo "<a href='/دليل_المستأجر_التفاعلي.html' class='btn'>الدليل التفاعلي</a>";

echo "</div>";
echo "</body>";
echo "</html>";
?>
