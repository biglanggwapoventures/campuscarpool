
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
Vue.component('ccbutton', require('./components/StateButton.vue'));
// Vue.component('search-place', require('./components/SearchPlace.vue'));

Vue.http.options.root = 'http://campuscarpool.dev/api';
// Vue.http.options.root = 'http://localhost:8000/api';
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
      path: '/dashboard', 
      component: require('./components/Home.vue'),
      name: 'dashboard',
      meta: {auth: true},
      children: [
        {
          path: 'driver',
          component: require('./components/driver/DriverTemplate.vue'),
          name: 'driver-dashboard',
          children: [
            {
              path: 'index',
              component: require('./components/driver/DriverHome.vue'),
              name: 'driver-index'
            },
            {
              path: 'routes',
              component: require('./components/driver/DriverRoutes.vue'),
              name: 'driver-routes',
            },
            {
              path: 'route/:id/view-requests',
              component: require('./components/driver/ViewRequests.vue'),
              name: 'driver-route-view-requests'
            },
          ]
        },
        {
          path: 'commuter',
          component: require('./components/commuter/CommuterTemplate.vue'),
          name: 'commuter-dashboard',
          children: [
            {
              path: 'index',
              component: require('./components/commuter/CommuterHome.vue'),
              name: 'commuter-index'
            },
            {
              path: 'browse-routes',
              component: require('./components/commuter/BrowseRoutes.vue'),
              name: 'browse-routes',
            },
            {
              path: 'my-requests',
              component: require('./components/commuter/MyRequests.vue'),
              name: 'commuter-requests',
            },
          ]

        }
      ]
    },
    { 
      path: '/new-route', 
      component: require('./components/driver/PostRide.vue'),
      name: 'new-route',
      meta: {auth: true},
    },
    {
      path: '/view-route/:id',
      component: require('./components/commuter/ViewDriverRoute.vue'),
      name: 'view-route',
      meta: {auth: true},
    },
    {
      path: '/preview-route/:id',
      component: require('./components/driver/PreviewRoute.vue'),
      name: 'preview-route',
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
  },
  notFoundRedirect: {path: '/dashboard'}
});
// Vue.use(ElementUI)

const app = new Vue({router}).$mount('#app')