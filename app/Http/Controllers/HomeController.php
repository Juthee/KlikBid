<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Live Auctions (accepting bids, more than 24h left)
        $liveAuctions = Auction::with(['category', 'user'])
            ->whereIn('status', ['active', 'scheduled'])
            ->where('start_at', '<=', now())
            ->where('end_at', '>', now()->addDay()) // More than 24h left
            ->orderBy('end_at', 'asc')
            ->take(8)
            ->get();

        // Ending Soon (less than 24 hours remaining)
        $endingSoon = Auction::with(['category', 'user'])
            ->whereIn('status', ['active', 'scheduled'])
            ->where('start_at', '<=', now())
            ->where('end_at', '>', now())
            ->where('end_at', '<=', now()->addDay()) // Less than 24h left
            ->orderBy('end_at', 'asc')
            ->take(8)
            ->get();

        // Starting Soon (within next 24 hours)
        $upcomingAuctions = Auction::with(['category', 'user'])
            ->whereIn('status', ['active', 'scheduled'])
            ->where('start_at', '>', now())
            ->where('start_at', '<=', now()->addDay())
            ->orderBy('start_at', 'asc')
            ->take(8)
            ->get();

        // Get total counts for "View All" buttons
        $totalLiveCount = Auction::whereIn('status', ['active', 'scheduled'])
            ->where('start_at', '<=', now())
            ->where('end_at', '>', now()->addDay())
            ->count();

        $totalEndingSoonCount = Auction::whereIn('status', ['active', 'scheduled'])
            ->where('start_at', '<=', now())
            ->where('end_at', '>', now())
            ->where('end_at', '<=', now()->addDay())
            ->count();

        $totalUpcomingCount = Auction::whereIn('status', ['active', 'scheduled'])
            ->where('start_at', '>', now())
            ->where('start_at', '<=', now()->addDay())
            ->count();

        // Get main categories for display
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('welcome', compact('liveAuctions', 'endingSoon', 'upcomingAuctions', 'totalLiveCount', 'totalEndingSoonCount', 'totalUpcomingCount', 'categories'));
    }
}
