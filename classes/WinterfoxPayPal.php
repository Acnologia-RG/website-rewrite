<?php
include_once __DIR__ . '/../settings/general.php';
include_once __DIR__ . '/../data/products.php';
class WinterfoxPayPal {
    private $paypalToken;

    public function __construct() {
        session_start();
        global $debug,
               $currentTime,
               $paypalClientId,
               $paypalClientSecret;

        // Set API Context
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $paypalClientId,    // ClientID
                $paypalClientSecret // ClientSecret
            )
        );

        // Configure API Context
        $apiContext->setConfig(
            array(
                'log.LogEnabled'    => $debug,          // Enable if Currently Debugging
                'log.FileName'      => 'PayPal.log',    // Name of Logging File
                'log.LogLevel'      => 'DEBUG'          // Level of Logging (DEBUG | FINE)
            )
        );

        if (  isset( $_SESSION['paypalToken'] ) && ( ($currentTime - $_SESSION['paypalToken']->creation_time) >= $_SESSION['paypalToken']->expires_in ) ) {
            $this->paypalToken = $_SESSION['paypalToken'];
        } else {
            $this->paypalToken = $this->getToken()->access_token;
        }
    }

    public function getToken() {
        global $currentTime,
               $paypalUserTokenURL,
               $paypalClientId,
               $paypalClientSecret;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $paypalUserTokenURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $paypalClientId . ":" . $paypalClientSecret);

        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = "Accept-Language: en_US";
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            $json = json_decode( $result );
            $_SESSION['paypalToken'] = $json;
            $_SESSION['paypalToken']->creation_time = $currentTime;
        }

        curl_close ($ch);

        return $_SESSION['paypalToken'];
    }

    public function createPayment() {
        global $debug,
               $url,
               $paypalCreatePaymentURL,
               $taxing,
               $tax,
               $productsArray,
               $paymentType,
               $paymentMethod,
               $currencyCode,
               $shipping,
               $shippingDiscount,
               $handling,
               $insurance,
               $paymentDescription,
               $noteToPayer;
        $_SESSION['store'] = convertObjectToObject( $_SESSION['store'], 'Store' );

        /**
         * Prepare Invoice Number
         */
        $invoiceNumber = uniqid();
        if ( isset($_SESSION['invoiceNumber']) ) {
            $invoiceNumber = $_SESSION['invoiceNumber'];
        } else {
            $_SESSION['invoiceNumber'] = $invoiceNumber;
        }

        /**
         * Redirect to Store if no Product is Selected
         */
//        if ( $_SESSION['cart'] <= 0 ) {
//            header( "Location: $url", 302 );
//            exit();
//        }

        /**
         * Set Product Values
         */
        $productId = $_SESSION['store']->getCart();
        reset( $productId ); $productId = trim( key( $productId ) );
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
         * Format Numbers
         */
        $productPrice = number_format( $productPrice, 2, '.', ',' );
        $productTax = number_format( $productTax, 2, '.', ',' );
        $subtotal = number_format( $subtotal, 2, '.', ',' );
        $subtotalTax = number_format( $subtotalTax, 2, '.', ',' );
        $total = number_format( $total, 2, '.', ',' );

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
                        "custom": "'.$_SESSION['discordId'].'",
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
                    "return_url": "'.get_the_permalink( get_page_by_title( 'Checkout' )->ID ).'?createPayment=TRUE",
                    "cancel_url": "'.get_the_permalink( get_page_by_title( 'Checkout' )->ID ).'?cancelPayment=TRUE"
                }
            }';
        $requestJSON = str_replace(PHP_EOL, '', $requestJSON);

        /**
         * Prepare and Execute cURL Operations
         */
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $paypalCreatePaymentURL);
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
            echo 'cURL Error: ' . curl_error($ch);
            error_log( 'cURL Error: ' . curl_error($ch) );
        } else {
//            if ( $debug ) echo json_encode($result);
            $resultJSON = json_decode($result);
            $_SESSION['confirmation'] = $resultJSON;
        }

        /**
         * Close cURL Operations
         */
        curl_close($ch);

        return $result;
    }

    public function executePayment() {
        session_start();
        global $debug,
               $dbConnectionString,
               $paypalExecutePaymentURL;
        $_SESSION['store'] = convertObjectToObject( $_SESSION['store'], 'Store' );

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

        curl_setopt($ch, CURLOPT_URL, "$paypalExecutePaymentURL/{$_SESSION['confirmation']->id}/execute");
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
            if ( $debug ) echo $result;
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
        unset($_SESSION['Store']);
        unset($_SESSION['confirmation']);
        unset($_SESSION['payment']);

        /**
         * Update User in Database
         */
        if ( $resultJSON->state === 'approved' ) {
            global $productsArray;

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
                    error_log('No database connection established.');
                }
            } catch( Exception $e ) {
                echo $e;
                error_log($e);
            }
        }

        return $result;
    }
}