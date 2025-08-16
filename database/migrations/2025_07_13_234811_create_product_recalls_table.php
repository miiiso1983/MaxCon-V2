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
        Schema::create('product_recalls', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('tenant_id');
            $table->uuid('product_id');
            $table->string('recall_number')->unique();
            $table->enum('recall_type', ['voluntary', 'mandatory', 'fda_requested', 'market_withdrawal', 'stock_recovery']);
            $table->enum('recall_class', ['class_i', 'class_ii', 'class_iii', 'market_withdrawal']);
            $table->text('reason');
            $table->text('description');
            $table->json('affected_batches')->nullable();
            $table->integer('quantity_affected');
            $table->enum('distribution_level', ['consumer', 'retail', 'wholesale', 'hospital', 'pharmacy', 'clinic', 'distributor']);
            $table->json('countries_affected')->nullable();
            $table->text('health_hazard')->nullable();
            $table->text('risk_assessment')->nullable();
            $table->date('initiated_date');
            $table->date('notification_date')->nullable();
            $table->date('completion_date')->nullable();
            $table->enum('status', ['initiated', 'in_progress', 'ongoing', 'completed', 'closed', 'terminated', 'on_hold'])->default('initiated');
            $table->string('regulatory_authority');
            $table->boolean('authority_notification')->default(false);
            $table->boolean('public_notification')->default(false);
            $table->boolean('media_release')->default(false);
            $table->boolean('customer_notification')->default(false);
            $table->boolean('healthcare_notification')->default(false);
            $table->json('recall_strategy')->nullable();
            $table->json('effectiveness_checks')->nullable();
            $table->integer('quantity_recovered')->default(0);
            $table->decimal('recovery_percentage', 5, 2)->default(0);
            $table->string('disposal_method')->nullable();
            $table->text('root_cause_analysis')->nullable();
            $table->json('corrective_actions')->nullable();
            $table->json('preventive_actions')->nullable();
            $table->boolean('follow_up_required')->default(false);
            $table->date('follow_up_date')->nullable();
            $table->date('closure_date')->nullable();
            $table->json('lessons_learned')->nullable();
            $table->json('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('product_registrations')->onDelete('cascade');
            $table->index(['tenant_id', 'status']);
            $table->index(['initiated_date']);
            $table->index(['completion_date']);
            $table->index(['recall_class']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_recalls');
    }
};
