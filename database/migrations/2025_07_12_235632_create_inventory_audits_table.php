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
        Schema::create('inventory_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('audit_number')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['full', 'partial', 'cycle', 'spot'])->default('full');
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->date('scheduled_date');
            $table->date('started_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->json('settings')->nullable(); // audit criteria, filters, etc.
            $table->timestamps();

            $table->index(['tenant_id', 'warehouse_id']);
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'scheduled_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_audits');
    }
};
