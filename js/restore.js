const restoreBtn = document.querySelector('#restore');
const setBtn = document.querySelector('#set');
const user_pwd = document.querySelector('[name="restore_user_pwd"]');
var error = 0;

if (restoreBtn) {
	restoreBtn.addEventListener('click', (e) => {
		e.preventDefault();

		let email = restoreBtn.closest('.form_login').querySelector('.signin_input').value;
		let comment = {
			email: email
		}
		fetch('vendor/restore.php', {
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
			restoreBtn.closest('.form_login').querySelector('.error_msg').classList.remove('none');
			restoreBtn.closest('.form_login').querySelector('.error_msg').textContent = answer.message;
		});
	});
}

if (setBtn) {
	setBtn.addEventListener('click', (e) => {
		e.preventDefault();

		let newPwd = setBtn.closest('.form_login').querySelector('[name="restore_user_pwd"]').value;
		let newPwdConfirmed = setBtn.closest('.form_login').querySelector('[name="confirmed_restore_user_pwd"]').value;
		let getFromUrl = window.location.search;
		let getString = new URLSearchParams(getFromUrl);
		let user_hash = getString.get('hash_restore');

		setBtn.closest('.form_login').querySelector('[name="restore_user_pwd"]').value = '';
		setBtn.closest('.form_login').querySelector('[name="confirmed_restore_user_pwd"]').value = '';

		if (error == 0) {
			let comment = {
				newPwd: newPwd,
				newPwdConfirmed: newPwdConfirmed,
				user_hash: user_hash
			}
			fetch('vendor/restore.php', {
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
				setBtn.closest('.form_login').querySelector('.error_msg').classList.remove('none');
				setBtn.closest('.form_login').querySelector('.error_msg').textContent = answer.message;
				setTimeout(function () {
					window.location = 'login.php';
				}, 3000);
			});
		}
	});
}

if (user_pwd) {
	user_pwd.addEventListener('input', () => {
		const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,15}$/;
		if (user_pwd.value.match(pwdRegex)) {
			user_pwd.closest('.form_login').querySelector('.error_user_pwd').classList.add('none');
			error = 0;
		} else {
			user_pwd.closest('.form_login').querySelector('.error_user_pwd').classList.remove('none');
			error = 1;
		}
	});
}