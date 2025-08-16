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
        Schema::create('company_registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('tenant_id');
            $table->string('company_name');
            $table->string('company_name_en')->nullable();
            $table->string('registration_number')->unique();
            $table->string('license_number')->unique();
            $table->enum('license_type', ['manufacturing', 'import', 'export', 'distribution', 'wholesale', 'retail', 'research']);
            $table->string('regulatory_authority');
            $table->date('registration_date');
            $table->date('license_issue_date');
            $table->date('license_expiry_date');
            $table->enum('status', ['active', 'suspended', 'expired', 'under_review', 'cancelled'])->default('active');
            $table->text('company_address');
            $table->string('contact_person');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->json('business_activities')->nullable();
            $table->json('authorized_products')->nullable();
            $table->enum('compliance_status', ['compliant', 'non_compliant', 'under_investigation', 'corrective_action'])->default('compliant');
            $table->date('last_inspection_date')->nullable();
            $table->date('next_inspection_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('documents')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->index(['tenant_id', 'status']);
            $table->index(['license_expiry_date']);
            $table->index(['next_inspection_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_registrations');
    }
};
