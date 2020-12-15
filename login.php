<?php
	session_start();
	require_once 'vendor/createDatabase.php';
	if ($_SESSION['user'] && $_SESSION['user']['varified'] == 1) {
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
		<form class="form_login">
			<!-- <label>Login</label> -->
			<input class="signin_input" type="text" name="user_name" placeholder="Login">
			<!-- <label>Password</label> -->
			<input class="signin_input" type="password" name="user_pwd" placeholder="Password">
			<a href="/confirmed.php?restore=omg" class ="forgot">Forgot password?</a>
			<button class="signin_btn" type="submit">Sign In</button>
			<p>Don't have account? - <a href="/register.php">SignUp</a></p>
			<p class="error_msg none">Test message!!</p>
		</form>
	</main>
	<footer class="footer">
		<div class="footer_text">
			&copy; Ndaniell's Camagru BOOOOM!
		</div>
	</footer>
	<script src="js/auth.js"></script>
</body>
</html>