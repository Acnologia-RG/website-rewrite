<?php
/**
 * PayPal Bootstrap - bootstrap.php
 */

/**
 * PayPal API Autoload
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * HoroBot / PayPal API Settings
 */
require_once __DIR__ . '/store/settings.php';

// API Context:
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        $clientId,    // ClientID
        $clientSecret // ClientSecret
    )
);

/**
 * Adjust Configuration for API Context
 */
$apiContext->setConfig(
    array(
        'log.LogEnabled'    => $debug,          // Enable if Currently Debugging
        'log.FileName'      => 'PayPal.log',    // Name of Logging File
        'log.LogLevel'      => 'DEBUG'          // Level of Logging (DEBUG | FINE)
    )
);

/**
 * Set User Access Token (if not already set)
 */
require_once __DIR__ . '/paypal/user-access-token.php';

/**
 * Log Errors
 */
if ( $debug ) {
    ini_set("log_errors", 1);
    ini_set("error_log", "/tmp/php-error.log");
}
