<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Auction;
use App\Models\User;

class AuctionApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $auction;
    public $seller;

    public function __construct(Auction $auction, User $seller)
    {
        $this->auction = $auction;
        $this->seller = $seller;
    }

    public function build()
    {
        return $this->view('emails.auction-approved')
                    ->subject('✅ Auction Approved - ' . $this->auction->title . ' - KlikBid')
                    ->with([
                        'auction' => $this->auction,
                        'seller' => $this->seller,
                    ]);
    }
}
