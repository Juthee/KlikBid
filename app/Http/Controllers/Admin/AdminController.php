<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\EmailNotificationService;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Admin dashboard with overview statistics
        $pendingAuctions = Auction::where('status', 'pending_approval')->count();
        $activeAuctions = Auction::where('status', 'active')->count();
        $totalUsers = User::count();
        $totalAuctions = Auction::count();

        $recentAuctions = Auction::with(['user', 'category'])
            ->where('status', 'pending_approval')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'pendingAuctions',
            'activeAuctions',
            'totalUsers',
            'totalAuctions',
            'recentAuctions'
        ));
    }

    public function auctions()
    {
        // Show all auctions for management
        $auctions = Auction::with(['user', 'category'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.auctions.index', compact('auctions'));
    }

    public function pendingAuctions()
    {
        // Show only pending auctions for approval
        $auctions = Auction::with(['user', 'category'])
            ->where('status', 'pending_approval')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.auctions.pending', compact('auctions'));
    }

    public function approveAuction(Auction $auction)
    {
        // Approve an auction
        $auction->update(['status' => 'active']);

        // Send approval email to seller
        $emailService = new EmailNotificationService();
        $emailService->sendAuctionApprovedNotification($auction, $auction->user);

        return redirect()->back()->with('success', "Auction '{$auction->title}' has been approved!");
    }

    public function rejectAuction(Request $request, Auction $auction)
    {
        // Reject an auction with reason
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $auction->update([
            'status' => 'cancelled',
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()->back()->with('success', "Auction '{$auction->title}' has been rejected.");
    }

    public function users()
    {
        // Show all users
        $users = User::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function showAuction(Auction $auction)
    {
        // Show detailed auction view for admin
        $auction->load(['user', 'category', 'bids.user']);

        return view('admin.auctions.show', compact('auction'));
    }
}
