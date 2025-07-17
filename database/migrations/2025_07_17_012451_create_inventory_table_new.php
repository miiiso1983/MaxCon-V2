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
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('product_id');
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->integer('quantity_on_hand')->default(0);
            $table->integer('quantity_reserved')->default(0);
            $table->integer('quantity_available')->default(0);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('total_value', 15, 2)->default(0);
            $table->date('received_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('manufacture_date')->nullable();
            $table->enum('status', ['active', 'quarantine', 'damaged', 'expired', 'recalled'])->default('active');
            $table->text('notes')->nullable();
            $table->json('properties')->nullable(); // temperature, humidity logs, etc.
            $table->timestamps();

            // Simple indexes only
            $table->index(['tenant_id', 'warehouse_id']);
            $table->index(['tenant_id', 'product_id']);
            $table->index(['tenant_id', 'expiry_date']);
            $table->index(['tenant_id', 'status']);
            $table->index(['warehouse_id', 'location_id']);
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
