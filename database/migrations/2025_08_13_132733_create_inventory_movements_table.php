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
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('product_id');
            $table->enum('type', ['in', 'out', 'transfer', 'adjustment'])->default('in');
            $table->decimal('quantity', 15, 3);
            $table->decimal('cost_price', 15, 2)->nullable();
            $table->string('reference_type')->nullable(); // invoice, purchase_order, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Indexes
            $table->index('warehouse_id');
            $table->index('product_id');
            $table->index('type');
            $table->index('created_by');
            $table->index(['reference_type', 'reference_id']);
            $table->index(['warehouse_id', 'type']);
            $table->index(['warehouse_id', 'product_id']);

            // Foreign keys
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
