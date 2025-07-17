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
        Schema::table('product_recalls', function (Blueprint $table) {
            $table->uuid('product_id')->nullable()->change();

            // Add product_name column if it doesn't exist
            if (!Schema::hasColumn('product_recalls', 'product_name')) {
                $table->string('product_name')->nullable()->after('product_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_recalls', function (Blueprint $table) {
            $table->uuid('product_id')->nullable(false)->change();

            if (Schema::hasColumn('product_recalls', 'product_name')) {
                $table->dropColumn('product_name');
            }
        });
    }
};
