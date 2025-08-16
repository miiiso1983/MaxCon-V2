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
        Schema::create('laboratory_tests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('tenant_id');
            $table->uuid('product_id');
            $table->string('test_number')->unique();
            $table->enum('test_type', ['quality_control', 'stability', 'bioequivalence', 'dissolution', 'microbiological', 'chemical', 'physical', 'toxicological', 'sterility', 'endotoxin']);
            $table->enum('test_category', ['raw_material', 'in_process', 'finished_product', 'stability_study', 'method_validation', 'reference_standard']);
            $table->string('laboratory_name');
            $table->string('laboratory_accreditation')->nullable();
            $table->string('sample_batch');
            $table->date('sample_date');
            $table->date('test_date');
            $table->date('completion_date')->nullable();
            $table->string('test_method');
            $table->json('test_parameters')->nullable();
            $table->json('specifications')->nullable();
            $table->json('results')->nullable();
            $table->text('conclusion')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'passed', 'failed', 'retest_required', 'cancelled', 'on_hold'])->default('scheduled');
            $table->string('technician_name')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('certificate_number')->nullable();
            $table->integer('validity_period')->nullable(); // in days
            $table->date('retest_date')->nullable();
            $table->json('storage_conditions')->nullable();
            $table->json('environmental_conditions')->nullable();
            $table->json('equipment_used')->nullable();
            $table->string('calibration_status')->nullable();
            $table->text('deviation_notes')->nullable();
            $table->json('corrective_actions')->nullable();
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('product_registrations')->onDelete('cascade');
            $table->index(['tenant_id', 'status']);
            $table->index(['test_date']);
            $table->index(['completion_date']);
            $table->index(['retest_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laboratory_tests');
    }
};
