<?php
	session_start();
	require_once __DIR__ . '/../config/setup.php';
	require_once 'dbFunctions.php';
	
	$_POST = json_decode(file_get_contents("php://input"), true);

	$commentText = htmlspecialchars($_POST['comment_text']);
	$commentTime = $_POST['comment_time'];
	$user_name = $_SESSION['user']['user_name'];
	$imageSrc = $_POST['image_src'];

	$sql_get_image_id = "SELECT * FROM images WHERE image_name = :image_name";
	$get_image_id = $connDb->prepare($sql_get_image_id);
	$get_image_id->bindValue(':image_name', $imageSrc);
	$get_image_id->execute();
	$image_id_found = $get_image_id->fetch(PDO::FETCH_ASSOC);
	$image_id = $image_id_found['id'];

	$data_upload = array(
		'user_name' => "$user_name",
		'image_id' => "$image_id",
		'comment_text' => "$commentText",
		'comment_time' => "$commentTime"
	);
	$sql_insert_comment = "INSERT INTO `comments`(`id`, `user_name`, `image_id`, `comment_text`, `comment_time`) VALUES(NULL, :user_name, :image_id, :comment_text, :comment_time)";
	$insert_comment = $connDb->prepare($sql_insert_comment);
	$insert_comment->execute($data_upload);

	$imgOwnerID = $image_id_found['user_id'];
	$imgOwnerEmail = getUserNameWithUserId($imgOwnerID, $connDb)['email'];

	if (getUserNameWithUserId($imgOwnerID, $connDb)['notifications'] == 1 && $imgOwnerID != $_SESSION['user']['id']) {
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "To: <$imgOwnerEmail>\r\n";
		$headers .= "From: <fsb@fsb.ru>\r\n";

		$message = '
				<html>
				<head>
				<title>You got new comment in Camagru project</title>
				</head>
				<body>
				<p>You have new comment from user ' . $user_name . ' </p>
				</body>
				</html>
				';
		
		mail($imgOwnerEmail, "You got new comment in Camagru project", $message, $headers);
	}

	$response = [
		"status" => true
	];
	echo json_encode($response);
?>