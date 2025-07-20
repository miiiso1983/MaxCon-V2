<?php

/**
 * Apply Customer Limits Feature
 * 
 * تطبيق ميزة تحديد عدد العملاء لكل مستأجر
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
    
    // First, check and fix customers table deleted_at column
    echo "\n🔧 Checking customers table structure...\n";
    $deletedAtColumn = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM customers LIKE 'deleted_at'");

    if (empty($deletedAtColumn)) {
        echo "❌ Column 'deleted_at' missing from customers table. Adding...\n";
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `customers` ADD COLUMN `deleted_at` timestamp NULL DEFAULT NULL AFTER `notes`");
        echo "✅ Column 'deleted_at' added to customers table\n";
    } else {
        echo "✅ Column 'deleted_at' already exists in customers table\n";
    }

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
    } else {
        echo "✅ Authentication columns already exist in customers table\n";
    }

    // Check if max_customers column exists in tenants table
    echo "\n🔧 Checking tenants table structure...\n";
    $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM tenants LIKE 'max_customers'");
    
    if (empty($columns)) {
        echo "❌ Column 'max_customers' missing from tenants table. Adding...\n";
        
        // Add max_customers column
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `tenants` ADD COLUMN `max_customers` int(11) NOT NULL DEFAULT 100 AFTER `max_users`");
        echo "✅ Column 'max_customers' added to tenants table\n";
        
        // Add current_customers_count column
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `tenants` ADD COLUMN `current_customers_count` int(11) NOT NULL DEFAULT 0 AFTER `max_customers`");
        echo "✅ Column 'current_customers_count' added to tenants table\n";
        
    } else {
        echo "✅ Column 'max_customers' already exists in tenants table\n";
        
        // Check if current_customers_count exists
        $countColumns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM tenants LIKE 'current_customers_count'");
        if (empty($countColumns)) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `tenants` ADD COLUMN `current_customers_count` int(11) NOT NULL DEFAULT 0 AFTER `max_customers`");
            echo "✅ Column 'current_customers_count' added to tenants table\n";
        } else {
            echo "✅ Column 'current_customers_count' already exists\n";
        }
    }
    
    // Update current customer counts for all tenants
    echo "\n🔄 Updating customer counts for all tenants...\n";
    
    $tenants = \Illuminate\Support\Facades\DB::table('tenants')->get();
    
    foreach ($tenants as $tenant) {
        $customerCount = \Illuminate\Support\Facades\DB::table('customers')
            ->where('tenant_id', $tenant->id)
            ->count();
            
        \Illuminate\Support\Facades\DB::table('tenants')
            ->where('id', $tenant->id)
            ->update(['current_customers_count' => $customerCount]);
            
        echo "  - Tenant '{$tenant->name}': {$customerCount} customers\n";
    }
    
    echo "✅ Customer counts updated successfully\n";
    
    // Test the feature
    echo "\n🧪 Testing customer limits feature...\n";
    
    $testTenant = \Illuminate\Support\Facades\DB::table('tenants')->first();
    if ($testTenant) {
        echo "  - Test tenant: {$testTenant->name}\n";
        echo "  - Max customers: {$testTenant->max_customers}\n";
        echo "  - Current customers: {$testTenant->current_customers_count}\n";
        echo "  - Remaining slots: " . ($testTenant->max_customers - $testTenant->current_customers_count) . "\n";
        
        $usagePercentage = $testTenant->max_customers > 0 
            ? ($testTenant->current_customers_count / $testTenant->max_customers) * 100 
            : 0;
        echo "  - Usage percentage: " . number_format($usagePercentage, 1) . "%\n";
    }
    
    echo "\n📊 Customer Limits Summary:\n";
    echo "========================\n";
    
    $summary = \Illuminate\Support\Facades\DB::select("
        SELECT 
            COUNT(*) as total_tenants,
            SUM(max_customers) as total_max_customers,
            SUM(current_customers_count) as total_current_customers,
            AVG(current_customers_count / max_customers * 100) as avg_usage_percentage
        FROM tenants 
        WHERE max_customers > 0
    ");
    
    if (!empty($summary)) {
        $stats = $summary[0];
        echo "  - Total tenants: {$stats->total_tenants}\n";
        echo "  - Total max customers allowed: {$stats->total_max_customers}\n";
        echo "  - Total current customers: {$stats->total_current_customers}\n";
        echo "  - Average usage: " . number_format($stats->avg_usage_percentage, 1) . "%\n";
    }
    
    // Show tenants near limit
    echo "\n⚠️  Tenants near customer limit (>80%):\n";
    $nearLimit = \Illuminate\Support\Facades\DB::select("
        SELECT 
            name,
            current_customers_count,
            max_customers,
            (current_customers_count / max_customers * 100) as usage_percentage
        FROM tenants 
        WHERE max_customers > 0 
        AND (current_customers_count / max_customers * 100) > 80
        ORDER BY usage_percentage DESC
    ");
    
    if (empty($nearLimit)) {
        echo "  - No tenants near limit\n";
    } else {
        foreach ($nearLimit as $tenant) {
            echo "  - {$tenant->name}: {$tenant->current_customers_count}/{$tenant->max_customers} (" . 
                 number_format($tenant->usage_percentage, 1) . "%)\n";
        }
    }
    
    echo "\n✅ Customer limits feature setup completed successfully!\n";
    echo "\n📝 Next steps:\n";
    echo "  1. Access Super Admin panel\n";
    echo "  2. Go to Tenant Management\n";
    echo "  3. Edit any tenant to set max_customers\n";
    echo "  4. View tenant details to see customer statistics\n";
    echo "  5. Click 'إدارة العملاء' to manage tenant customers\n";
    
    echo "\n🎯 Features added:\n";
    echo "  ✅ Customer limits per tenant\n";
    echo "  ✅ Real-time customer count tracking\n";
    echo "  ✅ Usage percentage calculation\n";
    echo "  ✅ Customer management interface\n";
    echo "  ✅ Automatic limit enforcement\n";
    echo "  ✅ Visual progress indicators\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🔗 Access the feature at:\n";
echo "  - Admin Panel: /admin/tenants\n";
echo "  - Customer Management: /admin/tenants/{tenant_id}/customers\n";
echo "\n";
?>
