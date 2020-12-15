const openModalButtons = document.querySelectorAll('[data-modal-target]');
const closeModalButtons = document.querySelectorAll('[data-close-button]');

openModalButtons.forEach(button => {
	button.addEventListener('click', () => {
		const modal = document.querySelector(button.dataset.modalTarget);

		modal.querySelectorAll('.signin_input').forEach(e => e.classList.remove('error_fields'));
		modal.querySelector('.error_msg').classList.add('none');

		openModal(modal);
		const apply = modal.querySelector('.blue_btn');
		
		apply.addEventListener('click', (e) => {
			e.preventDefault();
			let new_email;
			let new_user_pwd;
			let new_user_name;
			let notif;
			let error = 0;

			if (modal.querySelector('.signin_input').name == 'new_email') {
				new_email = modal.querySelector('.signin_input').value;
				notif = modal.querySelector('#notif_checkbox').checked;
			}
			if (modal.querySelector('.signin_input').name == 'new_user_pwd') {
				new_user_pwd = modal.querySelector('.signin_input').value;
			}
			if (modal.querySelector('.signin_input').name == 'new_user_name') {
				new_user_name = modal.querySelector('.signin_input').value;
			}
			let user_pwd = modal.querySelector(`[name="user_pwd"]`).value;

			let user = {
				new_user_name: new_user_name,
				new_user_pwd: new_user_pwd,
				new_email: new_email,
				user_pwd: user_pwd,
				notif: notif
			};

			const nameRegex = /^[a-zA-Z0-9_]{3,15}$/;
			const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,15}$/;

			if (new_user_name) {
				if (new_user_name.trim().match(nameRegex)) {
					modal.querySelector('.error_user_name').classList.add('none');
					error = 0;
				} else {
					modal.querySelector('.error_user_name').classList.remove('none');
					error = 1;
				}
			}
			if (new_user_pwd) {
				if (new_user_pwd.match(pwdRegex)) {
					modal.querySelector('.error_user_pwd').classList.add('none');
					error = 0;
				} else {
					modal.querySelector('.error_user_pwd').classList.remove('none');
					error = 1;
				}
			}
			// console.log(error);
			if (error == 0) {
				fetch('vendor/settingsChange.php', {
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
						document.location.href = '/settings.php';
					} else {
						if (answer.type === 1) {
							answer.fields.forEach(function (field) {
								modal.querySelector(`[name="${field}"]`).classList.add('error_fields');
							});
						}
						modal.querySelector('.error_msg').classList.remove('none');
						modal.querySelector('.error_msg').textContent = answer.message;
					}
				});
			}
		});
	});
});

closeModalButtons.forEach(button => {
	button.addEventListener('click', () => {
		const modal = button.closest('.modal');
		closeModal(modal);
	});
});

function openModal(modal) {
	if (modal == null) return;
	modal.classList.add('active');
}

function closeModal(modal) {
	if (modal == null) return;
	modal.classList.remove('active');
	field = modal.querySelectorAll('.signin_input');
	if (modal.querySelector('.error_user_name')) {
		modal.querySelector('.error_user_name').classList.add('none');
	}
	if (modal.querySelector('.error_user_pwd')) {
		modal.querySelector('.error_user_pwd').classList.add('none');
	}
	field.forEach(pole => {
		pole.value = '';
	});
}
