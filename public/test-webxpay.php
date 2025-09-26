<?php
// Corrected WebXPay test using exact sample format
$plaintext = '525|100';
$publickey = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCv03MBjd+WVCckBeNBnpVV5nEv
TKq8sReshDTnJR2XpZZGb9TqKncKw19c6FdX8aFfxw4XEnAPtewfPId4iNkMKYyu
vuLPaQ6xiyYziaKr/hUobwGPoj6Hskl3Kw4BP9uFK0K96ChuajX6DvENH+LiJXNJ
U4N8GjVpr4jHkpLT8QIDAQAB
-----END PUBLIC KEY-----";

// Encrypt using the exact same method as WebXPay sample
openssl_public_encrypt($plaintext, $encrypt, $publickey);
$payment = base64_encode($encrypt);

// Use staging URL
$url = 'https://stagingxpay.info/index.php?route=checkout/billing';

// Custom fields exactly like sample
$custom_fields = base64_encode('test|123|deposit|456');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebXPay Test - KlikBid</title>
</head>
<body>
    <h2>WebXPay Integration Test</h2>
    <form action="<?php echo $url; ?>" method="POST">
        <!-- All required fields based on WebXPay sample -->
        First name: <input type="text" name="first_name" value="John"><br>
        Last name: <input type="text" name="last_name" value="Doe"><br>
        Email: <input type="text" name="email" value="test@example.com"><br>
        Contact Number: <input type="text" name="contact_number" value="0777888999"><br>
        Address Line 1: <input type="text" name="address_line_one" value="Test Address"><br>
        Address Line 2: <input type="text" name="address_line_two" value=""><br>
        City: <input type="text" name="city" value="Colombo"><br>
        State: <input type="text" name="state" value="Western"><br>
        Zip/Postal Code: <input type="text" name="postal_code" value="10300"><br>
        Country: <input type="text" name="country" value="Sri Lanka"><br>
        Currency: <input type="text" name="process_currency" value="LKR"><br>
        CMS: <input type="text" name="cms" value="PHP"><br>
        Custom: <input type="text" name="custom_fields" value="<?php echo $custom_fields; ?>"><br>
        Mechanism: <input type="text" name="enc_method" value="JCs3J+6oSz4V0LgE0zi/Bg=="><br>

        <!-- Hidden fields -->
        <input type="hidden" name="secret_key" value="e27d95b3-12d6-4511-aa0b-9ea234a6ab2a">
        <input type="hidden" name="payment" value="<?php echo $payment; ?>">

        <br><br>
        <input type="submit" value="Test WebXPay Payment">
    </form>
</body>
</html>
