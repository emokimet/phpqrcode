<?php
if (isset($_POST['result']) && isset($_POST['format'])) {
    $base64_result = $_POST['result'];
    $format = $_POST['format'];
    $paymentId = $_POST['paymentId'];

    // Display the image
    // echo '<img src="data:image/' . htmlspecialchars($format) . ';base64,' . htmlspecialchars($base64_result) . '" alt="Swish QR Code">';
} else {
    echo 'No image data received.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Swish Payment</title>
    <style>
        .qr-code {
            margin: 20px;
            text-align: center;
        }
        .status {
            font-size: 1.5em;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="qr-code">
    <h2>Scan to Pay</h2>
    <img src="<?php echo 'data:image/' . htmlspecialchars($format) . ';base64,' . htmlspecialchars($base64_result) ?>" alt="QR Code for Swish Payment">
</div>

<div class="status" id="status">Waiting for payment...</div>

<script>
    function checkPaymentStatus() {
        const paymentId = <?php echo json_encode($paymentId); ?>;

        fetch(`check_payment_status.php?payment_id=${paymentId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById("status").innerText = data;

                // Stop polling if payment is completed
                if (data.includes("successful") || data.includes("declined")) {
                    clearInterval(statusInterval);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Poll the payment status every 5 seconds
    const statusInterval = setInterval(checkPaymentStatus, 5000);
</script>

</body>
</html>
