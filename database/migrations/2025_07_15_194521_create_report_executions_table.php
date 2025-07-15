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
        Schema::create('report_executions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->json('parameters')->nullable(); // Runtime parameters
            $table->string('status')->default('pending'); // pending, running, completed, failed, cancelled
            $table->json('result_data')->nullable(); // Cached result data
            $table->decimal('execution_time', 8, 2)->nullable(); // Execution time in seconds
            $table->integer('row_count')->nullable(); // Number of rows returned
            $table->string('file_path')->nullable(); // Path to exported file
            $table->string('export_format')->nullable(); // pdf, excel, csv, html, json
            $table->text('error_message')->nullable(); // Error details if failed
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['report_id', 'status']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_executions');
    }
};
