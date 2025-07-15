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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type'); // summary, detailed, analytical, comparative
            $table->string('category'); // sales, financial, inventory, customers, products, employees
            $table->json('query_builder'); // Dynamic query configuration
            $table->json('filters')->nullable(); // Available filters
            $table->json('columns'); // Report columns configuration
            $table->json('settings')->nullable(); // Additional settings
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(false);
            $table->foreignId('created_by')->constrained('users');
            $table->string('tenant_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'type']);
            $table->index(['tenant_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
