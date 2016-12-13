
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.a
 */

require('./bootstrap');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */


Vue.component('form-input', require('./components/Input.vue'));
Vue.component('user-card', require('./components/UserCard.vue'));
Vue.component('user-navbar', require('./components/UserNavbar.vue'));
Vue.component('driver-route', require('./components/DriverRoute.vue'));
Vue.component('search-ride', require('./components/SearchRide.vue'));
Vue.component('post-route', require('./components/driver/PostRoute.vue'));
// Vue.component('search-place', require('./components/SearchPlace.vue'));

Vue.http.options.root = 'http://localhost:8000/api';
// Vue.http.options.loginData.url = 'http://localhost:8000/api';

const router = new VueRouter({
  routes : [
    { 
      path: '/', 
      component: require('./components/LoginForm.vue'),
      name: 'login',
      meta: {auth: false},
    },
    { 
      path: '/register', 
      component: require('./components/RegisterForm.vue'),
      name: 'register',
      meta: {auth: false},
    },
    { 
      path: '/home', 
      component: require('./components/Home.vue'),
      name: 'home',
      meta: {auth: true},
    },
  ]
})

Vue.router = router;

Vue.use(require('@websanova/vue-auth'), {
  auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
  http: require('@websanova/vue-auth/drivers/http/vue-resource.1.x.js'),
  router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
  tokenName: 'token',
  authRedirect: {
    path: '/'
  }
});

const app = new Vue({router}).$mount('#app')