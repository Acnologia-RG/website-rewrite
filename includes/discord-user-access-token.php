<?php
require_once __DIR__ . '/../vendor/autoload.php';

$provider = new \Discord\OAuth\Discord([
    'clientId' => '289381714885869568',
    'clientSecret' => 'bXQ-fZs2ud9i_6cVqUhnSgAFA6G0ePIe',
    'redirectUri' => 'https://horobot.pw/callback',
]);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $token = $_SESSION['access_token'];
    $user = null;
    try {
        $user = $provider->getResourceOwner($token);
    } catch (Exception $e) {
        header('Location: https://horobot.pw/callback?logout=1');
    }

    echo '<li><a href="./profile" class="btn btn-default btn-login" style="padding: 0; padding-right: 10px; padding-left: 10px"><img src="https://cdn.discordapp.com/avatars/' . $user->id . '/' . $user->avatar . '.png?size=128" width="50" height="50"> <b>' . $user->username . '</b></a></li>';
    echo '<li><a href="./callback?logout=1" class="btn btn-default btn-invite"><i class="glyphicon glyphicon-log-in"></i> <b>Logout</b></a></li>';
} else {
    echo '<li><a href="' . $provider->getAuthorizationUrl(array('scope' => 'identify guilds')) . '" class="btn btn-default btn-invite"><i class="glyphicon glyphicon-log-in"></i> <b>Login</b></a></li>';
}