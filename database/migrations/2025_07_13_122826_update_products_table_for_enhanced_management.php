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
        Schema::table('products', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('products', 'code')) {
                $table->string('code')->nullable();
            }
            if (!Schema::hasColumn('products', 'qr_code')) {
                $table->string('qr_code')->nullable();
            }
            if (!Schema::hasColumn('products', 'short_description')) {
                $table->text('short_description')->nullable();
            }
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            }
            if (!Schema::hasColumn('products', 'country_of_origin')) {
                $table->string('country_of_origin')->nullable();
            }
            if (!Schema::hasColumn('products', 'wholesale_price')) {
                $table->decimal('wholesale_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('products', 'retail_price')) {
                $table->decimal('retail_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('products', 'currency')) {
                $table->string('currency', 3)->default('IQD');
            }
            if (!Schema::hasColumn('products', 'base_unit')) {
                $table->string('base_unit')->default('piece');
            }
            if (!Schema::hasColumn('products', 'unit_weight')) {
                $table->decimal('unit_weight', 8, 3)->nullable();
            }
            if (!Schema::hasColumn('products', 'unit_volume')) {
                $table->decimal('unit_volume', 8, 3)->nullable();
            }
            if (!Schema::hasColumn('products', 'dimensions')) {
                $table->string('dimensions')->nullable();
            }
            if (!Schema::hasColumn('products', 'current_stock')) {
                $table->decimal('current_stock', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'minimum_stock')) {
                $table->decimal('minimum_stock', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'maximum_stock')) {
                $table->decimal('maximum_stock', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('products', 'reorder_level')) {
                $table->decimal('reorder_level', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('products', 'reorder_quantity')) {
                $table->decimal('reorder_quantity', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('products', 'status')) {
                $table->enum('status', ['active', 'inactive', 'discontinued', 'out_of_stock'])->default('active');
            }
            if (!Schema::hasColumn('products', 'type')) {
                $table->enum('type', ['simple', 'variable', 'grouped', 'external'])->default('simple');
            }
            if (!Schema::hasColumn('products', 'track_stock')) {
                $table->boolean('track_stock')->default(true);
            }
            if (!Schema::hasColumn('products', 'allow_backorder')) {
                $table->boolean('allow_backorder')->default(false);
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
            if (!Schema::hasColumn('products', 'manufacturing_date')) {
                $table->date('manufacturing_date')->nullable();
            }
            if (!Schema::hasColumn('products', 'shelf_life_days')) {
                $table->integer('shelf_life_days')->nullable();
            }
            if (!Schema::hasColumn('products', 'images')) {
                $table->json('images')->nullable();
            }
            if (!Schema::hasColumn('products', 'primary_image')) {
                $table->string('primary_image')->nullable();
            }
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->nullable();
            }
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            if (!Schema::hasColumn('products', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
            if (!Schema::hasColumn('products', 'tags')) {
                $table->json('tags')->nullable();
            }
            if (!Schema::hasColumn('products', 'attributes')) {
                $table->json('attributes')->nullable();
            }
            if (!Schema::hasColumn('products', 'variations')) {
                $table->json('variations')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
