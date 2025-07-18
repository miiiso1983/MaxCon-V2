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
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->string('payment_number')->unique();
            $table->decimal('amount', 15, 2);
            $table->datetime('payment_date');
            $table->enum('payment_method', [
                'cash', 'check', 'bank_transfer', 'credit_card', 'online'
            ])->default('cash');
            $table->string('reference_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('check_number')->nullable();
            $table->datetime('check_date')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('processed_at')->nullable();
            $table->string('currency', 3)->default('IQD');
            $table->decimal('exchange_rate', 10, 4)->default(1);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['customer_id', 'status']);
            $table->index(['tenant_id', 'status']);
            $table->index(['payment_date', 'status']);
            $table->index('payment_number');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_payments');
    }
};
