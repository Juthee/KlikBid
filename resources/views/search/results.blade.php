<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - KlikBid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .auction-card:hover {
            transform: translateY(-4px);
            transition: transform 0.3s ease;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header with Search -->
    <header class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <h1 class="text-3xl font-bold text-blue-600">KlikBid</h1>
                        <span class="ml-2 text-sm text-gray-500">Sri Lanka</span>
                    </a>
                </div>

                <!-- Enhanced Search Bar -->
                <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                    <form class="w-full flex" method="GET" action="{{ route('search') }}">
                        <div class="flex-1 relative">
                            <input type="text" name="q" placeholder="Search auctions, items, locations..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   value="{{ request('q') }}">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                🔍
                            </div>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-r-lg hover:bg-blue-700 font-semibold">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-6 items-center">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600">Home</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Browse</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Help</a>
                    @guest
                        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Login</a>
                        <a href="{{ route('register') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Register</a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</button>
                        </form>
                    @endguest
                </nav>
            </div>
        </div>
    </header>

    <!-- Advanced Filter Bar -->
    <div class="bg-gray-100 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4 flex flex-wrap gap-4 items-center">
                <form method="GET" action="{{ route('search') }}" class="flex flex-wrap gap-4 items-center w-full">
                    <!-- Keep search query -->
                    <input type="hidden" name="q" value="{{ request('q') }}">

                    <!-- Category Filter -->
                    <select name="category" class="bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <!-- Main Category -->
                                <option value="{{ $category->slug ?? $category->id }}" {{ request('category') == ($category->slug ?? $category->id) ? 'selected' : '' }}>
                                    {{ $category->icon ?? '📦' }} {{ $category->name }}
                                </option>
                                <!-- Subcategories -->
                                @if($category->children)
                                    @foreach($category->children()->orderBy('sort_order')->get() as $subcategory)
                                        <option value="{{ $subcategory->slug ?? $subcategory->id }}" {{ request('category') == ($subcategory->slug ?? $subcategory->id) ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;└─ {{ $subcategory->icon ?? '📦' }} {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </select>

                    <!-- Location Filter -->
                    <select name="location" class="bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">All Locations</option>

                        <!-- Western Province -->
                        <optgroup label="🏙️ Western Province">
                            <option value="colombo" {{ request('location') == 'colombo' ? 'selected' : '' }}>Colombo</option>
                            <option value="gampaha" {{ request('location') == 'gampaha' ? 'selected' : '' }}>Gampaha</option>
                            <option value="kalutara" {{ request('location') == 'kalutara' ? 'selected' : '' }}>Kalutara</option>
                        </optgroup>

                        <!-- Central Province -->
                        <optgroup label="🏔️ Central Province">
                            <option value="kandy" {{ request('location') == 'kandy' ? 'selected' : '' }}>Kandy</option>
                            <option value="matale" {{ request('location') == 'matale' ? 'selected' : '' }}>Matale</option>
                            <option value="nuwara-eliya" {{ request('location') == 'nuwara-eliya' ? 'selected' : '' }}>Nuwara Eliya</option>
                        </optgroup>

                        <!-- Southern Province -->
                        <optgroup label="🏖️ Southern Province">
                            <option value="galle" {{ request('location') == 'galle' ? 'selected' : '' }}>Galle</option>
                            <option value="matara" {{ request('location') == 'matara' ? 'selected' : '' }}>Matara</option>
                            <option value="hambantota" {{ request('location') == 'hambantota' ? 'selected' : '' }}>Hambantota</option>
                        </optgroup>

                        <!-- North Western Province -->
                        <optgroup label="🌾 North Western Province">
                            <option value="kurunegala" {{ request('location') == 'kurunegala' ? 'selected' : '' }}>Kurunegala</option>
                            <option value="puttalam" {{ request('location') == 'puttalam' ? 'selected' : '' }}>Puttalam</option>
                        </optgroup>

                        <!-- North Central Province -->
                        <optgroup label="⛲ North Central Province">
                            <option value="anuradhapura" {{ request('location') == 'anuradhapura' ? 'selected' : '' }}>Anuradhapura</option>
                            <option value="polonnaruwa" {{ request('location') == 'polonnaruwa' ? 'selected' : '' }}>Polonnaruwa</option>
                        </optgroup>

                        <!-- Uva Province -->
                        <optgroup label="⛰️ Uva Province">
                            <option value="badulla" {{ request('location') == 'badulla' ? 'selected' : '' }}>Badulla</option>
                            <option value="monaragala" {{ request('location') == 'monaragala' ? 'selected' : '' }}>Monaragala</option>
                        </optgroup>

                        <!-- Sabaragamuwa Province -->
                        <optgroup label="💎 Sabaragamuwa Province">
                            <option value="ratnapura" {{ request('location') == 'ratnapura' ? 'selected' : '' }}>Ratnapura</option>
                            <option value="kegalle" {{ request('location') == 'kegalle' ? 'selected' : '' }}>Kegalle</option>
                        </optgroup>

                        <!-- Eastern Province -->
                        <optgroup label="🌊 Eastern Province">
                            <option value="ampara" {{ request('location') == 'ampara' ? 'selected' : '' }}>Ampara</option>
                            <option value="batticaloa" {{ request('location') == 'batticaloa' ? 'selected' : '' }}>Batticaloa</option>
                            <option value="trincomalee" {{ request('location') == 'trincomalee' ? 'selected' : '' }}>Trincomalee</option>
                        </optgroup>

                        <!-- Northern Province -->
                        <optgroup label="🏛️ Northern Province">
                            <option value="jaffna" {{ request('location') == 'jaffna' ? 'selected' : '' }}>Jaffna</option>
                            <option value="kilinochchi" {{ request('location') == 'kilinochchi' ? 'selected' : '' }}>Kilinochchi</option>
                            <option value="mannar" {{ request('location') == 'mannar' ? 'selected' : '' }}>Mannar</option>
                            <option value="mullaitivu" {{ request('location') == 'mullaitivu' ? 'selected' : '' }}>Mullaitivu</option>
                            <option value="vavuniya" {{ request('location') == 'vavuniya' ? 'selected' : '' }}>Vavuniya</option>
                        </optgroup>
                    </select>

                    <!-- Price Range -->
                    <div class="flex items-center gap-2">
                        <input type="number" name="min_price" placeholder="Min Rs"
                               class="w-28 bg-white border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                               value="{{ request('min_price') }}">
                        <span class="text-gray-500">-</span>
                        <input type="number" name="max_price" placeholder="Max Rs"
                               class="w-28 bg-white border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                               value="{{ request('max_price') }}">
                    </div>

                    <!-- Sort Options -->
                    <select name="sort" class="bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>⏰ Ending Soon</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>🆕 Newest First</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>💰 Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>💸 Price: High to Low</option>
                    </select>

                    <!-- Apply Filters Button -->
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold">
                        Apply Filters
                    </button>

                    <!-- Clear All Link -->
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-800 text-sm underline">Clear All</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Results Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    @if(isset($searchStats['query']) && $searchStats['query'])
                        Search Results for "{{ $searchStats['query'] }}"
                    @else
                        Browse Auctions
                    @endif
                </h2>
                <p class="text-gray-600 mt-1">
                    Found {{ $searchStats['total'] ?? $auctions->total() ?? $auctions->count() }} auctions
                    @if(isset($searchStats['filters_applied']) && $searchStats['filters_applied'] > 0)
                        with {{ $searchStats['filters_applied'] }} filter(s) applied
                    @endif
                </p>
            </div>
        </div>

        <!-- Results -->
        @if($auctions->count() > 0)
            <!-- Auction Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($auctions as $auction)
                    <div class="bg-white rounded-lg shadow-md auction-card overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full
                        @if(request('sort') == 'ending_soon') border-2 border-red-200
                        @else border-2 border-green-200
                        @endif">

                        <!-- Image Section -->
                        <div class="h-48 bg-gray-200 flex items-center justify-center relative overflow-hidden">
                            @if($auction->images && count($auction->images) > 0)
                                <img src="{{ asset('storage/' . $auction->images[0]) }}"
                                     alt="{{ $auction->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-gray-500">🏠 {{ $auction->category->name ?? 'Category' }}</span>
                            @endif

                            <!-- Status Indicator -->
                            <div class="absolute top-2 right-2 px-2 py-1 rounded-full text-xs font-bold
                                @if(request('sort') == 'ending_soon') bg-red-500 text-white animate-bounce
                                @else bg-green-500 text-white animate-pulse
                                @endif">
                                @if(request('sort') == 'ending_soon')
                                    URGENT
                                @else
                                    LIVE
                                @endif
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="p-4 flex flex-col flex-grow">
                            <h4 class="font-semibold mb-2">{{ $auction->title }}</h4>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($auction->description, 50) }}</p>

                            <!-- Additional auction details -->
                            <div class="space-y-1 mb-3 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Deposit:</span>
                                    <span class="font-medium text-purple-600">
                                        @if($auction->deposit_amount > 0)
                                            Rs {{ number_format($auction->deposit_amount / 100, 0) }}
                                        @else
                                            Free
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Total Bids:</span>
                                    <span class="font-medium">{{ $auction->bids->count() ?? 0 }}</span>
                                </div>
                            </div>

                            <!-- Pricing and Time Info -->
                            <div class="flex justify-between items-end mb-4 mt-auto">
                                <div>
                                    <p class="text-xs text-gray-500">Current Bid</p>
                                    <p class="font-bold text-lg
                                        @if(request('sort') == 'ending_soon') text-red-600
                                        @else text-green-600
                                        @endif">
                                        Rs {{ number_format($auction->base_price / 100, 0) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Ends in</p>
                                    <p class="font-semibold text-red-600">
                                        @php
                                            $now = now();
                                            $endTime = $auction->end_at;

                                            if ($now >= $endTime) {
                                                $timeDisplay = 'Ended';
                                            } else {
                                                $totalMinutes = $now->diffInMinutes($endTime);
                                                $days = intval($totalMinutes / (24 * 60));
                                                $hours = intval(($totalMinutes % (24 * 60)) / 60);
                                                $minutes = $totalMinutes % 60;

                                                if ($days > 0) {
                                                    $timeDisplay = $days . 'd ' . $hours . 'h';
                                                } elseif ($hours > 0) {
                                                    $timeDisplay = $hours . 'h ' . $minutes . 'm';
                                                } else {
                                                    $timeDisplay = $minutes . 'm';
                                                }
                                            }
                                        @endphp
                                        {{ $timeDisplay }}
                                    </p>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="mt-auto">
                                <a href="{{ route('auctions.show', $auction) }}"
                                   class="block w-full text-white text-center py-2 rounded-lg font-semibold transition-colors
                                    @if(request('sort') == 'ending_soon') bg-red-600 hover:bg-red-700
                                    @else bg-green-600 hover:bg-green-700
                                    @endif">
                                    @if(request('sort') == 'ending_soon')
                                        ⚡ BID URGENTLY
                                    @else
                                        🎯 BID NOW
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if(method_exists($auctions, 'links'))
                <div class="mt-12">
                    {{ $auctions->links() }}
                </div>
            @endif
        @else
            <!-- No Results -->
            <div class="text-center py-16">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No auctions found</h3>
                <p class="text-gray-600 mb-6">
                    @if(isset($searchStats['query']) && $searchStats['query'])
                        No results found for "{{ $searchStats['query'] }}". Try adjusting your search or filters.
                    @else
                        No auctions match your current filter criteria. Try adjusting your search or filters.
                    @endif
                </p>
                <a href="{{ url('/') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    Browse All Auctions
                </a>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h6 class="font-semibold mb-4">KlikBid</h6>
                    <p class="text-gray-400 text-sm">Sri Lanka's premier online auction platform. Buy and sell with confidence.</p>
                </div>
                <div>
                    <h6 class="font-semibold mb-4">Quick Links</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ url('/') }}" class="hover:text-white">Home</a></li>
                        <li><a href="{{ route('search') }}" class="hover:text-white">Browse Auctions</a></li>
                        <li><a href="#" class="hover:text-white">How It Works</a></li>
                        <li><a href="#" class="hover:text-white">Help Center</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="font-semibold mb-4">Categories</h6>
                    <ul class="space-y-2 text-gray-400">
                        @if(isset($categories))
                            @foreach($categories->take(4) as $category)
                                <li><a href="{{ route('search', ['category' => $category->slug ?? $category->id]) }}" class="hover:text-white">{{ $category->name }}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <div>
                    <h6 class="font-semibold mb-4">Contact</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li>📧 info@klikbid.lk</li>
                        <li>📞 +94 11 123 4567</li>
                        <li>📍 Colombo, Sri Lanka</li>
                        <li>🕒 24/7 Support</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 KlikBid. All rights reserved. | Terms & Conditions | Privacy Policy</p>
            </div>
        </div>
    </footer>
</body>
</html>
