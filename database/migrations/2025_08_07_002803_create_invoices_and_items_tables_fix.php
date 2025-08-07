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
        // Create invoices table if it doesn't exist
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->string('invoice_number')->unique();
                $table->unsignedBigInteger('customer_id');
                $table->unsignedBigInteger('sales_order_id')->nullable();
                $table->unsignedBigInteger('created_by');
                $table->date('invoice_date');
                $table->date('due_date');
                $table->enum('type', ['sales', 'proforma'])->default('sales');
                $table->enum('status', ['draft', 'pending', 'paid', 'overdue', 'cancelled'])->default('draft');
                $table->text('notes')->nullable();
                $table->string('currency', 20)->default('IQD');
                $table->decimal('exchange_rate', 10, 4)->default(1.0000);
                $table->decimal('shipping_cost', 15, 2)->default(0);
                $table->decimal('additional_charges', 15, 2)->default(0);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed');
                $table->decimal('subtotal_amount', 15, 2);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2);
                $table->decimal('previous_balance', 15, 2)->default(0);
                $table->decimal('credit_limit', 15, 2)->default(0);
                $table->string('sales_representative')->nullable();
                $table->text('qr_code')->nullable();
                $table->text('free_samples')->nullable();
                $table->timestamps();
            });
        }

        // Create invoice_items table if it doesn't exist
        if (!Schema::hasTable('invoice_items')) {
            Schema::create('invoice_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('invoice_id');
                $table->unsignedBigInteger('product_id');
                $table->string('product_name');
                $table->string('product_code')->nullable();
                $table->string('batch_number')->nullable();
                $table->date('expiry_date')->nullable();
                $table->integer('quantity');
                $table->decimal('unit_price', 15, 2);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed');
                $table->decimal('line_total', 15, 2);
                $table->decimal('total_amount', 15, 2);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};
