<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured auctions (active ones)
        $featuredAuctions = Auction::with(['category', 'user'])
            ->whereIn('status', ['active', 'scheduled'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Get main categories for display
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('welcome', compact('featuredAuctions', 'categories'));
    }
}
