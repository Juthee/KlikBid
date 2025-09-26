<?php
// Simple WebXPay callback handler without Laravel middleware
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log all incoming data
$logData = [
    'method' => $_SERVER['REQUEST_METHOD'],
    'post_data' => $_POST,
    'get_data' => $_GET,
    'headers' => getallheaders(),
    'raw_input' => file_get_contents('php://input')
];

file_put_contents('webxpay_callback.log', date('Y-m-d H:i:s') . " - " . json_encode($logData) . "\n", FILE_APPEND);

echo "Callback received successfully";
?>
