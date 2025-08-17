<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_recalls')) {
            // Align ID to UUID (char(36)) using raw SQL to avoid DBAL dependency
            try {
                DB::statement("ALTER TABLE product_recalls MODIFY COLUMN id CHAR(36) NOT NULL");
            } catch (\Throwable $e) {
                // ignore if already char(36)
            }

            // Add closure_date if completion_date is missing
            if (!Schema::hasColumn('product_recalls', 'completion_date') && !Schema::hasColumn('product_recalls', 'closure_date')) {
                Schema::table('product_recalls', function (Blueprint $table) {
                    $table->date('closure_date')->nullable();
                });
            }

            // Ensure recall_number exists
            if (!Schema::hasColumn('product_recalls', 'recall_number')) {
                Schema::table('product_recalls', function (Blueprint $table) {
                    $table->string('recall_number')->nullable()->index();
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_recalls')) {
            Schema::table('product_recalls', function (Blueprint $table) {
                // No-op safe down
            });
        }
    }
};

