<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'title','images', 'description', 'base_price',
        'reserve_price', 'buy_now_price', 'deposit_amount', 'address_line',
        'district', 'province', 'latitude', 'longitude', 'status',
        'start_at', 'end_at', 'winner_user_id', 'winning_bid_amount',
        'paid_at', 'defaulted_at'
    ];

    protected $casts = [
        'images' => 'array',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'paid_at' => 'datetime',
        'defaulted_at' => 'datetime',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    public function bids()
    {
        return $this->hasMany(\App\Models\Bid::class);
    }
}
