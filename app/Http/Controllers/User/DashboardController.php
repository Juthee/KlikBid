<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Dashboard overview statistics
        $stats = [
            'active_bids' => $this->getActiveBidsCount($user->id),
            'total_listings' => Auction::where('user_id', $user->id)->count(),
            'won_auctions' => $this->getWonAuctionsCount($user->id),
            'total_bids_placed' => Bid::where('user_id', $user->id)->count(),
        ];

        // Recent activity
        $recentBids = Bid::with(['auction.category'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentListings = Auction::with('category')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('user.dashboard', compact('stats', 'recentBids', 'recentListings'));
    }

    public function myListings()
    {
        $user = Auth::user();

        $listings = Auction::with(['category', 'bids'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.my-listings', compact('listings'));
    }

    public function myBids()
    {
        $user = Auth::user();

        $bids = Bid::with(['auction.category', 'auction.user'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Group bids by auction to show status
        $bidsByAuction = $bids->groupBy('auction_id');

        return view('user.my-bids', compact('bids', 'bidsByAuction'));
    }

    public function wonAuctions()
    {
        $user = Auth::user();

        $wonAuctions = Auction::with(['category', 'bids'])
            ->where('winner_user_id', $user->id)
            ->whereIn('status', ['won', 'ended'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('user.won-auctions', compact('wonAuctions'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email']));

        // Store additional profile info if needed
        // For now, we'll just update the basic user info

        return redirect()->route('user.profile')
            ->with('success', 'Profile updated successfully!');
    }

    private function getActiveBidsCount($userId)
    {
        // Count auctions where user has bid and auction is still active
        return Bid::whereHas('auction', function($query) {
                $query->where('status', 'active');
            })
            ->where('user_id', $userId)
            ->distinct('auction_id')
            ->count();
    }

    private function getWonAuctionsCount($userId)
    {
        return Auction::where('winner_user_id', $userId)
            ->whereIn('status', ['won', 'ended'])
            ->count();
    }

    public function auctionDetail(Auction $auction)
    {
        // Check if user owns this auction
        if ($auction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to auction.');
        }

        $auction->load(['category', 'bids.user']);

        // Get auction statistics
        $stats = [
            'total_bids' => $auction->bids->count(),
            'unique_bidders' => $auction->bids->pluck('user_id')->unique()->count(),
            'highest_bid' => $auction->bids->max('bid_amount'),
            'participants' => DB::table('auction_participants')->where('auction_id', $auction->id)->count(),
        ];

        return view('user.auction-detail', compact('auction', 'stats'));
    }
}
