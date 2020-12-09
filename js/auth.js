/* Authorization zone */

const signinBtn = document.querySelector('.signin_btn');

signinBtn.addEventListener('click', (e) => {
	e.preventDefault();
	
	signinBtn.closest('.form_login').querySelector('.signin_input').classList.remove('error_fields');

	let user_name = signinBtn.closest('.form_login').querySelector('[name="user_name"]').value;
	let user_pwd = signinBtn.closest('.form_login').querySelector('[name="user_pwd"]').value;

	let user = {
		user_name: user_name,
		user_pwd: user_pwd
	};

	fetch('vendor/signin.php', {
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
			document.location.href = '/profile.php';
		} else {
			if (answer.type === 1) {
				answer.fields.forEach(function (field) {
					signinBtn.closest('.form_login').querySelector(`[name="${field}"]`).classList.add('error_fields');
				});
			}
			signinBtn.closest('.form_login').querySelector('.error_msg').classList.remove('none');
			signinBtn.closest('.form_login').querySelector('.error_msg').textContent = answer.message;
		}
	});
});