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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');

            // Basic Information
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['manufacturer', 'distributor', 'wholesaler', 'retailer', 'service_provider']);
            $table->enum('status', ['active', 'inactive', 'suspended', 'blacklisted'])->default('active');

            // Contact Information
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Iraq');

            // Business Information
            $table->string('tax_number')->nullable();
            $table->string('commercial_registration')->nullable();
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('iban')->nullable();

            // Rating and Performance
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('average_delivery_time', 8, 2)->default(0); // in days
            $table->decimal('quality_score', 3, 2)->default(0);
            $table->decimal('service_score', 3, 2)->default(0);

            // Payment Terms
            $table->enum('payment_terms', ['cash', 'credit_7', 'credit_15', 'credit_30', 'credit_45', 'credit_60', 'credit_90', 'custom'])->default('credit_30');
            $table->integer('credit_days')->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);

            // Additional Information
            $table->json('categories')->nullable(); // Product categories they supply
            $table->json('certifications')->nullable(); // ISO, GMP, etc.
            $table->text('notes')->nullable();
            $table->boolean('is_preferred')->default(false);
            $table->date('first_order_date')->nullable();
            $table->date('last_order_date')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'type']);
            $table->index(['tenant_id', 'is_preferred']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
