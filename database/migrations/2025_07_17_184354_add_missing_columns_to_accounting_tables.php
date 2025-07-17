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
        // Add missing columns to chart_of_accounts table
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('chart_of_accounts', 'cost_center_id')) {
                $table->unsignedBigInteger('cost_center_id')->nullable()->after('currency_code');
                $table->index('cost_center_id');
            }

            if (!Schema::hasColumn('chart_of_accounts', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable()->after('cost_center_id');
                $table->index('project_id');
            }

            if (!Schema::hasColumn('chart_of_accounts', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('project_id');
                $table->index('department_id');
            }

            // Add other missing columns if needed
            if (!Schema::hasColumn('chart_of_accounts', 'normal_balance')) {
                $table->enum('normal_balance', ['debit', 'credit'])->default('debit')->after('account_category');
            }

            if (!Schema::hasColumn('chart_of_accounts', 'allow_posting')) {
                $table->boolean('allow_posting')->default(true)->after('is_active');
            }

            if (!Schema::hasColumn('chart_of_accounts', 'require_cost_center')) {
                $table->boolean('require_cost_center')->default(false)->after('allow_posting');
            }

            if (!Schema::hasColumn('chart_of_accounts', 'require_project')) {
                $table->boolean('require_project')->default(false)->after('require_cost_center');
            }
        });

        // Add missing columns to journal_entry_lines table if it exists
        if (Schema::hasTable('journal_entry_lines')) {
            Schema::table('journal_entry_lines', function (Blueprint $table) {
                if (!Schema::hasColumn('journal_entry_lines', 'cost_center_id')) {
                    $table->unsignedBigInteger('cost_center_id')->nullable()->after('base_credit_amount');
                    $table->index('cost_center_id');
                }

                if (!Schema::hasColumn('journal_entry_lines', 'project_id')) {
                    $table->unsignedBigInteger('project_id')->nullable()->after('cost_center_id');
                    $table->index('project_id');
                }

                if (!Schema::hasColumn('journal_entry_lines', 'department_id')) {
                    $table->unsignedBigInteger('department_id')->nullable()->after('project_id');
                    $table->index('department_id');
                }
            });
        }

        // Create projects table if it doesn't exist
        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->string('code', 20);
                $table->string('name');
                $table->string('name_en')->nullable();
                $table->text('description')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->enum('status', ['planning', 'active', 'on_hold', 'completed', 'cancelled'])->default('planning');
                $table->decimal('budget_amount', 15, 2)->nullable();
                $table->decimal('actual_amount', 15, 2)->default(0);
                $table->unsignedBigInteger('manager_id')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->unique(['tenant_id', 'code']);
                $table->index('tenant_id');
                $table->index('status');
                $table->index('is_active');
            });
        }

        // Create departments table if it doesn't exist
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->string('code', 20);
                $table->string('name');
                $table->string('name_en')->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->integer('level')->default(1);
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('manager_id')->nullable();
                $table->text('description')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->unique(['tenant_id', 'code']);
                $table->index('tenant_id');
                $table->index('parent_id');
                $table->index('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove added columns from chart_of_accounts
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->dropColumn(['cost_center_id', 'project_id', 'department_id', 'normal_balance', 'allow_posting', 'require_cost_center', 'require_project']);
        });

        // Remove added columns from journal_entry_lines
        if (Schema::hasTable('journal_entry_lines')) {
            Schema::table('journal_entry_lines', function (Blueprint $table) {
                $table->dropColumn(['cost_center_id', 'project_id', 'department_id']);
            });
        }

        // Drop created tables
        Schema::dropIfExists('projects');
        Schema::dropIfExists('departments');
    }
};
