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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('discount_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed_amount', 'buy_x_get_y', 'bulk_discount'])->default('percentage');
            $table->decimal('value', 15, 2); // Percentage or fixed amount
            $table->decimal('minimum_amount', 15, 2)->default(0); // Minimum order amount
            $table->decimal('maximum_discount', 15, 2)->nullable(); // Maximum discount amount
            $table->integer('minimum_quantity')->default(0); // Minimum quantity
            $table->enum('applies_to', ['all_products', 'specific_products', 'specific_categories', 'specific_customers'])->default('all_products');
            $table->json('applicable_products')->nullable(); // Product IDs
            $table->json('applicable_categories')->nullable(); // Category names
            $table->json('applicable_customers')->nullable(); // Customer IDs
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('usage_limit')->nullable(); // Total usage limit
            $table->integer('usage_limit_per_customer')->nullable();
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('stackable')->default(false); // Can be combined with other discounts
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('terms_conditions')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'discount_code']);
            $table->index(['tenant_id', 'is_active']);
            $table->index(['start_date', 'end_date']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
