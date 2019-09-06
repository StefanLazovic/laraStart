require('./bootstrap');
window.Vue = require('vue');


////////////////////////////////////////////////////////////////////////////////
// AdminLTE
require('admin-lte');


////////////////////////////////////////////////////////////////////////////////
import VueRouter from 'vue-router'
Vue.use(VueRouter)
let routes = [
  { path: '/dashboard', component: require('./components/Dashboard.vue').default },
  { path: '/users', component: require('./components/Users.vue').default },
  { path: '/profile', component: require('./components/Profile.vue').default },
  { path: '/developer', component: require('./components/Developer.vue').default },
  { path: '/home', function() {return view('home')} },
  { path: '*', component: require('./components/NotFound.vue').default }
]
const router = new VueRouter({
  mode: 'history',
  routes: routes
})


////////////////////////////////////////////////////////////////////////////////
// Access Control List (Gate.js, AuthServiceProvider.php, master.blade.php, Users.vue)
import Gate from './Gate.js'
Vue.prototype.$gate = new Gate(window.user);


////////////////////////////////////////////////////////////////////////////////
// vform
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)


////////////////////////////////////////////////////////////////////////////////
// filters for beautifying date (moment.js) and user's type field uppercase
Vue.filter('upText', function(text) {
  return text.charAt(0).toUpperCase() + text.slice(1);
})
// Moment.js
var moment = require('moment');
// Users.vue
Vue.filter('myDate', function(created) {
  return moment(created).format('LLLL');
})


////////////////////////////////////////////////////////////////////////////////
// create a custom event ===> Fire.$emit('RefreshPage'), Fire.$on('RefreshPage', () => {})
window.Fire = new Vue();


////////////////////////////////////////////////////////////////////////////////
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  thickness: '7px'
})


////////////////////////////////////////////////////////////////////////////////
// Sweet Alert 2
import Swal from 'sweetalert2'
window.Swal = Swal;
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});
window.Toast = Toast;


////////////////////////////////////////////////////////////////////////////////
// 404 Not Found
Vue.component('not-found', require('./components/NotFound.vue').default);

// laravel-vue-pagination (app.js, Users.vue)
Vue.component('pagination', require('laravel-vue-pagination'));


////////////////////////////////////////////////////////////////////////////////
const app = new Vue({
    el: '#app',
    router,
    data: {
      search: '',
      searchBox: false
    },
    methods: {

      // search form (app.js, master.blade.php, Users.vue, UserController@search, api.php)
      // _.debounce() - delays triggering a fat arrow function from the moment _.debounce is initiated - delay value is given in milliseconds
      searchit: _.debounce(() => {
        Fire.$emit('searching');
      }, 300)

    }
});


// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
