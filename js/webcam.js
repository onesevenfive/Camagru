const blur = document.getElementById('filter-1');
const contrast = document.getElementById('filter-2');
const grayscale = document.getElementById('filter-3');
const saturate = document.getElementById('filter-4');
const sepia = document.getElementById('filter-5');

const sticker1 = document.getElementById('sticker-1');
const sticker2 = document.getElementById('sticker-2');
const sticker3 = document.getElementById('sticker-3');
const sticker4 = document.getElementById('sticker-4');

const canvasZone = document.querySelector('.canvas');

const uploadFile = document.querySelector('.upload_file');
const saveBtn = document.querySelector('.save_btn');

async function getWebcam(){
	try {
		const videoSrc = await navigator.mediaDevices.getUserMedia({video: {
			width: { ideal: 1280 },
			height: { ideal: 720 }
		}});
		var video = document.getElementById("video");
		video.srcObject = videoSrc;
	} catch(error) {
		console.log(error);
	}
}

const captureBtn = document.querySelector('.capture_btn');
let canvas = document.getElementById("canvas");
var canvasContext = canvas.getContext("2d");
captureBtn.addEventListener('click', () => {
	canvas.width = 1280;
	canvas.height = 720;
	canvasContext.drawImage(video, 0, 0, 1280, 720, 0, 0, 1280, 720);
	newImageMake.querySelector('.camera').classList.add('none');
	newImageMake.querySelector('.canvas').classList.remove('none');
	if (newImageMake.querySelector('.camera').classList.contains('none')) {
		saveBtn.removeAttribute('disabled');
		enableStickers(1);
		enableFilters(1);
	}
});

addImage.addEventListener('click', () => {
	blur.checked = false;
	contrast.checked = false;
	grayscale.checked = false;
	saturate.checked = false;
	sepia.checked = false;
	newImageMake.classList.add('active');
	overlay.classList.add('active');
	saveBtn.setAttribute('disabled', true);
	enableStickers(0);
	enableFilters(0);
	getWebcam();
});

closeNewImage.addEventListener('click', () => {
	video.srcObject = null;
	closePicture(newImageMake);
	newImageMake.querySelector('.camera').classList.remove('none');
	newImageMake.querySelector('.canvas').classList.add('none');
	deleteStickers();
	enableStickers(2);

	document.location.reload();
});

blur.addEventListener("click", function() {
	if (blur.checked) {
		canvas.style.filter="blur(5px)";
	} else {
		canvas.style.filter="blur(0)";
	}
});
contrast.addEventListener("click", function() {
	if (contrast.checked) {
		canvas.style.filter="contrast(50%)";
	} else {
		canvas.style.filter="contrast(100%)";
	}
});
grayscale.addEventListener("click", function() {
	if (grayscale.checked) {
		canvas.style.filter="grayscale(100%)";
	} else {
		canvas.style.filter="grayscale(0%)";
	}
});
saturate.addEventListener("click", function() {
	if (saturate.checked) {
		canvas.style.filter="saturate(70%)";
	} else {
		canvas.style.filter="saturate(100%)";
	}
});
sepia.addEventListener("click", function() {
	if (sepia.checked) {
		canvas.style.filter="sepia(80%)";
	} else {
		canvas.style.filter="sepia(0%)";
	}
});

uploadFile.addEventListener('change', (event) => {
	let files = event.currentTarget.files;
	filterToNull(canvas);
	if (files) {
		saveBtn.removeAttribute('disabled');
		captureBtn.setAttribute('disabled', true);

		let file = files[0];
		let type = file.type;
		let size = file.size;
		if (size <= 8000000 && (type == 'image/jpg' || type == 'image/jpeg' || type == 'image/png')) {
			var reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function (e) {
				var img = new Image();
				img.src = e.target.result;
				img.onload = function () {
					var height = this.height;
					var width = this.width;
					canvas.width = width;
					canvas.height = height;
					canvasContext.drawImage(img, 0, 0, width, height, 0, 0, width, height);
					newImageMake.querySelector('.camera').classList.add('none');
					newImageMake.querySelector('.canvas').classList.remove('none');
				}
			};
		} else {
			alert('Файл не соответствует размеру или типу');
		}
	}
});

saveBtn.addEventListener('click', (event) => {
	event.preventDefault();

	let files = uploadFile.files;
	let file = files[0];

	for (let i = 0; i < canvasZone.children.length; i++) {
		const currentCanvas = canvasZone.children[i];
		currentCanvas.getContext('2d');
		canvasContext.drawImage(currentCanvas, 0, 0);
	}
	let fileUrl = canvas.toDataURL();
	let comment = {
		fileUrl: fileUrl
	}
	fetch(`${location.origin}/vendor/addImages.php`, {
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
		newImageMake.querySelector('.camera').classList.remove('none');
		newImageMake.querySelector('.canvas').classList.add('none');
		captureBtn.removeAttribute('disabled');
		saveBtn.setAttribute('disabled', true);
		filterToNull(canvas);
		deleteStickers();
		enableStickers(0);
		enableStickers(2);
		enableFilters(0);
		// paginationScript(1);
		// addPagination();
	})
});

function filterToNull(canvas) {
	canvas.style.filter="blur(0)";
	canvas.style.filter="contrast(100%)";
	canvas.style.filter="grayscale(0%)";
	canvas.style.filter="saturate(100%)";
	canvas.style.filter="sepia(0%)";
	blur.checked = false;
	contrast.checked = false;
	grayscale.checked = false;
	saturate.checked = false;
	sepia.checked = false;
}

function enableFilters(command) {
	if (command == 1) {
		blur.removeAttribute('disabled');
		contrast.removeAttribute('disabled');
		grayscale.removeAttribute('disabled');
		saturate.removeAttribute('disabled');
		sepia.removeAttribute('disabled');
	} else if (command == 0) {
		blur.setAttribute('disabled', true);
		contrast.setAttribute('disabled', true);
		grayscale.setAttribute('disabled', true);
		saturate.setAttribute('disabled', true);
		sepia.setAttribute('disabled', true);
	}
}

function enableStickers(command) {
	if (command == 1) {
		sticker1.removeAttribute('disabled');
		sticker2.removeAttribute('disabled');
		sticker3.removeAttribute('disabled');
		sticker4.removeAttribute('disabled');
	} else if (command == 0) {
		sticker1.setAttribute('disabled', true);
		sticker2.setAttribute('disabled', true);
		sticker3.setAttribute('disabled', true);
		sticker4.setAttribute('disabled', true);
	} else if (command == 2) {
		sticker1.checked = false;
		sticker2.checked = false;
		sticker3.checked = false;
		sticker4.checked = false;
	}
}

function deleteStickers() {
	if (document.getElementById('canvas1')) {
		canvas1.remove();
	}
	if (document.getElementById('canvas2')) {
		canvas2.remove();
	}
	if (document.getElementById('canvas3')) {
		canvas3.remove();
	}
	if (document.getElementById('canvas4')) {
		canvas4.remove();
	}
}

sticker1.addEventListener("click", function() {
	if (sticker1.checked) {
		let canvas1HTML = `<canvas id="canvas1" style="z-index: 6;"></canvas>`;
		canvasZone.insertAdjacentHTML('beforeend', canvas1HTML);
		let canvas1 = document.getElementById('canvas1');
		let canvas1contex = canvas1.getContext("2d");
		let canvas1IMG = document.getElementById('sticker-1-img');
		canvas1.width = 1280;
		canvas1.height = 720;
		canvas1contex.drawImage(canvas1IMG, 0, 420, 310, 300);
	} else {
		if (document.getElementById('canvas1')) {
			canvas1.remove();
		}
	}
});

sticker2.addEventListener("click", function() {
	if (sticker2.checked) {
		let canvas2HTML = `<canvas id="canvas2" style="z-index: 7;"></canvas>`;
		canvasZone.insertAdjacentHTML('beforeend', canvas2HTML);
		let canvas2 = document.getElementById('canvas2');
		let canvas2contex = canvas2.getContext("2d");
		let canvas2IMG = document.getElementById('sticker-2-img');
		canvas2.width = 1280;
		canvas2.height = 720;
		canvas2contex.drawImage(canvas2IMG, 1020, 460, 260, 260);
	} else {
		if (document.getElementById('canvas2')) {
			canvas2.remove();
		}
	}
});

sticker3.addEventListener("click", function() {
	if (sticker3.checked) {
		let canvas3HTML = `<canvas id="canvas3" style="z-index: 8;"></canvas>`;
		canvasZone.insertAdjacentHTML('beforeend', canvas3HTML);
		let canvas3 = document.getElementById('canvas3');
		let canvas3contex = canvas3.getContext("2d");
		let canvas3IMG = document.getElementById('sticker-3-img');
		canvas3.width = 1280;
		canvas3.height = 720;
		canvas3contex.drawImage(canvas3IMG, 0, 420, 300, 300);
	} else {
		if (document.getElementById('canvas3')) {
			canvas3.remove();
		}
	}
});

sticker4.addEventListener("click", function() {
	if (sticker4.checked) {
		let canvas4HTML = `<canvas id="canvas4" style="z-index: 9;"></canvas>`;
		canvasZone.insertAdjacentHTML('beforeend', canvas4HTML);
		let canvas4 = document.getElementById('canvas4');
		let canvas4contex = canvas4.getContext("2d");
		let canvas4IMG = document.getElementById('sticker-4-img');
		canvas4.width = 1280;
		canvas4.height = 720;
		canvas4contex.drawImage(canvas4IMG, 1005, 495, 275, 225);
	} else {
		if (document.getElementById('canvas4')) {
			canvas4.remove();
		}
	}
});

// addPagination();

function paginationScript(data) {
	if (data == 1) {
		if (gallery.children.length > 16) {
			gallery.lastChild.remove();
			// if (document.getElementById('pageNav').classList.contains('none')) {
			// 	document.getElementById('pageNav').classList.remove('none')
			// }
			addPagination();
		}
	}
	if (data == 0) {
		if (gallery.children.length > 16) {

		}
	}
}