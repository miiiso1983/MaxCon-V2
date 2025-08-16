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
        Schema::create('product_registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('tenant_id');
            $table->uuid('company_id');
            $table->string('product_name');
            $table->string('product_name_en')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('registration_number')->unique();
            $table->string('batch_number')->nullable();
            $table->enum('product_type', ['pharmaceutical', 'vaccine', 'medical_device', 'supplement', 'cosmetic', 'herbal', 'biological']);
            $table->string('therapeutic_class')->nullable();
            $table->enum('dosage_form', ['tablet', 'capsule', 'syrup', 'injection', 'cream', 'ointment', 'drops', 'inhaler', 'suppository', 'powder'])->nullable();
            $table->string('strength')->nullable();
            $table->string('pack_size')->nullable();
            $table->string('manufacturer');
            $table->string('country_of_origin');
            $table->string('regulatory_authority');
            $table->date('registration_date');
            $table->date('approval_date')->nullable();
            $table->date('expiry_date');
            $table->date('renewal_date')->nullable();
            $table->enum('status', ['registered', 'pending', 'approved', 'rejected', 'suspended', 'withdrawn', 'expired'])->default('pending');
            $table->json('approval_conditions')->nullable();
            $table->json('contraindications')->nullable();
            $table->json('side_effects')->nullable();
            $table->json('storage_conditions')->nullable();
            $table->string('shelf_life')->nullable();
            $table->json('quality_specifications')->nullable();
            $table->json('clinical_trial_data')->nullable();
            $table->string('marketing_authorization')->nullable();
            $table->decimal('price_approval', 10, 2)->nullable();
            $table->boolean('import_permit_required')->default(false);
            $table->boolean('controlled_substance')->default(false);
            $table->boolean('prescription_only')->default(false);
            $table->text('notes')->nullable();
            $table->json('documents')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company_registrations')->onDelete('cascade');
            $table->index(['tenant_id', 'status']);
            $table->index(['expiry_date']);
            $table->index(['renewal_date']);
            $table->index(['product_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_registrations');
    }
};
