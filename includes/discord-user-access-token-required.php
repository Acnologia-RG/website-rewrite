<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../store/settings.php';

session_start();

$provider = new \Discord\OAuth\Discord([
    'clientId' => $discordClientId,
    'clientSecret' => $discordClientSecret,
//    'redirectUri' => "$url/callback?original_request=".urlencode("$url/payment-details.php"),
    'redirectUri' => "$url/callback?original_request=$url/payment-details.php",
]);

if ( !isset($_SESSION['access_token']) ) {
    header( 'Location: '.$provider->getAuthorizationUrl(array('scope' => 'identify guilds')), 302);
    exit();
}
