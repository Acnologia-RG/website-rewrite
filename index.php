<!DOCTYPE html>
<html>
	<head>
        <title>HoroBot - A Discord Bot</title>
        <meta name="description" content="HoroBot - a very capable and helpful Discord bot">
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://horobot.pw/" />
        <meta property="og:site_name" content="HoroBot" />
        <meta property="og:title" content="HoroBot - Discord Bot" />
        <meta property="og:image" content="./img/icon-small.png" />
        <meta property="og:description" content="HoroBot - a very capable and helpful Discord bot" />
        <meta name="twitter:card" value="HoroBot - a very capable and helpful Discord bot" />
        <?php require_once __DIR__ . '/includes/head.php'; ?>
    </head>
	<body id="top"><?php session_start();var_dump($_SESSION['payment']);var_dump($_SESSION['paypalToken']); ?>
		<?php include __DIR__ . '/includes/navigation.php'; ?>

		<div class="container">
			<div class="row" style="margin-top: 8%">
				<hr>
			</div>
			<div class="row">
				<div class="mol-md-5">
			</div>
		</div>

		<div class="container">
			<center>
				<img class="circle-icon-big animated fadeInLeft" src="./img/icon-large.png">
			</center>
		</div>

		<div class="container">
			<div class="row" style="margin-top: 0%">
				<hr>
			</div>
			<div class="row">
				<div class="mol-md-5">
			</div>
		</div>

        <?php include_once __DIR__ . '/includes/assets.php'; ?>
	</body>
</html>
