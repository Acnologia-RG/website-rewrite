<html>
	<body>
		<?php
			require_once __DIR__ . '/vendor/autoload.php';
			
			session_start();
			
			$provider = new \Discord\OAuth\Discord([
				'clientId' => '289381714885869568',
				'clientSecret' => 'BkvYsgRY1O_npJ_7v-unvgw8e9YhzUh6',
				'redirectUri' => 'http://localhost/callback.php',
			]);
			
			if (isset($_GET['code'])) {
				$token = $provider->getAccessToken('authorization_code', [
					'code' => $_GET['code'],
				]);
				
				$_SESSION['access_token'] = $token;
				header('Location: http://localhost/');
			}
		?>
	</body>
</html>