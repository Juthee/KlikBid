<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - KlikBid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .auction-card:hover { transform: translateY(-4px); transition: transform 0.3s ease; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header with Search -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
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
                        @foreach($categories as $category)
                            <!-- Main Category -->
                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                            <!-- Subcategories -->
                            @foreach($category->children()->orderBy('sort_order')->get() as $subcategory)
                                <option value="{{ $subcategory->slug }}" {{ request('category') == $subcategory->slug ? 'selected' : '' }}>
                                    &nbsp;&nbsp;&nbsp;&nbsp;└─ {{ $subcategory->icon }} {{ $subcategory->name }}
                                </option>
                            @endforeach
                        @endforeach
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
                    @if($searchStats['query'])
                        Search Results for "{{ $searchStats['query'] }}"
                    @else
                        Browse Auctions
                    @endif
                </h2>
                <p class="text-gray-600 mt-1">
                    Found {{ $searchStats['total'] }} auctions
                    @if($searchStats['filters_applied'] > 0)
                        with {{ $searchStats['filters_applied'] }} filter(s) applied
                    @endif
                </p>
            </div>
        </div>

        <!-- Results -->
        @if($auctions->count() > 0)
            <!-- Auction Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($auctions as $auction)
                    <div class="bg-white rounded-xl shadow-md auction-card overflow-hidden border">
                        <!-- Auction Image Placeholder -->
                        <div class="h-48 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                            <span class="text-4xl">
                                @if($auction->category->slug == 'land-properties')
                                    🏠
                                @elseif($auction->category->slug == 'vehicles')
                                    🚗
                                @elseif($auction->category->slug == 'electronics')
                                    📱
                                @elseif($auction->category->slug == 'luxury')
                                    💎
                                @else
                                    📦
                                @endif
                            </span>
                        </div>

                        <div class="p-4">
                            <!-- Category and Status -->
                            <div class="flex items-start justify-between mb-2">
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    {{ $auction->category->name }}
                                </span>
                                <span class="text-green-600 text-xs font-medium">🟢 ACTIVE</span>
                            </div>

                            <!-- Title -->
                            <h3 class="font-semibold text-lg mb-2 text-gray-800">{{ $auction->title }}</h3>

                            <!-- Description -->
                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($auction->description, 50) }}</p>

                            <!-- Price and Location -->
                            <div class="flex justify-between items-center mb-3">
                                <div>
                                    <p class="text-xs text-gray-500">Starting Bid</p>
                                    <p class="font-bold text-lg text-green-600">Rs {{ number_format($auction->base_price / 100, 0) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">📍 Location</p>
                                    <p class="text-sm font-medium">{{ $auction->district ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Time and Action -->
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="text-xs text-gray-500">Ends in</p>
                                    <p class="font-semibold text-red-600">
                                        @php
                                            $hoursLeft = \Carbon\Carbon::now()->diffInHours($auction->end_at);
                                            $daysLeft = intval($hoursLeft / 24);
                                            $remainingHours = $hoursLeft % 24;
                                        @endphp
                                        @if($daysLeft > 0)
                                            {{ $daysLeft }}d {{ $remainingHours }}h
                                        @else
                                            {{ $hoursLeft }}h
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">👤 Seller</p>
                                    <p class="text-sm font-medium">{{ $auction->user->name }}</p>
                                </div>
                            </div>

                            <!-- Bid Button -->
                            <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-semibold transition-colors">
                                Place Bid
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $auctions->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-16">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No auctions found</h3>
                <p class="text-gray-600 mb-6">
                    @if($searchStats['query'])
                        No results found for "{{ $searchStats['query'] }}". Try adjusting your search or filters.
                    @else
                        No auctions match your current filter criteria.
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
                        @foreach($categories->take(4) as $category)
                            <li><a href="{{ route('search', ['category' => $category->slug]) }}" class="hover:text-white">{{ $category->name }}</a></li>
                        @endforeach
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
