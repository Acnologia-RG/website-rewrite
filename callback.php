<html>
	<body>
		<?php
		require_once __DIR__ . '/vendor/autoload.php';
		require_once __DIR__ . '/settings/general.php';
		
		session_start();
		
		if (isset($_GET['redirect']) && _GET['redirect'])
			$redirect = $_GET['redirect'];
		
		if (isset($_GET['logout']) && $_GET['logout']) {
			session_destroy();
			header('Location: ' . $redirect, 302);
			exit();
		}
		
		if (isset($_GET['code']) && $_GET['code'] && !isset($_SESSION['access_token'])) {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/oauth2/token");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id=" . $discordClientId . "&client_secret=" . $discordClientSecret . "&grant_type=authorization_code&code=" . $_GET['code'] . "&redirect_uri=" . site_url() . "/checkout");
			curl_setopt($ch, CURLOPT_POST, 1);

			$headers = array();
			$headers[] = "Content-Type: application/x-www-form-urlencoded";
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
			}
			curl_close ($ch);
			$result = json_decode($result);
			$_SESSION['access_token'] = $result;
			
			if (isset($redirect))
				header('Location: ' . $redirect, 302);
		}
		
		if (isset($_SESSION['access_token']) && !isset($_SESSION['user'])) {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, "https://discordapp.com/api/users/@me");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");


			$headers = array();
			$headers[] = "Authorization: " . $result->token_type . " " . $result->access_token;
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			if (curl_errno($ch)) {
				echo 'Error:' . curl_error($ch);
			}
			curl_close ($ch);
			$result = json_decode($result);
			$_SESSION['user'] = $result;
		}
		
		var_dump($_SESSION['access_token']);
		var_dump($_SESSION['user']);
		?>
	</body>
</html>