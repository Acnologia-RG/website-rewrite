<?php
/**
 * Payment Details Screen - payment-details.php
 */

/**
 * HoroBot / PayPal API Settings
 */
require __DIR__ . '/store/settings.php';

/**
 * Define Necessary Values;
 * Collect Type-Safe Post Value
 */
$product = [];
$_POST['id'] = (integer)base64_decode( $_POST['id'] );
$_SESSION['cart'] = $_POST['id']; // Prepare PayPal-Ready Information

/**
 * Redirect to Store if no Product is Selected
 */
if ( empty( $_POST['id'] > 0 ) ) {
    header( "Location: $url", 302 );
    exit();
}

/**
 * Define Product Details
 */
else {
    $product = $productsArray[ $_POST['id'] ];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Details | Horobot - A Discord Bot</title>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
</head>
<body>
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
</body>
</html>

