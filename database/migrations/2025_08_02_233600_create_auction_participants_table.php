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
        Schema::create('auction_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('deposit_amount'); // in LKR cents
            $table->foreignId('payment_txn_id')->constrained('payments')->onDelete('cascade');
            $table->enum('status', ['held', 'refunded', 'forfeited', 'applied']);
            $table->datetime('joined_at');
            $table->datetime('refunded_at')->nullable();
            $table->datetime('forfeited_at')->nullable();
            $table->timestamps();

            $table->unique(['auction_id', 'user_id']); // One participation per auction per user
            $table->index('status');
            $table->index('joined_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_participants');
    }
};
