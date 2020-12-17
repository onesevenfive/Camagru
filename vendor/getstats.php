<?php
	session_start();
	require_once __DIR__ . '/../config/setup.php';
	require_once 'dbFunctions.php';
	
	$_POST = json_decode(file_get_contents("php://input"), true);

	$imageSrc = $_POST['image_src'];

	$imgData = getUserIdWithSrc($imageSrc, $connDb);
	$comments_number = getCommentsNumber($imgData['id'], $connDb);
	$likes_number = getImageLikes($imgData['id'], $connDb);

	$response = [
		"status" => true,
		"comments" => $comments_number,
		"likes" => $likes_number
	];
	echo json_encode($response);