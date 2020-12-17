<?php
session_start();
require_once __DIR__ . '/../config/setup.php';
require_once 'dbFunctions.php';
require_once 'pagination.php';

$_POST = json_decode(file_get_contents("php://input"), true);
$aim = $_POST;

$result = '';

$i = 1;
if ($aim == 'user') {
	$result = '<div id="pageNav" class="pagin '. $displayUser .'">';

	if ($page) {
		$result = $result . '<a href="profile_my.php"><button><<</button></a>';
		$result = $result . '<a href="profile_my.php?page='. $pageDownUser .'"><button><</button></a>';
	}

	for ($i = 1; $i <= $pagesTotalUser; $i++) {
		if (($i == $pageUser + 1)) {
			$result = $result . '<a href="profile_my.php?page='. $i .'"><button class="active">'. $i .'</button></a>';
		}
		if (($i != $pageUser + 1) && ($i <= $pageUser + 3) && ($i >= $pageUser - 1)) {
			$result = $result . '<a href="profile_my.php?page='. $i .'"><button>'. $i .'</button></a>';
		}
	}
	if (($page + 1) != $pagesTotalUser) {
		$result = $result . '<a href="profile_my.php?page='. $pageUpUser .'"><button>></button></a>';
		$result = $result . '<a href="profile_my.php?page='. $pagesTotalUser .'"><button>>></button></a>';
	}
	$result = $result . "</div>";
	var_dump($page);

	echo $result;
	// $response = [
	// 	"status" => true,
	// 	"result" => $result
	// ];
	// echo json_encode($response);
	die();
}
if ($aim == 'all') {
	$result = '<div id="pageNav" class="pagin '. $display .'">';

	if ($page) {
		$result = $result . '<a href="profile_my.php"><button><<</button></a>';
		$result = $result . '<a href="profile_my.php?page='. $pageDown .'"><button><</button></a>';
	}

	for ($i = 1; $i <= $pagesTotal; $i++) {
		if (($i == $page + 1)) {
			$result = $result . '<a href="profile_my.php?page='. $i .'"><button class="active">'. $i .'</button></a>';
		}
		if (($i != $page + 1) && ($i <= $page + 3) && ($i >= $page - 1)) {
			$result = $result . '<a href="profile_my.php?page='. $i .'"><button>'. $i .'</button></a>';
		}
	}
	if (($page + 1) != $pagesTotal) {
		$result = $result . '<a href="profile_my.php?page='. $pageUp .'"><button>></button></a>';
		$result = $result . '<a href="profile_my.php?page='. $pagesTotal .'"><button>>></button></a>';
	}
	$result = $result . "</div>";

	echo $result;

	// $response = [
	// 	"status" => true,
	// 	"result" => $result
	// ];
	// echo json_encode($response);
	die();
}