require('./bootstrap');
window.Vue = require('vue').default;
import VueRouter from 'vue-router'
import vuetify from './vuetify';
import router from "./router";
import store from "./store/index";
window.Vue.component('app', require('./components/App.vue').default);


Vue.use(VueRouter);

const app = new Vue({
    el: '#app',
    vuetify,
    router,
    store,
});