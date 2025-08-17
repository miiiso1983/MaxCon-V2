<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('certificates')) {
            Schema::table('certificates', function (Blueprint $table) {
                if (!Schema::hasColumn('certificates', 'product_name')) {
                    $table->string('product_name')->nullable();
                }
                if (!Schema::hasColumn('certificates', 'next_audit_date')) {
                    $table->date('next_audit_date')->nullable();
                }
                if (!Schema::hasColumn('certificates', 'certification_body')) {
                    $table->string('certification_body')->nullable();
                }
                if (!Schema::hasColumn('certificates', 'renewal_reminder_days')) {
                    $table->integer('renewal_reminder_days')->default(30);
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('certificates')) {
            Schema::table('certificates', function (Blueprint $table) {
                if (Schema::hasColumn('certificates', 'product_name')) {
                    $table->dropColumn('product_name');
                }
                if (Schema::hasColumn('certificates', 'next_audit_date')) {
                    $table->dropColumn('next_audit_date');
                }
                if (Schema::hasColumn('certificates', 'certification_body')) {
                    $table->dropColumn('certification_body');
                }
                if (Schema::hasColumn('certificates', 'renewal_reminder_days')) {
                    $table->dropColumn('renewal_reminder_days');
                }
            });
        }
    }
};

