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
        Schema::create('hr_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('payroll_period'); // e.g., "2024-01"
            $table->integer('month');
            $table->integer('year');
            
            // Salary Components
            $table->decimal('basic_salary', 12, 2);
            $table->json('allowances')->nullable(); // Housing, transport, etc.
            $table->decimal('overtime_amount', 10, 2)->default(0);
            $table->decimal('bonus', 10, 2)->default(0);
            $table->decimal('gross_salary', 12, 2);
            
            // Deductions
            $table->json('deductions')->nullable(); // Custom deductions
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('social_security', 10, 2)->default(0);
            $table->decimal('insurance_deduction', 10, 2)->default(0);
            $table->decimal('loan_deduction', 10, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2);
            
            // Attendance Data
            $table->integer('working_days');
            $table->integer('present_days');
            $table->integer('absent_days');
            $table->integer('leave_days');
            $table->decimal('overtime_hours', 5, 2)->default(0);
            $table->decimal('late_hours', 5, 2)->default(0);
            
            // Status and Payment
            $table->enum('status', ['draft', 'calculated', 'approved', 'paid', 'cancelled'])->default('draft');
            $table->date('payment_date')->nullable();
            $table->enum('payment_method', ['bank_transfer', 'cash', 'check'])->nullable();
            $table->string('bank_reference')->nullable();
            $table->text('notes')->nullable();
            
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('hr_employees')->onDelete('cascade');
            $table->foreign('generated_by')->references('id')->on('hr_employees')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('hr_employees')->onDelete('set null');
            $table->unique(['employee_id', 'month', 'year']);
            $table->index(['tenant_id', 'payroll_period', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_payrolls');
    }
};
