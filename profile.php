<?php
	session_start();
	require_once 'vendor/checkUserInDb.php';
		if (!$_SESSION['user'] || $_SESSION['user']['varified'] == 0) {
		header('Location: index.php');
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ndaniell's Camagru</title>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
</head>
<body>
	<header class="header">
		<div class="container header__container">
			<div class="camagru_bck">
				<div class="camagru">
					CAMAGRU
				</div>
			</div>
			<div class="header_right">
				<div class="header_menu">
					<a href="profile_my.php">My gallery</a>
					<a href="settings.php">Settings</a>
					<a href="vendor/logout.php">Logout</a>
				</div>
				<!-- <div class="hello_user">
					Hello, <?= $_SESSION['user']['user_name'] ?>!
				</div> -->
			</div>
		</div>
	</header>
	<main class="profile_main">
	
		<form action="vendor/addImages.php" method="POST" class="form none">
			<label class="file">
				<span class="file__text">Выберите файл в формате jpg или png</span>
				<span class="file__name"></span>
				<input class="file__input" type="file" name="file" accept="image/jpeg,image/png">
			</label>
			<button class="btn" name="upload" disabled>Upload</button>
		</form>
		<div class="gallery">
			<?php
				require_once 'vendor/createDatabase.php';
				require_once 'vendor/dbFunctions.php';
				function printImages($files, $connDb) {
					$files = array_filter($files, function($file) {
						return !in_array($file, ['.' , '..']);
					});
					if (count($files)) {
						foreach ($files as $file) {
							$imgData = getUserIdWithSrc($file, $connDb);
							$user_name = getUserNameWithUserId($imgData['user_id'], $connDb)['user_name'];
							$comments_number = getCommentsNumber($imgData['id'], $connDb);
							$currentUserLike = checkCurrentUserLike($_SESSION['user']['id'], $imgData['id'], $connDb);
							$likes_number = getImageLikes($imgData['id'], $connDb);
							if ($currentUserLike) {
								$isLike = 'fas';
							} else {
								$isLike = 'far';
							}
							?>
								<div class="gallery__item">
									<img src="/uploads/<?= $file ?>" alt="image" class="gallery__image">
									<div class="more">
										<div>
											<a href="#"><i class="<?= $isLike ?> fa-heart"></i></a>
											<a href="#" class="likes"> <?= $likes_number ?> </a>
											<a href="#" id="comments"><i class="far fa-comments"></i></a>
											<a href="#" class="comments"> <?= $comments_number ?> </a>
										</div>
										<a href="#">by <?= $user_name ?></a>
									</div>
								</div>
							<?php
						}
					}
					else {
						echo '<div class="no-photo">Нет фото!</div>';
					}
				}

				$dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
				$filesData = array();
				foreach (scandir($dir) as $file) {
					$filesData[$file] = filemtime($dir . '/' . $file);
				}
				arsort($filesData);
				$filesData = array_keys($filesData);
				printImages($filesData, $connDb);
			?>
		</div>
		<div class="modal">
			<img src="" alt="image" class="opened_image">
			<div class="comment_zone"></div>
			<div class="send_comment">
				<input type="text" class="signin_input" placeholder="Comment plz..">
				<button class="signin_btn" type="submit" id="sendCommentBtn">Send</button>
			</div>
			<button class="close_img_btn" type="submit">&times;</button>
		</div>
		<div id="overlay"></div>
	</main>
	<footer class="footer">
		<div class="footer_text">
			&copy; Ndaniell's Camagru BOOOOM!
		</div>
	</footer>
	<script src="js/script.js"></script>
</body>
</html>