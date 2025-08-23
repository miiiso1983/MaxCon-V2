<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_warnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('type'); // delay, absence, behavior, performance
            $table->text('reason');
            $table->enum('severity', ['low','medium','high','critical'])->default('low');
            $table->date('date');
            $table->boolean('escalated')->default(false);
            $table->unsignedInteger('warning_count')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('employee_id')->references('id')->on('hr_employees')->onDelete('cascade');
            $table->index(['tenant_id','employee_id','date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_warnings');
    }
};

