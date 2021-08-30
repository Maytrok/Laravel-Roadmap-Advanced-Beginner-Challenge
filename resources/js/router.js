import VueRouter from "vue-router";

import Dashboard from "./components/Dashboard.vue";
import Users from "./components/Users.vue";
import Clients from "./components/Clients.vue";
import Projects from "./components/Projects.vue";
import Tasks from "./components/Tasks.vue";
import Login from "./components/Login.vue";

const routes = [
    { path: '/', component: Dashboard, name: "dashboard" },
    { path: '/user', component: Users, name: "user" },
    { path: '/clients', component: Clients, name: "clients" },
    { path: '/projects', component: Projects, name: "projects" },
    { path: '/tasks', component: Tasks, name: "tasks" },
    { path: '/login', component: Login, name: "login" },
];

export default new VueRouter({
    mode: 'history',
    routes
});