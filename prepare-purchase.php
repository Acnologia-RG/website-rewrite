<?php
/**
* Ensure that the user is logged into Discord
*/
require __DIR__ . '/includes/discord-user-access-token-required.php';

/**
 * HoroBot / PayPal API Settings
 */
require __DIR__ . '/store/settings.php';

/**
 * Define Necessary Values;
 * Collect Type-Safe Post Value
 */
if ( isset($_POST['id']) ) {
    $_POST['id'] = (integer)base64_decode( $_POST['id'] );
    $_SESSION['cart'] = $_POST['id']; // Prepare PayPal-Ready Information
}

header( "Location: $url/payment-details.php", 302 );
exit();