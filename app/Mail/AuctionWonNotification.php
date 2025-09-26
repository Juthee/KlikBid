<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Auction;
use App\Models\User;

class AuctionWonNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $auction;
    public $winner;

    public function __construct(Auction $auction, User $winner)
    {
        $this->auction = $auction;
        $this->winner = $winner;
    }

    public function build()
    {
        return $this->view('emails.auction-won')
                    ->subject('🏆 Congratulations! You Won - ' . $this->auction->title . ' - KlikBid')
                    ->with([
                        'auction' => $this->auction,
                        'winner' => $this->winner,
                    ]);
    }
}
