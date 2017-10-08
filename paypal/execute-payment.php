<?php
/**
 * Execute PayPal Payment - execute-payment.php
 */

/**
 * PayPal PHP API Libraries & Settings
 */
require __DIR__ . '/../bootstrap.php';

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
$headers[] = "Authorization: Bearer " . $_SESSION['paypalToken']->access_token;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

/**
 * Echo Error or Result
 */
$result = curl_exec($ch);
$resultJSON = null;
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
    error_log( 'cURL Error: ' . curl_error($ch) );
} else {
    echo $result;
    $resultJSON = json_decode($result);
    $_SESSION['payment'] = $resultJSON;
}

/**
 * Close cURL Operations
 */
curl_close($ch);

/**
 * Clear User Store Session
 */
unset($_SESSION['invoiceNumber']);
unset($_SESSION['paypalToken']);
unset($_SESSION['cart']);
unset($_SESSION['confirmation']);
unset($_SESSION['payment']);

/**
 * Update User in Database
 */
if ( $resultJSON->state === 'approved' ) {
    global $productsArray;

//    $payerID = $resultJSON->payer->payer_info->payer_id;
    $discordID = (int)$resultJSON->transactions[0]->custom;
    $gems = $productsArray[$resultJSON->transactions[0]->item_list->items[0]->sku]['gems'];

    try {
        if ( $connection = pg_connect( $dbConnectionString ) ) {
            $query = 'UPDATE users.user SET foxGems=foxGems + $1 WHERE id=$2;';
            $request = pg_prepare( $connection, 'payment', $query );
            if ( $request ) {
                pg_execute( $connection, 'payment', array((int)$gems, (int)$discordID) );
            }
            pg_close( $connection );
        } else {
            error_log('No databse connection established.');
        }
    } catch( Exception $e ) {
        echo $e;
        error_log($e);
    }
}
