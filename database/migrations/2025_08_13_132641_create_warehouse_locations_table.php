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
            $table->unsignedBigInteger('warehouse_id');
            $table->string('code');
            $table->string('name');
            $table->string('zone', 10)->nullable();
            $table->string('aisle', 10)->nullable();
            $table->string('shelf', 10)->nullable();
            $table->string('level', 10)->nullable();
            $table->string('position', 10)->nullable();
            $table->enum('type', ['shelf', 'floor', 'rack', 'bin', 'zone'])->default('shelf');
            $table->text('description')->nullable();
            $table->decimal('capacity', 15, 2)->nullable();
            $table->decimal('used_capacity', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('properties')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('warehouse_id');
            $table->index('type');
            $table->index('is_active');
            $table->index(['warehouse_id', 'code']);
            $table->index(['warehouse_id', 'is_active']);
            $table->unique(['warehouse_id', 'code']);

            // Foreign keys
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
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
