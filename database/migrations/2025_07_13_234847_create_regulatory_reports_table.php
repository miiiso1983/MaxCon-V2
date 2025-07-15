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
        Schema::create('regulatory_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('company_id')->nullable();
            $table->uuid('inspection_id')->nullable();
            $table->uuid('test_id')->nullable();
            $table->uuid('recall_id')->nullable();
            $table->string('report_number')->unique();
            $table->enum('report_type', ['inspection', 'laboratory', 'adverse_event', 'recall', 'compliance', 'periodic_safety', 'annual', 'quarterly', 'monthly', 'incident', 'deviation', 'change_control']);
            $table->enum('report_category', ['regulatory_submission', 'compliance_monitoring', 'safety_surveillance', 'quality_assurance', 'risk_management', 'post_market', 'clinical_trial', 'manufacturing', 'distribution']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('regulatory_authority');
            $table->date('submission_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->enum('status', ['draft', 'under_review', 'approved', 'submitted', 'accepted', 'rejected', 'revision_required', 'closed'])->default('draft');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent', 'critical'])->default('medium');
            $table->date('reporting_period_start')->nullable();
            $table->date('reporting_period_end')->nullable();
            $table->json('data_sources')->nullable();
            $table->text('methodology')->nullable();
            $table->json('findings')->nullable();
            $table->json('conclusions')->nullable();
            $table->json('recommendations')->nullable();
            $table->json('action_items')->nullable();
            $table->boolean('follow_up_required')->default(false);
            $table->date('follow_up_date')->nullable();
            $table->string('prepared_by');
            $table->string('reviewed_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->json('distribution_list')->nullable();
            $table->enum('confidentiality_level', ['public', 'internal', 'confidential', 'restricted', 'classified'])->default('internal');
            $table->string('version')->default('1.0');
            $table->json('revision_history')->nullable();
            $table->json('supporting_documents')->nullable();
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company_registrations')->onDelete('set null');
            $table->foreign('inspection_id')->references('id')->on('regulatory_inspections')->onDelete('set null');
            $table->foreign('test_id')->references('id')->on('laboratory_tests')->onDelete('set null');
            $table->foreign('recall_id')->references('id')->on('product_recalls')->onDelete('set null');
            $table->index(['tenant_id', 'status']);
            $table->index(['due_date']);
            $table->index(['submission_date']);
            $table->index(['priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulatory_reports');
    }
};
