<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../settings/general.php';

session_start();

$ch = curl_init();

if (!isset($_GET['code']) && !isset($_SESSION['access_token']))
	header("Location: https://discordapp.com/api/oauth2/authorize?response_type=code&client_id={$discordClientId}&scope=identify%20guilds&redirect_uri=" . site_url() . "/checkout");

?>