<?php

namespace App\Http\Controllers;

use App\Services\WebXPayService;
use App\Models\Auction;
use App\Models\Payment;
use App\Models\AuctionParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WebXPayController extends Controller
{
    private $webXPayService;

    public function __construct()
    {
        $this->webXPayService = new WebXPayService(false); // false = staging mode
    }

    /**
     * Handle deposit payment for joining auction
     */
    public function payDeposit(Request $request, $auctionId)
    {
        try {
            $user = Auth::user();
            $auction = Auction::findOrFail($auctionId);

            // Validation checks
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to join auction');
            }

            if ($auction->status !== 'active') {
                return back()->with('error', 'Auction is not active for bidding');
            }

            // Check if user already joined this auction
            $existingParticipant = AuctionParticipant::where('user_id', $user->id)
                ->where('auction_id', $auction->id)
                ->where('status', 'held')
                ->first();

            if ($existingParticipant) {
                return back()->with('info', 'You have already joined this auction');
            }

            // Calculate deposit amount based on base price
            $depositAmount = $this->calculateDepositAmount($auction->base_price);

            // Create WebXPay payment request
            $paymentRequest = $this->webXPayService->createDepositPayment(
                $user,
                $auction,
                $depositAmount
            );

            // Store session data for return handling
            session([
                'payment_type' => 'deposit',
                'auction_id' => $auction->id,
                'payment_id' => $paymentRequest['payment_id']
            ]);

            // Return payment form view
            return view('payment.webxpay-form', [
                'paymentUrl' => $paymentRequest['payment_url'],
                'formData' => $paymentRequest['form_data'],
                'auction' => $auction,
                'depositAmount' => $depositAmount,
                'paymentType' => 'Auction Deposit'
            ]);

        } catch (\Exception $e) {
            Log::error('Deposit payment error: ' . $e->getMessage());
            return back()->with('error', 'Failed to process deposit payment. Please try again.');
        }
    }

    /**
     * Handle winner payment for auction
     */
    public function payWinner(Request $request, $auctionId)
    {
        try {
            $user = Auth::user();
            $auction = Auction::with('bids')->findOrFail($auctionId);

            // Validation checks
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login to make payment');
            }

            if ($auction->winner_user_id !== $user->id) {
                return back()->with('error', 'You are not the winner of this auction');
            }

            if ($auction->paid_at) {
                return back()->with('info', 'Payment has already been made for this auction');
            }

            $winningAmount = $auction->winning_bid_amount;

            // Check if user has deposit that can be applied
            $depositUsed = 0;
            $depositPayment = Payment::where('user_id', $user->id)
                ->where('auction_id', $auction->id)
                ->where('type', 'deposit')
                ->where('status', 'captured')
                ->first();

            if ($depositPayment) {
                $depositUsed = $depositPayment->amount / 100; // Convert from cents
            }

            // Create WebXPay payment request
            $paymentRequest = $this->webXPayService->createWinnerPayment(
                $user,
                $auction,
                $winningAmount,
                $depositUsed
            );

            // Store session data for return handling
            session([
                'payment_type' => 'winner',
                'auction_id' => $auction->id,
                'payment_id' => $paymentRequest['payment_id']
            ]);

            // Return payment form view
            return view('payment.webxpay-form', [
                'paymentUrl' => $paymentRequest['payment_url'],
                'formData' => $paymentRequest['form_data'],
                'auction' => $auction,
                'winningAmount' => $winningAmount,
                'depositUsed' => $depositUsed,
                'finalAmount' => $winningAmount - $depositUsed,
                'paymentType' => 'Auction Payment'
            ]);

        } catch (\Exception $e) {
            Log::error('Winner payment error: ' . $e->getMessage());
            return back()->with('error', 'Failed to process payment. Please try again.');
        }
    }

    /**
     * Handle WebXPay callback response
     */
    public function handleCallback(Request $request)
    {
            Log::info('WebXPay callback received - Method: ' . $request->method());
            Log::info('WebXPay callback data: ', $request->all());
        try {
            Log::info('WebXPay callback received', $request->all());

            // WebXPay sends the data directly in the request
            $responseData = [
                'payment' => $request->input('payment'),
                'signature' => $request->input('signature'),
                'custom_fields' => $request->input('custom_fields')
            ];

            // Handle WebXPay response
            $result = $this->webXPayService->handlePaymentResponse($responseData);

            if (!$result['success']) {
                return redirect('/')->with('error', 'Payment processing failed: ' . $result['message']);
            }

            $payment = Payment::find($result['payment_id']);
            if (!$payment) {
                return redirect('/')->with('error', 'Payment record not found');
            }

            $auction = Auction::find($payment->auction_id);

            if ($result['status'] === 'captured') {
                // Payment successful
                if ($payment->type === 'deposit') {
                    // Handle deposit payment success
                    $this->handleDepositSuccess($payment, $auction);

                    return redirect('/auctions/' . $auction->id . '?payment=success');

                } elseif ($payment->type === 'auction_payment') {
                    // Handle winner payment success
                    $this->handleWinnerPaymentSuccess($payment, $auction);

                    return redirect()->route('user.won-auctions')
                        ->with('success', 'Payment successful! The seller will contact you soon.');
                }
            } else {
                // Payment failed
                return redirect('/auctions/' . $auction->id . '?payment=failed');
            }

        } catch (\Exception $e) {
            Log::error('WebXPay callback error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Payment processing error. Please contact support.');
        }
    }

    /**
     * Handle successful deposit payment
     */
    private function handleDepositSuccess($payment, $auction)
    {
        DB::transaction(function () use ($payment, $auction) {
            // Create auction participant record
            AuctionParticipant::updateOrCreate([
                'user_id' => $payment->user_id,
                'auction_id' => $auction->id,
            ], [
                'deposit_amount' => $payment->amount,
                'payment_txn_id' => $payment->id,
                'status' => 'held',
                'joined_at' => now()
            ]);

            // Mark payment as ready for email
            $payment->update(['customer_email_sent' => false]);
        });
    }

    /**
     * Handle successful winner payment
     */
    private function handleWinnerPaymentSuccess($payment, $auction)
    {
        DB::transaction(function () use ($payment, $auction) {
            // Mark auction as paid
            $auction->update(['paid_at' => now()]);

            // Mark seller payout as ready for processing
            $payment->update([
                'seller_payout_status' => 'processing',
                'customer_email_sent' => false
            ]);

            // If there was a deposit, mark it as applied
            $depositPayment = Payment::where('user_id', $payment->user_id)
                ->where('auction_id', $auction->id)
                ->where('type', 'deposit')
                ->where('status', 'captured')
                ->first();

            if ($depositPayment) {
                $depositPayment->update(['type' => 'deposit_applied']);

                // Update participant status
                AuctionParticipant::where('user_id', $payment->user_id)
                    ->where('auction_id', $auction->id)
                    ->update(['status' => 'applied']);
            }
        });
    }

    /**
     * Calculate deposit amount based on base price
     */
    private function calculateDepositAmount($basePrice)
    {
        if ($basePrice > 100000) return 5000;
        if ($basePrice >= 50000) return 1000;
        if ($basePrice >= 10000) return 500;
        if ($basePrice >= 1000) return 100;
        if ($basePrice >= 100) return 50;
        return 0; // No deposit required for items under Rs 100
    }

    /**
     * Show payment success page
     */
    public function success(Request $request)
    {
        $paymentType = session('payment_type', 'payment');
        $auctionId = session('auction_id');
        $auction = $auctionId ? Auction::find($auctionId) : null;

        return view('payment.success', [
            'paymentType' => $paymentType,
            'auction' => $auction
        ]);
    }

    /**
     * Show payment cancel page
     */
    public function cancel(Request $request)
    {
        $paymentType = session('payment_type', 'payment');
        $auctionId = session('auction_id');
        $auction = $auctionId ? Auction::find($auctionId) : null;

        return view('payment.cancel', [
            'paymentType' => $paymentType,
            'auction' => $auction
        ]);
    }

    /**
     * Admin: View seller payout report
     */
    public function adminPayoutReport()
    {
        $this->middleware('auth');

        $payouts = $this->webXPayService->getSellerPayoutReport();

        return view('admin.seller-payouts', [
            'payouts' => $payouts
        ]);
    }

    /**
     * Admin: Mark payout as completed
     */
    public function adminMarkPayoutCompleted(Request $request, $paymentId)
    {
        $this->middleware('auth');

        $success = $this->webXPayService->markPayoutCompleted($paymentId, Auth::id());

        if ($success) {
            return back()->with('success', 'Payout marked as completed');
        } else {
            return back()->with('error', 'Failed to update payout status');
        }
    }
}
