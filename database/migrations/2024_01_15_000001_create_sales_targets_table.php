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
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            
            // Target Details
            $table->string('title'); // عنوان الهدف
            $table->text('description')->nullable(); // وصف الهدف
            
            // Target Type & Level
            $table->enum('target_type', ['product', 'vendor', 'sales_team', 'department', 'sales_rep']); // نوع الهدف
            $table->unsignedBigInteger('target_entity_id'); // ID الكيان المستهدف
            $table->string('target_entity_name'); // اسم الكيان المستهدف
            
            // Time Period
            $table->enum('period_type', ['monthly', 'quarterly', 'yearly']); // نوع الفترة
            $table->date('start_date'); // تاريخ البداية
            $table->date('end_date'); // تاريخ النهاية
            $table->integer('year'); // السنة
            $table->integer('month')->nullable(); // الشهر (للأهداف الشهرية)
            $table->integer('quarter')->nullable(); // الربع (للأهداف الفصلية)
            
            // Target Values
            $table->enum('measurement_type', ['quantity', 'value', 'both']); // نوع القياس
            $table->decimal('target_quantity', 15, 2)->nullable(); // الكمية المستهدفة
            $table->decimal('target_value', 15, 2)->nullable(); // القيمة المستهدفة
            $table->string('currency', 3)->default('IQD'); // العملة
            $table->string('unit')->nullable(); // الوحدة (قطعة، كيلو، إلخ)
            
            // Progress Tracking
            $table->decimal('achieved_quantity', 15, 2)->default(0); // الكمية المحققة
            $table->decimal('achieved_value', 15, 2)->default(0); // القيمة المحققة
            $table->decimal('progress_percentage', 5, 2)->default(0); // نسبة التقدم
            $table->timestamp('last_updated_at')->nullable(); // آخر تحديث للتقدم
            
            // Status & Notifications
            $table->enum('status', ['active', 'completed', 'paused', 'cancelled'])->default('active');
            $table->boolean('notification_80_sent')->default(false); // إشعار 80%
            $table->boolean('notification_100_sent')->default(false); // إشعار 100%
            $table->json('notification_settings')->nullable(); // إعدادات الإشعارات
            
            // Metadata
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->json('additional_data')->nullable(); // بيانات إضافية
            $table->text('notes')->nullable(); // ملاحظات
            
            $table->timestamps();
            
            // Indexes
            $table->index(['tenant_id', 'target_type']);
            $table->index(['tenant_id', 'period_type', 'year']);
            $table->index(['tenant_id', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index(['target_type', 'target_entity_id']);
            
            // Foreign Keys
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_targets');
    }
};
