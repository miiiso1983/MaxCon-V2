<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\DB; 

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_recalls')) {
            Schema::table('product_recalls', function (Blueprint $table) {
                // Align ID to UUID (char(36)) if currently numeric
                $connection = Schema::getConnection();
                $columns = $connection->getDoctrineSchemaManager()->listTableColumns('product_recalls');
                if (isset($columns['id'])) {
                    $type = (string) $columns['id']->getType();
                    if ($type !== 'string') {
                        $table->char('id', 36)->change();
                    }
                }

                // Add closure_date if completion_date is missing
                if (!Schema::hasColumn('product_recalls', 'completion_date') && !Schema::hasColumn('product_recalls', 'closure_date')) {
                    $table->date('closure_date')->nullable();
                }

                // Ensure recall_number exists
                if (!Schema::hasColumn('product_recalls', 'recall_number')) {
                    $table->string('recall_number')->nullable()->index();
                }
            });
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

