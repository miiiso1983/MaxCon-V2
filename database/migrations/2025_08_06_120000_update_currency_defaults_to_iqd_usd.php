<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update customers table
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('currency', 3)->default('IQD')->change();
            });
            
            // Update existing records
            DB::table('customers')->whereNotIn('currency', ['IQD', 'USD'])->update(['currency' => 'IQD']);
        }

        // Update suppliers table
        if (Schema::hasTable('suppliers')) {
            if (Schema::hasColumn('suppliers', 'currency')) {
                Schema::table('suppliers', function (Blueprint $table) {
                    $table->string('currency', 3)->default('IQD')->change();
                });
                
                // Update existing records
                DB::table('suppliers')->whereNotIn('currency', ['IQD', 'USD'])->update(['currency' => 'IQD']);
            }
        }

        // Update purchase_orders table
        if (Schema::hasTable('purchase_orders')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->string('currency', 3)->default('IQD')->change();
            });
            
            // Update existing records
            DB::table('purchase_orders')->whereNotIn('currency', ['IQD', 'USD'])->update(['currency' => 'IQD']);
        }

        // Update payments table
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('currency', 3)->default('IQD')->change();
            });
            
            // Update existing records
            DB::table('payments')->whereNotIn('currency', ['IQD', 'USD'])->update(['currency' => 'IQD']);
        }

        // Update cost_centers table
        if (Schema::hasTable('cost_centers')) {
            Schema::table('cost_centers', function (Blueprint $table) {
                $table->string('currency_code', 3)->default('IQD')->change();
            });
            
            // Update existing records
            DB::table('cost_centers')->whereNotIn('currency_code', ['IQD', 'USD'])->update(['currency_code' => 'IQD']);
        }

        // Update invoices table if exists
        if (Schema::hasTable('invoices')) {
            if (Schema::hasColumn('invoices', 'currency')) {
                Schema::table('invoices', function (Blueprint $table) {
                    $table->string('currency', 3)->default('IQD')->change();
                });
                
                // Update existing records
                DB::table('invoices')->whereNotIn('currency', ['IQD', 'USD'])->update(['currency' => 'IQD']);
            }
        }

        // Update products table if exists
        if (Schema::hasTable('products')) {
            if (Schema::hasColumn('products', 'currency')) {
                Schema::table('products', function (Blueprint $table) {
                    $table->string('currency', 3)->default('IQD')->change();
                });
                
                // Update existing records
                DB::table('products')->whereNotIn('currency', ['IQD', 'USD'])->update(['currency' => 'IQD']);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert changes if needed
        // Note: This is optional as we're just changing defaults
    }
};
