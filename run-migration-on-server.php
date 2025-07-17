<?php
/**
 * Script to run migration on server
 * Upload this file to the server and run: php run-migration-on-server.php
 */

echo "🔧 Running accounting system migration on server...\n";

// Check if we're in Laravel environment
if (!function_exists('app')) {
    echo "❌ This script must be run in Laravel environment\n";
    echo "💡 Run this instead: php artisan migrate\n";
    exit(1);
}

try {
    // Run the specific migration
    echo "📋 Running migration: add_missing_columns_to_accounting_tables\n";
    
    $exitCode = \Illuminate\Support\Facades\Artisan::call('migrate', [
        '--path' => 'database/migrations/2025_07_17_184354_add_missing_columns_to_accounting_tables.php',
        '--force' => true
    ]);
    
    if ($exitCode === 0) {
        echo "✅ Migration completed successfully!\n";
        
        // Test the database
        echo "\n📊 Testing database structure...\n";
        
        // Check if columns exist
        $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM chart_of_accounts LIKE 'cost_center_id'");
        if (count($columns) > 0) {
            echo "✅ cost_center_id column exists\n";
        } else {
            echo "❌ cost_center_id column missing\n";
        }
        
        // Test the problematic query
        try {
            $count = \Illuminate\Support\Facades\DB::table('chart_of_accounts')
                ->where('tenant_id', 4)
                ->whereNull('cost_center_id')
                ->whereNull('deleted_at')
                ->count();
            echo "✅ Test query successful: $count accounts without cost center\n";
        } catch (\Exception $e) {
            echo "❌ Test query failed: " . $e->getMessage() . "\n";
        }
        
        // Run seeder
        echo "\n🌱 Running seeder...\n";
        $exitCode = \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'AccountingSystemSeeder',
            '--force' => true
        ]);
        
        if ($exitCode === 0) {
            echo "✅ Seeder completed successfully!\n";
        } else {
            echo "⚠️  Seeder had issues, but migration is complete\n";
        }
        
    } else {
        echo "❌ Migration failed with exit code: $exitCode\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n💡 Manual solution:\n";
    echo "1. Upload server-fix-columns.sql to your server\n";
    echo "2. Run it in phpMyAdmin or MySQL command line\n";
    echo "3. Or run: php artisan migrate --force\n";
}

echo "\n🎉 Script completed!\n";
?>
