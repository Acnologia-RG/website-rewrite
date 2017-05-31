<!DOCTYPE html>

<html>
	<head>
		<title>HoroBot - A Discord Bot</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="WiNteR">
		<meta name="description" content="HoroBot - a very capable and helpful Discord bot">
		<link rel="icon" href="./img/favicon.ico" type="image/x-icon">
		<link href="https://fonts.googleapis.com/css?family=Raleway:300" rel="stylesheet" type="text/css">
		<link href="./css/bootstrap.css" rel="stylesheet" type="text/css">
		<link href="./css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="./css/animate.css" rel="stylesheet" type="text/css">
		<link href="./css/main.css" rel="stylesheet" type="text/css">
	</head>
	<body id="top">
		<nav class="navbar navbar-inverse navbar-fixed-top animated fadeInDown" role="navigation" style="background: linear gradient(to right, rgb(255, 255, 255), rgb(242, 242, 242));">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="./index">
						<img class="circle-icon" src="./img/icon-small.png">
						<h1>HoroBot</h1>
						<a class="btn btn-default btn-invite hidden-xs" href="https://discordapp.com/oauth2/authorize?client_id=289381714885869568&scope=bot&permissions=372435975" target="_blank">
							<b>Invite HoroBot</b>
						</a>
					</a>
					<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right" style="font-size: 14px">
						<li>
							<a class="btn btn-default btn-invite hidden-xs" href="./index">
								<i class="glyphicon glyphicon-home"></i>
								<b>Home</b>
							</a>
						</li>
						<li>
							<a type="button" class="btn btn-default btn-invite hidden-xs dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<i class="glyphicon glyphicon-info-sign"></i>
								<b>Commands & Info</b>
								<span class="caret">
							</a>
							<ul class="dropdown-menu">
								<li>
									<a href="./about">
										<i class="glyphicon glyphicon-question-sign"></i>
										<b>About</b>
									</a>
									<a href="./commands">
										<i class="glyphicon glyphicon-book"></i>
										<b>Commands</b>
									</a>
									<a href="https://github.com/WinteryFox/HoroBot" target="_blank">
										<i class="glyphicon glyphicon-console"></i>
										<b>GitHub</b>
									</a>
									<a href="https://patreon.com/HoroBot" target="_blank">
										<i class="glyphicon glyphicon-euro"></i>
										<b>Patreon</b>
									</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="https://discord.gg/MCUTSZz" class="btn btn-default btn-invite hidden-xs" target="_blank">
								<i class="glyphicon glyphicon-comment"></i>
								<b>Support</b>
							</a>
						</li>
						<?php
							require_once __DIR__ . '/vendor/autoload.php';
							
							session_start();
							
							$provider = new \Discord\OAuth\Discord([
								'clientId' => '289381714885869568',
								'clientSecret' => 'KTGcUobdJRYWy_83Uhys5Dob-_ysHbBH',
								'redirectUri' => 'http://localhost/callback',
							]);
							
							if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
								$token = $_SESSION['access_token'];
								$user = null;
								try {
									$user = $provider->getResourceOwner($token);
								} catch (Exception $e) {
									header('Location: http://localhost/callback?logout=1');
								}
								
								echo '<li><a href="./profile" class="btn btn-default btn-login" style="padding: 0; padding-right: 10px; padding-left: 10px"><img src="https://cdn.discordapp.com/avatars/' . $user->id . '/' . $user->avatar . '.png?size=128" width="50" height="50"> <b>' . $user->username . '</b></a></li>';
								echo '<li><a href="./callback?logout=1" class="btn btn-default btn-invite"><i class="glyphicon glyphicon-log-in"></i> <b>Logout</b></a></li>';
							} else {
								echo '<li><a href="' . $provider->getAuthorizationUrl(array('scope' => 'identify guilds')) . '" class="btn btn-default btn-invite"><i class="glyphicon glyphicon-log-in"></i> <b>Login</b></a></li>';
							}
						?>
					</ul>
				</div>
			</div>
		</nav>
		
		<div class="container">
			<div class="row" style="margin-top: 8%">
				<hr>
			</div>
			<div class="row">
				<div class="mol-md-5">
			</div>
		</div>
		
		<div class="container animated fadeInLeft">
			<h3 class="orange">Developer</h3>
			<table class="table devtable" style="border-color: #eee">
				<tbody>
					<tr class="dev">
						<td>
							<br>
							<a href="https://github.com/WinteryFox" target="_blank" style="color: #4c4c4c">
								<div class="circle-icon-dev" style="background: url('./img/avatars/fox.png') no-repeat; background-size: 124px 124px; background-position: center"></div>
								<br>
								<h4>Le Winter-y Fox</h4>
							</a>
						</td>
					</tr>
				</tbody>
			</table>
			
			<h3 class="orange">Contributors</h3>
			<table class="table devtable" style="border-color: #eee">
				<tbody>
					<tr class="dev">
						<td>
							<br>
							<a href="https://flarebot.stream/" target="_blank" style="color: #4c4c4c">
								<div class="circle-icon-dev" style="background: url('./img/avatars/arsen.png') no-repeat; background-size: 124px 124px; background-position: center"></div>
							</a>
							<p style="text-align: center">
								<br>
								<b>Arsen</b>
								<br>
								Utility Methods
							</p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<script src="./js/google-analytics.js"></script>
		<script src="./js/jquery-1.11.1.min.js"></script>
		<script src="./js/bootstrap.min.js"></script>
		<script>
			$(document).ready(function(){
				var scroll_start = 0;
				var startchange = $('#top');
				var offset = startchange.offset();
				if (startchange.length){
				$(document).scroll(function() {
				   scroll_start = $(this).scrollTop();
				   if(scroll_start > offset.top) {
					   $(".navbar-inverse").css('background-color', 'rgba(240,240,240,0.85)');
					} else {
					   $('.navbar-inverse').css('background-color', 'transparent');
					}
				});
				}
			 });
		</script>
	</body>
</html>
