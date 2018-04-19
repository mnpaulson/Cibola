
require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuetify from 'vuetify'
import VueFuse from 'vue-fuse'

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
import Customer from './views/Customer'
import Admin from './views/Admin'
import Employee from './views/Employee'
import CustomerForm from './components/CustomerForm'
import JobLookup from './components/JobLookup'
import JobForm from './components/JobForm'
import EmployeeForm from './components/EmployeeForm'
import CustomerList from './components/CustomerList'
import JobList from './components/JobList'


Vue.component('CustomerForm', require('./components/CustomerForm.vue'));
Vue.component('JobLookup', require('./components/JobLookup.vue'));
Vue.component('JobForm', require('./components/JobForm.vue'));
Vue.component('EmployeeForm', require('./components/EmployeeForm.vue'));
Vue.component('CustomerList', require('./components/CustomerList.vue'));
Vue.component('JobList', require('./components/JobList.vue'));
Vue.component('EmployeeJobs', require('./components/EmployeeJobs.vue'));
Vue.component('Alert', require('./components/Alert.vue'));


const router = new VueRouter({
    // mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/job',
            name: 'jobs',
            component: Job
        },
        {
            path: '/job/:id',
            name: 'job',
            component: Job
        },
        {
            path: '/job/:id/:cus',
            name: 'jobcus',
            component: Job
        },
        {
            path: '/admin',
            name: 'admin',
            component: Admin,
        },
        {
            path: '/customer',
            name: 'customers',
            component: Customer
        },
        {
            path: '/customer/:id',
            name: 'customer',
            component: Customer
        },
        {
            path: '/employee',
            name: 'employee',
            component: Employee
        }
    ],
});

Vue.use(VueFuse)

var store = {
    alert: {
        status: null,
        msg: null,
        type: null
    },
    setAlert(status, type, msg) {
        this.alert.status = status;
        this.alert.msg = msg;
        this.alert.type = type;       
    }
};

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    data: {
        store: store      
    }
});

Vue.use(Vuetify, {
  theme: {
    primary: '#3f51b5',
    secondary: '#b0bec5',
    accent: '#8c9eff',
    error: '#b71c1c'
  }
})