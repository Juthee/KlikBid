<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Won Auctions - KlikBid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .won-card:hover {
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
                <span class="text-gray-900">Won Auctions</span>
            </div>
        </nav>

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Won Auctions</h2>
                <p class="mt-2 text-gray-600">Congratulations on your successful bids!</p>
            </div>
            <a href="{{ url('/') }}"
               class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                🎯 Browse More Auctions
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Won Auctions Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            @php
                $totalWon = $wonAuctions->total();
                $totalValue = $wonAuctions->sum(function($auction) {
                    return $auction->winning_bid_amount ?? 0;
                });
                $paidCount = $wonAuctions->where('paid_at', '!=', null)->count();
                $pendingPayment = $wonAuctions->where('paid_at', null)->count();
            @endphp

            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $totalWon }}</div>
                <div class="text-sm text-gray-600">Total Won</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">Rs {{ number_format($totalValue / 100, 0) }}</div>
                <div class="text-sm text-gray-600">Total Value</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $paidCount }}</div>
                <div class="text-sm text-gray-600">Completed</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $pendingPayment }}</div>
                <div class="text-sm text-gray-600">Pending Payment</div>
            </div>
        </div>

        <!-- Won Auctions List -->
        @if($wonAuctions->count() > 0)
            <div class="space-y-6 mb-8">
                @foreach($wonAuctions as $auction)
                    @php
                        $isPaid = $auction->paid_at !== null;
                        $isOverdue = !$isPaid && $auction->end_at < now()->subDays(5); // 5 days to pay
                        $daysLeft = $isPaid ? 0 : max(0, 5 - now()->diffInDays($auction->end_at));
                    @endphp

                    <div class="won-card bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6">
                            <!-- Header with Status -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-xl font-semibold text-gray-900 mr-3">🏆 {{ $auction->title }}</h3>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            @if($isPaid) bg-green-100 text-green-800
                                            @elseif($isOverdue) bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            @if($isPaid)
                                                ✅ Completed
                                            @elseif($isOverdue)
                                                ⚠️ Payment Overdue
                                            @else
                                                💰 Payment Due
                                            @endif
                                        </span>
                                    </div>

                                    <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                        <span>🏷️ {{ $auction->category->name }}</span>
                                        @if($auction->district)
                                            <span>📍 {{ $auction->district }}, {{ $auction->province }}</span>
                                        @endif
                                        <span>👤 {{ $auction->user->name }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Auction Results -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                <div class="bg-green-50 rounded-lg p-4 text-center">
                                    <p class="text-sm text-green-600 font-medium">Your Winning Bid</p>
                                    <p class="text-2xl font-bold text-green-800">Rs {{ number_format($auction->winning_bid_amount / 100, 0) }}</p>
                                </div>
                                <div class="bg-blue-50 rounded-lg p-4 text-center">
                                    <p class="text-sm text-blue-600 font-medium">Starting Price</p>
                                    <p class="text-lg font-bold text-blue-800">Rs {{ number_format($auction->base_price / 100, 0) }}</p>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-4 text-center">
                                    <p class="text-sm text-purple-600 font-medium">Total Bids</p>
                                    <p class="text-lg font-bold text-purple-800">{{ $auction->bids->count() }}</p>
                                </div>
                                <div class="bg-yellow-50 rounded-lg p-4 text-center">
                                    <p class="text-sm text-yellow-600 font-medium">Your Savings</p>
                                    @php
                                        $savings = ($auction->reserve_price ?? $auction->base_price * 2) - $auction->winning_bid_amount;
                                        $savingsPercent = $savings > 0 ? (($savings / ($auction->reserve_price ?? $auction->base_price * 2)) * 100) : 0;
                                    @endphp
                                    <p class="text-lg font-bold text-yellow-800">
                                        @if($savings > 0)
                                            Rs {{ number_format($savings / 100, 0) }} ({{ number_format($savingsPercent, 1) }}%)
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Auction Timeline -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <h4 class="font-medium text-gray-900 mb-3">Auction Timeline</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-600">Auction Started</p>
                                        <p class="font-medium">{{ $auction->start_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Auction Ended</p>
                                        <p class="font-medium">{{ $auction->end_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">You Won</p>
                                        <p class="font-medium text-green-600">{{ $auction->end_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Status & Actions -->
                            @if($isPaid)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-center">
                                        <span class="text-green-600 text-2xl mr-3">✅</span>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-green-800">Payment Completed</h4>
                                            <p class="text-sm text-green-700">
                                                Paid on {{ $auction->paid_at->format('M j, Y g:i A') }}
                                                ({{ $auction->paid_at->diffForHumans() }})
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($isOverdue)
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-center">
                                        <span class="text-red-600 text-2xl mr-3">⚠️</span>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-red-800">Payment Overdue</h4>
                                            <p class="text-sm text-red-700">
                                                Payment was due {{ now()->subDays(5)->addDays(now()->diffInDays($auction->end_at))->diffForHumans() }}.
                                                Your deposit may be at risk of forfeiture.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-center">
                                        <span class="text-yellow-600 text-2xl mr-3">💰</span>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-yellow-800">Payment Required</h4>
                                            <p class="text-sm text-yellow-700">
                                                Please complete payment within {{ $daysLeft }} day{{ $daysLeft != 1 ? 's' : '' }}.
                                                Amount due: <strong>Rs {{ number_format($auction->winning_bid_amount / 100, 0) }}</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('auctions.show', $auction) }}"
                                   class="bg-blue-600 text-white py-2 px-4 rounded-lg text-sm hover:bg-blue-700">
                                    View Auction Details
                                </a>

                                <a href="{{ route('bidding.history', $auction) }}"
                                   class="bg-gray-600 text-white py-2 px-4 rounded-lg text-sm hover:bg-gray-700">
                                    View Bid History
                                </a>

                                @if(!$isPaid)
                                    <button class="bg-green-600 text-white py-2 px-4 rounded-lg text-sm hover:bg-green-700"
                                            onclick="alert('Payment integration coming soon! For now, this is a demo of the won auctions interface.')">
                                        💳 Make Payment
                                    </button>
                                @endif

                                @if($isPaid)
                                    <button class="bg-purple-600 text-white py-2 px-4 rounded-lg text-sm hover:bg-purple-700"
                                            onclick="alert('Invoice download feature coming soon!')">
                                        📄 Download Invoice
                                    </button>
                                @endif
                            </div>

                            <!-- Seller Contact Info (for coordination) -->
                            @if($isPaid)
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <h5 class="font-medium text-gray-900 mb-2">📞 Next Steps</h5>
                                    <p class="text-sm text-gray-600">
                                        Your payment has been processed. The seller ({{ $auction->user->name }}) will contact you
                                        to arrange item pickup/delivery. If you haven't heard from them within 2 business days,
                                        please contact support.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $wonAuctions->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🏆</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No won auctions yet</h3>
                <p class="text-gray-600 mb-6">Keep bidding to win your first auction!</p>
                <a href="{{ url('/') }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                    🎯 Browse Auctions
                </a>
            </div>
        @endif

        <!-- Winner Guidelines -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🏆 Winner Guidelines</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 mt-1">💳</span>
                    <div>
                        <p class="font-medium text-sm">Complete Payment Quickly</p>
                        <p class="text-xs text-gray-600">Pay within 5 days to avoid deposit forfeiture</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 mt-1">📞</span>
                    <div>
                        <p class="font-medium text-sm">Contact the Seller</p>
                        <p class="text-xs text-gray-600">Coordinate pickup or delivery arrangements</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 mt-1">🔍</span>
                    <div>
                        <p class="font-medium text-sm">Inspect Items</p>
                        <p class="text-xs text-gray-600">Verify condition matches the description</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 mt-1">📋</span>
                    <div>
                        <p class="font-medium text-sm">Keep Records</p>
                        <p class="text-xs text-gray-600">Save invoices and communication for your records</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 mt-1">⭐</span>
                    <div>
                        <p class="font-medium text-sm">Leave Feedback</p>
                        <p class="text-xs text-gray-600">Rate your experience to help other users</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 mt-1">🆘</span>
                    <div>
                        <p class="font-medium text-sm">Get Support</p>
                        <p class="text-xs text-gray-600">Contact us if you encounter any issues</p>
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
