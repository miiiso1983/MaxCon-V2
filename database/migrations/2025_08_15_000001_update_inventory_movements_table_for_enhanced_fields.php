<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory_movements', 'tenant_id')) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            }
            if (!Schema::hasColumn('inventory_movements', 'movement_number')) {
                $table->string('movement_number', 100)->nullable()->after('tenant_id');
                $table->index('movement_number');
            }
            if (!Schema::hasColumn('inventory_movements', 'movement_type')) {
                $table->string('movement_type', 50)->nullable()->after('product_id');
                $table->index('movement_type');
            }
            if (!Schema::hasColumn('inventory_movements', 'movement_reason')) {
                $table->string('movement_reason', 100)->nullable()->after('movement_type');
            }
            if (!Schema::hasColumn('inventory_movements', 'unit_cost')) {
                $table->decimal('unit_cost', 15, 2)->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('inventory_movements', 'total_cost')) {
                $table->decimal('total_cost', 15, 2)->default(0)->after('unit_cost');
            }
            if (!Schema::hasColumn('inventory_movements', 'balance_before')) {
                $table->decimal('balance_before', 15, 3)->default(0)->after('total_cost');
            }
            if (!Schema::hasColumn('inventory_movements', 'balance_after')) {
                $table->decimal('balance_after', 15, 3)->default(0)->after('balance_before');
            }
            if (!Schema::hasColumn('inventory_movements', 'movement_date')) {
                $table->dateTime('movement_date')->nullable()->after('balance_after');
            }
            if (!Schema::hasColumn('inventory_movements', 'reference_number')) {
                $table->string('reference_number', 191)->nullable()->after('movement_date');
            }
            if (!Schema::hasColumn('inventory_movements', 'inventory_id')) {
                $table->unsignedBigInteger('inventory_id')->nullable()->after('product_id');
                $table->index('inventory_id');
            }
        });

        // Backfill movement_type from legacy 'type' column when available
        if (Schema::hasColumn('inventory_movements', 'type') && Schema::hasColumn('inventory_movements', 'movement_type')) {
            DB::statement("UPDATE inventory_movements SET movement_type = COALESCE(movement_type, type)");
        }

        // Backfill unit_cost from legacy 'cost_price' column when available
        if (Schema::hasColumn('inventory_movements', 'cost_price') && Schema::hasColumn('inventory_movements', 'unit_cost')) {
            DB::statement("UPDATE inventory_movements SET unit_cost = COALESCE(unit_cost, cost_price)");
        }

        // Compute total_cost where possible
        if (Schema::hasColumn('inventory_movements', 'total_cost') && Schema::hasColumn('inventory_movements', 'unit_cost')) {
            DB::statement("UPDATE inventory_movements SET total_cost = COALESCE(total_cost, 0) + (COALESCE(quantity,0) * COALESCE(unit_cost,0)) WHERE total_cost IS NULL OR total_cost = 0");
        }
    }

    public function down(): void
    {
        Schema::table('inventory_movements', function (Blueprint $table) {
            // Be conservative on down(): just drop added columns if they exist
            foreach ([
                'tenant_id','movement_number','movement_type','movement_reason','unit_cost','total_cost',
                'balance_before','balance_after','movement_date','reference_number','inventory_id'
            ] as $col) {
                if (Schema::hasColumn('inventory_movements', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

