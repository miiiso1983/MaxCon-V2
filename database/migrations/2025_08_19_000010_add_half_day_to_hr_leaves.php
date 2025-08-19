<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('hr_leaves', function (Blueprint $table) {
            $table->boolean('is_half_day')->default(false)->after('end_date');
            $table->enum('half_day_session', ['morning','afternoon'])->nullable()->after('is_half_day');
        });
        // Modify days_requested to decimal(5,2) without requiring doctrine/dbal
        DB::statement("ALTER TABLE hr_leaves MODIFY days_requested DECIMAL(5,2) NOT NULL");
    }

    public function down(): void
    {
        // Revert column type and drop new columns
        DB::statement("ALTER TABLE hr_leaves MODIFY days_requested INT NOT NULL");
        Schema::table('hr_leaves', function (Blueprint $table) {
            $table->dropColumn(['is_half_day','half_day_session']);
        });
    }
};

