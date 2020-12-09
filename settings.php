<?php
	session_start();
	require_once 'vendor/checkUserInDb.php';
		if (!$_SESSION['user'] || $_SESSION['user']['varified'] == 0) {
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
	<link rel="stylesheet" href="css/modalSettings.css">
</head>
<body>
	<header class="header">
		<div class="container header__container">
			<div class="camagru_bck">
				<div class="camagru">
					CAMAGRU
				</div>
			</div>
			<div class="header_right">
				<div class="header_menu">
					<a href="settings.php">Settings</a>
					<a href="#">About</a>
					<a href="vendor/logout.php">Logout</a>
				</div>
				<div class="hello_user">
					Hello, <?= $_SESSION['user']['user_name'] ?>!
				</div>
			</div>
		</div>
	</header>
	<main class="signin_main">
		<div class="modal_menu">
			<button data-modal-target="#modal">Change user name</button>
			<div class="modal" id="modal">
				<div class="modal-header">
					<div class="title">User name change mode</div>
					<button data-close-button class="close-button">&times;</button>
				</div>
				<div class="modal-body">
					<!-- <label>New login</label> -->
					<input class="signin_input" type="text" name="new_user_name" placeholder="New login">
					<!-- <label>Password</label> -->
					<input class="signin_input" type="password" name="user_pwd" placeholder="Password">
					<button class="signin_btn" type="submit">Apply</button>
					<p class="error_msg none">Test message!!</p>
				</div>
			</div>
			<button data-modal-target="#modal2">Change email address</button>
			<div class="modal" id="modal2">
				<div class="modal-header">
					<div class="title">Email change mode</div>
					<button data-close-button class="close-button">&times;</button>
				</div>
				<div class="modal-body">
					<!-- <label>New email</label> -->
					<input class="signin_input" type="email" name="new_email" placeholder="New email">
					<!-- <label>Password</label> -->
					<input class="signin_input" type="password" name="user_pwd" placeholder="Password">
					<button class="signin_btn" type="submit">Apply</button>
					<p class="error_msg none">Test message!!</p>
				</div>
			</div>
			<button data-modal-target="#modal3">Change user password</button>
			<div class="modal" id="modal3">
				<div class="modal-header">
					<div class="title">User password change mode</div>
					<button data-close-button class="close-button">&times;</button>
				</div>
				<div class="modal-body">
					<!-- <label>Password</label> -->
					<input class="signin_input" type="password" name="new_user_pwd" placeholder="New password">
					<input class="signin_input" type="password" name="user_pwd" placeholder="Current password">
					<!-- <label>New password</label> -->
					<button class="signin_btn" type="submit">Apply</button>
					<p class="error_msg none">Test message!!</p>
				</div>
			</div>
			<div style="margin-top: 20px">
				<a href="/profile.php">Back to profile</a>
			</div>
		</div>
	</main>
	<footer class="footer">
		<div class="footer_text">
			&copy; Ndaniell's Camagru BOOOOM!
		</div>
	</footer>
	<script src="js/settings.js"></script>
</body>
</html>