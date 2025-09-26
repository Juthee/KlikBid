@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Payment Successful!</h1>
            <p class="mt-2 text-lg text-gray-600">Your payment has been processed successfully</p>
        </div>

        <!-- Payment Details Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                <h2 class="text-lg font-semibold text-green-800">Payment Confirmation</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Info -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Payment Details</h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Payment ID:</dt>
                                <dd class="text-sm font-medium text-gray-900">#{{ $payment->id }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Amount:</dt>
                                <dd class="text-sm font-medium text-gray-900">Rs {{ number_format($payment->amount / 100, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Type:</dt>
                                <dd class="text-sm font-medium text-gray-900">
                                    @if($payment->type === 'deposit')
                                        Auction Deposit
                                    @else
                                        Auction Payment
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Status:</dt>
                                <dd class="text-sm font-medium text-green-600">Completed</dd>
                            </div>
                            @if($payment->webxpay_reference)
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Reference:</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $payment->webxpay_reference }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Auction Info -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Auction Details</h3>
                        <div class="flex items-start space-x-3">
                            @if($auction->image)
                                <img src="{{ asset('storage/' . $auction->image) }}" alt="{{ $auction->title }}" class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $auction->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $auction->category }}</p>
                                <p class="text-sm text-gray-500">Ends: {{ $auction->end_time->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if($payment->type === 'deposit')
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">You've Successfully Joined the Auction!</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Your deposit has been secured and you can now place bids on this auction. The deposit will be refunded if you don't win, or applied to your purchase if you do win.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('auctions.show', $auction->id) }}"
               class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <i class="fas fa-gavel mr-2"></i>
                @if($payment->type === 'deposit')
                    Start Bidding
                @else
                    View Auction
                @endif
            </a>

            <a href="{{ route('user.dashboard') }}"
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <i class="fas fa-tachometer-alt mr-2"></i>
                Go to Dashboard
            </a>
        </div>

        <!-- Receipt Download (Future Enhancement) -->
        <div class="text-center mt-8">
            <p class="text-sm text-gray-500">A confirmation email will be sent to your registered email address.</p>
        </div>
    </div>
</div>
@endsection
