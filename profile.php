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
		<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
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
								'clientSecret' => 'zmcqowQRG2NfpHz2rnyiIdy_0k2hcFV1',
								'redirectUri' => 'http://localhost/callback',
							]);
							
							if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
								$token = $_SESSION['access_token'];
								$user = $provider->getResourceOwner($token);
								
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
		
		<div class="container">
			<center><h1 class="orange animated fadeInRight"><b>Your Guilds</b></h1></center>
			<div class="panel-group animated fadeInLeft" id="accordion">
				<?php
					$con = null;
					try {
						$con = pg_connect("host=localhost port=5432 user=postgres password=RazorStar3");
					} catch (Exception $e) { }
					if (!$con) {
						echo '<center><h2>An unexpected exception happened :c</h2></center>';
						return;
					}
					
					$guilds = $user->guilds;
					
					$size = 0;
					for ($i = 0; $i < count($guilds); $i++) {
						$haspermissions = false;
						
						if (!$guilds[$i]->isOwner) {
							$haspermissions = (1 << 3 & $guilds[$i]->permissions) > 0;
						} else {
							$haspermissions = true;
						}
						
						if ($haspermissions) {
							$settings = null;
							try {
								$settings = pg_query($con, "SELECT * FROM guilds.guild WHERE id='" . $guilds[$i]->id . "'");
							} catch (Exception $e) { continue; }
							if (!$settings) {
								continue;
							}
							
							$icon = null;
							if ($guilds[$i]->icon == null) {
								$icon = 'https://discordapp.com/assets/dd4dbc0016779df1378e7812eabaa04d.png';
							} else {
								$icon = 'https://cdn.discordapp.com/icons/' . $guilds[$i]->id . '/' . $guilds[$i]->icon . '.png?size=128';
							}
							
							echo '<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#accordion" href="#collapse' . $i . '">
												<div id="container" style="height: 100%; width: 100%; display: inline-block">
													<img src="' . $icon . '" class="circle-icon"> <h2 style="margin-left: 70px; margin-top: 15px">' . $guilds[$i]->name . '</h2>
												</div>
											</a>
										</h4>
									</div>
									<div id="collapse' . $i . '" class="panel-collapse collapse">
										<div class="panel-body">';
										
										echo '<form action="./submit#redirect=http://localhost/profile" method="POST">';
										
										$row = pg_fetch_row($settings);
										
										echo "These are <b>" . $guilds[$i]->name . "</b>'s settings, I trust you've received the usual lecture from the guild owner. It usually boils down to these three things:<br>
											#1) Respect the privacy of others.<br>
											#2) Think before you type.<br>
											#3) With great power comes great responsibility.<br><br>";
										echo '<label for="id">Guild ID</label>
											<textarea class="form-control" rows="1" id="id" disabled>' . $row[0] . '</textarea><br><br>';
										echo '<label for="language">Language</label>
										<select class="form-control" id="language" value="' . $row[1] . '">
											<option value="en">EN</option>
											<option value="nl">NL</option>
											<option value="es">ES</option>
											<option value="pt">PT</option>
										</select><br>';
										echo '<label for="prefix">Prefix</label><br>
											<input type="text" id="prefix" value="' . $row[2] . '"></input><br><br>';
										echo '<label for="welcome">Welcome Message</label><br>
											<input type="text" id="welcome" value="' . $row[3] . '"></input><br><br>';
										echo '<label for="pmwelcome">PM Welcome Message</label><br>
											<input type="text" id="pmwelcome" value="' . $row[5] . '"></input><br><br>';
										echo '<label for="role">[Auto-Role] Role ID</label><br>
											<input type="text" id="role" value="' . $row[4] . '"></input><br><br>';
										
										if ($row[6] == 't') {
											echo '<label for="lvlup">Level Up Notifications</label><br>
												<input type="checkbox" id="lvlup" checked data-toggle="toggle"></input><br><br>';
										} else {
											echo '<label for="lvlup">Level Up Notifications</label><br>
												<input type="checkbox" id="lvlup" data-toggle="toggle"></input><br><br>';
										}
										
										if ($row[7] == 't') {
											echo '<label for="present">Blacklist Present Ban</label><br>
												<input type="checkbox" id="present" checked data-toggle="toggle"></input><br><br>';
										} else {
											echo '<label for="present">Blacklist Present Ban</label><br>
												<input type="checkbox" id="present" data-toggle="toggle"></input><br><br>';
										}
										
										if ($row[8] == 't') {
											echo '<label for="ignore">Blacklist Bot Ignore</label><br>
												<input type="checkbox" id="ignore" checked data-toggle="toggle"></input><br><br>';
										} else {
											echo '<label for="ignore">Blacklist Bot Ignore</label><br>
												<input type="checkbox" id="ignore" data-toggle="toggle"></input><br><br>';
										}
										
										$blacklist = pg_query($con, "select * from blacklists.blacklist where id='" . $guilds[$i]->id . "';");
										$sblacklist = "";
										
										while ($row = pg_fetch_row($blacklist)) {
											$sblacklist .= $row[1] . "\n";
										}
										
										echo '<label for="blacklist" value="">Blacklist (new line for new id)</label>
											<textarea class="form-control" rows="5" id="blacklist">' . $sblacklist . '</textarea><br>';
										
										echo '<center><input type="submit" class="btn btn-success"></input></center><br>';
											
										echo '</form>';
										
							echo		'</div>
									</div>
								 </div>';
							$size++;
						}
					}
					
					if ($size == 0) {
						echo "<center><h2>Nothing here but me, the Wise Wolf of Yoitsu!</h2></center>";
					}
				?>
			</div>
		</div>
		
		<script src="./js/google-analytics.js"></script>
		<script src="./js/jquery-1.11.1.min.js"></script>
		<script src="./js/bootstrap.min.js"></script>
		<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
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