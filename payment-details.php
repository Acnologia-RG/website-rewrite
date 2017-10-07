<?php
/**
 * Payment Details Screen - payment-details.php
 */

/**
 * HoroBot / PayPal API Settings
 */
require __DIR__ . '/store/settings.php';

/**
 * Declare Product Array
 */
$product = [];

/**
 * Redirect to Store if no Product is Selected
 */
if ( !($_SESSION['cart'] > 0) ) {
    header( "Location: $url/shop.php", 302 );
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
    <h1>Payment Details</h1>
    <h2>
        Product Information
    </h2>
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
                <td>$<?= $product['price'] ?>(USD)</td>
            </tr>
        </tbody>
    </table>
    <h2>
        Payment
    </h2>
    <div id="paypal-button"></div>
    <script>
        var CREATE_PAYMENT_URL  = '<?= $url ?>/paypal/create-payment.php';
        var EXECUTE_PAYMENT_URL = '<?= $url ?>/paypal/execute-payment.php';

        paypal.Button.render({

            env: 'sandbox', // Or 'sandbox'

            commit: true, // Show a 'Pay Now' button

            payment: function() {
                return paypal.request.post(CREATE_PAYMENT_URL).then(function(data) {
                    return data.id;
                });
            },

            onAuthorize: function(data) {
                return paypal.request.post(EXECUTE_PAYMENT_URL, {
                    paymentID: data.paymentID,
                    payerID:   data.payerID
                }).then(function() {
                    console.log('Payment Complete.');
                });
            }

        }, '#paypal-button');
    </script>
    <?php include_once __DIR__ . '/includes/assets.php'; ?>
</body>
</html>

