<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuctionController; // Add this import
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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
});

// Admin routes (only for admins)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/auctions', [\App\Http\Controllers\Admin\AdminController::class, 'auctions'])->name('auctions.index');
    Route::get('/auctions/pending', [\App\Http\Controllers\Admin\AdminController::class, 'pendingAuctions'])->name('auctions.pending');
    Route::get('/auctions/{auction}', [\App\Http\Controllers\Admin\AdminController::class, 'showAuction'])->name('auctions.show');
    Route::post('/auctions/{auction}/approve', [\App\Http\Controllers\Admin\AdminController::class, 'approveAuction'])->name('auctions.approve');
    Route::post('/auctions/{auction}/reject', [\App\Http\Controllers\Admin\AdminController::class, 'rejectAuction'])->name('auctions.reject');
    Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users.index');
});

require __DIR__.'/auth.php';
