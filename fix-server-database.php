<?php

/**
 * Script to fix missing tables on production server
 * Run this script on the server to create missing tables
 */

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "🔧 Starting database fix script...\n";

try {
    // Check database connection
    DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
    
    // List of tables to check and create
    $tables = [
        'purchase_requests' => function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('request_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'cancelled', 'completed'])->default('draft');
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->date('required_date');
            $table->text('justification')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->decimal('estimated_total', 15, 2)->default(0);
            $table->decimal('approved_budget', 15, 2)->nullable();
            $table->string('budget_code')->nullable();
            $table->string('cost_center')->nullable();
            $table->json('attachments')->nullable();
            $table->text('special_instructions')->nullable();
            $table->boolean('is_urgent')->default(false);
            $table->date('deadline')->nullable();
            $table->timestamps();
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'requested_by']);
            $table->index(['tenant_id', 'required_date']);
        },
        
        'purchase_request_items' => function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->string('item_name');
            $table->string('item_code')->nullable();
            $table->text('description')->nullable();
            $table->string('unit')->default('piece');
            $table->decimal('quantity', 10, 2);
            $table->decimal('estimated_price', 10, 2)->default(0);
            $table->decimal('total_estimated', 12, 2)->default(0);
            $table->text('specifications')->nullable();
            $table->string('brand_preference')->nullable();
            $table->string('model_preference')->nullable();
            $table->text('technical_requirements')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'ordered'])->default('pending');
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['purchase_request_id']);
            $table->index(['product_id']);
        },
        
        'purchase_orders' => function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_request_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'sent', 'confirmed', 'partially_received', 'completed', 'cancelled'])->default('draft');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('order_date');
            $table->date('expected_delivery_date');
            $table->date('actual_delivery_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('currency', 3)->default('IQD');
            $table->enum('payment_terms', ['cash', 'credit_30', 'credit_60', 'credit_90', 'custom'])->default('credit_30');
            $table->text('payment_notes')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('delivery_contact')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->text('delivery_instructions')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->json('attachments')->nullable();
            $table->decimal('received_percentage', 5, 2)->default(0);
            $table->boolean('is_urgent')->default(false);
            $table->string('reference_number')->nullable();
            $table->timestamps();
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'supplier_id']);
            $table->index(['tenant_id', 'order_date']);
        },
        
        'purchase_order_items' => function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('purchase_request_item_id')->nullable()->constrained()->onDelete('set null');
            $table->string('item_name');
            $table->string('item_code')->nullable();
            $table->text('description')->nullable();
            $table->string('unit')->default('piece');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 12, 2);
            $table->text('specifications')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->decimal('received_quantity', 10, 2)->default(0);
            $table->decimal('remaining_quantity', 10, 2)->default(0);
            $table->enum('status', ['pending', 'partially_received', 'received', 'cancelled'])->default('pending');
            $table->text('quality_notes')->nullable();
            $table->enum('quality_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['purchase_order_id']);
            $table->index(['product_id']);
        },
        
        'quotations' => function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_request_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('quotation_number')->unique();
            $table->string('supplier_reference')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'sent', 'received', 'under_review', 'accepted', 'rejected', 'expired'])->default('draft');
            $table->date('quotation_date');
            $table->date('valid_until');
            $table->date('response_date')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('currency', 3)->default('IQD');
            $table->enum('payment_terms', ['cash', 'credit_30', 'credit_60', 'credit_90', 'custom'])->default('credit_30');
            $table->text('delivery_terms')->nullable();
            $table->integer('delivery_days')->nullable();
            $table->text('warranty_terms')->nullable();
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'supplier_id']);
            $table->index(['tenant_id', 'quotation_date']);
        },
        
        'quotation_items' => function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('purchase_request_item_id')->nullable()->constrained()->onDelete('set null');
            $table->string('item_name');
            $table->string('item_code')->nullable();
            $table->text('description')->nullable();
            $table->string('unit')->default('piece');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 12, 2);
            $table->text('specifications')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->text('technical_specs')->nullable();
            $table->integer('delivery_days')->nullable();
            $table->text('warranty_info')->nullable();
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->index(['quotation_id']);
            $table->index(['product_id']);
        },
        
        'supplier_contracts' => function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('contract_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['general', 'exclusive', 'framework', 'service'])->default('general');
            $table->enum('status', ['draft', 'active', 'expired', 'terminated', 'suspended'])->default('draft');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('signed_date')->nullable();
            $table->decimal('contract_value', 15, 2)->nullable();
            $table->string('currency', 3)->default('IQD');
            $table->enum('payment_terms', ['cash', 'credit_30', 'credit_60', 'credit_90', 'custom'])->default('credit_30');
            $table->text('payment_conditions')->nullable();
            $table->integer('delivery_days')->nullable();
            $table->text('delivery_terms')->nullable();
            $table->text('quality_standards')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('penalties')->nullable();
            $table->text('warranty_terms')->nullable();
            $table->boolean('auto_renewal')->default(false);
            $table->integer('renewal_period')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'supplier_id']);
            $table->index(['tenant_id', 'start_date', 'end_date']);
        }
    ];

    $created = 0;
    $skipped = 0;

    foreach ($tables as $tableName => $tableDefinition) {
        if (!Schema::hasTable($tableName)) {
            echo "📋 Creating table: {$tableName}...\n";
            Schema::create($tableName, $tableDefinition);
            $created++;
            echo "✅ Table {$tableName} created successfully\n";
        } else {
            echo "⏭️  Table {$tableName} already exists, skipping\n";
            $skipped++;
        }
    }

    // Add is_active column to tenants if not exists
    if (Schema::hasTable('tenants') && !Schema::hasColumn('tenants', 'is_active')) {
        echo "📋 Adding is_active column to tenants table...\n";
        Schema::table('tenants', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('status');
        });
        echo "✅ Column is_active added to tenants table\n";
    }

    echo "\n🎉 Database fix completed!\n";
    echo "📊 Summary:\n";
    echo "   - Tables created: {$created}\n";
    echo "   - Tables skipped: {$skipped}\n";
    echo "   - Total tables checked: " . count($tables) . "\n";
    
    // Test the fix
    echo "\n🧪 Testing database...\n";
    $testQuery = DB::table('purchase_requests')->count();
    echo "✅ purchase_requests table test: {$testQuery} records\n";
    
    echo "\n✅ All done! The purchasing module should work now.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
    exit(1);
}
