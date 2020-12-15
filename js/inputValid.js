const user_name = document.querySelector('[name="user_name"]');
const email = document.querySelector('[name="email"]');
const user_pwd = document.querySelector('[name="user_pwd"]');
var error = 0;

email.addEventListener('input', () => {
	if (email.validity.typeMismatch || email.value.length > 30) {
		error = 1;
	} else {
		error = 0;
	}
});

user_name.addEventListener('input', () => {
	const nameRegex = /^[a-zA-Z0-9_]{3,15}$/;
	if (user_name.value.trim().match(nameRegex)) {
		user_name.closest('.form_login').querySelector('.error_user_name').classList.add('none');
		error = 0;
	} else {
		user_name.closest('.form_login').querySelector('.error_user_name').classList.remove('none');
		error = 1;
	}
});

user_pwd.addEventListener('input', () => {
	const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,15}$/;
	if (user_pwd.value.match(pwdRegex)) {
		user_name.closest('.form_login').querySelector('.error_user_pwd').classList.add('none');
		error = 0;
	} else {
		user_name.closest('.form_login').querySelector('.error_user_pwd').classList.remove('none');
		error = 1;
	}
});