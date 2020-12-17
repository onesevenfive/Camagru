<?php
	session_start();
	require_once __DIR__ . '/../config/setup.php';
	require_once 'dbFunctions.php';

	$_POST = json_decode(file_get_contents("php://input"), true);

	$user_pwd = $_POST['user_pwd'];
	$user_name = $_SESSION['user']['user_name'];

	if (isset($_POST['new_user_name'])) {
		$new_user_name = $_POST['new_user_name'];
		if (checkPwd($connDb, $user_pwd, $user_name)) {
			$sql_get_user_name = "SELECT * FROM users WHERE user_name = :user";
			$check_user_name = $connDb->prepare($sql_get_user_name);
			$check_user_name->bindValue(':user', $new_user_name);
			$check_user_name->execute();
			$name_match_found = $check_user_name->fetch(PDO::FETCH_ASSOC);
			if ($name_match_found) {
				$response = [
					"status" => false,
					"message" => 'This login already exists',
					"type" => 1,
					"fields" => ['new_user_name']
				];
				echo json_encode($response);
				die();
			}

			$sql_set_value = "UPDATE users SET user_name = :new_user_name WHERE user_name = :user_name";
			$set_value = $connDb->prepare($sql_set_value);
			$set_value->bindValue(':new_user_name', $new_user_name);
			$set_value->bindValue(':user_name', $user_name);
			$set_value->execute();

			$sql_set_value2 = "UPDATE comments SET user_name = :new_user_name WHERE user_name = :user_name";
			$set_value2 = $connDb->prepare($sql_set_value2);
			$set_value2->bindValue(':new_user_name', $new_user_name);
			$set_value2->bindValue(':user_name', $user_name);
			$set_value2->execute();

			$_SESSION['user']['user_name'] = $new_user_name;

			$response = [
				"status" => true,
				"message" => 'Login changed successfully'
			];
			echo json_encode($response);
		} else {
			$response = [
				"status" => false,
				"message" => 'Incorrect password!'
			];
			echo json_encode($response);
		}
	}
	if (isset($_POST['new_email'])) {
		$new_email = $_POST['new_email'];
		if ($new_email != '') {
			if (checkPwd($connDb, $user_pwd, $user_name)) {
				$sql_set_value = "UPDATE users SET email = :new_email WHERE user_name = :user_name";
				$set_value = $connDb->prepare($sql_set_value);
				$set_value->bindValue(':new_email', $new_email);
				$set_value->bindValue(':user_name', $user_name);
				$set_value->execute();
				$_SESSION['user']['email'] = $new_email;
				setNotifications($connDb);

				$response = [
					"status" => true,
					"message" => 'Email changed successfully'
				];
				echo json_encode($response);
			} else {
				$response = [
					"status" => false,
					"message" => 'Incorrect password!'
				];
				echo json_encode($response);
			}
		} else {
			setNotifications($connDb);
		}
	}
	if (isset($_POST['notif'])) {
		$currentUserData = getUserNameWithUserId($_SESSION['user']['id'], $connDb);
		if ($_POST['notif'] == true && $currentUserData['notifications'] == 0) {
			$sql_set_value = "UPDATE users SET notifications = 1 WHERE user_name = :user_name";
			$set_value = $connDb->prepare($sql_set_value);
			$set_value->bindValue(':user_name', $user_name);
			$set_value->execute();
			$response = [
				"status" => true,
				"notif" => 'checked'
			];
			echo json_encode($response);
		} else if ($_POST['notif'] == false && $currentUserData['notifications'] == 1) {
			$sql_set_value = "UPDATE users SET notifications = 0 WHERE user_name = :user_name";
			$set_value = $connDb->prepare($sql_set_value);
			$set_value->bindValue(':user_name', $user_name);
			$set_value->execute();
			$response = [
				"status" => true,
				"notif" => ''
			];
			echo json_encode($response);
		}
	}
	if (isset($_POST['new_user_pwd'])) {
		$new_user_pwd = md5($_POST['new_user_pwd']);
		if (checkPwd($connDb, $user_pwd, $user_name)) {
			$sql_set_value = "UPDATE users SET user_pwd = :new_user_pwd WHERE user_name = :user_name";
			$set_value = $connDb->prepare($sql_set_value);
			$set_value->bindValue(':new_user_pwd', $new_user_pwd);
			$set_value->bindValue(':user_name', $user_name);
			$set_value->execute();

			$response = [
				"status" => true,
				"message" => 'Password changed successfully'
			];
			echo json_encode($response);
		} else {
			$response = [
				"status" => false,
				"message" => 'Incorrect password!'
			];
			echo json_encode($response);
		}
	}

	function checkPwd($connDb, $user_pwd, $user_name) {
		$user_pwd = md5($_POST['user_pwd']);
		$check_array = array(
			'user' => "$user_name",
			'pwd' => "$user_pwd"
		);
		if (!$connDb) {
			echo "Crash";
			die();
		}
		$sql_user_pwd = "SELECT * FROM users WHERE user_name = :user AND user_pwd = :pwd";
		$prep_user_pwd = $connDb->prepare($sql_user_pwd);
		$prep_user_pwd->execute($check_array);
		$pwd_valid = $prep_user_pwd->fetch(PDO::FETCH_ASSOC);
		if ($pwd_valid) {
			$ret = true;
		} else {
			$ret = false;
		}
		return $ret;
	}

	function setNotifications($connDb) {
		$currentUserData = getUserNameWithUserId($_SESSION['user']['id'], $connDb);
		if ($_POST['notif'] == true && $currentUserData['notifications'] == 0) {
			$sql_set_value = "UPDATE users SET notifications = 1 WHERE user_name = :user_name";
			$set_value = $connDb->prepare($sql_set_value);
			$set_value->bindValue(':user_name', $user_name);
			$set_value->execute();
		} else if ($_POST['notif'] == false && $currentUserData['notifications'] == 1) {
			$sql_set_value = "UPDATE users SET notifications = 0 WHERE user_name = :user_name";
			$set_value = $connDb->prepare($sql_set_value);
			$set_value->bindValue(':user_name', $user_name);
			$set_value->execute();
		}
	}