<?php
session_start();
require_once __DIR__ . '/../config/setup.php';

// $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

// $tmpName = $_FILES['file']['tmp_name'];
// $name = $_FILES['file']['name'];
// $fileExtension = pathinfo($name, PATHINFO_EXTENSION);
// $uniqueName = time() . uniqid(rand()) . "." . $fileExtension;

// move_uploaded_file($tmpName, $uploadPath . $uniqueName);

// $user_id = $_SESSION['user']['id'];
// $user_name = $_SESSION['user']['user_name'];

// $data_upload = array(
// 	'user_id' => "$user_id",
// 	'image_name' => "$uniqueName"
// );
// $sql_insert_img = "INSERT INTO `images`(`id`, `user_id`, `image_name`) VALUES(NULL, :user_id, :image_name)";
// $prep_insert_img = $connDb->prepare($sql_insert_img);
// $prep_insert_img->execute($data_upload);

// $response = [
// 	"status" => true,
// 	"image_name" => $uniqueName,
// 	"user_name" => $user_name
// ];
// echo json_encode($response);


$_POST = json_decode(file_get_contents("php://input"), true);

$uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
$img = $_POST['fileUrl'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$uniqueName = time() . uniqid(rand()) . '.png';
$file = $uploadPath . $uniqueName;
file_put_contents($file, $data);

$user_id = $_SESSION['user']['id'];
$user_name = $_SESSION['user']['user_name'];

$data_upload = array(
	'user_id' => "$user_id",
	'image_name' => "$uniqueName"
);
$sql_insert_img = "INSERT INTO `images`(`id`, `user_id`, `image_name`) VALUES(NULL, :user_id, :image_name)";
$prep_insert_img = $connDb->prepare($sql_insert_img);
$prep_insert_img->execute($data_upload);

$response = [
	"status" => true,
	"image_name" => $uniqueName,
	"user_name" => $user_name
];
echo json_encode($response);