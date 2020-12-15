<?php
	session_start();
	require_once 'createDatabase.php';

// Get all raw from Images by Image_name
function getUserIdWithSrc ($imgSrc, $connDb) {
	$sql_get_image_id = "SELECT * FROM images WHERE image_name = :image_name";
	$get_image_id = $connDb->prepare($sql_get_image_id);
	$get_image_id->bindValue(':image_name', $imgSrc);
	$get_image_id->execute();
	$image_id_found = $get_image_id->fetch(PDO::FETCH_ASSOC);
	// $user_id = $image_id_found['user_id'];
	return $image_id_found;
}

// Get user name from Users by user ID
function getUserNameWithUserId ($user_id, $connDb) {
	$sql_get_user_name = "SELECT * FROM users WHERE id = :user_id";
	$get_user_name = $connDb->prepare($sql_get_user_name);
	$get_user_name->bindValue(':user_id', $user_id);
	$get_user_name->execute();
	$user_name_found = $get_user_name->fetch(PDO::FETCH_ASSOC);
	// $user_name = $user_name_found['user_name'];
	return $user_name_found;
}

// Get number of comments for image ID
function getCommentsNumber ($image_id, $connDb) {
	$sql_get_comments = "SELECT COUNT(*) FROM comments WHERE image_id = :image_id";
	$get_comments = $connDb->prepare($sql_get_comments);
	$get_comments->bindValue(':image_id', $image_id);
	$get_comments->execute();
	$comments_found = $get_comments->fetchColumn();
	return $comments_found;
}

// Check if user has like on image
function checkCurrentUserLike ($user_id, $image_id, $connDb) {
	$sql_get_like = "SELECT * FROM likes WHERE image_id = :image_id AND user_id = :user_id";
	$get_like = $connDb->prepare($sql_get_like);
	$get_like->bindValue(':image_id', $image_id);
	$get_like->bindValue(':user_id', $user_id);
	$get_like->execute();
	$like_found = $get_like->fetch(PDO::FETCH_ASSOC);
	return($like_found);
}

// Get number of likes for image ID
function getImageLikes ($image_id, $connDb) {
	$sql_get_likes = "SELECT COUNT(*) FROM likes WHERE image_id = :image_id";
	$get_likes = $connDb->prepare($sql_get_likes);
	$get_likes->bindValue(':image_id', $image_id);
	$get_likes->execute();
	$likes_found = $get_likes->fetchColumn();
	return($likes_found);
}

// Get all images of current User
function getUserImages($user_id, $connDb) {
	$sql_get_user_images = "SELECT * FROM images WHERE user_id = :user_id ORDER BY id DESC";
	$get_user_images = $connDb->prepare($sql_get_user_images);
	$get_user_images->bindValue(':user_id', $user_id);
	$get_user_images->execute();
	$images_found = $get_user_images->fetchAll(PDO::FETCH_ASSOC);
	return($images_found);
}