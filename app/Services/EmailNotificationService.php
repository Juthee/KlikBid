<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\OutbidNotification;
use App\Mail\AuctionEndingNotification;
use App\Mail\AuctionWonNotification;
use App\Mail\AuctionApprovedNotification;
use App\Models\Auction;
use App\Models\User;
use App\Models\Bid;

class EmailNotificationService
{
    /**
     * Send outbid notification to a user
     */
    public function sendOutbidNotification(Auction $auction, User $bidder, Bid $previousBid)
    {
        try {
            Mail::to($bidder->email)->send(
                new OutbidNotification($auction, $bidder, $previousBid)
            );

            \Log::info("Outbid email sent to {$bidder->email} for auction {$auction->id}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send outbid email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send auction ending notification
     */
    public function sendAuctionEndingNotification(Auction $auction, User $bidder, Bid $userBid, $timeRemaining, $isWinning)
    {
        try {
            Mail::to($bidder->email)->send(
                new AuctionEndingNotification($auction, $bidder, $userBid, $timeRemaining, $isWinning)
            );

            \Log::info("Auction ending email sent to {$bidder->email} for auction {$auction->id}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send auction ending email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send auction won notification
     */
    public function sendAuctionWonNotification(Auction $auction, User $winner)
    {
        try {
            Mail::to($winner->email)->send(
                new AuctionWonNotification($auction, $winner)
            );

            \Log::info("Auction won email sent to {$winner->email} for auction {$auction->id}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send auction won email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send auction approved notification to seller
     */
    public function sendAuctionApprovedNotification(Auction $auction, User $seller)
    {
        try {
            Mail::to($seller->email)->send(
                new AuctionApprovedNotification($auction, $seller)
            );

            \Log::info("Auction approved email sent to {$seller->email} for auction {$auction->id}");
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send auction approved email: " . $e->getMessage());
            return false;
        }
    }
}
