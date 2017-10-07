<html>
	<body>
		<?php
			require_once __DIR__ . '/vendor/autoload.php';
			require_once __DIR__ . '/store/settings.php';
			
			session_start();
			
			$provider = new \Discord\OAuth\Discord([
                'clientId' => $discordClientId,
                'clientSecret' => $discordClientSecret,
				'redirectUri' => "$url/callback",
			]);
			
			$redirect = $url;
			if (isset($_GET['original_request']) && $_GET['original_request']) {
				$redirect = $_GET['original_request'];
			}
			
			if (isset($_GET['logout']) && $_GET['logout']) {
				session_destroy();
				header('Location: ' . $redirect, 302);
				exit();
			}
			
			if (isset($_GET['code']) && $_GET['code']) {
				$token = $provider->getAccessToken('authorization_code', [
					'code' => $_GET['code'],
				]);
				
				$_SESSION['access_token'] = $token;
				
				header('Location: ' . $redirect, 302);
				exit();
			}
			
			echo 'Nothing to see here. Something must have gone wrong, click <a href="https://horobot.pw">here</a> to return to the website';
		?>
	</body>
</html>