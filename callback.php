<html>
	<body>
		<?php
			require_once __DIR__ . '/vendor/autoload.php';
			
			session_start();
			
			$provider = new \Discord\OAuth\Discord([
				'clientId' => '289381714885869568',
				'clientSecret' => 'bXQ-fZs2ud9i_6cVqUhnSgAFA6G0ePIe',
				'redirectUri' => 'https://horobot.pw/callback',
			]);
			
			$redirect = 'https://horobot.pw';
			if (isset($_GET['redirect']) && $_GET['redirect']) {
				$redirect = $_GET['redirect'];
			}
			
			if (isset($_GET['logout']) && $_GET['logout']) {
				session_destroy();
				header('Location: ' . $redirect);
			}
			
			if (isset($_GET['code']) && $_GET['code']) {
				$token = $provider->getAccessToken('authorization_code', [
					'code' => $_GET['code'],
				]);
				
				$_SESSION['access_token'] = $token;
				
				header('Location: ' . $redirect);
			}
			
			echo 'Nothing to see here. Something must have gone wrong, click <a href="https://horobot.pw">here</a> to return to the website';
		?>
	</body>
</html>