/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import router from './router';
require('./bootstrap');

import swal from 'sweetalert2'
window.swal =swal;

window.toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
})



import { Form, HasError, AlertError } from 'vform'
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

window.Form = Form


window.moment = require('moment');
// Vue.use(DatatableFactory);

// let routes = [
//     { path: '/home', component: require('./components/Home.vue').default },
//     // { path: '/profile', component: require('./components/Profile.vue').default },
//     // { path: '/users', component: require('./components/Users.vue').default },
//     // { path: '/upload', component: require('./components/FileUpload.vue').default },
// ]


// const router = new VueRouter({
//     routes
// })

Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1);
})
Vue.filter('myDate', function(text){
    return moment(text).format('DD/MM/YYYY, h:mm:ss a').toString();
})

Vue.filter('myDateFormate', function(text){
  return moment(text).format('DD/MM/YYYY').toString();
})


/* Common components */

// Vue.component('bs-select', require('./components/common/BootstrapSelect.vue').default);

//registering common components programatically
const req = require.context('./components/common', true, /\.(js|vue)$/i);
req.keys().map(key => {
  const name = key.match(/\w+/)[0];
  return Vue.component(name, req(key).default)
});


/* Vue Progres bar */

import VueProgressBar from 'vue-progressbar'
const VueProgressbarOptions = {
    color: '#bffaf3',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
      speed: '0.2s',
      opacity: '0.6s',
      termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
  }

Vue.use(VueProgressBar, VueProgressbarOptions)

/* ./ Vue Progres bar */

// Vue data table

import VdtnetTable from 'vue-datatables-net'

Vue.use(VdtnetTable)

// vue laravel dataTable
import DataTable from 'laravel-vue-datatable';
Vue.use(DataTable);

import Vue from 'vue';
import {Tabs, Tab} from 'vue-tabs-component';
Vue.component('tabs', Tabs);
Vue.component('tab', Tab);

Vue.component('pagination', require('laravel-vue-pagination'));


window.Fire = new Vue();

const app = new Vue({
    el: '#app',
    router
});



