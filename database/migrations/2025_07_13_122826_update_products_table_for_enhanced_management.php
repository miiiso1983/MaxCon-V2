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
                $table->string('code')->after('name')->nullable();
            }
            if (!Schema::hasColumn('products', 'qr_code')) {
                $table->string('qr_code')->after('barcode')->nullable();
            }
            if (!Schema::hasColumn('products', 'short_description')) {
                $table->text('short_description')->after('description')->nullable();
            }
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')->after('short_description')->nullable()->constrained('product_categories')->onDelete('set null');
            }
            if (!Schema::hasColumn('products', 'country_of_origin')) {
                $table->string('country_of_origin')->after('manufacturer')->nullable();
            }
            if (!Schema::hasColumn('products', 'wholesale_price')) {
                $table->decimal('wholesale_price', 10, 2)->after('selling_price')->nullable();
            }
            if (!Schema::hasColumn('products', 'retail_price')) {
                $table->decimal('retail_price', 10, 2)->after('wholesale_price')->nullable();
            }
            if (!Schema::hasColumn('products', 'currency')) {
                $table->string('currency', 3)->after('retail_price')->default('IQD');
            }
            if (!Schema::hasColumn('products', 'base_unit')) {
                $table->string('base_unit')->after('currency')->default('piece');
            }
            if (!Schema::hasColumn('products', 'unit_weight')) {
                $table->decimal('unit_weight', 8, 3)->after('base_unit')->nullable();
            }
            if (!Schema::hasColumn('products', 'unit_volume')) {
                $table->decimal('unit_volume', 8, 3)->after('unit_weight')->nullable();
            }
            if (!Schema::hasColumn('products', 'dimensions')) {
                $table->string('dimensions')->after('unit_volume')->nullable();
            }
            if (!Schema::hasColumn('products', 'current_stock')) {
                $table->decimal('current_stock', 10, 2)->after('dimensions')->default(0);
            }
            if (!Schema::hasColumn('products', 'minimum_stock')) {
                $table->decimal('minimum_stock', 10, 2)->after('current_stock')->default(0);
            }
            if (!Schema::hasColumn('products', 'maximum_stock')) {
                $table->decimal('maximum_stock', 10, 2)->after('minimum_stock')->nullable();
            }
            if (!Schema::hasColumn('products', 'reorder_level')) {
                $table->decimal('reorder_level', 10, 2)->after('maximum_stock')->nullable();
            }
            if (!Schema::hasColumn('products', 'reorder_quantity')) {
                $table->decimal('reorder_quantity', 10, 2)->after('reorder_level')->nullable();
            }
            if (!Schema::hasColumn('products', 'status')) {
                $table->enum('status', ['active', 'inactive', 'discontinued', 'out_of_stock'])->after('reorder_quantity')->default('active');
            }
            if (!Schema::hasColumn('products', 'type')) {
                $table->enum('type', ['simple', 'variable', 'grouped', 'external'])->after('status')->default('simple');
            }
            if (!Schema::hasColumn('products', 'track_stock')) {
                $table->boolean('track_stock')->after('type')->default(true);
            }
            if (!Schema::hasColumn('products', 'allow_backorder')) {
                $table->boolean('allow_backorder')->after('track_stock')->default(false);
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->after('allow_backorder')->default(false);
            }
            if (!Schema::hasColumn('products', 'manufacturing_date')) {
                $table->date('manufacturing_date')->after('is_featured')->nullable();
            }
            if (!Schema::hasColumn('products', 'shelf_life_days')) {
                $table->integer('shelf_life_days')->after('expiry_date')->nullable();
            }
            if (!Schema::hasColumn('products', 'images')) {
                $table->json('images')->after('shelf_life_days')->nullable();
            }
            if (!Schema::hasColumn('products', 'primary_image')) {
                $table->string('primary_image')->after('images')->nullable();
            }
            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug')->after('primary_image')->nullable();
            }
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title')->after('slug')->nullable();
            }
            if (!Schema::hasColumn('products', 'meta_description')) {
                $table->text('meta_description')->after('meta_title')->nullable();
            }
            if (!Schema::hasColumn('products', 'tags')) {
                $table->json('tags')->after('meta_description')->nullable();
            }
            if (!Schema::hasColumn('products', 'attributes')) {
                $table->json('attributes')->after('tags')->nullable();
            }
            if (!Schema::hasColumn('products', 'variations')) {
                $table->json('variations')->after('attributes')->nullable();
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
