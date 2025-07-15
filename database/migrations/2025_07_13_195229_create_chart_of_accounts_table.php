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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('account_code', 20)->unique();
            $table->string('account_name');
            $table->string('account_name_en')->nullable();
            $table->enum('account_type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->enum('account_category', [
                'current_asset', 'non_current_asset',
                'current_liability', 'non_current_liability',
                'owners_equity',
                'operating_revenue', 'non_operating_revenue',
                'operating_expense', 'non_operating_expense'
            ]);
            $table->unsignedBigInteger('parent_account_id')->nullable();
            $table->integer('level')->default(1);
            $table->boolean('is_parent')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->string('currency_code', 3)->default('IQD');
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->boolean('is_system_account')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['tenant_id', 'account_type']);
            $table->index(['tenant_id', 'account_category']);
            $table->index(['tenant_id', 'parent_account_id']);
            $table->index(['tenant_id', 'is_active']);
            $table->index('account_code');

            // Foreign keys
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('parent_account_id')->references('id')->on('chart_of_accounts')->onDelete('set null');
            $table->foreign('cost_center_id')->references('id')->on('cost_centers')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
