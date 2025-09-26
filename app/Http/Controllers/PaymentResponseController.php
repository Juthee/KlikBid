<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WebXPayService;
use App\Models\Payment;
use App\Models\Auction;
use App\Models\AuctionParticipant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentResponseController extends Controller
{
    private $webXPayService;

    public function __construct()
    {
        $this->webXPayService = new WebXPayService(false); // false = staging mode
    }

    /**
     * Handle WebXPay response callback
     */
    public function handleCallback(Request $request)
    {
        try {
            Log::info('WebXPay callback received', $request->all());

            // Process the WebXPay response
            $result = $this->webXPayService->handlePaymentResponse($request->all());

            if ($result['success']) {
                // Get payment record
                $payment = Payment::find($result['payment_id']);

                if ($payment && $result['status'] === 'captured') {
                    // Payment successful
                    $this->handleSuccessfulPayment($payment);

                    return redirect()->route('payment.success', ['payment' => $payment->id])
                        ->with('success', 'Payment successful! You have joined the auction.');
                } else {
                    // Payment failed
                    return redirect()->route('payment.failed')
                        ->with('error', 'Payment failed. Please try again.');
                }
            } else {
                // Response processing failed
                Log::error('WebXPay response processing failed: ' . $result['message']);
                return redirect()->route('payment.failed')
                    ->with('error', 'Payment verification failed. Please contact support.');
            }

        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage());
            return redirect()->route('payment.failed')
                ->with('error', 'An error occurred processing your payment. Please contact support.');
        }
    }

    /**
     * Handle successful payment processing
     */
    private function handleSuccessfulPayment($payment)
    {
        if ($payment->type === 'deposit') {
            // Add user to auction participants
            AuctionParticipant::updateOrCreate([
                'auction_id' => $payment->auction_id,
                'user_id' => $payment->user_id
            ], [
                'deposit_amount' => $payment->amount,
                'deposit_status' => 'held',
                'joined_at' => now()
            ]);

            Log::info("User {$payment->user_id} successfully joined auction {$payment->auction_id}");
        } elseif ($payment->type === 'auction_payment') {
            // Mark auction as paid
            $auction = Auction::find($payment->auction_id);
            if ($auction) {
                $auction->update(['paid_at' => now()]);
                Log::info("Auction {$auction->id} marked as paid by user {$payment->user_id}");
            }
        }

        // Mark payment as processed
        $payment->update([
            'processed_at' => now(),
            'customer_email_sent' => false // Will trigger email notification
        ]);
    }

    /**
     * Show payment success page
     */
    public function success(Request $request, Payment $payment)
    {
        // Verify this payment belongs to current user
        if ($payment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to payment information');
        }

        $auction = $payment->auction;

        return view('payments.success', compact('payment', 'auction'));
    }

    /**
     * Show payment failure page
     */
    public function failed(Request $request)
    {
        return view('payments.failed');
    }

    /**
     * Show payment status page (for checking payment status)
     */
    public function status(Request $request, Payment $payment)
    {
        // Verify this payment belongs to current user
        if ($payment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to payment information');
        }

        $auction = $payment->auction;

        return view('payments.status', compact('payment', 'auction'));
    }
}
