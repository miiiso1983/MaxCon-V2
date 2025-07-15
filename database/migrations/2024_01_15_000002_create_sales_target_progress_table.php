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
        Schema::create('sales_target_progress', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            
            // Target Reference
            $table->unsignedBigInteger('sales_target_id');
            
            // Progress Data
            $table->date('progress_date'); // تاريخ التقدم
            $table->decimal('daily_quantity', 15, 2)->default(0); // الكمية اليومية
            $table->decimal('daily_value', 15, 2)->default(0); // القيمة اليومية
            $table->decimal('cumulative_quantity', 15, 2)->default(0); // الكمية التراكمية
            $table->decimal('cumulative_value', 15, 2)->default(0); // القيمة التراكمية
            $table->decimal('progress_percentage', 5, 2)->default(0); // نسبة التقدم
            
            // Source Information
            $table->string('source_type')->nullable(); // مصدر البيانات (invoice, order, etc.)
            $table->unsignedBigInteger('source_id')->nullable(); // ID المصدر
            $table->json('source_details')->nullable(); // تفاصيل المصدر
            
            // Metadata
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['tenant_id', 'sales_target_id']);
            $table->index(['progress_date']);
            $table->index(['sales_target_id', 'progress_date']);
            $table->unique(['sales_target_id', 'progress_date']); // يوم واحد لكل هدف
            
            // Foreign Keys
            $table->foreign('sales_target_id')->references('id')->on('sales_targets')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_target_progress');
    }
};
