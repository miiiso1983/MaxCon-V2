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
        Schema::create('quality_certificates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('tenant_id');
            $table->uuid('product_id');
            $table->string('certificate_number')->unique();
            $table->enum('certificate_type', ['coa', 'gmp', 'iso', 'halal', 'organic', 'stability', 'bioequivalence', 'sterility', 'batch_release', 'import']);
            $table->string('issuing_authority');
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->integer('validity_period')->nullable(); // in days
            $table->string('batch_number')->nullable();
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date_product')->nullable();
            $table->json('quality_parameters')->nullable();
            $table->json('test_results')->nullable();
            $table->json('specifications_met')->nullable();
            $table->json('deviations')->nullable();
            $table->string('approved_by');
            $table->enum('status', ['valid', 'expired', 'suspended', 'revoked', 'pending_renewal', 'under_review'])->default('valid');
            $table->boolean('renewal_required')->default(false);
            $table->date('renewal_date')->nullable();
            $table->json('storage_conditions')->nullable();
            $table->json('handling_instructions')->nullable();
            $table->json('distribution_restrictions')->nullable();
            $table->json('recall_information')->nullable();
            $table->string('certificate_file')->nullable();
            $table->json('supporting_documents')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('product_registrations')->onDelete('cascade');
            $table->index(['tenant_id', 'status']);
            $table->index(['expiry_date']);
            $table->index(['renewal_date']);
            $table->index(['certificate_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_certificates');
    }
};
