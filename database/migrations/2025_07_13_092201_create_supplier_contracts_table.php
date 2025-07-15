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
        Schema::create('supplier_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');

            // Contract Information
            $table->string('contract_number')->unique();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['supply', 'service', 'maintenance', 'consulting', 'framework', 'exclusive'])->default('supply');
            $table->enum('status', ['draft', 'active', 'suspended', 'expired', 'terminated', 'renewed'])->default('draft');

            // Dates
            $table->date('start_date');
            $table->date('end_date');
            $table->date('signed_date')->nullable();
            $table->date('renewal_date')->nullable();
            $table->integer('renewal_period_months')->nullable();
            $table->boolean('auto_renewal')->default(false);

            // Financial Terms
            $table->decimal('contract_value', 15, 2)->default(0);
            $table->decimal('minimum_order_value', 15, 2)->default(0);
            $table->decimal('maximum_order_value', 15, 2)->nullable();
            $table->string('currency', 3)->default('IQD');
            $table->enum('payment_terms', ['cash', 'credit_7', 'credit_15', 'credit_30', 'credit_45', 'credit_60', 'credit_90', 'custom'])->default('credit_30');
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('penalty_rate', 5, 2)->default(0);

            // Performance Terms
            $table->integer('delivery_days')->nullable();
            $table->decimal('quality_threshold', 5, 2)->default(95);
            $table->decimal('service_level_agreement', 5, 2)->default(99);
            $table->text('performance_indicators')->nullable();

            // Legal Information
            $table->text('terms_conditions')->nullable();
            $table->text('special_clauses')->nullable();
            $table->string('governing_law')->default('Iraqi Law');
            $table->text('dispute_resolution')->nullable();
            $table->json('attachments')->nullable();

            // Tracking
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->decimal('utilization_percentage', 5, 2)->default(0);
            $table->decimal('total_orders_value', 15, 2)->default(0);
            $table->integer('total_orders_count')->default(0);
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'supplier_id']);
            $table->index(['tenant_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_contracts');
    }
};
