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
        Schema::table('cost_centers', function (Blueprint $table) {
            if (!Schema::hasColumn('cost_centers', 'currency_code')) {
                // Place after actual_amount if exists, otherwise just add
                try {
                    $table->string('currency_code', 3)->default('IQD')->after('actual_amount');
                } catch (\Throwable $e) {
                    // Fallback without positioning if DBMS/table layout differs
                    $table->string('currency_code', 3)->default('IQD');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cost_centers', function (Blueprint $table) {
            if (Schema::hasColumn('cost_centers', 'currency_code')) {
                $table->dropColumn('currency_code');
            }
        });
    }
};

