
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuetify from 'vuetify'


Vue.use(VueRouter)
Vue.use(Vuetify)

import App from './views/App'
import Job from './views/Job'
import Home from './views/Home'
import Customers from './views/Customers'
import Admin from './views/Admin'
import CustomerForm from './components/CustomerForm'

Vue.component('CustomerForm', require('./components/CustomerForm.vue'));

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/job',
            name: 'job',
            component: Job,
        },
        {
            path: '/customers',
            name: 'customers',
            component: Customers,
        },
        {
            path: '/admin',
            name: 'admin',
            component: Admin,
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
