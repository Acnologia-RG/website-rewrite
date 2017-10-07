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

        <?php include_once __DIR__ . '/includes/assets.php'; ?>
	</body>
</html>
