<?php
	session_start();
	require_once __DIR__ . '/../config/setup.php';
	require_once 'dbFunctions.php';
	
	$_POST = json_decode(file_get_contents("php://input"), true);

	$user_id = $_SESSION['user']['id'];
	$imageSrc = $_POST['image_src'];
	$likeClass = $_POST['like'];

	$image_id = getUserIdWithSrc($imageSrc, $connDb)['id'];

	$sql_get_like = "SELECT * FROM likes WHERE image_id = :image_id AND user_id = :user_id";
	$get_like = $connDb->prepare($sql_get_like);
	$get_like->bindValue(':image_id', $image_id);
	$get_like->bindValue(':user_id', $user_id);
	$get_like->execute();
	$like_found = $get_like->fetch(PDO::FETCH_ASSOC);

	if ($like_found) {
		$sql_delete_like = "DELETE FROM likes WHERE image_id = :image_id";
		$delete_like = $connDb->prepare($sql_delete_like);
		$delete_like->bindValue(':image_id', $image_id);
		$delete_like->execute();
		$likes_number = getImageLikes($image_id, $connDb);
		$response = [
			"status" => false,
			"likes" => $likes_number
		];
		echo json_encode($response);
		die();
	} else {
		// print_r("no likes!");
		$data_upload = array(
			'image_id' => "$image_id",
			'user_id' => "$user_id"
		);
		$sql_insert_like = "INSERT INTO `likes`(`id`, `image_id`, `user_id`) VALUES(NULL, :image_id, :user_id)";
		$insert_like = $connDb->prepare($sql_insert_like);
		$insert_like->execute($data_upload);
		$likes_number = getImageLikes($image_id, $connDb);
		$response = [
			"status" => true,
			"likes" => $likes_number
		];
		echo json_encode($response);
		die();
	}
?>