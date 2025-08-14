@php
use Illuminate\Support\Facades\DB;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $auction->title }} - KlikBid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .auction-image {
            min-height: 400px;
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
                    @guest
                        <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Login</a>
                        <a href="{{ route('register') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Register</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</button>
                        </form>
                    @endguest
                </nav>
            </div>
        </div>
    </header>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-4 mt-4 rounded">
            <div class="flex items-center">
                <span class="text-2xl mr-3">✅</span>
                <div>
                    <p class="font-medium">{{ session('success') }}</p>
                    <p class="text-sm">You will receive an email notification once your auction is reviewed and approved.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Auction Details -->
            <div class="lg:col-span-2">
                <!-- Auction Images -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                    @if($auction->images && count($auction->images) > 0)
                        <div class="relative">
                            <!-- Main Image -->
                            <img src="{{ asset('storage/' . $auction->images[0]) }}"
                                alt="{{ $auction->title }}"
                                class="w-full h-96 object-contain bg-gray-100">

                            <!-- Image Counter Badge -->
                            @if(count($auction->images) > 1)
                                <div class="absolute bottom-4 right-4 bg-black bg-opacity-70 text-white px-3 py-1 rounded-lg text-sm font-medium">
                                    📸 {{ count($auction->images) }} photos
                                </div>
                            @endif

                            <!-- Image Gallery Thumbnails (if multiple images) -->
                            @if(count($auction->images) > 1)
                                <div class="absolute bottom-4 left-4 flex space-x-2">
                                    @foreach(array_slice($auction->images, 1, 4) as $index => $image)
                                        <div class="w-12 h-12 rounded border-2 border-white overflow-hidden">
                                            <img src="{{ asset('storage/' . $auction->images[0]) }}"
                                                alt="{{ $auction->title }}"
                                                class="w-full h-96 object-contain bg-gray-100">
                                        </div>
                                        @if($index == 3 && count($auction->images) > 5)
                                            <div class="w-12 h-12 rounded border-2 border-white bg-black bg-opacity-70 flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">+{{ count($auction->images) - 5 }}</span>
                                            </div>
                                            @break
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="auction-image bg-gray-200 flex items-center justify-center min-h-[400px]">
                            <div class="text-center">
                                <span class="text-6xl">📷</span>
                                <p class="text-gray-600 mt-2">No images uploaded</p>
                                <p class="text-sm text-gray-500">Category: {{ $auction->category->name }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Auction Information -->
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $auction->title }}</h1>
                    <div class="flex items-center space-x-3">
                        <!-- Edit Button (only for pending auctions by owner) -->
                        @auth
                            @if($auction->user_id === Auth::id() && $auction->status === 'pending_approval')
                                <a href="{{ route('auctions.edit', $auction) }}"
                                class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 text-sm font-medium">
                                    ✏️ Edit Auction
                                </a>
                            @endif
                        @endauth

                        <!-- Status Badge -->
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($auction->status == 'pending_approval') bg-yellow-100 text-yellow-800
                            @elseif($auction->status == 'active') bg-green-100 text-green-800
                            @elseif($auction->status == 'ended') bg-gray-100 text-gray-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $auction->status)) }}
                        </span>
                    </div>
                </div>

                <!-- Auction Schedule -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Auction Schedule</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Starts</p>
                            <p class="font-medium">{{ $auction->start_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Ends</p>
                            <p class="font-medium">{{ $auction->end_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-blue-50 rounded">
                        <p class="text-sm text-blue-800">
                            ⏰ Duration: {{ $auction->start_at->diffInDays($auction->end_at) }} days,
                            {{ $auction->start_at->diffInHours($auction->end_at) % 24 }} hours
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Bidding Info -->
            <div class="lg:col-span-1">
                <!-- Current Bid Status -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Current Bid Status</h3>

                    @php
                        $currentBid = $auction->bids()->where('is_highest_snapshot', true)->first();
                        $currentAmount = $currentBid ? $currentBid->bid_amount : $auction->base_price;
                        $minIncrement = max(ceil($currentAmount * 0.01), 100);
                        $minNextBid = $currentAmount + $minIncrement;
                    @endphp

                    <!-- Current High Bid -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">
                            @if($currentBid)
                                Current Highest Bid
                            @else
                                Starting Price
                            @endif
                        </p>
                        <p class="text-3xl font-bold text-green-600">Rs {{ number_format($currentAmount / 100, 0) }}</p>
                        @if($currentBid)
                            <p class="text-xs text-gray-500">by {{ $currentBid->user->name }} • {{ $currentBid->created_at->diffForHumans() }}</p>
                        @endif
                    </div>

                    <!-- Next Minimum Bid -->
                    @if($auction->status == 'active')
                        <div class="mb-4 p-3 bg-blue-50 rounded">
                            <p class="text-sm text-blue-600 font-medium">Next Minimum Bid</p>
                            <p class="text-lg font-bold text-blue-800">Rs {{ number_format($minNextBid / 100, 0) }}</p>
                            <p class="text-xs text-blue-600">Minimum increase: Rs {{ number_format($minIncrement / 100, 0) }}</p>
                        </div>
                    @endif

                    <!-- Participation Deposit -->
                    <div class="mb-6 p-4 bg-gray-50 rounded">
                        <p class="text-sm font-medium text-gray-700 mb-2">Participation Deposit</p>
                        <p class="text-lg font-bold text-purple-600">
                            @if($auction->deposit_amount > 0)
                                Rs {{ number_format($auction->deposit_amount / 100, 0) }}
                            @else
                                No deposit required
                            @endif
                        </p>
                        <p class="text-xs text-gray-600 mt-1">Refundable after auction ends</p>
                    </div>

                    @auth
                        @php
                            $userJoined = DB::table('auction_participants')
                                ->where('auction_id', $auction->id)
                                ->where('user_id', Auth::id())
                                ->where('status', 'held')
                                ->exists();

                            $userIsHighestBidder = $currentBid && $currentBid->user_id === Auth::id();
                        @endphp

                        @if($auction->status == 'pending_approval')
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-3">⏳</span>
                                    <div>
                                        <p class="font-medium text-yellow-800">Under Review</p>
                                        <p class="text-sm text-yellow-700">This auction is being reviewed by our team.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($auction->status == 'scheduled')
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-3">📅</span>
                                    <div>
                                        <p class="font-medium text-blue-800">Auction Approved!</p>
                                        <p class="text-sm text-blue-700">Bidding will start at the scheduled time.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($auction->status == 'active')
                            @if(!$userJoined)
                                <!-- Join Auction Button -->
                                <a href="{{ route('bidding.join', $auction) }}"
                                class="block w-full bg-purple-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-purple-700 text-center mb-3">
                                    💳 Join Auction (Pay Deposit)
                                </a>
                                <p class="text-xs text-gray-600 text-center mb-4">
                                    You must join the auction first to place bids
                                </p>
                            @else
                                @if($userIsHighestBidder)
                                    <div class="p-4 bg-green-50 border border-green-200 rounded mb-4">
                                        <div class="flex items-center">
                                            <span class="text-2xl mr-3">🏆</span>
                                            <div>
                                                <p class="font-medium text-green-800">You're Winning!</p>
                                                <p class="text-sm text-green-700">You have the highest bid</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Bidding Form -->
                                <form method="POST" action="{{ route('bidding.place-bid', $auction) }}" class="mb-4">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="bid_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                            Your Bid Amount (LKR)
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-3 text-gray-500">Rs</span>
                                            <input type="number"
                                                id="bid_amount"
                                                name="bid_amount"
                                                min="{{ $minNextBid / 100 }}"
                                                step="1"
                                                value="{{ $minNextBid / 100 }}"
                                                class="w-full pl-8 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="{{ number_format($minNextBid / 100, 0) }}">
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Minimum: Rs {{ number_format($minNextBid / 100, 0) }}
                                        </p>
                                    </div>

                                    <!-- Quick Bid Buttons -->
                                    <div class="grid grid-cols-3 gap-2 mb-4">
                                        <button type="button"
                                                onclick="setBidAmount({{ ($minNextBid + ($minIncrement * 1)) / 100 }})"
                                                class="bg-gray-100 text-gray-700 py-2 px-3 rounded text-sm hover:bg-gray-200">
                                            +Rs {{ number_format($minIncrement / 100, 0) }}
                                        </button>
                                        <button type="button"
                                                onclick="setBidAmount({{ ($minNextBid + ($minIncrement * 5)) / 100 }})"
                                                class="bg-gray-100 text-gray-700 py-2 px-3 rounded text-sm hover:bg-gray-200">
                                            +Rs {{ number_format(($minIncrement * 5) / 100, 0) }}
                                        </button>
                                        <button type="button"
                                                onclick="setBidAmount({{ ($minNextBid + ($minIncrement * 10)) / 100 }})"
                                                class="bg-gray-100 text-gray-700 py-2 px-3 rounded text-sm hover:bg-gray-200">
                                            +Rs {{ number_format(($minIncrement * 10) / 100, 0) }}
                                        </button>
                                    </div>

                                    <button type="submit"
                                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                                        🎯 Place Bid
                                    </button>
                                </form>
                            @endif

                            @if($auction->buy_now_price)
                                <button class="w-full bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 mb-3">
                                    ⚡ Buy Now - Rs {{ number_format($auction->buy_now_price / 100, 0) }}
                                </button>
                            @endif
                        @else
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-3">⏰</span>
                                    <div>
                                        <p class="font-medium text-gray-800">Auction Not Active</p>
                                        <p class="text-sm text-gray-600">Bidding is not currently available.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded text-center">
                            <p class="text-blue-800 font-medium mb-2">Want to bid on this auction?</p>
                            <a href="{{ route('login') }}"
                            class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                Login to Bid
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Auction Statistics -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Auction Statistics</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Bids</span>
                            <span class="font-medium">{{ $auction->bids->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Participants</span>
                            <span class="font-medium">{{ DB::table('auction_participants')->where('auction_id', $auction->id)->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Time Left</span>
                            <span class="font-medium text-red-600">
                                @if($auction->status == 'active')
                                    @php
                                        // Calculate time remaining properly
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
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>

                    @if($auction->bids->count() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('bidding.history', $auction) }}"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                View Bid History →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

<script>
function setBidAmount(amount) {
    document.getElementById('bid_amount').value = amount;
}
</script>
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
