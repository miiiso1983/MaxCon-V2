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
        Schema::create('inspections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('inspection_title');
            $table->enum('inspection_type', ['routine', 'complaint', 'follow_up', 'pre_approval', 'post_market']);
            $table->string('inspector_name');
            $table->string('inspection_authority');
            $table->date('scheduled_date');
            $table->date('completion_date')->nullable();
            $table->enum('inspection_status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'postponed']);
            $table->string('facility_name');
            $table->text('facility_address');
            $table->text('scope_of_inspection')->nullable();
            $table->text('findings')->nullable();
            $table->text('recommendations')->nullable();
            $table->enum('compliance_rating', ['excellent', 'good', 'satisfactory', 'needs_improvement', 'non_compliant'])->nullable();
            $table->boolean('follow_up_required')->default(false);
            $table->date('follow_up_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'inspection_type']);
            $table->index(['tenant_id', 'inspection_status']);
            $table->index(['tenant_id', 'scheduled_date']);
            $table->index(['tenant_id', 'completion_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
