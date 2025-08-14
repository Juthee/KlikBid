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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->longText('description');
            $table->unsignedBigInteger('base_price'); // in LKR cents
            $table->unsignedBigInteger('reserve_price')->nullable(); // in LKR cents
            $table->unsignedBigInteger('buy_now_price')->nullable(); // in LKR cents
            $table->unsignedBigInteger('deposit_amount'); // in LKR cents
            $table->string('address_line')->nullable();
            $table->string('district')->nullable();
            $table->string('province')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'scheduled', 'active', 'ended', 'unsold', 'reserve_not_met', 'won', 'defaulted', 'cancelled'])->default('draft');
            $table->datetime('start_at');
            $table->datetime('end_at');
            $table->foreignId('winner_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedBigInteger('winning_bid_amount')->nullable(); // in LKR cents
            $table->datetime('paid_at')->nullable();
            $table->datetime('defaulted_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'start_at', 'end_at']);
            $table->index('category_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
