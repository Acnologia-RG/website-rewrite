<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../store/settings.php';

session_start();

$provider = new \Discord\OAuth\Discord([
    'clientId' => $discordClientId,
    'clientSecret' => $discordClientSecret,
    'redirectUri' => "$url/checkout",
]);

if ( isset($_SESSION['access_token']) ) {
    $token = $_SESSION['access_token'];
    $user = null;
    try {
        $user = $provider->getResourceOwner($token);
        $_SESSION['discordId'] = $user->getId();
    } catch (Exception $e) {
        header("Location: $url/callback?logout=1", 302);
        exit();
    }
} else {
    header( 'Location: '.$provider->getAuthorizationUrl(array('scope' => 'identify guilds')), 302);
    exit();
}
