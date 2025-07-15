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
        Schema::create('inventory_audit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('inventory_audits')->onDelete('cascade');
            $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->nullable()->constrained('warehouse_locations')->onDelete('set null');
            $table->string('batch_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('system_quantity', 15, 3); // quantity in system
            $table->decimal('counted_quantity', 15, 3)->nullable(); // actual counted quantity
            $table->decimal('variance', 15, 3)->nullable(); // difference
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('variance_value', 15, 2)->nullable(); // variance * unit_cost
            $table->enum('status', ['pending', 'counted', 'verified', 'adjusted'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('counted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('counted_at')->nullable();
            $table->timestamps();

            $table->index(['audit_id', 'product_id']);
            $table->index(['audit_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_audit_items');
    }
};
