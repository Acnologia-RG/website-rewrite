<html>
	<body>
		<?php
			require_once __DIR__ . '/vendor/autoload.php';
			
			session_start();
			
			$provider = new \Discord\OAuth\Discord([
				'clientId' => '289381714885869568',
				'clientSecret' => 'Im735_XxNx9P6Rg4Hw1TqAeWNRIEq7r4',
				'redirectUri' => 'http://localhost/callback',
			]);
			
			$redirect = 'http://localhost';
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
			
			echo 'Nothing to see here. Something must have gone wrong, click <a href="http://localhost">here</a> to return to the website';
		?>
	</body>
</html>