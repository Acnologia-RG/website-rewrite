<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../store/settings.php';

session_start();

$provider = new \Discord\OAuth\Discord([
    'clientId' => $discordClientId,
    'clientSecret' => $discordClientSecret,
    'redirectUri' => "$url/checkout",
]);

if ( !isset($_SESSION['access_token']) ) {
    header( 'Location: '.$provider->getAuthorizationUrl(array('scope' => 'identify guilds')), 302);
    exit();
}
