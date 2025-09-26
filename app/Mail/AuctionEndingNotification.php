<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Auction;
use App\Models\User;
use App\Models\Bid;

class AuctionEndingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $auction;
    public $bidder;
    public $yourBid;
    public $timeRemaining;
    public $isWinning;

    public function __construct(Auction $auction, User $bidder, Bid $yourBid, $timeRemaining, $isWinning)
    {
        $this->auction = $auction;
        $this->bidder = $bidder;
        $this->yourBid = $yourBid;
        $this->timeRemaining = $timeRemaining;
        $this->isWinning = $isWinning;
    }

    public function build()
    {
        return $this->view('emails.auction-ending')
                    ->subject('Auction Ending Soon - ' . $this->auction->title . ' - KlikBid')
                    ->with([
                        'auction' => $this->auction,
                        'bidder' => $this->bidder,
                        'yourBid' => $this->yourBid,
                        'timeRemaining' => $this->timeRemaining,
                        'isWinning' => $this->isWinning,
                    ]);
    }
}
