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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->nullable()->constrained()->onDelete('set null');
            $table->string('payment_type')->default('commission'); // commission, bonus, penalty
            $table->decimal('amount_usd', 10, 2);
            $table->decimal('amount_inr', 10, 2)->nullable();
            $table->decimal('exchange_rate', 8, 4)->nullable();
            $table->integer('leads_count')->default(0);
            $table->string('period_start');
            $table->string('period_end');
            $table->string('status')->default('pending'); // pending, processed, paid, failed
            $table->string('payment_method')->nullable(); // bank_transfer, paypal, etc.
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
