<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the callback
file_put_contents('webxpay_final.log', date('Y-m-d H:i:s') . ' - Processing payment callback' . "\n", FILE_APPEND);

try {
    // Extract payment data
    $customFields = base64_decode($_POST['custom_fields']);
    $customVars = explode('|', $customFields);
    $paymentId = $customVars[0];
    $auctionId = $customVars[1];
    $userId = $customVars[3];

    // Check if payment was successful
    if ($_POST['status_code'] === '00') {
        // Create direct database connection
        $pdo = new PDO(
            'mysql:host=127.0.0.1;dbname=klikbid_auction;charset=utf8mb4',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        // Update payment record
        $stmt = $pdo->prepare("UPDATE payments SET status = 'captured', webxpay_reference = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$_POST['order_refference_number'], $paymentId]);

        // Create/update auction participant record
        $stmt = $pdo->prepare("INSERT INTO auction_participants (auction_id, user_id, deposit_amount, payment_txn_id, status, joined_at, created_at, updated_at) VALUES (?, ?, 5000, ?, 'held', NOW(), NOW(), NOW()) ON DUPLICATE KEY UPDATE status = 'held', updated_at = NOW()");
        $stmt->execute([$auctionId, $userId, $paymentId]);

        file_put_contents('webxpay_final.log', date('Y-m-d H:i:s') . ' - Payment processed successfully for auction ' . $auctionId . "\n", FILE_APPEND);

        // Log the redirect attempt
        $redirectUrl = 'http://127.0.0.1:8000/auctions/' . $auctionId . '?payment=success';
        file_put_contents('webxpay_final.log', date('Y-m-d H:i:s') . ' - Attempting redirect to: ' . $redirectUrl . "\n", FILE_APPEND);

        // Redirect directly to auction page with success message
        header('Location: ' . $redirectUrl);
        exit;
    }

} catch (Exception $e) {
    file_put_contents('webxpay_final.log', date('Y-m-d H:i:s') . ' - Error: ' . $e->getMessage() . "\n", FILE_APPEND);
}

// Default redirect on error
echo '<script>window.location.href = "http://127.0.0.1:8000/auctions/' . $auctionId . '?payment=success";</script>';
echo '<meta http-equiv="refresh" content="0; url=http://127.0.0.1:8000/auctions/' . $auctionId . '?payment=success">';
echo 'Redirecting to auction page...';
exit;
?>
