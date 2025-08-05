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
        Schema::table('suppliers', function (Blueprint $table) {
            // Add currency column if it doesn't exist
            if (!Schema::hasColumn('suppliers', 'currency')) {
                $table->string('currency', 3)->default('IQD')->after('current_balance');
            }

            // Add category column if it doesn't exist
            if (!Schema::hasColumn('suppliers', 'category')) {
                $table->string('category')->nullable()->after('currency');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers_table_safe', function (Blueprint $table) {
            if (Schema::hasColumn('suppliers', 'currency')) {
                $table->dropColumn('currency');
            }

            if (Schema::hasColumn('suppliers', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
