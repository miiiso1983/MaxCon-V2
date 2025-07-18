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
        Schema::table('customers', function (Blueprint $table) {
            // Authentication fields
            $table->string('password')->nullable()->after('email');
            $table->timestamp('email_verified_at')->nullable()->after('password');
            $table->rememberToken()->after('email_verified_at');
            
            // Login tracking
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            
            // Financial fields
            $table->decimal('previous_debt', 15, 2)->default(0)->after('current_balance');
            
            // Soft deletes
            $table->softDeletes()->after('notes');
            
            // Indexes for performance
            $table->index(['email', 'tenant_id']);
            $table->index(['is_active', 'tenant_id']);
            $table->index(['customer_code', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'password',
                'email_verified_at',
                'remember_token',
                'last_login_at',
                'last_login_ip',
                'previous_debt',
                'deleted_at'
            ]);
            
            $table->dropIndex(['email', 'tenant_id']);
            $table->dropIndex(['is_active', 'tenant_id']);
            $table->dropIndex(['customer_code', 'tenant_id']);
        });
    }
};
