<?php
session_start();
require_once __DIR__ . '/../config/setup.php';
require_once 'dbFunctions.php';

$perPage = 16;

$allImages = getAllImages($connDb);
// $allUserImages = getUserImages($_SESSION['user']['id'], $connDb);
// $totalUserImages = count($allUserImages);
$totalImages = count($allImages);

if (isset($_GET['page']) && ctype_digit($_GET['page']) == true) {
	$page = $_GET['page'] - 1;
	$offset = $page * $perPage;
} else {
	$page = 0;
	$offset = 0;
}

if ($totalImages > $perPage) {
	$pagesTotal = ceil($totalImages / $perPage);
	$pageUp = $page + 2;
	$pageDown = $page;
	$display = '';
} else {
	$pages = 1;
	$pagesTotal = 1;
	$display = ' none';
}

if (isset($_SESSION['user'])) {
	$allUserImages = getUserImages($_SESSION['user']['id'], $connDb);
	$totalUserImages = count($allUserImages);
	if ($totalUserImages > $perPage) {
		$pagesTotalUser = ceil($totalUserImages / $perPage);
		$pageUpUser = $page + 2;
		$pageDownUser = $page;
		$displayUser = '';
	} else {
		$pagesUser = 1;
		$pagesTotalUser = 1;
		$displayUser = ' none';
	}
}
