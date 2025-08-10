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
        // Guard: avoid complex ALTER TABLE logic on SQLite (can cause duplicate columns during table rebuild)
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'sqlite') {
            // SQLite alter-table is limited; skip this safe-updater on SQLite
            // The invoices table already contains core columns like due_date in seed/base migrations
            return;
        }

        // Add missing columns to existing invoices table
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                // Add only missing columns
                if (!Schema::hasColumn('invoices', 'payment_status')) {
                    $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid')->after('status');
                }

                if (!Schema::hasColumn('invoices', 'subtotal')) {
                    $table->decimal('subtotal', 15, 2)->default(0)->after('payment_status');
                }

                if (!Schema::hasColumn('invoices', 'discount_percentage')) {
                    $table->decimal('discount_percentage', 5, 2)->default(0)->after('discount_amount');
                }

                if (!Schema::hasColumn('invoices', 'tax_percentage')) {
                    $table->decimal('tax_percentage', 5, 2)->default(0)->after('tax_amount');
                }

                if (!Schema::hasColumn('invoices', 'paid_amount')) {
                    $table->decimal('paid_amount', 15, 2)->default(0)->after('total_amount');
                }

                if (!Schema::hasColumn('invoices', 'remaining_amount')) {
                    $table->decimal('remaining_amount', 15, 2)->default(0)->after('paid_amount');
                }

                if (!Schema::hasColumn('invoices', 'previous_debt')) {
                    $table->decimal('previous_debt', 15, 2)->default(0)->after('remaining_amount');
                }

                if (!Schema::hasColumn('invoices', 'current_debt')) {
                    $table->decimal('current_debt', 15, 2)->default(0)->after('previous_debt');
                }

                if (!Schema::hasColumn('invoices', 'terms_conditions')) {
                    $table->text('terms_conditions')->nullable()->after('notes');
                }

                if (!Schema::hasColumn('invoices', 'qr_code_data')) {
                    $table->json('qr_code_data')->nullable()->after('currency');
                }

                if (!Schema::hasColumn('invoices', 'pdf_path')) {
                    $table->string('pdf_path')->nullable()->after('qr_code_data');
                }

                if (!Schema::hasColumn('invoices', 'email_sent_at')) {
                    $table->timestamp('email_sent_at')->nullable()->after('pdf_path');
                }

                if (!Schema::hasColumn('invoices', 'whatsapp_sent_at')) {
                    $table->timestamp('whatsapp_sent_at')->nullable()->after('email_sent_at');
                }

                if (!Schema::hasColumn('invoices', 'printed_at')) {
                    $table->timestamp('printed_at')->nullable()->after('whatsapp_sent_at');
                }
            });
        }

        // Create invoice_items table if not exists
        if (!Schema::hasTable('invoice_items')) {
            Schema::create('invoice_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('invoice_id');
                $table->unsignedBigInteger('product_id');
                $table->string('product_name'); // Store name at time of invoice
                $table->string('product_code')->nullable();
                $table->decimal('quantity', 10, 2);
                $table->string('unit')->default('قطعة');
                $table->decimal('unit_price', 15, 2);
                $table->decimal('selling_price', 15, 2);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('discount_percentage', 5, 2)->default(0);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->decimal('tax_percentage', 5, 2)->default(0);
                $table->decimal('line_total', 15, 2);
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

                $table->index(['invoice_id']);
                $table->index(['product_id']);
            });
        }

        // Create invoice_payments table if not exists
        if (!Schema::hasTable('invoice_payments')) {
            Schema::create('invoice_payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('invoice_id');
                $table->decimal('amount', 15, 2);
                $table->date('payment_date');
                $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'credit_card', 'other'])->default('cash');
                $table->string('reference_number')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by');
                $table->timestamps();

                $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

                $table->index(['invoice_id', 'payment_date']);
            });
        }

        // Create warehouse_stock table if not exists
        if (!Schema::hasTable('warehouse_stock')) {
            Schema::create('warehouse_stock', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('warehouse_id');
                $table->unsignedBigInteger('product_id');
                $table->decimal('quantity', 10, 2)->default(0);
                $table->decimal('reserved_quantity', 10, 2)->default(0);
                $table->decimal('available_quantity', 10, 2)->default(0);
                $table->decimal('min_stock_level', 10, 2)->default(0);
                $table->decimal('max_stock_level', 10, 2)->nullable();
                $table->timestamps();

                $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

                $table->unique(['warehouse_id', 'product_id']);
                $table->index(['warehouse_id', 'quantity']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove only the columns that were added by this migration
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                // Only drop columns that were actually added by this migration
                $columnsToCheck = [
                    'payment_status', 'subtotal', 'discount_percentage', 'tax_percentage',
                    'paid_amount', 'remaining_amount', 'previous_debt', 'current_debt',
                    'terms_conditions', 'qr_code_data', 'pdf_path',
                    'email_sent_at', 'whatsapp_sent_at', 'printed_at'
                ];

                $columnsToDrop = [];
                foreach ($columnsToCheck as $column) {
                    if (Schema::hasColumn('invoices', $column)) {
                        $columnsToDrop[] = $column;
                    }
                }

                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }

        Schema::dropIfExists('warehouse_stock');
        Schema::dropIfExists('invoice_payments');
        Schema::dropIfExists('invoice_items');
    }
};
