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
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('movement_number')->unique();
            $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_location_id')->nullable()->constrained('warehouse_locations')->onDelete('set null');
            $table->foreignId('to_location_id')->nullable()->constrained('warehouse_locations')->onDelete('set null');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->enum('type', ['in', 'out', 'transfer', 'adjustment', 'return', 'damage', 'expiry'])->default('in');
            $table->enum('reason', [
                'purchase', 'sale', 'transfer', 'adjustment', 'return', 'damage',
                'expiry', 'theft', 'loss', 'production', 'consumption', 'sample'
            ])->default('purchase');
            $table->decimal('quantity', 15, 3);
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 15, 2)->nullable();
            $table->decimal('balance_before', 15, 3);
            $table->decimal('balance_after', 15, 3);
            $table->string('reference_type')->nullable(); // invoice, purchase_order, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('movement_date');
            $table->timestamps();

            $table->index(['tenant_id', 'movement_date']);
            $table->index(['tenant_id', 'warehouse_id']);
            $table->index(['tenant_id', 'product_id']);
            $table->index(['tenant_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
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
