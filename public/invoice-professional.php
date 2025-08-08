<?php
/**
 * Professional Invoice Creation - Database Connected Version
 * نسخة محدثة متصلة بقاعدة البيانات
 */

// Include Laravel bootstrap to access database
require_once '../vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once '../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Start session for authentication
session_start();

// Try different methods to connect to database
$db_connected = false;
$customers = [];
$products = [];
$tenant_id = 4; // Default tenant ID

// Method 1: Try to use Laravel's database connection
try {
    if (file_exists('../bootstrap/app.php')) {
        require_once '../vendor/autoload.php';
        $app = require_once '../bootstrap/app.php';

        // Get database configuration
        $config = [
            'host' => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'rrpkfnxwgn_maxcon'),
            'username' => env('DB_USERNAME', 'rrpkfnxwgn_maxcon'),
            'password' => env('DB_PASSWORD', 'maxcon2024!')
        ];

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

        $db_connected = true;
    }
} catch (Exception $e) {
    // Method 2: Try direct connection with common configurations
    $configs = [
        ['host' => 'localhost', 'db' => 'rrpkfnxwgn_maxcon', 'user' => 'rrpkfnxwgn_maxcon', 'pass' => 'maxcon2024!'],
        ['host' => '127.0.0.1', 'db' => 'rrpkfnxwgn_maxcon', 'user' => 'rrpkfnxwgn_maxcon', 'pass' => 'maxcon2024!'],
        ['host' => 'localhost', 'db' => 'maxcon', 'user' => 'root', 'pass' => ''],
        ['host' => '127.0.0.1', 'db' => 'maxcon', 'user' => 'root', 'pass' => '']
    ];

    foreach ($configs as $config) {
        try {
            $pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['db']};charset=utf8mb4",
                $config['user'],
                $config['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ]
            );
            $db_connected = true;
            break;
        } catch (Exception $e2) {
            continue;
        }
    }
}

// If connected, fetch data
if ($db_connected) {
    try {
        // Fetch customers from database
        $stmt = $pdo->prepare("
            SELECT id, name, phone, email,
                   COALESCE(credit_limit, 0) as credit_limit,
                   COALESCE((
                       SELECT SUM(total_amount - COALESCE(paid_amount, 0))
                       FROM invoices
                       WHERE customer_id = customers.id
                       AND status IN ('sent', 'pending', 'overdue')
                       AND tenant_id = ?
                   ), 0) as previous_balance
            FROM customers
            WHERE tenant_id = ? AND status = 'active'
            ORDER BY name
            LIMIT 50
        ");
        $stmt->execute([$tenant_id, $tenant_id]);
        $customers = $stmt->fetchAll();

        // Fetch products from database
        $stmt = $pdo->prepare("
            SELECT id, name, code,
                   COALESCE(selling_price, 0) as price,
                   COALESCE(stock_quantity, 0) as stock,
                   COALESCE(manufacturer, 'غير محدد') as company
            FROM products
            WHERE tenant_id = ? AND status = 'active'
            ORDER BY name
            LIMIT 100
        ");
        $stmt->execute([$tenant_id]);
        $products = $stmt->fetchAll();

    } catch (Exception $e) {
        $db_connected = false;
        $error_message = $e->getMessage();
    }
}

// Fallback data if no database connection
if (!$db_connected || empty($customers) || empty($products)) {
    $db_connected = false;

    // Enhanced fallback data - more realistic for pharmaceutical business
    $customers = [
        ['id' => 1, 'name' => 'صيدلية الشفاء المركزية', 'phone' => '07901234567', 'email' => 'shifa.central@gmail.com', 'credit_limit' => 25000, 'previous_balance' => 3200],
        ['id' => 2, 'name' => 'صيدلية النور الطبية', 'phone' => '07907654321', 'email' => 'alnoor.pharmacy@gmail.com', 'credit_limit' => 15000, 'previous_balance' => 1800],
        ['id' => 3, 'name' => 'صيدلية الحياة للأدوية', 'phone' => '07801234567', 'email' => 'alhayat.drugs@gmail.com', 'credit_limit' => 30000, 'previous_balance' => 4500],
        ['id' => 4, 'name' => 'صيدلية الأمل الحديثة', 'phone' => '07751234567', 'email' => 'alamal.modern@gmail.com', 'credit_limit' => 20000, 'previous_balance' => 2100],
        ['id' => 5, 'name' => 'صيدلية الرحمة الطبية', 'phone' => '07711234567', 'email' => 'alrahma.medical@gmail.com', 'credit_limit' => 18000, 'previous_balance' => 950],
        ['id' => 6, 'name' => 'مستشفى بغداد التخصصي', 'phone' => '07641234567', 'email' => 'baghdad.hospital@gmail.com', 'credit_limit' => 50000, 'previous_balance' => 8200],
        ['id' => 7, 'name' => 'مركز الكرخ الطبي', 'phone' => '07781234567', 'email' => 'karkh.medical@gmail.com', 'credit_limit' => 35000, 'previous_balance' => 5600],
        ['id' => 8, 'name' => 'صيدلية الزهراء', 'phone' => '07821234567', 'email' => 'alzahra.pharmacy@gmail.com', 'credit_limit' => 12000, 'previous_balance' => 1400],
    ];

    $products = [
        ['id' => 1, 'name' => 'باراسيتامول 500 مغ - 20 قرص', 'code' => 'PAR500-20', 'price' => 2500, 'stock' => 150, 'company' => 'شركة الأدوية العراقية'],
        ['id' => 2, 'name' => 'أموكسيسيلين 250 مغ - 14 كبسولة', 'code' => 'AMX250-14', 'price' => 8750, 'stock' => 80, 'company' => 'شركة الصحة الدولية'],
        ['id' => 3, 'name' => 'فيتامين د3 1000 وحدة - 30 قرص', 'code' => 'VIT1000-30', 'price' => 12000, 'stock' => 120, 'company' => 'شركة الفيتامينات المتقدمة'],
        ['id' => 4, 'name' => 'أسبرين 100 مغ - 30 قرص', 'code' => 'ASP100-30', 'price' => 1250, 'stock' => 200, 'company' => 'شركة القلب والأوعية'],
        ['id' => 5, 'name' => 'أوميجا 3 - 60 كبسولة', 'code' => 'OMG3-60', 'price' => 18500, 'stock' => 90, 'company' => 'شركة المكملات الطبية'],
        ['id' => 6, 'name' => 'كريم مضاد حيوي - 15 غرام', 'code' => 'ANT-CR-15', 'price' => 6500, 'stock' => 110, 'company' => 'شركة الجلدية المتخصصة'],
        ['id' => 7, 'name' => 'شراب السعال للأطفال - 120 مل', 'code' => 'COUGH-120', 'price' => 4200, 'stock' => 75, 'company' => 'شركة أدوية الأطفال'],
        ['id' => 8, 'name' => 'قطرة عين مضادة للالتهاب - 10 مل', 'code' => 'EYE-10', 'price' => 9800, 'stock' => 60, 'company' => 'شركة طب العيون'],
        ['id' => 9, 'name' => 'أقراص الضغط - 28 قرص', 'code' => 'BP-28', 'price' => 15600, 'stock' => 95, 'company' => 'شركة أمراض القلب'],
        ['id' => 10, 'name' => 'مرهم للجروح - 25 غرام', 'code' => 'WOUND-25', 'price' => 7800, 'stock' => 85, 'company' => 'شركة العناية بالجروح'],
        ['id' => 11, 'name' => 'فيتامين ب المركب - 30 قرص', 'code' => 'VITB-30', 'price' => 8900, 'stock' => 140, 'company' => 'شركة الفيتامينات المتقدمة'],
        ['id' => 12, 'name' => 'مضاد حيوي واسع المجال - 10 أقراص', 'code' => 'ANTI-10', 'price' => 22500, 'stock' => 45, 'company' => 'شركة المضادات الحيوية'],
    ];
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء فاتورة احترافية - MaxCon</title>
    <meta name="csrf-token" content="<?= bin2hex(random_bytes(16)) ?>">
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- Select2 for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <!-- Professional Invoice CSS -->
    <link href="/css/professional-invoice.css" rel="stylesheet">
    
    <style>
        body { font-family: 'Cairo', sans-serif; }
        
        /* Modern Professional Invoice Design */
        .invoice-container {
            background: #f8fafc;
            min-height: 100vh;
            padding: 20px 0;
        }

        .invoice-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }

        .invoice-title {
            font-size: 28px;
            font-weight: 800;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .invoice-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin-top: 8px;
        }

        .main-form {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            overflow: hidden;
            padding: 30px;
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f1f5f9;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-label.required::after {
            content: ' *';
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #ffffff;
            font-family: 'Cairo', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-family: 'Cairo', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.5);
            color: white;
            text-decoration: none;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.5);
            color: white;
            text-decoration: none;
        }

        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-top: 20px;
        }

        .items-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 10px;
            text-align: center;
            font-weight: 600;
            font-size: 13px;
            white-space: nowrap;
        }

        .items-table th:first-child {
            text-align: right;
            width: 35%;
        }

        .items-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .items-table tr:hover {
            background: #f8fafc;
        }

        .items-table input, .items-table select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            text-align: center;
        }

        .items-table input:focus, .items-table select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }

        .items-table .product-select {
            text-align: right !important;
        }

        /* Credit limit display */
        .credit-info {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #0ea5e9;
            border-radius: 12px;
            padding: 15px;
            margin-top: 15px;
            display: none;
        }

        .credit-info.show {
            display: block;
        }

        .credit-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .credit-item:last-child {
            margin-bottom: 0;
            font-weight: 600;
            border-top: 1px solid #0ea5e9;
            padding-top: 8px;
        }

        .credit-label {
            color: #0369a1;
        }

        .credit-value {
            color: #1e40af;
            font-weight: 600;
        }

        .credit-warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
        }

        .credit-danger {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #dc2626;
        }

        .add-item-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
        }

        .add-item-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .remove-item-btn {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-item-btn:hover {
            background: #dc2626;
            transform: scale(1.05);
        }

        .actions-section {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
            text-align: center;
        }

        .actions-grid {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .invoice-wrapper {
                padding: 0 15px;
            }
            
            .invoice-header {
                padding: 20px;
                text-align: center;
            }
            
            .invoice-title {
                font-size: 24px;
                justify-content: center;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .actions-grid {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            z-index: 10000;
            font-weight: 600;
            transform: translateX(100%);
            transition: all 0.3s ease;
            max-width: 400px;
            font-family: 'Cairo', sans-serif;
        }

        .notification.show {
            transform: translateX(0);
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-wrapper">
            <!-- Header -->
            <div class="invoice-header">
                <div class="invoice-title">
                    <i class="fas fa-file-invoice"></i>
                    إنشاء فاتورة احترافية
                </div>
                <div class="invoice-subtitle">
                    نظام إدارة الفواتير المتطور مع QR Code والبحث الذكي
                    <?php if (!$db_connected): ?>
                        <br><small style="color: #fbbf24;">⚠️ وضع تجريبي - بيانات احتياطية (<?= count($customers) ?> عميل، <?= count($products) ?> منتج)</small>
                        <?php if (isset($error_message)): ?>
                            <br><small style="color: #ef4444; font-size: 11px;">خطأ الاتصال: <?= htmlspecialchars(substr($error_message, 0, 100)) ?>...</small>
                        <?php endif; ?>
                    <?php else: ?>
                        <br><small style="color: #10b981;">✅ متصل بقاعدة البيانات - <?= count($customers) ?> عميل، <?= count($products) ?> منتج</small>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Main Form -->
            <div class="main-form">
                <form id="invoiceForm" method="POST" action="/tenant/sales/invoices">
                    <!-- Customer Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            معلومات العميل
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label required">العميل</label>
                                <select name="customer_id" required class="form-control searchable-select" id="customerSelect">
                                    <option value="">اختر العميل</option>
                                    <?php foreach($customers as $customer): ?>
                                        <option value="<?= $customer['id'] ?>"
                                                data-credit-limit="<?= $customer['credit_limit'] ?>"
                                                data-previous-balance="<?= $customer['previous_balance'] ?>"
                                                data-phone="<?= $customer['phone'] ?>"
                                                data-email="<?= $customer['email'] ?? '' ?>">
                                            <?= htmlspecialchars($customer['name']) ?> - <?= $customer['phone'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">تاريخ الفاتورة</label>
                                <input type="date" name="invoice_date" class="form-control" 
                                       value="<?= date('Y-m-d') ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">تاريخ الاستحقاق</label>
                                <input type="date" name="due_date" class="form-control" 
                                       value="<?= date('Y-m-d', strtotime('+30 days')) ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">المندوب</label>
                                <input type="text" name="sales_representative" class="form-control" 
                                       placeholder="اسم المندوب">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">المستودع</label>
                                <input type="text" name="warehouse_name" class="form-control" 
                                       placeholder="اسم المستودع" value="المستودع الرئيسي">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">نوع الفاتورة</label>
                                <select name="type" class="form-control">
                                    <option value="sales">فاتورة مبيعات</option>
                                    <option value="proforma">فاتورة أولية</option>
                                </select>
                            </div>
                        </div>

                        <!-- Credit Information Display -->
                        <div id="creditInfo" class="credit-info">
                            <div class="credit-item">
                                <span class="credit-label">سقف المديونية:</span>
                                <span class="credit-value" id="creditLimit">0 د.ع</span>
                            </div>
                            <div class="credit-item">
                                <span class="credit-label">المديونية السابقة:</span>
                                <span class="credit-value" id="previousBalance">0 د.ع</span>
                            </div>
                            <div class="credit-item">
                                <span class="credit-label">مبلغ الفاتورة الحالية:</span>
                                <span class="credit-value" id="currentInvoice">0 د.ع</span>
                            </div>
                            <div class="credit-item">
                                <span class="credit-label">إجمالي المديونية الجديدة:</span>
                                <span class="credit-value" id="newBalance">0 د.ع</span>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            عناصر الفاتورة
                        </div>
                        
                        <div style="overflow-x: auto;">
                            <table class="items-table" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 35%; text-align: right;">المنتج</th>
                                        <th style="width: 10%; text-align: center;">الكمية</th>
                                        <th style="width: 12%; text-align: center;">السعر</th>
                                        <th style="width: 12%; text-align: center;">الخصم</th>
                                        <th style="width: 10%; text-align: center;">العينات</th>
                                        <th style="width: 15%; text-align: center;">المجموع</th>
                                        <th style="width: 6%; text-align: center;">حذف</th>
                                    </tr>
                                </thead>
                                <tbody id="invoiceItems">
                                    <tr class="item-row">
                                        <td style="text-align: right;">
                                            <select name="items[0][product_id]" required class="form-control product-select searchable-select" onchange="updateProductInfo(this, 0)" style="width: 100%; text-align: right;">
                                                <option value="">اختر المنتج</option>
                                                <?php foreach($products as $product): ?>
                                                    <option value="<?= $product['id'] ?>"
                                                            data-price="<?= $product['price'] ?>"
                                                            data-stock="<?= $product['stock'] ?>"
                                                            data-code="<?= $product['code'] ?>"
                                                            data-company="<?= htmlspecialchars($product['company'] ?? '') ?>">
                                                        <?= htmlspecialchars($product['name']) ?> (<?= $product['code'] ?>) - <?= htmlspecialchars($product['company'] ?? '') ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="number" name="items[0][quantity]" min="1" step="1" required
                                                   placeholder="1" value="1"
                                                   onchange="calculateItemTotal(0)" style="width: 100%; text-align: center;">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="number" name="items[0][unit_price]" min="0" step="0.01" required
                                                   placeholder="0.00" value="0"
                                                   onchange="calculateItemTotal(0)" style="width: 100%; text-align: center;">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="number" name="items[0][discount_amount]" min="0" step="0.01"
                                                   placeholder="0.00" value="0"
                                                   onchange="calculateItemTotal(0)" style="width: 100%; text-align: center;">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="number" name="items[0][free_samples]" min="0" step="1"
                                                   placeholder="0" value="0" style="width: 100%; text-align: center;">
                                        </td>
                                        <td style="text-align: center;">
                                            <input type="number" name="items[0][total_amount]" readonly
                                                   placeholder="0.00" value="0"
                                                   style="background: #f9fafb; width: 100%; text-align: center;">
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" onclick="removeItem(0)" class="remove-item-btn" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <button type="button" onclick="addItem()" class="add-item-btn">
                            <i class="fas fa-plus"></i>
                            إضافة منتج
                        </button>
                    </div>

                    <!-- Additional Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            معلومات إضافية
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">ملاحظات</label>
                                <textarea name="notes" class="form-control" rows="3" 
                                          placeholder="ملاحظات إضافية..."></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">شروط الدفع</label>
                                <textarea name="payment_terms" class="form-control" rows="3" 
                                          placeholder="شروط وأحكام الدفع...">الدفع خلال 30 يوم من تاريخ الفاتورة</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="subtotal_amount" id="subtotalAmount" value="0">
                    <input type="hidden" name="discount_amount" id="discountAmount" value="0">
                    <input type="hidden" name="total_amount" id="totalAmount" value="0">
                </form>
            </div>

            <!-- Action Buttons -->
            <div class="actions-section">
                <div class="actions-grid">
                    <?php if (!$db_connected): ?>
                        <button type="button" onclick="window.location.reload()" class="btn btn-primary">
                            <i class="fas fa-sync"></i>
                            إعادة محاولة الاتصال
                        </button>
                        <a href="/debug-symfony.php" target="_blank" class="btn btn-primary">
                            <i class="fas fa-bug"></i>
                            تشخيص المشكلة
                        </a>
                    <?php endif; ?>
                    <button type="button" onclick="showNotification('معاينة الفاتورة قريباً!', 'info')" class="btn btn-primary">
                        <i class="fas fa-eye"></i>
                        معاينة الفاتورة
                    </button>
                    <button type="button" onclick="saveInvoice('draft')" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        حفظ كمسودة
                    </button>
                    <button type="button" onclick="saveInvoice('finalize')" class="btn btn-success">
                        <i class="fas fa-check-circle"></i>
                        إنهاء وحفظ الفاتورة
                    </button>
                    <a href="/tenant/sales/invoices" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للفواتير
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- JavaScript -->
    <script>
        let itemIndex = 1;
        const products = <?= json_encode($products) ?>;
        const customers = <?= json_encode($customers) ?>;

        // Update customer information when customer is selected
        function updateCustomerInfo() {
            const customerSelect = document.getElementById('customerSelect');
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];

            if (selectedOption.value) {
                const creditLimit = parseFloat(selectedOption.dataset.creditLimit || 0);
                const previousBalance = parseFloat(selectedOption.dataset.previousBalance || 0);

                // Show credit info
                const creditInfo = document.getElementById('creditInfo');
                creditInfo.classList.add('show');

                // Update credit display
                document.getElementById('creditLimit').textContent = formatCurrency(creditLimit);
                document.getElementById('previousBalance').textContent = formatCurrency(previousBalance);

                // Update totals
                updateCreditInfo();
            } else {
                // Hide credit info
                document.getElementById('creditInfo').classList.remove('show');
            }
        }

        // Update credit information
        function updateCreditInfo() {
            const customerSelect = document.getElementById('customerSelect');
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];

            if (selectedOption.value) {
                const creditLimit = parseFloat(selectedOption.dataset.creditLimit || 0);
                const previousBalance = parseFloat(selectedOption.dataset.previousBalance || 0);
                const currentInvoice = parseFloat(document.getElementById('totalAmount').value || 0);
                const newBalance = previousBalance + currentInvoice;

                // Update display
                document.getElementById('currentInvoice').textContent = formatCurrency(currentInvoice);
                document.getElementById('newBalance').textContent = formatCurrency(newBalance);

                // Update styling based on credit limit
                const creditInfo = document.getElementById('creditInfo');
                creditInfo.className = 'credit-info show';

                if (newBalance > creditLimit) {
                    creditInfo.classList.add('credit-danger');
                } else if (newBalance > creditLimit * 0.8) {
                    creditInfo.classList.add('credit-warning');
                }
            }
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('ar-IQ', {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }).format(amount) + ' د.ع';
        }

        // Update product information when product is selected
        function updateProductInfo(selectElement, index) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price || 0);

            // Update price field
            const priceInput = document.querySelector(`input[name="items[${index}][unit_price]"]`);
            if (priceInput) {
                priceInput.value = price.toFixed(2);
            }

            // Recalculate totals
            calculateItemTotal(index);
        }

        // Calculate item total
        function calculateItemTotal(index) {
            const quantity = parseFloat(document.querySelector(`input[name="items[${index}][quantity]"]`).value || 0);
            const unitPrice = parseFloat(document.querySelector(`input[name="items[${index}][unit_price]"]`).value || 0);
            const discount = parseFloat(document.querySelector(`input[name="items[${index}][discount_amount]"]`).value || 0);
            
            const lineTotal = (quantity * unitPrice) - discount;
            const totalInput = document.querySelector(`input[name="items[${index}][total_amount]"]`);
            
            if (totalInput) {
                totalInput.value = Math.max(0, lineTotal).toFixed(2);
            }
            
            calculateGrandTotal();
        }

        // Calculate grand total
        function calculateGrandTotal() {
            let subtotal = 0;
            let totalDiscount = 0;

            // Sum all item totals
            document.querySelectorAll('input[name*="[total_amount]"]').forEach(input => {
                subtotal += parseFloat(input.value || 0);
            });

            // Sum all discounts
            document.querySelectorAll('input[name*="[discount_amount]"]').forEach(input => {
                totalDiscount += parseFloat(input.value || 0);
            });

            const total = subtotal;

            // Update hidden fields
            document.getElementById('subtotalAmount').value = subtotal.toFixed(2);
            document.getElementById('discountAmount').value = totalDiscount.toFixed(2);
            document.getElementById('totalAmount').value = total.toFixed(2);

            // Update credit information
            updateCreditInfo();
        }

        // Add new item row
        function addItem() {
            const tbody = document.getElementById('invoiceItems');
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';

            let productOptions = '<option value="">اختر المنتج</option>';
            products.forEach(product => {
                productOptions += `<option value="${product.id}" data-price="${product.price}" data-stock="${product.stock}" data-code="${product.code}">
                    ${product.name} (${product.code}) - ${product.company}
                </option>`;
            });

            newRow.innerHTML = `
                <td style="text-align: right;">
                    <select name="items[${itemIndex}][product_id]" required class="form-control product-select searchable-select" onchange="updateProductInfo(this, ${itemIndex})" style="width: 100%; text-align: right;">
                        ${productOptions}
                    </select>
                </td>
                <td style="text-align: center;">
                    <input type="number" name="items[${itemIndex}][quantity]" min="1" step="1" required
                           placeholder="1" value="1"
                           onchange="calculateItemTotal(${itemIndex})" style="width: 100%; text-align: center;">
                </td>
                <td style="text-align: center;">
                    <input type="number" name="items[${itemIndex}][unit_price]" min="0" step="0.01" required
                           placeholder="0.00" value="0"
                           onchange="calculateItemTotal(${itemIndex})" style="width: 100%; text-align: center;">
                </td>
                <td style="text-align: center;">
                    <input type="number" name="items[${itemIndex}][discount_amount]" min="0" step="0.01"
                           placeholder="0.00" value="0"
                           onchange="calculateItemTotal(${itemIndex})" style="width: 100%; text-align: center;">
                </td>
                <td style="text-align: center;">
                    <input type="number" name="items[${itemIndex}][free_samples]" min="0" step="1"
                           placeholder="0" value="0" style="width: 100%; text-align: center;">
                </td>
                <td style="text-align: center;">
                    <input type="number" name="items[${itemIndex}][total_amount]" readonly
                           placeholder="0.00" value="0"
                           style="background: #f9fafb; width: 100%; text-align: center;">
                </td>
                <td style="text-align: center;">
                    <button type="button" onclick="removeItem(${itemIndex})" class="remove-item-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(newRow);

            // Initialize Select2 for the new row
            $(newRow).find('.searchable-select').select2({
                placeholder: 'ابحث واختر...',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                },
                width: '100%'
            });

            itemIndex++;
            updateRemoveButtons();
        }

        // Remove item row
        function removeItem(index) {
            const row = document.querySelector(`tr:has(select[name="items[${index}][product_id]"])`);
            if (row) {
                row.remove();
                calculateGrandTotal();
                updateRemoveButtons();
            }
        }

        // Update remove buttons state
        function updateRemoveButtons() {
            const rows = document.querySelectorAll('#invoiceItems tr');
            const removeButtons = document.querySelectorAll('.remove-item-btn');
            
            removeButtons.forEach((btn, index) => {
                btn.disabled = rows.length <= 1;
            });
        }

        // Save invoice
        function saveInvoice(action) {
            showNotification(`جاري ${action === 'draft' ? 'حفظ المسودة' : 'حفظ الفاتورة'}...`, 'info');

            // Collect form data
            const formData = new FormData();
            const customerSelect = document.getElementById('customerSelect');

            if (!customerSelect.value) {
                showNotification('يرجى اختيار العميل أولاً', 'error');
                return;
            }

            // Basic invoice data
            formData.append('customer_id', customerSelect.value);
            formData.append('invoice_date', document.querySelector('input[name="invoice_date"]').value);
            formData.append('due_date', document.querySelector('input[name="due_date"]').value);
            formData.append('sales_representative', document.querySelector('input[name="sales_representative"]').value);
            formData.append('warehouse_name', document.querySelector('input[name="warehouse_name"]').value);
            formData.append('type', document.querySelector('select[name="type"]').value);
            formData.append('notes', document.querySelector('textarea[name="notes"]').value);
            formData.append('payment_terms', document.querySelector('textarea[name="payment_terms"]').value);
            formData.append('status', action === 'draft' ? 'draft' : 'sent');

            // Invoice totals
            formData.append('subtotal_amount', document.getElementById('subtotalAmount').value);
            formData.append('discount_amount', document.getElementById('discountAmount').value);
            formData.append('total_amount', document.getElementById('totalAmount').value);

            // Invoice items
            const items = [];
            document.querySelectorAll('#invoiceItems tr').forEach((row, index) => {
                const productSelect = row.querySelector('select[name*="product_id"]');
                const quantity = row.querySelector('input[name*="quantity"]');
                const unitPrice = row.querySelector('input[name*="unit_price"]');
                const discount = row.querySelector('input[name*="discount_amount"]');
                const freeSamples = row.querySelector('input[name*="free_samples"]');
                const total = row.querySelector('input[name*="total_amount"]');

                if (productSelect && productSelect.value) {
                    items.push({
                        product_id: productSelect.value,
                        quantity: quantity.value,
                        unit_price: unitPrice.value,
                        discount_amount: discount.value,
                        free_samples: freeSamples.value,
                        total_amount: total.value
                    });
                }
            });

            if (items.length === 0) {
                showNotification('يرجى إضافة منتج واحد على الأقل', 'error');
                return;
            }

            formData.append('items', JSON.stringify(items));

            // Send to server
            fetch('/tenant/sales/invoices', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`تم ${action === 'draft' ? 'حفظ المسودة' : 'حفظ الفاتورة'} بنجاح!`, 'success');

                    // Redirect to invoice view after 2 seconds
                    setTimeout(() => {
                        if (data.invoice_id) {
                            window.location.href = `/tenant/sales/invoices/${data.invoice_id}`;
                        } else {
                            window.location.href = '/tenant/sales/invoices';
                        }
                    }, 2000);
                } else {
                    showNotification(data.message || 'حدث خطأ أثناء الحفظ', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('حدث خطأ في الاتصال بالخادم', 'error');
            });
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = 'notification';
            
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                warning: '#f59e0b',
                info: '#3b82f6'
            };

            notification.style.background = colors[type];
            notification.innerHTML = `<i class="fas fa-info-circle"></i> ${message}`;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Initialize Select2 for searchable dropdowns
        function initializeSelect2() {
            $('.searchable-select').select2({
                placeholder: 'ابحث واختر...',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    },
                    searching: function() {
                        return "جاري البحث...";
                    }
                },
                width: '100%'
            });

            // Add event listener for customer selection
            $('#customerSelect').on('change', function() {
                updateCustomerInfo();
            });
        }

        // Initialize on page load
        $(document).ready(function() {
            // Initialize Select2
            initializeSelect2();

            // Calculate initial totals
            calculateGrandTotal();
            updateRemoveButtons();

            // Show welcome message
            setTimeout(() => {
                showNotification('مرحباً بك في نظام الفواتير الاحترافي المحدث!', 'success');
            }, 500);
        });
    </script>
</body>
</html>
