<?php
	session_start();
	require_once 'createDatabase.php';

	$_POST = json_decode(file_get_contents("php://input"), true);

	if (isset($_POST['email'])) {
		$recievedEmail = $_POST['email'];
		$sql_check_email = "SELECT * FROM users WHERE email = :email";
		$check_email = $connDb->prepare($sql_check_email);
		$check_email->bindValue(':email', $recievedEmail);
		$check_email->execute();
		$email_found = $check_email->fetch(PDO::FETCH_ASSOC);
		if ($email_found) {
			$user_hash = md5($recievedEmail . time());
			$sql_set_value = "UPDATE users SET user_hash = :user_hash WHERE email = :email";
			$set_value = $connDb->prepare($sql_set_value);
			$set_value->bindValue(':email', $recievedEmail);
			$set_value->bindValue(':user_hash', $user_hash);
			$set_value->execute();

			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=utf-8\r\n";
			$headers .= "To: <$recievedEmail>\r\n";
			$headers .= "From: <fsb@fsb.ru>\r\n";

			$message = '
					<html>
					<head>
					<title>Camargu password restore</title>
					</head>
					<body>
					<p>To restore your password click <a href="localhost/confirmed.php?hash_restore=' . $user_hash . '">link</a></p>
					</body>
					</html>
					';

			mail($recievedEmail, "Camargu password restore!", $message, $headers);

			$response = [
				"status" => true,
				"message" => 'Check your email plz for following instructions'
			];
			echo json_encode($response);
			die();
		} else {
			$response = [
				"status" => false,
				"message" => 'Email not found!'
			];
			echo json_encode($response);
			die();
		}
	}

	if (isset($_POST['newPwd'])) {
		$newPwd = $_POST['newPwd'];
		$newPwdConfirmed = $_POST['newPwdConfirmed'];
		$hash_restore = $_POST['user_hash'];

		if ($newPwd != $newPwdConfirmed) {
			$response = [
				"status" => false,
				"message" => 'Passwords doesn\'t match'
			];
			echo json_encode($response);
			die();
		}
		$newPwd = md5($newPwd);
		$sql_set_value = "UPDATE users SET user_pwd = :user_pwd WHERE user_hash = :user_hash";
		$set_value = $connDb->prepare($sql_set_value);
		$set_value->bindValue(':user_pwd', $newPwd);
		$set_value->bindValue(':user_hash', $hash_restore);
		$set_value->execute();
		$response = [
			"status" => true,
			"message" => 'New password successfully set'
		];
		echo json_encode($response);
		die();
	}