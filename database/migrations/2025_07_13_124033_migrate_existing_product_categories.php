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
        // Create default categories from existing product categories
        // Check if category column exists first
        if (Schema::hasColumn('products', 'category')) {
            $existingCategories = DB::table('products')
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->pluck('category');
        } else {
            $existingCategories = collect();
        }

        foreach ($existingCategories as $categoryName) {
            // Check if category already exists
            $existingCategory = DB::table('product_categories')
                ->where('name', $categoryName)
                ->first();

            if (!$existingCategory) {
                // Get a tenant_id from products table
                $tenantId = DB::table('products')
                    ->where('category', $categoryName)
                    ->value('tenant_id');

                if ($tenantId) {
                    // Generate unique code
                    $lastCategory = DB::table('product_categories')
                        ->where('tenant_id', $tenantId)
                        ->orderBy('id', 'desc')
                        ->first();

                    $number = $lastCategory ? (int) substr($lastCategory->code, 4) + 1 : 1;
                    $code = 'CAT-' . str_pad($number, 4, '0', STR_PAD_LEFT);

                    // Create the category
                    $categoryId = DB::table('product_categories')->insertGetId([
                        'tenant_id' => $tenantId,
                        'name' => $categoryName,
                        'code' => $code,
                        'status' => 'active',
                        'sort_order' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Update products to use the new category_id
                    DB::table('products')
                        ->where('category', $categoryName)
                        ->where('tenant_id', $tenantId)
                        ->update(['category_id' => $categoryId]);
                }
            } else {
                // Update products to use existing category_id
                DB::table('products')
                    ->where('category', $categoryName)
                    ->update(['category_id' => $existingCategory->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
