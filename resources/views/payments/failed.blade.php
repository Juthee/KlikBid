@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Error Header -->
        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="fas fa-times text-red-600 text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Payment Failed</h1>
            <p class="mt-2 text-lg text-gray-600">We couldn't process your payment</p>
        </div>

        <!-- Error Details Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-4 bg-red-50 border-b border-red-200">
                <h2 class="text-lg font-semibold text-red-800">What happened?</h2>
            </div>
            <div class="p-6">
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Common reasons for payment failure:</h3>
                        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Insufficient funds in your account</li>
                            <li>Card expired or blocked</li>
                            <li>Incorrect card details entered</li>
                            <li>Bank declined the transaction</li>
                            <li>Network connectivity issues</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">What you can do:</h3>
                        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside">
                            <li>Check your card details and try again</li>
                            <li>Try using a different payment method</li>
                            <li>Contact your bank if the issue persists</li>
                            <li>Reach out to our support team for assistance</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="history.back()"
                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <i class="fas fa-redo mr-2"></i>
                Try Again
            </button>

            <a href="{{ route('home') }}"
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <i class="fas fa-home mr-2"></i>
                Back to Home
            </a>
        </div>

        <!-- Support Contact -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-question-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Need Help?</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>If you continue to experience issues, please contact our support team:</p>
                        <div class="mt-2 space-y-1">
                            <p><strong>Email:</strong> support@klikbid.lk</p>
                            <p><strong>Phone:</strong> +94 11 123 4567</p>
                            <p><strong>Hours:</strong> Monday - Friday, 9:00 AM - 6:00 PM (IST)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="text-center mt-8">
            <p class="text-xs text-gray-500">
                <i class="fas fa-shield-alt mr-1"></i>
                Your payment information is always secure and encrypted. No payment details are stored on our servers.
            </p>
        </div>
    </div>
</div>
@endsection
