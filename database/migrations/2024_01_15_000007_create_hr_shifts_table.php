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
        Schema::create('hr_shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name');
            $table->string('code')->unique();
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('break_duration')->default(60); // minutes
            $table->decimal('total_hours', 5, 2)->default(8);
            $table->boolean('is_night_shift')->default(false);
            $table->decimal('night_shift_allowance', 8, 2)->default(0);
            $table->decimal('overtime_threshold', 5, 2)->default(8);
            $table->integer('late_tolerance')->default(15); // minutes
            $table->integer('early_leave_tolerance')->default(15); // minutes
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_shifts');
    }
};
