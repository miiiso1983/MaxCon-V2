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
        if (!Schema::hasColumn('chart_of_accounts', 'parent_account_id')) {
            Schema::table('chart_of_accounts', function (Blueprint $table) {
                $table->unsignedBigInteger('parent_account_id')->nullable()->after('account_category');
                $table->index(['tenant_id', 'parent_account_id']);
            });

            // Try to add FK constraint safely (ignore if fails)
            try {
                Schema::table('chart_of_accounts', function (Blueprint $table) {
                    $table->foreign('parent_account_id')
                        ->references('id')
                        ->on('chart_of_accounts')
                        ->nullOnDelete();
                });
            } catch (\Throwable $e) {
                // Ignore FK creation errors (e.g., if storage engine does not support FKs)
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('chart_of_accounts', 'parent_account_id')) {
            try {
                Schema::table('chart_of_accounts', function (Blueprint $table) {
                    $table->dropForeign(['parent_account_id']);
                });
            } catch (\Throwable $e) {
                // ignore if FK missing
            }

            Schema::table('chart_of_accounts', function (Blueprint $table) {
                $table->dropIndex(['tenant_id', 'parent_account_id']);
                $table->dropColumn('parent_account_id');
            });
        }
    }
};

