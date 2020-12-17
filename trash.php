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