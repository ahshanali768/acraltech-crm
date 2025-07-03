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
        if (!Schema::hasTable('agent_breaks')) {
            Schema::create('agent_breaks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->date('date');
                $table->dateTime('start_time');
                $table->dateTime('end_time')->nullable();
                $table->integer('duration_minutes')->nullable();
                $table->string('break_type')->default('other'); // lunch, coffee, personal, other
                $table->text('reason')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('agent_breaks', function (Blueprint $table) {
                if (!Schema::hasColumn('agent_breaks', 'break_type')) {
                    $table->string('break_type')->default('other')->after('duration_minutes');
                }
                
                if (!Schema::hasColumn('agent_breaks', 'reason')) {
                    $table->text('reason')->nullable()->after('break_type');
                }
                
                // Ensure date field is properly typed
                if (Schema::hasColumn('agent_breaks', 'date')) {
                    $table->date('date')->change();
                } else {
                    $table->date('date')->after('user_id');
                }
                
                // Ensure datetime fields are properly typed
                if (Schema::hasColumn('agent_breaks', 'start_time')) {
                    $table->dateTime('start_time')->change();
                } else {
                    $table->dateTime('start_time')->after('date');
                }
                
                if (Schema::hasColumn('agent_breaks', 'end_time')) {
                    $table->dateTime('end_time')->nullable()->change();
                } else {
                    $table->dateTime('end_time')->nullable()->after('start_time');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop columns or the table in the down migration
        // as it might contain important break data
        Schema::table('agent_breaks', function (Blueprint $table) {
            // Only remove columns added in this migration if needed
            if (Schema::hasColumn('agent_breaks', 'break_type')) {
                $table->dropColumn('break_type');
            }
            
            if (Schema::hasColumn('agent_breaks', 'reason')) {
                $table->dropColumn('reason');
            }
        });
    }
};
