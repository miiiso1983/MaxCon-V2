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
        // Create purchase_orders table if it doesn't exist
        if (!Schema::hasTable('purchase_orders')) {
            Schema::create('purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->onDelete('cascade');

                // Order Information
                $table->string('po_number')->unique();
                $table->unsignedBigInteger('supplier_id');
                $table->unsignedBigInteger('purchase_request_id')->nullable();
                $table->enum('status', ['draft', 'sent', 'confirmed', 'partially_received', 'completed', 'cancelled'])->default('draft');

                // Dates
                $table->date('order_date');
                $table->date('expected_delivery_date');
                $table->date('actual_delivery_date')->nullable();
                $table->date('due_date')->nullable();

                // Financial Information
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('shipping_cost', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->string('currency', 3)->default('IQD');
                $table->decimal('exchange_rate', 10, 4)->default(1);

                // Payment Information
                $table->enum('payment_terms', ['cash', 'credit_7', 'credit_15', 'credit_30', 'credit_45', 'credit_60', 'credit_90', 'custom'])->default('credit_30');
                $table->integer('payment_days')->nullable();
                $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending');
                $table->decimal('paid_amount', 15, 2)->default(0);

                // Delivery Information
                $table->text('delivery_address')->nullable();
                $table->string('delivery_contact')->nullable();
                $table->string('delivery_phone')->nullable();
                $table->text('delivery_instructions')->nullable();

                // Additional Information
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->text('notes')->nullable();
                $table->text('terms_conditions')->nullable();
                $table->json('attachments')->nullable();

                // Tracking
                $table->decimal('received_percentage', 5, 2)->default(0);
                $table->boolean('is_urgent')->default(false);
                $table->string('reference_number')->nullable();

                $table->timestamps();

                // Indexes
                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'supplier_id']);
                $table->index(['tenant_id', 'order_date']);
                $table->index(['tenant_id', 'payment_status']);
            });
        }

        // Create purchase_order_items table if it doesn't exist
        if (!Schema::hasTable('purchase_order_items')) {
            Schema::create('purchase_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');

                // Item Information
                $table->decimal('quantity', 10, 2);
                $table->decimal('unit_price', 10, 2);
                $table->decimal('discount_amount', 10, 2)->default(0);
                $table->decimal('discount_percentage', 5, 2)->default(0);
                $table->decimal('tax_amount', 10, 2)->default(0);
                $table->decimal('tax_percentage', 5, 2)->default(0);
                $table->decimal('total_amount', 12, 2);
                $table->decimal('received_quantity', 10, 2)->default(0);
                $table->decimal('remaining_quantity', 10, 2)->default(0);
                $table->string('unit')->default('piece');
                $table->text('description')->nullable();
                $table->text('specifications')->nullable();
                $table->string('batch_number')->nullable();
                $table->date('expiry_date')->nullable();
                $table->text('quality_notes')->nullable();
                $table->enum('quality_status', ['pending', 'approved', 'rejected', 'conditional'])->default('pending');
                $table->text('notes')->nullable();
                $table->integer('sort_order')->default(0);
                $table->enum('status', ['pending', 'confirmed', 'partially_received', 'received', 'cancelled'])->default('pending');

                $table->timestamps();

                // Indexes
                $table->index(['purchase_order_id']);
                $table->index(['product_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
    }
};
