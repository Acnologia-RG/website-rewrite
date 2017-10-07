<?php
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

/**
 * Ensure that the user is logged into Discord
 */
require __DIR__ . '/includes/discord-user-access-token-required.php';

header( "Location: $url/checkout", 302 );
exit();