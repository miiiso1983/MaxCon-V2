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
        Schema::create('certificates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('tenant_id');
            $table->string('certificate_name');
            $table->enum('certificate_type', ['gmp', 'iso', 'haccp', 'halal', 'organic', 'fda', 'ce_marking', 'other']);
            $table->string('certificate_number');
            $table->string('issuing_authority');
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->enum('certificate_status', ['active', 'expired', 'suspended', 'revoked']);
            $table->string('product_name')->nullable();
            $table->string('facility_name')->nullable();
            $table->text('scope_of_certification')->nullable();
            $table->date('audit_date')->nullable();
            $table->date('next_audit_date')->nullable();
            $table->string('certification_body')->nullable();
            $table->string('accreditation_number')->nullable();
            $table->integer('renewal_reminder_days')->default(30);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'certificate_type']);
            $table->index(['tenant_id', 'certificate_status']);
            $table->index(['tenant_id', 'expiry_date']);
            $table->index(['tenant_id', 'next_audit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
