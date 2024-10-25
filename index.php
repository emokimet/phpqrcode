<?php
$amount = isset($_GET['amount']) ? $_GET["amount"] : '10';
$message = isset($_GET['message']) ? $_GET["message"] : 'Default';
$payee = isset($_GET['payee']) ? $_GET["payee"] : '0000000001';
$paymentId = isset($_GET['paymentId']) ? $_GET["paymentId"] : '12345';
$format = 'jpg'; // Use jpg, png or svg

// Passing our data to the array
$data = json_encode(array(
    "amount" => array(
        "type" => "swishNumber",
        "value" => "$amount",
    ),

    "format" => "$format",
    "size" => "300",

    "message" => array(
        "type" => "swishString",
        "value" => "$message"
    ),

    "payee" => array(
        "type" => "swishString",
        "value" => "$payee",
    ),

    "paymentId" => array(
        "type" => "swishNumber",
        "value" => "$paymentId",
    ),

    "type" => "object"
));

$data_string = ($data);

function isMobile()
{
    // Get the User-Agent string
    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    // Define an array of mobile user agents
    $mobileAgents = array(
        'iPhone',
        'iPod',
        'Android',
        'webOS',
        'BlackBerry',
        'Windows Phone',
        'Opera Mini',
        'IEMobile',
        'Mobile'
    );

    // Check if the User-Agent string contains any of the mobile agents
    foreach ($mobileAgents as $device) {
        if (stripos($userAgent, $device) !== false) {
            return true; // Mobile device detected
        }
    }

    return false; // Not a mobile device
}

// Usage
if (isMobile()) {
    $url = "check_swish.php";
    header("Location: $url");
} else {
    // Using CURL to get the generated QR from Swish API
    $ch = curl_init('https://mpc.getswish.net/qrg-swish/api/v1/prefilled');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
        )
    );

    $result = curl_exec($ch);

    if ($result === false) {
        echo curl_error($ch);
    }

    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    // If any errors occurs
    if ($response_code == 200 or $response_code == 201) {
        $base64_result = base64_encode($result);

        echo '<form id="redirectForm" action="payment_page.php" method="post">
            <input type="hidden" name="result" value="' . $base64_result . '">
            <input type="hidden" name="paymentId" value="' . $paymentId . '">
            <input type="hidden" name="format" value="' . $format . '">
          </form>
          <script>document.getElementById("redirectForm").submit();</script>';
    } else {
        echo 'The request failed. Code: ' . $response_code;
    }
}
?>