<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AuctionController extends Controller
{
    public function index()
    {
        // Show all auctions (we'll build this later)
        $auctions = Auction::with(['category', 'user'])
            ->where('status', '!=', 'draft')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('auctions.index', compact('auctions'));
    }

    public function create()
    {
        // Show the create auction form
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        return view('auctions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validate and create new auction
        $request->validate([
            'title' => 'required|min:5|max:150',
            'description' => 'required|min:20|max:10000',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:1',
            'reserve_price' => 'nullable|numeric|min:1',
            'buy_now_price' => 'nullable|numeric|min:1',
            'start_at' => 'required|date|after:now',
            'end_at' => 'required|date|after:start_at',
            'address_line' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            // Add image validation
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120' // Max 5MB per image
        ]);

        // Handle image uploads
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Store in public/storage/auction_images
                $path = $image->storeAs('auction_images', $filename, 'public');
                $imagePaths[] = $path;
            }
        }

        // Calculate deposit amount based on base price
        $basePrice = $request->base_price * 100; // Convert to cents
        $depositAmount = $this->calculateDepositAmount($basePrice);

        $auction = Auction::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'images' => $imagePaths, // Add images array
            'base_price' => $basePrice,
            'reserve_price' => $request->reserve_price ? $request->reserve_price * 100 : null,
            'buy_now_price' => $request->buy_now_price ? $request->buy_now_price * 100 : null,
            'deposit_amount' => $depositAmount,
            'address_line' => $request->address_line,
            'district' => $request->district,
            'province' => $request->province,
            'status' => 'pending_approval',
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
        ]);

        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Auction created successfully and is pending approval!');
    }

    public function show(Auction $auction)
    {
        // Show individual auction page
        $auction->load(['category', 'user', 'bids.user']);
        return view('auctions.show', compact('auction'));
    }

    public function edit(Auction $auction)
    {
        // Check if user owns this auction
        if ($auction->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own auctions.');
        }

        // Check if auction can be edited (only pending_approval auctions)
        if ($auction->status !== 'pending_approval') {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You can only edit auctions that are pending approval.');
        }

        // Get categories for the dropdown
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        return view('auctions.edit', compact('auction', 'categories'));
    }

    public function update(Request $request, Auction $auction)
    {
        // Check if user owns this auction
        if ($auction->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own auctions.');
        }

        // Check if auction can be edited
        if ($auction->status !== 'pending_approval') {
            return redirect()->route('auctions.show', $auction)
                ->with('error', 'You can only edit auctions that are pending approval.');
        }

        // Validate the request
        $request->validate([
            'title' => 'required|min:5|max:150',
            'description' => 'required|min:20|max:10000',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:1',
            'reserve_price' => 'nullable|numeric|min:1',
            'buy_now_price' => 'nullable|numeric|min:1',
            'start_at' => 'required|date|after:now',
            'end_at' => 'required|date|after:start_at',
            'address_line' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            // Images validation - allow empty array if keeping existing images
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string'
        ]);

        // Handle images
        $imagePaths = [];

        // Keep existing images that weren't removed
        if ($request->has('existing_images')) {
            $imagePaths = $request->existing_images;
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('auction_images', $filename, 'public');
                $imagePaths[] = $path;
            }
        }

        // Ensure at least one image
        if (empty($imagePaths)) {
            return back()->withErrors(['images' => 'At least one image is required.']);
        }

        // Calculate deposit amount based on new base price
        $basePrice = $request->base_price * 100;
        $depositAmount = $this->calculateDepositAmount($basePrice);

        // Update the auction
        $auction->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'images' => $imagePaths,
            'base_price' => $basePrice,
            'reserve_price' => $request->reserve_price ? $request->reserve_price * 100 : null,
            'buy_now_price' => $request->buy_now_price ? $request->buy_now_price * 100 : null,
            'deposit_amount' => $depositAmount,
            'address_line' => $request->address_line,
            'district' => $request->district,
            'province' => $request->province,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
        ]);

        return redirect()->route('auctions.show', $auction)
            ->with('success', 'Auction updated successfully! Your changes will be reviewed.');
    }

    private function calculateDepositAmount($basePriceInCents)
    {
        $basePriceInRupees = $basePriceInCents / 100;

        if ($basePriceInRupees > 100000) {
            return 500000; // Rs 5,000 in cents
        } elseif ($basePriceInRupees > 50000) {
            return 100000; // Rs 1,000 in cents
        } elseif ($basePriceInRupees > 10000) {
            return 50000;  // Rs 500 in cents
        } elseif ($basePriceInRupees > 1000) {
            return 10000;  // Rs 100 in cents
        } elseif ($basePriceInRupees > 100) {
            return 5000;   // Rs 50 in cents
        } else {
            return 0;      // No deposit
        }
    }

    public function getCurrentBid(Auction $auction)
    {
        $currentBid = $auction->bids()->with('user')->where('is_highest_snapshot', true)->first();
        $currentAmount = $currentBid ? $currentBid->bid_amount : $auction->base_price;
        $minIncrement = max(ceil($currentAmount * 0.01), 10000);
        $minNextBid = $currentAmount + $minIncrement;

        return response()->json([
            'current_bid' => $currentAmount / 100,
            'next_minimum' => $minNextBid / 100,
            'current_winner' => $currentBid ? $currentBid->user->name : null,
            'bid_count' => $auction->bids->count(),
            'participants' => DB::table('auction_participants')->where('auction_id', $auction->id)->count(),
            'timestamp' => now()->timestamp
        ]);
    }
}
