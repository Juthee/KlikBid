<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KlikBid</title>
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
                        <span class="ml-2 text-sm text-gray-500">Admin Panel</span>
                    </a>
                </div>

                <!-- Admin Navigation -->
                <nav class="hidden md:flex space-x-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-600 font-medium">Dashboard</a>
                    <a href="{{ route('admin.auctions.pending') }}" class="text-gray-700 hover:text-blue-600">Pending Auctions</a>
                    <a href="{{ route('admin.auctions.index') }}" class="text-gray-700 hover:text-blue-600">All Auctions</a>
                    <a href="{{ route('admin.users.index') }}" class="text-gray-700 hover:text-blue-600">Users</a>
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600">Public Site</a>
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
        <!-- Page Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Admin Dashboard</h2>
            <p class="mt-2 text-gray-600">Overview of your KlikBid auction platform</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pending Auctions -->
            <div class="stat-card bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <span class="text-2xl">⏳</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Approval</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $pendingAuctions }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.auctions.pending') }}"
                       class="text-sm text-yellow-600 hover:text-yellow-800 font-medium">
                        Review pending →
                    </a>
                </div>
            </div>

            <!-- Active Auctions -->
            <div class="stat-card bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <span class="text-2xl">🎯</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Auctions</p>
                        <p class="text-2xl font-bold text-green-600">{{ $activeAuctions }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.auctions.index') }}"
                       class="text-sm text-green-600 hover:text-green-800 font-medium">
                        View all auctions →
                    </a>
                </div>
            </div>

            <!-- Total Users -->
            <div class="stat-card bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <span class="text-2xl">👥</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $totalUsers }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users.index') }}"
                       class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Manage users →
                    </a>
                </div>
            </div>

            <!-- Total Auctions -->
            <div class="stat-card bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <span class="text-2xl">📦</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Auctions</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $totalAuctions }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.auctions.index') }}"
                       class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                        View details →
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Pending Auctions -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Pending Auctions</h3>
                    <a href="{{ route('admin.auctions.pending') }}"
                       class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        View all pending
                    </a>
                </div>
            </div>

            @if($recentAuctions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Auction
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Seller
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Starting Price
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Submitted
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentAuctions as $auction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ Str::limit($auction->title, 40) }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $auction->district ? $auction->district . ', ' . $auction->province : 'No location' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $auction->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $auction->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $auction->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            Rs {{ number_format($auction->base_price / 100, 0) }}
                                        </div>
                                        @if($auction->reserve_price)
                                            <div class="text-xs text-gray-500">
                                                Reserve: Rs {{ number_format($auction->reserve_price / 100, 0) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $auction->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.auctions.show', $auction) }}"
                                               class="bg-blue-100 text-blue-800 px-3 py-1 rounded text-xs hover:bg-blue-200">
                                                Review
                                            </a>
                                            <form method="POST" action="{{ route('admin.auctions.approve', $auction) }}" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="bg-green-100 text-green-800 px-3 py-1 rounded text-xs hover:bg-green-200"
                                                        onclick="return confirm('Approve this auction?')">
                                                    Approve
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-8 text-center">
                    <div class="text-gray-400 text-4xl mb-4">📝</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No pending auctions</h3>
                    <p class="text-gray-500">All auctions have been reviewed. Great job!</p>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h4>
                <div class="space-y-2">
                    <a href="{{ route('admin.auctions.pending') }}"
                       class="block w-full bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 text-center">
                        Review Pending Auctions
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="block w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 text-center">
                        Manage Users
                    </a>
                    <a href="{{ url('/') }}"
                       class="block w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 text-center">
                        View Public Site
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Platform Health</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Server Status</span>
                        <span class="text-green-600 font-medium">✅ Online</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Database</span>
                        <span class="text-green-600 font-medium">✅ Connected</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Storage</span>
                        <span class="text-green-600 font-medium">✅ Available</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h4>
                <div class="space-y-3 text-sm">
                    @if($recentAuctions->count() > 0)
                        @foreach($recentAuctions->take(3) as $auction)
                            <div class="border-l-2 border-blue-500 pl-3">
                                <p class="text-gray-900">New auction: {{ Str::limit($auction->title, 25) }}</p>
                                <p class="text-gray-500 text-xs">{{ $auction->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No recent activity</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2025 KlikBid Admin Panel. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
