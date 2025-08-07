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
        // Create tenant 4 if it doesn't exist
        $tenant4 = DB::table('tenants')->where('id', 4)->first();
        if (!$tenant4) {
            DB::table('tenants')->insert([
                'id' => 4,
                'name' => 'MaxCon Pharmaceutical Company',
                'slug' => 'maxcon-pharma',
                'status' => 'active',
                'plan' => 'premium',
                'max_customers' => 1000,
                'current_customers_count' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update user 2 to tenant 4
        DB::table('users')->where('id', 2)->update([
            'tenant_id' => 4,
            'updated_at' => now(),
        ]);

        // Move existing data from tenant 1 to tenant 4
        // Move customers
        DB::table('customers')->where('tenant_id', 1)->update(['tenant_id' => 4]);

        // Move products
        DB::table('products')->where('tenant_id', 1)->update(['tenant_id' => 4]);

        // Move invoices
        DB::table('invoices')->where('tenant_id', 1)->update(['tenant_id' => 4]);

        // Move suppliers if they exist
        if (Schema::hasTable('suppliers')) {
            DB::table('suppliers')->where('tenant_id', 1)->update(['tenant_id' => 4]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Move data back to tenant 1
        DB::table('customers')->where('tenant_id', 4)->update(['tenant_id' => 1]);
        DB::table('products')->where('tenant_id', 4)->update(['tenant_id' => 1]);
        DB::table('invoices')->where('tenant_id', 4)->update(['tenant_id' => 1]);

        if (Schema::hasTable('suppliers')) {
            DB::table('suppliers')->where('tenant_id', 4)->update(['tenant_id' => 1]);
        }

        // Update user back to tenant 1
        DB::table('users')->where('id', 2)->update(['tenant_id' => 1]);

        // Delete tenant 4
        DB::table('tenants')->where('id', 4)->delete();
    }
};
