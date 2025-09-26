<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $paymentType }} - KlikBid Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to KlikBid
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Secure Payment</h1>
            <p class="text-gray-600 mt-2">Powered by WebXPay - Sri Lanka's trusted payment gateway</p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Payment Details -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-receipt text-blue-600 mr-2"></i>
                        Payment Details
                    </h2>

                    <!-- Auction Information -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="font-medium text-gray-900 mb-2">{{ $auction->title }}</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Category:</span>
                                <span class="font-medium">{{ $auction->category->name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Location:</span>
                                <span class="font-medium">{{ $auction->district }}</span>
                            </div>
                            @if($auction->base_price)
                            <div>
                                <span class="text-gray-600">Base Price:</span>
                                <span class="font-medium text-green-600">Rs {{ number_format($auction->base_price) }}</span>
                            </div>
                            @endif
                            @if(isset($winningAmount))
                            <div>
                                <span class="text-gray-600">Winning Bid:</span>
                                <span class="font-medium text-green-600">Rs {{ number_format($winningAmount) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Breakdown -->
                    <div class="border-t pt-4">
                        <h4 class="font-medium text-gray-900 mb-3">Payment Breakdown</h4>

                        @if(isset($depositAmount))
                            <!-- Deposit Payment -->
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Auction Deposit</span>
                                <span class="font-medium text-blue-600">Rs {{ number_format($depositAmount) }}</span>
                            </div>
                            <div class="text-sm text-gray-500 mb-4">
                                <i class="fas fa-info-circle mr-1"></i>
                                Refundable if you don't win the auction
                            </div>
                        @endif

                        @if(isset($winningAmount))
                            <!-- Winner Payment -->
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Winning Bid Amount</span>
                                <span class="font-medium">Rs {{ number_format($winningAmount) }}</span>
                            </div>
                            @if(isset($depositUsed) && $depositUsed > 0)
                            <div class="flex justify-between items-center py-2 text-green-600">
                                <span>Deposit Applied</span>
                                <span>- Rs {{ number_format($depositUsed) }}</span>
                            </div>
                            @endif
                            <div class="border-t border-gray-200 pt-2 mt-2">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-gray-900">Amount to Pay</span>
                                    <span class="font-bold text-xl text-blue-600">Rs {{ number_format($finalAmount ?? $winningAmount) }}</span>
                                </div>
                            </div>
                        @endif

                        @if(isset($depositAmount))
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-900">Total Amount</span>
                                <span class="font-bold text-xl text-blue-600">Rs {{ number_format($depositAmount) }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-credit-card text-green-600 mr-2"></i>
                        Payment Information
                    </h2>

                    <!-- Security Notice -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                            <div>
                                <h4 class="font-medium text-green-800">Secure Payment</h4>
                                <p class="text-sm text-green-700">Your payment is secured by 256-bit SSL encryption</p>
                            </div>
                        </div>
                    </div>

                    <!-- WebXPay Payment Form -->
                    <form action="{{ $paymentUrl }}" method="POST" id="webxpay-form">
                        @csrf

                        <!-- Hidden WebXPay Fields -->
                        @foreach($formData as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <!-- Payment Methods Info -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-3">Available Payment Methods</h4>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="bg-gray-50 p-2 rounded text-center">
                                    <i class="fas fa-credit-card text-blue-600 mb-1"></i>
                                    <div>Visa & MasterCard</div>
                                </div>
                                <div class="bg-gray-50 p-2 rounded text-center">
                                    <i class="fas fa-university text-green-600 mb-1"></i>
                                    <div>Local Banks</div>
                                </div>
                                <div class="bg-gray-50 p-2 rounded text-center">
                                    <i class="fas fa-mobile-alt text-orange-600 mb-1"></i>
                                    <div>eZ Cash</div>
                                </div>
                                <div class="bg-gray-50 p-2 rounded text-center">
                                    <i class="fas fa-wallet text-purple-600 mb-1"></i>
                                    <div>Digital Wallets</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-4 px-6 rounded-lg font-semibold text-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <i class="fas fa-lock mr-2"></i>
                                Proceed to Secure Payment
                            </button>

                            <a href="{{ route('auctions.show', $auction->id) }}"
                               class="w-full bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-medium text-center block hover:bg-gray-300 transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Cancel & Return to Auction
                            </a>
                        </div>

                        <!-- Terms Notice -->
                        <div class="mt-6 text-xs text-gray-500 text-center">
                            By proceeding, you agree to KlikBid's
                            <a href="#" class="text-blue-600 hover:text-blue-800">Terms & Conditions</a>
                            and
                            <a href="#" class="text-blue-600 hover:text-blue-800">Privacy Policy</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Support Information -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
                <div class="text-center">
                    <h3 class="font-semibold text-gray-900 mb-2">Need Help?</h3>
                    <p class="text-gray-600 mb-4">Our support team is here to assist you with your payment</p>
                    <div class="flex justify-center space-x-6 text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-phone mr-2 text-blue-600"></i>
                            +94 11 234 5678
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-envelope mr-2 text-green-600"></i>
                            support@klikbid.lk
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-clock mr-2 text-orange-600"></i>
                            24/7 Support
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
        <div class="bg-white rounded-lg p-8 text-center">
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Processing Payment</h3>
            <p class="text-gray-600">Please wait while we redirect you to the payment gateway...</p>
        </div>
    </div>

    <script>
        // Show loading overlay when form is submitted
        document.getElementById('webxpay-form').addEventListener('submit', function() {
            document.getElementById('loading-overlay').style.display = 'flex';
        });

        // Auto-submit form if needed (uncomment if you want automatic redirect)
        // setTimeout(function() {
        //     document.getElementById('webxpay-form').submit();
        // }, 2000);
    </script>
</body>
</html>
