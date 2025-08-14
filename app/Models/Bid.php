<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'user_id',
        'bid_amount',
        'is_highest_snapshot'
    ];

    protected $casts = [
        'bid_amount' => 'integer',
        'is_highest_snapshot' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getFormattedBidAttribute()
    {
        return 'Rs ' . number_format($this->bid_amount / 100, 0);
    }
}
