require('./bootstrap');

import Vue from 'vue';

import { Form, HasError, AlertError } from 'vform';

import swal from 'sweetalert2';
window.swal = swal;
import moment from 'moment';
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.component('pagination', require('laravel-vue-pagination'));

import VueRouter from 'vue-router';

import VueProgressBar from 'vue-progressbar';

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '4px'
});

Vue.use(VueRouter)

Vue.prototype.$can = function(value){
    return Permissions.includes(value);
}
Vue.prototype.$is = function(value){
    return Permissions.includes(value);
}

let routes = [
    { path: '/dashboard', components: require('./components/Dashboard.vue') },
    { path: '/profile', components: require('./components/Profile.vue') },
    { path: '/users', components: require('./components/Users.vue') },
    { path: '/developer', components: require('./components/Developer.vue') },
    { path: '/roles', components: require('./components/Roles.vue') },
    { path: '/permissions', components: require('./components/Permission.vue') }
]

const router = new VueRouter({
    mode: 'history',
    routes
});

Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('myDate', function(mydate){
    return moment(mydate).format('MMMM Do YYYY');
});

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

Vue.component(
    'not-found',
    require('./components/Notfound.vue').default
);

window.Fire = new Vue();

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', swal.stopTimer)
        toast.addEventListener('mouseleave', swal.resumeTimer)
    }
});
window.toast = toast;

const app = new Vue({
    el: '#app',
    router,
    data: {
        search: ''
    },
    methods:{
        searchit: _.debounce(() => {
            Fire.$emit('searching');
        }, 1000)
    }
});