const user_name = document.querySelector('[name="user_name"]');
const email = document.querySelector('[name="email"]');
const user_pwd = document.querySelector('[name="user_name"]');

console.log(email);

email.addEventListener('input', () => {
	if (email.validity.typeMismatch) {
		email.setCustomValidity("I expect an e-mail, darling! 30 chars long!!");
	} else {
		email.setCustomValidity("");
	}
});

user_name.addEventListener('input', () => {
	const nameRegex = /^[a-zA-Z0-9_]{3,15}$/;
	if (user_name.value.match(nameRegex)) {
		user_name.setCustomValidity("I expect an user_name, 3-15 chars long, a-z, A-Z, 0-9!!");
	} else {
		user_name.setCustomValidity("");
	}
});