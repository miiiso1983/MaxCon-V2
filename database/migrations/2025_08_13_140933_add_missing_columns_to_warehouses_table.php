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
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('warehouses', 'location')) {
                $table->string('location')->nullable()->after('description');
            }
            if (!Schema::hasColumn('warehouses', 'address')) {
                $table->text('address')->nullable()->after('location');
            }
            if (!Schema::hasColumn('warehouses', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('warehouses', 'manager_id')) {
                $table->unsignedBigInteger('manager_id')->nullable()->after('email');
            }
            if (!Schema::hasColumn('warehouses', 'type')) {
                $table->enum('type', ['main', 'branch', 'storage', 'pharmacy'])->default('main')->after('manager_id');
            }
            if (!Schema::hasColumn('warehouses', 'total_capacity')) {
                $table->decimal('total_capacity', 15, 2)->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('warehouses', 'used_capacity')) {
                $table->decimal('used_capacity', 15, 2)->default(0)->after('total_capacity');
            }
            if (!Schema::hasColumn('warehouses', 'settings')) {
                $table->json('settings')->nullable()->after('used_capacity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            // Drop columns if they exist
            $columnsToDrop = [];

            if (Schema::hasColumn('warehouses', 'settings')) {
                $columnsToDrop[] = 'settings';
            }
            if (Schema::hasColumn('warehouses', 'used_capacity')) {
                $columnsToDrop[] = 'used_capacity';
            }
            if (Schema::hasColumn('warehouses', 'total_capacity')) {
                $columnsToDrop[] = 'total_capacity';
            }
            if (Schema::hasColumn('warehouses', 'type')) {
                $columnsToDrop[] = 'type';
            }
            if (Schema::hasColumn('warehouses', 'manager_id')) {
                $columnsToDrop[] = 'manager_id';
            }
            if (Schema::hasColumn('warehouses', 'email')) {
                $columnsToDrop[] = 'email';
            }
            if (Schema::hasColumn('warehouses', 'address')) {
                $columnsToDrop[] = 'address';
            }
            if (Schema::hasColumn('warehouses', 'location')) {
                $columnsToDrop[] = 'location';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
