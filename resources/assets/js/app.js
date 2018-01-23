
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
// Vue.use(Vuetify)

Vue.use(Vuetify, {
    theme: {
        primary: '#1976D2',
        secondary: '#424242',
        accent: '#82B1FF',
        error: '#FF5252',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FFC107'
      }
  })

import App from './views/App'
import Job from './views/Job'
import Home from './views/Home'
import Customers from './views/Customers'
import Admin from './views/Admin'
import CustomerForm from './components/CustomerForm'
import JobLookup from './components/JobLookup'
import EmployeeForm from './components/EmployeeForm'

Vue.component('CustomerForm', require('./components/CustomerForm.vue'));
Vue.component('JobLookup', require('./components/JobLookup.vue'));
Vue.component('EmployeeForm', require('./components/EmployeeForm.vue'));

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

Vue.use(Vuetify, {
  theme: {
    primary: '#3f51b5',
    secondary: '#b0bec5',
    accent: '#8c9eff',
    error: '#b71c1c'
  }
})