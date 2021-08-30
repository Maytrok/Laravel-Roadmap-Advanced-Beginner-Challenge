require('./bootstrap');
window.Vue = require('vue').default;
import VueRouter from 'vue-router'
import vuetify from './vuetify';
import router from "./router";
import store from "./store/index";
import VueSweetalert2 from 'vue-sweetalert2';


window.Vue.component('app', require('./components/App.vue').default);

import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(VueRouter);
Vue.use(VueSweetalert2);

const app = new Vue({
    el: '#app',
    vuetify,
    router,
    store,
});