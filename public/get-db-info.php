<?php
/**
 * Get Database Information from Cloudways
 * الحصول على معلومات قاعدة البيانات من Cloudways
 */

echo "<h1>الحصول على معلومات قاعدة البيانات</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;} .info{color:blue;} .code{background:#f4f4f4;padding:10px;border-radius:5px;font-family:monospace;}</style>";

echo "<h2>📋 كيفية الحصول على بيانات قاعدة البيانات الصحيحة</h2>";

echo "<div style='background:#e7f3ff;padding:20px;border-radius:10px;border:1px solid #b3d9ff;margin:20px 0;'>";
echo "<h3>🔍 الخطوات في Cloudways Dashboard:</h3>";
echo "<ol>";
echo "<li><strong>ادخل إلى Cloudways Dashboard</strong></li>";
echo "<li><strong>اختر التطبيق (MaxCon)</strong></li>";
echo "<li><strong>اذهب إلى تبويب 'Application Management'</strong></li>";
echo "<li><strong>انقر على 'Access Details'</strong></li>";
echo "<li><strong>ستجد معلومات قاعدة البيانات:</strong>";
echo "<ul>";
echo "<li>Database Name</li>";
echo "<li>Database Username</li>";
echo "<li>Database Password</li>";
echo "<li>Database Host (عادة localhost)</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<h2>📝 نموذج لإدخال البيانات الصحيحة</h2>";

if ($_POST) {
    $host = $_POST['host'] ?? 'localhost';
    $database = $_POST['database'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($database && $username) {
        echo "<h3>🧪 اختبار البيانات المدخلة</h3>";
        
        try {
            $pdo = new PDO(
                "mysql:host={$host};dbname={$database};charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
            
            echo "<p class='ok'>✅ نجح الاتصال بقاعدة البيانات!</p>";
            
            // Test tables
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "<p class='ok'>✅ الجداول الموجودة: " . implode(', ', $tables) . "</p>";
            
            // Check required tables
            $required_tables = ['customers', 'products', 'invoices', 'users', 'tenants'];
            $existing_required = array_intersect($required_tables, $tables);
            
            if (count($existing_required) >= 3) {
                echo "<p class='ok'>✅ الجداول المطلوبة موجودة</p>";
                
                // Update .env file
                echo "<h3>📝 تحديث ملف .env</h3>";
                
                $env_file = '../.env';
                if (file_exists($env_file)) {
                    $content = file_get_contents($env_file);
                    
                    // Update database settings
                    $content = preg_replace('/DB_HOST=.*/', "DB_HOST={$host}", $content);
                    $content = preg_replace('/DB_DATABASE=.*/', "DB_DATABASE={$database}", $content);
                    $content = preg_replace('/DB_USERNAME=.*/', "DB_USERNAME={$username}", $content);
                    $content = preg_replace('/DB_PASSWORD=.*/', "DB_PASSWORD={$password}", $content);
                    
                    if (file_put_contents($env_file, $content)) {
                        echo "<p class='ok'>✅ تم تحديث ملف .env بنجاح!</p>";
                        
                        echo "<div class='code'>";
                        echo "DB_HOST={$host}<br>";
                        echo "DB_DATABASE={$database}<br>";
                        echo "DB_USERNAME={$username}<br>";
                        echo "DB_PASSWORD=" . (empty($password) ? '(فارغ)' : str_repeat('*', strlen($password))) . "<br>";
                        echo "</div>";
                        
                        echo "<h3>🎉 تم الإصلاح بنجاح!</h3>";
                        echo "<p class='ok'>الآن يمكنك اختبار الصفحة:</p>";
                        echo "<a href='/invoice-professional.php' target='_blank' style='background:#28a745;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>اختبر الفواتير الاحترافية</a>";
                        
                    } else {
                        echo "<p class='error'>❌ فشل في تحديث ملف .env</p>";
                    }
                } else {
                    echo "<p class='error'>❌ ملف .env غير موجود</p>";
                }
                
            } else {
                echo "<p class='warning'>⚠️ بعض الجداول المطلوبة مفقودة</p>";
            }
            
        } catch (Exception $e) {
            echo "<p class='error'>❌ فشل الاتصال: {$e->getMessage()}</p>";
        }
    }
}
?>

<form method="POST" style="background:#f8f9fa;padding:20px;border-radius:10px;border:1px solid #dee2e6;">
    <h3>🔧 أدخل بيانات قاعدة البيانات الصحيحة</h3>
    
    <div style="margin-bottom:15px;">
        <label style="display:block;font-weight:bold;margin-bottom:5px;">Database Host:</label>
        <input type="text" name="host" value="<?= $_POST['host'] ?? 'localhost' ?>" 
               style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
        <small style="color:#666;">عادة localhost أو 127.0.0.1</small>
    </div>
    
    <div style="margin-bottom:15px;">
        <label style="display:block;font-weight:bold;margin-bottom:5px;">Database Name:</label>
        <input type="text" name="database" value="<?= $_POST['database'] ?? '' ?>" 
               style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;" required>
        <small style="color:#666;">مثل: rrpkfnxwgn_maxcon</small>
    </div>
    
    <div style="margin-bottom:15px;">
        <label style="display:block;font-weight:bold;margin-bottom:5px;">Database Username:</label>
        <input type="text" name="username" value="<?= $_POST['username'] ?? '' ?>" 
               style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;" required>
        <small style="color:#666;">مثل: rrpkfnxwgn_maxcon</small>
    </div>
    
    <div style="margin-bottom:15px;">
        <label style="display:block;font-weight:bold;margin-bottom:5px;">Database Password:</label>
        <input type="password" name="password" value="<?= $_POST['password'] ?? '' ?>" 
               style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
        <small style="color:#666;">كلمة المرور من Cloudways Dashboard</small>
    </div>
    
    <button type="submit" style="background:#007bff;color:white;padding:10px 20px;border:none;border-radius:5px;cursor:pointer;">
        🧪 اختبار الاتصال وتحديث الإعدادات
    </button>
</form>

<div style="background:#fff3cd;padding:15px;border-radius:5px;border:1px solid #ffeaa7;margin:20px 0;">
    <h3>💡 نصائح مهمة:</h3>
    <ul>
        <li><strong>احصل على البيانات من Cloudways Dashboard</strong> - لا تخمن!</li>
        <li><strong>انسخ والصق البيانات بدقة</strong> - تجنب الأخطاء الإملائية</li>
        <li><strong>تأكد من صلاحيات المستخدم</strong> - يجب أن يكون له صلاحيات كاملة</li>
        <li><strong>جرب كلمات مرور مختلفة</strong> - إذا كنت غير متأكد</li>
    </ul>
</div>

<div style="background:#d1ecf1;padding:15px;border-radius:5px;border:1px solid #bee5eb;margin:20px 0;">
    <h3>🔗 روابط مفيدة:</h3>
    <ul>
        <li><a href="/debug-symfony.php" target="_blank">صفحة التشخيص الشاملة</a></li>
        <li><a href="/invoice-professional.php" target="_blank">اختبار الفواتير الاحترافية</a></li>
        <li><a href="/tenant/sales/invoices" target="_blank">صفحة الفواتير الرئيسية</a></li>
    </ul>
</div>

<hr>
<p><small>تم التشغيل في: <?= date('Y-m-d H:i:s') ?></small></p>
