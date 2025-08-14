<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review: {{ $auction->title }} - KlikBid Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <h1 class="text-2xl font-bold text-blue-600">KlikBid</h1>
                        <span class="ml-2 text-sm text-gray-500">Admin Panel</span>
                    </a>
                </div>
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    <a href="{{ route('admin.auctions.pending') }}" class="text-gray-700 hover:text-blue-600">Pending Auctions</a>
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600">Public Site</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-4 mt-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Review Auction</h2>
                    <p class="mt-2 text-gray-600">Review and approve this auction submission</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.auctions.pending') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                        ← Back to Pending
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Auction Details -->
            <div class="lg:col-span-2">
                <!-- Auction Status Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold">{{ $auction->title }}</h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($auction->status == 'pending_approval') bg-yellow-100 text-yellow-800
                            @elseif($auction->status == 'active') bg-green-100 text-green-800
                            @elseif($auction->status == 'scheduled') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $auction->status)) }}
                        </span>
                    </div>

                    <!-- Seller Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Seller Information</h4>
                            <p class="text-sm text-gray-600">Name: {{ $auction->user->name }}</p>
                            <p class="text-sm text-gray-600">Email: {{ $auction->user->email }}</p>
                            <p class="text-sm text-gray-600">Joined: {{ $auction->user->created_at->format('M j, Y') }}</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Auction Details</h4>
                            <p class="text-sm text-gray-600">Category: {{ $auction->category->name }}</p>
                            <p class="text-sm text-gray-600">Created: {{ $auction->created_at->format('M j, Y g:i A') }}</p>
                            <p class="text-sm text-gray-600">ID: #{{ $auction->id }}</p>
                        </div>
                    </div>

                    <!-- Location -->
                    @if($auction->address_line || $auction->district)
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-2">Location</h4>
                            <p class="text-sm text-gray-700">
                                @if($auction->address_line){{ $auction->address_line }}@endif
                                @if($auction->district), {{ $auction->district }}@endif
                                @if($auction->province), {{ $auction->province }}@endif
                            </p>
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-2">Item Description</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">{{ $auction->description }}</p>
                        </div>
                    </div>

                    <!-- Auction Schedule -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-2">Auction Schedule</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-3 rounded">
                                <p class="text-sm text-blue-600 font-medium">Start Date</p>
                                <p class="text-blue-800">{{ $auction->start_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="bg-red-50 p-3 rounded">
                                <p class="text-sm text-red-600 font-medium">End Date</p>
                                <p class="text-red-800">{{ $auction->end_at->format('M j, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            Duration: {{ $auction->start_at->diffInDays($auction->end_at) }} days,
                            {{ $auction->start_at->diffInHours($auction->end_at) % 24 }} hours
                        </p>
                    </div>

                    <!-- Pricing Information -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-green-50 p-4 rounded">
                            <p class="text-sm text-green-600 font-medium">Base Price</p>
                            <p class="text-xl font-bold text-green-800">Rs {{ number_format($auction->base_price / 100, 0) }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded">
                            <p class="text-sm text-purple-600 font-medium">Deposit Required</p>
                            <p class="text-xl font-bold text-purple-800">
                                @if($auction->deposit_amount > 0)
                                    Rs {{ number_format($auction->deposit_amount / 100, 0) }}
                                @else
                                    No Deposit
                                @endif
                            </p>
                        </div>
                        @if($auction->buy_now_price)
                            <div class="bg-yellow-50 p-4 rounded">
                                <p class="text-sm text-yellow-600 font-medium">Buy Now Price</p>
                                <p class="text-xl font-bold text-yellow-800">Rs {{ number_format($auction->buy_now_price / 100, 0) }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Item Images -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h4 class="font-medium text-gray-900 mb-4">Item Images</h4>

                    @if($auction->images && count($auction->images) > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($auction->images as $index => $image)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $auction->images[0]) }}"
                                        alt="{{ $auction->title }}"
                                        class="w-full h-96 object-contain bg-gray-100">
                                    @if($index === 0)
                                        <div class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                            Main Image
                                        </div>
                                    @endif
                                    <div class="absolute top-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                        {{ $index + 1 }}/{{ count($auction->images) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Image Summary -->
                        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm text-green-800 font-medium">
                                ✅ {{ count($auction->images) }} image(s) uploaded successfully
                            </p>
                        </div>
                    @else
                        <div class="bg-gray-200 rounded-lg h-64 flex items-center justify-center">
                            <div class="text-center">
                                <span class="text-6xl">📷</span>
                                <p class="text-gray-600 mt-2">No images uploaded</p>
                                <p class="text-sm text-gray-500">Category: {{ $auction->category->name }}</p>
                            </div>
                        </div>

                        <!-- Warning for no images -->
                        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-800 font-medium">
                                ⚠️ No images found - Consider rejecting this auction as images are required
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Admin Actions -->
            <div class="lg:col-span-1">
                <!-- Admin Actions Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Admin Actions</h3>

                    @if($auction->status == 'pending_approval')
                        <!-- Approve Button -->
                        <form method="POST" action="{{ route('admin.auctions.approve', $auction) }}" class="mb-3">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Approve this auction? It will become visible to users.')"
                                    class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 font-medium">
                                ✅ Approve Auction
                            </button>
                        </form>

                        <!-- Reject Button -->
                        <button onclick="showRejectForm()"
                                class="w-full bg-red-600 text-white py-3 px-4 rounded-lg hover:bg-red-700 font-medium mb-4">
                            ❌ Reject Auction
                        </button>

                        <!-- Reject Form (Hidden by default) -->
                        <div id="rejectForm" class="hidden">
                            <form method="POST" action="{{ route('admin.auctions.reject', $auction) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Rejection Reason
                                    </label>
                                    <textarea name="rejection_reason"
                                              rows="3"
                                              required
                                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500"
                                              placeholder="Please provide a reason for rejection..."></textarea>
                                </div>
                                <div class="flex space-x-2">
                                    <button type="submit"
                                            class="flex-1 bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">
                                        Confirm Reject
                                    </button>
                                    <button type="button"
                                            onclick="hideRejectForm()"
                                            class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded hover:bg-gray-400">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 rounded text-center">
                            <p class="text-gray-600">This auction has already been reviewed.</p>
                            <p class="text-sm text-gray-500 mt-1">Status: {{ ucfirst(str_replace('_', ' ', $auction->status)) }}</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Info Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Quick Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Auction ID</span>
                            <span class="font-medium">#{{ $auction->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Category</span>
                            <span class="font-medium">{{ $auction->category->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Submitted</span>
                            <span class="font-medium">{{ $auction->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-medium">{{ $auction->start_at->diffInDays($auction->end_at) }} days</span>
                        </div>
                    </div>
                </div>

                <!-- View Public Preview -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Preview</h3>
                    <a href="{{ route('auctions.show', $auction) }}"
                       target="_blank"
                       class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 font-medium text-center block">
                        👁️ View as User
                    </a>
                    <p class="text-xs text-gray-500 mt-2 text-center">Opens in new tab</p>
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

    <script>
        function showRejectForm() {
            document.getElementById('rejectForm').classList.remove('hidden');
        }

        function hideRejectForm() {
            document.getElementById('rejectForm').classList.add('hidden');
        }
    </script>
</body>
</html>
