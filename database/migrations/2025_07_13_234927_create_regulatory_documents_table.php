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
        Schema::create('regulatory_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('company_id')->nullable();
            $table->uuid('product_id')->nullable();
            $table->string('document_number')->unique();
            $table->enum('document_type', ['license', 'certificate', 'permit', 'registration', 'approval', 'sop', 'policy', 'guideline', 'specification', 'protocol', 'report', 'correspondence', 'submission', 'amendment', 'withdrawal']);
            $table->enum('document_category', ['regulatory_submission', 'quality_documentation', 'manufacturing', 'clinical', 'safety', 'labeling', 'advertising', 'import_export', 'inspection', 'compliance', 'legal', 'administrative']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('version')->default('1.0');
            $table->string('language')->default('ar');
            $table->string('regulatory_authority')->nullable();
            $table->date('submission_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('review_date')->nullable();
            $table->date('next_review_date')->nullable();
            $table->enum('status', ['draft', 'under_review', 'approved', 'effective', 'expired', 'superseded', 'withdrawn', 'archived'])->default('draft');
            $table->enum('confidentiality_level', ['public', 'internal', 'confidential', 'restricted', 'classified'])->default('internal');
            $table->enum('access_level', ['all_users', 'authorized_only', 'management_only', 'regulatory_team', 'quality_team'])->default('authorized_only');
            $table->string('author');
            $table->string('reviewer')->nullable();
            $table->string('approver')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable(); // in bytes
            $table->string('file_type')->nullable();
            $table->string('checksum')->nullable();
            $table->text('digital_signature')->nullable();
            $table->integer('retention_period')->nullable(); // in years
            $table->date('disposal_date')->nullable();
            $table->string('archive_location')->nullable();
            $table->json('related_documents')->nullable();
            $table->json('keywords')->nullable();
            $table->json('tags')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('company_registrations')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('product_registrations')->onDelete('set null');
            $table->index(['tenant_id', 'status']);
            $table->index(['expiry_date']);
            $table->index(['next_review_date']);
            $table->index(['document_type']);
            $table->index(['document_category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regulatory_documents');
    }
};
