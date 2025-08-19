<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    public function up(): void
    {
        // Case A: Table already exists -> ensure essential columns
        if (Schema::hasTable('journal_entry_details')) {
            Schema::table('journal_entry_details', function (Blueprint $table) {
                if (!Schema::hasColumn('journal_entry_details', 'currency_code')) {
                    $table->string('currency_code', 3)->default('IQD')->after('credit_amount');
                }
                if (!Schema::hasColumn('journal_entry_details', 'exchange_rate')) {
                    $table->decimal('exchange_rate', 10, 4)->default(1.0000)->after('currency_code');
                }
                if (!Schema::hasColumn('journal_entry_details', 'debit_amount_local')) {
                    $table->decimal('debit_amount_local', 15, 2)->default(0)->after('exchange_rate');
                }
                if (!Schema::hasColumn('journal_entry_details', 'credit_amount_local')) {
                    $table->decimal('credit_amount_local', 15, 2)->default(0)->after('debit_amount_local');
                }
                if (!Schema::hasColumn('journal_entry_details', 'cost_center_id')) {
                    $table->unsignedBigInteger('cost_center_id')->nullable()->after('credit_amount_local');
                }
                if (!Schema::hasColumn('journal_entry_details', 'reference_number')) {
                    $table->string('reference_number')->nullable()->after('cost_center_id');
                }
                if (!Schema::hasColumn('journal_entry_details', 'line_number')) {
                    $table->integer('line_number')->default(1)->after('reference_number');
                }
                if (!Schema::hasColumn('journal_entry_details', 'created_at')) {
                    $table->timestamps();
                }

                // Indexes (best-effort)
                try { $table->index(['tenant_id', 'journal_entry_id']); } catch (\Throwable $e) {}
                try { $table->index(['tenant_id', 'account_id']); } catch (\Throwable $e) {}
                try { $table->index(['tenant_id', 'cost_center_id']); } catch (\Throwable $e) {}
                try { $table->index('line_number'); } catch (\Throwable $e) {}
            });
            return;
        }

        // Case B: Legacy table exists -> try to rename, else create and copy
        if (Schema::hasTable('journal_entry_lines')) {
            try {
                Schema::rename('journal_entry_lines', 'journal_entry_details');
                // After rename, ensure columns/indexes (some may be missing)
                Schema::table('journal_entry_details', function (Blueprint $table) {
                    if (!Schema::hasColumn('journal_entry_details', 'currency_code')) {
                        $table->string('currency_code', 3)->default('IQD')->after('credit_amount');
                    }
                    if (!Schema::hasColumn('journal_entry_details', 'exchange_rate')) {
                        $table->decimal('exchange_rate', 10, 4)->default(1.0000)->after('currency_code');
                    }
                    if (!Schema::hasColumn('journal_entry_details', 'debit_amount_local')) {
                        $table->decimal('debit_amount_local', 15, 2)->default(0)->after('exchange_rate');
                    }
                    if (!Schema::hasColumn('journal_entry_details', 'credit_amount_local')) {
                        $table->decimal('credit_amount_local', 15, 2)->default(0)->after('debit_amount_local');
                    }
                    if (!Schema::hasColumn('journal_entry_details', 'cost_center_id')) {
                        $table->unsignedBigInteger('cost_center_id')->nullable()->after('credit_amount_local');
                    }
                    if (!Schema::hasColumn('journal_entry_details', 'reference_number')) {
                        $table->string('reference_number')->nullable()->after('cost_center_id');
                    }
                    if (!Schema::hasColumn('journal_entry_details', 'line_number')) {
                        $table->integer('line_number')->default(1)->after('reference_number');
                    }
                    if (!Schema::hasColumn('journal_entry_details', 'created_at')) {
                        $table->timestamps();
                    }

                    try { $table->index(['tenant_id', 'journal_entry_id']); } catch (\Throwable $e) {}
                    try { $table->index(['tenant_id', 'account_id']); } catch (\Throwable $e) {}
                    try { $table->index(['tenant_id', 'cost_center_id']); } catch (\Throwable $e) {}
                    try { $table->index('line_number'); } catch (\Throwable $e) {}
                });
                return;
            } catch (\Throwable $e) {
                // Fall through to create new and copy
            }

            // Create new details table per spec
            Schema::create('journal_entry_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('journal_entry_id');
                $table->unsignedBigInteger('account_id');
                $table->text('description')->nullable();
                $table->decimal('debit_amount', 15, 2)->default(0);
                $table->decimal('credit_amount', 15, 2)->default(0);
                $table->string('currency_code', 3)->default('IQD');
                $table->decimal('exchange_rate', 10, 4)->default(1.0000);
                $table->decimal('debit_amount_local', 15, 2)->default(0);
                $table->decimal('credit_amount_local', 15, 2)->default(0);
                $table->unsignedBigInteger('cost_center_id')->nullable();
                $table->string('reference_number')->nullable();
                $table->integer('line_number')->default(1);
                $table->timestamps();

                $table->index(['tenant_id', 'journal_entry_id']);
                $table->index(['tenant_id', 'account_id']);
                $table->index(['tenant_id', 'cost_center_id']);
                $table->index('line_number');
            });

            // Copy data from legacy table into new table (best-effort)
            $lineCounters = [];
            DB::table('journal_entry_lines')->orderBy('id')->chunkById(500, function ($rows) use (&$lineCounters) {
                $batch = [];
                foreach ($rows as $r) {
                    $tenantId = $r->tenant_id ?? null;
                    $entryId = $r->journal_entry_id ?? null;
                    $accountId = $r->account_id ?? null;
                    $debit = (float)($r->debit_amount ?? 0);
                    $credit = (float)($r->credit_amount ?? 0);
                    $rate = isset($r->exchange_rate) ? (float)$r->exchange_rate : 1.0000;
                    $currency = $r->currency_code ?? 'IQD';
                    $desc = $r->description ?? null;
                    $costCenterId = $r->cost_center_id ?? null;
                    $ref = $r->reference_number ?? null;

                    $key = $entryId ?? 0;
                    if (!isset($lineCounters[$key])) { $lineCounters[$key] = 0; }
                    $lineNo = isset($r->line_number) ? (int)$r->line_number : (++$lineCounters[$key]);

                    $createdAt = property_exists($r, 'created_at') ? $r->created_at : Carbon::now();
                    $updatedAt = property_exists($r, 'updated_at') ? $r->updated_at : Carbon::now();

                    $batch[] = [
                        'tenant_id' => $tenantId,
                        'journal_entry_id' => $entryId,
                        'account_id' => $accountId,
                        'description' => $desc,
                        'debit_amount' => $debit,
                        'credit_amount' => $credit,
                        'currency_code' => $currency,
                        'exchange_rate' => $rate,
                        'debit_amount_local' => $debit * $rate,
                        'credit_amount_local' => $credit * $rate,
                        'cost_center_id' => $costCenterId,
                        'reference_number' => $ref,
                        'line_number' => $lineNo,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                    ];
                }
                if (!empty($batch)) {
                    DB::table('journal_entry_details')->insert($batch);
                }
            });

            return;
        }

        // Case C: No legacy table -> create fresh table
        Schema::create('journal_entry_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('journal_entry_id');
            $table->unsignedBigInteger('account_id');
            $table->text('description')->nullable();
            $table->decimal('debit_amount', 15, 2)->default(0);
            $table->decimal('credit_amount', 15, 2)->default(0);
            $table->string('currency_code', 3)->default('IQD');
            $table->decimal('exchange_rate', 10, 4)->default(1.0000);
            $table->decimal('debit_amount_local', 15, 2)->default(0);
            $table->decimal('credit_amount_local', 15, 2)->default(0);
            $table->unsignedBigInteger('cost_center_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->integer('line_number')->default(1);
            $table->timestamps();

            $table->index(['tenant_id', 'journal_entry_id']);
            $table->index(['tenant_id', 'account_id']);
            $table->index(['tenant_id', 'cost_center_id']);
            $table->index('line_number');
        });
    }

    public function down(): void
    {
        // Safe: do not drop/rename automatically to avoid data loss in legacy setups
        // If you need to revert, handle manually.
    }
};

