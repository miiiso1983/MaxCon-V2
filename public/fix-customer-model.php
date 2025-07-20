<?php

/**
 * Fix Customer Model - Remove SoftDeletes if column doesn't exist
 * 
 * إصلاح نموذج العميل - إزالة SoftDeletes إذا لم يكن العمود موجود
 */

try {
    // Load Laravel
    require_once '../vendor/autoload.php';
    $app = require_once '../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo "✅ Laravel loaded successfully\n";
    
    // Test database connection
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Database connected: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
    
    // Check if deleted_at column exists in customers table
    $deletedAtColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers LIKE 'deleted_at'");
    
    if (empty($deletedAtColumn)) {
        echo "❌ Column 'deleted_at' missing from customers table. Adding...\n";
        
        // Add deleted_at column
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD COLUMN `deleted_at` timestamp NULL DEFAULT NULL AFTER `notes`");
        echo "✅ Column 'deleted_at' added to customers table\n";
        
        // Check for other missing authentication columns
        $passwordColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers LIKE 'password'");
        if (empty($passwordColumn)) {
            echo "❌ Authentication columns missing from customers table. Adding...\n";
            \Illuminate\Support\Facades\DB::statement("
                ALTER TABLE `customers` 
                ADD COLUMN `password` varchar(255) NULL AFTER `email`,
                ADD COLUMN `email_verified_at` timestamp NULL AFTER `password`,
                ADD COLUMN `remember_token` varchar(100) NULL AFTER `email_verified_at`,
                ADD COLUMN `last_login_at` timestamp NULL AFTER `remember_token`,
                ADD COLUMN `last_login_ip` varchar(45) NULL AFTER `last_login_at`,
                ADD COLUMN `previous_debt` decimal(15,2) NOT NULL DEFAULT 0.00 AFTER `current_balance`
            ");
            echo "✅ Authentication columns added to customers table\n";
        }
        
        // Check for company_name column
        $companyNameColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers LIKE 'company_name'");
        if (empty($companyNameColumn)) {
            echo "❌ Column 'company_name' missing from customers table. Adding...\n";
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD COLUMN `company_name` varchar(255) NULL AFTER `name`");
            echo "✅ Column 'company_name' added to customers table\n";
        }
        
        // Check for balance column
        $balanceColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers LIKE 'balance'");
        if (empty($balanceColumn)) {
            echo "❌ Column 'balance' missing from customers table. Adding...\n";
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD COLUMN `balance` decimal(15,2) NOT NULL DEFAULT 0.00 AFTER `current_balance`");
            echo "✅ Column 'balance' added to customers table\n";
        }
        
    } else {
        echo "✅ Column 'deleted_at' already exists in customers table\n";
    }
    
    // Test the customers table
    echo "\n🧪 Testing customers table...\n";
    
    $customerCount = \Illuminate\Support\Facades\DB::table('customers')->count();
    echo "✅ Customers table test successful: {$customerCount} records\n";
    
    // Show table structure
    echo "\n📋 Current customers table structure:\n";
    $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers");
    foreach ($columns as $column) {
        echo "  - {$column->Field} ({$column->Type}) " . ($column->Null === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
    
    echo "\n✅ Customer model fix completed successfully!\n";
    echo "\n📝 Next steps:\n";
    echo "  1. Refresh the page that was showing the error\n";
    echo "  2. The customers relationship should now work properly\n";
    echo "  3. SoftDeletes functionality is now available\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🔗 You can now access:\n";
echo "  - Admin Panel: /admin/tenants\n";
echo "  - Customer Management: /admin/tenants/{tenant_id}/customers\n";
echo "\n";
?>
