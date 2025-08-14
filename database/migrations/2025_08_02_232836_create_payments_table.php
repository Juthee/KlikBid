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
            $table->foreignId('auction_id')->nullable()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('amount'); // in LKR cents
            $table->char('currency', 3)->default('LKR');
            $table->enum('type', ['deposit', 'deposit_refund', 'auction_payment', 'refund_payout', 'fee', 'chargeback']);
            $table->enum('status', ['pending', 'authorized', 'captured', 'refunded', 'failed']);
            $table->string('gateway_ref')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['auction_id', 'type']);
            $table->index('status');
            $table->index('gateway_ref');
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
