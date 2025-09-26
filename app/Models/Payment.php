<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'auction_id',
        'seller_id',
        'amount',
        'commission_amount',
        'seller_payout_amount',
        'currency',
        'type',
        'status',
        'webxpay_order_id',
        'webxpay_reference',
        'webxpay_transaction_time',
        'seller_payout_status',
        'seller_payout_date',
        'customer_email_sent',
        'customer_email_sent_at',
        'gateway_ref',
        'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'webxpay_transaction_time' => 'datetime',
        'seller_payout_date' => 'datetime',
        'customer_email_sent_at' => 'datetime',
    ];
}
