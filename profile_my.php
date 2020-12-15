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
					<a href="index.php">All gallery</a>
					<a href="settings.php">Settings</a>
					<a href="vendor/logout.php">Logout</a>
				</div>
			</div>
		</div>
	</header>
	<main class="profile_main">
		<div class="add_image">
			Add foto
		</div>
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
				function printUserImages($connDb) {
					$user_id = $_SESSION['user']['id'];
					$user_name = $_SESSION['user']['user_name'];
					$images_found = getUserImages($user_id, $connDb);
					for ($i = 0; $i < count($images_found); $i++) {
						$comments_number = getCommentsNumber($images_found[$i]['id'], $connDb);
						$currentUserLike = checkCurrentUserLike($_SESSION['user']['id'], $images_found[$i]['id'], $connDb);
						$likes_number = getImageLikes($images_found[$i]['id'], $connDb);
						if ($currentUserLike) {
							$isLike = 'fas';
						} else {
							$isLike = 'far';
						}
						?>
							<div class="gallery__item">
								<img src="/uploads/<?= $images_found[$i]['image_name'] ?>" alt="image" class="gallery__image">
								<a href="#" class="gallery__btn"><i class="far fa-trash-alt"></i></a>
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
				printUserImages($connDb);
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
		<div class="new_image">
			<div class="camera_container">
				<div class="camera">
					<video autoplay="true" id="video"></video> 
				</div>
				<div class="canvas none">
					<canvas id="canvas" style="z-index: 5;"></canvas>
				</div>
				<div class="clear_block"></div>
			</div>
			<div class="select-box">
				<div>
					<input class="custom-checkbox" type="checkbox" id="filter-1" name="filter-1" value="indigo">
					<label for="filter-1">Blur</label>
				</div>
				<div>
					<input class="custom-checkbox" type="checkbox" id="filter-2" name="filter-2" value="red">
					<label for="filter-2">Contrast</label>
				</div>
				<div>
					<input class="custom-checkbox" type="checkbox" id="filter-3" name="filter-3" value="brown">
					<label for="filter-3">Grayscale</label>
				</div>
				<div>
					<input class="custom-checkbox" type="checkbox" id="filter-4" name="filter-4" value="yellow">
					<label for="filter-4">Saturate</label>
				</div>
				<div>
					<input class="custom-checkbox" type="checkbox" id="filter-5" name="filter-5" value="green">
					<label for="filter-5">Sepia</label>
				</div>
			</div>
			<div class="sticker_box">
				<div class="sticker">
					<input class="custom-checkbox" type="checkbox" id="sticker-1" name="sticker-1">
					<img src="/stcks/woman.png" class="sticker_img" id="sticker-1-img" alt="">
				</div>
				<div class="sticker">
					<input class="custom-checkbox" type="checkbox" id="sticker-2" name="sticker-2">
					<img src="/stcks/cat5.png" class="sticker_img" id="sticker-2-img" alt="">
				</div>
				<div class="sticker">
					<input class="custom-checkbox" type="checkbox" id="sticker-3" name="sticker-3">
					<img src="/stcks/cat4.png" class="sticker_img" id="sticker-3-img" alt="">
				</div>
				<div class="sticker">
					<input class="custom-checkbox" type="checkbox" id="sticker-4" name="sticker-4">
					<img src="/stcks/cat3.png" class="sticker_img" id="sticker-4-img" alt="">
				</div>
			</div>
			<div class="video_buttons">
				<div>
					<button class="capture_btn">Capture</button>
				</div>
				<div>
					<button class="save_btn" disabled>Save</button>
				</div>
				<div class="div_with_upload">
					<input class="upload_file" type="file" name="file" id="file" accept="image/jpeg,image/png">
					<label for="file" class="upload_btn">Upload</label>
				</div>
			</div>
			<button class="close_new_image" type="submit">&times;</button>
		</div>
		<div id="overlay"></div>
	</main>
	<footer class="footer">
		<div class="footer_text">
			&copy; Ndaniell's Camagru BOOOOM!
		</div>
	</footer>
	<script src="js/script.js"></script>
	<script src="js/webcam.js"></script>
</body>
</html>