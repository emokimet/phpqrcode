<?php
require 'vendor/autoload.php'; // Autoload Composer dependencies

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;

// Get invoice and amount from URL parameters
$faktura = isset($_GET['faktura']) ? $_GET['faktura'] : '0';
$amount = isset($_GET['amount']) ? $_GET['amount'] : '0';

// Create Swish payment URL (this will be encoded in the QR code)
$swishUrl = "swish://paymentrequest?token=" . urlencode("faktura=$faktura&amount=$amount");

// Generate the QR code using the endroid/qr-code library
$qrCode = Builder::create()
    ->writer(new PngWriter())
    ->writerOptions([])
    ->data($swishUrl)
    ->encoding(new Encoding('UTF-8'))
    ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
    ->size(300)
    ->margin(10)
    ->build();

// Save the QR code as an image
$qrCode->saveToFile(__DIR__ . '/images/swish_qr.png');

// Display the QR code in the HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swish Payment</title>
</head>
<body>
    <h1>Scan to Pay with Swish</h1>
    <img src="images/swish_qr.png" alt="Swish QR Code">
</body>
</html>