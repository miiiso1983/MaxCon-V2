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
        // 1) Ensure journal_number column exists
        if (!Schema::hasColumn('journal_entries', 'journal_number')) {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->string('journal_number', 50)->nullable()->after('tenant_id');
                $table->index('journal_number');
            });
        }

        // 2) Backfill journal_number for existing rows
        if (Schema::hasColumn('journal_entries', 'journal_number')) {
            $hasEntryNumber = Schema::hasColumn('journal_entries', 'entry_number');

            // Build counters per tenant + yyyymm based on any existing journal_numbers
            $counters = [];
            $existing = DB::table('journal_entries')
                ->select('tenant_id', 'entry_date', 'journal_number')
                ->whereNotNull('journal_number')
                ->get();
            foreach ($existing as $row) {
                if (!empty($row->journal_number)) {
                    $yyyymm = Carbon::parse($row->entry_date)->format('Ym');
                    $key = $row->tenant_id . '-' . $yyyymm;
                    $seq = 0;
                    if (preg_match('/JE-(\d{6})-(\d{4})/', $row->journal_number, $m)) {
                        $seq = (int)$m[2];
                        $yyyymm = $m[1];
                        $key = $row->tenant_id . '-' . $yyyymm;
                    }
                    $counters[$key] = max($counters[$key] ?? 0, $seq);
                }
            }

            // Chunk through rows missing journal_number
            DB::table('journal_entries')
                ->whereNull('journal_number')
                ->orderBy('id')
                ->chunkById(200, function ($rows) use (&$counters, $hasEntryNumber) {
                    foreach ($rows as $r) {
                        $number = null;
                        if ($hasEntryNumber && !empty($r->entry_number)) {
                            $number = $r->entry_number;
                        } else {
                            $yyyymm = Carbon::parse($r->entry_date)->format('Ym');
                            $key = $r->tenant_id . '-' . $yyyymm;
                            $next = ($counters[$key] ?? 0) + 1;
                            $counters[$key] = $next;
                            $number = 'JE-' . $yyyymm . '-' . str_pad((string)$next, 4, '0', STR_PAD_LEFT);
                        }
                        DB::table('journal_entries')->where('id', $r->id)->update(['journal_number' => $number]);
                    }
                });

            // Try to enforce uniqueness on journal_number
            try {
                Schema::table('journal_entries', function (Blueprint $table) {
                    $table->unique('journal_number');
                });
            } catch (\Throwable $e) {
                // Fallback: keep non-unique index if duplicates exist
            }
        }

        // 3) Ensure fiscal_year and fiscal_period are nullable/default
        if (Schema::hasColumn('journal_entries', 'fiscal_year')) {
            try {
                DB::statement('ALTER TABLE journal_entries MODIFY fiscal_year INT NULL DEFAULT NULL');
            } catch (\Throwable $e) {
                // ignore if DB engine or permissions prevent change
            }
        } else {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->integer('fiscal_year')->nullable()->default(null)->after('entry_date');
            });
        }

        if (Schema::hasColumn('journal_entries', 'fiscal_period')) {
            try {
                DB::statement('ALTER TABLE journal_entries MODIFY fiscal_period TINYINT NULL DEFAULT NULL');
            } catch (\Throwable $e) {
                // ignore
            }
        } else {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->tinyInteger('fiscal_period')->nullable()->default(null)->after('fiscal_year');
            });
        }

        // 4) Backfill fiscal_year and fiscal_period if null
        if (Schema::hasColumn('journal_entries', 'fiscal_year')) {
            DB::table('journal_entries')
                ->select('id', 'entry_date')
                ->whereNull('fiscal_year')
                ->orderBy('id')
                ->chunkById(500, function ($rows) {
                    foreach ($rows as $r) {
                        $year = Carbon::parse($r->entry_date)->year;
                        DB::table('journal_entries')->where('id', $r->id)->update(['fiscal_year' => $year]);
                    }
                });
        }
        if (Schema::hasColumn('journal_entries', 'fiscal_period')) {
            DB::table('journal_entries')
                ->select('id', 'entry_date')
                ->whereNull('fiscal_period')
                ->orderBy('id')
                ->chunkById(500, function ($rows) {
                    foreach ($rows as $r) {
                        $month = (int)Carbon::parse($r->entry_date)->format('m');
                        DB::table('journal_entries')->where('id', $r->id)->update(['fiscal_period' => $month]);
                    }
                });
        }
    }

    public function down(): void
    {
        // Reverse only what we added, conservatively
        if (Schema::hasColumn('journal_entries', 'journal_number')) {
            try {
                Schema::table('journal_entries', function (Blueprint $table) {
                    $table->dropUnique(['journal_number']);
                });
            } catch (\Throwable $e) {
                // ignore if not unique
            }
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->dropIndex(['journal_number']);
                $table->dropColumn('journal_number');
            });
        }
        // Do not drop fiscal_year/fiscal_period to avoid data loss
    }
};

