<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - KlikBid</title>
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
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('user.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <span>→</span>
                <span class="text-gray-900">Profile</span>
            </div>
        </nav>

        <!-- Page Header -->
        <div class="text-center mb-8">
            <div class="relative inline-block">
                <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="absolute bottom-0 right-0 w-8 h-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                    <span class="text-white text-xs">✓</span>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mt-4">{{ $user->name }}</h2>
            <p class="text-gray-600">Member since {{ $user->created_at->format('F Y') }}</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Profile Information</h3>

                    <form method="POST" action="{{ route('user.profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <!-- Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Your full name as it appears on official documents</p>
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">We'll send important auction notifications to this email</p>
                        </div>

                        <!-- Phone (Optional for now) -->
                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="+94 77 123 4567">
                            <p class="text-xs text-gray-500 mt-1">Optional: For important auction notifications</p>
                        </div>

                        <!-- Address (Optional for now) -->
                        <div class="mb-6">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea id="address" name="address" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Your address in Sri Lanka">{{ old('address', '') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Optional: Helps with local auction recommendations</p>
                        </div>

                        <!-- Email Preferences -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Email Preferences</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="email_outbid" class="mr-3" checked>
                                    <span class="text-sm text-gray-700">Notify me when I'm outbid on an auction</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="email_ending" class="mr-3" checked>
                                    <span class="text-sm text-gray-700">Notify me when auctions I'm bidding on are ending soon</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="email_won" class="mr-3" checked>
                                    <span class="text-sm text-gray-700">Notify me when I win an auction</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="email_marketing" class="mr-3">
                                    <span class="text-sm text-gray-700">Send me promotional emails about new auctions</span>
                                </label>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('user.dashboard') }}"
                               class="text-gray-600 hover:text-gray-800">← Back to Dashboard</a>
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold">
                                💾 Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Change Password</h3>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="mb-4">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                            <input type="password" id="current_password" name="current_password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <!-- New Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                            <input type="password" id="password" name="password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <button type="submit"
                                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-semibold">
                            🔒 Update Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Profile Stats & Info -->
            <div class="lg:col-span-1">
                <!-- Account Status -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Status</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Email Verified</span>
                            <span class="text-green-600 font-medium">✅ Verified</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">KYC Status</span>
                            <span class="text-yellow-600 font-medium">⏳ Pending</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Account Type</span>
                            <span class="text-blue-600 font-medium">🙋 Standard User</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Member Since</span>
                            <span class="text-gray-700 font-medium">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button class="w-full bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 text-sm"
                                onclick="alert('KYC verification will be available when payment integration is added!')">
                            📋 Complete KYC Verification
                        </button>
                    </div>
                </div>

                <!-- Activity Summary -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Activity Summary</h3>

                    @php
                        $userStats = [
                            'auctions_created' => \App\Models\Auction::where('user_id', $user->id)->count(),
                            'bids_placed' => \App\Models\Bid::where('user_id', $user->id)->count(),
                            'auctions_won' => \App\Models\Auction::where('winner_user_id', $user->id)->count(),
                            'total_spent' => \App\Models\Auction::where('winner_user_id', $user->id)->sum('winning_bid_amount') / 100,
                        ];
                    @endphp

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Auctions Created</span>
                            <span class="text-blue-600 font-bold">{{ $userStats['auctions_created'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Bids Placed</span>
                            <span class="text-green-600 font-bold">{{ $userStats['bids_placed'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Auctions Won</span>
                            <span class="text-yellow-600 font-bold">{{ $userStats['auctions_won'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Spent</span>
                            <span class="text-purple-600 font-bold">Rs {{ number_format($userStats['total_spent'], 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>

                    <div class="space-y-3">
                        <a href="{{ route('auctions.create') }}"
                           class="block w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 text-center text-sm">
                            📦 Post New Auction
                        </a>
                        <a href="{{ url('/') }}"
                           class="block w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 text-center text-sm">
                            🎯 Browse Auctions
                        </a>
                        <a href="{{ route('user.my-bids') }}"
                           class="block w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 text-center text-sm">
                            📊 View My Bids
                        </a>
                    </div>
                </div>

                <!-- Support -->
                <div class="bg-gray-50 rounded-lg p-6 mt-6">
                    <h4 class="font-medium text-gray-900 mb-2">Need Help?</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Contact our support team if you have any questions about your account or auctions.
                    </p>
                    <button class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 text-sm"
                            onclick="alert('Support system coming soon! For now, you can report issues via email.')">
                        📧 Contact Support
                    </button>
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
