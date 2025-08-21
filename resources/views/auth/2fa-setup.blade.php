<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Two-Factor Authentication Setup') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="max-w-md mx-auto">
                        <h3 class="text-lg font-medium mb-4">🔒 Secure Your Account</h3>

                        <div class="mb-6">
                            <p class="text-sm text-gray-600 mb-4">
                                Scan this QR code with Google Authenticator app on your phone:
                            </p>

                            <div class="text-center mb-4">
                                <img src="{{ $qrCodeUrl }}" alt="2FA QR Code" class="mx-auto border rounded-lg">
                            </div>

                            <p class="text-xs text-gray-500 mb-4">
                                Don't have Google Authenticator?
                                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                                   target="_blank" class="text-blue-600 hover:underline">Download for Android</a> |
                                <a href="https://apps.apple.com/app/google-authenticator/id388497605"
                                   target="_blank" class="text-blue-600 hover:underline">Download for iOS</a>
                            </p>
                        </div>

                        <form method="POST" action="{{ route('2fa.enable') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="one_time_password" class="block text-sm font-medium text-gray-700">
                                    Enter 6-digit code from your app:
                                </label>
                                <input type="text"
                                       id="one_time_password"
                                       name="one_time_password"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="123456"
                                       maxlength="6"
                                       required>
                                @error('one_time_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <a href="{{ route('dashboard') }}"
                                   class="text-sm text-gray-600 hover:text-gray-800">
                                    ← Back to Dashboard
                                </a>
                                <button type="submit"
                                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                    Enable 2FA
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
