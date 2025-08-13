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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->decimal('quantity', 15, 3)->default(0);
            $table->decimal('available_quantity', 15, 3)->default(0);
            $table->decimal('reserved_quantity', 15, 3)->default(0);
            $table->decimal('cost_price', 15, 2)->nullable();
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'expired', 'damaged', 'reserved'])->default('active');
            $table->json('properties')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('warehouse_id');
            $table->index('product_id');
            $table->index('location_id');
            $table->index('status');
            $table->index('expiry_date');
            $table->index(['warehouse_id', 'product_id']);
            $table->index(['warehouse_id', 'status']);
            $table->unique(['warehouse_id', 'product_id', 'batch_number']);

            // Foreign keys
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('warehouse_locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
