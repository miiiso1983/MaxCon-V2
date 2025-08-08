<?php
/**
 * Check Database Data and Add Sample Data
 * فحص بيانات قاعدة البيانات وإضافة بيانات تجريبية
 */

echo "<h1>فحص وإدارة بيانات قاعدة البيانات</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;} .info{color:blue;}</style>";

try {
    // Database connection
    $host = 'localhost';
    $database = 'rrpkfnxwgn';
    $username = 'rrpkfnxwgn';
    $password = $_GET['pass'] ?? 'default_password'; // Get password from URL parameter
    $tenant_id = 4;
    
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
    
    echo "<p class='ok'>✅ متصل بقاعدة البيانات بنجاح</p>";
    
    echo "<h2>📊 فحص البيانات الحالية</h2>";
    
    // Check customers
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM customers WHERE tenant_id = ?");
    $stmt->execute([$tenant_id]);
    $customer_count = $stmt->fetch()['count'];
    
    // Check products
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM products WHERE tenant_id = ?");
    $stmt->execute([$tenant_id]);
    $product_count = $stmt->fetch()['count'];
    
    // Check invoices
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM invoices WHERE tenant_id = ?");
    $stmt->execute([$tenant_id]);
    $invoice_count = $stmt->fetch()['count'];
    
    echo "<div style='background:#f8f9fa;padding:15px;border-radius:5px;margin:10px 0;'>";
    echo "<h3>البيانات الحالية:</h3>";
    echo "<ul>";
    echo "<li><strong>العملاء:</strong> {$customer_count}</li>";
    echo "<li><strong>المنتجات:</strong> {$product_count}</li>";
    echo "<li><strong>الفواتير:</strong> {$invoice_count}</li>";
    echo "</ul>";
    echo "</div>";
    
    // Add sample data if needed
    if (isset($_POST['add_sample_data'])) {
        echo "<h2>➕ إضافة بيانات تجريبية</h2>";
        
        // Sample customers
        $sample_customers = [
            ['name' => 'صيدلية الشفاء المركزية', 'phone' => '07901234567', 'email' => 'shifa@example.com', 'credit_limit' => 25000],
            ['name' => 'صيدلية النور الطبية', 'phone' => '07907654321', 'email' => 'alnoor@example.com', 'credit_limit' => 15000],
            ['name' => 'مستشفى بغداد التخصصي', 'phone' => '07641234567', 'email' => 'baghdad@example.com', 'credit_limit' => 50000],
        ];
        
        foreach ($sample_customers as $customer) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO customers (tenant_id, name, phone, email, credit_limit, status, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, 'active', NOW(), NOW())
                ");
                $stmt->execute([$tenant_id, $customer['name'], $customer['phone'], $customer['email'], $customer['credit_limit']]);
                echo "<p class='ok'>✅ تم إضافة العميل: {$customer['name']}</p>";
            } catch (Exception $e) {
                echo "<p class='warning'>⚠️ العميل موجود بالفعل: {$customer['name']}</p>";
            }
        }
        
        // Sample products
        $sample_products = [
            ['name' => 'باراسيتامول 500 مغ', 'code' => 'PAR500', 'selling_price' => 2500, 'stock_quantity' => 150],
            ['name' => 'أموكسيسيلين 250 مغ', 'code' => 'AMX250', 'selling_price' => 8750, 'stock_quantity' => 80],
            ['name' => 'فيتامين د3 1000 وحدة', 'code' => 'VIT1000', 'selling_price' => 12000, 'stock_quantity' => 120],
            ['name' => 'أسبرين 100 مغ', 'code' => 'ASP100', 'selling_price' => 1250, 'stock_quantity' => 200],
        ];
        
        foreach ($sample_products as $product) {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO products (tenant_id, name, code, selling_price, stock_quantity, status, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, 'active', NOW(), NOW())
                ");
                $stmt->execute([$tenant_id, $product['name'], $product['code'], $product['selling_price'], $product['stock_quantity']]);
                echo "<p class='ok'>✅ تم إضافة المنتج: {$product['name']}</p>";
            } catch (Exception $e) {
                echo "<p class='warning'>⚠️ المنتج موجود بالفعل: {$product['name']}</p>";
            }
        }
        
        echo "<p class='ok'><strong>✅ تم إضافة البيانات التجريبية بنجاح!</strong></p>";
        echo "<a href='/invoice-professional.php' style='background:#28a745;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>اختبر الفواتير الاحترافية الآن</a>";
    }
    
    // Show existing data
    if ($customer_count > 0) {
        echo "<h3>👥 العملاء الموجودين:</h3>";
        $stmt = $pdo->prepare("SELECT name, phone, credit_limit FROM customers WHERE tenant_id = ? LIMIT 10");
        $stmt->execute([$tenant_id]);
        $customers = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse:collapse;width:100%;'>";
        echo "<tr><th>الاسم</th><th>الهاتف</th><th>سقف المديونية</th></tr>";
        foreach ($customers as $customer) {
            echo "<tr>";
            echo "<td>{$customer['name']}</td>";
            echo "<td>{$customer['phone']}</td>";
            echo "<td>" . number_format($customer['credit_limit']) . " د.ع</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    if ($product_count > 0) {
        echo "<h3>🏥 المنتجات الموجودة:</h3>";
        $stmt = $pdo->prepare("SELECT name, code, selling_price, stock_quantity FROM products WHERE tenant_id = ? LIMIT 10");
        $stmt->execute([$tenant_id]);
        $products = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse:collapse;width:100%;'>";
        echo "<tr><th>الاسم</th><th>الكود</th><th>السعر</th><th>المخزون</th></tr>";
        foreach ($products as $product) {
            echo "<tr>";
            echo "<td>{$product['name']}</td>";
            echo "<td>{$product['code']}</td>";
            echo "<td>" . number_format($product['selling_price']) . " د.ع</td>";
            echo "<td>{$product['stock_quantity']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Add sample data form
    if ($customer_count == 0 || $product_count == 0) {
        echo "<h2>➕ إضافة بيانات تجريبية</h2>";
        echo "<div style='background:#fff3cd;padding:15px;border-radius:5px;border:1px solid #ffeaa7;'>";
        echo "<p>لا توجد بيانات كافية في قاعدة البيانات. هل تريد إضافة بيانات تجريبية؟</p>";
        echo "<form method='POST'>";
        echo "<button type='submit' name='add_sample_data' style='background:#007bff;color:white;padding:10px 20px;border:none;border-radius:5px;cursor:pointer;'>";
        echo "إضافة بيانات تجريبية (3 عملاء + 4 منتجات)";
        echo "</button>";
        echo "</form>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ خطأ في الاتصال: {$e->getMessage()}</p>";
    echo "<p>تأكد من أن كلمة المرور صحيحة. استخدم: <code>?pass=كلمة_المرور_الصحيحة</code></p>";
}

echo "<h2>🔗 روابط مفيدة</h2>";
echo "<ul>";
echo "<li><a href='/invoice-professional.php' target='_blank'>اختبار الفواتير الاحترافية</a></li>";
echo "<li><a href='/get-db-info.php' target='_blank'>إعدادات قاعدة البيانات</a></li>";
echo "<li><a href='/tenant/customers' target='_blank'>إدارة العملاء</a></li>";
echo "<li><a href='/tenant/products' target='_blank'>إدارة المنتجات</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><small>تم التشغيل في: " . date('Y-m-d H:i:s') . "</small></p>";
?>
