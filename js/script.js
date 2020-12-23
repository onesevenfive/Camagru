const form = document.querySelector('.form');
const gallery = document.querySelector('.gallery');
// const fileInput = document.querySelector('.file__input');
const formBtn = document.querySelector('.btn');

const closeOpenPict = document.querySelector('.close_img_btn');
const addImage = document.querySelector('.add_image');
const newImageMake = document.querySelector('.new_image');
const closeNewImage = document.querySelector('.close_new_image');
const overlay = document.getElementById('overlay')
// const uploadFile = document.querySelector('.upload_file');
// const saveBtn = document.querySelector('.save_btn');

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
				like.closest('.gallery__item').querySelector('.likes').innerHTML = answer.likes;
			} else {
				like.classList.remove('fas', 'fa-heart');
				like.classList.add('far', 'fa-heart');
				like.closest('.gallery__item').querySelector('.likes').innerHTML = answer.likes;
			}
		});
	}
	if (e.target.classList.contains('fa-trash-alt')) {
		let self = e.target;
		let imageFullPath = self.closest('.gallery__item').querySelector('.gallery__image').getAttribute('src');
		let stringToDelete = '/uploads/';
		let stringToDeleteLength = stringToDelete.length;
		let imgName = imageFullPath.substr(stringToDeleteLength);

		fetch(`${location.origin}/vendor/deleteImage.php`, {
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

			document.location.reload();
			// paginationScript(0);
			// addPagination();
			// if (gallery.querySelectorAll('.gallery__item').length == 0) {
			// 	gallery.innerHTML = `<div class="no-photo">Нет фото!</div>`;
			// }
		});
	}
})

// gallery.addEventListener('mouseover', (e) => {
// 	let self = e.target;
// 	// console.log(self.className);
// 	if (self.className == "gallery__image" || self.className == "more") {
// 		let imageSrc = self.closest('.gallery__item').querySelector('.gallery__image').getAttribute('src');
// 		console.log(imageSrc);
// 		imageSrc = imageSrc.substring(imageSrc.lastIndexOf('/') + 1);
// 		let comment = {
// 			image_src: imageSrc
// 		}
// 		fetch('vendor/getstats.php', {
// 			method: 'POST',
// 			headers: {
// 				'Content-Type': 'application/json',
// 			},
// 			body: JSON.stringify(comment)
// 		})
// 		.then(function(response) {
// 			return response.text()
// 		})
// 		.then(function(body) {
// 			let answer = JSON.parse(body);
// 			if (answer.status) {
// 				self.closest('.gallery__item').querySelector('.likes').innerHTML = answer.likes;
// 				self.closest('.gallery__item').querySelector('.comments').innerHTML = answer.comments;
// 			}
// 		});
// 	}
// });

// Close opened image and using overlay
closeOpenPict.addEventListener('click', () => {
	const modal = document.querySelector('.modal');

	let imageSrc = modal.querySelector('.opened_image').getAttribute('src');
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
			let allImg = gallery.querySelectorAll('.gallery__image');
			let neededImg, t;
			for (let i = 0; i < allImg.length; i++) {
				t = allImg[i].getAttribute('src');
				t = t.substring(t.lastIndexOf('/') + 1);
				if (t == imageSrc) {
					neededImg = allImg[i];
					break;
				}
			}
			neededImg.closest('.gallery__item').querySelector('.comments').innerHTML = answer.comments;
		}
	});

	closePicture(modal);
});

overlay.addEventListener('click', () => {
	const modal = document.querySelector('.modal');
	closePicture(modal);
});


//Modal opener and closer
function openPicture(modal, img_src) {
	if (modal == null) return;
	modal.classList.add('active');
	overlay.classList.add('active')
	modal.querySelector('.opened_image').src = img_src;
	modal.querySelector('.signin_input').classList.remove('error_fields');
}

window.onkeydown = function (e) {
	if (e.keyCode == 27) {
		if (document.querySelector('.modal').classList.contains('active')) {
			let crntModal = document.querySelector('.modal');
			closePicture(crntModal);
		}
	}
};

function closePicture(modal) {
	if (modal == null) return;
	modal.classList.remove('active');
	overlay.classList.remove('active')
}

//Comment zone
// Add new comment
sendCommentBtn.addEventListener('click', (e) => {
	e.preventDefault();
	sendCommentBtn.closest('.modal').querySelector('.signin_input').classList.remove('error_fields');
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
	if (commentText == "") {
		sendCommentBtn.closest('.modal').querySelector('.signin_input').classList.add('error_fields');
	} else {
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
	}
});

//Fill all comments
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
				allComments.innerHTML += "<span class='comment_username'>" + answer.comments[i]['user_name'] + "</span>" + 
				" " + "<span class='comment_time'>" + answer.comments[i]['comment_time'] + "</span>" + " : " + answer.comments[i]['comment_text'] + "<br>";
			}
		}
	});
}

function addPagination() {
	if (document.getElementById('pageNav')) {
		document.getElementById('pageNav').remove();
	}
	if (document.location.pathname == '/profile.php' || document.location.pathname == '/index.php') {
		fetch('vendor/paginationScript.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify('all')
		})
		.then(function(response) {
			return response.text()
		})
		.then(function(body) {
			document.querySelector('.profile_main').insertAdjacentHTML('beforeend', body);
		});
	}
	if (document.location.pathname == '/profile_my.php') {
		fetch('vendor/paginationScript.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify('user')
		})
		.then(function(response) {
			return response.text()
		})
		.then(function(body) {
			document.querySelector('.profile_main').insertAdjacentHTML('beforeend', body);
		});
	}
}

function backToIndex() {
	window.location = 'index.php';
}