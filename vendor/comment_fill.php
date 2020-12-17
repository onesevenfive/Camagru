<?php
	session_start();
	require_once __DIR__ . '/../config/setup.php';
	
	$_POST = json_decode(file_get_contents("php://input"), true);

	$imageSrc = $_POST['image_src'];

	$sql_get_image_id = "SELECT * FROM images WHERE image_name = :image_name";
	$get_image_id = $connDb->prepare($sql_get_image_id);
	$get_image_id->bindValue(':image_name', $imageSrc);
	$get_image_id->execute();
	$image_id_found = $get_image_id->fetch(PDO::FETCH_ASSOC);
	$image_id = $image_id_found['id'];

	$sql_get_comments = "SELECT * FROM comments WHERE image_id = :image_id ORDER BY id DESC";
	$get_comments = $connDb->prepare($sql_get_comments);
	$get_comments->bindValue(':image_id', $image_id);
	$get_comments->execute();
	$comments = $get_comments->fetchAll(PDO::FETCH_ASSOC);

	$response = [
		"status" => true,
		"comments" => $comments
	];
	echo json_encode($response);
?>