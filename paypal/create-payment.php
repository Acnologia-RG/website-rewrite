<?php
/**
 * Create PayPal Payment - create-payment.php
 */

/**
* PayPal PHP API Libraries & Settings
*/
require '../bootstrap.php';

/**
 * Redirect to Store if no Product is Selected
 */
if ( empty( $_SESSION['cart'] > 0 ) ) {
    header( "Location: $url", 302 );
    exit();
}

/**
 * Prepare Invoice Number
 */
$invoiceNumber = rand( (int)1000000000, (int)9999999999 );
if ( $_SESSION['invoiceNumber'] ) {
    $invoiceNumber = $_SESSION['invoiceNumber'];
} else {
    $_SESSION['invoiceNumber'] = $invoiceNumber;
}

/**
 * Set Product Values
 */
$productId = $_SESSION['cart'];
$product = $productsArray[$productId];
$productName = $product['name'];
$productDescription = $product['description'];
$productQuantity = 1;
$productPrice = $product['price'];
$productTax = 0;
if ( $taxing ) $productTax = ($product['price']*$tax); // Only apply tax if enabled
$subtotal = $product['price'];
$subtotalTax = $productTax;
$total = $subtotal+$subtotalTax;

/**
 * Set JSON Values
 */
$requestJSON =
    '{
        "intent": "'.$paymentType.'", 
        "payer":
        { 
            "payment_method": "'.$paymentMethod.'"
        }, 
        "transactions":
        [
            {
                "amount":
                {
                    "total": "'.$total.'",
                    "currency": "'.$currencyCode.'",
                    "details":
                    {
                        "subtotal": "'.$subtotal.'",
                        "tax": "'.$subtotalTax.'",
                        "shipping": "'.$shipping.'",
                        "handling_fee": "'.$handling.'",
                        "shipping_discount": "'.$shippingDiscount.'",
                        "insurance": "'.$insurance.'"
                    }
                },
                "description": "'.$paymentDescription.'",
                "custom": "'.$_SESSION['discordId'].'"
                "invoice_number": "'.$invoiceNumber.'",
                "payment_options":
                {
                    "allowed_payment_method": "INSTANT_FUNDING_SOURCE"
                },
                "soft_descriptor": "'.$paymentDescription.'",
                "item_list":
                {
                    "items":
                    [
                        {
                            "name": "'.$productName.'",
                            "description": "'.$productDescription.'",
                            "quantity": "'.$productQuantity.'",
                            "price": "'.$productPrice.'",
                            "tax": "'.$productTax.'",
                            "sku": "'.$productId.'",
                            "currency": "'.$currencyCode.'"
                        }
                    ]
                }
            }
        ],
        "note_to_payer": "'.$noteToPayer.'",
        "redirect_urls":
        {
            "return_url": "'.$url.'/paypal/create-payment.php",
            "cancel_url": "'.$url.'/paypal/cancel-payment.php"
        }
    }';
$requestJSON = str_replace(PHP_EOL, '', $requestJSON);

/**
 * Prepare and Execute cURL Operations
 */
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $createPaymentURL);
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
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    echo $result;
    $resultJSON = json_decode($result);
    $_SESSION['confirmation'] = $resultJSON;
}

/**
 * Close cURL Operations
 */
curl_close($ch);
