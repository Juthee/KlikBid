<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Auction;
use App\Models\User;
use App\Models\Bid;

class OutbidNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $auction;
    public $bidder;
    public $yourBid;

    public function __construct(Auction $auction, User $bidder, Bid $yourBid)
    {
        $this->auction = $auction;
        $this->bidder = $bidder;
        $this->yourBid = $yourBid;
    }

    public function build()
    {
        return $this->view('emails.outbid')
                    ->subject('You\'ve Been Outbid - ' . $this->auction->title . ' - KlikBid')
                    ->with([
                        'auction' => $this->auction,
                        'bidder' => $this->bidder,
                        'yourBid' => $this->yourBid,
                    ]);
    }
}
