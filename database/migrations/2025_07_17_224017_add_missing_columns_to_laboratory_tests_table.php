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
        Schema::table('laboratory_tests', function (Blueprint $table) {
            // Add missing columns for overdue tests functionality
            $table->string('test_name')->after('test_number');
            $table->string('product_name')->after('test_name');
            $table->string('batch_number')->after('product_name');
            $table->date('expected_completion_date')->nullable()->after('completion_date');
            $table->decimal('cost', 10, 2)->nullable()->after('notes');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent', 'critical'])->default('medium')->after('cost');

            // Modify existing columns to match seeder data
            $table->string('test_method')->nullable()->change();
            $table->json('specifications')->nullable()->change();

            // Add indexes for overdue functionality
            $table->index(['test_date', 'status']);
            $table->index(['expected_completion_date']);
            $table->index(['priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laboratory_tests', function (Blueprint $table) {
            $table->dropColumn([
                'test_name',
                'product_name',
                'batch_number',
                'expected_completion_date',
                'cost',
                'priority'
            ]);

            $table->dropIndex(['test_date', 'status']);
            $table->dropIndex(['expected_completion_date']);
            $table->dropIndex(['priority']);
        });
    }
};
