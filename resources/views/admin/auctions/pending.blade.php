<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Auctions - KlikBid Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Pending Auctions</h1>
            <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Back to Dashboard
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Pending Auctions List -->
        @if(isset($auctions) && $auctions->count() > 0)
            <div class="bg-white rounded-lg shadow-lg">
                @foreach($auctions as $auction)
                <div class="p-6 border-b border-gray-200 last:border-b-0">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Auction Info -->
                        <div class="lg:col-span-2">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $auction->title }}</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                <div>
                                    <span class="font-medium">Base Price:</span>
                                    Rs {{ number_format($auction->base_price / 100, 0) }}
                                </div>
                                <div>
                                    <span class="font-medium">Category:</span>
                                    {{ $auction->category->name ?? 'N/A' }}
                                </div>
                                <div>
                                    <span class="font-medium">Seller:</span>
                                    {{ $auction->user->name ?? 'N/A' }}
                                </div>
                                <div>
                                    <span class="font-medium">Created:</span>
                                    {{ $auction->created_at->format('M j, Y g:i A') }}
                                </div>
                            </div>

                            @if($auction->description)
                                <div class="mt-3">
                                    <span class="font-medium text-gray-700">Description:</span>
                                    <p class="text-gray-600 text-sm mt-1">{{ Str::limit($auction->description, 150) }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col justify-center space-y-3">
                            <form method="POST" action="{{ route('admin.auctions.approve', $auction) }}" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium transition-colors"
                                        onclick="return confirm('Are you sure you want to approve this auction?')">
                                    ✅ Approve Auction
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.auctions.reject', $auction) }}" class="w-full">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium transition-colors"
                                        onclick="return confirm('Are you sure you want to reject this auction?')">
                                    ❌ Reject Auction
                                </button>
                            </form>

                            <a href="{{ route('admin.auctions.show', $auction) }}"
                               class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium text-center transition-colors">
                                👁️ View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $auctions->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <div class="text-6xl mb-4">📋</div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">No Pending Auctions</h2>
                <p class="text-gray-600 mb-6">All auctions have been reviewed. Check back later for new submissions.</p>
                <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium">
                    Back to Dashboard
                </a>
            </div>
        @endif
    </div>
</body>
</html>
