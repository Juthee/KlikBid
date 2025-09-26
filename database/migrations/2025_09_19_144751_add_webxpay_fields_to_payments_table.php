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
        Schema::table('payments', function (Blueprint $table) {
            // WebXPay Integration Fields
            $table->string('webxpay_order_id')->nullable()->after('gateway_ref');
            $table->string('webxpay_reference')->nullable()->after('webxpay_order_id');
            $table->timestamp('webxpay_transaction_time')->nullable()->after('webxpay_reference');

            // Seller Payout Tracking
            $table->foreignId('seller_id')->nullable()->constrained('users')->after('user_id');
            $table->unsignedBigInteger('commission_amount')->default(0)->after('amount'); // Platform fee
            $table->unsignedBigInteger('seller_payout_amount')->default(0)->after('commission_amount');
            $table->enum('seller_payout_status', ['pending', 'processing', 'completed', 'failed'])->default('pending')->after('seller_payout_amount');
            $table->timestamp('seller_payout_date')->nullable()->after('seller_payout_status');

            // Email Tracking
            $table->boolean('customer_email_sent')->default(false)->after('seller_payout_date');
            $table->timestamp('customer_email_sent_at')->nullable()->after('customer_email_sent');

            // Additional Indexes for Performance
            $table->index('webxpay_order_id');
            $table->index('seller_id');
            $table->index('seller_payout_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['webxpay_order_id']);
            $table->dropIndex(['seller_id']);
            $table->dropIndex(['seller_payout_status']);

            $table->dropColumn([
                'webxpay_order_id',
                'webxpay_reference',
                'webxpay_transaction_time',
                'seller_id',
                'commission_amount',
                'seller_payout_amount',
                'seller_payout_status',
                'seller_payout_date',
                'customer_email_sent',
                'customer_email_sent_at'
            ]);
        });
    }
};
