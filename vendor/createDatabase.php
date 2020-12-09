<?php

$userName = "root";
$userPassword = "root";
$charset = "utf8";

$db = "camagru"; 

// Connect to DBMS
try {
	$conn = new PDO("mysql:host=$host", $userName, $userPassword);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// Get all existing databases names
	$databases = $conn->query('show databases')->fetchAll(PDO::FETCH_COLUMN);

	// If database not exists create database
	if(!in_array($db, $databases))
	{
		try {
			$dbh = new PDO("mysql:host=$host; charset=$charset", $userName, $userPassword);
		
			$dbh->exec("CREATE DATABASE `$db`;
					CREATE USER '$userName'@'localhost' IDENTIFIED BY '$userPassword';
					GRANT ALL ON `$db`.* TO '$userName'@'localhost';
					FLUSH PRIVILEGES;");
			// createTables();
			// echo("Data base created");
		
		} catch (PDOException $e) {
			die("DB ERROR: ". $e->getMessage());
		}
	}
	$databases = $conn->query('show databases')->fetchAll(PDO::FETCH_COLUMN);
	// Else create tables
	if (in_array($db, $databases)) {
		try {
			$connDb = new PDO("mysql:host=localhost;dbname=$db", $userName, $userPassword);
			$connDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$tableUsers = "CREATE TABLE IF NOT EXISTS users (
				id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				user_name VARCHAR(15) NOT NULL,
				email VARCHAR(30) NOT NULL,
				user_pwd VARCHAR(32) NOT NULL,
				user_hash VARCHAR (32) NOT NULL,
				varified INT(1) NOT NULL
				)";
			$connDb->exec($tableUsers);
			$tableImages = "CREATE TABLE IF NOT EXISTS images (
				id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				user_id INT(11) NOT NULL,
				image_name VARCHAR(60) NOT NULL
				)";
			$connDb->exec($tableImages);
			$tableComments = "CREATE TABLE IF NOT EXISTS comments (
				id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				user_name VARCHAR(15) NOT NULL,
				image_id INT(11) NOT NULL,
				comment_text VARCHAR(200) NOT NULL,
				comment_time DATETIME NOT NULL
				)";
			$connDb->exec($tableComments);
			$tableLikes = "CREATE TABLE IF NOT EXISTS likes (
				id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				image_id INT(11) NOT NULL,
				user_id INT(11) NOT NULL
				)";
			$connDb->exec($tableLikes);
		}
		catch (PDOException $e) {
			die("Table creation Error: ". $e->getMessage());
		}
	}
}
catch(PDOException $e)
{
	echo "Connection failed: " . $e->getMessage();
}
