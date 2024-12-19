import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import Home from './views/user/Home.vue'
import Shop from './views/user/Shop.vue'
import Login from './views/user/Login.vue'
import Register from './views/user/Register.vue'

const routes = [
    {path: "/", component: Home},
    {path: "/shop", component: Shop},
    {path: "/login", component: Login},
    {path: "/register", component: Register},
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

createApp(App).use(router).mount('#app')
