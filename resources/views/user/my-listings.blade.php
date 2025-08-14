<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Listings - KlikBid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .listing-card:hover {
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
                <span class="text-gray-900">My Listings</span>
            </div>
        </nav>

        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">My Listings</h2>
                <p class="mt-2 text-gray-600">Manage all your auction listings</p>
            </div>
            <a href="{{ route('auctions.create') }}"
               class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                📦 Post New Auction
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Listings Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $listings->where('status', 'active')->count() }}</div>
                <div class="text-sm text-gray-600">Active</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $listings->where('status', 'pending_approval')->count() }}</div>
                <div class="text-sm text-gray-600">Pending</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $listings->where('status', 'scheduled')->count() }}</div>
                <div class="text-sm text-gray-600">Scheduled</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-gray-600">{{ $listings->total() }}</div>
                <div class="text-sm text-gray-600">Total</div>
            </div>
        </div>

        <!-- Listings Grid -->
        @if($listings->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                @foreach($listings as $listing)
                    <div class="listing-card bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Auction Image -->
                        <div class="h-48 bg-gray-200 overflow-hidden">
                            @if($listing->images && count($listing->images) > 0)
                                <img src="{{ asset('storage/' . $listing->images[0]) }}"
                                    alt="{{ $listing->title }}"
                                    class="w-full h-full object-contain bg-gray-100">
                                @if(count($listing->images) > 1)
                                    <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                                        +{{ count($listing->images) - 1 }} more
                                    </div>
                                @endif
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <span class="text-4xl">{{ $listing->category->name == 'Mobile Phones' ? '📱' : ($listing->category->name == 'Cars' ? '🚗' : ($listing->category->name == 'Houses' ? '🏠' : '📦')) }}</span>
                                        <p class="text-sm text-gray-500 mt-2">{{ $listing->category->name }}</p>
                                        <p class="text-xs text-gray-400">No images uploaded</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Auction Details -->
                        <div class="p-6">
                            <!-- Status Badge -->
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($listing->status == 'pending_approval') bg-yellow-100 text-yellow-800
                                    @elseif($listing->status == 'active') bg-green-100 text-green-800
                                    @elseif($listing->status == 'scheduled') bg-blue-100 text-blue-800
                                    @elseif($listing->status == 'ended') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $listing->status)) }}
                                </span>

                                @if($listing->bids->count() > 0)
                                    <span class="text-sm text-green-600 font-medium">
                                        {{ $listing->bids->count() }} bid{{ $listing->bids->count() > 1 ? 's' : '' }}
                                    </span>
                                @endif
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $listing->title }}</h3>

                            <!-- Location -->
                            @if($listing->district)
                                <p class="text-sm text-gray-600 mb-3">📍 {{ $listing->district }}, {{ $listing->province }}</p>
                            @endif

                            <!-- Pricing Info -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-500">Starting Price</p>
                                    <p class="font-bold text-blue-600">Rs {{ number_format($listing->base_price / 100, 0) }}</p>
                                </div>
                                @if($listing->bids->count() > 0)
                                    <div>
                                        <p class="text-xs text-gray-500">Current Bid</p>
                                        <p class="font-bold text-green-600">Rs {{ number_format($listing->bids->max('bid_amount') / 100, 0) }}</p>
                                    </div>
                                @else
                                    <div>
                                        <p class="text-xs text-gray-500">Deposit Required</p>
                                        <p class="font-bold text-purple-600">
                                            @if($listing->deposit_amount > 0)
                                                Rs {{ number_format($listing->deposit_amount / 100, 0) }}
                                            @else
                                                Free
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Auction Schedule -->
                            <div class="mb-4 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Starts:</span>
                                    <span>{{ $listing->start_at->format('M j, g:i A') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Ends:</span>
                                    <span>{{ $listing->end_at->format('M j, g:i A') }}</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <a href="{{ route('auctions.show', $listing) }}"
                                class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-lg text-center text-sm hover:bg-blue-700">
                                    View Details
                                </a>

                                @if($listing->status === 'pending_approval')
                                    <a href="{{ route('auctions.edit', $listing) }}"
                                    class="flex-1 bg-yellow-600 text-white py-2 px-4 rounded-lg text-center text-sm hover:bg-yellow-700">
                                        ✏️ Edit
                                    </a>
                                @elseif($listing->bids->count() > 0)
                                    <a href="{{ route('bidding.history', $listing) }}"
                                    class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg text-center text-sm hover:bg-green-700">
                                        View Bids ({{ $listing->bids->count() }})
                                    </a>
                                @endif
                            </div>

                            <!-- Quick Stats -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex justify-between text-xs text-gray-500">
                                    <span>Created: {{ $listing->created_at->format('M j, Y') }}</span>
                                    @if($listing->status == 'active')
                                        @php
                                            $timeLeft = now()->diffInHours($listing->end_at);
                                            $days = intval($timeLeft / 24);
                                            $hours = $timeLeft % 24;
                                        @endphp
                                        <span class="text-red-600 font-medium">
                                            @if($days > 0){{ $days }}d {{ $hours }}h left @else {{ $timeLeft }}h left @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $listings->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No listings yet</h3>
                <p class="text-gray-600 mb-6">Start selling by posting your first auction</p>
                <a href="{{ route('auctions.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                    📦 Post Your First Auction
                </a>
            </div>
        @endif

        <!-- Tips Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">💡 Tips for Better Listings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 mt-1">✓</span>
                    <div>
                        <p class="font-medium text-sm">Use Clear Photos</p>
                        <p class="text-xs text-gray-600">Multiple high-quality images attract more bidders</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 mt-1">✓</span>
                    <div>
                        <p class="font-medium text-sm">Detailed Descriptions</p>
                        <p class="text-xs text-gray-600">Include condition, features, and specifications</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 mt-1">✓</span>
                    <div>
                        <p class="font-medium text-sm">Competitive Pricing</p>
                        <p class="text-xs text-gray-600">Research similar items for fair starting prices</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 mt-1">✓</span>
                    <div>
                        <p class="font-medium text-sm">Optimal Timing</p>
                        <p class="text-xs text-gray-600">3-7 day auctions typically perform best</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 mt-1">✓</span>
                    <div>
                        <p class="font-medium text-sm">Respond Quickly</p>
                        <p class="text-xs text-gray-600">Answer bidder questions promptly</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-green-600 mt-1">✓</span>
                    <div>
                        <p class="font-medium text-sm">Honest Condition</p>
                        <p class="text-xs text-gray-600">Accurately describe item condition and flaws</p>
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
