<?php
	session_start();
	if ($_SESSION['user'] || $_SESSION['user']['varified'] == 1) {
		header('Location: index.php');
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ndaniell's Camagru</title>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<header class="header">
		<div class="container header__container">
			<div class="camagru_bck">
				<div class="camagru">
					CAMAGRU
				</div>
			</div>
		</div>
	</header>
	<main class="signin_main">
		<?php
			require_once 'vendor/createDatabase.php';
			if ($_GET['hash']) {
				$hash = $_GET['hash'];
				$sql_check_user_hash = "SELECT * FROM users WHERE user_hash = :user_hash";
				$check_user_hash = $connDb->prepare($sql_check_user_hash);
				$check_user_hash->bindValue(':user_hash', $hash);
				$check_user_hash->execute();
				$hash_found = $check_user_hash->fetch(PDO::FETCH_ASSOC);
				if ($hash_found) {
					if ($hash_found['varified'] == 0) {
						?>
							<div>
								<p class="email_confirmed"> Email successfully confirmed! </p>
								<p><a href="/index.php">SignIn</a></p>
							</div>
							
						<?php
						$sql_set_varified = "UPDATE users SET varified='1' WHERE user_hash = :user_hash";
						$set_varified = $connDb->prepare($sql_set_varified);
						$set_varified->bindValue(':user_hash', $hash);
						$set_varified->execute();
					} else if ($hash_found['varified'] == 1) {
						?>
							<div>
								<p class="email_confirmed"> Email was already confirmed! </p>
								<p><a href="/index.php">SignIn</a></p>
							</div>
						<?php
					}
				} 
				else {
					?>
					<div>
						<p class="email_confirmed"> Something goes wrong! Oops! </p>
					</div>
					<?php
				}
			}
			?>
	</main>
	<footer class="footer">
		<div class="footer_text">
			&copy; Ndaniell's Camagru BOOOOM!
		</div>
	</footer>
</body>
</html>