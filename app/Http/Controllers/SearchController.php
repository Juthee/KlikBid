<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auction;
use App\Models\Category;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Start with base query for approved and active auctions
        $query = Auction::with(['category', 'user'])
            ->whereIn('status', ['approved', 'active'])
            ->where('end_at', '>', now()); // Only show active auctions

        // Search by keyword in title and description (case-insensitive)
        if ($request->filled('q')) {
            $searchTerm = strtolower($request->input('q')); // Convert to lowercase
            $query->where(function($q) use ($searchTerm) {
                $q->whereRaw('LOWER(title) like ?', ['%' . $searchTerm . '%'])
                ->orWhereRaw('LOWER(description) like ?', ['%' . $searchTerm . '%'])
                ->orWhereRaw('LOWER(district) like ?', ['%' . $searchTerm . '%']);
            });
        }

        // Filter by category (supports both main categories and subcategories)
        if ($request->filled('category')) {
            $categorySlug = $request->input('category');
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)
                ->orWhereHas('parent', function($parentQ) use ($categorySlug) {
                    $parentQ->where('slug', $categorySlug);
                });
            });
        }

        // Filter by location (district) - case-insensitive
        if ($request->filled('location')) {
            $location = strtolower($request->input('location'));
            $query->whereRaw('LOWER(district) like ?', ['%' . $location . '%']);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $minPrice = $request->input('min_price') * 100; // Convert to cents
            $query->where('base_price', '>=', $minPrice);
        }

        if ($request->filled('max_price')) {
            $maxPrice = $request->input('max_price') * 100; // Convert to cents
            $query->where('base_price', '<=', $maxPrice);
        }

        // Apply sorting
        $sort = $request->input('sort', 'ending_soon');
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'price_low':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('base_price', 'desc');
                break;
            case 'ending_soon':
            default:
                $query->orderBy('end_at', 'asc');
                break;
        }

        // Get the results with pagination
        $auctions = $query->paginate(12)->appends($request->query());

        // Get all categories for filter dropdown
        // Get only main categories (level 0) with their children
        $categories = Category::where('is_active', true)
            ->where('level', 0)  // Only main categories
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        // Calculate some search statistics
        $totalResults = $query->count();
        $searchStats = [
            'total' => $totalResults,
            'query' => $request->input('q'),
            'filters_applied' => $this->countAppliedFilters($request)
        ];

        return view('search.results', compact('auctions', 'categories', 'searchStats', 'request'));
    }

    /**
     * Count how many filters are currently applied
     */
    private function countAppliedFilters(Request $request)
    {
        $filters = ['q', 'category', 'location', 'min_price', 'max_price', 'sort'];
        $applied = 0;

        foreach ($filters as $filter) {
            if ($request->filled($filter) && $filter !== 'sort') {
                $applied++;
            }
        }

        return $applied;
    }

    /**
     * Get suggestions for autocomplete (future enhancement)
     */
    public function suggestions(Request $request)
    {
        if (!$request->filled('term')) {
            return response()->json([]);
        }

        $term = $request->input('term');

        $suggestions = Auction::where('status', 'approved')
            ->where(function($q) use ($term) {
                $q->where('title', 'like', '%' . $term . '%')
                  ->orWhere('description', 'like', '%' . $term . '%');
            })
            ->limit(5)
            ->pluck('title')
            ->toArray();

        return response()->json($suggestions);
    }
}
