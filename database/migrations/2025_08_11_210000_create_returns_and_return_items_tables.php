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
        if (!Schema::hasTable('returns')) {
            Schema::create('returns', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->string('return_number')->unique();
                $table->unsignedBigInteger('invoice_id');
                $table->unsignedBigInteger('customer_id');
                $table->date('return_date');
                $table->enum('type', ['return', 'exchange'])->default('return');
                $table->enum('status', ['pending', 'approved', 'completed', 'rejected'])->default('pending');
                $table->text('reason')->nullable();
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->decimal('refund_amount', 15, 2)->default(0);
                $table->enum('refund_method', ['cash', 'credit', 'bank_transfer'])->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('processed_by')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();

                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'customer_id']);
                $table->index(['invoice_id']);
            });
        }

        if (!Schema::hasTable('return_items')) {
            Schema::create('return_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('return_id');
                $table->unsignedBigInteger('invoice_item_id');
                $table->unsignedBigInteger('product_id')->nullable();
                $table->string('product_name')->nullable();
                $table->string('product_code')->nullable();
                $table->string('batch_number')->nullable();
                $table->date('expiry_date')->nullable();

                $table->integer('quantity_returned');
                $table->integer('quantity_original')->default(0);
                $table->decimal('unit_price', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);

                $table->enum('condition', ['good', 'damaged', 'expired'])->default('good');
                $table->text('reason')->nullable();
                $table->text('notes')->nullable();

                // Exchange fields
                $table->unsignedBigInteger('exchange_product_id')->nullable();
                $table->integer('exchange_quantity')->nullable();
                $table->decimal('exchange_unit_price', 15, 2)->nullable();
                $table->decimal('exchange_total_amount', 15, 2)->nullable();

                $table->timestamps();

                $table->index(['return_id']);
                $table->index(['invoice_item_id']);
                $table->index(['product_id']);
                $table->index(['exchange_product_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('return_items')) {
            Schema::dropIfExists('return_items');
        }
        if (Schema::hasTable('returns')) {
            Schema::dropIfExists('returns');
        }
    }
};

