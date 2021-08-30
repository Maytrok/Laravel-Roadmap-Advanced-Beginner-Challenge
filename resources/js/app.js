require('./bootstrap');
window.Vue = require('vue').default;
import vuetify from './vuetify';
window.Vue.component('app', require('./components/App.vue').default);

const app = new Vue({
    el: '#app',
    vuetify
});