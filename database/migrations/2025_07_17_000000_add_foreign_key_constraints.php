<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add foreign key constraints after all tables are created
        
        // Sales orders foreign keys
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
        
        // Sales order items foreign keys
        if (Schema::hasTable('sales_order_items')) {
            Schema::table('sales_order_items', function (Blueprint $table) {
                if (Schema::hasTable('products')) {
                    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                }
            });
        }
        
        // Invoice foreign keys
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (Schema::hasTable('customers') && !$this->foreignKeyExists('invoices', 'invoices_customer_id_foreign')) {
                    $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                }
            });
        }
        
        // Invoice items foreign keys
        if (Schema::hasTable('invoice_items')) {
            Schema::table('invoice_items', function (Blueprint $table) {
                if (Schema::hasTable('products') && !$this->foreignKeyExists('invoice_items', 'invoice_items_product_id_foreign')) {
                    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                }
            });
        }
        
        // Payment foreign keys
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (Schema::hasTable('customers') && !$this->foreignKeyExists('payments', 'payments_customer_id_foreign')) {
                    $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove foreign key constraints
        
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
        });
        
        if (Schema::hasTable('sales_order_items')) {
            Schema::table('sales_order_items', function (Blueprint $table) {
                if ($this->foreignKeyExists('sales_order_items', 'sales_order_items_product_id_foreign')) {
                    $table->dropForeign(['product_id']);
                }
            });
        }
        
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if ($this->foreignKeyExists('invoices', 'invoices_customer_id_foreign')) {
                    $table->dropForeign(['customer_id']);
                }
            });
        }
        
        if (Schema::hasTable('invoice_items')) {
            Schema::table('invoice_items', function (Blueprint $table) {
                if ($this->foreignKeyExists('invoice_items', 'invoice_items_product_id_foreign')) {
                    $table->dropForeign(['product_id']);
                }
            });
        }
        
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if ($this->foreignKeyExists('payments', 'payments_customer_id_foreign')) {
                    $table->dropForeign(['customer_id']);
                }
            });
        }
    }
    
    /**
     * Check if foreign key exists
     */
    private function foreignKeyExists($table, $foreignKey)
    {
        $keys = Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->listTableForeignKeys($table);
            
        foreach ($keys as $key) {
            if ($key->getName() === $foreignKey) {
                return true;
            }
        }
        
        return false;
    }
};
