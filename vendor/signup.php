<?php
	session_start();
	require_once __DIR__ . '/../config/setup.php';

	$_POST = json_decode(file_get_contents("php://input"), true);

	$user_name = $_POST['user_name'];
	$email = $_POST['email'];
	$user_pwd = $_POST['user_pwd'];
	$user_pwd_confirm = $_POST['user_pwd_confirm'];
	$notif = $_POST['notif'];

	$sql_get_user_name = "SELECT * FROM users WHERE user_name = :user";
	$check_user_name = $connDb->prepare($sql_get_user_name);
	$check_user_name->bindValue(':user', $user_name);
	$check_user_name->execute();
	$name_match_found = $check_user_name->fetch(PDO::FETCH_ASSOC);
	if ($name_match_found) {
		$response = [
			"status" => false,
			"message" => 'This login already exists',
			"type" => 1,
			"fields" => ['user_name']
		];
		echo json_encode($response);
		die();
	}

	$sql_get_email = "SELECT * FROM users WHERE email = :email";
	$check_user_email = $connDb->prepare($sql_get_email);
	$check_user_email->bindValue(':email', $email);
	$check_user_email->execute();
	$email_match_found = $check_user_email->fetch(PDO::FETCH_ASSOC);
	if ($email_match_found) {
		$response = [
			"status" => false,
			"message" => 'This email already registered',
			"type" => 1,
			"fields" => ['email']
		];
		echo json_encode($response);
		die();
	}

	$error_fields = [];
	if ($user_name === '') {
		$error_fields[] = 'user_name';
	}
	if ($user_pwd === '') {
		$error_fields[] = 'user_pwd';
	}
	if ($user_pwd_confirm === '') {
		$error_fields[] = 'user_pwd_confirm';
	}
	if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error_fields[] = 'email';
	}
	if (!empty($error_fields)) {
		$response = [
			"status" => false,
			"message" => 'Empty fields are not allowed',
			"type" => 1,
			"fields" => $error_fields
		];
		echo json_encode($response);
		die();
	}

	if ($user_pwd === $user_pwd_confirm) {
		$user_pwd = md5($user_pwd);
		$user_hash = md5($login . time());
		$data_upload = array(
			'user' => "$user_name",
			'email' => "$email",
			'pwd' => "$user_pwd",
			'user_hash' => "$user_hash",
			'notif' => "$notif"
		);
		$sql_insert_user = "INSERT INTO `users`(`id`, `user_name`, `email`, `user_pwd`, `user_hash`, `varified`, `notifications`) VALUES(NULL, :user, :email, :pwd, :user_hash, 0, :notif)";
		$prep_insert_user = $connDb->prepare($sql_insert_user);
		$prep_insert_user->execute($data_upload);

		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "To: <$email>\r\n";
		$headers .= "From: <fsb@fsb.ru>\r\n";

		$message = '
				<html>
				<head>
				<title>Camagru project email confirm</title>
				</head>
				<body>
				<p>Welcome to Camargu!</p>
				<p>To confirm your email click <a href="localhost/confirmed.php?hash=' . $user_hash . '">link</a></p>
				</body>
				</html>
				';

		mail($email, "Confirm your email", $message, $headers);

		$response = [
			"status" => true,
			"message" => 'Registration successfull'
		];
		echo json_encode($response);
	} else {
		$response = [
			"status" => false,
			"message" => 'Passwords doesn\'t match'
		];
		echo json_encode($response);
	}
