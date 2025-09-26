<?php
session_start();

// Get the auction ID from the most recent payment
$auctionId = null;

try {
    // Connect to database
    $pdo = new PDO(
        'mysql:host=127.0.0.1;dbname=klikbid_auction;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Get the most recent deposit payment from the last 10 minutes
    // Include both 'captured' and 'pending' status
    $stmt = $pdo->prepare("
        SELECT auction_id, user_id, updated_at, status
        FROM payments
        WHERE (status = 'captured' OR status = 'pending')
        AND type = 'deposit'
        AND updated_at > DATE_SUB(NOW(), INTERVAL 10 MINUTE)
        ORDER BY updated_at DESC
        LIMIT 1
    ");
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        $auctionId = $result['auction_id'];
        // Log for debugging
        file_put_contents(__DIR__ . '/payment_success.log', date('Y-m-d H:i:s') . ' - Redirecting to auction: ' . $auctionId . ' (status: ' . $result['status'] . ') for payment at: ' . $result['updated_at'] . "\n", FILE_APPEND);
    }

    $redirectUrl = $auctionId ? "http://127.0.0.1:8000/auctions/$auctionId" : "http://127.0.0.1:8000";

} catch (Exception $e) {
    // Log error and fallback to homepage
    file_put_contents(__DIR__ . '/payment_success.log', date('Y-m-d H:i:s') . ' - Error: ' . $e->getMessage() . "\n", FILE_APPEND);
    $redirectUrl = "http://127.0.0.1:8000";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <meta http-equiv="refresh" content="3; url=<?php echo $redirectUrl; ?>">
</head>
<body>
    <div style="text-align: center; margin-top: 100px; font-family: Arial;">
        <h1>Payment Successful!</h1>
        <p>Your deposit payment has been processed successfully.</p>
        <p>You can now participate in the auction.</p>
        <p>Redirecting to auction <?php echo $auctionId ? "#$auctionId" : ""; ?> in 3 seconds...</p>
        <a href="<?php echo $redirectUrl; ?>">Click here if not redirected automatically</a>
    </div>
</body>
</html>
