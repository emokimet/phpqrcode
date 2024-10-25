<?php

// Read JSON data from Swish callback
$data = json_decode(file_get_contents('php://input'), true);

// Check payment status and update a file or database
$paymentId = $data['paymentReference']; // Example ID reference for the payment
$status = $data['status']; // This could be 'PAID', 'DECLINED', etc.

// Save status to a file or database (for simplicity, we’ll use a text file here)
file_put_contents("payment_status_$paymentId.txt", $status);

// Respond with 200 OK to Swish
http_response_code(200);
?>
