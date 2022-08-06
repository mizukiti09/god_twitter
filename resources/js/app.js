/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./device_sp');
require('./fade');
// require('./wave');

import Vue from 'vue';
import axios from 'axios';
import vueCookies from 'vue-cookies';
// import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
// import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
const VueCtkDateTimePicker = window['vue-ctk-date-time-picker'];

Vue.component('vue-ctk-date-time-picker', VueCtkDateTimePicker);

Vue.prototype.$axios = axios;
Vue.prototype.$vueCookies = vueCookies;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('twitter-accounts', require('./components/TwitterAccountsComponent.vue').default);
Vue.component('twitter-auto-follow-action', require('./components/TwitterAutoFollowActionComponent.vue').default);
Vue.component('twitter-auto-like-action', require('./components/TwitterAutoLikeActionComponent.vue').default);
Vue.component('twitter-auto-tweet-action', require('./components/TwitterAutoTweetActionComponent.vue').default);
Vue.component('twitter-auto-tweet-edit-action', require('./components/TwitterAutoTweetEditActionComponent.vue').default);
Vue.component('twitter-target-account', require('./components/TwitterTargetAccountComponent.vue').default);
Vue.component('twitter-target-account-edit', require('./components/TwitterTargetAccountEditComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});