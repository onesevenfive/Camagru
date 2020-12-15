<?php
	session_start();
	require_once 'createDatabase.php';

	$_POST = json_decode(file_get_contents("php://input"), true);

	$user_name = $_POST['user_name'];
	$user_pwd = $_POST['user_pwd'];

	// $name_regex = '/^[a-zA-Z0-9_]{3,15}$/';
	// $pwd_regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,15}$/';

	// if (!preg_match($name_regex, $user_name) || !preg_match($pwd_regex, $user_pwd)) {
	// 	$response = [
	// 		"status" => false,
	// 		"message" => 'Incorrect symbols',
	// 	];
	// 	echo json_encode($response);
	// 	die();
	// }

	$error_fields = [];

	if ($user_name === '') {
		$error_fields[] = 'user_name';
	}
	if ($user_pwd === '') {
		$error_fields[] = 'user_pwd';
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

	$user_pwd = md5($user_pwd);

	$data_check = array(
		'user' => "$user_name",
		'pwd' => "$user_pwd"
	);
	$sql_get_user_auth = "SELECT * FROM users WHERE user_name = :user AND user_pwd = :pwd";
	$prep_get_user_auth = $connDb->prepare($sql_get_user_auth);
	$prep_get_user_auth->execute($data_check);
	$current_user = $prep_get_user_auth->fetch(PDO::FETCH_ASSOC);
	if ($current_user) {
		if ($current_user['varified'] == 0) {
			$response = [
				"status" => false,
				"message" => 'Confirm your email plz!'
			];
			echo json_encode($response);
			die();
		}
		$_SESSION['user'] = [
			"id" => $current_user['id'],
			"user_name" => $current_user['user_name'],
			"email" => $current_user['email'],
			"varified" => $current_user['varified']
		];

		$response = [
			"status" => true
		];
		echo json_encode($response);
	}
	else {
		$response = [
			"status" => false,
			"message" => 'Incorrect username or password!'
		];
		echo json_encode($response);
	}
?>