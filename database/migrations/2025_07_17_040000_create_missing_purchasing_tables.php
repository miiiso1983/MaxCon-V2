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
        // Create purchase_requests table if not exists
        if (!Schema::hasTable('purchase_requests')) {
            Schema::create('purchase_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->onDelete('cascade');

                // Request Information
                $table->string('request_number')->unique();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'cancelled', 'completed'])->default('draft');

                // Requester Information
                $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
                $table->unsignedBigInteger('department_id')->nullable();
                $table->date('required_date');
                $table->text('justification')->nullable();

                // Approval Information
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->text('approval_notes')->nullable();
                $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('rejected_at')->nullable();
                $table->text('rejection_reason')->nullable();

                // Budget Information
                $table->decimal('estimated_total', 15, 2)->default(0);
                $table->decimal('approved_budget', 15, 2)->nullable();
                $table->string('budget_code')->nullable();
                $table->string('cost_center')->nullable();

                // Additional Information
                $table->json('attachments')->nullable();
                $table->text('special_instructions')->nullable();
                $table->boolean('is_urgent')->default(false);
                $table->date('deadline')->nullable();

                $table->timestamps();

                // Indexes
                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'requested_by']);
                $table->index(['tenant_id', 'required_date']);
            });
        }

        // Create purchase_request_items table if not exists
        if (!Schema::hasTable('purchase_request_items')) {
            Schema::create('purchase_request_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_request_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');

                // Item Information
                $table->string('item_name');
                $table->string('item_code')->nullable();
                $table->text('description')->nullable();
                $table->string('unit')->default('piece');
                $table->decimal('quantity', 10, 2);
                $table->decimal('estimated_price', 10, 2)->default(0);
                $table->decimal('total_estimated', 12, 2)->default(0);

                // Specifications
                $table->text('specifications')->nullable();
                $table->string('brand_preference')->nullable();
                $table->string('model_preference')->nullable();
                $table->text('technical_requirements')->nullable();

                // Status
                $table->enum('status', ['pending', 'approved', 'rejected', 'ordered'])->default('pending');
                $table->text('notes')->nullable();
                $table->integer('sort_order')->default(0);

                $table->timestamps();

                // Indexes
                $table->index(['purchase_request_id']);
                $table->index(['product_id']);
            });
        }

        // Create purchase_orders table if not exists
        if (!Schema::hasTable('purchase_orders')) {
            Schema::create('purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
                $table->foreignId('purchase_request_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('supplier_id')->constrained()->onDelete('cascade');

                // Order Information
                $table->string('order_number')->unique();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('status', ['draft', 'sent', 'confirmed', 'partially_received', 'completed', 'cancelled'])->default('draft');
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

                // Dates
                $table->date('order_date');
                $table->date('expected_delivery_date');
                $table->date('actual_delivery_date')->nullable();

                // Financial Information
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('shipping_cost', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->string('currency', 3)->default('IQD');

                // Payment Information
                $table->enum('payment_terms', ['cash', 'credit_30', 'credit_60', 'credit_90', 'custom'])->default('credit_30');
                $table->text('payment_notes')->nullable();

                // Delivery Information
                $table->text('delivery_address')->nullable();
                $table->string('delivery_contact')->nullable();
                $table->string('delivery_phone')->nullable();
                $table->text('delivery_instructions')->nullable();

                // Additional Information
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->text('notes')->nullable();
                $table->text('terms_conditions')->nullable();
                $table->json('attachments')->nullable();

                // Tracking
                $table->decimal('received_percentage', 5, 2)->default(0);
                $table->boolean('is_urgent')->default(false);
                $table->string('reference_number')->nullable();

                $table->timestamps();

                // Indexes
                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'supplier_id']);
                $table->index(['tenant_id', 'order_date']);
            });
        }

        // Create purchase_order_items table if not exists
        if (!Schema::hasTable('purchase_order_items')) {
            Schema::create('purchase_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('purchase_request_item_id')->nullable()->constrained()->onDelete('set null');

                // Item Information
                $table->string('item_name');
                $table->string('item_code')->nullable();
                $table->text('description')->nullable();
                $table->string('unit')->default('piece');
                $table->decimal('quantity', 10, 2);
                $table->decimal('unit_price', 10, 2);
                $table->decimal('total_price', 12, 2);

                // Specifications
                $table->text('specifications')->nullable();
                $table->string('brand')->nullable();
                $table->string('model')->nullable();

                // Receiving Information
                $table->decimal('received_quantity', 10, 2)->default(0);
                $table->decimal('remaining_quantity', 10, 2)->default(0);
                $table->enum('status', ['pending', 'partially_received', 'received', 'cancelled'])->default('pending');

                // Quality Information
                $table->text('quality_notes')->nullable();
                $table->enum('quality_status', ['pending', 'approved', 'rejected'])->default('pending');

                $table->text('notes')->nullable();
                $table->integer('sort_order')->default(0);

                $table->timestamps();

                // Indexes
                $table->index(['purchase_order_id']);
                $table->index(['product_id']);
            });
        }

        // Create quotations table if not exists
        if (!Schema::hasTable('quotations')) {
            Schema::create('quotations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
                $table->foreignId('purchase_request_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('supplier_id')->constrained()->onDelete('cascade');

                // Quotation Information
                $table->string('quotation_number')->unique();
                $table->string('supplier_reference')->nullable();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('status', ['draft', 'sent', 'received', 'under_review', 'accepted', 'rejected', 'expired'])->default('draft');

                // Dates
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
                $table->enum('payment_terms', ['cash', 'credit_30', 'credit_60', 'credit_90', 'custom'])->default('credit_30');
                $table->text('delivery_terms')->nullable();
                $table->integer('delivery_days')->nullable();
                $table->text('warranty_terms')->nullable();

                // Additional Information
                $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('reviewed_at')->nullable();
                $table->text('review_notes')->nullable();
                $table->text('terms_conditions')->nullable();
                $table->json('attachments')->nullable();

                $table->timestamps();

                // Indexes
                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'supplier_id']);
                $table->index(['tenant_id', 'quotation_date']);
            });
        }

        // Create quotation_items table if not exists
        if (!Schema::hasTable('quotation_items')) {
            Schema::create('quotation_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quotation_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
                $table->foreignId('purchase_request_item_id')->nullable()->constrained()->onDelete('set null');

                // Item Information
                $table->string('item_name');
                $table->string('item_code')->nullable();
                $table->text('description')->nullable();
                $table->string('unit')->default('piece');
                $table->decimal('quantity', 10, 2);
                $table->decimal('unit_price', 10, 2);
                $table->decimal('total_price', 12, 2);

                // Specifications
                $table->text('specifications')->nullable();
                $table->string('brand')->nullable();
                $table->string('model')->nullable();
                $table->text('technical_specs')->nullable();

                // Additional Information
                $table->integer('delivery_days')->nullable();
                $table->text('warranty_info')->nullable();
                $table->text('notes')->nullable();
                $table->integer('sort_order')->default(0);

                $table->timestamps();

                // Indexes
                $table->index(['quotation_id']);
                $table->index(['product_id']);
            });
        }

        // Create supplier_contracts table if not exists
        if (!Schema::hasTable('supplier_contracts')) {
            Schema::create('supplier_contracts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
                $table->foreignId('supplier_id')->constrained()->onDelete('cascade');

                // Contract Information
                $table->string('contract_number')->unique();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('type', ['general', 'exclusive', 'framework', 'service'])->default('general');
                $table->enum('status', ['draft', 'active', 'expired', 'terminated', 'suspended'])->default('draft');

                // Dates
                $table->date('start_date');
                $table->date('end_date');
                $table->date('signed_date')->nullable();

                // Financial Terms
                $table->decimal('contract_value', 15, 2)->nullable();
                $table->string('currency', 3)->default('IQD');
                $table->enum('payment_terms', ['cash', 'credit_30', 'credit_60', 'credit_90', 'custom'])->default('credit_30');
                $table->text('payment_conditions')->nullable();

                // Delivery Terms
                $table->integer('delivery_days')->nullable();
                $table->text('delivery_terms')->nullable();
                $table->text('quality_standards')->nullable();

                // Legal Information
                $table->text('terms_conditions')->nullable();
                $table->text('penalties')->nullable();
                $table->text('warranty_terms')->nullable();
                $table->boolean('auto_renewal')->default(false);
                $table->integer('renewal_period')->nullable(); // in months

                // Additional Information
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('approved_at')->nullable();
                $table->json('attachments')->nullable();
                $table->text('notes')->nullable();

                $table->timestamps();

                // Indexes
                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'supplier_id']);
                $table->index(['tenant_id', 'start_date', 'end_date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_contracts');
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('purchase_request_items');
        Schema::dropIfExists('purchase_requests');
    }
};
