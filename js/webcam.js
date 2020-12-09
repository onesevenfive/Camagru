async function getWebcam(){
	try {
		const videoSrc = await navigator.mediaDevices.getUserMedia({video:true});
		var video = document.getElementById("video");
		video.srcObject = videoSrc;
	} catch(error) {
		console.log(error);
	}
}

getWebcam();

const captureBtn = document.querySelector('.capture_btn');
let canvas = document.getElementById("canvas");
let canvasContext = canvas.getContext("2d");
captureBtn.addEventListener('click', () => {
	canvasContext.drawImage(video, 0, 0, 320, 240);
});