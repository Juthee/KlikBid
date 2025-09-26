<?php
$result = file_put_contents('test-log.txt', 'Test write: ' . date('Y-m-d H:i:s') . "\n");
if ($result) {
    echo "File write successful!";
} else {
    echo "File write failed!";
}
?>
