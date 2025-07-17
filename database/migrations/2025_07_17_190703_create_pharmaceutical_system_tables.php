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
        // Create laboratory_tests table
        Schema::create('laboratory_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('test_code', 50);
            $table->string('test_name');
            $table->string('test_name_en')->nullable();
            $table->string('category', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('normal_range')->nullable();
            $table->string('unit', 50)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('duration_hours')->nullable();
            $table->boolean('requires_fasting')->default(false);
            $table->string('sample_type', 100)->nullable();
            $table->string('equipment_required')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'test_code']);
            $table->index('tenant_id');
            $table->index('category');
            $table->index('is_active');
        });

        // Create patients table
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('patient_number', 50);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('blood_type', 10)->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_history')->nullable();
            $table->string('insurance_number', 100)->nullable();
            $table->string('insurance_provider')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'patient_number']);
            $table->index('tenant_id');
            $table->index('phone');
            $table->index('email');
            $table->index('is_active');
        });

        // Create doctors table
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('doctor_code', 50);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('specialization')->nullable();
            $table->string('license_number', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('clinic_address')->nullable();
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'doctor_code']);
            $table->index('tenant_id');
            $table->index('specialization');
            $table->index('phone');
            $table->index('is_active');
        });

        // Create prescriptions table
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('prescription_number', 50);
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->date('prescription_date');
            $table->text('diagnosis')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'dispensed', 'partially_dispensed', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('dispensed_by')->nullable();
            $table->timestamp('dispensed_at')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'prescription_number']);
            $table->index('tenant_id');
            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('prescription_date');
            $table->index('status');
        });

        // Create prescription_items table
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prescription_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity_prescribed');
            $table->integer('quantity_dispensed')->default(0);
            $table->string('dosage')->nullable();
            $table->string('frequency')->nullable();
            $table->string('duration')->nullable();
            $table->text('instructions')->nullable();
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            $table->timestamps();

            $table->index('prescription_id');
            $table->index('product_id');
        });

        // Create patient_tests table
        Schema::create('patient_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('laboratory_test_id');
            $table->date('test_date');
            $table->string('result_value')->nullable();
            $table->enum('result_status', ['normal', 'abnormal', 'critical'])->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('technician_id')->nullable();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            $table->index('patient_id');
            $table->index('laboratory_test_id');
            $table->index('test_date');
            $table->index('status');
        });

        // Create drug_interactions table
        Schema::create('drug_interactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('drug_a_id');
            $table->unsignedBigInteger('drug_b_id');
            $table->enum('interaction_type', ['major', 'moderate', 'minor']);
            $table->text('description');
            $table->text('clinical_effect')->nullable();
            $table->text('management')->nullable();
            $table->integer('severity_level')->default(1);
            $table->boolean('is_active')->default(true);
            $table->string('source')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tenant_id');
            $table->index('drug_a_id');
            $table->index('drug_b_id');
            $table->index('interaction_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drug_interactions');
        Schema::dropIfExists('patient_tests');
        Schema::dropIfExists('prescription_items');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('laboratory_tests');
    }
};
