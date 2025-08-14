<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BiddingController extends Controller
{
    public function joinAuction(Auction $auction)
    {
        // Show deposit payment page for joining auction
        $user = Auth::user();

        // Check if user already joined this auction
        $alreadyJoined = DB::table('auction_participants')
            ->where('auction_id', $auction->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyJoined) {
            return redirect()->route('auctions.show', $auction)
                ->with('info', 'You have already joined this auction and can place bids!');
        }

        // Check if auction is active/scheduled
        if (!in_array($auction->status, ['active', 'scheduled'])) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'This auction is not available for bidding.');
        }

        return view('bidding.join', compact('auction'));
    }

    public function processJoin(Request $request, Auction $auction)
    {
        // Process deposit payment and join auction
        $user = Auth::user();

        // Validate auction status
        if (!in_array($auction->status, ['active', 'scheduled'])) {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'This auction is not available for bidding.');
        }

        // Check if already joined
        $alreadyJoined = DB::table('auction_participants')
            ->where('auction_id', $auction->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyJoined) {
            return redirect()->route('auctions.show', $auction)
                ->with('info', 'You have already joined this auction!');
        }

        // For now, we'll simulate deposit payment success
        // In a real implementation, this would integrate with PayHere
        try {
            DB::beginTransaction();

            // Create payment record first (mock)
            $paymentId = DB::table('payments')->insertGetId([
                'user_id' => $user->id,
                'auction_id' => $auction->id,
                'amount' => $auction->deposit_amount,
                'currency' => 'LKR',
                'type' => 'deposit',
                'status' => 'captured',
                'gateway_ref' => 'MOCK_' . time(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create auction participant record
            DB::table('auction_participants')->insert([
                'auction_id' => $auction->id,
                'user_id' => $user->id,
                'deposit_amount' => $auction->deposit_amount,
                'payment_txn_id' => $paymentId, // Use the actual payment ID
                'status' => 'held',
                'joined_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('auctions.show', $auction)
                ->with('success', 'Successfully joined auction! You can now place bids. Deposit: Rs ' . number_format($auction->deposit_amount / 100, 0));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to join auction. Please try again.');
        }
    }

    public function placeBid(Request $request, Auction $auction)
    {
        $request->validate([
            'bid_amount' => 'required|numeric|min:1'
        ]);

        $user = Auth::user();
        $bidAmountInCents = $request->bid_amount * 100;

        // Check if user has joined the auction
        $hasJoined = DB::table('auction_participants')
            ->where('auction_id', $auction->id)
            ->where('user_id', $user->id)
            ->where('status', 'held')
            ->exists();

        if (!$hasJoined) {
            return redirect()->route('bidding.join', $auction)
                ->with('error', 'You must join the auction first by paying the participation deposit.');
        }

        // Check auction status and timing
        if ($auction->status !== 'active') {
            return redirect()->back()
                ->with('error', 'This auction is not currently active for bidding.');
        }

        if (now() < $auction->start_at || now() > $auction->end_at) {
            return redirect()->back()
                ->with('error', 'Bidding is not allowed at this time.');
        }

        // Get current highest bid
        $currentHighestBid = $this->getCurrentHighestBid($auction);
        $currentBidAmount = $currentHighestBid ? $currentHighestBid->bid_amount : $auction->base_price;

        // Calculate minimum next bid (1% increment rule)
        $minIncrement = max(ceil($currentBidAmount * 0.01), 100); // At least Rs 1
        $minNextBid = $currentBidAmount + $minIncrement;

        // Validate bid amount
        if ($bidAmountInCents < $minNextBid) {
            return redirect()->back()
                ->with('error', 'Your bid must be at least Rs ' . number_format($minNextBid / 100, 0) .
                      ' (minimum ' . number_format($minIncrement / 100, 0) . ' increase)');
        }

        // Check if user is trying to outbid themselves
        if ($currentHighestBid && $currentHighestBid->user_id === $user->id) {
            return redirect()->back()
                ->with('error', 'You already have the highest bid on this auction.');
        }

        try {
            DB::beginTransaction();

            // Mark previous highest bid as no longer highest
            if ($currentHighestBid) {
                $currentHighestBid->update(['is_highest_snapshot' => false]);
            }

            // Create new bid
            $bid = Bid::create([
                'auction_id' => $auction->id,
                'user_id' => $user->id,
                'bid_amount' => $bidAmountInCents,
                'is_highest_snapshot' => true,
            ]);

            DB::commit();

            return redirect()->route('auctions.show', $auction)
                ->with('success', 'Bid placed successfully! Your bid: Rs ' . number_format($bidAmountInCents / 100, 0));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Failed to place bid. Please try again.');
        }
    }

    public function bidHistory(Auction $auction)
    {
        // Show bid history for an auction
        $bids = Bid::with('user')
            ->where('auction_id', $auction->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('bidding.history', compact('auction', 'bids'));
    }

    private function getCurrentHighestBid(Auction $auction)
    {
        return Bid::where('auction_id', $auction->id)
            ->where('is_highest_snapshot', true)
            ->first();
    }

    public function getMinimumBid(Auction $auction)
    {
        // API endpoint to get minimum bid amount (for AJAX)
        $currentHighestBid = $this->getCurrentHighestBid($auction);
        $currentBidAmount = $currentHighestBid ? $currentHighestBid->bid_amount : $auction->base_price;

        $minIncrement = max(ceil($currentBidAmount * 0.01), 100);
        $minNextBid = $currentBidAmount + $minIncrement;

        return response()->json([
            'current_bid' => $currentBidAmount,
            'min_increment' => $minIncrement,
            'min_next_bid' => $minNextBid,
            'formatted_current' => 'Rs ' . number_format($currentBidAmount / 100, 0),
            'formatted_min_next' => 'Rs ' . number_format($minNextBid / 100, 0),
        ]);
    }
}
