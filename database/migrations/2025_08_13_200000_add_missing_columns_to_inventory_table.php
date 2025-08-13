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
        Schema::table('inventory', function (Blueprint $table) {
            // Add tenant_id if it doesn't exist
            if (!Schema::hasColumn('inventory', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')->after('id');
                $table->index('tenant_id');
            }
            
            // Add location_code if it doesn't exist
            if (!Schema::hasColumn('inventory', 'location_code')) {
                $table->string('location_code', 50)->nullable()->after('cost_price');
                $table->index('location_code');
            }
            
            // Update status enum to include quarantine
            if (Schema::hasColumn('inventory', 'status')) {
                $table->enum('status', ['active', 'quarantine', 'damaged', 'expired'])->default('active')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            if (Schema::hasColumn('inventory', 'tenant_id')) {
                $table->dropIndex(['tenant_id']);
                $table->dropColumn('tenant_id');
            }
            
            if (Schema::hasColumn('inventory', 'location_code')) {
                $table->dropIndex(['location_code']);
                $table->dropColumn('location_code');
            }
        });
    }
};
