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
        // Check if suppliers table doesn't exist before creating it
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
                $table->string('code')->unique();
                $table->string('name');
                $table->string('name_en')->nullable();
                $table->enum('type', ['manufacturer', 'distributor', 'wholesaler', 'retailer', 'service_provider']);
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                $table->string('contact_person')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->string('city')->nullable();
                $table->string('country')->default('Iraq');
                $table->string('tax_number')->nullable();
                $table->string('commercial_registration')->nullable();
                $table->enum('payment_terms', ['cash', 'credit_15', 'credit_30', 'credit_45', 'credit_60'])->default('cash');
                $table->decimal('credit_limit', 15, 2)->default(0);
                $table->decimal('current_balance', 15, 2)->default(0);
                $table->boolean('is_preferred')->default(false);
                $table->text('notes')->nullable();
                $table->json('documents')->nullable();
                $table->timestamps();

                // Indexes
                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'type']);
                $table->index('code');
                $table->index('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
