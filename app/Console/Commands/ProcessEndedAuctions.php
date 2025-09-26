<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Auction;
use App\Models\Bid;
use App\Services\EmailNotificationService;
use Carbon\Carbon;

class ProcessEndedAuctions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'auctions:process-ended';

    /**
     * The console command description.
     */
    protected $description = 'Process ended auctions, determine winners, and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing ended auctions...');

        // Find auctions that have ended but haven't been processed
        $endedAuctions = Auction::where('status', 'active')
            ->where('end_at', '<=', now())
            ->get();

        $processedCount = 0;
        $emailService = new EmailNotificationService();

        foreach ($endedAuctions as $auction) {
            $this->info("Processing auction: {$auction->title}");

            // Get the highest bid for this auction
            $winningBid = Bid::where('auction_id', $auction->id)
                ->where('is_highest_snapshot', true)
                ->first();

            if ($winningBid) {
                // Auction has a winner
                $winner = $winningBid->user;

                // Update auction status and winner info
                $auction->update([
                    'status' => 'won',
                    'winner_user_id' => $winner->id,
                    'winning_bid_amount' => $winningBid->bid_amount
                ]);

                // Send winner notification email
                $emailService->sendAuctionWonNotification($auction, $winner);

                $this->info("Winner notification sent to: {$winner->email}");

                // Optionally, send notification to seller that their item sold
                $seller = $auction->user;
                // You can create a separate "Item Sold" notification for sellers here

                $processedCount++;
            } else {
                // No bids - auction ends without winner
                $auction->update([
                    'status' => 'ended'
                ]);

                $this->info("Auction ended with no bids: {$auction->title}");
                $processedCount++;
            }
        }

        // Also check for auctions ending soon (within 1 hour) to send ending notifications
        $endingSoonAuctions = Auction::where('status', 'active')
            ->where('end_at', '>', now())
            ->where('end_at', '<=', now()->addHour())
            ->get();

        foreach ($endingSoonAuctions as $auction) {
            // Get all bidders for this auction
            $bidders = $auction->bids()
                ->with('user')
                ->select('user_id')
                ->distinct()
                ->get();

            foreach ($bidders as $bidderRecord) {
                $bidder = $bidderRecord->user;

                // Get this bidder's latest bid
                $latestBid = $auction->bids()
                    ->where('user_id', $bidder->id)
                    ->latest()
                    ->first();

                // Calculate time remaining
                $timeRemaining = now()->diffInMinutes($auction->end_at);

                // Check if this bidder is currently winning
                $isWinning = $auction->bids()
                    ->where('is_highest_snapshot', true)
                    ->where('user_id', $bidder->id)
                    ->exists();

                // Send ending notification (only if not already sent recently)
                // You might want to add a flag to prevent spam
                $emailService->sendAuctionEndingNotification(
                    $auction,
                    $bidder,
                    $latestBid,
                    $timeRemaining . ' minutes',
                    $isWinning
                );

                $this->info("Ending notification sent to: {$bidder->email}");
            }
        }

        $this->info("Processed {$processedCount} ended auctions.");
        return Command::SUCCESS;
    }
}
