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
        Schema::table('regulatory_inspections', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('regulatory_inspections', 'overall_rating')) {
                $table->enum('overall_rating', ['excellent', 'good', 'satisfactory', 'needs_improvement', 'unsatisfactory', 'critical'])->nullable()->after('status');
            }

            // Also add compliance_score if it doesn't exist
            if (!Schema::hasColumn('regulatory_inspections', 'compliance_score')) {
                $table->integer('compliance_score')->nullable()->after('overall_rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regulatory_inspections', function (Blueprint $table) {
            if (Schema::hasColumn('regulatory_inspections', 'overall_rating')) {
                $table->dropColumn('overall_rating');
            }

            if (Schema::hasColumn('regulatory_inspections', 'compliance_score')) {
                $table->dropColumn('compliance_score');
            }
        });
    }
};
