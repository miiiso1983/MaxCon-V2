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
        Schema::table('warehouses', function (Blueprint $table) {
            // Add missing columns
            $table->string('address')->nullable()->after('location');
            $table->string('email')->nullable()->after('phone');
            $table->unsignedBigInteger('manager_id')->nullable()->after('email');
            $table->enum('type', ['main', 'branch', 'storage', 'pharmacy'])->default('main')->after('manager_id');
            $table->decimal('total_capacity', 15, 2)->nullable()->after('is_active');
            $table->decimal('used_capacity', 15, 2)->default(0)->after('total_capacity');
            $table->json('settings')->nullable()->after('used_capacity');

            // Drop old column if exists
            if (Schema::hasColumn('warehouses', 'manager_name')) {
                $table->dropColumn('manager_name');
            }

            // Add indexes (check if they don't exist)
            if (!Schema::hasIndex('warehouses', 'warehouses_tenant_id_index')) {
                $table->index('tenant_id');
            }
            if (!Schema::hasIndex('warehouses', 'warehouses_type_index')) {
                $table->index('type');
            }
            if (!Schema::hasIndex('warehouses', 'warehouses_is_active_index')) {
                $table->index('is_active');
            }
            if (!Schema::hasIndex('warehouses', 'warehouses_tenant_id_type_index')) {
                $table->index(['tenant_id', 'type']);
            }
            if (!Schema::hasIndex('warehouses', 'warehouses_tenant_id_is_active_index')) {
                $table->index(['tenant_id', 'is_active']);
            }

            // Add foreign keys
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['manager_id']);

            // Drop indexes
            $table->dropIndex(['tenant_id']);
            $table->dropIndex(['type']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['tenant_id', 'type']);
            $table->dropIndex(['tenant_id', 'is_active']);

            // Drop columns
            $table->dropColumn([
                'address', 'email', 'manager_id', 'type',
                'total_capacity', 'used_capacity', 'settings'
            ]);

            // Add back old column
            $table->string('manager_name')->nullable()->after('location');
        });
    }
};
