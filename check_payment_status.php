<?php
// check_payment_status.php

$paymentId = $_GET['payment_id'];
$statusFile = "payment_status_$paymentId.txt";

// Check if status file exists and read status
if (file_exists($statusFile)) {
    $status = file_get_contents($statusFile);
    if ($status == 'PAID') {
        echo "Payment successful!";
    } elseif ($status == 'DECLINED') {
        echo "Payment declined.";
    } else {
        echo "Payment is still processing. Please wait...";
    }
} else {
    echo "Waiting for payment...";
}
?>