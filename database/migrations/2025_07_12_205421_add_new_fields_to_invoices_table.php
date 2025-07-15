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
        Schema::table('invoices', function (Blueprint $table) {
            // Add new fields for invoice form
            $table->decimal('additional_charges', 10, 2)->default(0)->after('shipping_cost');
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed')->after('discount_amount');
            $table->decimal('subtotal_amount', 10, 2)->default(0)->after('type');

            // Rename subtotal to match new naming
            $table->renameColumn('subtotal', 'subtotal_old');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['additional_charges', 'discount_type', 'subtotal_amount']);
            $table->renameColumn('subtotal_old', 'subtotal');
        });
    }
};
