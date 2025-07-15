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
        Schema::table('invoice_items', function (Blueprint $table) {
            // Add new fields for invoice items
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed')->after('discount_amount');
            $table->decimal('total_amount', 10, 2)->default(0)->after('line_total');
            $table->text('notes')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'total_amount', 'notes']);
        });
    }
};
