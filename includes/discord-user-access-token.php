<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../store/settings.php';

session_start();

$provider = new \Discord\OAuth\Discord([
    'clientId' => $discordClientId,
    'clientSecret' => $discordClientSecret,
    'redirectUri' => "$url/callback",
]);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $token = $_SESSION['access_token'];
    $user = null;
    try {
        $user = $provider->getResourceOwner($token);
        $_SESSION['discordId'] = $user->getId();
    } catch (Exception $e) {
        header("Location: $url/callback?logout=1", 302);
        exit();
    }

    $userArray = $user->toArray();
    echo '<li><a href="./profile" class="btn btn-default btn-login" style="padding: 0; padding-right: 10px; padding-left: 10px"><img src="https://cdn.discordapp.com/avatars/' . $user->getId() . '/' . $userArray['avatar'] . '.png?size=128" width="50" height="50"> <b>' . $userArray['username'] . '</b></a></li>';
    echo '<li><a href="./callback?logout=1" class="btn btn-default btn-invite"><i class="glyphicon glyphicon-log-in"></i> <b>Logout</b></a></li>';
} else {
    echo '<li><a href="' . $provider->getAuthorizationUrl(array('scope' => 'identify guilds')) . '" class="btn btn-default btn-invite"><i class="glyphicon glyphicon-log-in"></i> <b>Login</b></a></li>';
}