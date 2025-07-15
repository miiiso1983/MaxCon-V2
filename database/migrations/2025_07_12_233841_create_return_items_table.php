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
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained()->onDelete('cascade');
            $table->foreignId('invoice_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->string('product_code')->nullable();
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('quantity_returned');
            $table->integer('quantity_original'); // Original quantity from invoice
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_amount', 15, 2);
            $table->text('condition')->nullable(); // good, damaged, expired
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();

            // For exchanges
            $table->foreignId('exchange_product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->integer('exchange_quantity')->nullable();
            $table->decimal('exchange_unit_price', 10, 2)->nullable();
            $table->decimal('exchange_total_amount', 15, 2)->nullable();

            $table->timestamps();

            $table->index(['return_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_items');
    }
};
