<?php

/**
 * Fix Customers Table - Add missing columns
 * 
 * إصلاح جدول العملاء - إضافة الأعمدة المفقودة
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
    
    echo "\n🔧 Checking customers table structure...\n";
    
    // Check if deleted_at column exists
    $deletedAtColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers LIKE 'deleted_at'");
    
    if (empty($deletedAtColumn)) {
        echo "❌ Column 'deleted_at' missing from customers table. Adding...\n";
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD COLUMN `deleted_at` timestamp NULL DEFAULT NULL");
        echo "✅ Column 'deleted_at' added to customers table\n";
    } else {
        echo "✅ Column 'deleted_at' already exists in customers table\n";
    }
    
    // Check for password column
    $passwordColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers LIKE 'password'");
    if (empty($passwordColumn)) {
        echo "❌ Authentication columns missing from customers table. Adding...\n";
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE `customers` 
            ADD COLUMN `password` varchar(255) NULL AFTER `email`,
            ADD COLUMN `email_verified_at` timestamp NULL AFTER `password`,
            ADD COLUMN `remember_token` varchar(100) NULL AFTER `email_verified_at`,
            ADD COLUMN `last_login_at` timestamp NULL AFTER `remember_token`,
            ADD COLUMN `last_login_ip` varchar(45) NULL AFTER `last_login_at`
        ");
        echo "✅ Authentication columns added to customers table\n";
    } else {
        echo "✅ Authentication columns already exist in customers table\n";
    }
    
    // Check for company_name column
    $companyNameColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers LIKE 'company_name'");
    if (empty($companyNameColumn)) {
        echo "❌ Column 'company_name' missing from customers table. Adding...\n";
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD COLUMN `company_name` varchar(255) NULL AFTER `name`");
        echo "✅ Column 'company_name' added to customers table\n";
    } else {
        echo "✅ Column 'company_name' already exists in customers table\n";
    }
    
    // Check for balance column
    $balanceColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers LIKE 'balance'");
    if (empty($balanceColumn)) {
        echo "❌ Column 'balance' missing from customers table. Adding...\n";
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD COLUMN `balance` decimal(15,2) NOT NULL DEFAULT 0.00");
        echo "✅ Column 'balance' added to customers table\n";
    } else {
        echo "✅ Column 'balance' already exists in customers table\n";
    }
    
    // Check for previous_debt column
    $previousDebtColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers LIKE 'previous_debt'");
    if (empty($previousDebtColumn)) {
        echo "❌ Column 'previous_debt' missing from customers table. Adding...\n";
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD COLUMN `previous_debt` decimal(15,2) NOT NULL DEFAULT 0.00");
        echo "✅ Column 'previous_debt' added to customers table\n";
    } else {
        echo "✅ Column 'previous_debt' already exists in customers table\n";
    }
    
    // Check for credit_limit column
    $creditLimitColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers LIKE 'credit_limit'");
    if (empty($creditLimitColumn)) {
        echo "❌ Column 'credit_limit' missing from customers table. Adding...\n";
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD COLUMN `credit_limit` decimal(15,2) NOT NULL DEFAULT 0.00");
        echo "✅ Column 'credit_limit' added to customers table\n";
    } else {
        echo "✅ Column 'credit_limit' already exists in customers table\n";
    }
    
    // Check for customer_code column
    $customerCodeColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers LIKE 'customer_code'");
    if (empty($customerCodeColumn)) {
        echo "❌ Column 'customer_code' missing from customers table. Adding...\n";
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD COLUMN `customer_code` varchar(50) NULL UNIQUE");
        echo "✅ Column 'customer_code' added to customers table\n";
    } else {
        echo "✅ Column 'customer_code' already exists in customers table\n";
    }
    
    // Now check tenants table for customer limits
    echo "\n🔧 Checking tenants table for customer limits...\n";
    
    $maxCustomersColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM tenants LIKE 'max_customers'");
    if (empty($maxCustomersColumn)) {
        echo "❌ Customer limit columns missing from tenants table. Adding...\n";
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE `tenants` 
            ADD COLUMN `max_customers` int(11) NOT NULL DEFAULT 100 AFTER `max_users`,
            ADD COLUMN `current_customers_count` int(11) NOT NULL DEFAULT 0 AFTER `max_customers`
        ");
        echo "✅ Customer limit columns added to tenants table\n";
    } else {
        echo "✅ Customer limit columns already exist in tenants table\n";
    }
    
    // Update current customer counts for all tenants
    echo "\n🔄 Updating customer counts for all tenants...\n";
    
    $tenants = \Illuminate\Support\Facades\DB::table('tenants')->get();
    
    foreach ($tenants as $tenant) {
        $customerCount = \Illuminate\Support\Facades\DB::table('customers')
            ->where('tenant_id', $tenant->id)
            ->whereNull('deleted_at')
            ->count();
            
        \Illuminate\Support\Facades\DB::table('tenants')
            ->where('id', $tenant->id)
            ->update(['current_customers_count' => $customerCount]);
            
        echo "  - Tenant '{$tenant->name}': {$customerCount} customers\n";
    }
    
    echo "✅ Customer counts updated successfully\n";
    
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
    
    echo "\n✅ Customers table fix completed successfully!\n";
    echo "\n📝 Next steps:\n";
    echo "  1. Refresh the page that was showing the error\n";
    echo "  2. The customers relationship should now work properly\n";
    echo "  3. Customer management features are now available\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🔗 You can now access:\n";
echo "  - Admin Panel: /admin/tenants\n";
echo "  - Customer Management: /admin/tenants/{tenant_id}/customers\n";
echo "\n";
?>
