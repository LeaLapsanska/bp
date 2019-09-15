// import vue + router

import Vue from 'vue';
import VueRouter from 'vue-router';

// import BootstrapVue from 'bootstrap-vue';
// import 'bootstrap/dist/css/bootstrap.css';
// import 'bootstrap-vue/dist/bootstrap-vue.css';

// Vue.use(BootstrapVue);
// import own auth

import auth from './auth/index.js'
//import refresh from './auth/refresh.js'
Vue.use(require('vue-moment'));

// import views

/*
import VueFilterDateFormat from 'vue-filter-date-format';

Vue.use(VueFilterDateFormat);
*/

import VModal from 'vue-js-modal'
Vue.use(VModal);

import Datetime from 'vue-datetime'
// You need a specific loader for CSS files
import 'vue-datetime/dist/vue-datetime.css'

Vue.use(Datetime)


import App from './views/App'
import Hello from './views/Hello'
import Home from './views/Home'
import Login from './views/Login'
import Profile from './views/Profile'
import Forgottenpassword from './views/Forgottenpassword'
import UsersList from './views/UsersList'
import RoomsList from './views/RoomsList'
import ParametersList from './views/ParametersList'
import EventsList from './views/EventsList'
import EventsCalendar from './views/EventsCalendar'
import MyEventsCalendar from './views/MyEventsCalendar'
import MyEventsList from './views/MyEventsList'
import RoomsOverview from './views/RoomsOverview'

// init router

Vue.use(VueRouter)

// axios

window.axios = require('axios');
window.axios.defaults.baseURL = '/api/';
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
	console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// define routes

const router = new VueRouter({
	routes: [
		{
			path: '/',
			name: 'home',
			component: Home,
			meta: {
				auth: true
			}
		},
		{
			path: '/hello',
			name: 'hello',
			component: Hello,
			meta: {
				auth: true
			}
		},
		{
			path: '/login',
			name: 'login',
			component: Login,
			meta: {
				auth: false
			}
		},
		{
			path: '/profile',
			name: 'profile',
			component: Profile,
			meta: {
				auth: true
			}
		},
        {
            path: '/forgottenpassword',
            name: 'forgottenpassword',
            component: Forgottenpassword,
            meta: {
                auth: false
            }
        },
        {
            path: '/users',
            name: 'users',
            component: UsersList,
            meta: {
                auth: true
            }
        },
        {
            path: '/rooms',
            name: 'rooms',
            component: RoomsList,
            meta: {
                auth: true
            }
        },
        {
            path: '/parameters',
            name: 'parameters',
            component: ParametersList,
            meta: {
                auth: true
            }
        },
        {
            path: '/events',
            name: 'events',
            component: EventsList,
            meta: {
                auth: true
            }
        },
        {
            path: '/calendar',
            name: 'calendar',
            component: EventsCalendar,
            meta: {
                auth: true
            }
        },
        {
            path: '/mycalendar',
            name: 'mycalendar',
            component: MyEventsCalendar,
            meta: {
                auth: true
            }
        },
        {
            path: '/myevents',
            name: 'myevents',
            component: MyEventsList,
            meta: {
                auth: true
            }
        },
        {
            path: '/roomsOverview',
            name: 'roomsOverview',
            component: RoomsOverview,
            meta: {
                auth: true
            }
        },
	],
});

// refresh login token

/*setInterval(function () {
	if(auth.check()) {
		refresh.stuff();
	}
}, 3000);*/

// check if logged in

router.beforeEach((to, from, next) => {
	if (to.meta.auth) {
		if (auth.check()) {
			next()
		} else {
			router.push('/login')
		}
	} else {
		if (to.name == 'login' && auth.check()) {
			router.push('/')
		} else {
			next()
		}
	}
})

// mount app

const app = new Vue({
	el: '#app',
	components: { App },
	router,
});
