<?php
session_start();
require_once __DIR__ . '/../config/setup.php';

$image = $_POST['deleteImage'];

if (isset($image)) {
	$uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
	unlink($uploadPath . $image);
}

$sql_get_image_id = "SELECT * FROM images WHERE image_name = :image_name";
$get_image_id = $connDb->prepare($sql_get_image_id);
$get_image_id->bindValue(':image_name', $image);
$get_image_id->execute();
$image_id_found = $get_image_id->fetch(PDO::FETCH_ASSOC);
$image_id = $image_id_found['id'];

$sql_delete_img = "DELETE FROM images WHERE image_name = :image_name";
$prep_delete_img = $connDb->prepare($sql_delete_img);
$prep_delete_img->bindValue(':image_name', $image);
$prep_delete_img->execute();

$sql_delete_comments = "DELETE FROM comments WHERE image_id = :image_id";
$delete_comments = $connDb->prepare($sql_delete_comments);
$delete_comments->bindValue(':image_id', $image_id);
$delete_comments->execute();

$sql_delete_likes = "DELETE FROM likes WHERE image_id = :image_id";
$delete_likes = $connDb->prepare($sql_delete_likes);
$delete_likes->bindValue(':image_id', $image_id);
$delete_likes->execute();