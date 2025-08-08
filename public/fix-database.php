<?php
/**
 * Database Connection Fixer
 * إصلاح مشكلة الاتصال بقاعدة البيانات
 */

echo "<h1>إصلاح مشكلة قاعدة البيانات</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;}</style>";

echo "<h2>اختبار إعدادات قاعدة البيانات المختلفة</h2>";

// Common database configurations for Cloudways
$configs = [
    [
        'name' => 'Cloudways Default',
        'host' => 'localhost',
        'database' => 'rrpkfnxwgn_maxcon',
        'username' => 'rrpkfnxwgn_maxcon',
        'password' => 'maxcon2024!'
    ],
    [
        'name' => 'Cloudways Alternative 1',
        'host' => '127.0.0.1',
        'database' => 'rrpkfnxwgn_maxcon',
        'username' => 'rrpkfnxwgn_maxcon',
        'password' => 'maxcon2024!'
    ],
    [
        'name' => 'Cloudways Alternative 2',
        'host' => 'localhost',
        'database' => 'rrpkfnxwgn_maxcon',
        'username' => 'rrpkfnxwgn_maxcon',
        'password' => 'MaxCon2024!'
    ],
    [
        'name' => 'Local Development',
        'host' => 'localhost',
        'database' => 'maxcon',
        'username' => 'root',
        'password' => ''
    ],
    [
        'name' => 'Local Development 2',
        'host' => '127.0.0.1',
        'database' => 'maxcon',
        'username' => 'root',
        'password' => 'root'
    ]
];

$working_config = null;

foreach ($configs as $config) {
    echo "<h3>اختبار: {$config['name']}</h3>";
    echo "<p>Host: {$config['host']}, Database: {$config['database']}, User: {$config['username']}</p>";
    
    try {
        $pdo = new PDO(
            "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]
        );
        
        echo "<p class='ok'>✅ الاتصال نجح!</p>";
        
        // Test basic queries
        try {
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "<p class='ok'>✅ الجداول الموجودة: " . implode(', ', $tables) . "</p>";
            
            // Test specific tables
            $required_tables = ['customers', 'products', 'invoices'];
            $missing_tables = [];
            
            foreach ($required_tables as $table) {
                if (!in_array($table, $tables)) {
                    $missing_tables[] = $table;
                }
            }
            
            if (empty($missing_tables)) {
                echo "<p class='ok'>✅ جميع الجداول المطلوبة موجودة</p>";
                
                // Test data count
                foreach ($required_tables as $table) {
                    try {
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM {$table}");
                        $result = $stmt->fetch();
                        echo "<p class='ok'>✅ جدول {$table}: {$result['count']} سجل</p>";
                    } catch (Exception $e) {
                        echo "<p class='warning'>⚠️ خطأ في جدول {$table}: {$e->getMessage()}</p>";
                    }
                }
                
                $working_config = $config;
                break;
                
            } else {
                echo "<p class='warning'>⚠️ الجداول المفقودة: " . implode(', ', $missing_tables) . "</p>";
            }
            
        } catch (Exception $e) {
            echo "<p class='warning'>⚠️ خطأ في الاستعلام: {$e->getMessage()}</p>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>❌ فشل الاتصال: {$e->getMessage()}</p>";
    }
    
    echo "<hr>";
}

if ($working_config) {
    echo "<h2>✅ تم العثور على إعدادات صحيحة!</h2>";
    echo "<div style='background:#d4edda;padding:15px;border-radius:5px;border:1px solid #c3e6cb;'>";
    echo "<h3>الإعدادات الصحيحة:</h3>";
    echo "<ul>";
    echo "<li><strong>Host:</strong> {$working_config['host']}</li>";
    echo "<li><strong>Database:</strong> {$working_config['database']}</li>";
    echo "<li><strong>Username:</strong> {$working_config['username']}</li>";
    echo "<li><strong>Password:</strong> " . (empty($working_config['password']) ? '(فارغ)' : '***') . "</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<h3>تحديث ملف .env</h3>";
    
    $env_file = '../.env';
    if (file_exists($env_file)) {
        $content = file_get_contents($env_file);
        
        // Update database settings
        $content = preg_replace('/DB_HOST=.*/', "DB_HOST={$working_config['host']}", $content);
        $content = preg_replace('/DB_DATABASE=.*/', "DB_DATABASE={$working_config['database']}", $content);
        $content = preg_replace('/DB_USERNAME=.*/', "DB_USERNAME={$working_config['username']}", $content);
        $content = preg_replace('/DB_PASSWORD=.*/', "DB_PASSWORD={$working_config['password']}", $content);
        
        if (file_put_contents($env_file, $content)) {
            echo "<p class='ok'>✅ تم تحديث ملف .env بالإعدادات الصحيحة</p>";
        } else {
            echo "<p class='error'>❌ فشل في تحديث ملف .env</p>";
        }
    }
    
} else {
    echo "<h2>❌ لم يتم العثور على إعدادات صحيحة</h2>";
    echo "<div style='background:#f8d7da;padding:15px;border-radius:5px;border:1px solid #f5c6cb;'>";
    echo "<h3>الحلول المقترحة:</h3>";
    echo "<ol>";
    echo "<li><strong>تحقق من Cloudways Dashboard:</strong> تأكد من بيانات قاعدة البيانات</li>";
    echo "<li><strong>إعادة تعيين كلمة المرور:</strong> في لوحة تحكم Cloudways</li>";
    echo "<li><strong>تحقق من صلاحيات المستخدم:</strong> تأكد من أن المستخدم له صلاحيات كاملة</li>";
    echo "<li><strong>تواصل مع الدعم:</strong> إذا استمرت المشكلة</li>";
    echo "</ol>";
    echo "</div>";
}

echo "<h2>الخطوات التالية</h2>";
echo "<div style='background:#f0f0f0;padding:15px;border-radius:5px;'>";
echo "<ol>";
echo "<li><strong>شغل إصلاح وضع التطوير:</strong> <a href='/fix-debug.php' target='_blank'>إصلاح APP_DEBUG</a></li>";
echo "<li><strong>اختبر الصفحة:</strong> <a href='/invoice-professional.php' target='_blank'>الفواتير الاحترافية</a></li>";
echo "<li><strong>مسح الـ Cache:</strong> php artisan config:clear</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<p><small>تم التشغيل في: " . date('Y-m-d H:i:s') . "</small></p>";
?>
