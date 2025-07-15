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
        Schema::create('regulatory_inspections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('company_id');
            $table->string('inspection_number')->unique();
            $table->enum('inspection_type', ['routine', 'pre_approval', 'post_market', 'complaint_based', 'follow_up', 'surveillance', 'special', 'gmp', 'gcp', 'gdp']);
            $table->string('regulatory_authority');
            $table->string('inspector_name');
            $table->string('inspector_credentials')->nullable();
            $table->datetime('scheduled_date');
            $table->datetime('actual_date')->nullable();
            $table->datetime('completion_date')->nullable();
            $table->integer('duration_hours')->nullable();
            $table->text('inspection_scope')->nullable();
            $table->json('areas_inspected')->nullable();
            $table->json('findings')->nullable();
            $table->json('observations')->nullable();
            $table->json('non_conformities')->nullable();
            $table->json('critical_findings')->nullable();
            $table->json('major_findings')->nullable();
            $table->json('minor_findings')->nullable();
            $table->json('recommendations')->nullable();
            $table->json('corrective_actions_required')->nullable();
            $table->json('corrective_actions_taken')->nullable();
            $table->boolean('follow_up_required')->default(false);
            $table->date('follow_up_date')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'report_pending', 'closed', 'follow_up_required', 'cancelled', 'postponed'])->default('scheduled');
            $table->enum('overall_rating', ['excellent', 'good', 'satisfactory', 'needs_improvement', 'unsatisfactory', 'critical'])->nullable();
            $table->integer('compliance_score')->nullable(); // 0-100
            $table->boolean('certificate_issued')->default(false);
            $table->string('certificate_number')->nullable();
            $table->date('certificate_validity')->nullable();
            $table->date('next_inspection_date')->nullable();
            $table->text('inspection_report')->nullable();
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company_registrations')->onDelete('cascade');
            $table->index(['tenant_id', 'status']);
            $table->index(['scheduled_date']);
            $table->index(['follow_up_date']);
            $table->index(['next_inspection_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulatory_inspections');
    }
};
