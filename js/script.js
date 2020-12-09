const form = document.querySelector('.form');
const gallery = document.querySelector('.gallery');
const fileInput = document.querySelector('.file__input');
const formBtn = document.querySelector('.btn');

// var pictureCrt = document.querySelectorAll('.gallery__item');
const closeOpenPict = document.querySelector('.close_img_btn');
const overlay = document.getElementById('overlay')

const sendCommentBtn = document.querySelector('#sendCommentBtn');

// Opening modal windows, liking foto, deleting foto
gallery.addEventListener('click', (e) => {
	if (e.target.classList.contains('gallery__image')) {
		let pict = e.target;
		const modal = document.querySelector('.modal');
		const img_src = pict.src;
		openPicture(modal, img_src);
		fillComments(img_src.substring(img_src.lastIndexOf('/') + 1));
	}
	if (e.target.classList.contains('fa-heart')) {
		let like = e.target;
		let likeClass = like.className;
		let imageSrc = like.closest('.gallery__item').querySelector('.gallery__image').getAttribute('src');
		imageSrc = imageSrc.substring(imageSrc.lastIndexOf('/') + 1);
		console.log(likeClass);
		let comment = {
			like: likeClass,
			image_src: imageSrc
		}
		fetch('vendor/likes.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(comment)
		})
		.then(function(response) {
			return response.text()
		})
		.then(function(body) {
			let answer = JSON.parse(body);
			if (answer.status) {
				like.classList.remove('far', 'fa-heart');
				like.classList.add('fas', 'fa-heart');
			} else {
				like.classList.remove('fas', 'fa-heart');
				like.classList.add('far', 'fa-heart');
			}
		});
	}
	if (e.target.classList.contains('fa-trash-alt')) {
		let self = e.target;
		let imageFullPath = self.closest('.gallery__item').querySelector('.gallery__image').getAttribute('src');
		let stringToDelete = '/uploads/';
		let stringToDeleteLength = stringToDelete.length;
		let imgName = imageFullPath.substr(stringToDeleteLength);

		fetch(`${location.origin}/deleteImage.php`, {
			method: 'POST',
			headers: {
				'Content-type': 'application/x-www-form-urlencoded'
			},
			body: `deleteImage=${imgName}`
		})
		.then(function(response) {
			return response.text()
		})
		.then(function(body) {
			self.closest('.gallery__item').remove();
			if (gallery.querySelectorAll('.gallery__item').length == 0) {
				gallery.innerHTML = `<div class="no-photo">Нет фото!</div>`;
			}
		});
	}
})

gallery.addEventListener('mouseover', (e) => {
	let imageSrc = gallery.querySelector('.gallery__image').getAttribute('src');
	imageSrc = imageSrc.substring(imageSrc.lastIndexOf('/') + 1);
	let comment = {
		image_src: imageSrc
	}
	fetch('vendor/getstats.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify(comment)
	})
	.then(function(response) {
		return response.text()
	})
	.then(function(body) {
		let answer = JSON.parse(body);
		if (answer.status) {
			gallery.querySelector('.likes').innerHTML = answer.likes;
			gallery.querySelector('.comments').innerHTML = answer.comments;
		}
	});
});

// File name on button
fileInput.addEventListener('change', (event) => {
	let files = event.currentTarget.files;

	if (files) {
		formBtn.removeAttribute('disabled');
		fileInput.closest('.file').querySelector('.file__text').textContent = '';
		fileInput.closest('.file').querySelector('.file__name').textContent = files[0].name;
	}
})

//Add foto
form.addEventListener('submit', (event) => {
	event.preventDefault();
	let file = form.querySelector('.file__input').files[0];

	let type = file.type;
	let size = file.size;

	if (size <= 8000000 && (type == 'image/jpg' || type == 'image/jpeg' || type == 'image/png')) {
		let formData = new FormData(form);
		formData.append('file', file);

		fetch(`${location.origin}/addImages.php`, {
			method: 'POST',
			body: formData
		})
		.then(function(response) {
			return response.text()
		})
		.then(function(body) {
			let answer = JSON.parse(body);
			let fileHTML = `
			<div class="gallery__item">
				<img src="/uploads/${answer.image_name}" alt="image" class="gallery__image">
				<a href="#" class="gallery__btn"><i class="far fa-trash-alt"></i></a>
				<div class="more">
					<div>
						<a href="#"><i class="far fa-heart"></i></a>
						<a href="#" class="likes"> </a>
						<a href="#" id="comments"><i class="far fa-comments"></i></a>
						<a href="#" class="comments"> </a>
					</div>
					<a href="#">by ${answer.user_name}</a>
				</div>
			</div>
			`;
			
			if (document.contains(document.querySelector('.no-photo'))) {
				document.querySelector('.no-photo').remove();
			}
			gallery.insertAdjacentHTML('afterbegin', fileHTML);
			formBtn.setAttribute('disabled', true);
			fileInput.closest('.file').querySelector('.file__text').textContent = 'Выберите файл в формате jpg или png';
			fileInput.closest('.file').querySelector('.file__name').textContent = '';
		});
	} else {
		alert('Файл не соответствует размеру или типу');
	}
});

closeOpenPict.addEventListener('click', () => {
	const modal = document.querySelector('.modal');
	closePicture(modal);
});

overlay.addEventListener('click', () => {
	const modal = document.querySelector('.modal');
	closePicture(modal);
});

function openPicture(modal, img_src) {
	if (modal == null) return;
	modal.classList.add('active');
	overlay.classList.add('active')
	modal.querySelector('.opened_image').src = img_src;
}

function closePicture(modal) {
	if (modal == null) return;
	modal.classList.remove('active');
	overlay.classList.remove('active')
}

//Comment zone

sendCommentBtn.addEventListener('click', (e) => {
	e.preventDefault();
	let commentText = sendCommentBtn.closest('.modal').querySelector('.signin_input').value;
	let imageSrc = sendCommentBtn.closest('.modal').querySelector('.opened_image').src;
	imageSrc = imageSrc.substring(imageSrc.lastIndexOf('/') + 1);


	let today = new Date();
	let date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
	let time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
	let dateTime = date+' '+time;

	let comment = {
		comment_text: commentText,
		comment_time: dateTime,
		image_src: imageSrc
	}

	fetch('vendor/comment.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify(comment)
	})
	.then(function(response) {
		return response.text()
	})
	.then(function(body) {
		let answer = JSON.parse(body);
		if (answer.status) {
			fillComments(imageSrc);
			sendCommentBtn.closest('.modal').querySelector('.signin_input').value = "";
		}
	});
});

function fillComments(imageSrc) {
	let allComments = sendCommentBtn.closest('.modal').querySelector('.comment_zone');
	allComments.innerHTML = "";
	let comment = {
		image_src: imageSrc
	}

	fetch('vendor/comment_fill.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify(comment)
	})
	.then(function(response) {
		return response.text()
	})
	.then(function(body) {
		let answer = JSON.parse(body);
		if (answer.status) {
			for (let i = 0; i < answer.comments.length; i++) {
				allComments.innerHTML += answer.comments[i]['user_name'] + " " + answer.comments[i]['comment_time'] + " : " + answer.comments[i]['comment_text'] + "<br>";
			}
		}
	});
}