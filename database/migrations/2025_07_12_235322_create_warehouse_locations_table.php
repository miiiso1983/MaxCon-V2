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
        Schema::create('warehouse_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->string('code'); // A1-B2-C3 (Zone-Aisle-Shelf)
            $table->string('name');
            $table->string('zone')->nullable(); // A, B, C
            $table->string('aisle')->nullable(); // 1, 2, 3
            $table->string('shelf')->nullable(); // A, B, C
            $table->string('level')->nullable(); // 1, 2, 3, 4
            $table->string('position')->nullable(); // 1, 2, 3
            $table->enum('type', ['zone', 'aisle', 'shelf', 'bin', 'position'])->default('position');
            $table->text('description')->nullable();
            $table->decimal('capacity', 10, 2)->nullable(); // max items
            $table->decimal('used_capacity', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('properties')->nullable(); // temperature_controlled, hazardous, etc.
            $table->timestamps();

            $table->unique(['warehouse_id', 'code']);
            $table->index(['warehouse_id', 'type']);
            $table->index(['warehouse_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_locations');
    }
};
