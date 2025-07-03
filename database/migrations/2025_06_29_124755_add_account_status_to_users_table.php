<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the new consolidated account_status field
            $table->enum('account_status', ['pending', 'active', 'revoked'])->default('pending')->after('status');
        });
        
        // Migrate existing data
        DB::statement("
            UPDATE users 
            SET account_status = CASE 
                WHEN approval_status = 'pending' OR approval_status IS NULL THEN 'pending'
                WHEN approval_status = 'approved' AND status = 'active' THEN 'active'
                WHEN approval_status = 'approved' AND status = 'revoked' THEN 'revoked'
                WHEN approval_status = 'rejected' THEN 'revoked'
                ELSE 'pending'
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('account_status');
        });
    }
};
