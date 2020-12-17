<?php
	session_start();
	if ($_SESSION['user'] || $_SESSION['user']['varified'] == 1 || (!$_GET['hash'] && !$_GET['restore'] && !$_GET['hash_restore'])) {
		header('Location: login.php');
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
			require_once 'config/setup.php';
			if ($_GET['restore']) {
				?>
					<main class="signin_main">
						<form class="form_login">
							<input class="signin_input" type="email" placeholder="Enter your email">
							<button class="signin_btn" type="submit" id="restore">Restore</button>
							<p><a href="/login.php">Back</a></p>
							<p class="error_msg none">Test message!!</p>
						</form>
					</main>
				<?php
			}
			if ($_GET['hash_restore']) {
				$hash_restore = $_GET['hash_restore'];
				$sql_check_user_hash = "SELECT * FROM users WHERE user_hash = :user_hash";
				$check_user_hash = $connDb->prepare($sql_check_user_hash);
				$check_user_hash->bindValue(':user_hash', $hash_restore);
				$check_user_hash->execute();
				$hash_restore_found = $check_user_hash->fetch(PDO::FETCH_ASSOC);
				if ($hash_restore_found) {
					?>
						<main class="signin_main">
							<form class="form_login">
								<input class="signin_input" type="password" name="restore_user_pwd" placeholder="New password">
								<p class="error_user_pwd none" id="valid_fields">Password must contain from 6 to 15 chars, including at least 1 uppercase letter and 1 numeral</p>
								<input class="signin_input" type="password" name="confirmed_restore_user_pwd" placeholder="Confirm password">
								<button class="signin_btn" type="submit" id="set">Set</button>
								<p><a href="/login.php">Back</a></p>
								<p class="error_msg none">Test message!!</p>
							</form>
						</main>
					<?php
				} else {
					?>
					<div>
						<p class="email_confirmed"> Something goes wrong! Oops! </p>
						<p><a href="/login.php">SignIn</a></p>
					</div>
					<?php
				}
			}
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
								<p><a href="/login.php">SignIn</a></p>
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
								<p><a href="/login.php">SignIn</a></p>
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
	<script src="js/restore.js"></script>
	<footer class="footer">
		<div class="footer_text">
			&copy; Ndaniell's Camagru BOOOOM!
		</div>
	</footer>
</body>
</html>