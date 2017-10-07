<?php
/**
 * Execute PayPal Payment - execute-payment.php
 */

/**
 * PayPal PHP API Libraries & Settings
 */
require '../bootstrap.php';

/**
 * Collect Payer ID
 */
$payerID = strip_tags($_POST['payerID']);

/**
 * Set JSON Values
 */
$requestJSON =
    '{
        "payer_id": "'.$payerID.'"
    }';
$requestJSON = str_replace(PHP_EOL, '', $requestJSON);

/**
 * Prepare and Execute cURL Operations
 */
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "$executePaymentURL/{$_SESSION['confirmation']->id}/execute");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $requestJSON);
curl_setopt($ch, CURLOPT_POST, 1);

$headers = array();
$headers[] = "Content-Type: application/json";
$headers[] = "Authorization: Bearer " . $_SESSION['token']->access_token;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

/**
 * Echo Error or Result
 */
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    echo $result;
    $resultJSON = json_decode($result);
    $_SESSION['payment'] = $resultJSON;
}

/**
 * Close cURL Operations
 */
curl_close($ch);
