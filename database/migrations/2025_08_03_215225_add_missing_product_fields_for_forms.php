<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // إضافة purchase_price إذا لم يكن موجوداً
            if (!Schema::hasColumn('products', 'purchase_price')) {
                $table->decimal('purchase_price', 15, 2)->default(0)->after('cost_price');
            }

            // إضافة generic_name إذا لم يكن موجوداً
            if (!Schema::hasColumn('products', 'generic_name')) {
                $table->string('generic_name')->nullable()->after('name');
            }

            // إضافة unit إذا لم يكن موجوداً
            if (!Schema::hasColumn('products', 'unit')) {
                $table->string('unit')->default('piece')->after('unit_of_measure');
            }

            // إضافة storage_conditions إذا لم يكن موجوداً
            if (!Schema::hasColumn('products', 'storage_conditions')) {
                $table->text('storage_conditions')->nullable()->after('manufacturing_date');
            }

            // تحديث القيم الافتراضية للحقول الموجودة
            // نسخ قيم cost_price إلى purchase_price للمنتجات الموجودة
            DB::statement('UPDATE products SET purchase_price = cost_price WHERE purchase_price = 0 OR purchase_price IS NULL');

            // نسخ قيم stock_quantity إلى current_stock للمنتجات الموجودة (إذا كان current_stock موجود)
            if (Schema::hasColumn('products', 'current_stock')) {
                DB::statement('UPDATE products SET current_stock = stock_quantity WHERE current_stock = 0 OR current_stock IS NULL');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
