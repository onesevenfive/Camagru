<?php
	session_start();
	if ($_SESSION['user']) {
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
			<p class="error_user_name none" id="valid_fields">User name must contain from 3 to 15 chars, uppercase, lowercase, numeral and underscore</p>
			<!-- <label>Email</label> -->
			<input class="signin_input" type="email" name="email" placeholder="Email">
			<!-- <label>Password</label> -->
			<input class="signin_input" type="password" name="user_pwd" placeholder="Password">
			<p class="error_user_pwd none" id="valid_fields">Password must contain from 6 to 15 chars, including at least 1 uppercase letter and 1 numeral</p>
			<!-- <label>Password confirm</label> -->
			<input class="signin_input" type="password" name="user_pwd_confirm" placeholder="Password confirm">
			<div class="notification_box">
				<input class="signin_input" id="notif_checkbox" type="checkbox" checked><p class="notifications">Receive comment notifications on email</p>
			</div>
			<button class="register_btn" type="submit">Register</button>
			<p>Already got account? - <a href="/login.php">SignIn</a></p>
			<p class="error_msg none">Test message!!</p>
		</form>
	</main>
	<footer class="footer">
		<div class="footer_text">
			&copy; Ndaniell's Camagru BOOOOM!
		</div>
	</footer>
	<script src="js/register.js"></script>
	<script src='js/inputValid.js'></script>
</body>
</html>