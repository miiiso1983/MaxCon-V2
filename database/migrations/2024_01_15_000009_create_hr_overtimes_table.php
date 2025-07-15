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
        Schema::create('hr_overtimes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('employee_id');
            $table->date('date');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->decimal('hours_requested', 5, 2);
            $table->decimal('hours_approved', 5, 2)->nullable();
            $table->decimal('hourly_rate', 8, 2);
            $table->decimal('overtime_rate', 3, 2)->default(1.5);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'completed'])->default('pending');
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->date('approved_date')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->boolean('is_holiday_overtime')->default(false);
            $table->boolean('is_night_overtime')->default(false);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('employee_id')->references('id')->on('hr_employees')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('hr_employees')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('hr_employees')->onDelete('set null');
            $table->index(['tenant_id', 'employee_id', 'status']);
            $table->index(['date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_overtimes');
    }
};
