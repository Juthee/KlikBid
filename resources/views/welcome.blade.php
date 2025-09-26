<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlikBid - Sri Lanka's Premier Auction Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .auction-card:hover {
            transform: translateY(-4px);
            transition: transform 0.3s ease;
        }
    </style>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .auction-card:hover {
            transform: translateY(-4px);
            transition: transform 0.3s ease;
        }

        /* NEW CSS FOR CARD ALIGNMENT */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .auction-card {
            min-height: 400px;
            /* Ensures minimum consistent height */
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold text-blue-600">KlikBid</h1>
                    <span class="ml-2 text-sm text-gray-500">Sri Lanka</span>
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
                        <button type="submit"
                            class="bg-blue-600 text-white px-8 py-2 rounded-r-lg hover:bg-blue-700 font-semibold">
                            Search
                        </button>
                    </form>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-6">
                    <a href="#" class="text-gray-700 hover:text-blue-600">Browse</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Help</a>
                    @guest
                        <a href="{{ route('login') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Login</a>
                        <a href="{{ route('register') }}"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Register</a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</button>
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
                    <select name="category"
                        class="bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        <option value="land-properties"
                            {{ request('category') == 'land-properties' ? 'selected' : '' }}>🏠 Land & Properties
                        </option>
                        <option value="vehicles" {{ request('category') == 'vehicles' ? 'selected' : '' }}>🚗 Vehicles
                        </option>
                        <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>📱
                            Electronics</option>
                        <option value="luxury" {{ request('category') == 'luxury' ? 'selected' : '' }}>💎 Luxury Items
                        </option>
                        <option value="machinery" {{ request('category') == 'machinery' ? 'selected' : '' }}>⚙️
                            Machinery</option>
                        <option value="antiques" {{ request('category') == 'antiques' ? 'selected' : '' }}>🏺 Antiques
                        </option>
                    </select>

                    <!-- Location Filter -->
                    <select name="location"
                        class="bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">All Locations</option>

                        <!-- Western Province -->
                        <optgroup label="🏙️ Western Province">
                            <option value="colombo" {{ request('location') == 'colombo' ? 'selected' : '' }}>Colombo
                            </option>
                            <option value="gampaha" {{ request('location') == 'gampaha' ? 'selected' : '' }}>Gampaha
                            </option>
                            <option value="kalutara" {{ request('location') == 'kalutara' ? 'selected' : '' }}>Kalutara
                            </option>
                        </optgroup>

                        <!-- Central Province -->
                        <optgroup label="🏔️ Central Province">
                            <option value="kandy" {{ request('location') == 'kandy' ? 'selected' : '' }}>Kandy
                            </option>
                            <option value="matale" {{ request('location') == 'matale' ? 'selected' : '' }}>Matale
                            </option>
                            <option value="nuwara-eliya" {{ request('location') == 'nuwara-eliya' ? 'selected' : '' }}>
                                Nuwara Eliya</option>
                        </optgroup>

                        <!-- Southern Province -->
                        <optgroup label="🏖️ Southern Province">
                            <option value="galle" {{ request('location') == 'galle' ? 'selected' : '' }}>Galle
                            </option>
                            <option value="matara" {{ request('location') == 'matara' ? 'selected' : '' }}>Matara
                            </option>
                            <option value="hambantota" {{ request('location') == 'hambantota' ? 'selected' : '' }}>
                                Hambantota</option>
                        </optgroup>

                        <!-- North Western Province -->
                        <optgroup label="🌾 North Western Province">
                            <option value="kurunegala" {{ request('location') == 'kurunegala' ? 'selected' : '' }}>
                                Kurunegala</option>
                            <option value="puttalam" {{ request('location') == 'puttalam' ? 'selected' : '' }}>Puttalam
                            </option>
                        </optgroup>

                        <!-- North Central Province -->
                        <optgroup label="⛲ North Central Province">
                            <option value="anuradhapura" {{ request('location') == 'anuradhapura' ? 'selected' : '' }}>
                                Anuradhapura</option>
                            <option value="polonnaruwa" {{ request('location') == 'polonnaruwa' ? 'selected' : '' }}>
                                Polonnaruwa</option>
                        </optgroup>

                        <!-- Uva Province -->
                        <optgroup label="⛰️ Uva Province">
                            <option value="badulla" {{ request('location') == 'badulla' ? 'selected' : '' }}>Badulla
                            </option>
                            <option value="monaragala" {{ request('location') == 'monaragala' ? 'selected' : '' }}>
                                Monaragala</option>
                        </optgroup>

                        <!-- Sabaragamuwa Province -->
                        <optgroup label="💎 Sabaragamuwa Province">
                            <option value="ratnapura" {{ request('location') == 'ratnapura' ? 'selected' : '' }}>
                                Ratnapura</option>
                            <option value="kegalle" {{ request('location') == 'kegalle' ? 'selected' : '' }}>Kegalle
                            </option>
                        </optgroup>

                        <!-- Eastern Province -->
                        <optgroup label="🌊 Eastern Province">
                            <option value="ampara" {{ request('location') == 'ampara' ? 'selected' : '' }}>Ampara
                            </option>
                            <option value="batticaloa" {{ request('location') == 'batticaloa' ? 'selected' : '' }}>
                                Batticaloa</option>
                            <option value="trincomalee" {{ request('location') == 'trincomalee' ? 'selected' : '' }}>
                                Trincomalee</option>
                        </optgroup>

                        <!-- Northern Province -->
                        <optgroup label="🏛️ Northern Province">
                            <option value="jaffna" {{ request('location') == 'jaffna' ? 'selected' : '' }}>Jaffna
                            </option>
                            <option value="kilinochchi" {{ request('location') == 'kilinochchi' ? 'selected' : '' }}>
                                Kilinochchi</option>
                            <option value="mannar" {{ request('location') == 'mannar' ? 'selected' : '' }}>Mannar
                            </option>
                            <option value="mullaitivu" {{ request('location') == 'mullaitivu' ? 'selected' : '' }}>
                                Mullaitivu</option>
                            <option value="vavuniya" {{ request('location') == 'vavuniya' ? 'selected' : '' }}>Vavuniya
                            </option>
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
                    <select name="sort"
                        class="bg-white border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="ending_soon" {{ request('sort') == 'ending_soon' ? 'selected' : '' }}>⏰ Ending
                            Soon</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>🆕 Newest First
                        </option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>💰 Price: Low
                            to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>💸 Price:
                            High to Low</option>
                    </select>

                    <!-- Apply Filters Button -->
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold">
                        Apply Filters
                    </button>

                    <!-- Clear All Link -->
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-800 text-sm underline">Clear
                        All</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-5xl font-bold mb-4">Welcome to KlikBid</h2>
            <p class="text-xl mb-8">Sri Lanka's trusted online auction platform</p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('search') }}"
                    class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block">
                    Start Bidding
                </a>
                @guest
                    <a href="{{ route('login') }}"
                        class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600">
                        Post Your Item
                    </a>
                @else
                    <a href="{{ route('auctions.create') }}"
                        class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600">
                        Post Your Item
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- <!-- Categories Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-3xl font-bold text-center mb-12">Browse Categories</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <!-- Land -->
                <div class="text-center p-6 bg-gray-50 rounded-lg hover:shadow-lg cursor-pointer">
                    <div class="text-4xl mb-3">🏞️</div>
                    <h4 class="font-semibold">Land</h4>
                    <p class="text-sm text-gray-600">Agricultural & Residential</p>
                </div>

                <!-- Properties -->
                <div class="text-center p-6 bg-gray-50 rounded-lg hover:shadow-lg cursor-pointer">
                    <div class="text-4xl mb-3">🏠</div>
                    <h4 class="font-semibold">Properties</h4>
                    <p class="text-sm text-gray-600">Houses & Commercial</p>
                </div>

                <!-- Vehicles -->
                <div class="text-center p-6 bg-gray-50 rounded-lg hover:shadow-lg cursor-pointer">
                    <div class="text-4xl mb-3">🚗</div>
                    <h4 class="font-semibold">Vehicles</h4>
                    <p class="text-sm text-gray-600">Cars, Bikes & More</p>
                </div>

                <!-- Electronics -->
                <div class="text-center p-6 bg-gray-50 rounded-lg hover:shadow-lg cursor-pointer">
                    <div class="text-4xl mb-3">📱</div>
                    <h4 class="font-semibold">Electronics</h4>
                    <p class="text-sm text-gray-600">Phones, Laptops & TVs</p>
                </div>

                <!-- Luxury -->
                <div class="text-center p-6 bg-gray-50 rounded-lg hover:shadow-lg cursor-pointer">
                    <div class="text-4xl mb-3">💎</div>
                    <h4 class="font-semibold">Luxury</h4>
                    <p class="text-sm text-gray-600">Jewelry & Watches</p>
                </div>

                <!-- More -->
                <div class="text-center p-6 bg-gray-50 rounded-lg hover:shadow-lg cursor-pointer">
                    <div class="text-4xl mb-3">📦</div>
                    <h4 class="font-semibold">More</h4>
                    <p class="text-sm text-gray-600">All Categories</p>
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Featured Auctions -->
    <!-- Live Auctions Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-3xl font-bold">🔴 Live Auctions</h3>
                <p class="text-gray-600">Bid now! These auctions are accepting bids</p>
            </div>

            @if ($liveAuctions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($liveAuctions as $auction)
                        <div
                            class="bg-white rounded-lg shadow-md border-2 border-green-200 auction-card overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full">
                            <div class="h-48 bg-gray-200 flex items-center justify-center relative overflow-hidden">
                                @if ($auction->images && count($auction->images) > 0)
                                    <img src="{{ asset('storage/' . $auction->images[0]) }}"
                                        alt="{{ $auction->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-gray-500">🏠 {{ $auction->category->name }}</span>
                                @endif
                                <!-- Live indicator -->
                                <div
                                    class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold animate-pulse">
                                    LIVE
                                </div>
                            </div>
                            <div class="p-4 flex flex-col flex-grow">
                                <h4 class="font-semibold mb-2">{{ $auction->title }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($auction->description, 50) }}</p>

                                <!-- Additional auction details -->
                                <div class="space-y-1 mb-3 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Deposit:</span>
                                        <span class="font-medium text-purple-600">
                                            @if ($auction->deposit_amount > 0)
                                                Rs {{ number_format($auction->deposit_amount / 100, 0) }}
                                            @else
                                                Free
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Total Bids:</span>
                                        <span class="font-medium">{{ $auction->bids->count() }}</span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-end mb-4 mt-auto">
                                    <div>
                                        <p class="text-xs text-gray-500">Current Bid</p>
                                        <p class="font-bold text-lg text-green-600">Rs
                                            {{ number_format($auction->base_price / 100, 0) }}</p>
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
                                <div class="mt-auto">
                                    <a href="{{ route('auctions.show', $auction) }}"
                                        class="block w-full bg-green-600 text-white text-center py-2 rounded-lg hover:bg-green-700 font-semibold">
                                        BID NOW
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <p class="text-gray-500 text-lg">No live auctions at the moment.</p>
                    <p class="text-gray-400 text-sm">Check back soon or browse upcoming auctions below!</p>
                </div>
            @endif
        </div>
    </section>
    @if ($totalLiveCount > 8)
        <div class="text-center mt-6">
            <a href="{{ route('search', ['status' => 'live']) }}"
                class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-semibold inline-flex items-center">
                View All Live Auctions ({{ $totalLiveCount }})
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    @endif

    <!-- Ending Soon Section -->
    <section class="py-16 bg-red-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-3xl font-bold">⚡ Ending Soon</h3>
                <p class="text-gray-600">Hurry! These auctions end within 24 hours</p>
            </div>

            @if ($endingSoon->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($endingSoon as $auction)
                        <div
                            class="bg-white rounded-lg shadow-md border-2 border-red-200 auction-card overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full">
                            <div class="h-48 bg-gray-200 flex items-center justify-center relative overflow-hidden">
                                @if ($auction->images && count($auction->images) > 0)
                                    <img src="{{ asset('storage/' . $auction->images[0]) }}"
                                        alt="{{ $auction->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-gray-500">🏠 {{ $auction->category->name }}</span>
                                @endif
                                <!-- Urgent indicator -->
                                <div
                                    class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold animate-bounce">
                                    URGENT
                                </div>
                            </div>
                            <div class="p-4 flex flex-col flex-grow">
                                <h4 class="font-semibold mb-2">{{ $auction->title }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($auction->description, 50) }}</p>

                                <!-- Additional auction details -->
                                <div class="space-y-1 mb-3 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Deposit:</span>
                                        <span class="font-medium text-purple-600">
                                            @if ($auction->deposit_amount > 0)
                                                Rs {{ number_format($auction->deposit_amount / 100, 0) }}
                                            @else
                                                Free
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Total Bids:</span>
                                        <span class="font-medium">{{ $auction->bids->count() }}</span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-end mb-4 mt-auto">
                                    <div>
                                        <p class="text-xs text-gray-500">Current Bid</p>
                                        <p class="font-bold text-lg text-red-600">Rs
                                            {{ number_format($auction->base_price / 100, 0) }}</p>
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

                                <div class="mt-auto">
                                    <a href="{{ route('auctions.show', $auction) }}"
                                        class="block w-full bg-red-600 text-white text-center py-2 rounded-lg hover:bg-red-700 font-semibold">
                                        ⚡ BID URGENTLY
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($totalEndingSoonCount > 8)
                    <div class="text-center mt-6">
                        <a href="{{ route('search', ['status' => 'ending_soon']) }}"
                            class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-semibold inline-flex items-center">
                            View All Ending Soon ({{ $totalEndingSoonCount }})
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-12 bg-white rounded-lg">
                    <p class="text-gray-500 text-lg">No auctions ending soon.</p>
                    <p class="text-gray-400 text-sm">All current auctions have plenty of time left!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Starting Soon Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-3xl font-bold">⏰ Starting Soon</h3>
                <p class="text-gray-600">Get ready! These auctions open within 24 hours</p>
            </div>

            @if ($upcomingAuctions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($upcomingAuctions as $auction)
                        <div
                            class="bg-white rounded-lg shadow-md border-2 border-blue-200 auction-card overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full">
                            <div class="h-48 bg-gray-200 flex items-center justify-center relative overflow-hidden">
                                @if ($auction->images && count($auction->images) > 0)
                                    <img src="{{ asset('storage/' . $auction->images[0]) }}"
                                        alt="{{ $auction->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-gray-500">🏠 {{ $auction->category->name }}</span>
                                @endif
                                <!-- Coming soon indicator -->
                                <div
                                    class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                    SOON
                                </div>
                            </div>
                            <div class="p-4 flex flex-col flex-grow">
                                <h4 class="font-semibold mb-2">{{ $auction->title }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($auction->description, 50) }}</p>
                                <div class="flex justify-between items-end mb-4 mt-auto">
                                    <div>
                                        <p class="text-xs text-gray-500">Starting Bid</p>
                                        <p class="font-bold text-lg text-blue-600">Rs
                                            {{ number_format($auction->base_price / 100, 0) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">Starts in</p>
                                        <p class="font-semibold text-blue-600">
                                            @php
                                                $now = now();
                                                $startTime = $auction->start_at;

                                                $totalMinutes = $now->diffInMinutes($startTime);
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
                                            @endphp
                                            Starts in {{ $timeDisplay }}
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-auto">
                                    <a href="{{ route('auctions.show', $auction) }}"
                                        class="block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 font-semibold">
                                        VIEW DETAILS
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <p class="text-gray-500 text-lg">No auctions starting soon.</p>
                    <p class="text-gray-400 text-sm">All upcoming auctions are scheduled for later!</p>
                </div>
            @endif
        </div>
    </section>
    @if ($totalUpcomingCount > 8)
        <div class="text-center mt-6">
            <a href="{{ route('search', ['status' => 'upcoming']) }}"
                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold inline-flex items-center">
                View All Starting Soon ({{ $totalUpcomingCount }})
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    @endif

    <!-- How It Works -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-3xl font-bold text-center mb-12">How KlikBid Works</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">📝</span>
                    </div>
                    <h4 class="text-xl font-semibold mb-3">1. Register & Verify</h4>
                    <p class="text-gray-600">Create your account and complete KYC verification to start bidding</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🔍</span>
                    </div>
                    <h4 class="text-xl font-semibold mb-3">2. Browse & Bid</h4>
                    <p class="text-gray-600">Find items you want, pay the deposit, and place your bids</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🏆</span>
                    </div>
                    <h4 class="text-xl font-semibold mb-3">3. Win & Pay</h4>
                    <p class="text-gray-600">Win auctions and complete secure payments to get your items</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 gradient-bg text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold mb-2">1,250+</div>
                    <div class="text-lg">Active Auctions</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">5,600+</div>
                    <div class="text-lg">Registered Users</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">Rs 2.5B+</div>
                    <div class="text-lg">Total Sales</div>
                </div>
                <div>
                    <div class="text-4xl font-bold mb-2">98%</div>
                    <div class="text-lg">Success Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h5 class="text-xl font-bold mb-4">KlikBid</h5>
                    <p class="text-gray-400">Sri Lanka's premier online auction platform. Buy and sell with confidence.
                    </p>
                </div>
                <div>
                    <h6 class="font-semibold mb-4">Quick Links</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Browse Auctions</a></li>
                        <li><a href="#" class="hover:text-white">Post an Auction</a></li>
                        <li><a href="#" class="hover:text-white">How It Works</a></li>
                        <li><a href="#" class="hover:text-white">Help Center</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="font-semibold mb-4">Categories</h6>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Land & Properties</a></li>
                        <li><a href="#" class="hover:text-white">Vehicles</a></li>
                        <li><a href="#" class="hover:text-white">Electronics</a></li>
                        <li><a href="#" class="hover:text-white">Luxury Items</a></li>
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
