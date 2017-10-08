<?php
/**
 * Payment Details Screen - checkout.php
 */

/**
 * HoroBot / PayPal API Settings
 */
require __DIR__ . '/bootstrap.php';

/**
 * Ensure Discord Login
 */
$provider = new \Discord\OAuth\Discord([
    'clientId' => $discordClientId,
    'clientSecret' => $discordClientSecret,
    'redirectUri' => "$url/checkout",
]);
if (isset($_GET['code']) && $_GET['code']) {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code'],
    ]);
    $_SESSION['access_token'] = $token;
}

/**
 * Declare Product Array
 */
$product = [];

/**
 * Redirect to Store if no Product is Selected
 */
if ( $_SESSION['cart'] < 1 ) {
    header( "Location: $url/shop", 302 );
    exit();
}
/**
 * Define Product Details
 */
else {
    $product = $productsArray[ $_SESSION['cart'] ];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Details | Horobot - A Discord Bot</title>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <meta name="description" content="HoroBot - a very capable and helpful Discord bot">
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://horobot.pw/" />
    <meta property="og:site_name" content="HoroBot" />
    <meta property="og:title" content="HoroBot - Discord Bot" />
    <meta property="og:image" content="./img/icon-small.png" />
    <meta property="og:description" content="HoroBot - a very capable and helpful Discord bot" />
    <meta name="twitter:card" value="HoroBot - a very capable and helpful Discord bot" />
    <?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/navigation.php'; ?>

    <div class="container">
        <div class="row" style="margin-top: 8%">
            <hr>
        </div>
        <div class="row">
            <div class="mol-md-5">
            </div>
        </div>

        <div class="container checkout">
            <div class="row">
                <div class="col-md-12">
                    <h1>Payment Details</h1>
                    <h2>
                        Product Information
                    </h2>
                    <aside>
                        <table>
                            <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Product Description</th>
                                <th>Product Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><?= $product['name'] ?></td>
                                <td><?= $product['description'] ?></td>
                                <td>€<?= $product['price'] ?>(EUR)</td>
                            </tr>
                            </tbody>
                        </table>
                    </aside>
                    <h2>
                        Payment
                    </h2>
                    <div id="paypal-button"></div>
                    <script>
                        var CREATE_PAYMENT_URL  = '<?= $url ?>/paypal/create-payment.php';
                        var EXECUTE_PAYMENT_URL = '<?= $url ?>/paypal/execute-payment.php';

                        paypal.Button.render({

                            env: 'sandbox',
                            commit: true, // Show a 'Pay Now' button

                            payment: function() {
                                return paypal.request.post(CREATE_PAYMENT_URL).then(function(data) {
                                    return JSON.parse(data).id;
                                });
                            },

                            onAuthorize: function(data) {
                                return paypal.request.post(EXECUTE_PAYMENT_URL, {
                                    paymentID: data.paymentID,
                                    payerID:   data.payerID
                                }).then(function() {
                                    // Remove Purchase Button
                                    var paypalButton = document.getElementById( 'paypal-button' );
                                    paypalButton.parentElement.removeChild( paypalButton );

                                    // Create Success Elements/Text
                                    var paypalMessageOverlay = document.createElement( 'DIV' );
                                    var paypalMessageContainer = document.createElement( 'ARTICLE' );
                                    var paypalMessageTitle = document.createElement( 'H2' );
                                    var paypalMessageTitleText = document.createTextNode( 'PayPal Transaction Details' );
                                    var paypalMessageParagraph = document.createElement( 'P' );
                                    var paypalMessageParagraphText = document.createTextNode( 'Your payment was successful, thank you very much!' );
                                    var paypalMessageButton = document.createElement( 'BUTTON' );
                                    var paypalMessageButtonText = document.createTextNode( 'Okay' );

                                    // Prepare Success Elements/Text
                                    paypalMessageTitle.appendChild( paypalMessageTitleText );
                                    paypalMessageContainer.appendChild( paypalMessageTitle );
                                    paypalMessageParagraph.appendChild( paypalMessageParagraphText );
                                    paypalMessageContainer.appendChild( paypalMessageParagraph );
                                    paypalMessageButton.appendChild( paypalMessageButtonText );
                                    paypalMessageContainer.appendChild( paypalMessageButton );
                                    paypalMessageOverlay.appendChild( paypalMessageContainer );

                                    // Add ID to Container
                                    paypalMessageOverlay.setAttribute( 'id', 'paypal-message' );

                                    // Add Animation Classes
                                    paypalMessageOverlay.setAttribute( 'class', 'animated fadeIn' );
                                    paypalMessageContainer.setAttribute( 'class', 'animated fadeInBottom' );

                                    // Add Event Listener to Button
                                    paypalMessageButton.addEventListener( 'click', function(){
                                        window.location = '<?= $url ?>';
                                    });

                                    // Add Message to HTML Document (wait 1 second)
                                    setTimeout(function(){
                                        document.body.appendChild( paypalMessageOverlay );
                                    }, 1000);
                                });
                            }

                        }, '#paypal-button');
                    </script>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row" style="margin-top: 0%">
                <hr>
            </div>
            <div class="row">
                <div class="mol-md-5">
                </div>
        </div>
        <?php include_once __DIR__ . '/includes/assets.php'; ?>
</body>
</html>

