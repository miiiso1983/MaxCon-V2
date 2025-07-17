<?php
/**
 * Emergency Database Fix Script
 * Access via: https://your-domain.com/emergency-fix.php
 */

// Security check - remove this line after fixing
if (!isset($_GET['fix']) || $_GET['fix'] !== 'database') {
    die('🔒 Access denied. Use: ?fix=database');
}

echo "<h1>🔧 Emergency Database Fix</h1>";
echo "<pre>";

try {
    // Load Laravel
    require_once '../vendor/autoload.php';
    $app = require_once '../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    echo "✅ Laravel loaded successfully\n";
    
    // Test database connection
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connected: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
    
    // Check if purchase_requests table exists
    $tableExists = DB::select("SHOW TABLES LIKE 'purchase_requests'");
    
    if (empty($tableExists)) {
        echo "❌ Table 'purchase_requests' does not exist. Creating...\n";
        
        // Create the table using raw SQL
        $sql = "
        CREATE TABLE `purchase_requests` (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `tenant_id` bigint(20) unsigned NOT NULL,
          `request_number` varchar(255) NOT NULL,
          `title` varchar(255) NOT NULL,
          `description` text DEFAULT NULL,
          `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
          `status` enum('draft','pending','approved','rejected','cancelled','completed') NOT NULL DEFAULT 'draft',
          `requested_by` bigint(20) unsigned NOT NULL,
          `department_id` bigint(20) unsigned DEFAULT NULL,
          `required_date` date NOT NULL,
          `justification` text DEFAULT NULL,
          `approved_by` bigint(20) unsigned DEFAULT NULL,
          `approved_at` timestamp NULL DEFAULT NULL,
          `approval_notes` text DEFAULT NULL,
          `rejected_by` bigint(20) unsigned DEFAULT NULL,
          `rejected_at` timestamp NULL DEFAULT NULL,
          `rejection_reason` text DEFAULT NULL,
          `estimated_total` decimal(15,2) NOT NULL DEFAULT 0.00,
          `approved_budget` decimal(15,2) DEFAULT NULL,
          `budget_code` varchar(255) DEFAULT NULL,
          `cost_center` varchar(255) DEFAULT NULL,
          `attachments` json DEFAULT NULL,
          `special_instructions` text DEFAULT NULL,
          `is_urgent` tinyint(1) NOT NULL DEFAULT 0,
          `deadline` date DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          UNIQUE KEY `purchase_requests_request_number_unique` (`request_number`),
          KEY `purchase_requests_tenant_id_index` (`tenant_id`),
          KEY `purchase_requests_requested_by_index` (`requested_by`),
          KEY `purchase_requests_tenant_id_status_index` (`tenant_id`,`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        DB::statement($sql);
        echo "✅ Table 'purchase_requests' created successfully\n";
        
        // Add foreign key constraints (if tables exist)
        try {
            $tenantExists = DB::select("SHOW TABLES LIKE 'tenants'");
            $usersExists = DB::select("SHOW TABLES LIKE 'users'");
            
            if (!empty($tenantExists)) {
                DB::statement("ALTER TABLE `purchase_requests` ADD CONSTRAINT `purchase_requests_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE");
                echo "✅ Added foreign key constraint for tenant_id\n";
            }
            
            if (!empty($usersExists)) {
                DB::statement("ALTER TABLE `purchase_requests` ADD CONSTRAINT `purchase_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE");
                DB::statement("ALTER TABLE `purchase_requests` ADD CONSTRAINT `purchase_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL");
                DB::statement("ALTER TABLE `purchase_requests` ADD CONSTRAINT `purchase_requests_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL");
                echo "✅ Added foreign key constraints for user references\n";
            }
        } catch (Exception $e) {
            echo "⚠️  Warning: Could not add foreign key constraints: " . $e->getMessage() . "\n";
        }
        
    } else {
        echo "✅ Table 'purchase_requests' already exists\n";
    }
    
    // Create purchase_request_items table
    $itemsTableExists = DB::select("SHOW TABLES LIKE 'purchase_request_items'");
    
    if (empty($itemsTableExists)) {
        echo "❌ Table 'purchase_request_items' does not exist. Creating...\n";
        
        $sql = "
        CREATE TABLE `purchase_request_items` (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `purchase_request_id` bigint(20) unsigned NOT NULL,
          `product_id` bigint(20) unsigned DEFAULT NULL,
          `item_name` varchar(255) NOT NULL,
          `item_code` varchar(255) DEFAULT NULL,
          `description` text DEFAULT NULL,
          `unit` varchar(255) NOT NULL DEFAULT 'piece',
          `quantity` decimal(10,2) NOT NULL,
          `estimated_price` decimal(10,2) NOT NULL DEFAULT 0.00,
          `total_estimated` decimal(12,2) NOT NULL DEFAULT 0.00,
          `specifications` text DEFAULT NULL,
          `brand_preference` varchar(255) DEFAULT NULL,
          `model_preference` varchar(255) DEFAULT NULL,
          `technical_requirements` text DEFAULT NULL,
          `status` enum('pending','approved','rejected','ordered') NOT NULL DEFAULT 'pending',
          `notes` text DEFAULT NULL,
          `sort_order` int(11) NOT NULL DEFAULT 0,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `purchase_request_items_purchase_request_id_index` (`purchase_request_id`),
          KEY `purchase_request_items_product_id_index` (`product_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
        
        DB::statement($sql);
        echo "✅ Table 'purchase_request_items' created successfully\n";
        
        // Add foreign key constraint
        try {
            DB::statement("ALTER TABLE `purchase_request_items` ADD CONSTRAINT `purchase_request_items_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE");
            echo "✅ Added foreign key constraint for purchase_request_items\n";
        } catch (Exception $e) {
            echo "⚠️  Warning: Could not add foreign key constraint: " . $e->getMessage() . "\n";
        }
    } else {
        echo "✅ Table 'purchase_request_items' already exists\n";
    }
    
    // Check if is_active column exists in tenants table
    try {
        $columns = DB::select("SHOW COLUMNS FROM tenants LIKE 'is_active'");
        if (empty($columns)) {
            echo "❌ Column 'is_active' missing from tenants table. Adding...\n";
            DB::statement("ALTER TABLE `tenants` ADD COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 1 AFTER `status`");
            echo "✅ Column 'is_active' added to tenants table\n";
        } else {
            echo "✅ Column 'is_active' already exists in tenants table\n";
        }
    } catch (Exception $e) {
        echo "⚠️  Warning: Could not check/add is_active column: " . $e->getMessage() . "\n";
    }
    
    // Test the fix
    echo "\n🧪 Testing the fix...\n";
    $count = DB::table('purchase_requests')->count();
    echo "✅ purchase_requests table test successful: {$count} records\n";
    
    // Show all tables
    echo "\n📋 Current tables:\n";
    $tables = DB::select("SHOW TABLES");
    $tableColumn = 'Tables_in_' . DB::getDatabaseName();
    foreach ($tables as $table) {
        if (strpos($table->$tableColumn, 'purchase') !== false || 
            strpos($table->$tableColumn, 'tenant') !== false ||
            strpos($table->$tableColumn, 'user') !== false) {
            echo "  - " . $table->$tableColumn . "\n";
        }
    }
    
    echo "\n🎉 Database fix completed successfully!\n";
    echo "🔗 You can now access: https://your-domain.com/tenant/purchasing/purchase-requests\n";
    echo "\n⚠️  IMPORTANT: Delete this file after fixing: /public/emergency-fix.php\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
    echo "\n🔍 Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
echo "<hr>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>Test the purchasing module: <a href='/tenant/purchasing/purchase-requests' target='_blank'>Purchase Requests</a></li>";
echo "<li>Delete this file: <code>/public/emergency-fix.php</code></li>";
echo "<li>Run migrations: <code>php artisan migrate</code></li>";
echo "</ol>";
?>
