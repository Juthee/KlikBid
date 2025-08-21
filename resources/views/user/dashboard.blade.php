<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - KlikBid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card:hover {
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
                    <a href="{{ route('user.dashboard') }}" class="text-blue-600 font-medium">Dashboard</a>
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
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h2>
            <p class="mt-2 text-gray-600">Here's your auction activity overview</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Active Bids -->
            <div class="stat-card bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Bids</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['active_bids'] }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('user.my-bids') }}"
                       class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        View all bids →
                    </a>
                </div>
            </div>

            <!-- My Listings -->
            <div class="stat-card bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <span class="text-2xl">📦</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">My Listings</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['total_listings'] }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('user.my-listings') }}"
                       class="text-sm text-green-600 hover:text-green-800 font-medium">
                        Manage listings →
                    </a>
                </div>
            </div>

            <!-- Won Auctions -->
            <div class="stat-card bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <span class="text-2xl">🏆</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Won Auctions</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['won_auctions'] }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('user.won-auctions') }}"
                       class="text-sm text-yellow-600 hover:text-yellow-800 font-medium">
                        View won items →
                    </a>
                </div>
            </div>

            <!-- Total Bids Placed -->
            <div class="stat-card bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <span class="text-2xl">💫</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Bids</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['total_bids_placed'] }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('user.my-bids') }}"
                       class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                        View history →
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('auctions.create') }}"
                           class="block w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 text-center">
                            📦 Post New Auction
                        </a>
                        <a href="{{ route('user.my-bids') }}"
                           class="block w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 text-center">
                            🎯 View My Bids
                        </a>
                        <a href="{{ route('user.profile') }}"
                           class="block w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 text-center">
                            👤 Edit Profile
                        </a>
                        <a href="{{ url('/') }}"
                           class="block w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 text-center">
                            🏠 Browse Auctions
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Bids -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Bids</h3>
                            <a href="{{ route('user.my-bids') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
                        </div>

                        @if($recentBids->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentBids->take(4) as $bid)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm">{{ Str::limit($bid->auction->title, 25) }}</p>
                                            <p class="text-xs text-gray-500">{{ $bid->auction->category->name }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-sm text-green-600">Rs {{ number_format($bid->bid_amount / 100, 0) }}</p>
                                            <p class="text-xs text-gray-500">{{ $bid->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <span class="text-4xl mb-2 block">🎯</span>
                                <p>No bids placed yet</p>
                                <a href="{{ url('/') }}" class="text-blue-600 hover:underline text-sm">Browse auctions to start bidding</a>
                            </div>
                        @endif
                    </div>

                    <!-- Recent Listings -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">My Recent Listings</h3>
                            <a href="{{ route('user.my-listings') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
                        </div>

                        @if($recentListings->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentListings->take(4) as $listing)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <p class="font-medium text-sm">{{ Str::limit($listing->title, 25) }}</p>
                                            <div class="flex items-center space-x-2 text-xs">
                                                <span class="text-gray-500">{{ $listing->category->name }}</span>
                                                <span class="px-2 py-1 rounded text-xs
                                                    @if($listing->status == 'pending_approval') bg-yellow-100 text-yellow-800
                                                    @elseif($listing->status == 'active') bg-green-100 text-green-800
                                                    @elseif($listing->status == 'scheduled') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $listing->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-sm text-blue-600">Rs {{ number_format($listing->base_price / 100, 0) }}</p>
                                            <p class="text-xs text-gray-500">{{ $listing->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <span class="text-4xl mb-2 block">📦</span>
                                <p>No listings created yet</p>
                                <a href="{{ route('auctions.create') }}" class="text-blue-600 hover:underline text-sm">Post your first auction</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Full Width Security Section -->
        <div class="mb-12">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-xl p-8 text-white">
                <div class="flex flex-col lg:flex-row items-center justify-between mb-8">
                    <div class="text-center lg:text-left mb-4 lg:mb-0">
                        <h2 class="text-3xl font-bold mb-2">🔒 Account Security Center</h2>
                        <p class="text-blue-100 text-lg">Protect your KlikBid account with enterprise-level security</p>
                    </div>
                    @if(Auth::user()->google2fa_enabled)
                        <div class="px-6 py-3 bg-green-500 text-white font-bold rounded-full text-lg">
                            ✅ PROTECTED
                        </div>
                    @else
                        <div class="px-6 py-3 bg-red-500 text-white font-bold rounded-full text-lg animate-pulse">
                            ⚠️ VULNERABLE
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @if(!Auth::user()->google2fa_enabled)
                        <!-- Enable 2FA Card -->
                        <div class="bg-white rounded-xl p-8 text-gray-900">
                            <div class="text-center mb-6">
                                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-4xl">🛡️</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Enable Two-Factor Authentication</h3>
                                <p class="text-gray-600 text-lg leading-relaxed">
                                    Add an extra layer of security to your auction account. Protect your bids, winnings, and personal information.
                                </p>
                            </div>

                            <div class="space-y-4 mb-6">
                                <div class="flex items-center space-x-3 text-gray-700">
                                    <span class="text-green-500 text-xl">✓</span>
                                    <span>Prevent unauthorized access</span>
                                </div>
                                <div class="flex items-center space-x-3 text-gray-700">
                                    <span class="text-green-500 text-xl">✓</span>
                                    <span>Secure your auction winnings</span>
                                </div>
                                <div class="flex items-center space-x-3 text-gray-700">
                                    <span class="text-green-500 text-xl">✓</span>
                                    <span>ISO 27001 security compliant</span>
                                </div>
                            </div>

                            <a href="{{ route('2fa.setup') }}"
                               class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white text-xl font-bold py-4 px-6 rounded-xl hover:from-blue-700 hover:to-purple-700 text-center transition duration-200 transform hover:scale-105">
                                🔧 Setup 2FA Now - FREE
                            </a>
                        </div>
                    @else
                        <!-- 2FA Enabled Card -->
                        <div class="bg-white rounded-xl p-8 text-gray-900">
                            <div class="text-center mb-6">
                                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-4xl">✅</span>
                                </div>
                                <h3 class="text-2xl font-bold text-green-800 mb-2">2FA Protection Active</h3>
                                <p class="text-gray-600 text-lg">
                                    Enabled on {{ Auth::user()->google2fa_enabled_at->format('M j, Y') }}
                                </p>
                            </div>

                            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                                <p class="font-medium text-gray-700 mb-4">To disable 2FA, enter your authentication code:</p>
                                <form method="POST" action="{{ route('2fa.disable') }}" class="space-y-4">
                                    @csrf
                                    <input type="text"
                                           name="one_time_password"
                                           placeholder="Enter 6-digit code"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-lg text-center tracking-widest"
                                           maxlength="6"
                                           required>
                                    <button type="submit"
                                            class="w-full bg-red-600 text-white font-bold py-3 rounded-lg hover:bg-red-700"
                                            onclick="return confirm('Are you sure? This will make your account less secure.')">
                                        Disable 2FA
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <!-- Security Tips Card -->
                    <div class="bg-white bg-opacity-10 backdrop-blur rounded-xl p-8">
                        <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                            <span class="mr-3">💡</span> Security Best Practices
                        </h3>
                        <div class="space-y-4 text-white">
                            <div class="flex items-start space-x-3">
                                <span class="text-yellow-300 text-xl mt-1">🔐</span>
                                <div>
                                    <p class="font-semibold">Use Strong Passwords</p>
                                    <p class="text-blue-100 text-sm">12+ characters with numbers, symbols</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="text-yellow-300 text-xl mt-1">📱</span>
                                <div>
                                    <p class="font-semibold">Enable 2FA Protection</p>
                                    <p class="text-blue-100 text-sm">Google Authenticator or similar app</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="text-yellow-300 text-xl mt-1">👀</span>
                                <div>
                                    <p class="font-semibold">Monitor Account Activity</p>
                                    <p class="text-blue-100 text-sm">Check for unauthorized access</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="text-yellow-300 text-xl mt-1">🚪</span>
                                <div>
                                    <p class="font-semibold">Secure Logout</p>
                                    <p class="text-blue-100 text-sm">Always logout from public devices</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Navigation -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Dashboard Navigation</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('user.my-listings') }}"
                   class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <span class="text-2xl mb-2">📦</span>
                    <span class="font-medium text-blue-800">My Listings</span>
                    <span class="text-xs text-blue-600">Manage your auctions</span>
                </a>

                <a href="{{ route('user.my-bids') }}"
                   class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                    <span class="text-2xl mb-2">🎯</span>
                    <span class="font-medium text-green-800">My Bids</span>
                    <span class="text-xs text-green-600">Track bidding activity</span>
                </a>

                <a href="{{ route('user.won-auctions') }}"
                   class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                    <span class="text-2xl mb-2">🏆</span>
                    <span class="font-medium text-yellow-800">Won Auctions</span>
                    <span class="text-xs text-yellow-600">Your winning bids</span>
                </a>

                <a href="{{ route('user.profile') }}"
                   class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                    <span class="text-2xl mb-2">👤</span>
                    <span class="font-medium text-purple-800">Profile</span>
                    <span class="text-xs text-purple-600">Account settings</span>
                </a>
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
