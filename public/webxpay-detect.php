<?php
// Log all incoming requests
$logEntry = [
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'],
    'url' => $_SERVER['REQUEST_URI'],
    'post_data' => $_POST,
    'get_data' => $_GET
];

file_put_contents(__DIR__ . '/webxpay-detect.log', json_encode($logEntry) . "\n", FILE_APPEND);

// Process the payment if status_code is "00" (successful)
if (isset($_POST['status_code']) && $_POST['status_code'] === '00' && isset($_POST['custom_fields'])) {
    try {
        // Extract data from custom_fields
        $customFields = base64_decode($_POST['custom_fields']);
        $parts = explode('|', $customFields);
        $paymentId = $parts[0];
        $auctionId = $parts[1];
        $userId = $parts[3];

        // Connect to database
        $pdo = new PDO(
            'mysql:host=127.0.0.1;dbname=klikbid_auction;charset=utf8mb4',
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        // Update payment status to captured
        $stmt = $pdo->prepare("UPDATE payments SET status = 'captured', webxpay_reference = ? WHERE id = ?");
        $stmt->execute([$_POST['order_refference_number'], $paymentId]);

        // Create auction participant record
        $stmt = $pdo->prepare("INSERT INTO auction_participants (auction_id, user_id, deposit_amount, payment_txn_id, status, joined_at, created_at, updated_at) VALUES (?, ?, 10000, ?, 'held', NOW(), NOW(), NOW()) ON DUPLICATE KEY UPDATE status = 'held', updated_at = NOW()");
        $stmt->execute([$auctionId, $userId, $paymentId]);

        file_put_contents(__DIR__ . '/webxpay-detect.log', "Payment processed successfully for auction $auctionId\n", FILE_APPEND);

    } catch (Exception $e) {
        file_put_contents(__DIR__ . '/webxpay-detect.log', "Payment processing error: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}

// Extract auction ID and redirect
if (isset($_POST['custom_fields'])) {
    $customFields = base64_decode($_POST['custom_fields']);
    $parts = explode('|', $customFields);
    $auctionId = $parts[1];

    file_put_contents(__DIR__ . '/webxpay-detect.log', "Redirecting to auction: $auctionId\n", FILE_APPEND);

    echo "<script>window.location.href = 'http://127.0.0.1:8000/auctions/$auctionId';</script>";
    echo "<meta http-equiv='refresh' content='0; url=http://127.0.0.1:8000/auctions/$auctionId'>";
    exit;
}

echo "WebXPay callback detected but no auction ID found!";
?>
