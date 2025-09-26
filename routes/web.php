<?php

\Log::info('Web routes file loaded at: ' . now());

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuctionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

// Add this at the top, right after the use statements
Route::any('/webxpay-simple-callback', function (Illuminate\Http\Request $request) {
    // Log the callback data
    \Log::info('Simple WebXPay callback', $request->all());

    try {
        // Extract the data WebXPay sent
        $responseData = [
            'payment' => $request->input('payment'),
            'signature' => $request->input('signature'),
            'custom_fields' => $request->input('custom_fields')
        ];

        // Create WebXPay service and process response
        $webXPayService = new App\Services\WebXPayService(false);
        $result = $webXPayService->handlePaymentResponse($responseData);

        if ($result['success'] && $result['status'] === 'captured') {
            // Find the auction and redirect with success
            $payment = App\Models\Payment::find($result['payment_id']);
            $auction = App\Models\Auction::find($payment->auction_id);

            return redirect()->route('auctions.show', $auction->id)
                ->with('success', 'Payment successful! You can now place bids.');
        } else {
            return redirect()->route('home')->with('error', 'Payment failed. Please try again.');
        }

    } catch (\Exception $e) {
        \Log::error('Simple callback error: ' . $e->getMessage());
        return redirect()->route('home')->with('error', 'Payment processing error.');
    }
})->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/webxpay-direct-callback', function (Request $request) {
    try {
        Log::info('Direct WebXPay callback received', $request->all());

        $controller = new App\Http\Controllers\WebXPayController();
        return $controller->handleCallback($request);

    } catch (\Exception $e) {
        Log::error('Direct callback error: ' . $e->getMessage());
        return response('Error', 500);
    }
})->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Homepage route - uses our HomeController to show auctions
Route::get('/', [HomeController::class, 'index']);

Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Two-Factor Authentication routes
    Route::get('/2fa/setup', [App\Http\Controllers\Auth\TwoFactorController::class, 'show'])->name('2fa.setup');
    Route::post('/2fa/enable', [App\Http\Controllers\Auth\TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/disable', [App\Http\Controllers\Auth\TwoFactorController::class, 'disable'])->name('2fa.disable');

    // Search routes
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

    // Auction routes
    Route::get('/auctions/create', [AuctionController::class, 'create'])->name('auctions.create');
    Route::post('/auctions', [AuctionController::class, 'store'])->name('auctions.store');
    Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');
    Route::get('/auctions/{auction}/current-bid', [AuctionController::class, 'getCurrentBid'])->name('auctions.current-bid');

    // Edit routes - make sure these come BEFORE the {auction} route to avoid conflicts
    Route::get('/auctions/{auction}/edit', [AuctionController::class, 'edit'])->name('auctions.edit');
    Route::put('/auctions/{auction}', [AuctionController::class, 'update'])->name('auctions.update');

    // Bidding routes
    Route::get('/auctions/{auction}/join', [\App\Http\Controllers\BiddingController::class, 'joinAuction'])->name('bidding.join');
    Route::post('/auctions/{auction}/join', [\App\Http\Controllers\BiddingController::class, 'processJoin'])->name('bidding.process-join');
    Route::post('/auctions/{auction}/bid', [\App\Http\Controllers\BiddingController::class, 'placeBid'])->name('bidding.place-bid');
    Route::get('/auctions/{auction}/bids', [\App\Http\Controllers\BiddingController::class, 'bidHistory'])->name('bidding.history');
    Route::get('/auctions/{auction}/min-bid', [\App\Http\Controllers\BiddingController::class, 'getMinimumBid'])->name('bidding.min-bid');

    // User Dashboard routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/my-listings', [\App\Http\Controllers\User\DashboardController::class, 'myListings'])->name('my-listings');
        Route::get('/my-bids', [\App\Http\Controllers\User\DashboardController::class, 'myBids'])->name('my-bids');
        Route::get('/won-auctions', [\App\Http\Controllers\User\DashboardController::class, 'wonAuctions'])->name('won-auctions');
        Route::get('/profile', [\App\Http\Controllers\User\DashboardController::class, 'profile'])->name('profile');
        Route::patch('/profile', [\App\Http\Controllers\User\DashboardController::class, 'updateProfile'])->name('profile.update');
        Route::get('/listings/{auction}', [\App\Http\Controllers\User\DashboardController::class, 'auctionDetail'])->name('auction-detail');
    });

    // WebXPay Payment Routes (authenticated users only)
    Route::post('/auction/{auction}/pay-deposit', [App\Http\Controllers\WebXPayController::class, 'payDeposit'])
        ->name('webxpay.deposit');
    Route::post('/auction/{auction}/pay-winner', [App\Http\Controllers\WebXPayController::class, 'payWinner'])
        ->name('webxpay.winner');
});

// WebXPay callback (no auth required - WebXPay calls this)
// Route::post('/webxpay/callback', [App\Http\Controllers\WebXPayController::class, 'handleCallback'])
//     ->name('webxpay.callback');
// Route::get('/webxpay/callback', [App\Http\Controllers\WebXPayController::class, 'handleCallback'])
//     ->name('webxpay.callback.get');

Route::get('/auto-login', function(Request $request) {
    $token = $request->get('token');
    $auctionId = $request->get('auction');

    \Log::info('Auto-login attempt', ['token' => $token, 'auction' => $auctionId]);

    if (!$token || !$auctionId) {
        \Log::error('Missing token or auction ID');
        return redirect('/')->with('error', 'Invalid login parameters.');
    }

    // Find valid token
    $tokenRecord = DB::table('temp_login_tokens')
        ->where('token', $token)
        ->where('expires_at', '>', now())
        ->first();

    \Log::info('Token lookup result', ['user_found' => $tokenRecord ? 'yes' : 'no']);

    if ($tokenRecord) {
        $user = DB::table('users')->where('id', $tokenRecord->user_id)->first();

        if ($user) {
            // Log in the user with remember token for persistence
            Auth::loginUsingId($user->id, true);

            // Regenerate session to ensure it's properly saved
            request()->session()->regenerate();

            \Log::info('User logged in successfully', ['user_id' => $user->id, 'auth_check' => Auth::check()]);

            // Delete the used token
            DB::table('temp_login_tokens')->where('token', $token)->delete();

            // Redirect to auction with success message
            return redirect()->to('/auctions/' . $auctionId)
                ->with('success', 'Payment successful! You can now place bids.');
        }
    }

    \Log::info('Auto-login failed - redirecting to home');
    return redirect('/')->with('error', 'Invalid or expired login token.');
});

Route::get('/debug-session', function() {
    return [
        'authenticated' => Auth::check(),
        'user_id' => Auth::id(),
        'user_name' => Auth::user() ? Auth::user()->name : 'No user',
        'session_id' => session()->getId(),
        'session_token' => session()->token(),
        'remember_token' => Auth::user() ? Auth::user()->remember_token : 'No remember token'
    ];
});

// Payment result pages (no auth required)
Route::get('/payment/success', [App\Http\Controllers\WebXPayController::class, 'success'])
    ->name('payment.success');
Route::get('/payment/cancel', [App\Http\Controllers\WebXPayController::class, 'cancel'])
    ->name('payment.cancel');

// Payment response routes
Route::post('/payment/callback', [PaymentResponseController::class, 'handleCallback'])->name('payment.callback');
Route::get('/payment/success/{payment}', [PaymentResponseController::class, 'success'])->name('payment.success')->middleware('auth');
Route::get('/payment/failed', [PaymentResponseController::class, 'failed'])->name('payment.failed');
Route::get('/payment/status/{payment}', [PaymentResponseController::class, 'status'])->name('payment.status')->middleware('auth');

// Admin routes (only for admins)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/auctions', [\App\Http\Controllers\Admin\AdminController::class, 'auctions'])->name('auctions.index');
    Route::get('/auctions/pending', [\App\Http\Controllers\Admin\AdminController::class, 'pendingAuctions'])->name('auctions.pending');
    Route::get('/auctions/{auction}', [\App\Http\Controllers\Admin\AdminController::class, 'showAuction'])->name('auctions.show');
    Route::post('/auctions/{auction}/approve', [\App\Http\Controllers\Admin\AdminController::class, 'approveAuction'])->name('auctions.approve');
    Route::post('/auctions/{auction}/reject', [\App\Http\Controllers\Admin\AdminController::class, 'rejectAuction'])->name('auctions.reject');
    Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users.index');

    // Admin payout management
    Route::get('/payouts', [App\Http\Controllers\WebXPayController::class, 'adminPayoutReport'])
        ->name('payouts');
    Route::post('/payouts/{payment}/complete', [App\Http\Controllers\WebXPayController::class, 'adminMarkPayoutCompleted'])
        ->name('payouts.complete');


});

require __DIR__.'/auth.php';
