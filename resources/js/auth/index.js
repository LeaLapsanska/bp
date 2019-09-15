export default {
	check() {
		return localStorage.getItem('token') ? true : false;
	},
	setToken(token) {
		localStorage.setItem('token', token);
	},
	getToken() {
		return localStorage.getItem('token');
	},
	removeToken() {
		localStorage.removeItem('token');
	},
	user(user) {
        localStorage.setItem('user', user);
    },
    // getUser() {
	//     return localStorage.getItem('user');
    // },
	getUser() {
		let user = localStorage.getItem('user')
		user = JSON.parse(user)

		return user;
	},
}
