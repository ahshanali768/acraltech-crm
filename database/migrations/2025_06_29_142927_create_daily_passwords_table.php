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
        Schema::create('daily_passwords', function (Blueprint $table) {
            $table->id();
            $table->string('password_code', 4);
            $table->date('password_date');
            $table->timestamp('generated_at');
            $table->timestamps();
            
            $table->unique('password_date'); // Only one password per day
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_passwords');
    }
};
