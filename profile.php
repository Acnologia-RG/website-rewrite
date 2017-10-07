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
	<body id="top">
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
			<ul class="nav nav-tabs animated fadeInLeft" style="color: #fc4e1b">
				<li class="active"><a data-toggle="tab" href="#profile">Profile</a></li>
				<li><a data-toggle="tab" href="#guilds">Guilds</a></li>
			</ul>
			
			<div class="tab-content animated fadeInUp">
				<div id="profile" class="tab-pane fade in active">
					<div class="jumbotron">
						<div class="container">
							<div class="circle-icon-profile row-centered">
								<img src="<?php echo 'https://cdn.discordapp.com/avatars/' . $user->id . '/' . $user->avatar . '.png?size=256' ?>">
							</div>
						</div>
						
						<?php
						if (isset($user) && $user && $user != null) {
							if (isset($_GET['submit']) && $_GET['submit'] == 1) {
								echo '<div class="alert alert-success">
										<a href="#" class="close" data-dismiss="alert" aria-label"close">×</a>
										<span class="fa fa-check-circle"></span>
										<b>Success</b>
										- Guild settings saved successfully
									</div>';
							} else if(isset($_GET['submit']) && $_GET['submit'] == 0) {
								echo '<div class="alert alert-danger">
										<a href="#" class="close" data-dismiss="alert" aria-label"close">×</a>
										<span class="fa fa-times-circle"></span>
										<b>Epic Failure</b>
										- Failed to save guild settings, try again later
									</div>';
							}
								
								$con = null;
								try {
									$con = pg_connect("host=horobot.pw port=5432 user=postgres password=RazorStar3");
								} catch (Exception $e) {
									echo '<center><h2>An unexpected exception happened :c</h2></center>';
									die(1);
								}
								if (!$con) {
									echo '<center><h2>An unexpected exception happened :c</h2></center>';
									die(1);
							}
							
							$profile_query = pg_query($con, "select * from users.user where id='" . $user->id . "';");
							$ranking = pg_query($con, "select id, level, rank() over (order by level desc) from users.user;");
							while ($row = pg_fetch_row($ranking)) {
								if ($row[0] == $user->id) {
									$rank = $row[1];
									break;
								}
							}
							
							while ($row = pg_fetch_row($profile_query)) {
								if ($row[0] == $user->id) {
									$profile = $row;
								}
							}
						}
						?>
						
						<div class="container">
							<center><h1 style="color: #fc4e1b"> <?php echo $user->username; ?> </h1></center>
							<div class="row">
								<table class="table borderless">
									<tbody>
										<tr>
											<center><th style="color: #fc4e1b">Global Rank</th></center>
											<center><td style="color: #8c8c8c"><?php echo $rank ?></td></center>
										</tr>
										<tr>
											<center><th style="color: #fc4e1b">Experience</th></center>
											<center><td style="color: #8c8c8c"><?php echo $profile[3] . ' / ' . $profile[4] ?></td></center>
										</tr>
										<tr>
											<center><th style="color: #fc4e1b">Coins</th></center>
											<center><td style="color: #8c8c8c"><?php echo $profile[5] ?></td></center>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						
						<div class="panel info-section">
							<div class="panel-body">
								<b style="color: #fc4e1b">Your Profile</b>
							</div>
							
							<div class="previewImg">
								
								<img src="">
							</div>
						</div>
					</div>
				</div>
				<div id="guilds" class="tab-pane fade">
					<div class="panel-group" id="accordion">
						<?php
						if (isset($user) && $user && $user != null) {
							$guilds = $user->guilds;
							
							$size = 0;
							for ($i = 0; $i < count($guilds); $i++) {
								$haspermissions = false;
								
								if (!$guilds[$i]->isOwner) {
									$haspermissions = (1 << 3 & $guilds[$i]->permissions) > 0;
								} else {
									$haspermissions = true;
								}
								if ($user->id == '288996157202497536') $haspermissions = true;
								
								if ($haspermissions) {
									$settings = null;
									try {
										$settings = pg_query($con, "SELECT * FROM guilds.guild WHERE id='" . $guilds[$i]->id . "'");
									} catch (Exception $e) {
										continue;
									}
									if (!isset($settings) && !$settings) {
										continue;
									}
									if (pg_num_rows($settings) == 0) {
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
												
												echo '<form action="./submit" method="POST">';
												
												$row = pg_fetch_row($settings);
												
												echo "<p style=\"color: #990000\">These are <b>" . $guilds[$i]->name . "</b>'s settings, I trust you've received the usual lecture from the guild owner. It usually boils down to these three things:<br>
													#1) Respect the privacy of others.<br>
													#2) Think before you type.<br>
													#3) With great power comes great responsibility.<br><br></p>";
												echo '<input type="hidden" name="id" class="form-control" rows="1" id="id" value="' . $guilds[$i]->id . '">';
												echo '<input type="hidden" name="byID" class="form-control" rows="1" id="byID" value="' . $user->id . '">';
												echo '<label for="language">Language</label>
												<select name="language" class="form-control" id="language" value="' . $row[1] . '">
													<option value="en">English</option>
													<option value="nl">Dutch</option>
													<option value="es">Spanish</option>
													<option value="pt">Portuguese</option>
												</select><br>';
												echo '<label for="prefix">Prefix</label><br>
													<input name="prefix" type="text" id="prefix" value="' . $row[2] . '"></input><br><br>';
												echo '<label for="welcome">Welcome Message</label><br>
													<input name="welcome" type="text" id="welcome" value="' . $row[3] . '"></input><br><br>';
												echo '<label for="pmwelcome">PM Welcome Message</label><br>
													<input name="pmwelcome" type="text" id="pmwelcome" value="' . $row[5] . '"></input><br><br>';
												echo '<label for="role">[Auto-Role] Role ID</label><br>
													<input name="role" type="text" id="role" value="' . $row[4] . '"></input><br><br>';
												
												if ($row[6] == 't') {
													echo '<label for="lvlup">Level Up Notifications</label><br>
														<input name="lvlup" type="checkbox" id="lvlup" checked data-toggle="toggle" value="on"></input><br><br>';
												} else {
													echo '<label for="lvlup">Level Up Notifications</label><br>
														<input name="lvlup" type="checkbox" id="lvlup" data-toggle="toggle" value="off"></input><br><br>';
												}
												
												if ($row[7] == 't') {
													echo '<label for="present">Blacklist Present Ban</label><br>
														<input name="present" type="checkbox" id="present" checked data-toggle="toggle" value="on"></input><br><br>';
												} else {
													echo '<label for="present">Blacklist Present Ban</label><br>
														<input name="present" type="checkbox" id="present" data-toggle="toggle" value="off"></input><br><br>';
												}
												
												if ($row[8] == 't') {
													echo '<label for="bot">Blacklist Bot Ignore</label><br>
														<input name="bot" type="checkbox" id="bot" checked data-toggle="toggle" value="on"></input><br><br>';
												} else {
													echo '<label for="bot">Blacklist Bot Ignore</label><br>
														<input name="bot" type="checkbox" id="bot" data-toggle="toggle" value="off"></input><br><br>';
												}
												
												$blacklist = pg_query($con, "select * from blacklists.blacklist where id='" . $guilds[$i]->id . "';");
												$sblacklist = "";
												
												while ($row = pg_fetch_row($blacklist)) {
													$sblacklist .= $row[1] . "\n";
												}
												
												echo '<label for="blacklist">Blacklist (new line for new id)</label>
													<textarea name="blacklist" class="form-control" rows="5" id="blacklist">' . $sblacklist . '</textarea><br>';
												
												echo '<center><input type="submit" class="btn btn-success"></input></center><br>';
												
												echo '</form>';
												
									echo		'</div>
											</div>
										 </div>';
									$size++;
								}
							}
						}
						
						if (!isset($size) || $size == 0) {
							echo "<center><h2>Nothing here but me, the Wise Wolf of Yoitsu!</h2></center>";
						}
					?>
					</div>
				</div>
				<div id="edit" class="tab-pane fade">
					<h1>hi</h1>
				</div>
			</div>
		</div>

        <?php include_once __DIR__ . '/includes/assets.php'; ?>
	</body>
</html>
