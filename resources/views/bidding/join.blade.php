<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Auction - {{ $auction->title }} - KlikBid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-blue-600">Home</a>
                <span>→</span>
                <a href="{{ route('auctions.show', $auction) }}" class="hover:text-blue-600">{{ Str::limit($auction->title, 30) }}</a>
                <span>→</span>
                <span class="text-gray-900">Join Auction</span>
            </div>
        </nav>

        <!-- Page Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Join Auction</h2>
            <p class="text-gray-600">Pay the participation deposit to start bidding</p>
        </div>

        <!-- Error/Success Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                {{ session('info') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Auction Summary -->
            <div class="lg:col-span-2">
                <!-- Auction Details Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $auction->title }}</h3>
                        <div class="flex items-center mt-2 text-sm text-gray-600">
                            <span class="mr-4">🏷️ {{ $auction->category->name }}</span>
                            @if($auction->district)
                                <span class="mr-4">📍 {{ $auction->district }}, {{ $auction->province }}</span>
                            @endif
                            <span>👤 {{ $auction->user->name }}</span>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-600 font-medium">Starting Price</p>
                            <p class="text-lg font-bold text-blue-800">Rs {{ number_format($auction->base_price / 100, 0) }}</p>
                        </div>

                        @if($auction->reserve_price)
                            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                <p class="text-sm text-yellow-600 font-medium">Reserve Price</p>
                                <p class="text-lg font-bold text-yellow-800">Rs {{ number_format($auction->reserve_price / 100, 0) }}</p>
                            </div>
                        @endif

                        @if($auction->buy_now_price)
                            <div class="text-center p-4 bg-green-50 rounded-lg">
                                <p class="text-sm text-green-600 font-medium">Buy Now Price</p>
                                <p class="text-lg font-bold text-green-800">Rs {{ number_format($auction->buy_now_price / 100, 0) }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Auction Schedule -->
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="font-medium text-gray-900 mb-3">Auction Schedule</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Starts</p>
                                <p class="font-medium">{{ $auction->start_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Ends</p>
                                <p class="font-medium">{{ $auction->end_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Why Join This Auction -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Why Join This Auction?</h4>
                    <div class="space-y-3 text-sm text-gray-700">
                        <div class="flex items-start">
                            <span class="text-green-600 mr-3 mt-0.5">✓</span>
                            <span>Secure bidding with deposit protection</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-green-600 mr-3 mt-0.5">✓</span>
                            <span>Your deposit is fully refundable if you don't win</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-green-600 mr-3 mt-0.5">✓</span>
                            <span>Only serious bidders can participate</span>
                        </div>
                        <div class="flex items-start">
                            <span class="text-green-600 mr-3 mt-0.5">✓</span>
                            <span>Fair bidding with minimum increment rules</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Deposit Payment -->
            <div class="lg:col-span-1">
                <!-- Participation Deposit Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">💳</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Participation Deposit</h3>
                        <p class="text-sm text-gray-600 mt-1">Required to join this auction</p>
                    </div>

                    <!-- Deposit Amount -->
                    <div class="text-center mb-6 p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-purple-600 font-medium">Deposit Amount</p>
                        <p class="text-3xl font-bold text-purple-800">
                            @if($auction->deposit_amount > 0)
                                Rs {{ number_format($auction->deposit_amount / 100, 0) }}
                            @else
                                FREE
                            @endif
                        </p>
                        <p class="text-xs text-purple-600 mt-1">
                            @if($auction->deposit_amount > 0)
                                Fully refundable
                            @else
                                No deposit required for this auction
                            @endif
                        </p>
                    </div>

                    <!-- Payment Form -->
                    <form method="POST" action="{{ route('bidding.process-join', $auction) }}">
                        @csrf

                        @if($auction->deposit_amount > 0)
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-900 mb-3">Payment Method</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="card" class="mr-3" checked>
                                        <div>
                                            <p class="font-medium">Credit/Debit Card</p>
                                            <p class="text-xs text-gray-500">Visa, MasterCard accepted</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="bank" class="mr-3">
                                        <div>
                                            <p class="font-medium">Bank Transfer</p>
                                            <p class="text-xs text-gray-500">Direct bank payment</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @endif

                        <!-- Terms Agreement -->
                        <div class="mb-6">
                            <label class="flex items-start">
                                <input type="checkbox" name="agree_terms" class="mt-1 mr-3" required>
                                <span class="text-sm text-gray-700">
                                    I agree to the <a href="#" class="text-blue-600 hover:underline">auction terms</a>
                                    and understand that my deposit will be held until the auction ends.
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-purple-700 transition duration-200">
                            @if($auction->deposit_amount > 0)
                                💳 Pay Deposit & Join Auction
                            @else
                                🎯 Join Auction (Free)
                            @endif
                        </button>
                    </form>

                    <!-- Security Note -->
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="mr-2">🔒</span>
                            <span>Secure payment processing with bank-level encryption</span>
                        </div>
                    </div>
                </div>

                <!-- Deposit Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="font-medium text-gray-900 mb-3">Deposit Information</h4>
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Deposit Amount:</span>
                            <span class="font-medium">
                                @if($auction->deposit_amount > 0)
                                    Rs {{ number_format($auction->deposit_amount / 100, 0) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Refund Policy:</span>
                            <span class="font-medium text-green-600">Full refund if you don't win</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Processing Time:</span>
                            <span class="font-medium">Instant</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-200">
                        <p class="text-xs text-gray-500">
                            * If you win the auction, your deposit will be applied toward the final payment or refunded based on auction terms.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-8">
            <a href="{{ route('auctions.show', $auction) }}"
               class="inline-flex items-center text-gray-600 hover:text-gray-800">
                <span class="mr-2">←</span>
                Back to Auction Details
            </a>
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
