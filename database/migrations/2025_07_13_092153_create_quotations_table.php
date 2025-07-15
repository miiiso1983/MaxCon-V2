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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');

            // Quotation Information
            $table->string('quotation_number')->unique();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_request_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();

            // Status and Dates
            $table->enum('status', ['draft', 'sent', 'received', 'under_review', 'accepted', 'rejected', 'expired'])->default('draft');
            $table->date('quotation_date');
            $table->date('valid_until');
            $table->date('response_date')->nullable();

            // Financial Information
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('currency', 3)->default('IQD');

            // Terms
            $table->enum('payment_terms', ['cash', 'credit_7', 'credit_15', 'credit_30', 'credit_45', 'credit_60', 'credit_90', 'custom'])->default('credit_30');
            $table->integer('delivery_days')->nullable();
            $table->text('delivery_terms')->nullable();
            $table->text('warranty_terms')->nullable();

            // Evaluation
            $table->decimal('technical_score', 3, 2)->default(0);
            $table->decimal('commercial_score', 3, 2)->default(0);
            $table->decimal('overall_score', 3, 2)->default(0);
            $table->text('evaluation_notes')->nullable();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('evaluated_at')->nullable();

            // Additional Information
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->text('special_conditions')->nullable();
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_selected')->default(false);
            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'supplier_id']);
            $table->index(['tenant_id', 'quotation_date']);
            $table->index(['tenant_id', 'valid_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
