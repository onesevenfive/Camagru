<?php
	session_start();
	require_once 'createDatabase.php';

	if ($_SESSION['user']) {
		$sql_user_exists = "SELECT * FROM users WHERE user_name = :user";
		$check_user_exists = $connDb->prepare($sql_user_exists);
		$check_user_exists->bindValue(':user', $_SESSION['user']['user_name']);
		$check_user_exists->execute();
		$user_exists = $check_user_exists->fetch(PDO::FETCH_ASSOC);
		if (!$user_exists)
		{
			unset($_SESSION['user']);
			header('Location: ../login.php');
		}
	}