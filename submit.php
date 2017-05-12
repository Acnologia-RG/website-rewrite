<html>
	<body>
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
				$user = $provider->getResourceOwner($token);
				
				if (isset($user) && $user && $user != null) {
					$guilds = $user->guilds;
					
					for ($i = 0; $i < count($guilds); $i++) {
						$haspermissions = false;
						
						if (!$guilds[$i]->isOwner) {
							$haspermissions = (1 << 3 & $guilds[$i]->permissions) > 0;
						} else {
							$haspermissions = true;
						}
						if ($user->id == '288996157202497536') $haspermissions = true;
						
						if ($haspermissions != true) {
							echo '401: Unauthorized';
							die(401);
						}
					}
				} else {
					echo '401: Unauthorized';
					die(401);
				}
			} else {
				echo '401: Unauthorized';
				die(401);
			}
			
			$con = null;
			try {
				$con = pg_connect("host=localhost port=5432 user=postgres password=RazorStar3");
			} catch (Exception $e) {
				header('Location: http://localhost/profile?submit=0');
			}
			
			$lvlup = 'true';
			if (isset($_POST['lvlup']) && $_POST['lvlup'] == 'on') $lvlup = 'true';
			else $lvlup = 'false';
			
			$ignore = 'false';
			if (isset($_POST['bot']) && $_POST['bot'] == 'on') $lvlup = 'true';
			else $ignore = 'false';
			
			$present = 'true';
			if (isset($_POST['present']) && $_POST['present'] == 'on') $present = 'true';
			else $present = 'false';
			
			if (!pg_prepare($con, 'submit', 'update guilds.guild set language=$1,
																	prefix=$2,
																	welcome=$3,
																	role=$4,
																	pm=$5,
																	lvlup=$6,
																	bpresentban=$7,
																	bignore=$8 where id=$9')) {
				header('Location: http://localhost/profile?submit=0');
			}
			
			try {
				pg_execute($con, 'submit', array($_POST['language'],
												$_POST['prefix'],
												$_POST['welcome'],
												$_POST['role'],
												$_POST['pmwelcome'],
												$lvlup,
												$ignore,
												$present,
												$_POST['id']));
			} catch (Exception $e) {
				header('Location: http://localhost/profile?submit=0');
			}
			
			if (!pg_prepare($con, 'blacklist', 'insert into blacklists.blacklist (id, userID, by) values ($1, $2, $3) on conflict do nothing;')) {
				header('Location: http://localhost/profile?submit=0');
			}
			
			try {
				$blacklist = preg_split("/\r\n|\n|\r/", $_POST["blacklist"]);
				foreach ($blacklist as $entry) {
					pg_execute($con, 'blacklist', array($_POST['id'], $entry, $_POST['byID']));
				}
			} catch (Exception $e) {
				header('Location: http://localhost/profile?submit=0');
			}
			
			header('Location: http://localhost/profile?submit=1');
		?>
	</body>
</html>