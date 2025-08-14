<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bids - KlikBid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .bid-card:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
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
                    <a href="{{ url('/') }}" class="flex items-center">
                        <h1 class="text-3xl font-bold text-blue-600">KlikBid</h1>
                        <span class="ml-2 text-sm text-gray-500">Sri Lanka</span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600">Home</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Browse</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Help</a>
                    <a href="{{ route('user.dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <span>→</span>
                <span class="text-gray-900">My Bids</span>
            </div>
        </nav>

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">My Bids</h2>
                <p class="mt-2 text-gray-600">Track all your bidding activity</p>
            </div>
            <a href="{{ url('/') }}"
               class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                🎯 Browse Auctions
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Bid Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            @php
                $activeBids = $bids->filter(function($bid) {
                    return $bid->auction->status === 'active';
                });
                $winningBids = $bids->filter(function($bid) {
                    return $bid->is_highest_snapshot && $bid->auction->status === 'active';
                });
                $wonAuctions = $bids->filter(function($bid) {
                    return $bid->auction->winner_user_id === auth()->id();
                });
                $totalSpent = $wonAuctions->sum(function($bid) {
                    return $bid->auction->winning_bid_amount ?? 0;
                });
            @endphp

            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $activeBids->count() }}</div>
                <div class="text-sm text-gray-600">Active Bids</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $winningBids->count() }}</div>
                <div class="text-sm text-gray-600">Currently Winning</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $wonAuctions->count() }}</div>
                <div class="text-sm text-gray-600">Won Auctions</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $bids->total() }}</div>
                <div class="text-sm text-gray-600">Total Bids</div>
            </div>
        </div>

        <!-- Bids List -->
        @if($bids->count() > 0)
            <div class="space-y-4 mb-8">
                @foreach($bids as $bid)
                    @php
                        $auction = $bid->auction;
                        $isHighestBidder = $bid->is_highest_snapshot;
                        $hasWon = $auction->winner_user_id === auth()->id();
                        $auctionEnded = in_array($auction->status, ['ended', 'won']);

                        // Determine bid status
                        if ($hasWon) {
                            $status = 'won';
                            $statusText = 'Won';
                            $statusColor = 'bg-green-100 text-green-800';
                            $iconColor = 'text-green-600';
                            $icon = '🏆';
                        } elseif ($auctionEnded) {
                            $status = 'lost';
                            $statusText = 'Lost';
                            $statusColor = 'bg-gray-100 text-gray-800';
                            $iconColor = 'text-gray-600';
                            $icon = '❌';
                        } elseif ($isHighestBidder && $auction->status === 'active') {
                            $status = 'winning';
                            $statusText = 'Winning';
                            $statusColor = 'bg-green-100 text-green-800';
                            $iconColor = 'text-green-600';
                            $icon = '🥇';
                        } elseif ($auction->status === 'active') {
                            $status = 'outbid';
                            $statusText = 'Outbid';
                            $statusColor = 'bg-red-100 text-red-800';
                            $iconColor = 'text-red-600';
                            $icon = '🔻';
                        } else {
                            $status = 'pending';
                            $statusText = ucfirst(str_replace('_', ' ', $auction->status));
                            $statusColor = 'bg-yellow-100 text-yellow-800';
                            $iconColor = 'text-yellow-600';
                            $icon = '⏳';
                        }

                        $currentHighestBid = $auction->bids()->where('is_highest_snapshot', true)->first();
                    @endphp

                    <div class="bid-card bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Auction Title & Category -->
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900 mr-3">{{ $auction->title }}</h3>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                            {{ $icon }} {{ $statusText }}
                                        </span>
                                    </div>

                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                        <span>🏷️ {{ $auction->category->name }}</span>
                                        @if($auction->district)
                                            <span>📍 {{ $auction->district }}, {{ $auction->province }}</span>
                                        @endif
                                        <span>👤 {{ $auction->user->name }}</span>
                                    </div>

                                    <!-- Bid Information -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                        <div>
                                            <p class="text-xs text-gray-500">Your Bid</p>
                                            <p class="font-bold text-blue-600">Rs {{ number_format($bid->bid_amount / 100, 0) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Current Highest</p>
                                            <p class="font-bold text-green-600">
                                                Rs {{ number_format(($currentHighestBid ? $currentHighestBid->bid_amount : $auction->base_price) / 100, 0) }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Starting Price</p>
                                            <p class="font-medium text-gray-800">Rs {{ number_format($auction->base_price / 100, 0) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Total Bids</p>
                                            <p class="font-medium text-gray-800">{{ $auction->bids->count() }}</p>
                                        </div>
                                    </div>

                                    <!-- Auction Timing -->
                                    <div class="flex items-center justify-between text-sm text-gray-600">
                                        <div>
                                            <span class="font-medium">Your bid placed:</span>
                                            <span>{{ $bid->created_at->format('M j, Y g:i A') }}</span>
                                            <span class="text-gray-500">({{ $bid->created_at->diffForHumans() }})</span>
                                        </div>
                                        @if($auction->status === 'active')
                                            <div class="text-right">
                                                @php
                                                    $timeLeft = now()->diffInHours($auction->end_at);
                                                    $days = intval($timeLeft / 24);
                                                    $hours = $timeLeft % 24;
                                                @endphp
                                                <span class="font-medium text-red-600">
                                                    @if($days > 0){{ $days }}d {{ $hours }}h left @else {{ $timeLeft }}h left @endif
                                                </span>
                                            </div>
                                        @elseif($auctionEnded)
                                            <div class="text-right">
                                                <span class="font-medium text-gray-600">
                                                    Ended {{ $auction->end_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex space-x-3">
                                    <a href="{{ route('auctions.show', $auction) }}"
                                       class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg text-center text-sm hover:bg-blue-700">
                                        View Auction
                                    </a>

                                    @if($auction->status === 'active')
                                        @if(!$isHighestBidder)
                                            <a href="{{ route('auctions.show', $auction) }}#bidding"
                                               class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg text-center text-sm hover:bg-green-700">
                                                Place Higher Bid
                                            </a>
                                        @endif
                                    @endif

                                    <a href="{{ route('bidding.history', $auction) }}"
                                       class="bg-gray-600 text-white py-2 px-4 rounded-lg text-sm hover:bg-gray-700">
                                       Bid History
                                    </a>
                                </div>

                                <!-- Status Messages -->
                                @if($status === 'winning')
                                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <div class="flex items-center">
                                            <span class="text-green-600 mr-2">🎯</span>
                                            <p class="text-sm text-green-800">
                                                <strong>You're currently winning!</strong> Keep an eye on this auction as it may receive higher bids.
                                            </p>
                                        </div>
                                    </div>
                                @elseif($status === 'outbid')
                                    <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <div class="flex items-center">
                                            <span class="text-red-600 mr-2">⚠️</span>
                                            <p class="text-sm text-red-800">
                                                <strong>You've been outbid!</strong> Current highest bid: Rs {{ number_format($currentHighestBid->bid_amount / 100, 0) }}
                                            </p>
                                        </div>
                                    </div>
                                @elseif($status === 'won')
                                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <div class="flex items-center">
                                            <span class="text-green-600 mr-2">🎉</span>
                                            <p class="text-sm text-green-800">
                                                <strong>Congratulations! You won this auction!</strong> Final winning bid: Rs {{ number_format($auction->winning_bid_amount / 100, 0) }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $bids->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🎯</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No bids placed yet</h3>
                <p class="text-gray-600 mb-6">Start bidding on auctions that interest you</p>
                <a href="{{ url('/') }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                    🎯 Browse Auctions
                </a>
            </div>
        @endif

        <!-- Bidding Tips -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">💡 Smart Bidding Tips</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="flex items-start space-x-3">
                    <span class="text-blue-600 mt-1">💰</span>
                    <div>
                        <p class="font-medium text-sm">Set a Maximum Budget</p>
                        <p class="text-xs text-gray-600">Decide your limit before bidding to avoid overspending</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-blue-600 mt-1">⏰</span>
                    <div>
                        <p class="font-medium text-sm">Bid at the Right Time</p>
                        <p class="text-xs text-gray-600">Consider bidding in the final hours for better chances</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-blue-600 mt-1">🔍</span>
                    <div>
                        <p class="font-medium text-sm">Research Item Value</p>
                        <p class="text-xs text-gray-600">Know the market price to bid competitively</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-blue-600 mt-1">📱</span>
                    <div>
                        <p class="font-medium text-sm">Stay Connected</p>
                        <p class="text-xs text-gray-600">Monitor auctions closely in the final hours</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-blue-600 mt-1">🎯</span>
                    <div>
                        <p class="font-medium text-sm">Strategic Increments</p>
                        <p class="text-xs text-gray-600">Use psychological pricing (e.g., Rs 201,000 vs Rs 200,000)</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-blue-600 mt-1">📊</span>
                    <div>
                        <p class="font-medium text-sm">Track Your Activity</p>
                        <p class="text-xs text-gray-600">Keep notes on items you're interested in</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2025 KlikBid. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
