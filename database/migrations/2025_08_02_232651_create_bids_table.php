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
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('bid_amount'); // in LKR cents
            $table->boolean('is_highest_snapshot')->default(false);
            $table->timestamps();

            $table->index(['auction_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('is_highest_snapshot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
