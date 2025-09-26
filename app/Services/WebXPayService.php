<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Auction;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

class WebXPayService
{
    // Your actual WebXPay credentials
    private const PUBLIC_KEY = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCv03MBjd+WVCckBeNBnpVV5nEv
TKq8sReshDTnJR2XpZZGb9TqKncKw19c6FdX8aFfxw4XEnAPtewfPId4iNkMKYyu
vuLPaQ6xiyYziaKr/hUobwGPoj6Hskl3Kw4BP9uFK0K96ChuajX6DvENH+LiJXNJ
U4N8GjVpr4jHkpLT8QIDAQAB
-----END PUBLIC KEY-----";

    private const SECRET_KEY = 'e27d95b3-12d6-4511-aa0b-9ea234a6ab2a';

    // URLs
    private const LIVE_URL = 'https://webxpay.com/index.php?route=checkout/billing';
    private const STAGING_URL = 'https://stagingxpay.info/index.php?route=checkout/billing';

    private $isLive;

    public function __construct($isLive = false)
    {
        $this->isLive = $isLive;
    }

    /**
     * Create payment request for auction deposits
     */
    public function createDepositPayment($user, $auction, $depositAmount)
    {
        try {
            // For staging environment, limit amount to prevent transaction limit errors
            $testAmount = $this->isLive ? $depositAmount : min($depositAmount, 50);

            // Generate unique order ID for tracking
            $orderId = 'DEP_' . $auction->id . '_' . $user->id . '_' . time();

            // Store payment record in database
            $payment = Payment::create([
                'user_id' => $user->id,
                'auction_id' => $auction->id,
                'seller_id' => $auction->user_id,
                'amount' => $testAmount * 100, // Store in cents
                'commission_amount' => 0,
                'seller_payout_amount' => 0,
                'currency' => 'LKR',
                'type' => 'deposit',
                'status' => 'pending',
                'webxpay_order_id' => $orderId,
                'seller_payout_status' => 'pending',
                'return_url' => url('/webxpay-final.php'),
            ]);

            // Create WebXPay payment request
            $paymentData = $this->encryptPaymentData($orderId, $testAmount);
            $customFields = $this->encryptCustomFields($payment->id, $auction->id, 'deposit', $user->id);

            return [
                'payment_url' => $this->getPaymentUrl(),
                'form_data' => [
                    'first_name' => $this->getFirstName($user),
                    'last_name' => $this->getLastName($user),
                    'email' => $user->email,
                    'contact_number' => $user->phone ?? '0777888999',
                    'address_line_one' => $user->address ?? 'Test Address',
                    'address_line_two' => '',
                    'city' => 'Colombo',
                    'state' => 'Western',
                    'postal_code' => '10300',
                    'country' => 'Sri Lanka',
                    'process_currency' => 'LKR',
                    'cms' => 'Laravel',
                    'custom_fields' => $customFields,
                    'enc_method' => 'JCs3J+6oSz4V0LgE0zi/Bg==',
                    'secret_key' => self::SECRET_KEY,
                    'payment' => $paymentData,
                    'return_url' => 'http://127.0.0.1:8000/webxpay-final.php',
                ],
                'payment_id' => $payment->id
            ];

        } catch (Exception $e) {
            Log::error('WebXPay deposit creation failed: ' . $e->getMessage());
            throw new Exception('Failed to create payment request');
        }
    }

    /**
     * Create payment request for auction winner payments
     */
    public function createWinnerPayment($user, $auction, $winningAmount, $depositUsed = 0)
    {
        try {
            // Calculate amounts
            $totalAmount = $winningAmount - $depositUsed;
            $commissionRate = 0.05; // 5% platform commission
            $commissionAmount = $winningAmount * $commissionRate;
            $sellerPayoutAmount = $winningAmount - $commissionAmount;

            // For staging environment, limit amount
            $testAmount = $this->isLive ? $totalAmount : min($totalAmount, 50);

            // Generate unique order ID
            $orderId = 'WIN_' . $auction->id . '_' . $user->id . '_' . time();

            // Store payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'auction_id' => $auction->id,
                'seller_id' => $auction->user_id,
                'amount' => $testAmount * 100, // Store in cents
                'commission_amount' => $commissionAmount * 100,
                'seller_payout_amount' => $sellerPayoutAmount * 100,
                'currency' => 'LKR',
                'type' => 'auction_payment',
                'status' => 'pending',
                'webxpay_order_id' => $orderId,
                'seller_payout_status' => 'pending'
            ]);

            // Create WebXPay payment request
            $paymentData = $this->encryptPaymentData($orderId, $testAmount);
            $customFields = $this->encryptCustomFields($payment->id, $auction->id, 'winner', $user->id);

            return [
                'payment_url' => $this->getPaymentUrl(),
                'form_data' => [
                    'first_name' => $this->getFirstName($user),
                    'last_name' => $this->getLastName($user),
                    'email' => $user->email,
                    'contact_number' => $user->phone ?? '0777888999',
                    'address_line_one' => $user->address ?? 'Test Address',
                    'address_line_two' => '',
                    'city' => 'Colombo',
                    'state' => 'Western',
                    'postal_code' => '10300',
                    'country' => 'Sri Lanka',
                    'process_currency' => 'LKR',
                    'cms' => 'Laravel',
                    'custom_fields' => $customFields,
                    'enc_method' => 'JCs3J+6oSz4V0LgE0zi/Bg==',
                    'secret_key' => self::SECRET_KEY,
                    'payment' => $paymentData,
                ],
                'payment_id' => $payment->id
            ];

        } catch (Exception $e) {
            Log::error('WebXPay winner payment creation failed: ' . $e->getMessage());
            throw new Exception('Failed to create winner payment request');
        }
    }

    /**
     * Handle WebXPay response callback
     */
    public function handlePaymentResponse($responseData)
    {
        try {
            // Decode response data
            $payment = base64_decode($responseData['payment']);
            $signature = base64_decode($responseData['signature']);
            $customFields = base64_decode($responseData['custom_fields']);

            // Verify signature
            if (!$this->verifySignature($payment, $signature)) {
                Log::error('WebXPay signature verification failed');
                return ['success' => false, 'message' => 'Invalid signature'];
            }

            // Parse payment response
            // Format: order_id|reference|datetime|gateway|status|comment
            $responseVars = explode('|', $payment);
            $orderId = $responseVars[0];
            $reference = $responseVars[1];
            $datetime = $responseVars[2];
            $gateway = $responseVars[3];
            $statusCode = $responseVars[4];
            $comment = $responseVars[5] ?? '';

            // Parse custom fields
            // Format: payment_id|auction_id|type|user_id
            $customVars = explode('|', $customFields);
            $paymentId = $customVars[0];
            $auctionId = $customVars[1];
            $paymentType = $customVars[2];
            $userId = $customVars[3];

            // Find payment record
            $paymentRecord = Payment::find($paymentId);
            if (!$paymentRecord) {
                Log::error('Payment record not found: ' . $paymentId);
                return ['success' => false, 'message' => 'Payment not found'];
            }

            // Update payment record
            $paymentRecord->update([
                'webxpay_reference' => $reference,
                'webxpay_transaction_time' => $datetime,
                'status' => $this->mapWebXPayStatus($statusCode),
                'gateway_ref' => $reference,
                'meta' => json_encode([
                    'gateway' => $gateway,
                    'status_code' => $statusCode,
                    'comment' => $comment,
                    'response_time' => now()
                ])
            ]);

            // Handle successful payments
            if ($statusCode == '0' || $statusCode == '00') {
                $this->handleSuccessfulPayment($paymentRecord, $paymentType);
            }

            return [
                'success' => true,
                'payment_id' => $paymentId,
                'status' => $this->mapWebXPayStatus($statusCode),
                'reference' => $reference
            ];

        } catch (Exception $e) {
            Log::error('WebXPay response handling failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Response processing failed'];
        }
    }

    /**
     * Encrypt payment data for WebXPay
     */
    private function encryptPaymentData($orderId, $amount)
    {
        $plaintext = $orderId . '|' . $amount;
        openssl_public_encrypt($plaintext, $encrypted, self::PUBLIC_KEY);
        return base64_encode($encrypted);
    }

    /**
     * Encrypt custom fields for tracking
     */
    private function encryptCustomFields($paymentId, $auctionId, $type, $userId)
    {
        $customData = $paymentId . '|' . $auctionId . '|' . $type . '|' . $userId;
        return base64_encode($customData);
    }

    /**
     * Verify signature from WebXPay response
     */
    private function verifySignature($payment, $signature)
    {
        openssl_public_decrypt($signature, $value, self::PUBLIC_KEY);
        return $value === $payment;
    }

    /**
     * Map WebXPay status codes to our system
     */
    private function mapWebXPayStatus($statusCode)
    {
        return match($statusCode) {
            '0', '00' => 'captured',
            '15' => 'failed',
            default => 'failed'
        };
    }

    /**
     * Handle successful payment processing
     */
    private function handleSuccessfulPayment($payment, $type)
    {
        if ($type === 'deposit') {
            // Handle deposit payment - user can now bid
            $auction = Auction::find($payment->auction_id);
            // You can add logic here to automatically join user to auction
        } elseif ($type === 'winner') {
            // Handle winner payment - auction complete
            $auction = Auction::find($payment->auction_id);
            $auction->update(['paid_at' => now()]);

            // Mark seller payout as ready for manual processing
            $payment->update(['seller_payout_status' => 'processing']);
        }

        // Mark email as ready to send
        $payment->update([
            'customer_email_sent' => false // Will be processed by email job
        ]);
    }

    /**
     * Get payment URL (staging or live)
     */
    private function getPaymentUrl()
    {
        return $this->isLive ? self::LIVE_URL : self::STAGING_URL;
    }

    /**
     * Generate seller payout report for admin
     */
    public function getSellerPayoutReport()
    {
        return Payment::with(['user', 'auction.user'])
            ->where('seller_payout_status', 'processing')
            ->where('status', 'captured')
            ->where('type', 'auction_payment')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'payment_id' => $payment->id,
                    'auction_title' => $payment->auction->title,
                    'seller_name' => $payment->auction->user->name,
                    'seller_email' => $payment->auction->user->email,
                    'payout_amount' => $payment->seller_payout_amount / 100,
                    'commission_amount' => $payment->commission_amount / 100,
                    'total_received' => $payment->amount / 100,
                    'webxpay_reference' => $payment->webxpay_reference,
                    'transaction_date' => $payment->webxpay_transaction_time
                ];
            });
    }

    /**
     * Mark seller payout as completed (called after manual transfer)
     */
    public function markPayoutCompleted($paymentId, $adminUserId)
    {
        $payment = Payment::find($paymentId);
        if ($payment) {
            $payment->update([
                'seller_payout_status' => 'completed',
                'seller_payout_date' => now(),
                'meta' => array_merge(json_decode($payment->meta, true) ?? [], [
                    'payout_processed_by' => $adminUserId,
                    'payout_processed_at' => now()
                ])
            ]);
            return true;
        }
        return false;
    }

    /**
     * Get user's first name safely
     */
    private function getFirstName($user)
    {
        if (!empty($user->first_name)) {
            return $user->first_name;
        }

        if (!empty($user->name)) {
            $nameParts = explode(' ', trim($user->name));
            return $nameParts[0] ?? 'John';
        }

        return 'John';
    }

    /**
     * Get user's last name safely
     */
    private function getLastName($user)
    {
        if (!empty($user->last_name)) {
            return $user->last_name;
        }

        if (!empty($user->name)) {
            $nameParts = explode(' ', trim($user->name));
            return $nameParts[1] ?? 'Doe';
        }

        return 'Doe';
    }
}
