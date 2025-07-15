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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('purchase_request_item_id')->nullable()->constrained()->onDelete('set null');

            // Item Information
            $table->string('item_name');
            $table->string('item_code')->nullable();
            $table->text('description')->nullable();
            $table->string('unit')->default('piece');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);

            // Product Details
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('origin_country')->nullable();
            $table->text('specifications')->nullable();
            $table->integer('warranty_months')->default(0);
            $table->text('warranty_terms')->nullable();

            // Delivery Information
            $table->integer('delivery_days')->nullable();
            $table->text('delivery_terms')->nullable();
            $table->decimal('availability_percentage', 5, 2)->default(100);

            // Evaluation
            $table->decimal('technical_score', 3, 2)->default(0);
            $table->decimal('commercial_score', 3, 2)->default(0);
            $table->text('evaluation_notes')->nullable();

            // Additional Information
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_alternative')->default(false);
            $table->string('alternative_reason')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['quotation_id']);
            $table->index(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
