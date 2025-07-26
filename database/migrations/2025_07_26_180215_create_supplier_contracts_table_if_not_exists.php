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
        // Check if table doesn't exist before creating it
        if (!Schema::hasTable('supplier_contracts')) {
            Schema::create('supplier_contracts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
                $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
                $table->string('contract_number')->unique();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('type', ['supply', 'service', 'maintenance', 'consulting', 'framework']);
                $table->enum('status', ['draft', 'pending', 'active', 'expired', 'terminated', 'cancelled'])->default('draft');
                $table->date('start_date');
                $table->date('end_date');
                $table->date('signed_date')->nullable();
                $table->date('renewal_date')->nullable();
                $table->integer('renewal_period_months')->nullable();
                $table->boolean('auto_renewal')->default(false);
                $table->decimal('contract_value', 15, 2);
                $table->decimal('minimum_order_value', 15, 2)->nullable();
                $table->decimal('maximum_order_value', 15, 2)->nullable();
                $table->string('currency', 3)->default('IQD');
                $table->text('payment_terms')->nullable();
                $table->text('delivery_terms')->nullable();
                $table->text('quality_requirements')->nullable();
                $table->text('penalty_terms')->nullable();
                $table->text('termination_conditions')->nullable();
                $table->text('special_conditions')->nullable();
                $table->json('attachments')->nullable();
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                // Indexes
                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'supplier_id']);
                $table->index(['start_date', 'end_date']);
                $table->index('contract_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_contracts');
    }
};
