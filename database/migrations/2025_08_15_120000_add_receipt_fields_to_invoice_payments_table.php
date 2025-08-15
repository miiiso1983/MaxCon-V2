<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('invoice_payments', 'receipt_number')) {
                $table->string('receipt_number')->nullable()->after('reference_number');
            }
            if (!Schema::hasColumn('invoice_payments', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('receipt_number');
            }
            if (!Schema::hasColumn('invoice_payments', 'whatsapp_sent_at')) {
                $table->timestamp('whatsapp_sent_at')->nullable()->after('pdf_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoice_payments', function (Blueprint $table) {
            if (Schema::hasColumn('invoice_payments', 'whatsapp_sent_at')) {
                $table->dropColumn('whatsapp_sent_at');
            }
            if (Schema::hasColumn('invoice_payments', 'pdf_path')) {
                $table->dropColumn('pdf_path');
            }
            if (Schema::hasColumn('invoice_payments', 'receipt_number')) {
                $table->dropColumn('receipt_number');
            }
        });
    }
};

