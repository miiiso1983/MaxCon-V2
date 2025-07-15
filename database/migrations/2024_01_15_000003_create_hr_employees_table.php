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
        Schema::create('hr_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name_arabic')->nullable();
            $table->string('full_name_english')->nullable();
            $table->string('national_id')->unique();
            $table->string('passport_number')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->default('single');
            $table->string('nationality')->default('Iraqi');
            $table->string('religion')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile');
            $table->string('email')->unique();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            
            // Employment Information
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('direct_manager_id')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'internship', 'consultant'])->default('full_time');
            $table->enum('employment_status', ['active', 'probation', 'suspended', 'terminated', 'resigned'])->default('active');
            $table->date('hire_date');
            $table->date('probation_end_date')->nullable();
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();
            
            // Salary Information
            $table->decimal('basic_salary', 12, 2);
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('iban')->nullable();
            $table->string('social_security_number')->nullable();
            $table->string('tax_number')->nullable();
            
            // Education & Experience
            $table->enum('education_level', ['high_school', 'diploma', 'bachelor', 'master', 'phd'])->nullable();
            $table->string('university')->nullable();
            $table->string('major')->nullable();
            $table->year('graduation_year')->nullable();
            $table->integer('experience_years')->default(0);
            $table->string('previous_company')->nullable();
            $table->json('skills')->nullable();
            $table->json('certifications')->nullable();
            $table->json('languages')->nullable();
            
            // Files
            $table->string('profile_photo')->nullable();
            $table->string('cv_file')->nullable();
            $table->string('id_copy')->nullable();
            $table->string('passport_copy')->nullable();
            $table->json('certificates_files')->nullable();
            $table->string('contract_file')->nullable();
            
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('termination_date')->nullable();
            $table->text('termination_reason')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('department_id')->references('id')->on('hr_departments');
            $table->foreign('position_id')->references('id')->on('hr_positions');
            $table->foreign('direct_manager_id')->references('id')->on('hr_employees')->onDelete('set null');
            $table->index(['tenant_id', 'employment_status', 'is_active']);
            $table->index(['department_id', 'position_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_employees');
    }
};
