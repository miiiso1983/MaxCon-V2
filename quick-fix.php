<?php
/**
 * Quick Database Fix Script
 * Run: php quick-fix.php
 */

echo "🔧 Quick Database Fix Starting...\n";

try {
    // Load Laravel
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    use Illuminate\Support\Facades\DB;

    echo "✅ Laravel loaded\n";
    
    // Test connection
    DB::connection()->getPdo();
    echo "✅ Database connected\n";
    
    // Quick fix for purchase_requests table
    $sql = "
    CREATE TABLE IF NOT EXISTS `purchase_requests` (
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
      UNIQUE KEY `request_number_unique` (`request_number`),
      KEY `tenant_id_index` (`tenant_id`),
      KEY `requested_by_index` (`requested_by`),
      KEY `tenant_status_index` (`tenant_id`,`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    DB::statement($sql);
    echo "✅ purchase_requests table created/verified\n";
    
    // Create items table
    $sql2 = "
    CREATE TABLE IF NOT EXISTS `purchase_request_items` (
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
      KEY `purchase_request_id_index` (`purchase_request_id`),
      KEY `product_id_index` (`product_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    DB::statement($sql2);
    echo "✅ purchase_request_items table created/verified\n";
    
    // Add is_active to tenants if not exists
    try {
        DB::statement("ALTER TABLE `tenants` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 1");
        echo "✅ is_active column added to tenants\n";
    } catch (Exception $e) {
        echo "⚠️  is_active column may already exist\n";
    }
    
    // Test the fix
    $count = DB::table('purchase_requests')->count();
    echo "✅ Test successful: {$count} records in purchase_requests\n";
    
    echo "\n🎉 Quick fix completed!\n";
    echo "🔗 Test at: /tenant/purchasing/purchase-requests\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
