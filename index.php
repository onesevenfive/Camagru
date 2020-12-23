<?php
	session_start();
	require_once 'vendor/checkUserInDb.php';
		if ($_SESSION['user'] && $_SESSION['user']['varified'] == 1) {
		header('Location: profile.php');
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
				<div class="camagru" onclick="backToIndex()">
					CAMAGRU
				</div>
			</div>
			<div class="header_right">
				<div class="header_menu">
					<a href="login.php" class="header_login_link">Sign In</a>
					<a href="register.php" class="header_login_link">Sign Up</a>
				</div>
			</div>
		</div>
	</header>
	<main class="profile_main">
		<div class="gallery">
			<?php
				require_once 'config/setup.php';
				require_once 'vendor/dbFunctions.php';
				require_once 'vendor/pagination.php';
				function printAllImages($connDb, $offset, $perPage) {
					$images_found = getAllImagesLimit($connDb, $offset, $perPage);
					for ($i = 0; $i < count($images_found); $i++) {
						$imgData = getUserIdWithSrc($images_found[$i]['image_name'], $connDb);
						$user_name = getUserNameWithUserId($imgData['user_id'], $connDb)['user_name'];
						$comments_number = getCommentsNumber($images_found[$i]['id'], $connDb);
						$likes_number = getImageLikes($images_found[$i]['id'], $connDb);
						?>
							<div class="gallery__item">
								<img src="/uploads/<?= $images_found[$i]['image_name'] ?>" alt="image" class="gallery__image">
								<div class="more">
									<div>
										<a href="#"><i class="far fa-heart"></i></a>
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
				printAllImages($connDb, $offset, $perPage);
			?>
		</div>
		<div class="modal">
			<img src="" alt="image" class="opened_image">
			<div class="comment_zone"></div>
			<div class="send_comment">
				<input type="text" class="signin_input" placeholder="Comment plz..">
				<button class="signin_btn" type="submit" id="sendCommentBtn">Send</button>
			</div>
			<button class="close_img_btn" type="submit">Close</button>
		</div>
		<div id="overlay"></div>
		<?php
			$i = 1;
			echo '<div id="pageNav" class="pagin'. $display .'">';

			if ($page) {
				echo '<a href="index.php"><button><<</button></a>';
				echo '<a href="index.php?page='. $pageDown .'"><button><</button></a>';
			}

			for ($i = 1; $i <= $pagesTotal; $i++) {
				if (($i == $page + 1)) {
					echo '<a href="index.php?page='. $i .'"><button class="active_page">'. $i .'</button></a>';
				}
				if (($i != $page + 1) && ($i <= $page + 3) && ($i >= $page - 1)) {
					echo '<a href="index.php?page='. $i .'"><button>'. $i .'</button></a>';
				}
			}
			if (($page + 1) != $pagesTotal) {
				echo '<a href="index.php?page='. $pageUp .'"><button>></button></a>';
				echo '<a href="index.php?page='. $pagesTotal .'"><button>>></button></a>';
			}
			echo "</div>";
		?>
	</main>
	<footer class="footer">
		<div class="footer_text">
			&copy; Ndaniell's Camagru BOOOOM!
		</div>
	</footer>
	<!-- <script src="js/script.js"></script> -->
</body>
</html>