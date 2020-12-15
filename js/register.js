/* Registration zone */
const registerBtn = document.querySelector('.register_btn');
const inputFields = document.querySelectorAll('.signin_input');

registerBtn.addEventListener('click', (e) => {
	e.preventDefault();
	
	registerBtn.closest('.form_login').querySelectorAll('.signin_input').forEach(e => e.classList.remove('error_fields'));

	let user_name = registerBtn.closest('.form_login').querySelector('[name="user_name"]').value;
	let user_pwd = registerBtn.closest('.form_login').querySelector('[name="user_pwd"]').value;
	let email = registerBtn.closest('.form_login').querySelector('[name="email"]').value;
	let user_pwd_confirm = registerBtn.closest('.form_login').querySelector('[name="user_pwd_confirm"]').value;
	let notif = registerBtn.closest('.form_login').querySelector('#notif_checkbox');
	let notif_status;
	if (notif.checked) {
		notif_status = 1;
	} else {
		notif_status = 0;
	}

	if (error == 0) {
		let user = {
			user_name: user_name,
			user_pwd: user_pwd,
			email: email,
			user_pwd_confirm: user_pwd_confirm,
			notif: notif_status
		};
	
		fetch('vendor/signup.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(user)
		})
		.then(function(response) {
			return response.text()
		})
		.then(function(body) {
			let answer = JSON.parse(body);
			if (answer.status) {
				document.location.href = '/login.php';
			} else {
				if (answer.type === 1) {
					answer.fields.forEach(function (field) {
						registerBtn.closest('.form_login').querySelector(`[name="${field}"]`).classList.add('error_fields');
					});
				}
				registerBtn.closest('.form_login').querySelector('.error_msg').classList.remove('none');
				registerBtn.closest('.form_login').querySelector('.error_msg').textContent = answer.message;
			}
		});
	}
});
