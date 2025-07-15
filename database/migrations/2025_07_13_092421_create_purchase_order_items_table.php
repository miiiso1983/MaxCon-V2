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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
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

            // Delivery Information
            $table->decimal('received_quantity', 10, 2)->default(0);
            $table->decimal('pending_quantity', 10, 2)->default(0);
            $table->date('expected_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();

            // Quality Information
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('quality_notes')->nullable();
            $table->enum('quality_status', ['pending', 'approved', 'rejected', 'conditional'])->default('pending');

            // Additional Information
            $table->text('specifications')->nullable();
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['pending', 'confirmed', 'partially_received', 'received', 'cancelled'])->default('pending');

            $table->timestamps();

            // Indexes
            $table->index(['purchase_order_id']);
            $table->index(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
